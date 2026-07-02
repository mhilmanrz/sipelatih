<?php

namespace App\Http\Controllers\Act;

use App\Http\Controllers\Controller;
use App\Models\Act\Activity;
use App\Models\Act\ActivityProfession;
use Illuminate\Http\Request;

class ActivityProfessionController extends Controller
{
    /**
     * Store a newly created sasaran profesi in storage.
     */
    public function store(Request $request, $kegiatanId)
    {
        $request->validate([
            'profession_id' => 'required|array|min:1',
            'profession_id.*' => 'exists:professions,id',
        ]);

        $activity = Activity::findOrFail($kegiatanId);

        $existingIds = ActivityProfession::where('activity_id', $activity->id)
            ->whereIn('profession_id', $request->profession_id)
            ->pluck('profession_id')
            ->all();

        $newIds = array_diff($request->profession_id, $existingIds);

        foreach ($newIds as $professionId) {
            ActivityProfession::create([
                'activity_id' => $activity->id,
                'profession_id' => $professionId,
            ]);
        }

        if (empty($newIds)) {
            return redirect()->route('kegiatan.show', ['kegiatan' => $activity->id, 'tab' => 'sasaran'])
                ->withErrors(['profession_id' => 'Sasaran profesi ini sudah ditambahkan.']);
        }

        return redirect()->route('kegiatan.show', ['kegiatan' => $activity->id, 'tab' => 'sasaran'])
            ->with('success', 'Sasaran Profesi berhasil ditambahkan.');
    }

    /**
     * Remove the specified sasaran profesi from storage.
     */
    public function destroy($kegiatanId, $id)
    {
        $activityProfession = ActivityProfession::where('activity_id', $kegiatanId)
            ->findOrFail($id);

        $activityProfession->delete();

        return redirect()->route('kegiatan.show', ['kegiatan' => $kegiatanId, 'tab' => 'sasaran'])
            ->with('success', 'Sasaran Profesi berhasil dihapus.');
    }
}
