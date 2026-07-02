<?php

namespace App\Http\Controllers\Act;

use App\Http\Controllers\Controller;
use App\Models\Act\Activity;
use App\Models\Act\ActivityEvaluation;
use App\Models\Act\ActivityParticipant;
use App\Models\Act\EvaluationCategory;
use App\Models\Act\ParticipantEvaluation;
use App\Models\Act\ParticipantEvaluationAnswer;
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

        // Base accepted activities for the selected year (approved past perencanaan, now with penyelenggara/evaluasi)
        $acceptedActivityIds = Activity::whereHas('latestStatus', function ($q) {
            $q->whereIn('stage', ['penyelenggara', 'evaluasi']);
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
}
