<?php

namespace App\Http\Controllers\Act;

use App\Http\Controllers\Controller;
use App\Models\Act\Activity;
use App\Models\Act\ActivityEvaluation;
use App\Models\Act\ActivityEvaluationCriteria;
use App\Models\Act\ParticipantEvaluation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ActivityEvaluationController extends Controller
{
    /**
     * Display a listing of activities based on evaluation steps.
     */
    public function index(Request $request): View
    {
        // 1. Get available years from start_date/end_date in activities
        $driver = DB::connection()->getDriverName();
        $yearExpr1 = $driver === 'sqlite' ? "strftime('%Y', start_date) as year" : 'YEAR(start_date) as year';
        $yearExpr2 = $driver === 'sqlite' ? "strftime('%Y', end_date) as year" : 'YEAR(end_date) as year';

        $availableYears = Activity::selectRaw($yearExpr1)
            ->whereNotNull('start_date')
            ->union(Activity::selectRaw($yearExpr2)->whereNotNull('end_date'))
            ->pluck('year')
            ->unique()
            ->sort()
            ->reverse();

        $selectedYear = (int) $request->input('year', $availableYears->first() ?? date('Y'));
        $activeTab = (int) $request->input('tab', 1);
        if (! in_array($activeTab, [1, 2, 3])) {
            $activeTab = 1;
        }

        // 2. Base query for accepted activities
        $query = Activity::query()
            ->whereHas('latestStatus', function ($q) {
                $q->where('status', 'accepted');
            });

        // Filter by Year
        $query->where(function ($q) use ($selectedYear) {
            $q->whereYear('start_date', $selectedYear)
                ->orWhereYear('end_date', $selectedYear);
        });

        // Filter by Search Query
        if ($request->has('q') && $request->q != '') {
            $query->whereHas('activityName', function ($q) use ($request) {
                $q->where('name', 'like', '%'.$request->q.'%');
            });
        }

        // 3. Filter by evaluation levels
        if ($activeTab === 2) {
            // Must pass level 1
            $query->whereHas('evaluations', function ($q) {
                $q->where('evaluation_type', 1)->where('is_passed', true);
            });
        } elseif ($activeTab === 3) {
            // Must pass level 2
            $query->whereHas('evaluations', function ($q) {
                $q->where('evaluation_type', 2)->where('is_passed', true);
            });
        }

        // Eager load necessary relationships
        $activities = $query->with([
            'activityName',
            'latestStatus',
            'evaluations' => function ($q) use ($activeTab) {
                $q->where('evaluation_type', $activeTab);
            },
        ])
            ->orderBy('start_date', 'desc')
            ->paginate($request->input('entries', 10))
            ->appends($request->all());

        return view('evaluations.index', compact('activities', 'activeTab', 'availableYears', 'selectedYear'));
    }

    /**
     * Show the detailed activity evaluation page.
     */
    public function show($id): View
    {
        $activity = Activity::with([
            'activityName',
            'activityType',
            'activityFormat',
            'activityMethod',
            'activityCategory',
            'workUnit',
            'fundSource',
            'budget',
            'activityParticipants.user',
            'activityParticipants.score',
            'activityMaterials.speakers',
            'evaluations.criteriaValues.criteria',
        ])->findOrFail($id);

        $evaluations = $activity->evaluations->keyBy('evaluation_type');

        $activeTab = (int) request()->query('tab', 1);
        if (! in_array($activeTab, [1, 2, 3])) {
            $activeTab = 1;
        }

        // Load data for active tab only
        $tabContent = null;

        if ($activeTab === 1) {
            $participantEvaluationsLevel1 = ParticipantEvaluation::whereIn(
                'activity_participant_id',
                $activity->activityParticipants->pluck('id')
            )
                ->where('evaluation_type', 1)
                ->with(['participant.user', 'speaker.user'])
                ->orderBy('activity_participant_id')
                ->get()
                ->groupBy('activity_participant_id');

            $level1Stats = $this->calculateLevel1Stats($activity, $participantEvaluationsLevel1);
            $tabContent = view('evaluations.tabs.tab-1', compact('activity', 'level1Stats', 'participantEvaluationsLevel1'))->render();
        } elseif ($activeTab === 2) {
            $level2Stats = $this->calculateLevel2Stats($activity);
            $tabContent = view('evaluations.tabs.tab-2', compact('activity', 'level2Stats'))->render();
        } elseif ($activeTab === 3) {
            $participantEvaluationsLevel3 = ParticipantEvaluation::whereIn(
                'activity_participant_id',
                $activity->activityParticipants->pluck('id')
            )
                ->where('evaluation_type', 3)
                ->with(['participant.user'])
                ->get()
                ->groupBy('activity_participant_id');

            $level3Stats = $this->calculateLevel3Stats($participantEvaluationsLevel3);
            $tabContent = view('evaluations.tabs.tab-3', compact('activity', 'level3Stats', 'participantEvaluationsLevel3'))->render();
        }

        return view('evaluations.show', compact(
            'activity',
            'evaluations',
            'activeTab',
            'tabContent'
        ));
    }

    private function calculateLevel1Stats($activity, $participantEvaluations): array
    {
        $totalForms = $participantEvaluations->sum(fn ($evals) => $evals->count());
        $submittedForms = $participantEvaluations->sum(fn ($evals) => $evals->where('submitted_at', '!=', null)->count());
        $percentage = $totalForms > 0 ? round(($submittedForms / $totalForms) * 100) : 0;

        return [
            'totalForms' => $totalForms,
            'submittedForms' => $submittedForms,
            'pendingForms' => $totalForms - $submittedForms,
            'percentage' => $percentage,
        ];
    }

    private function calculateLevel2Stats($activity): array
    {
        $scores = $activity->activityParticipants->flatMap(fn ($p) => $p->score ? [$p->score] : []);
        $preScores = $scores->whereNotNull('pre_test_score')->pluck('pre_test_score');
        $postScores = $scores->whereNotNull('post_test_score')->pluck('post_test_score');

        $avgPre = $preScores->isNotEmpty() ? round($preScores->avg(), 2) : 0;
        $avgPost = $postScores->isNotEmpty() ? round($postScores->avg(), 2) : 0;
        $avgDelta = $postScores->isNotEmpty() && $preScores->isNotEmpty() ? round($avgPost - $avgPre, 2) : 0;

        return [
            'avgPre' => $avgPre,
            'avgPost' => $avgPost,
            'avgDelta' => $avgDelta,
        ];
    }

    private function calculateLevel3Stats($participantEvaluations): array
    {
        $totalForms = $participantEvaluations->sum(fn ($evals) => $evals->count());
        $submittedForms = $participantEvaluations->sum(fn ($evals) => $evals->where('submitted_at', '!=', null)->count());
        $percentage = $totalForms > 0 ? round(($submittedForms / $totalForms) * 100) : 0;

        return [
            'totalForms' => $totalForms,
            'submittedForms' => $submittedForms,
            'percentage' => $percentage,
        ];
    }

    public function loadTab(Request $request, $id): Response
    {
        $activity = Activity::with([
            'activityName',
            'activityParticipants.user',
            'activityParticipants.score',
            'activityMaterials.speakers',
            'evaluations',
        ])->findOrFail($id);

        $tab = (int) $request->query('tab', 1);
        if (! in_array($tab, [1, 2, 3])) {
            $tab = 1;
        }

        $html = '';

        if ($tab === 1) {
            // Load Level 1 data
            $participantEvaluationsLevel1 = ParticipantEvaluation::whereIn(
                'activity_participant_id',
                $activity->activityParticipants->pluck('id')
            )
                ->where('evaluation_type', 1)
                ->with(['participant.user', 'speaker.user'])
                ->orderBy('activity_participant_id')
                ->get()
                ->groupBy('activity_participant_id');

            $level1Stats = $this->calculateLevel1Stats($activity, $participantEvaluationsLevel1);
            $html = view('evaluations.tabs.tab-1', compact('activity', 'level1Stats', 'participantEvaluationsLevel1'))->render();
        } elseif ($tab === 2) {
            // Load Level 2 data
            $level2Stats = $this->calculateLevel2Stats($activity);
            $html = view('evaluations.tabs.tab-2', compact('activity', 'level2Stats'))->render();
        } elseif ($tab === 3) {
            // Load Level 3 data
            $participantEvaluationsLevel3 = ParticipantEvaluation::whereIn(
                'activity_participant_id',
                $activity->activityParticipants->pluck('id')
            )
                ->where('evaluation_type', 3)
                ->with(['participant.user'])
                ->get()
                ->groupBy('activity_participant_id');

            $level3Stats = $this->calculateLevel3Stats($participantEvaluationsLevel3);
            $html = view('evaluations.tabs.tab-3', compact('activity', 'level3Stats', 'participantEvaluationsLevel3'))->render();
        }

        return response($html, 200, ['Content-Type' => 'text/html; charset=utf-8']);
    }

    /**
     * Store or update evaluation for a level.
     */
    public function store(Request $request, $id): RedirectResponse
    {
        $activity = Activity::findOrFail($id);

        $request->validate([
            'evaluation_type' => ['required', 'integer', 'in:1,2,3'],
            'is_passed' => ['required', 'boolean'],
            'notes' => ['nullable', 'string', 'max:1000'],
            'criteria' => ['required', 'array'],
            'criteria.*.is_passed' => ['required', 'boolean'],
            'criteria.*.value' => ['nullable', 'string', 'max:500'],
        ]);

        $evaluationType = (int) $request->evaluation_type;

        // Double check prerequisites
        if ($evaluationType === 2) {
            $level1Passed = ActivityEvaluation::where('activity_id', $activity->id)
                ->where('evaluation_type', 1)
                ->where('is_passed', true)
                ->exists();
            if (! $level1Passed) {
                return redirect()->back()->withErrors(['error' => 'Evaluasi 1 harus lulus terlebih dahulu sebelum melakukan Evaluasi 2.']);
            }
        } elseif ($evaluationType === 3) {
            $level2Passed = ActivityEvaluation::where('activity_id', $activity->id)
                ->where('evaluation_type', 2)
                ->where('is_passed', true)
                ->exists();
            if (! $level2Passed) {
                return redirect()->back()->withErrors(['error' => 'Evaluasi 2 harus lulus terlebih dahulu sebelum melakukan Evaluasi 3.']);
            }
        }

        DB::transaction(function () use ($activity, $request, $evaluationType) {
            // 1. Create or update parent evaluation record
            $evaluation = ActivityEvaluation::updateOrCreate(
                [
                    'activity_id' => $activity->id,
                    'evaluation_type' => $evaluationType,
                ],
                [
                    'is_passed' => $request->boolean('is_passed'),
                    'notes' => $request->notes,
                    'evaluated_by' => auth()->id(),
                    'evaluated_at' => now(),
                ]
            );

            // 2. Create or update each criteria value
            foreach ($request->input('criteria') as $criteriaId => $cData) {
                ActivityEvaluationCriteria::updateOrCreate(
                    [
                        'activity_evaluation_id' => $evaluation->id,
                        'evaluation_criteria_id' => $criteriaId,
                    ],
                    [
                        'value' => $cData['value'] ?? null,
                        'is_passed' => filter_var($cData['is_passed'], FILTER_VALIDATE_BOOLEAN),
                    ]
                );
            }
        });

        return redirect()->route('evaluations.show', $activity->id)
            ->with('success', "Evaluasi Tingkat {$evaluationType} berhasil disimpan.");
    }

    /**
     * Generate participant evaluation forms for an activity.
     */
    public function generateForms(Request $request, $id): RedirectResponse
    {
        $activity = Activity::with('activityParticipants', 'activityMaterials.speakers')->findOrFail($id);

        $evaluationType = (int) $request->input('evaluation_type', 1);

        if (! in_array($evaluationType, [1, 3])) {
            return redirect()->back()->withErrors(['error' => 'Invalid evaluation type.']);
        }

        DB::transaction(function () use ($activity, $evaluationType) {
            if ($evaluationType === 1) {
                // Generate Level 1 forms: per participant × per speaker, and per participant × kegiatan
                foreach ($activity->activityParticipants as $participant) {
                    // Speaker evaluation
                    $speakers = $activity->activityMaterials->flatMap(fn ($m) => $m->speakers)->unique('id');
                    foreach ($speakers as $speaker) {
                        ParticipantEvaluation::firstOrCreate(
                            [
                                'activity_participant_id' => $participant->id,
                                'evaluation_type' => 1,
                                'form_type' => 'speaker',
                                'activity_speaker_id' => $speaker->id,
                            ],
                            ['token' => null]
                        );
                    }

                    // Activity evaluation (1 per participant)
                    ParticipantEvaluation::firstOrCreate(
                        [
                            'activity_participant_id' => $participant->id,
                            'evaluation_type' => 1,
                            'form_type' => 'activity',
                        ],
                        ['token' => null]
                    );
                }
            } elseif ($evaluationType === 3) {
                // Generate Level 3 forms: 1 per participant
                foreach ($activity->activityParticipants as $participant) {
                    ParticipantEvaluation::firstOrCreate(
                        [
                            'activity_participant_id' => $participant->id,
                            'evaluation_type' => 3,
                        ],
                        ['token' => null]
                    );
                }
            }
        });

        $levelLabel = $evaluationType === 1 ? 'Level 1' : 'Level 3';

        return redirect()->route('evaluations.show', $activity->id)
            ->with('success', "Form {$levelLabel} berhasil dibuat untuk semua peserta.");
    }

    /**
     * Toggle Level 3 availability for an activity.
     */
    public function toggleLevel3(Request $request, $id): RedirectResponse
    {
        $activity = Activity::findOrFail($id);

        if ($request->input('action') === 'enable') {
            $this->generateForms(new Request(['evaluation_type' => 3]), $id);
        } elseif ($request->input('action') === 'disable') {
            ParticipantEvaluation::where('activity_id', $activity->id)
                ->where('evaluation_type', 3)
                ->delete();

            return redirect()->route('evaluations.show', $activity->id)
                ->with('success', 'Level 3 evaluasi dihapus.');
        }

        return redirect()->back();
    }
}
