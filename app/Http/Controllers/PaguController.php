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
    public function index()
    {
        $budgets = Budget::with('budgetCategory')->get();
        $categories = BudgetCategory::all();
        
        return view('pagu', compact('budgets', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'rkkal_code' => 'required|string|max:255',
            'budget_category_id' => 'required|exists:budget_categories,id',
            'submark' => 'nullable|string|max:255',
            'total_amount' => 'required|numeric|min:0',
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
            'rkkal_code' => 'required|string|max:255',
            'budget_category_id' => 'required|exists:budget_categories,id',
            'submark' => 'nullable|string|max:255',
            'total_amount' => 'required|numeric|min:0',
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
