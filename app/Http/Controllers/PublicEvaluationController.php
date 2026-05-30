<?php

namespace App\Http\Controllers;

use App\Models\Act\EvaluationCriteria;
use App\Models\Act\ParticipantEvaluation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PublicEvaluationController extends Controller
{
    public function show($token): View
    {
        $evaluation = ParticipantEvaluation::where('token', $token)
            ->with([
                'participant.activity.activityName',
                'participant.user',
                'speaker.user',
                'answers.criteria',
                'files',
            ])
            ->firstOrFail();

        $criteria = EvaluationCriteria::where('evaluation_type', $evaluation->evaluation_type)
            ->where(function ($query) use ($evaluation) {
                if ($evaluation->form_type) {
                    $query->where('form_type', $evaluation->form_type);
                } else {
                    $query->whereNull('form_type');
                }
            })
            ->orderBy('order')
            ->get()
            ->groupBy('evaluation_category_id');

        return view('public.evaluation-form', compact('evaluation', 'criteria'));
    }

    public function store(Request $request, $token): RedirectResponse
    {
        $evaluation = ParticipantEvaluation::where('token', $token)->firstOrFail();

        if ($evaluation->isSubmitted()) {
            return redirect()->back()->withErrors(['error' => 'Evaluasi sudah disubmit sebelumnya.']);
        }

        $request->validate([
            'answers' => ['required', 'array'],
            'answers.*' => ['required', 'integer', 'between:1,4'],
            'supervisor_recommendation' => ['nullable', 'string', 'max:1000'],
        ]);

        $ipAddress = $request->ip();

        DB::transaction(function () use ($evaluation, $request, $ipAddress) {
            foreach ($request->input('answers', []) as $criteriaId => $rating) {
                $evaluation->answers()->updateOrCreate(
                    ['evaluation_criteria_id' => $criteriaId],
                    ['rating' => $rating]
                );
            }

            $evaluation->update([
                'supervisor_recommendation' => $request->input('supervisor_recommendation'),
                'submitted_at' => now(),
                'ip_address' => $ipAddress,
            ]);
        });

        return view('public.evaluation-success', compact('evaluation'));
    }
}
