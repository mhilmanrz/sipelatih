<?php

namespace App\Http\Controllers\User;

use App\Models\User\Position;
use App\Http\Requests\User\StorePositionRequest;
use App\Http\Requests\User\UpdatePositionRequest;
use App\Http\Controllers\Controller;

class PositionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(\Illuminate\Http\Request $request)
    {
        $search = $request->input('search');

        $query = Position::query();

        if ($search) {
            $query->where('name', 'like', '%' . $search . '%')
                ->orWhere('code', 'like', '%' . $search . '%');
        }

        $positions = $query->paginate(10);

        return view('user.position.index', compact('positions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('user.position.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(\Illuminate\Http\Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:255|unique:positions,code',
            'name' => 'required|string|max:255',
        ]);

        Position::create($validated);

        return redirect()->route('positions.index')->with('success', 'Data Jabatan berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Position $position)
    {
        // Typically not needed for simple dictionary tables like this
        return redirect()->route('positions.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Position $position)
    {
        return view('user.position.edit', compact('position'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(\Illuminate\Http\Request $request, Position $position)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:255|unique:positions,code,' . $position->id,
            'name' => 'required|string|max:255',
        ]);

        $position->update($validated);

        return redirect()->route('positions.index')->with('success', 'Data Jabatan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Position $position)
    {
        $position->delete();

        return redirect()->route('positions.index')->with('success', 'Data Jabatan berhasil dihapus.');
    }
}
