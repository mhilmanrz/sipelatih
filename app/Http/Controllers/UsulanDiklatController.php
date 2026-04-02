<?php

namespace App\Http\Controllers;

use App\Models\Act\Activity;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ActivityImport;
use App\Imports\ActivityPerParticipantImport;
use App\Exports\ActivityTemplateExport;
use App\Exports\ActivityPerParticipantTemplateExport;

class UsulanDiklatController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->input('entries', 10);
        $search = $request->input('search');

        $query = Activity::with(['activityName', 'activityType', 'materialType', 'latestStatus', 'workUnit']);

        if ($search) {
            $query->whereHas('activityName', function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%');
            })
                ->orWhereHas('workUnit', function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%');
                });
        }

        $kegiatan = $query->paginate($perPage);

        return view('usulan.usulan', compact('kegiatan'));
    }

    public function importPage()
    {
        return view('usulan.import_kegiatan');
    }

    public function importStore(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:10240',
        ]);

        try {
            Excel::import(new ActivityImport, $request->file('file'));
            return redirect()->route('usulan-diklat')->with('success', 'Data kegiatan berhasil diimport');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat import: ' . $e->getMessage());
        }
    }

    public function downloadTemplate()
    {
        return Excel::download(new ActivityTemplateExport, 'Template_Import_Kegiatan.xlsx');
    }

    public function importPerPesertaPage()
    {
        return view('usulan.import_kegiatan_per_peserta');
    }

    public function importPerPesertaStore(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:10240',
        ]);

        try {
            Excel::import(new ActivityPerParticipantImport, $request->file('file'));
            return redirect()->route('usulan-diklat')->with('success', 'Data kegiatan per peserta berhasil diimport');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat import: ' . $e->getMessage());
        }
    }

    public function downloadTemplatePerPeserta()
    {
        return Excel::download(new ActivityPerParticipantTemplateExport, 'Template_Import_Kegiatan_Per_Peserta.xlsx');
    }
}
