<?php

namespace App\Http\Controllers\Act;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreActivityStatusRequest;
use App\Http\Requests\UpdateActivityStatusRequest;
use App\Models\Act\ActivityStatus;
use Carbon\Carbon;

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
     * Get all statuses for a specific activity, ordered by date.
     */
    public function getByActivity($activityId)
    {
        $activityStatuses = ActivityStatus::where('activity_id', $activityId)
            ->orderBy('date', 'asc')
            ->get();

        return response()->json($activityStatuses);
    }

    /**
     * Store a newly created resource in storage.
     * Date is auto-set to today.
     */
    public function store(StoreActivityStatusRequest $request)
    {
        $activityStatus = ActivityStatus::create([
            'activity_id' => $request->activity_id,
            'date' => Carbon::today(),
            'status' => $request->status,
            'note' => $request->note,
        ]);

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
     * Update the specified resource in storage.
     */
    public function update(UpdateActivityStatusRequest $request, string $id)
    {
        $activityStatus = ActivityStatus::find($id);

        if (!$activityStatus) {
            return response()->json(['message' => 'Activity Status not found'], 404);
        }

        $activityStatus->update($request->validated());
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
