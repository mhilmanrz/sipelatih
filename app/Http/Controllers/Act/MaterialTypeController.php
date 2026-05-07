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
    public function index(Request $request)
    {
        $query = MaterialType::query();
        if ($request->has('q') && $request->q != '') {
            $query->where('name', 'like', '%'.$request->q.'%');
        }
        $perPage = $request->input('entries', $request->input('per_page', 10));
        $materialTypes = $query->paginate($perPage)->appends($request->all());

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
            'code' => 'nullable|string|max:255|unique:material_types,code',
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
            'code' => 'nullable|string|max:255|unique:material_types,code,' . $id,
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
