<?php

namespace App\Http\Controllers\Act;

use App\Http\Controllers\Controller;
use App\Models\Act\Activity;
use App\Models\Act\ActivitySpeaker;
use Illuminate\Http\Request;

class ActivitySpeakerController extends Controller
{
    public function store(Request $request, $kegiatanId)
    {
        $request->validate([
            'activity_material_id' => 'required|exists:activity_materials,id',
            'user_id' => 'required|exists:users,id',
        ]);

        $activity = Activity::findOrFail($kegiatanId);

        ActivitySpeaker::create([
            'activity_material_id' => $request->activity_material_id,
            'user_id' => $request->user_id,
        ]);

        return redirect()->route('kegiatan.show', ['kegiatan' => $activity->id, 'tab' => 'narasumber'])
            ->with('success', 'Narasumber berhasil ditambahkan.');
    }

    public function destroy($kegiatanId, $id)
    {
        $speaker = ActivitySpeaker::findOrFail($id);
        $speaker->delete();

        return redirect()->route('kegiatan.show', ['kegiatan' => $kegiatanId, 'tab' => 'narasumber'])
            ->with('success', 'Narasumber berhasil dihapus.');
    }
}
