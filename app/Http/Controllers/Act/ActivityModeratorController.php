<?php

namespace App\Http\Controllers\Act;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreActivityModeratorRequest;
use App\Http\Requests\UpdateActivityModeratorRequest;
use App\Models\Act\ActivityModerator;
use App\Models\Act\ActivityMaterial;

class ActivityModeratorController extends Controller
{
    protected $relations = ['user.workUnit', 'activityMaterial'];

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $activityModerators = ActivityModerator::with($this->relations)->paginate(10);
        return response()->json($activityModerators);
    }

    /**
     * Get all moderators for a specific activity.
     */
    public function getByActivity($activityId)
    {
        $materialIds = ActivityMaterial::where('activity_id', $activityId)->pluck('id');

        $moderators = ActivityModerator::with($this->relations)
            ->whereIn('activity_material_id', $materialIds)
            ->get();

        return response()->json($moderators);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreActivityModeratorRequest $request)
    {
        $activityModerator = ActivityModerator::create($request->validated());
        $activityModerator->load($this->relations);

        return response()->json($activityModerator, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $activityModerator = ActivityModerator::with($this->relations)->find($id);

        if (!$activityModerator) {
            return response()->json(['message' => 'Activity Moderator not found'], 404);
        }

        return response()->json($activityModerator);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateActivityModeratorRequest $request, string $id)
    {
        $activityModerator = ActivityModerator::find($id);

        if (!$activityModerator) {
            return response()->json(['message' => 'Activity Moderator not found'], 404);
        }

        $activityModerator->update($request->validated());
        $activityModerator->load($this->relations);

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
