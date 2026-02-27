<?php

namespace App\Http\Controllers\Act;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
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
     * Show the form for creating a new resource.
     */
    public function create() {}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $activityMaterial = ActivityMaterial::create($request->all());
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
     * Show the form for editing the specified resource.
     */
    public function edit() {}

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $activityMaterial = ActivityMaterial::find($id);

        if (!$activityMaterial) {
            return response()->json(['message' => 'Activity Material not found'], 404);
        }

        $activityMaterial->update($request->all());
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
