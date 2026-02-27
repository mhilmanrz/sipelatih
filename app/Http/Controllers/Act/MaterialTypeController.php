<?php

namespace App\Http\Controllers\Act;

use App\Http\Controllers\Controller;
use App\Models\Act\MaterialType;
use Illuminate\Http\Request;

class MaterialTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $materialTypes = MaterialType::paginate(10);
        return response()->json($materialTypes);
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
        $materialType = MaterialType::create($request->all());
        return response()->json($materialType, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $materialType = MaterialType::find($id);

        if (!$materialType) {
            return response()->json(['message' => 'Material Type not found'], 404);
        }

        return response()->json($materialType);
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
        $materialType = MaterialType::find($id);

        if (!$materialType) {
            return response()->json(['message' => 'Material Type not found'], 404);
        }

        $materialType->update($request->all());
        return response()->json($materialType);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $materialType = MaterialType::find($id);

        if (!$materialType) {
            return response()->json(['message' => 'Material Type not found'], 404);
        }

        $materialType->delete();
        return response()->json(['message' => 'Material Type deleted successfully'], 200);
    }
}
