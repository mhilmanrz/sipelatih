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
        return view('dashboard', compact('totalActivities', 'totalUsers'));
    }
}
