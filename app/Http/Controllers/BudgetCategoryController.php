<?php

namespace App\Http\Controllers;

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
        $budgetCategories = $query->paginate($perPage)->appends($request->all());

        return response()->json($budgetCategories);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $budgetCategory = BudgetCategory::create($request->all());

        return response()->json($budgetCategory, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $budgetCategory = BudgetCategory::find($id);

        if (! $budgetCategory) {
            return response()->json(['message' => 'Budget Category not found'], 404);
        }

        return response()->json($budgetCategory);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit() {}

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $budgetCategory = BudgetCategory::find($id);

        if (! $budgetCategory) {
            return response()->json(['message' => 'Budget Category not found'], 404);
        }

        $budgetCategory->update($request->all());

        return response()->json($budgetCategory);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $budgetCategory = BudgetCategory::find($id);

        if (! $budgetCategory) {
            return response()->json(['message' => 'Budget Category not found'], 404);
        }

        $budgetCategory->delete();

        return response()->json(['message' => 'Budget Category deleted successfully'], 200);
    }
}
