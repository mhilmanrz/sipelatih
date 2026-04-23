<?php

namespace App\Http\Controllers\Act;

use App\Http\Controllers\Controller;
use App\Models\Act\Activity;
use App\Models\Act\ActivityTarget;
use Illuminate\Http\Request;

class ActivityTargetController extends Controller
{
    public function store(Request $request, $kegiatanId)
    {
        $request->validate([
            'target_number' => 'required|integer|in:1,2,3',
            'description' => 'required|string',
        ]);

        $activity = Activity::findOrFail($kegiatanId);

        $activity->activityTargets()->updateOrCreate(
            ['target_number' => $request->target_number],
            ['description' => $request->description]
        );

        return redirect()->route('kegiatan.show', ['kegiatan' => $kegiatanId, 'tab' => 'justifikasi'])
            ->with('success', 'Target berhasil disimpan.');
    }

    public function update(Request $request, $kegiatanId, $id)
    {
        $request->validate([
            'description' => 'required|string',
        ]);

        $target = ActivityTarget::where('activity_id', $kegiatanId)->findOrFail($id);
        $target->update(['description' => $request->description]);

        return redirect()->route('kegiatan.show', ['kegiatan' => $kegiatanId, 'tab' => 'justifikasi'])
            ->with('success', 'Target berhasil diperbarui.');
    }

    public function destroy($kegiatanId, $id)
    {
        $target = ActivityTarget::where('activity_id', $kegiatanId)->findOrFail($id);
        $target->delete();

        return redirect()->route('kegiatan.show', ['kegiatan' => $kegiatanId, 'tab' => 'justifikasi'])
            ->with('success', 'Target berhasil dihapus.');
    }
}
