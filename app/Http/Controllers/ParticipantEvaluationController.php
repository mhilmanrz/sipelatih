<?php

namespace App\Http\Controllers;

use App\Models\Act\Activity;
use App\Models\Act\EvaluationCriteria;
use App\Models\Act\ParticipantEvaluation;
use App\Models\Act\ParticipantEvaluationFile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ParticipantEvaluationController extends Controller
{
    public function index(Request $request): View
    {
        $user = auth()->user();
        $selectedActivityId = $request->input('activity_id');

        // Fetch activities for filter dropdown
        $filterActivities = Activity::whereHas('participants', function ($query) use ($user) {
            $query->where('user_id', $user->id)
                ->whereHas('participantEvaluations');
        })
            ->with('activityName')
            ->get()
            ->sortBy(fn ($activity) => $activity->activityName->name ?? '')
            ->values();

        $evaluationsQuery = ParticipantEvaluation::whereHas('participant', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })
            ->with([
                'participant.activity.activityName',
                'speaker.user',
            ]);

        if ($request->filled('activity_id')) {
            $evaluationsQuery->whereHas('participant', function ($query) use ($selectedActivityId) {
                $query->where('activity_id', $selectedActivityId);
            });
        }

        // Stats calculation
        $statsQuery = ParticipantEvaluation::whereHas('participant', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        });

        if ($request->filled('activity_id')) {
            $statsQuery->whereHas('participant', function ($query) use ($selectedActivityId) {
                $query->where('activity_id', $selectedActivityId);
            });
        }

        $totalCount = $statsQuery->count();
        $completedCount = (clone $statsQuery)->whereNotNull('submitted_at')->count();
        $pendingCount = $totalCount - $completedCount;
        $completionRate = $totalCount > 0 ? (int) round(($completedCount / $totalCount) * 100) : 0;

        $stats = [
            'total' => $totalCount,
            'completed' => $completedCount,
            'pending' => $pendingCount,
            'rate' => $completionRate,
        ];

        $evaluations = $evaluationsQuery
            ->orderBy('evaluation_type')
            ->orderBy('created_at', 'desc')
            ->paginate($request->input('per_page', 10))
            ->withQueryString();

        return view('participant-evaluations.index', compact('evaluations', 'filterActivities', 'selectedActivityId', 'stats'));
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

        $criteria = EvaluationCriteria::with('category')
            ->select('evaluation_criteria.*')
            ->leftJoin('evaluation_categories', 'evaluation_criteria.evaluation_category_id', '=', 'evaluation_categories.id')
            ->where('evaluation_criteria.evaluation_type', $evaluation->evaluation_type)
            ->where(function ($query) use ($evaluation) {
                if ($evaluation->form_type) {
                    $query->where('evaluation_criteria.form_type', $evaluation->form_type);
                } else {
                    $query->whereNull('evaluation_criteria.form_type');
                }
            })
            ->orderByRaw('COALESCE(evaluation_categories.order, 9999)')
            ->orderBy('evaluation_criteria.order')
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

        $allCriteriaIds = array_merge(
            array_keys($request->input('answers', [])),
            array_keys($request->allFiles()['files'] ?? [])
        );
        $criteria = EvaluationCriteria::whereIn('id', $allCriteriaIds)->get();

        $rules = [
            'answers' => ['nullable', 'array'],
            'files' => ['nullable', 'array'],
            'supervisor_recommendation' => ['nullable', 'string', 'max:1000'],
        ];

        foreach ($criteria as $crit) {
            if ($crit->type === 'rating') {
                $rules["answers.{$crit->id}"] = ['required', 'integer', 'between:1,4'];
            } elseif ($crit->type === 'file') {
                $rules["files.{$crit->id}"] = ['nullable', 'file', 'max:10240'];
            } else {
                $rules["answers.{$crit->id}"] = ['nullable', 'string', 'max:2000'];
            }
        }

        $request->validate($rules);

        DB::transaction(function () use ($evaluation, $request, $criteria) {
            foreach ($request->input('answers', []) as $criteriaId => $answer) {
                $crit = $criteria->firstWhere('id', $criteriaId);
                if (! $crit) {
                    continue;
                }

                $data = $crit->type === 'rating'
                    ? ['rating' => $answer, 'answer_text' => null]
                    : ['rating' => null, 'answer_text' => $answer];

                $evaluation->answers()->updateOrCreate(
                    ['evaluation_criteria_id' => $criteriaId],
                    $data
                );
            }

            foreach ($request->allFiles()['files'] ?? [] as $criteriaId => $file) {
                $crit = $criteria->firstWhere('id', $criteriaId);
                if (! $crit || $crit->type !== 'file') {
                    continue;
                }

                $path = $file->store('evaluation-files', 'local');

                ParticipantEvaluationFile::updateOrCreate(
                    [
                        'participant_evaluation_id' => $evaluation->id,
                        'evaluation_criteria_id' => $criteriaId,
                    ],
                    [
                        'file_name' => $file->getClientOriginalName(),
                        'file_path' => $path,
                        'file_size' => $file->getSize(),
                        'mime_type' => $file->getMimeType(),
                    ]
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
