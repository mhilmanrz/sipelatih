<?php

namespace App\Http\Controllers;

use App\Models\Act\EvaluationCriteria;
use App\Models\Act\ParticipantEvaluation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ParticipantEvaluationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request): View
    {
        $user = auth()->user();

        $evaluations = ParticipantEvaluation::whereHas('participant', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })
            ->with([
                'participant.activity.activityName',
                'speaker.user',
            ])
            ->orderBy('evaluation_type')
            ->orderBy('created_at', 'desc')
            ->paginate($request->input('per_page', 10));

        return view('participant-evaluations.index', compact('evaluations'));
    }

    public function show($participantEvaluationId): View
    {
        $user = auth()->user();
        $evaluation = ParticipantEvaluation::whereHas('participant', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })
            ->with([
                'participant.activity.activityName',
                'speaker.user',
                'answers.criteria',
                'files',
            ])
            ->findOrFail($participantEvaluationId);

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

        return view('participant-evaluations.show', compact('evaluation', 'criteria'));
    }

    public function store(Request $request, $participantEvaluationId): RedirectResponse
    {
        $user = auth()->user();
        $evaluation = ParticipantEvaluation::whereHas('participant', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->findOrFail($participantEvaluationId);

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

            $evaluation->update([
                'supervisor_recommendation' => $request->input('supervisor_recommendation'),
                'submitted_at' => now(),
            ]);
        });

        return redirect()->route('my-evaluations.index')
            ->with('success', 'Evaluasi berhasil disimpan.');
    }
}
