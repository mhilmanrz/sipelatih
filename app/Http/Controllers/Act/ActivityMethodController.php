<?php

namespace App\Http\Controllers\Act;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Act\ActivityMethod;


class ActivityMethodController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $activityMethods = ActivityMethod::paginate(10);
        return response()->json($activityMethods);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $activityMethod = ActivityMethod::create($request->all());
        return response()->json($activityMethod, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $activityMethod = ActivityMethod::find($id);

        if (!$activityMethod) {
            return response()->json(['message' => 'Activity Method not found'], 404);
        }

        return response()->json($activityMethod);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit()
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $activityMethod = ActivityMethod::find($id);

        if (!$activityMethod) {
            return response()->json(['message' => 'Activity Method not found'], 404);
        }

        $activityMethod->update($request->all());
        return response()->json($activityMethod);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $activityMethod = ActivityMethod::find($id);

        if (!$activityMethod) {
            return response()->json(['message' => 'Activity Method not found'], 404);
        }

        $activityMethod->delete();
        return response()->json(['message' => 'Activity Method deleted successfully'], 200);
    }
}
