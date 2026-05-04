<?php

namespace App\Http\Controllers;

use App\Jobs\ImportActivityNameJob;
use App\Models\Act\ActivityName;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Facades\Excel;

class ActivityNameController extends Controller
{
    public function index(Request $request)
    {
        $query = ActivityName::query();
        if ($request->has('q') && $request->q != '') {
            $query->where('name', 'like', '%'.$request->q.'%');
        }
        $perPage = $request->input('entries', $request->input('per_page', 10));
        $activityNames = $query->paginate($perPage)->appends($request->all());

        return view('dictionaries.activity_names.index', compact('activityNames'));
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048',
        ]);

        try {
            $path = $request->file('file')->store('imports', 'local');
            ImportActivityNameJob::dispatch($path);

            return redirect()->route('activity-names.index')->with('success', 'Nama Kegiatan sedang diimpor di antrean (background). Harap periksa beberapa saat lagi.');
        } catch (\Exception $e) {
            return redirect()->route('activity-names.index')->with('error', 'Gagal mengupload file untuk impor: '.$e->getMessage());
        }
    }

    public function downloadTemplate()
    {
        $header = ['nama_kegiatan', 'start_date', 'end_date', 'year'];
        $data = [
            ['Contoh Nama Kegiatan 1', '2026-05-01', '2026-05-10', '2026'],
            ['Contoh Nama Kegiatan 2', null, null, null],
        ];

        return Excel::download(new class($header, $data) implements FromCollection, WithHeadings
        {
            protected $header;

            protected $data;

            public function __construct($header, $data)
            {
                $this->header = $header;
                $this->data = $data;
            }

            public function collection()
            {
                return collect($this->data);
            }

            public function headings(): array
            {
                return $this->header;
            }
        }, 'template_nama_kegiatan.xlsx');
    }

    public function create()
    {
        return view('dictionaries.activity_names.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'year' => 'nullable|integer',
        ]);
        ActivityName::create($request->all());

        return redirect()->route('activity-names.index')->with('success', 'Nama Kegiatan berhasil ditambahkan.');
    }

    public function edit(ActivityName $activityName)
    {
        return view('dictionaries.activity_names.edit', compact('activityName'));
    }

    public function update(Request $request, ActivityName $activityName)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'year' => 'nullable|integer',
        ]);
        $activityName->update($request->all());

        return redirect()->route('activity-names.index')->with('success', 'Nama Kegiatan berhasil diperbarui.');
    }

    public function destroy(ActivityName $activityName)
    {
        $activityName->delete();

        return redirect()->route('activity-names.index')->with('success', 'Nama Kegiatan berhasil dihapus.');
    }
}
