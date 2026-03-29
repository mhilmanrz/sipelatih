<?php

namespace App\Http\Controllers\Act;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Act\ActivityProfession;

class ActivityProfessionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $activityProfessions = ActivityProfession::paginate(10);
        return response()->json($activityProfessions);
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
        $activityProfession = ActivityProfession::create($request->all());
        return response()->json($activityProfession, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $activityProfession = ActivityProfession::find($id);

        if (!$activityProfession) {
            return response()->json(['message' => 'Activity Profession not found'], 404);
        }

        return response()->json($activityProfession);
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
        $activityProfession = ActivityProfession::find($id);

        if (!$activityProfession) {
            return response()->json(['message' => 'Activity Profession not found'], 404);
        }

        $activityProfession->update($request->all());
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
