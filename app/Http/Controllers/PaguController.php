<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Budget;
use App\Models\BudgetCategory;

class PaguController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $selectedYear = $request->input('year');
        
        $query = Budget::with('budgetCategory');
        if ($selectedYear) {
            $query->where('year', $selectedYear);
        }
        
        $budgets = $query->get();
        $categories = BudgetCategory::all();
        
        $availableYears = Budget::select('year')
                            ->whereNotNull('year')
                            ->distinct()
                            ->orderBy('year', 'desc')
                            ->pluck('year')
                            ->toArray();
                            
        if (empty($availableYears)) {
            $availableYears = [date('Y')];
        } else if (!in_array(date('Y'), $availableYears)) {
            $availableYears[] = date('Y');
            rsort($availableYears);
        }
        
        return view('pagu', compact('budgets', 'categories', 'selectedYear', 'availableYears'));
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
                \Illuminate\Validation\Rule::unique('budgets')->where(function ($query) use ($request) {
                    return $query->where('year', $request->year);
                })
            ],
            'budget_category_id' => 'required|exists:budget_categories,id',
            'submark' => 'nullable|string|max:255',
            'total_amount' => 'required|numeric|min:0',
        ], [
            'rkkal_code.unique' => 'Pagu dengan kombinasi Kode RKKAL dan Tahun Anggaran tersebut sudah ada.',
        ]);

        $data = $request->all();
        // Since it's a new pagu, remaining amount is equal to total at first
        $data['remaining_amount'] = $request->total_amount;

        Budget::create($data);

        return redirect()->route('pagu.index')->with('success', 'Pagu berhasil ditambahkan.');
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
                \Illuminate\Validation\Rule::unique('budgets')->where(function ($query) use ($request) {
                    return $query->where('year', $request->year);
                })->ignore($pagu->id)
            ],
            'budget_category_id' => 'required|exists:budget_categories,id',
            'submark' => 'nullable|string|max:255',
            'total_amount' => 'required|numeric|min:0',
        ], [
            'rkkal_code.unique' => 'Pagu dengan kombinasi Kode RKKAL dan Tahun Anggaran tersebut sudah ada.',
        ]);

        // Calculate difference if total_amount is being updated
        $diff = $request->total_amount - $pagu->total_amount;
        
        $data = $request->all();
        // Adjust remaining_amount by the same difference
        $data['remaining_amount'] = $pagu->remaining_amount + $diff;

        $pagu->update($data);

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
}
