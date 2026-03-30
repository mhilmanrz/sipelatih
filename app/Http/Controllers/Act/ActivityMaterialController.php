<?php

namespace App\Http\Controllers\Act;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Act\Activity;
use App\Models\Act\ActivityMaterial;

class ActivityMaterialController extends Controller
{
    /**
     * Store a newly created materi in storage.
     */
    public function store(Request $request, $kegiatanId)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'value' => 'required|numeric|min:0.1', // JPL
        ]);

        $activity = Activity::findOrFail($kegiatanId);

        ActivityMaterial::create([
            'activity_id' => $activity->id,
            'name' => $request->name,
            'value' => $request->value,
        ]);

        return redirect()->route('kegiatan.show', ['kegiatan' => $activity->id, 'tab' => 'materi'])
            ->with('success', 'Materi berhasil ditambahkan.');
    }

    /**
     * Remove the specified materi from storage.
     */
    public function destroy($kegiatanId, $id)
    {
        $activityMaterial = ActivityMaterial::where('activity_id', $kegiatanId)
            ->findOrFail($id);
            
        $activityMaterial->delete();

        return redirect()->route('kegiatan.show', ['kegiatan' => $kegiatanId, 'tab' => 'materi'])
            ->with('success', 'Materi berhasil dihapus.');
    }
}
