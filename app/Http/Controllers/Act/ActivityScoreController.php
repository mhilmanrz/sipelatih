<?php

namespace App\Http\Controllers\Act;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Act\ActivityScore;

class ActivityScoreController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $activityScores = ActivityScore::paginate(10);
        return response()->json($activityScores);
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
        $activityScore = ActivityScore::create($request->all());
        return response()->json($activityScore, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $activityScore = ActivityScore::find($id);

        if (!$activityScore) {
            return response()->json(['message' => 'Activity Score not found'], 404);
        }

        return response()->json($activityScore);
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
        $activityScore = ActivityScore::find($id);

        if (!$activityScore) {
            return response()->json(['message' => 'Activity Score not found'], 404);
        }

        $activityScore->update($request->all());
        return response()->json($activityScore);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $activityScore = ActivityScore::find($id);

        if (!$activityScore) {
            return response()->json(['message' => 'Activity Score not found'], 404);
        }

        $activityScore->delete();
        return response()->json(['message' => 'Activity Score deleted successfully'], 200);
    }
}
