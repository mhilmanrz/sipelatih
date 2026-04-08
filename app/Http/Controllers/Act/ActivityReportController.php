<?php

namespace App\Http\Controllers\Act;

use App\Http\Controllers\Controller;
use App\Models\Act\Activity;
use App\Models\Act\ActivityReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ActivityReportController extends Controller
{
    public function index()
    {
        $activities = Activity::with(['report', 'activityName'])->get();

        return view('laporanKegiatan', compact('activities'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'activity_id' => 'required|exists:activities,id',
            'file' => 'required|file|mimes:pdf,doc,docx,xls,xlsx|max:10240',
        ]);

        $file = $request->file('file');
        $filename = time().'_'.$file->getClientOriginalName();
        $path = $file->storeAs('reports', $filename, 'public');

        ActivityReport::create([
            'activity_id' => $request->activity_id,
            'file_path' => $path,
        ]);

        return back()->with('success', 'Laporan berhasil diupload.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'file' => 'required|file|mimes:pdf,doc,docx,xls,xlsx|max:10240',
        ]);

        $report = ActivityReport::findOrFail($id);

        // Delete old file
        if (Storage::disk('public')->exists($report->file_path)) {
            Storage::disk('public')->delete($report->file_path);
        }

        $file = $request->file('file');
        $filename = time().'_'.$file->getClientOriginalName();
        $path = $file->storeAs('reports', $filename, 'public');

        $report->update([
            'file_path' => $path,
        ]);

        return back()->with('success', 'Laporan berhasil diperbarui.');
    }

    public function downloadTemplate()
    {
        $filename = 'Template_Laporan_Kegiatan.docx';
        $content = 'Ini adalah template laporan kegiatan dummy.';

        return response()->streamDownload(function () use ($content) {
            echo $content;
        }, $filename);
    }
}
