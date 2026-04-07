<?php

namespace App\Http\Controllers\Act;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Act\Activity;
use App\Models\Act\ActivityKakFile;
use Illuminate\Support\Facades\Storage;

class ActivityKakController extends Controller
{
    /**
     * Upload PDF KAK untuk kegiatan
     */
    public function store(Request $request, $kegiatanId)
    {
        $request->validate([
            'kak_file' => 'required|file|mimes:pdf|max:10240',
        ], [
            'kak_file.mimes' => 'File harus berformat PDF.',
            'kak_file.max' => 'Ukuran file maksimal 10MB.',
        ]);

        $activity = Activity::findOrFail($kegiatanId);

        // Hapus file lama jika ada
        $existing = ActivityKakFile::where('activity_id', $activity->id)->first();
        if ($existing) {
            Storage::disk('public')->delete($existing->url);
            $existing->delete();
        }

        // Simpan file baru
        $path = $request->file('kak_file')->store("kak/{$kegiatanId}", 'public');

        ActivityKakFile::create([
            'activity_id' => $activity->id,
            'url' => $path,
        ]);

        return redirect()->route('kegiatan.show', ['kegiatan' => $kegiatanId, 'tab' => 'kak'])
            ->with('success', 'Dokumen KAK berhasil diunggah.');
    }

    /**
     * Hapus file KAK
     */
    public function destroy($kegiatanId, $kakId)
    {
        $kak = ActivityKakFile::where('activity_id', $kegiatanId)->findOrFail($kakId);
        Storage::disk('public')->delete($kak->url);
        $kak->delete();

        return redirect()->route('kegiatan.show', ['kegiatan' => $kegiatanId, 'tab' => 'kak'])
            ->with('success', 'Dokumen KAK berhasil dihapus.');
    }

    /**
     * Unduh template Word KAK
     */
    public function downloadTemplate()
    {
        $templatePath = public_path('templates/Template_KAK.docx');

        if (!file_exists($templatePath)) {
            return redirect()->back()->withErrors(['template' => 'Template belum tersedia. Silahkan hubungi admin.']);
        }

        return response()->download($templatePath, 'Template_KAK.docx');
    }
}
