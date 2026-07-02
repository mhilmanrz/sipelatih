<?php

namespace App\Http\Controllers\Act;

use App\Exports\MaterialTemplateExport;
use App\Http\Controllers\Controller;
use App\Jobs\ImportMaterialJob;
use App\Models\Act\Activity;
use App\Models\Act\ActivityMaterial;
use App\Models\Act\MaterialImportLog;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ActivityMaterialController extends Controller
{
    /**
     * Show the form to import materi.
     */
    public function importPage($kegiatanId)
    {
        $kegiatan = Activity::findOrFail($kegiatanId);
        $logs = MaterialImportLog::where('activity_id', $kegiatanId)->latest()->get();

        return view('usulan.detail.import_materi', compact('kegiatan', 'logs'));
    }

    /**
     * Process excel file and import.
     */
    public function importStore(Request $request, $kegiatanId)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048',
        ]);

        $activity = Activity::findOrFail($kegiatanId);

        try {
            $file = $request->file('file');
            $filename = $file->getClientOriginalName();
            $path = $file->store('imports', 'local');

            $importLog = MaterialImportLog::create([
                'activity_id' => $activity->id,
                'filename' => $filename,
                'status' => 'pending',
                'total_rows' => 0,
                'success_count' => 0,
                'failed_count' => 0,
            ]);

            ImportMaterialJob::dispatch($path, $activity->id, $importLog->id);

            return redirect()->route('kegiatan.materi.import.page', ['kegiatan' => $activity->id])
                ->with('success', 'Data materi sedang diimpor di antrean (background). Harap periksa riwayat di bawah.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat upload untuk impor: '.$e->getMessage());
        }
    }

    /**
     * Download Excel template.
     */
    public function downloadTemplate()
    {
        return Excel::download(new MaterialTemplateExport, 'Template_Import_Materi.xlsx');
    }

    /**
     * Store a newly created materi in storage.
     */
    public function store(Request $request, $kegiatanId)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'value' => 'required|numeric|min:0.1', // JPL
        ]);

        $activity = Activity::findOrFail($kegiatanId);

        ActivityMaterial::create([
            'activity_id' => $activity->id,
            'name' => $request->name,
            'value' => $request->value,
        ]);

        return redirect()->route('kegiatan.show', ['kegiatan' => $activity->id, 'tab' => 'materi'])
            ->with('success', 'Materi berhasil ditambahkan.');
    }

    /**
     * Update the specified materi in storage.
     */
    public function update(Request $request, $kegiatanId, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'value' => 'required|numeric|min:0.1', // JPL
        ]);

        $activityMaterial = ActivityMaterial::where('activity_id', $kegiatanId)
            ->findOrFail($id);

        $activityMaterial->update([
            'name' => $request->name,
            'value' => $request->value,
        ]);

        return redirect()->route('kegiatan.show', ['kegiatan' => $kegiatanId, 'tab' => 'materi'])
            ->with('success', 'Materi berhasil diperbarui.');
    }

    /**
     * Remove the specified materi from storage.
     */
    public function destroy($kegiatanId, $id)
    {
        $activityMaterial = ActivityMaterial::where('activity_id', $kegiatanId)
            ->findOrFail($id);

        $activityMaterial->delete();

        return redirect()->route('kegiatan.show', ['kegiatan' => $kegiatanId, 'tab' => 'materi'])
            ->with('success', 'Materi berhasil dihapus.');
    }
}
