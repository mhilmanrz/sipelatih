<?php

namespace App\Http\Controllers\Act;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Act\TargetParticipant;

class TargetParticipantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $targetParticipants = TargetParticipant::paginate(10);
        return response()->json($targetParticipants);
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
        $targetParticipant = TargetParticipant::create($request->all());
        return response()->json($targetParticipant, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $targetParticipant = TargetParticipant::find($id);

        if (!$targetParticipant) {
            return response()->json(['message' => 'Target Participant not found'], 404);
        }

        return response()->json($targetParticipant);
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
        $targetParticipant = TargetParticipant::find($id);

        if (!$targetParticipant) {
            return response()->json(['message' => 'Target Participant not found'], 404);
        }

        $targetParticipant->update($request->all());
        return response()->json($targetParticipant);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $targetParticipant = TargetParticipant::find($id);

        if (!$targetParticipant) {
            return response()->json(['message' => 'Target Participant not found'], 404);
        }

        $targetParticipant->delete();
        return response()->json(['message' => 'Target Participant deleted successfully'], 200);
    }
}
