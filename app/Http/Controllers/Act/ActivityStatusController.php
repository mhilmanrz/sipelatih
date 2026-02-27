<?php

namespace App\Http\Controllers\Act;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Act\ActivityStatus;

class ActivityStatusController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $activityStatuses = ActivityStatus::paginate(10);
        return response()->json($activityStatuses);
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
        $activityStatus = ActivityStatus::create($request->all());
        return response()->json($activityStatus, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $activityStatus = ActivityStatus::find($id);

        if (!$activityStatus) {
            return response()->json(['message' => 'Activity Status not found'], 404);
        }

        return response()->json($activityStatus);
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
        $activityStatus = ActivityStatus::find($id);

        if (!$activityStatus) {
            return response()->json(['message' => 'Activity Status not found'], 404);
        }

        $activityStatus->update($request->all());
        return response()->json($activityStatus);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $activityStatus = ActivityStatus::find($id);

        if (!$activityStatus) {
            return response()->json(['message' => 'Activity Status not found'], 404);
        }

        $activityStatus->delete();
        return response()->json(['message' => 'Activity Status deleted successfully'], 200);
    }
}
