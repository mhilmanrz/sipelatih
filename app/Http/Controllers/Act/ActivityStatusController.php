<?php

namespace App\Http\Controllers\Act;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Act\Activity;
use App\Models\Act\ActivityStatus;
use App\Models\Budget;
use Illuminate\Support\Facades\DB;

class ActivityStatusController extends Controller
{
    /**
     * Submit an activity (change status from draft to submitted).
     */
    public function submit(Request $request, $kegiatanId)
    {
        $activity = Activity::findOrFail($kegiatanId);

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

    /**
     * Accept an activity and deduct budget (admin only).
     */
    public function accept(Request $request, $kegiatanId)
    {
        $activity = Activity::with('budget')->findOrFail($kegiatanId);

        $latestStatus = $activity->latestStatus ? $activity->latestStatus->status : 'draft';

        if ($latestStatus !== 'submitted') {
            return redirect()->back()->with('error', 'Hanya kegiatan berstatus "Terkirim" yang dapat disetujui.');
        }

        DB::transaction(function () use ($activity, $request) {
            // Buat record status accepted
            ActivityStatus::create([
                'activity_id' => $activity->id,
                'status' => 'accepted',
                'note' => $request->input('note', 'Disetujui oleh admin.'),
            ]);

            // Kurangi remaining_amount dari budget yang terkait
            if ($activity->budget_id && $activity->budget_amount) {
                $budget = Budget::find($activity->budget_id);
                if ($budget) {
                    $newRemaining = max(0, $budget->remaining_amount - $activity->budget_amount);
                    $budget->update(['remaining_amount' => $newRemaining]);
                }
            }
        });

        return redirect()->route('kegiatan.show', ['kegiatan' => $activity->id, 'tab' => 'pengiriman'])
            ->with('success', 'Usulan kegiatan berhasil disetujui dan pagu anggaran telah dikurangi.');
    }

    /**
     * Reject/revision an activity (admin only).
     */
    public function reject(Request $request, $kegiatanId)
    {
        $activity = Activity::findOrFail($kegiatanId);

        $latestStatus = $activity->latestStatus ? $activity->latestStatus->status : 'draft';

        if ($latestStatus !== 'submitted') {
            return redirect()->back()->with('error', 'Hanya kegiatan berstatus "Terkirim" yang dapat ditolak.');
        }

        $request->validate([
            'note' => 'required|string|max:500',
        ], [
            'note.required' => 'Catatan alasan penolakan wajib diisi.',
        ]);

        ActivityStatus::create([
            'activity_id' => $activity->id,
            'status' => 'revision',
            'note' => $request->input('note'),
        ]);

        return redirect()->route('kegiatan.show', ['kegiatan' => $activity->id, 'tab' => 'pengiriman'])
            ->with('success', 'Usulan dikembalikan untuk revisi.');
    }
}
