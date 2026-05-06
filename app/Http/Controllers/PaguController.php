<?php

namespace App\Http\Controllers;

use App\Exports\BudgetTemplateExport;
use App\Jobs\ImportBudgetJob;
use App\Models\Act\Activity;
use App\Models\Budget;
use App\Models\BudgetCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;

class PaguController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $selectedYear = $request->input('year');
        $searchQuery = $request->input('q');
        $perPage = $request->input('per_page', 10);

        $query = Budget::with('budgetCategory');

        if ($selectedYear) {
            $query->where('year', $selectedYear);
        }

        if ($searchQuery) {
            $query->where(function ($q) use ($searchQuery) {
                $q->where('rkkal_code', 'like', "%{$searchQuery}%")
                    ->orWhere('submark', 'like', "%{$searchQuery}%")
                    ->orWhereHas('budgetCategory', function ($qCat) use ($searchQuery) {
                        $qCat->where('name', 'like', "%{$searchQuery}%");
                    });
            });
        }

        // Hitung sumari berdasarkan filter sebelum dipaginasi
        $summaryQuery = clone $query;
        $allBudgets = $summaryQuery->get();

        $totalDana = $allBudgets->sum('total_amount');
        $budgetIds = $allBudgets->pluck('id');
        $totalTerserap = Activity::whereIn('budget_id', $budgetIds)->sum('budget_amount');
        $totalSisa = $totalDana - $totalTerserap;

        // Ambil data untuk tabel dengan paginasi
        $budgets = $query->paginate($perPage)->withQueryString();
        $categories = BudgetCategory::all();

        $availableYears = Budget::select('year')
            ->whereNotNull('year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year')
            ->toArray();

        if (empty($availableYears)) {
            $availableYears = [date('Y')];
        } elseif (! in_array(date('Y'), $availableYears)) {
            $availableYears[] = date('Y');
            rsort($availableYears);
        }

        // Data for Bar Chart Per RKAKL
        $chartYear = $selectedYear ?: (empty($availableYears) ? date('Y') : max($availableYears));
        $budgetsForChart = Budget::with('activities')->where('year', $chartYear)->get();

        $rkaklLabels = [];
        $rkaklDigunakan = [];
        $rkaklSisa = [];

        foreach ($budgetsForChart as $b) {
            $used = $b->activities->sum('budget_amount');
            // Sisa berdasarkan Pagu Efektif (sudah dikurangi Blokir)
            $sisa = $b->efektif_amount - $used;

            $label = $b->rkkal_code;
            if ($b->submark) {
                $label .= ' - '.Str::limit($b->submark, 20);
            }

            $rkaklLabels[] = $label;
            $rkaklDigunakan[] = $used;
            $rkaklSisa[] = $sisa < 0 ? 0 : $sisa;
        }

        return view('pagu', compact(
            'budgets', 'categories', 'selectedYear', 'availableYears',
            'totalDana', 'totalTerserap', 'totalSisa',
            'chartYear', 'rkaklLabels', 'rkaklDigunakan', 'rkaklSisa'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'year' => 'required|integer|min:2000',
            'rkkal_code' => [
                'required',
                'string',
                'max:255',
                Rule::unique('budgets')->where(function ($query) use ($request) {
                    return $query->where('year', $request->year);
                }),
            ],
            'budget_category_id' => 'required|exists:budget_categories,id',
            'submark' => 'nullable|string|max:255',
            'total_amount' => 'required|numeric|min:0',
            'blokir' => 'nullable|numeric|min:0',
        ], [
            'rkkal_code.unique' => 'Pagu dengan kombinasi Kode RKKAL dan Tahun Anggaran tersebut sudah ada.',
        ]);

        Budget::create($request->only(['year', 'rkkal_code', 'budget_category_id', 'submark', 'total_amount', 'blokir']));

        return redirect()->route('pagu.index')->with('success', 'Pagu berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Budget $pagu)
    {
        $pagu->load([
            'budgetCategory',
            'activities.activityName',
            'activities.workUnit',
            'activities.picUser',
        ]);

        return view('paguDetail', compact('pagu'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Budget $pagu)
    {
        $request->validate([
            'year' => 'required|integer|min:2000',
            'rkkal_code' => [
                'required',
                'string',
                'max:255',
                Rule::unique('budgets')->where(function ($query) use ($request) {
                    return $query->where('year', $request->year);
                })->ignore($pagu->id),
            ],
            'budget_category_id' => 'required|exists:budget_categories,id',
            'submark' => 'nullable|string|max:255',
            'total_amount' => 'required|numeric|min:0',
            'blokir' => 'nullable|numeric|min:0',
        ], [
            'rkkal_code.unique' => 'Pagu dengan kombinasi Kode RKKAL dan Tahun Anggaran tersebut sudah ada.',
        ]);

        $pagu->update($request->only(['year', 'rkkal_code', 'budget_category_id', 'submark', 'total_amount', 'blokir']));

        return redirect()->route('pagu.index')->with('success', 'Pagu berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Budget $pagu)
    {
        $pagu->delete();

        return redirect()->route('pagu.index')->with('success', 'Pagu berhasil dihapus.');
    }

    /**
     * Download the import template.
     */
    public function downloadTemplate()
    {
        return Excel::download(new BudgetTemplateExport, 'Template_Import_Pagu.xlsx');
    }

    /**
     * Show import page.
     */
    public function importPage()
    {
        return view('paguImport');
    }

    /**
     * Store bulk import via queue.
     */
    public function importStore(Request $request)
    {
        $request->validate([
            'file_excel' => 'required|mimes:xlsx,xls|max:5120',
        ]);

        if ($request->hasFile('file_excel')) {
            $file = $request->file('file_excel');
            // Check original name to avoid overriding random chunks if same name is processed
            $filename = time().'_'.$file->getClientOriginalName();

            // Simpan sementara di storage/app/private/imports
            $filePath = $file->storeAs('imports', $filename, 'local');

            // Dispatch proses ke queue
            ImportBudgetJob::dispatch($filePath);

            return redirect()->back()->with('success', 'File Excel berhasil diunggah. Proses impor berjalan di latar belakang.');
        }

        return redirect()->back()->withErrors(['file_excel' => 'Gagal mengunggah file.']);
    }
}
