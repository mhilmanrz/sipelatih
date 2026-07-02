<?php

namespace App\Http\Controllers\Act;

use App\Http\Controllers\Controller;
use App\Models\Act\Activity;
use Illuminate\Http\Request;

class ActivityBudgetController extends Controller
{
    /**
     * Abort with 403 unless the current user owns one of the given stage roles (or is superadmin).
     */
    private function authorizeStage(array $roles): void
    {
        abort_unless(auth()->user()->hasAnyRole([...$roles, 'superadmin']), 403);
    }

    /**
     * Perencanaan records the approved (diterima) budget amount.
     * Only writable while the activity is at the perencanaan stage.
     */
    public function updateDiterima(Request $request, $kegiatanId)
    {
        $this->authorizeStage(['perencanaan']);

        $activity = Activity::findOrFail($kegiatanId);

        abort_unless($activity->currentStage() === 'perencanaan', 403);

        $validated = $request->validate([
            'budget_diterima' => 'required|numeric|min:0',
        ]);

        $activity->update($validated);

        return redirect()->route('kegiatan.show', ['kegiatan' => $activity->id, 'tab' => 'kegiatan'])
            ->with('success', 'Anggaran diterima berhasil disimpan.');
    }

    /**
     * Penyelenggara records the realized (diserap) budget amount.
     * Only writable while the activity is at the penyelenggara stage.
     */
    public function updateDiserap(Request $request, $kegiatanId)
    {
        $this->authorizeStage(['penyelenggara']);

        $activity = Activity::findOrFail($kegiatanId);

        abort_unless($activity->currentStage() === 'penyelenggara', 403);

        $validated = $request->validate([
            'budget_diserap' => 'required|numeric|min:0',
        ]);

        $activity->update($validated);

        return redirect()->route('kegiatan.show', ['kegiatan' => $activity->id, 'tab' => 'kegiatan'])
            ->with('success', 'Anggaran diserap berhasil disimpan.');
    }
}
