<?php

namespace App\Http\Controllers\Act;

use App\Http\Controllers\Controller;
use App\Models\Act\ActivityScope;
use Illuminate\Http\Request;

class ActivityScopeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $activityScopes = ActivityScope::paginate(10);
        return view('activity_scope.index', compact('activityScopes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('activity_scope.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        ActivityScope::create($request->all());
        return redirect()->route('activity-scopes.index')->with('success', 'Ruang Lingkup Kegiatan berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        return redirect()->route('activity-scopes.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $activityScope = ActivityScope::findOrFail($id);
        return view('activity_scope.edit', compact('activityScope'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $activityScope = ActivityScope::findOrFail($id);
        $activityScope->update($request->all());

        return redirect()->route('activity-scopes.index')->with('success', 'Ruang Lingkup Kegiatan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $activityScope = ActivityScope::findOrFail($id);
        $activityScope->delete();

        return redirect()->route('activity-scopes.index')->with('success', 'Ruang Lingkup Kegiatan berhasil dihapus.');
    }
}
