<?php

namespace App\Http\Controllers\Act;

use App\Http\Controllers\Controller;
use App\Models\Act\ActivityMethod;
use Illuminate\Http\Request;

class ActivityMethodController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $activityMethods = ActivityMethod::paginate(10);

        return view('activity_method.index', compact('activityMethods'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('activity_method.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        ActivityMethod::create($request->all());

        return redirect()->route('activity-methods.index')->with('success', 'Metode Kegiatan berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        return redirect()->route('activity-methods.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $activityMethod = ActivityMethod::findOrFail($id);

        return view('activity_method.edit', compact('activityMethod'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $activityMethod = ActivityMethod::findOrFail($id);
        $activityMethod->update($request->all());

        return redirect()->route('activity-methods.index')->with('success', 'Metode Kegiatan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $activityMethod = ActivityMethod::findOrFail($id);
        $activityMethod->delete();

        return redirect()->route('activity-methods.index')->with('success', 'Metode Kegiatan berhasil dihapus.');
    }
}
