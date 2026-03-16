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
        return view('profession.index', compact('professions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('profession.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Profession::create($request->all());
        return redirect()->route('professions.index')->with('success', 'Profesi berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // Not used in standard web CRUD usually, but leaving for now or redirecting
        return redirect()->route('professions.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $profession = Profession::findOrFail($id);
        return view('profession.edit', compact('profession'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $profession = Profession::findOrFail($id);
        $profession->update($request->all());

        return redirect()->route('professions.index')->with('success', 'Profesi berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $profession = Profession::findOrFail($id);
        $profession->delete();

        return redirect()->route('professions.index')->with('success', 'Profesi berhasil dihapus.');
    }
}
