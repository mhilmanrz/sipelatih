<?php

namespace App\Http\Controllers\Act;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Act\ActivityModerator;

class ActivityModeratorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $activityModerators = ActivityModerator::paginate(10);
        return response()->json($activityModerators);
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
        $activityModerator = ActivityModerator::create($request->all());
        return response()->json($activityModerator, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $activityModerator = ActivityModerator::find($id);

        if (!$activityModerator) {
            return response()->json(['message' => 'Activity Moderator not found'], 404);
        }

        return response()->json($activityModerator);
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
        $activityModerator = ActivityModerator::find($id);

        if (!$activityModerator) {
            return response()->json(['message' => 'Activity Moderator not found'], 404);
        }

        $activityModerator->update($request->all());
        return response()->json($activityModerator);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $activityModerator = ActivityModerator::find($id);

        if (!$activityModerator) {
            return response()->json(['message' => 'Activity Moderator not found'], 404);
        }

        $activityModerator->delete();
        return response()->json(['message' => 'Activity Moderator deleted successfully'], 200);
    }
}
