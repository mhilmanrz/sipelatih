<?php

namespace App\Http\Controllers;

use App\Models\Act\Activity;
use App\Models\User\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalActivities = Activity::count();
        $totalUsers = User::count();

        // 1. Draft (latest status draft or no status at all)
        $draftCount = Activity::where(function ($query) {
            $query->whereDoesntHave('latestStatus')
                ->orWhereHas('latestStatus', function ($q) {
                    $q->where('status', 'draft');
                });
        })->count();

        // 2. Tahap Pengajuan (latest status submitted, and never revised)
        $submittedCount = Activity::whereHas('latestStatus', function ($query) {
            $query->where('status', 'submitted');
        })->whereDoesntHave('statuses', function ($query) {
            $query->where('status', 'revision');
        })->count();

        // 3. Telah Perbaikan (latest status submitted, and has been revised in history)
        $telahPerbaikanCount = Activity::whereHas('latestStatus', function ($query) {
            $query->where('status', 'submitted');
        })->whereHas('statuses', function ($query) {
            $query->where('status', 'revision');
        })->count();

        // 4. Proses Penilaian (accepted activities where grading is configured / in progress)
        $prosesPenilaianCount = Activity::whereHas('latestStatus', function ($query) {
            $query->where('status', 'accepted');
        })->whereHas('scoreSetting')->count();

        // 5. Butuh Perbaikan (latest status revision)
        $revisionCount = Activity::whereHas('latestStatus', function ($query) {
            $query->where('status', 'revision');
        })->count();

        // 6. Disetujui (latest status accepted)
        $acceptedCount = Activity::whereHas('latestStatus', function ($query) {
            $query->where('status', 'accepted');
        })->count();

        // 7. Ditolak (always 0, as not supported in the database enum)
        $rejectedCount = 0;

        $chartData = Activity::with('workUnit')
            ->get()
            ->groupBy(function ($item) {
                return $item->workUnit ? $item->workUnit->name : 'Unknown';
            })
            ->map->count()
            ->toArray();

        return view('dashboard', compact(
            'totalActivities',
            'totalUsers',
            'draftCount',
            'submittedCount',
            'telahPerbaikanCount',
            'prosesPenilaianCount',
            'revisionCount',
            'acceptedCount',
            'rejectedCount',
            'chartData'
        ));
    }

    public function getActivitiesByDate(Request $request)
    {
        $date = $request->query('date', now()->format('Y-m-d'));

        $activities = Activity::with('activityName')
            ->whereDate('start_date', '<=', $date)
            ->whereDate('end_date', '>=', $date)
            ->orderBy('start_date', 'asc')
            ->get();

        return response()->json($activities);
    }
}
