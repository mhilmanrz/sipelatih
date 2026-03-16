<?php

namespace App\Http\Controllers\Act;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreActivityMaterialRequest;
use App\Http\Requests\UpdateActivityMaterialRequest;
use App\Models\Act\ActivityMaterial;

class ActivityMaterialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $activityMaterials = ActivityMaterial::paginate(10);
        return response()->json($activityMaterials);
    }

    /**
     * Get all materials for a specific activity, with total JPL.
     */
    public function getByActivity($activityId)
    {
        $materials = ActivityMaterial::where('activity_id', $activityId)->get();
        $totalJpl = $materials->sum('jpl');

        return response()->json([
            'materials' => $materials,
            'total_jpl' => round($totalJpl, 1),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreActivityMaterialRequest $request)
    {
        $activityMaterial = ActivityMaterial::create($request->validated());

        return response()->json($activityMaterial, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $activityMaterial = ActivityMaterial::find($id);

        if (!$activityMaterial) {
            return response()->json(['message' => 'Activity Material not found'], 404);
        }

        return response()->json($activityMaterial);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateActivityMaterialRequest $request, string $id)
    {
        $activityMaterial = ActivityMaterial::find($id);

        if (!$activityMaterial) {
            return response()->json(['message' => 'Activity Material not found'], 404);
        }

        $activityMaterial->update($request->validated());
        return response()->json($activityMaterial);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $activityMaterial = ActivityMaterial::find($id);

        if (!$activityMaterial) {
            return response()->json(['message' => 'Activity Material not found'], 404);
        }

        $activityMaterial->delete();
        return response()->json(['message' => 'Activity Material deleted successfully'], 200);
    }
}
