<?php

namespace App\Http\Controllers\Act;

use App\Http\Controllers\Controller;
use App\Models\Act\ActivityScope;
use Illuminate\Http\Request;

class ActivityScopeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $activityScopes = ActivityScope::paginate(10);
        return response()->json($activityScopes);
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
        $activityScope = ActivityScope::create($request->all());
        return response()->json($activityScope, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $activityScope = ActivityScope::find($id);

        if (!$activityScope) {
            return response()->json(['message' => 'Activity Scope not found'], 404);
        }

        return response()->json($activityScope);
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
        $activityScope = ActivityScope::find($id);

        if (!$activityScope) {
            return response()->json(['message' => 'Activity Scope not found'], 404);
        }

        $activityScope->update($request->all());
        return response()->json($activityScope);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $activityScope = ActivityScope::find($id);

        if (!$activityScope) {
            return response()->json(['message' => 'Activity Scope not found'], 404);
        }

        $activityScope->delete();
        return response()->json(['message' => 'Activity Scope deleted successfully'], 200);
    }
}
