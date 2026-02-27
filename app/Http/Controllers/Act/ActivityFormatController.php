<?php

namespace App\Http\Controllers\Act;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Act\ActivityFormat;

class ActivityFormatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $activityFormats = ActivityFormat::paginate(10);
        return response()->json($activityFormats);
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
        $activityFormat = ActivityFormat::create($request->all());
        return response()->json($activityFormat, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $activityFormat = ActivityFormat::find($id);

        if (!$activityFormat) {
            return response()->json(['message' => 'Activity Format not found'], 404);
        }

        return response()->json($activityFormat);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit() {}

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $activityFormat = ActivityFormat::find($id);

        if (!$activityFormat) {
            return response()->json(['message' => 'Activity Format not found'], 404);
        }

        $activityFormat->update($request->all());
        return response()->json($activityFormat);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $activityFormat = ActivityFormat::find($id);

        if (!$activityFormat) {
            return response()->json(['message' => 'Activity Format not found'], 404);
        }

        $activityFormat->delete();
        return response()->json(['message' => 'Activity Format deleted successfully'], 200);
    }
}
