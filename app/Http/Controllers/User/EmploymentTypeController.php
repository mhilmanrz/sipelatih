<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User\EmploymentType;
use Illuminate\Http\Request;

class EmploymentTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $employmentTypes = EmploymentType::paginate(10);
        return response()->json($employmentTypes);
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
        $employmentType = EmploymentType::create($request->all());
        return response()->json($employmentType, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $employmentType = EmploymentType::find($id);

        if (!$employmentType) {
            return response()->json(['message' => 'Employment Type not found'], 404);
        }

        return response()->json($employmentType);
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
        $employmentType = EmploymentType::find($id);

        if (!$employmentType) {
            return response()->json(['message' => 'Employment Type not found'], 404);
        }

        $employmentType->update($request->all());
        return response()->json($employmentType);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $employmentType = EmploymentType::find($id);

        if (!$employmentType) {
            return response()->json(['message' => 'Employment Type not found'], 404);
        }

        $employmentType->delete();
        return response()->json(['message' => 'Employment Type deleted successfully'], 200);
    }
}
