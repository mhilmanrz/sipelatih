<?php

namespace App\Http\Controllers\Act;

use App\Http\Controllers\Controller;
use App\Models\Act\ActivityType;
use Illuminate\Http\Request;

class ActivityTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $activityTypes = ActivityType::paginate(10);
        return response()->json($activityTypes);
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
        $activityType = ActivityType::create($request->all());
        return response()->json($activityType, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $activityType = ActivityType::find($id);

        if (!$activityType) {
            return response()->json(['message' => 'Activity Type not found'], 404);
        }

        return response()->json($activityType);
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
        $activityType = ActivityType::find($id);

        if (!$activityType) {
            return response()->json(['message' => 'Activity Type not found'], 404);
        }

        $activityType->update($request->all());
        return response()->json($activityType);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $activityType = ActivityType::find($id);

        if (!$activityType) {
            return response()->json(['message' => 'Activity Type not found'], 404);
        }

        $activityType->delete();
        return response()->json(['message' => 'Activity Type deleted successfully'], 200);
    }
}
