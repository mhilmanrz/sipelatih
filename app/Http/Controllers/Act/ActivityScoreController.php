<?php

namespace App\Http\Controllers\Act;

use App\Exports\ActivityScoreTemplateExport;
use App\Http\Controllers\Controller;
use App\Jobs\ImportActivityScoreJob;
use App\Models\Act\Activity;
use App\Models\Act\ActivityParticipant;
use App\Models\Act\ActivityScore;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ActivityScoreController extends Controller
{
    public function update(Request $request, $kegiatan_id, $participant_id)
    {
        $request->validate([
            'pre_test_score' => 'nullable|integer|min:0|max:100',
            'post_test_score' => 'nullable|integer|min:0|max:100',
            'practice_score' => 'nullable|integer|min:0|max:100',
            'is_passed' => 'required|boolean',
        ]);

        $participant = ActivityParticipant::where('activity_id', $kegiatan_id)
            ->findOrFail($participant_id);

        $participant->update([
            'is_passed' => $request->is_passed,
        ]);

        ActivityScore::updateOrCreate(
            ['activity_participant_id' => $participant->id],
            [
                'pre_test_score' => $request->pre_test_score,
                'post_test_score' => $request->post_test_score,
                'practice_score' => $request->practice_score,
            ]
        );

        return back()->with('success', 'Nilai peserta berhasil diperbarui.');
    }

    public function downloadTemplate($kegiatan_id)
    {
        return Excel::download(new ActivityScoreTemplateExport, 'Template_Import_Nilai.xlsx');
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
