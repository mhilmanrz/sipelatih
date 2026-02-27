<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use Illuminate\Http\Request;

class BudgetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $budgets = Budget::with('budgetCategory')->paginate(10);
        return response()->json($budgets);
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
        $budget = Budget::create($request->all());
        return response()->json($budget->load('budgetCategory'), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $budget = Budget::with('budgetCategory')->find($id);

        if (!$budget) {
            return response()->json(['message' => 'Budget not found'], 404);
        }

        return response()->json($budget);
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
        $budget = Budget::find($id);

        if (!$budget) {
            return response()->json(['message' => 'Budget not found'], 404);
        }

        $budget->update($request->all());
        return response()->json($budget->load('budgetCategory'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $budget = Budget::find($id);

        if (!$budget) {
            return response()->json(['message' => 'Budget not found'], 404);
        }

        $budget->delete();
        return response()->json(['message' => 'Budget deleted successfully'], 200);
    }
}
