<?php

namespace App\Http\Controllers\Act;

use App\Http\Controllers\Controller;
use App\Models\Act\Activity;
use App\Models\Act\ActivityStatus;
use Carbon\Carbon;
use Illuminate\Http\Request;

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

        // Enforce all requirements are met before submitting
        $kegiatanRequiredFields = [
            'activity_name_id', 'activity_type_id', 'activity_category_id', 'activity_scope_id',
            'material_type_id', 'activity_method_id', 'batch_id', 'activity_format_id',
            'quota_participant', 'work_unit_id', 'pic_user_id', 'organizer_pic_id',
            'date', 'reference_number', 'start_date', 'end_date', 'start_time',
            'end_time', 'tempat', 'fund_source_id', 'budget_amount',
        ];
        $kegiatanComplete = true;
        foreach ($kegiatanRequiredFields as $field) {
            if (is_null($activity->{$field}) || $activity->{$field} === '') {
                $kegiatanComplete = false;
                break;
            }
        }

        $justifikasiComplete = ! empty($activity->tujuan)
            && ! empty($activity->justifikasi)
            && $activity->activityTargets()->exists();

        $sasaranComplete = $activity->activityProfessions()->exists();
        $kakComplete = $activity->activityKakFiles()->exists();
        $materiComplete = $activity->activityMaterials()->exists();
        $narasumberComplete = $activity->speakers()->exists();
        $pesertaComplete = $activity->activityParticipants()->exists();

        $waktuComplete = false;
        if ($activity->start_date) {
            $startDate = Carbon::parse($activity->start_date)->startOfDay();
            $today = now()->startOfDay();
            $daysRemaining = $today->diffInDays($startDate, false);
            $waktuComplete = $daysRemaining < 45;
        }

        if (! $kegiatanComplete || ! $justifikasiComplete || ! $sasaranComplete || ! $kakComplete || ! $materiComplete || ! $narasumberComplete || ! $pesertaComplete || ! $waktuComplete) {
            return redirect()->back()->with('error', 'Persyaratan pengiriman usulan belum terpenuhi. Silakan periksa kembali kelengkapan data usulan Anda.');
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
