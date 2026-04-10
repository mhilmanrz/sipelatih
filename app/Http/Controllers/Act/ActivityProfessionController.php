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
            'profession_id' => 'required|exists:professions,id',
        ]);

        $activity = Activity::findOrFail($kegiatanId);

        // Check if the profession is already added to prevent duplicates
        $exists = ActivityProfession::where('activity_id', $activity->id)
            ->where('profession_id', $request->profession_id)
            ->exists();

        if ($exists) {
            return redirect()->route('kegiatan.show', ['kegiatan' => $activity->id, 'tab' => 'sasaran'])
                ->withErrors(['profession_id' => 'Sasaran profesi ini sudah ditambahkan.']);
        }

        ActivityProfession::create([
            'activity_id' => $activity->id,
            'profession_id' => $request->profession_id,
        ]);

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
