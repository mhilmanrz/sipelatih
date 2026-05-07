<?php

namespace App\Http\Controllers\Act;

use App\Http\Controllers\Controller;
use App\Models\Act\ActivityCategory;
use Illuminate\Http\Request;

class ActivityCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = ActivityCategory::query();
        if ($request->has('q') && $request->q != '') {
            $query->where('name', 'like', '%'.$request->q.'%');
        }
        $perPage = $request->input('entries', $request->input('per_page', 10));
        $activityCategories = $query->paginate($perPage)->appends($request->all());

        return view('activity_category.index', compact('activityCategories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('activity_category.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'code' => 'nullable|string|max:255|unique:activity_categories,code',
            'name' => 'required|string|max:255',
        ]);

        ActivityCategory::create($request->all());

        return redirect()->route('activity-categories.index')->with('success', 'Kategori Kegiatan berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        return redirect()->route('activity-categories.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $activityCategory = ActivityCategory::findOrFail($id);

        return view('activity_category.edit', compact('activityCategory'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'code' => 'nullable|string|max:255|unique:activity_categories,code,' . $id,
            'name' => 'required|string|max:255',
        ]);

        $activityCategory = ActivityCategory::findOrFail($id);
        $activityCategory->update($request->all());

        return redirect()->route('activity-categories.index')->with('success', 'Kategori Kegiatan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $activityCategory = ActivityCategory::findOrFail($id);
        $activityCategory->delete();

        return redirect()->route('activity-categories.index')->with('success', 'Kategori Kegiatan berhasil dihapus.');
    }
}
