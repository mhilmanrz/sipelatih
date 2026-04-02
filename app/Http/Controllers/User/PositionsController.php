<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User\Positions;
use Illuminate\Http\Request;

class PositionsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $positions = Positions::paginate(10);
        return response()->json($positions);
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
        $position = Positions::create($request->all());
        return response()->json($position, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $position = Positions::find($id);

        if (!$position) {
            return response()->json(['message' => 'Position not found'], 404);
        }

        return response()->json($position);
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
        $position = Positions::find($id);

        if (!$position) {
            return response()->json(['message' => 'Position not found'], 404);
        }

        $position->update($request->all());
        return response()->json($position);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $position = Positions::find($id);

        if (!$position) {
            return response()->json(['message' => 'Position not found'], 404);
        }

        $position->delete();
        return response()->json(['message' => 'Position deleted successfully'], 200);
    }
}
