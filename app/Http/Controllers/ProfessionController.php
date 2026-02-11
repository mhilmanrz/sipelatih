<?php

namespace App\Http\Controllers;

use App\Models\Profession;
use Illuminate\Http\Request;

class ProfessionController extends Controller
{
    public function index()
    {
        $professions = Profession::all();
        return response()->json($professions);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:professions,name',
        ]);

        $profession = Profession::create($validated);

        return response()->json($profession, 201);
    }

    public function show(Profession $profession)
    {
        return response()->json($profession);
    }

    public function update(Request $request, string $id)
    {
        $profession = Profession::find($id);

        if (! $profession) {
            return response()->json([
                'success' => false,
                'message' => 'Profession tidak ditemukan'
            ], 404);
        }
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:professions,name,' . $profession->id,
        ]);

        $profession->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Profession updated successfully',
            'data' => $profession
        ]);
    }

    public function destroy(string $id)
    {
        $profession = Profession::find($id);

        if (! $profession) {
            return response()->json([
                'success' => false,
                'message' => 'Profession tidak ditemukan'
            ], 404);
        }
        $profession->delete();

        return response()->json([
            'success' => true,
            'message' => 'Profession deleted'
        ]);
    }
}
