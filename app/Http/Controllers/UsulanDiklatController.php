<?php

namespace App\Http\Controllers;

use App\Exports\ActivityPerParticipantTemplateExport;
use App\Exports\ActivityTemplateExport;
use App\Jobs\ImportActivityJob;
use App\Jobs\ImportActivityParticipantJob;
use App\Models\Act\Activity;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class UsulanDiklatController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->input('entries', 10);
        $search = $request->input('search');
        $year = $request->input('year');
        $status = $request->input('status');

        $query = Activity::with(['activityName', 'activityType', 'materialType', 'latestStatus', 'workUnit']);

        if ($search) {
            $query->whereHas('activityName', function ($q) use ($search) {
                $q->where('name', 'like', '%'.$search.'%');
            })
                ->orWhereHas('workUnit', function ($q) use ($search) {
                    $q->where('name', 'like', '%'.$search.'%');
                });
        }

        if ($year) {
            $query->where(function ($q) use ($year) {
                $q->whereYear('end_date', $year)
                    ->orWhereYear('start_date', $year)
                    ->orWhereYear('date', $year);
            });
        }

        if ($status) {
            if ($status === 'draft') {
                $query->whereDoesntHave('latestStatus');
            } else {
                $query->whereHas('latestStatus', function ($q) use ($status) {
                    $q->where('status', $status);
                });
            }
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
            $path = $request->file('file')->store('imports', 'local');
            ImportActivityJob::dispatch($path);

            return redirect()->route('usulan-diklat')->with('success', 'Data kegiatan sedang diimport di antrean (background). Harap periksa beberapa saat lagi.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat upload untuk import: '.$e->getMessage());
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
            $path = $request->file('file')->store('imports', 'local');
            ImportActivityParticipantJob::dispatch($path);

            return redirect()->route('usulan-diklat')->with('success', 'Data kegiatan per peserta sedang diimport di antrean (background). Harap periksa beberapa saat lagi.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat upload untuk import: '.$e->getMessage());
        }
    }

    public function downloadTemplatePerPeserta()
    {
        return Excel::download(new ActivityPerParticipantTemplateExport, 'Template_Import_Kegiatan_Per_Peserta.xlsx');
    }
}
