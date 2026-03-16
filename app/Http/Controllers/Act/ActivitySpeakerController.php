<?php

namespace App\Http\Controllers\Act;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreActivitySpeakerRequest;
use App\Http\Requests\UpdateActivitySpeakerRequest;
use App\Models\Act\ActivitySpeaker;
use App\Models\Act\ActivityMaterial;

class ActivitySpeakerController extends Controller
{
    protected $relations = ['user.workUnit', 'activityMaterial'];

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $activitySpeakers = ActivitySpeaker::with($this->relations)->paginate(10);
        return response()->json($activitySpeakers);
    }

    /**
     * Get all speakers for a specific activity.
     */
    public function getByActivity($activityId)
    {
        $materialIds = ActivityMaterial::where('activity_id', $activityId)->pluck('id');

        $speakers = ActivitySpeaker::with($this->relations)
            ->whereIn('activity_material_id', $materialIds)
            ->get();

        return response()->json($speakers);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreActivitySpeakerRequest $request)
    {
        $activitySpeaker = ActivitySpeaker::create($request->validated());
        $activitySpeaker->load($this->relations);

        return response()->json($activitySpeaker, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $activitySpeaker = ActivitySpeaker::with($this->relations)->find($id);

        if (!$activitySpeaker) {
            return response()->json(['message' => 'Activity Speaker not found'], 404);
        }

        return response()->json($activitySpeaker);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateActivitySpeakerRequest $request, string $id)
    {
        $activitySpeaker = ActivitySpeaker::find($id);

        if (!$activitySpeaker) {
            return response()->json(['message' => 'Activity Speaker not found'], 404);
        }

        $activitySpeaker->update($request->validated());
        $activitySpeaker->load($this->relations);

        return response()->json($activitySpeaker);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $activitySpeaker = ActivitySpeaker::find($id);

        if (!$activitySpeaker) {
            return response()->json(['message' => 'Activity Speaker not found'], 404);
        }

        $activitySpeaker->delete();
        return response()->json(['message' => 'Activity Speaker deleted successfully'], 200);
    }
}
