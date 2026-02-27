<?php

namespace App\Http\Controllers\Act;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Act\ActivityKakFile;

class ActivityKakFileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $activityKakFiles = ActivityKakFile::paginate(10);
        return response()->json($activityKakFiles);
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
        $activityKakFile = ActivityKakFile::create($request->all());
        return response()->json($activityKakFile, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $activityKakFile = ActivityKakFile::find($id);

        if (!$activityKakFile) {
            return response()->json(['message' => 'Activity Kak File not found'], 404);
        }

        return response()->json($activityKakFile);
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
        $activityKakFile = ActivityKakFile::find($id);

        if (!$activityKakFile) {
            return response()->json(['message' => 'Activity Kak File not found'], 404);
        }

        $activityKakFile->update($request->all());
        return response()->json($activityKakFile);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $activityKakFile = ActivityKakFile::find($id);

        if (!$activityKakFile) {
            return response()->json(['message' => 'Activity Kak File not found'], 404);
        }

        $activityKakFile->delete();
        return response()->json(['message' => 'Activity Kak File deleted successfully'], 200);
    }
}
