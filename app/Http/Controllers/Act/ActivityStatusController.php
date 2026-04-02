<?php

namespace App\Http\Controllers\Act;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Act\Activity;
use App\Models\Act\ActivityStatus;

class ActivityStatusController extends Controller
{
    /**
     * Submit an activity (change status from draft to submitted).
     */
    public function submit(Request $request, $kegiatanId)
    {
        $activity = Activity::findOrFail($kegiatanId);

        // Optional: Ensure it's currently in draft or has no status
        $latestStatus = $activity->latestStatus ? $activity->latestStatus->status : 'draft';

        if ($latestStatus !== 'draft' && $latestStatus !== 'revision') {
            return redirect()->back()->with('error', 'Status kegiatan saat ini tidak dapat dikirim.');
        }

        ActivityStatus::create([
            'activity_id' => $activity->id,
            'status' => 'submitted',
            'note' => $request->input('note', 'Dikirim untuk persetujuan.'),
        ]);

        return redirect()->route('kegiatan.show', ['kegiatan' => $activity->id, 'tab' => 'pengiriman'])
            ->with('success', 'Usulan kegiatan berhasil dikirim.');
    }

    /**
     * Cancel submission (revert to draft).
     */
    public function cancel(Request $request, $kegiatanId)
    {
        $activity = Activity::findOrFail($kegiatanId);
        
        $latestStatus = $activity->latestStatus ? $activity->latestStatus->status : 'draft';

        if ($latestStatus !== 'submitted') {
            return redirect()->back()->with('error', 'Hanya kegiatan yang baru dikirim yang dapat dibatalkan.');
        }

        ActivityStatus::create([
            'activity_id' => $activity->id,
            'status' => 'draft',
            'note' => 'Pengiriman dibatalkan oleh pengguna.',
        ]);

        return redirect()->route('kegiatan.show', ['kegiatan' => $activity->id, 'tab' => 'pengiriman'])
            ->with('success', 'Pengiriman usulan berhasil dibatalkan dan dikembalikan ke status Draft.');
    }
}
