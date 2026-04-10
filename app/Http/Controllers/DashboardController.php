<?php

namespace App\Http\Controllers;

use App\Models\Act\Activity;
use App\Models\User\User;

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
            'revisionCount',
            'acceptedCount',
            'chartData'
        ));
    }
}
