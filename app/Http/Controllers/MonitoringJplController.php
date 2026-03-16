<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Act\ActivityParticipant;
use Illuminate\Http\Request;

class MonitoringJplController extends Controller
{
    /**
     * Display monitoring capaian JPL per peserta per kegiatan.
     *
     * Kolom: Nama Pegawai, NIP, Unit Kerja, Nama Kegiatan, Waktu Kegiatan,
     *        Cakupan Kegiatan, Jabatan, Tenaga, Target, Capaian, Keterangan
     */
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $searchNip = $request->input('search_nip');
        $searchName = $request->input('search_name');

        $query = ActivityParticipant::query()
            ->with([
                'user.workUnit',
                'user.position',
                'user.employmentType',
                'activity.activityScope',
                'activity.materials',
            ])
            ->join('users', 'activity_participants.user_id', '=', 'users.id')
            ->join('activities', 'activity_participants.activity_id', '=', 'activities.id')
            ->select('activity_participants.*')
            ->when($searchNip, function ($q) use ($searchNip) {
                $q->where('users.employee_id', 'like', '%' . $searchNip . '%');
            })
            ->when($searchName, function ($q) use ($searchName) {
                $q->where('users.name', 'like', '%' . $searchName . '%');
            })
            ->orderBy('users.name')
            ->orderBy('activities.start_date');

        $paginated = $query->paginate($perPage);

        $data = $paginated->getCollection()->map(function ($participant) {
            $user = $participant->user;
            $activity = $participant->activity;

            // Capaian JPL = sum semua materi JPL dari kegiatan ini
            $capaianKegiatan = $activity->materials->sum('jpl');

            // Total capaian semua kegiatan yang diikuti user ini
            $totalCapaian = ActivityParticipant::where('user_id', $user->id)
                ->with('activity.materials')
                ->get()
                ->sum(fn($p) => $p->activity->materials->sum('jpl'));

            $target = $user->jpl_target ?? 24;
            $keterangan = $totalCapaian >= $target ? 'Tercapai' : 'Belum Tercapai';

            // Format waktu kegiatan
            $waktu = null;
            if ($activity->start_date && $activity->end_date) {
                $start = \Carbon\Carbon::parse($activity->start_date);
                $end = \Carbon\Carbon::parse($activity->end_date);
                $waktu = $start->format('j') . '-' . $end->format('j') . ' ' . $end->translatedFormat('F Y');
            } elseif ($activity->start_date) {
                $waktu = \Carbon\Carbon::parse($activity->start_date)->translatedFormat('j F Y');
            }

            return [
                'participant_id'     => $participant->id,
                'nama_pegawai'       => $user->name,
                'nip'                => $user->employee_id,
                'unit_kerja'         => optional($user->workUnit)->name,
                'nama_kegiatan'      => $activity->name,
                'waktu_kegiatan'     => $waktu,
                'cakupan_kegiatan'   => optional($activity->activityScope)->name,
                'jabatan'            => optional($user->position)->name,
                'tenaga'             => optional($user->employmentType)->name,
                'target'             => $target,
                'capaian'            => round($totalCapaian, 1),
                'capaian_kegiatan'   => round($capaianKegiatan, 1),
                'keterangan'         => $keterangan,
            ];
        });

        return response()->json([
            'data' => $data,
            'meta' => [
                'current_page' => $paginated->currentPage(),
                'last_page'    => $paginated->lastPage(),
                'per_page'     => $paginated->perPage(),
                'total'        => $paginated->total(),
            ],
        ]);
    }
}
