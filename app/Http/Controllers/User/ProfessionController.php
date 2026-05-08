<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\ProfessionCategory;
use App\Models\User\Profession;
use Illuminate\Http\Request;

class ProfessionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->input('entries', 10);
        $professions = Profession::with('category')->paginate($perPage)->appends($request->all());

        return view('profession.index', compact('professions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = ProfessionCategory::all();

        return view('profession.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'nullable|string|max:255|unique:professions,code',
            'name' => 'required|string|max:255',
            'profession_category_id' => 'required|exists:profession_categories,id',
        ]);

        $validated['code'] = $validated['code'] ?: null;

        Profession::create($validated);

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
        $categories = ProfessionCategory::all();

        return view('profession.edit', compact('profession', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'code' => 'nullable|string|max:255|unique:professions,code,'.$id,
            'name' => 'required|string|max:255',
            'profession_category_id' => 'required|exists:profession_categories,id',
        ]);

        $validated['code'] = $validated['code'] ?: null;

        $profession = Profession::findOrFail($id);
        $profession->update($validated);

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
