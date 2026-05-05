<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User\Position;
use Illuminate\Http\Request;

class PositionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $query = Position::query();

        if ($search) {
            $query->where('name', 'like', '%'.$search.'%')
                ->orWhere('code', 'like', '%'.$search.'%');
        }

        $perPage = $request->input('entries', 10);
        $positions = $query->paginate($perPage)->appends($request->all());

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
    public function store(Request $request)
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
    public function update(Request $request, Position $position)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:255|unique:positions,code,'.$position->id,
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
