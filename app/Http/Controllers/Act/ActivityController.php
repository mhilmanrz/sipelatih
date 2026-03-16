<?php

namespace App\Http\Controllers\Act;

use App\Http\Controllers\Controller;
use App\Models\Act\Activity;
use App\Http\Requests\Act\StoreActivityRequest;
use App\Http\Requests\Act\UpdateActivityRequest;

class ActivityController extends Controller
{
    /**
     * Relations to eager load for detail view.
     */
    protected $relations = [
        'activityType',
        'activityScope',
        'materialType',
        'activityMethod',
        'batch',
        'activityFormat',
        'targetParticipant',
        'workUnit',
        'user',
    ];

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $activities = Activity::with($this->relations)->paginate(10);
        return response()->json($activities);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreActivityRequest $request)
    {
        $activity = Activity::create($request->validated());
        $activity->load($this->relations);

        return response()->json($activity, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $activity = Activity::with($this->relations)->find($id);

        if (!$activity) {
            return response()->json(['message' => 'Activity not found'], 404);
        }

        return response()->json($activity);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateActivityRequest $request, $id)
    {
        $activity = Activity::find($id);

        if (!$activity) {
            return response()->json(['message' => 'Activity not found'], 404);
        }

        $activity->update($request->validated());
        $activity->load($this->relations);

        return response()->json($activity);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $activity = Activity::find($id);

        if (!$activity) {
            return response()->json(['message' => 'Activity not found'], 404);
        }

        $activity->delete();
        return response()->json(null, 204);
    }
}
