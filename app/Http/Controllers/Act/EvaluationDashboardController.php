<?php

namespace App\Http\Controllers\Act;

use App\Http\Controllers\Controller;
use App\Models\Act\Activity;
use App\Models\Act\ActivityEvaluation;
use App\Models\Act\ActivityParticipant;
use App\Models\Act\ActivityScope;
use App\Models\Act\EvaluationCategory;
use App\Models\Act\ParticipantEvaluation;
use App\Models\Act\ParticipantEvaluationAnswer;
use App\Models\User\User;
use App\Models\User\WorkUnit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class EvaluationDashboardController extends Controller
{
    public function index(Request $request): View
    {
        $driver = DB::connection()->getDriverName();
        $yearExpr = $driver === 'sqlite' ? "strftime('%Y', start_date) as year" : 'YEAR(start_date) as year';

        $availableYears = Activity::selectRaw($yearExpr)
            ->whereNotNull('start_date')
            ->pluck('year')
            ->unique()
            ->sort()
            ->reverse()
            ->values();

        $selectedYear = (int) $request->input('year', $availableYears->first() ?? date('Y'));

        // Base accepted activities for the selected year
        $acceptedActivityIds = Activity::whereHas('latestStatus', function ($q) {
            $q->where('status', 'accepted');
        })
            ->where(function ($q) use ($selectedYear) {
                $q->whereYear('start_date', $selectedYear)
                    ->orWhereYear('end_date', $selectedYear);
            })
            ->pluck('id');

        // ── OVERVIEW STATS ──────────────────────────────────────────────
        $totalKegiatan = $acceptedActivityIds->count();

        $totalPeserta = ActivityParticipant::whereIn('activity_id', $acceptedActivityIds)->count();

        $totalUnitKerja = Activity::whereIn('id', $acceptedActivityIds)
            ->whereNotNull('work_unit_id')
            ->distinct('work_unit_id')
            ->count('work_unit_id');

        // Evaluation level distribution (based on activity_evaluations is_passed)
        $level1ActivityIds = ActivityEvaluation::whereIn('activity_id', $acceptedActivityIds)
            ->where('evaluation_type', 1)->where('is_passed', true)->pluck('activity_id');
        $level2ActivityIds = ActivityEvaluation::whereIn('activity_id', $acceptedActivityIds)
            ->where('evaluation_type', 2)->where('is_passed', true)->pluck('activity_id');
        $level3ActivityIds = ActivityEvaluation::whereIn('activity_id', $acceptedActivityIds)
            ->where('evaluation_type', 3)->where('is_passed', true)->pluck('activity_id');

        $evaluationDistribution = [
            'level1' => $level1ActivityIds->count(),
            'level2' => $level2ActivityIds->count(),
            'level3' => $level3ActivityIds->count(),
        ];

        // Peserta per bulan
        $monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        $monthExpr = $driver === 'sqlite' ? "strftime('%m', start_date)" : 'MONTH(start_date)';

        $pesertaPerBulan = Activity::whereIn('id', $acceptedActivityIds)
            ->selectRaw("{$monthExpr} as bulan, SUM((SELECT COUNT(*) FROM activity_participants WHERE activity_participants.activity_id = activities.id)) as total")
            ->groupByRaw($monthExpr)
            ->orderByRaw($monthExpr)
            ->get()
            ->mapWithKeys(fn ($row) => [(int) $row->bulan => (int) $row->total]);

        $pesertaPerBulanChart = collect(range(1, 12))->mapWithKeys(fn ($m) => [
            $monthNames[$m - 1] => $pesertaPerBulan->get($m, 0),
        ]);

        // ── LEVEL 1 ─────────────────────────────────────────────────────
        $level1ParticipantIds = ActivityParticipant::whereIn('activity_id', $acceptedActivityIds)->pluck('id');

        $level1Kegiatan = $acceptedActivityIds->count();
        $level1Peserta = $level1ParticipantIds->count();
        $level1UnitKerja = Activity::whereIn('id', $acceptedActivityIds)
            ->whereNotNull('work_unit_id')->distinct('work_unit_id')->count('work_unit_id');

        // Response rate Level 1
        $level1Total = ParticipantEvaluation::whereIn('activity_participant_id', $level1ParticipantIds)
            ->where('evaluation_type', 1)->count();
        $level1Submitted = ParticipantEvaluation::whereIn('activity_participant_id', $level1ParticipantIds)
            ->where('evaluation_type', 1)->whereNotNull('submitted_at')->count();

        // Average rating per category (Level 1)
        $level1Categories = EvaluationCategory::where('evaluation_type', 1)
            ->with(['criteria' => fn ($q) => $q->where('type', 'rating')])
            ->orderBy('order')
            ->get();

        $level1SubmittedEvalIds = ParticipantEvaluation::whereIn('activity_participant_id', $level1ParticipantIds)
            ->where('evaluation_type', 1)->whereNotNull('submitted_at')->pluck('id');

        $level1CategoryRatings = $level1Categories->map(function ($cat) use ($level1SubmittedEvalIds) {
            $criteriaIds = $cat->criteria->pluck('id');

            if ($criteriaIds->isEmpty() || $level1SubmittedEvalIds->isEmpty()) {
                return ['name' => $cat->name, 'avg' => 0, 'form_type' => $cat->form_type];
            }

            $avg = ParticipantEvaluationAnswer::whereIn('participant_evaluation_id', $level1SubmittedEvalIds)
                ->whereIn('evaluation_criteria_id', $criteriaIds)
                ->whereNotNull('rating')
                ->avg('rating');

            return [
                'name' => $cat->name,
                'avg' => round($avg ?? 0, 2),
                'form_type' => $cat->form_type,
            ];
        });

        // ── LEVEL 2 ─────────────────────────────────────────────────────
        // Level 2 = activities that passed Level 1
        $level2EligibleActivityIds = $level1ActivityIds;
        $level2ParticipantIds = ActivityParticipant::whereIn('activity_id', $level2EligibleActivityIds)->pluck('id');

        $level2Kegiatan = $level2EligibleActivityIds->count();
        $level2Peserta = $level2ParticipantIds->count();
        $level2UnitKerja = Activity::whereIn('id', $level2EligibleActivityIds)
            ->whereNotNull('work_unit_id')->distinct('work_unit_id')->count('work_unit_id');

        // Pass/Fail from activity_participants (is_passed)
        $level2Passed = ActivityParticipant::whereIn('activity_id', $level2EligibleActivityIds)
            ->where('is_passed', true)->count();
        $level2Failed = ActivityParticipant::whereIn('activity_id', $level2EligibleActivityIds)
            ->where('is_passed', false)->count();
        $level2Pending = $level2Peserta - $level2Passed - $level2Failed;

        $level2PassDistribution = [
            'lulus' => $level2Passed,
            'tidak_lulus' => $level2Failed,
            'belum' => $level2Pending,
        ];

        // ── LEVEL 3 ─────────────────────────────────────────────────────
        $level3EligibleActivityIds = $level2ActivityIds;
        $level3ParticipantIds = ActivityParticipant::whereIn('activity_id', $level3EligibleActivityIds)->pluck('id');

        $level3Kegiatan = $level3EligibleActivityIds->count();
        $level3Peserta = $level3ParticipantIds->count();
        $level3UnitKerja = Activity::whereIn('id', $level3EligibleActivityIds)
            ->whereNotNull('work_unit_id')->distinct('work_unit_id')->count('work_unit_id');

        // Response rate Level 3
        $level3Total = ParticipantEvaluation::whereIn('activity_participant_id', $level3ParticipantIds)
            ->where('evaluation_type', 3)->count();
        $level3Submitted = ParticipantEvaluation::whereIn('activity_participant_id', $level3ParticipantIds)
            ->where('evaluation_type', 3)->whereNotNull('submitted_at')->count();

        $level3SubmittedEvalIds = ParticipantEvaluation::whereIn('activity_participant_id', $level3ParticipantIds)
            ->where('evaluation_type', 3)->whereNotNull('submitted_at')->pluck('id');

        // Average rating per Level 3 category
        $level3Categories = EvaluationCategory::where('evaluation_type', 3)
            ->with(['criteria' => fn ($q) => $q->where('type', 'rating')])
            ->orderBy('order')
            ->get();

        $level3CategoryRatings = $level3Categories->map(function ($cat) use ($level3SubmittedEvalIds) {
            $criteriaIds = $cat->criteria->pluck('id');

            if ($criteriaIds->isEmpty() || $level3SubmittedEvalIds->isEmpty()) {
                return ['name' => $cat->name, 'avg' => 0];
            }

            $avg = ParticipantEvaluationAnswer::whereIn('participant_evaluation_id', $level3SubmittedEvalIds)
                ->whereIn('evaluation_criteria_id', $criteriaIds)
                ->whereNotNull('rating')
                ->avg('rating');

            return [
                'name' => $cat->name,
                'avg' => round($avg ?? 0, 2),
            ];
        });

        return view('evaluations.dashboard', compact(
            'selectedYear',
            'availableYears',
            // Overview
            'totalKegiatan',
            'totalPeserta',
            'totalUnitKerja',
            'evaluationDistribution',
            'pesertaPerBulanChart',
            // Level 1
            'level1Kegiatan',
            'level1Peserta',
            'level1UnitKerja',
            'level1Total',
            'level1Submitted',
            'level1CategoryRatings',
            // Level 2
            'level2Kegiatan',
            'level2Peserta',
            'level2UnitKerja',
            'level2PassDistribution',
            // Level 3
            'level3Kegiatan',
            'level3Peserta',
            'level3UnitKerja',
            'level3Total',
            'level3Submitted',
            'level3CategoryRatings',
        ));
    }

    public function participantDashboard(Request $request): View
    {
        $monthsList = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];

        $scopesList = ActivityScope::orderBy('name')->get();
        $workUnitsList = WorkUnit::orderBy('name')->get();

        // Get list of accepted activities for filter dropdown
        $activitiesList = Activity::whereHas('latestStatus', function ($q) {
            $q->where('status', 'accepted');
        })->with('activityName')->get()->sortBy(function ($activity) {
            return $activity->activityName->name ?? '';
        });

        // Current filter values
        $selectedMonth = $request->input('month');
        $selectedScope = $request->input('activity_scope_id');
        $selectedWorkUnit = $request->input('work_unit_id');
        $selectedActivity = $request->input('activity_id');
        $searchEmployee = $request->input('employee_name');

        // Query participant list
        $query = ActivityParticipant::query()
            ->whereHas('activity.latestStatus', function ($q) {
                $q->where('status', 'accepted');
            });

        // Apply filters
        if ($request->filled('month')) {
            $query->whereHas('activity', function ($q) use ($request) {
                $q->whereMonth('start_date', $request->month)
                    ->orWhereMonth('end_date', $request->month);
            });
        }

        if ($request->filled('activity_scope_id')) {
            $query->whereHas('activity', function ($q) use ($request) {
                $q->where('activity_scope_id', $request->activity_scope_id);
            });
        }

        if ($request->filled('work_unit_id')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('work_unit_id', $request->work_unit_id);
            });
        }

        if ($request->filled('activity_id')) {
            $query->where('activity_id', $request->activity_id);
        }

        if ($request->filled('employee_name')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%'.$request->employee_name.'%');
            });
        }

        // Calculate statistics cards
        $totalKegiatan = (clone $query)->distinct('activity_id')->count('activity_id');
        $totalPeserta = (clone $query)->count();

        // Unique work units of filtered participants
        $totalUnitKerja = User::whereIn('id', (clone $query)->select('user_id'))
            ->whereNotNull('work_unit_id')
            ->distinct('work_unit_id')
            ->count('work_unit_id');

        // Response rate of evaluation forms
        $totalForms = ParticipantEvaluation::whereIn('activity_participant_id', (clone $query)->select('id'))->count();
        $submittedForms = ParticipantEvaluation::whereIn('activity_participant_id', (clone $query)->select('id'))
            ->whereNotNull('submitted_at')
            ->count();
        $responseRate = $totalForms > 0 ? round(($submittedForms / $totalForms) * 100, 2) : 0;

        // Paginate participants with relationships
        $participants = $query->with([
            'user.workUnit',
            'activity.activityName',
            'activity.activityScope',
            'activity.activityMaterials.speakers.user',
            'participantEvaluations.answers.criteria',
            'participantEvaluations.files',
        ])->paginate($request->input('per_page', 10))->appends($request->all());

        // Process data for presentation
        $mappedParticipants = $participants->map(function ($ap) {
            // General Info
            $name = $ap->user->name ?? '-';
            $workUnit = $ap->user->workUnit->name ?? '-';
            $activityTitle = $ap->activity->activityName->name ?? '-';

            // Level 1: check standard activity form and speaker forms
            $activityEval1 = $ap->participantEvaluations
                ->where('evaluation_type', 1)
                ->where('form_type', 'activity')
                ->first();
            $activitySubmitted = $activityEval1 ? $activityEval1->isSubmitted() : false;

            $speakerEvals = $ap->participantEvaluations
                ->where('evaluation_type', 1)
                ->where('form_type', 'speaker');
            $activitySpeakerIds = $ap->activity->activityMaterials
                ->flatMap(fn ($m) => $m->speakers)
                ->pluck('id')
                ->unique();
            $totalSpeakers = $activitySpeakerIds->count();

            if ($totalSpeakers > 0) {
                $submittedSpeakersCount = $speakerEvals
                    ->whereIn('activity_speaker_id', $activitySpeakerIds)
                    ->whereNotNull('submitted_at')
                    ->count();
                $speakerStatus = ($submittedSpeakersCount === $totalSpeakers)
                    ? 'Sudah'
                    : 'Belum ('.$submittedSpeakersCount.'/'.$totalSpeakers.')';
            } else {
                $speakerStatus = 'Tidak Ada Narasumber';
            }

            $level1Data = [
                'cat1' => $activitySubmitted ? 'Sudah' : 'Belum', // Pelayanan Administrasi
                'cat2' => $activitySubmitted ? 'Sudah' : 'Belum', // Sarana dan Fasilitas
                'cat3' => $activitySubmitted ? 'Sudah' : 'Belum', // Metode dan Proses Pembelajaran
                'cat4' => $activitySubmitted ? 'Sudah' : 'Belum', // Kepuasan dan Keberlanjutan Program
                'narasumber' => $speakerStatus,
            ];

            // Level 2: result (is_passed)
            if ($ap->is_passed === true) {
                $level2Status = 'Lulus';
            } elseif ($ap->is_passed === false) {
                $level2Status = 'Tidak Lulus';
            } else {
                $level2Status = 'Belum Dinilai';
            }
            $finalScore = $ap->calculateFinalScore();

            // Level 3: status per category and ketercapaian
            $level3Eval = $ap->participantEvaluations
                ->where('evaluation_type', 3)
                ->first();
            $level3Submitted = $level3Eval ? $level3Eval->isSubmitted() : false;

            $cat5Status = 'Belum';
            $cat6Score = '-';
            $cat7Score = '-';
            $cat8Score = '-';
            $cat9Score = '-';
            $cat10Status = 'Belum';
            $ketercapaian = '-';

            if ($level3Submitted && $level3Eval) {
                $answersGrouped = $level3Eval->answers->groupBy(function ($ans) {
                    return $ans->criteria->evaluation_category_id ?? null;
                });

                $cat5Status = $answersGrouped->has(5) ? 'Sudah' : 'Belum';

                $cat6Avg = $answersGrouped->has(6) ? $answersGrouped->get(6)->whereNotNull('rating')->avg('rating') : null;
                $cat6Score = $cat6Avg !== null ? round($cat6Avg, 2) : '-';

                $cat7Avg = $answersGrouped->has(7) ? $answersGrouped->get(7)->whereNotNull('rating')->avg('rating') : null;
                $cat7Score = $cat7Avg !== null ? round($cat7Avg, 2) : '-';

                $cat8Avg = $answersGrouped->has(8) ? $answersGrouped->get(8)->whereNotNull('rating')->avg('rating') : null;
                $cat8Score = $cat8Avg !== null ? round($cat8Avg, 2) : '-';

                $cat9Avg = $answersGrouped->has(9) ? $answersGrouped->get(9)->whereNotNull('rating')->avg('rating') : null;
                $cat9Score = $cat9Avg !== null ? round($cat9Avg, 2) : '-';

                $hasFiles = $level3Eval->files->isNotEmpty();
                $hasRecom = $answersGrouped->has(10);
                $cat10Status = ($hasFiles || $hasRecom) ? 'Sudah' : 'Belum';

                $ratingAns = $level3Eval->answers->filter(function ($ans) {
                    return in_array($ans->criteria->evaluation_category_id ?? null, [6, 7, 8, 9]) && $ans->rating !== null;
                });
                $ketercapaian = $ratingAns->isNotEmpty() ? round($ratingAns->avg('rating'), 2) : '-';
            }

            return (object) [
                'id' => $ap->id,
                'name' => $name,
                'workUnit' => $workUnit,
                'activityTitle' => $activityTitle,
                'level1' => (object) $level1Data,
                'level2' => (object) [
                    'status' => $level2Status,
                    'score' => $finalScore,
                ],
                'level3' => (object) [
                    'submitted' => $level3Submitted ? 'Sudah' : 'Belum',
                    'cat5' => $cat5Status,
                    'cat6' => $cat6Score,
                    'cat7' => $cat7Score,
                    'cat8' => $cat8Score,
                    'cat9' => $cat9Score,
                    'cat10' => $cat10Status,
                    'ketercapaian' => $ketercapaian,
                ],
            ];
        });

        return view('evaluations.dashboard_participant', compact(
            'monthsList',
            'scopesList',
            'workUnitsList',
            'activitiesList',
            'selectedMonth',
            'selectedScope',
            'selectedWorkUnit',
            'selectedActivity',
            'searchEmployee',
            'totalKegiatan',
            'totalPeserta',
            'totalUnitKerja',
            'responseRate',
            'participants',
            'mappedParticipants'
        ));
    }
}
