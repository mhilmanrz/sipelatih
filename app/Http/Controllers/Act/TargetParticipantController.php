<?php

namespace App\Http\Controllers\Act;

use App\Http\Controllers\Controller;
use App\Models\Act\TargetParticipant;
use Illuminate\Http\Request;

class TargetParticipantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = TargetParticipant::query();
        if ($request->has('q') && $request->q != '') {
            $query->where('name', 'like', '%'.$request->q.'%');
        }
        $perPage = $request->input('entries', $request->input('per_page', 10));
        $targetParticipants = $query->paginate($perPage)->appends($request->all());

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

        if (! $targetParticipant) {
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

        if (! $targetParticipant) {
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

        if (! $targetParticipant) {
            return response()->json(['message' => 'Target Participant not found'], 404);
        }

        $targetParticipant->delete();

        return response()->json(['message' => 'Target Participant deleted successfully'], 200);
    }
}
