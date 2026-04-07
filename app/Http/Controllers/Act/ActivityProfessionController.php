<?php

namespace App\Http\Controllers\Act;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Act\Activity;
use App\Models\Act\ActivityProfession;

class ActivityProfessionController extends Controller
{
    /**
     * Store a newly created sasaran profesi in storage.
     */
    public function store(Request $request, $kegiatanId)
    {
        $request->validate([
            'profession_id' => 'required',
        ]);

        $professionInput = $request->profession_id;
        if (!is_numeric($professionInput)) {
            $profession = \App\Models\User\Profession::firstOrCreate(['name' => $professionInput]);
            $professionId = $profession->id;
        } else {
            $professionId = $professionInput;
        }

        $activity = Activity::findOrFail($kegiatanId);

        // Check if the profession is already added to prevent duplicates
        $exists = ActivityProfession::where('activity_id', $activity->id)
            ->where('profession_id', $professionId)
            ->exists();

        if ($exists) {
            return redirect()->route('kegiatan.show', ['kegiatan' => $activity->id, 'tab' => 'sasaran'])
                ->withErrors(['profession_id' => 'Sasaran profesi ini sudah ditambahkan.']);
        }

        ActivityProfession::create([
            'activity_id' => $activity->id,
            'profession_id' => $professionId,
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
