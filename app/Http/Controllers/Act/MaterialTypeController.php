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

        return view('material_type.index', compact('materialTypes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('material_type.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        MaterialType::create($request->all());

        return redirect()->route('material-types.index')->with('success', 'Jenis Materi berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        return redirect()->route('material-types.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $materialType = MaterialType::findOrFail($id);

        return view('material_type.edit', compact('materialType'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $materialType = MaterialType::findOrFail($id);
        $materialType->update($request->all());

        return redirect()->route('material-types.index')->with('success', 'Jenis Materi berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $materialType = MaterialType::findOrFail($id);
        $materialType->delete();

        return redirect()->route('material-types.index')->with('success', 'Jenis Materi berhasil dihapus.');
    }
}
