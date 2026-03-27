<?php

namespace App\Http\Controllers;

use App\Models\Act\Activity;
use App\Models\User\Profession;
use Illuminate\Http\Request;

class UsulanDiklatController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->input('entries', 10);
        $search = $request->input('search');

        $query = Activity::with(['activityName', 'activityType', 'activityScope', 'materialType', 'latestStatus', 'workUnit', 'materials']);

        if ($search) {
            $query->whereHas('activityName', function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%');
            })
                ->orWhereHas('workUnit', function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%');
                });
        }

        $kegiatan = $query->paginate($perPage);

        return \Inertia\Inertia::render('Usulan/Index', [
            'kegiatan' => $kegiatan,
            'filters' => [
                'search' => $search,
                'entries' => $perPage,
            ]
        ]);
    }

    private function loadCommonRelations(Activity $activity)
    {
        return $activity->load([
            'activityName',
            'workUnit',
            'activityType',
            'activityScope',
            'materialType',
            'latestStatus'
        ]);
    }

    public function showKegiatan(Activity $activity)
    {
        $this->loadCommonRelations($activity);
        $activity->load(['activityMethod', 'batch', 'activityFormat', 'targetParticipant', 'picUser']);

        return \Inertia\Inertia::render('Usulan/Detail/Kegiatan', [
            'kegiatan' => $activity
        ]);
    }

    public function showSasaranProfesi(Activity $activity)
    {
        $this->loadCommonRelations($activity);
        $activity->load(['activityProfessions.profession']);

        $availableProfessions = Profession::orderBy('name')->get();

        return \Inertia\Inertia::render('Usulan/Detail/SasaranProfesi', [
            'kegiatan' => $activity,
            'availableProfessions' => $availableProfessions
        ]);
    }

    public function showKak(Activity $activity)
    {
        $this->loadCommonRelations($activity);
        $activity->load(['kakFile']);

        return \Inertia\Inertia::render('Usulan/Detail/Kak', [
            'kegiatan' => $activity
        ]);
    }

    public function showUnderConstruction(Activity $activity, $tab)
    {
        $this->loadCommonRelations($activity);

        $tabNames = [
            'materi' => 'Materi',
            'narasumber' => 'Narasumber',
            'peserta' => 'Peserta',
            'pengiriman' => 'Pengiriman',
            'penilaian' => 'Penilaian',
            'sertifikat' => 'Sertifikat',
        ];

        return \Inertia\Inertia::render('Usulan/Detail/UnderConstruction', [
            'kegiatan' => $activity,
            'activeTab' => $tabNames[$tab] ?? ucfirst($tab)
        ]);
    }

    public function storeProfession(Request $request, Activity $activity)
    {
        $request->validate([
            'profession_id' => 'required|exists:professions,id'
        ]);

        $activity->activityProfessions()->firstOrCreate([
            'profession_id' => $request->profession_id
        ]);

        return back();
    }

    public function destroyProfession(Activity $activity, $id)
    {
        $activity->activityProfessions()->where('id', $id)->delete();
        return back();
    }
}
