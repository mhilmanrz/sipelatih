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
        $year = $request->input('year', date('Y'));

        $usersQuery = User::doesntHave('roles')
            ->where('email', '!=', 'admin@mail.com')
            ->with(['workUnit', 'profession.category', 'activityParticipants' => function ($q) use ($year) {
                $q->where('is_passed', true)
                    ->whereHas('activity', function ($qa) use ($year) {
                        $qa->whereYear('end_date', $year)
                            ->orWhereYear('start_date', $year);
                    })
                    ->with('activity.activityMaterials');
            }])->when($searchNip, function ($q, $nip) {
                $q->where('employee_id', 'like', '%'.$nip.'%');
            })->when($searchNama, function ($q, $nama) {
                $q->where('name', 'like', '%'.$nama.'%');
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
            ->whereHas('user', function ($q) use ($searchNip, $searchNama) {
                $q->doesntHave('roles')->where('email', '!=', 'admin@mail.com');
                if ($searchNip) {
                    $q->where('employee_id', 'like', '%'.$searchNip.'%');
                }
                if ($searchNama) {
                    $q->where('name', 'like', '%'.$searchNama.'%');
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
            'users', 'detailedActivities', 'chartLabels', 'chartData', 'year',
            'numerator1', 'denominator1', 'teiPercentage',
            'numerator2', 'denominator2', 'cgPercentage'
        ));
    }
}
