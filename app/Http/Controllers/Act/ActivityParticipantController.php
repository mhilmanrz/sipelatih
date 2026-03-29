<?php

namespace App\Http\Controllers\Act;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Act\ActivityParticipant;

class ActivityParticipantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $activityParticipants = ActivityParticipant::paginate(10);
        return response()->json($activityParticipants);
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
        $activityParticipant = ActivityParticipant::create($request->all());
        return response()->json($activityParticipant, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $activityParticipant = ActivityParticipant::find($id);

        if (!$activityParticipant) {
            return response()->json(['message' => 'Activity Participant not found'], 404);
        }

        return response()->json($activityParticipant);
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
        $activityParticipant = ActivityParticipant::find($id);

        if (!$activityParticipant) {
            return response()->json(['message' => 'Activity Participant not found'], 404);
        }

        $activityParticipant->update($request->all());
        return response()->json($activityParticipant);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $activityParticipant = ActivityParticipant::find($id);

        if (!$activityParticipant) {
            return response()->json(['message' => 'Activity Participant not found'], 404);
        }

        $activityParticipant->delete();
        return response()->json(['message' => 'Activity Participant deleted successfully'], 200);
    }
}
