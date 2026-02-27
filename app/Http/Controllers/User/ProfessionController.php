<?php

namespace App\Http\Controllers\User;

use App\Models\User\Profession;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProfessionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $professions = Profession::paginate(10);
        return response()->json($professions);
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
        $profession = Profession::create($request->all());
        return response()->json($profession, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $profession = Profession::find($id);

        if (!$profession) {
            return response()->json(['message' => 'Profession not found'], 404);
        }

        return response()->json($profession);
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
        $profession = Profession::find($id);

        if (!$profession) {
            return response()->json(['message' => 'Profession not found'], 404);
        }

        $profession->update($request->all());
        return response()->json($profession);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $profession = Profession::find($id);

        if (!$profession) {
            return response()->json(['message' => 'Profession not found'], 404);
        }

        $profession->delete();
        return response()->json(['message' => 'Profession deleted successfully'], 200);
    }
}
