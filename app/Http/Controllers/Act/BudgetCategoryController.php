<?php

namespace App\Http\Controllers\Act;

use App\Http\Controllers\Controller;
use App\Models\BudgetCategory;
use Illuminate\Http\Request;

class BudgetCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = BudgetCategory::query();
        if ($request->has('q') && $request->q != '') {
            $query->where('name', 'like', '%' . $request->q . '%');
        }
        $perPage = $request->input('entries', $request->input('per_page', 10));
        $categoryPagus = $query->paginate($perPage)->appends($request->all());

        return view('budget_categories.index', compact('categoryPagus'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('budget_categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50',
        ]);

        BudgetCategory::create($request->all());

        return redirect()->route('budget-categories.index')->with('success', 'Kategori Pagu berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        return redirect()->route('budget-categories.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $categoryPagu = BudgetCategory::findOrFail($id);

        return view('budget_categories.edit', compact('categoryPagu'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50',
        ]);

        $categoryPagu = BudgetCategory::findOrFail($id);
        $categoryPagu->update($request->all());

        return redirect()->route('budget-categories.index')->with('success', 'Kategori Pagu berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $categoryPagu = BudgetCategory::findOrFail($id);
        $categoryPagu->delete();

        return redirect()->route('budget-categories.index')->with('success', 'Kategori Pagu berhasil dihapus.');
    }
}
