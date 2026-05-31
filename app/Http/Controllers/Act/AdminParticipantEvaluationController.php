<?php

namespace App\Http\Controllers\Act;

use App\Http\Controllers\Controller;
use App\Models\Act\EvaluationCriteria;
use App\Models\Act\ParticipantEvaluation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AdminParticipantEvaluationController extends Controller
{
    public function show($participantEvaluationId): View
    {
        $evaluation = ParticipantEvaluation::with([
            'participant.activity.activityName',
            'participant.user',
            'speaker.user',
            'answers.criteria',
            'files',
        ])->findOrFail($participantEvaluationId);

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

        return view('admin.participant-evaluations.show', compact('evaluation', 'criteria'));
    }

    public function store(Request $request, $participantEvaluationId): RedirectResponse
    {
        $evaluation = ParticipantEvaluation::findOrFail($participantEvaluationId);

        $request->validate([
            'answers' => ['required', 'array'],
            'answers.*' => ['required', 'integer', 'between:1,4'],
            'supervisor_recommendation' => ['nullable', 'string', 'max:1000'],
        ]);

        DB::transaction(function () use ($evaluation, $request) {
            foreach ($request->input('answers', []) as $criteriaId => $rating) {
                $evaluation->answers()->updateOrCreate(
                    ['evaluation_criteria_id' => $criteriaId],
                    ['rating' => $rating]
                );
            }

            if ($request->filled('supervisor_recommendation')) {
                $evaluation->update([
                    'supervisor_recommendation' => $request->supervisor_recommendation,
                    'submitted_at' => now(),
                ]);
            }
        });

        return redirect()->route('evaluations.show', $evaluation->participant->activity_id)
            ->with('success', 'Evaluasi berhasil disimpan.');
    }

    public function destroy($participantEvaluationId): RedirectResponse
    {
        $evaluation = ParticipantEvaluation::findOrFail($participantEvaluationId);
        $activityId = $evaluation->participant->activity_id;

        DB::transaction(function () use ($evaluation) {
            $evaluation->answers()->delete();
            $evaluation->files()->delete();
            $evaluation->update([
                'submitted_at' => null,
                'supervisor_recommendation' => null,
            ]);
        });

        return redirect()->route('evaluations.show', $activityId)
            ->with('success', 'Evaluasi direset.');
    }
}
