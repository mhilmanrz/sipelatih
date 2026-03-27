<?php

namespace App\Http\Controllers\Act;

use App\Http\Controllers\Controller;
use App\Models\Act\Activity;
use App\Http\Requests\Act\StoreActivityRequest;
use App\Http\Requests\Act\UpdateActivityRequest;
use App\Http\Resources\ActivityResource;

class ActivityController extends Controller
{
    /**
     * Relations to eager load for detail view.
     */
    protected $relations = [
        'activityName',
        'activityType',
        'activityScope',
        'materialType',
        'activityMethod',
        'batch',
        'activityFormat',
        'targetParticipant',
        'workUnit',
        'user',
        'picUser',
        'latestStatus',
    ];

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $activities = Activity::with($this->relations)->paginate(10);
        return ActivityResource::collection($activities);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $activityNames = \App\Models\Act\ActivityName::all();
        $activityTypes = \App\Models\Act\ActivityType::all();
        $activityScopes = \App\Models\Act\ActivityScope::all();
        $materialTypes = \App\Models\Act\MaterialType::all();
        $activityMethods = \App\Models\Act\ActivityMethod::all();
        $batches = \App\Models\Act\Batch::all();
        $activityFormats = \App\Models\Act\ActivityFormat::all();
        $targetParticipants = \App\Models\Act\TargetParticipant::all();
        $workUnits = \App\Models\User\WorkUnit::all();

        return view('usulan.tambahdata', compact(
            'activityNames',
            'activityTypes',
            'activityScopes',
            'materialTypes',
            'activityMethods',
            'batches',
            'activityFormats',
            'targetParticipants',
            'workUnits'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreActivityRequest $request)
    {
        $activity = Activity::create($request->validated());
        $activity->load($this->relations);
        return new ActivityResource($activity);
    }

    /**
     * Display the specified resource.
     */
    public function show(\Illuminate\Http\Request $request, $id)
    {
        $activity = Activity::with($this->relations)->find($id);

        if (!$activity) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json(['message' => 'Activity not found'], 404);
            }
            abort(404);
        }

        if ($request->expectsJson() || $request->is('api/*')) {
            return new ActivityResource($activity);
        }

        return view('usulan.detail.index', ['kegiatan' => $activity]);
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

        return new ActivityResource($activity);
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
