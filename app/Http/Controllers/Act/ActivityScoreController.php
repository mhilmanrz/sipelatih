<?php

namespace App\Http\Controllers\Act;

use App\Exports\ActivityScoreTemplateExport;
use App\Http\Controllers\Controller;
use App\Jobs\ImportActivityScoreJob;
use App\Models\Act\Activity;
use App\Models\Act\ActivityComponentScore;
use App\Models\Act\ActivityParticipant;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ActivityScoreController extends Controller
{
    public function update(Request $request, $kegiatan_id, $participant_id)
    {
        $kegiatan = Activity::with('scoreComponents', 'scoreSetting')->findOrFail($kegiatan_id);
        $participant = ActivityParticipant::where('activity_id', $kegiatan_id)->findOrFail($participant_id);

        $components = $kegiatan->scoreComponents;

        if ($components->isEmpty()) {
            return back()->withErrors(['score' => 'Pengaturan komponen penilaian belum dikonfigurasi.']);
        }

        // Validasi dinamis per komponen
        $rules = [];
        foreach ($components as $component) {
            $rules["score_{$component->id}"] = 'nullable|numeric|min:0|max:100';
        }
        $validated = $request->validate($rules);

        // Simpan nilai per komponen
        foreach ($components as $component) {
            $scoreValue = $validated["score_{$component->id}"] ?? null;
            ActivityComponentScore::updateOrCreate(
                [
                    'activity_participant_id' => $participant->id,
                    'activity_score_component_id' => $component->id,
                ],
                ['score' => $scoreValue]
            );
        }

        // Hitung nilai akhir dan update is_passed
        $participant->load('componentScores');
        $finalScore = $participant->calculateFinalScore();
        $threshold = $kegiatan->scoreSetting?->passing_threshold ?? 70;

        $participant->update([
            'is_passed' => $finalScore !== null && $finalScore >= $threshold,
        ]);

        return back()->with('success', 'Nilai peserta berhasil diperbarui.');
    }

    public function downloadTemplate($kegiatan_id)
    {
        return Excel::download(new ActivityScoreTemplateExport($kegiatan_id), 'Template_Import_Nilai.xlsx');
    }

    public function importPage($kegiatan_id)
    {
        $kegiatan = Activity::findOrFail($kegiatan_id);

        return view('act.import_nilai', compact('kegiatan'));
    }

    public function importStore(Request $request, $kegiatan_id)
    {
        $request->validate([
            'file_excel' => 'required|mimes:xlsx,xls,csv|max:5120',
        ]);

        if ($request->hasFile('file_excel')) {
            $file = $request->file('file_excel');
            $filename = time().'_'.$file->getClientOriginalName();

            $filePath = $file->storeAs('imports', $filename, 'local');

            ImportActivityScoreJob::dispatch($filePath, $kegiatan_id);

            return redirect()->route('kegiatan.show', ['kegiatan' => $kegiatan_id, 'tab' => 'input-nilai'])
                ->with('success', 'File Excel nilai berhasil diunggah. Proses impor berjalan di latar belakang (queue). Harap tunggu beberapa saat.');
        }

        return redirect()->back()->withErrors(['file_excel' => 'Gagal mengunggah file.']);
    }
}
