<?php

namespace App\Http\Controllers;

use App\Models\Act\ActivityParticipant;
use App\Models\User\User;
use Illuminate\Http\Request;

class MonitoringJplController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->input('q');
        $year = $request->input('year', date('Y'));
        $page = $request->input('page', 1);
        $perPage = $request->input('per_page', 10);

        $usersQuery = User::doesntHave('roles')
            ->where('email', '!=', 'admin@mail.com')
            ->with(['workUnit', 'profession.category', 'activityParticipants' => function ($query) use ($year) {
                $query->where('is_passed', true)
                    ->whereHas('activity', function ($qa) use ($year) {
                        $qa->whereYear('end_date', $year)
                            ->orWhereYear('start_date', $year);
                    })
                    ->with('activity.activityMaterials');
            }])->when($q, function ($query, $q) {
                $query->where(function($sub) use ($q) {
                    $sub->where('employee_id', 'like', '%'.$q.'%')
                        ->orWhere('name', 'like', '%'.$q.'%');
                });
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
            ->whereHas('activity', function ($qa) use ($year) {
                $qa->whereYear('end_date', $year)
                    ->orWhereYear('start_date', $year);
            })
            ->whereHas('user', function ($query) use ($q) {
                $query->doesntHave('roles')->where('email', '!=', 'admin@mail.com');
                if ($q) {
                    $query->where(function($sub) use ($q) {
                        $sub->where('employee_id', 'like', '%'.$q.'%')
                            ->orWhere('name', 'like', '%'.$q.'%');
                    });
                }
            });

        $detailedActivities = $detailedQuery->get()
            ->unique(function ($participant) {
                return $participant->user_id.'-'.$participant->activity_id;
            })
            ->map(function ($participant) {
                $participant->capaian_jpl = $participant->activity ? $participant->activity->activityMaterials->sum('value') : 0;
                $participant->target_jpl = $participant->user?->profession?->category?->jpl_target ?? 24;

                return $participant;
            });

        $totalDetailed = $detailedActivities->count();
        $detailedActivitiesPaginated = new \Illuminate\Pagination\LengthAwarePaginator(
            $detailedActivities->forPage($page, $perPage)->values(),
            $totalDetailed,
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        // CHART DATA: JPL per kategori profesi
        $jplPerCategory = ActivityParticipant::with([
            'user.profession.category',
            'activity.activityMaterials',
        ])->where('is_passed', true)
            ->whereHas('activity', function ($qa) use ($year) {
                $qa->whereYear('end_date', $year)
                    ->orWhereYear('start_date', $year);
            })
            ->whereHas('user', function ($q) {
                $q->doesntHave('roles')->where('email', '!=', 'admin@mail.com');
            })
            ->get()
            ->groupBy(fn ($p) => $p->user?->profession?->category?->name ?? 'Tidak Berkategori')
            ->map(fn ($group) => round(
                $group->sum(fn ($p) => $p->activity?->activityMaterials?->sum('value') ?? 0) / 45,
                2
            ))
            ->sortKeys();

        $chartLabels = $jplPerCategory->keys();
        $chartData = $jplPerCategory->values();

        // INDIKATOR KINERJA — semua pegawai
        $allUsers = User::doesntHave('roles')
            ->where('email', '!=', 'admin@mail.com')
            ->with(['profession.category', 'activityParticipants' => function ($q) use ($year) {
                $q->where('is_passed', true)
                    ->whereHas('activity', function ($qa) use ($year) {
                        $qa->whereYear('end_date', $year)
                            ->orWhereYear('start_date', $year);
                    })
                    ->with('activity.activityMaterials');
            }])->get();

        $numerator1 = 0; // Capaian >= 40 for target == 40
        $denominator1 = 0; // Target == 40
        $numerator2 = 0; // Capaian >= 24 for target == 24
        $denominator2 = 0; // Target == 24

        foreach ($allUsers as $u) {
            $targetJpl = $u->profession?->category?->jpl_target ?? 24;
            if ($targetJpl == 40 || $targetJpl == 24) {
                $uniqueParticipants = $u->activityParticipants->unique('activity_id');
                $capaian = $uniqueParticipants->sum(function ($p) {
                    return $p->activity ? $p->activity->activityMaterials->sum('value') : 0;
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

        return view('monitoringJpl', compact(
            'users', 'detailedActivities', 'detailedActivitiesPaginated', 'chartLabels', 'chartData', 'year',
            'numerator1', 'denominator1', 'teiPercentage',
            'numerator2', 'denominator2', 'cgPercentage'
        ));
    }
}
