<?php

namespace App\Http\Controllers;

use App\Models\Act\ActivityParticipant;
use App\Models\User\User;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class MonitoringJplController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('q');
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
            }])->when($search, function ($q, $search) {
                $q->where(function ($query) use ($search) {
                    $query->where('name', 'like', '%'.$search.'%')
                        ->orWhere('employee_id', 'like', '%'.$search.'%')
                        ->orWhereHas('workUnit', function ($qu) use ($search) {
                            $qu->where('name', 'like', '%'.$search.'%');
                        })
                        ->orWhereHas('profession', function ($qu) use ($search) {
                            $qu->where('name', 'like', '%'.$search.'%');
                        });
                });
            });

        // Get users and calculate JPL
        $users = $usersQuery->get()->map(function ($user) {
            $uniqueParticipants = $user->activityParticipants->unique('activity_id');
            $capaian = $uniqueParticipants->sum(function ($participant) {
                return $participant->activity ? $participant->activity->activityMaterials->sum('value') : 0;
            });

            $user->capaian_jpl = round($capaian / 45, 2);
            $user->target_jpl = $user->profession?->category?->jpl_target ?? 24;
            $user->category_name = $user->profession?->category?->name ?? '-';
            $user->unique_activities_count = $uniqueParticipants->count();

            return $user;
        })->values();

        // Paginate users
        $perPage = $request->input('entries', 10);
        $page = $request->input('page', 1);
        $paginatedUsers = new LengthAwarePaginator(
            $users->forPage($page, $perPage),
            $users->count(),
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

        $numerator1 = 0;
        $denominator1 = 0;
        $numerator2 = 0;
        $denominator2 = 0;

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
            'users', 'paginatedUsers', 'chartLabels', 'chartData', 'year',
            'numerator1', 'denominator1', 'teiPercentage',
            'numerator2', 'denominator2', 'cgPercentage'
        ));
    }
}
