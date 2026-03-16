<?php

namespace App\Http\Controllers\Act;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreActivityProfessionRequest;
use App\Http\Requests\UpdateActivityProfessionRequest;
use App\Models\Act\ActivityProfession;

class ActivityProfessionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $activityProfessions = ActivityProfession::with('profession')->paginate(10);
        return response()->json($activityProfessions);
    }

    /**
     * Get all professions for a specific activity.
     */
    public function getByActivity($activityId)
    {
        $activityProfessions = ActivityProfession::with('profession')
            ->where('activity_id', $activityId)
            ->get();

        return response()->json($activityProfessions);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreActivityProfessionRequest $request)
    {
        $activityProfession = ActivityProfession::create($request->validated());
        $activityProfession->load('profession');

        return response()->json($activityProfession, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $activityProfession = ActivityProfession::with('profession')->find($id);

        if (!$activityProfession) {
            return response()->json(['message' => 'Activity Profession not found'], 404);
        }

        return response()->json($activityProfession);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateActivityProfessionRequest $request, string $id)
    {
        $activityProfession = ActivityProfession::find($id);

        if (!$activityProfession) {
            return response()->json(['message' => 'Activity Profession not found'], 404);
        }

        $activityProfession->update($request->validated());
        $activityProfession->load('profession');

        return response()->json($activityProfession);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $activityProfession = ActivityProfession::find($id);

        if (!$activityProfession) {
            return response()->json(['message' => 'Activity Profession not found'], 404);
        }

        $activityProfession->delete();
        return response()->json(['message' => 'Activity Profession deleted successfully'], 200);
    }
}
