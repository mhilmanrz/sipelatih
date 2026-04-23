<?php

namespace App\Http\Controllers;

use App\Models\Act\ActivityParticipant;
use App\Models\User\User;
use Illuminate\Http\Request;

class MonitoringJplController extends Controller
{
    public function index(Request $request)
    {
        $searchNip = $request->input('nip');
        $searchNama = $request->input('nama');
        $usersQuery = User::with(['workUnit', 'profession.category', 'activityParticipants' => function ($q) {
            $q->where('is_passed', true)->with('activity.activityMaterials');
        }])->when($searchNip, function ($q, $nip) {
            $q->where('employee_id', 'like', '%' . $nip . '%');
        })->when($searchNama, function ($q, $nama) {
            $q->where('name', 'like', '%' . $nama . '%');
        });

        // Get users and format them
        $users = $usersQuery->get()->map(function ($user) {
            // Filter out duplicate participants for the same activity
            $uniqueParticipants = $user->activityParticipants->unique('activity_id');
            $capaian = $uniqueParticipants->sum(function ($participant) {
                return $participant->activity ? $participant->activity->activityMaterials->sum('value') : 0;
            });

            $user->capaian_jpl = round($capaian / 45, 2);
            $user->target_jpl = $user->profession?->category?->jpl_target ?? 24;
            $user->unique_activities_count = $uniqueParticipants->count();

            return $user;
        });

        // TABLE 2: Detailed Activities
        $detailedQuery = ActivityParticipant::with([
            'user.workUnit',
            'user.profession.category',
            'user.employmentType',
            'activity.activityName',
            'activity.activityScope',
            'activity.activityMaterials',
            'activity.activityProfessions.profession',
        ])->where('is_passed', true)
            ->whereHas('user', function ($q) use ($searchNip, $searchNama) {
                if ($searchNip) {
                    $q->where('employee_id', 'like', '%' . $searchNip . '%');
                }
                if ($searchNama) {
                    $q->where('name', 'like', '%' . $searchNama . '%');
                }
            });

        $detailedActivities = $detailedQuery->get()
            ->unique(function ($participant) {
                return $participant->user_id . '-' . $participant->activity_id;
            })
            ->map(function ($participant) {
                $participant->capaian_jpl = $participant->activity ? $participant->activity->activityMaterials->sum('value') : 0;
                $participant->target_jpl = $participant->user?->profession?->category?->jpl_target ?? 24;

                return $participant;
            });

        // CHART DATA: Sort by capaian descending, top 10
        $topUsers = $users->sortByDesc('capaian_jpl')->take(10)->values();
        $chartLabels = $topUsers->pluck('name');
        $chartData = $topUsers->pluck('capaian_jpl');

        $year = $request->input('year', date('Y'));

        // Query all users except SuperAdmins
        $users = User::whereDoesntHave('roles', function ($q) {
            $q->where('name', 'SuperAdmin');
        })
            ->with(['profession.category', 'activityParticipants' => function ($q) use ($year) {
                $q->where('is_passed', true)
                    ->whereHas('activity', function ($qa) use ($year) {
                        // Assuming activity has end_date which falls in the selected year
                        // or start_date if end_date is null. Usually year of end_date.
                        $qa->whereYear('end_date', $year)
                            ->orWhereYear('start_date', $year);
                    })
                    ->with('activity.activityMaterials');
            }])
            ->get();

        $numerator1 = 0; // Capaian >= 40 for target == 40
        $denominator1 = 0; // Target == 40

        $numerator2 = 0; // Capaian >= 24 for target == 24
        $denominator2 = 0; // Target == 24

        foreach ($users as $user) {
            $targetJpl = $user->profession?->category?->jpl_target ?? 24; // Default to 24 if null theoretically

            if ($targetJpl == 40 || $targetJpl == 24) {
                // Calculate capaian for this user in this year
                $uniqueParticipants = $user->activityParticipants->unique('activity_id');
                $capaian = $uniqueParticipants->sum(function ($participant) {
                    return $participant->activity ? $participant->activity->activityMaterials->sum('value') : 0;
                });

                $capaianJpl = round($capaian / 45, 2);

                if ($targetJpl == 40) {
                    $denominator1++;
                    if ($capaianJpl >= 40) {
                        $numerator1++;
                    }
                } elseif ($targetJpl == 24) {
                    $denominator2++;
                    if ($capaianJpl >= 24) {
                        $numerator2++;
                    }
                }
            }
        }

        $teiPercentage = $denominator1 > 0 ? round(($numerator1 / $denominator1) * 100, 2) : 0;
        $cgPercentage = $denominator2 > 0 ? round(($numerator2 / $denominator2) * 100, 2) : 0;

        return view('monitoringJpl', compact('users', 'detailedActivities', 'chartLabels', 'chartData', 'year', 'numerator1', 'denominator1', 'teiPercentage', 'numerator2', 'denominator2', 'cgPercentage'));
    }
}
