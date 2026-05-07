<?php

namespace App\Http\Controllers;

use App\Models\ProfessionCategory;
use Illuminate\Http\Request;

class ProfessionCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = ProfessionCategory::query();
        if ($request->has('q') && $request->q != '') {
            $query->where('name', 'like', '%'.$request->q.'%');
        }
        $perPage = $request->input('entries', $request->input('per_page', 10));
        $categories = $query->paginate($perPage)->appends($request->all());

        return view('profession-category.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('profession-category.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'code' => 'nullable|string|max:255|unique:profession_categories,code',
            'name' => 'required|string|max:255',
            'jpl_target' => 'required|integer|min:0',
        ]);

        ProfessionCategory::create($request->all());

        return redirect()->route('profession-categories.index')->with('success', 'Kategori Profesi berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(ProfessionCategory $professionCategory)
    {
        return redirect()->route('profession-categories.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProfessionCategory $professionCategory)
    {
        return view('profession-category.edit', compact('professionCategory'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ProfessionCategory $professionCategory)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'jpl_target' => 'required|integer|min:0',
        ]);

        $professionCategory->update($request->all());

        return redirect()->route('profession-categories.index')->with('success', 'Kategori Profesi berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProfessionCategory $professionCategory)
    {
        $professionCategory->delete();

        return redirect()->route('profession-categories.index')->with('success', 'Kategori Profesi berhasil dihapus.');
    }
}
