<?php

namespace App\Http\Controllers\Act;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Act\ActivitySpeaker;

class ActivitySpeakerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $activitySpeakers = ActivitySpeaker::paginate(10);
        return response()->json($activitySpeakers);
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
        $activitySpeaker = ActivitySpeaker::create($request->all());
        return response()->json($activitySpeaker, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $activitySpeaker = ActivitySpeaker::find($id);

        if (!$activitySpeaker) {
            return response()->json(['message' => 'Activity Speaker not found'], 404);
        }

        return response()->json($activitySpeaker);
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
        $activitySpeaker = ActivitySpeaker::find($id);

        if (!$activitySpeaker) {
            return response()->json(['message' => 'Activity Speaker not found'], 404);
        }

        $activitySpeaker->update($request->all());
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
