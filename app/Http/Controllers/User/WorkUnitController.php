<?php

namespace App\Http\Controllers\User;

use App\Models\User\WorkUnit;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class WorkUnitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $workUnits = WorkUnit::paginate(10);
        return response()->json($workUnits);
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
        $workUnit = WorkUnit::create($request->all());
        return response()->json($workUnit, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $workUnit = WorkUnit::find($id);

        if (!$workUnit) {
            return response()->json(['message' => 'Work Unit not found'], 404);
        }

        return response()->json($workUnit);
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
        $workUnit = WorkUnit::find($id);

        if (!$workUnit) {
            return response()->json(['message' => 'Work Unit not found'], 404);
        }

        $workUnit->update($request->all());
        return response()->json($workUnit);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $workUnit = WorkUnit::find($id);

        if (!$workUnit) {
            return response()->json(['message' => 'Work Unit not found'], 404);
        }

        $workUnit->delete();
        return response()->json(['message' => 'Work Unit deleted successfully'], 200);
    }
}
