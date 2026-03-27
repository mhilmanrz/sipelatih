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

        $draftCount = Activity::whereHas('latestStatus', function ($query) {
            $query->where('status', 'draft');
        })->count();

        $submittedCount = Activity::whereHas('latestStatus', function ($query) {
            $query->where('status', 'submitted');
        })->count();

        $revisionCount = Activity::whereHas('latestStatus', function ($query) {
            $query->where('status', 'revision');
        })->count();

        $acceptedCount = Activity::whereHas('latestStatus', function ($query) {
            $query->where('status', 'accepted');
        })->count();

        $processingCount = Activity::whereHas('latestStatus', function ($query) {
            $query->where('status', 'processing');
        })->count();

        $rejectedCount = Activity::whereHas('latestStatus', function ($query) {
            $query->where('status', 'rejected');
        })->count();

        $chartData = Activity::with('workUnit')
            ->get()
            ->groupBy(function ($item) {
                return $item->workUnit ? $item->workUnit->name : 'Unknown';
            })
            ->map->count()
            ->toArray();

        // Calendar events: all activities with a start_date
        $calendarEvents = Activity::with('activityName')
            ->whereNotNull('start_date')
            ->get()
            ->map(function ($item) {
                return [
                    'title' => $item->activityName->name ?? 'Kegiatan',
                    'start' => $item->start_date,
                    'end'   => $item->end_date,
                ];
            });

        return \Inertia\Inertia::render('Dashboard', [
            'totalActivities' => $totalActivities,
            'totalUsers' => $totalUsers,
            'draftCount' => $draftCount,
            'submittedCount' => $submittedCount,
            'revisionCount' => $revisionCount,
            'acceptedCount' => $acceptedCount,
            'processingCount' => $processingCount,
            'rejectedCount' => $rejectedCount,
            'chartLabels' => array_keys($chartData),
            'chartData' => array_values($chartData),
            'calendarEvents' => $calendarEvents,
        ]);
    }
}
