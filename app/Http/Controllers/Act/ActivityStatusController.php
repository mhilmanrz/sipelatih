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
     * Abort with 403 unless the current user owns one of the given stage roles (or is superadmin).
     */
    private function authorizeStage(array $roles): void
    {
        abort_unless(auth()->user()->hasAnyRole([...$roles, 'superadmin']), 403);
    }

    /**
     * Pengusul submits to perencanaan.
     * Valid from: pengusul/draft or pengusul/revision
     */
    public function submit(Request $request, $kegiatanId)
    {
        $this->authorizeStage(['pengusul']);

        $activity = Activity::findOrFail($kegiatanId);

        $stage = $activity->currentStage();
        $status = $activity->currentStatus();

        if ($stage !== 'pengusul' || ! in_array($status, ['draft', 'revision'])) {
            return redirect()->back()->with('error', 'Usulan tidak dapat dikirim dari status saat ini.');
        }

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
            $daysRemaining = now()->startOfDay()->diffInDays(Carbon::parse($activity->start_date)->startOfDay(), false);
            $waktuComplete = $daysRemaining < 45;
        }

        if (! $kegiatanComplete || ! $justifikasiComplete || ! $sasaranComplete || ! $kakComplete
            || ! $materiComplete || ! $narasumberComplete || ! $pesertaComplete || ! $waktuComplete) {
            return redirect()->back()->with('error', 'Persyaratan pengiriman belum terpenuhi. Silakan periksa kelengkapan data.');
        }

        ActivityStatus::create([
            'activity_id' => $activity->id,
            'stage' => 'perencanaan',
            'status' => 'pending',
            'note' => $request->input('note', 'Dikirim untuk review perencanaan.'),
        ]);

        return redirect()->route('kegiatan.show', ['kegiatan' => $activity->id, 'tab' => 'pengiriman'])
            ->with('success', 'Usulan berhasil dikirim ke Tim Perencanaan.');
    }

    /**
     * Pengusul cancels submission (while still perencanaan/pending).
     */
    public function cancelSubmit($kegiatanId)
    {
        $this->authorizeStage(['pengusul']);

        $activity = Activity::findOrFail($kegiatanId);

        if ($activity->currentStage() !== 'perencanaan' || $activity->currentStatus() !== 'pending') {
            return redirect()->back()->with('error', 'Usulan hanya bisa ditarik jika masih menunggu review perencanaan.');
        }

        ActivityStatus::create([
            'activity_id' => $activity->id,
            'stage' => 'pengusul',
            'status' => 'draft',
            'note' => 'Pengiriman ditarik kembali oleh pengusul.',
        ]);

        return redirect()->route('kegiatan.show', ['kegiatan' => $activity->id, 'tab' => 'pengiriman'])
            ->with('success', 'Usulan berhasil ditarik kembali ke Draft.');
    }

    /**
     * Perencanaan approves → penyelenggara/pending.
     */
    public function approve(Request $request, $kegiatanId)
    {
        $this->authorizeStage(['perencanaan']);

        $activity = Activity::findOrFail($kegiatanId);

        if ($activity->currentStage() !== 'perencanaan' || $activity->currentStatus() !== 'pending') {
            return redirect()->back()->with('error', 'Tidak dapat menyetujui usulan dari status saat ini.');
        }

        ActivityStatus::create([
            'activity_id' => $activity->id,
            'stage' => 'penyelenggara',
            'status' => 'pending',
            'note' => $request->input('note', 'Disetujui oleh perencanaan, diteruskan ke penyelenggara.'),
        ]);

        return redirect()->route('kegiatan.show', ['kegiatan' => $activity->id, 'tab' => 'pengiriman'])
            ->with('success', 'Usulan disetujui dan diteruskan ke Tim Penyelenggara.');
    }

    /**
     * Return for revision to the previous stage.
     * perencanaan/pending → pengusul/revision
     * penyelenggara/pending → perencanaan/revision
     */
    public function returnRevision(Request $request, $kegiatanId)
    {
        $request->validate(['note' => 'required|string|max:1000']);

        $activity = Activity::findOrFail($kegiatanId);
        $stage = $activity->currentStage();
        $status = $activity->currentStatus();

        $this->authorizeStage(array_filter([
            $stage === 'perencanaan' ? 'perencanaan' : null,
            $stage === 'penyelenggara' ? 'penyelenggara' : null,
        ]));

        $targetStage = match (true) {
            $stage === 'perencanaan' && $status === 'pending' => 'pengusul',
            $stage === 'penyelenggara' && $status === 'pending' => 'perencanaan',
            default => null,
        };

        if (! $targetStage) {
            return redirect()->back()->with('error', 'Tidak dapat mengembalikan dari status saat ini.');
        }

        ActivityStatus::create([
            'activity_id' => $activity->id,
            'stage' => $targetStage,
            'status' => 'revision',
            'note' => $request->input('note'),
        ]);

        $targetLabel = $targetStage === 'pengusul' ? 'Pengusul' : 'Perencanaan';

        return redirect()->route('kegiatan.show', ['kegiatan' => $activity->id, 'tab' => 'pengiriman'])
            ->with('success', "Usulan dikembalikan ke {$targetLabel} untuk revisi.");
    }

    /**
     * Perencanaan forwards after fixing revision from penyelenggara.
     * perencanaan/revision → penyelenggara/pending
     */
    public function perencanaanForward(Request $request, $kegiatanId)
    {
        $this->authorizeStage(['perencanaan']);

        $activity = Activity::findOrFail($kegiatanId);

        if ($activity->currentStage() !== 'perencanaan' || $activity->currentStatus() !== 'revision') {
            return redirect()->back()->with('error', 'Tidak dapat meneruskan dari status saat ini.');
        }

        ActivityStatus::create([
            'activity_id' => $activity->id,
            'stage' => 'penyelenggara',
            'status' => 'pending',
            'note' => $request->input('note', 'Revisi selesai, diteruskan kembali ke penyelenggara.'),
        ]);

        return redirect()->route('kegiatan.show', ['kegiatan' => $activity->id, 'tab' => 'pengiriman'])
            ->with('success', 'Usulan diteruskan kembali ke Tim Penyelenggara.');
    }

    /**
     * Penyelenggara marks activity complete → evaluasi/pending.
     */
    public function penyelenggaraComplete(Request $request, $kegiatanId)
    {
        $this->authorizeStage(['penyelenggara']);

        $activity = Activity::findOrFail($kegiatanId);

        if ($activity->currentStage() !== 'penyelenggara' || $activity->currentStatus() !== 'pending') {
            return redirect()->back()->with('error', 'Tidak dapat menyelesaikan dari status saat ini.');
        }

        ActivityStatus::create([
            'activity_id' => $activity->id,
            'stage' => 'evaluasi',
            'status' => 'pending',
            'note' => $request->input('note', 'Kegiatan selesai dilaksanakan, diteruskan ke evaluasi.'),
        ]);

        return redirect()->route('kegiatan.show', ['kegiatan' => $activity->id, 'tab' => 'pengiriman'])
            ->with('success', 'Kegiatan selesai dan diteruskan ke Tim Evaluasi.');
    }

    /**
     * Evaluasi submits final evaluation → evaluasi/completed.
     */
    public function evaluasiComplete(Request $request, $kegiatanId)
    {
        $this->authorizeStage(['evaluasi']);

        $activity = Activity::findOrFail($kegiatanId);

        if ($activity->currentStage() !== 'evaluasi' || $activity->currentStatus() !== 'pending') {
            return redirect()->back()->with('error', 'Tidak dapat menyelesaikan evaluasi dari status saat ini.');
        }

        ActivityStatus::create([
            'activity_id' => $activity->id,
            'stage' => 'evaluasi',
            'status' => 'completed',
            'note' => $request->input('note', 'Evaluasi selesai.'),
        ]);

        return redirect()->route('kegiatan.show', ['kegiatan' => $activity->id, 'tab' => 'pengiriman'])
            ->with('success', 'Evaluasi selesai. Kegiatan telah ditutup.');
    }
}
