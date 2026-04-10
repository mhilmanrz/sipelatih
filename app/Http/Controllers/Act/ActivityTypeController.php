<?php

namespace App\Http\Controllers\Act;

use App\Http\Controllers\Controller;
use App\Models\Act\ActivityType;
use Illuminate\Http\Request;

class ActivityTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $activityTypes = ActivityType::paginate(10);

        return view('activity_type.index', compact('activityTypes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('activity_type.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        ActivityType::create($request->all());

        return redirect()->route('activity-types.index')->with('success', 'Jenis Kegiatan berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        return redirect()->route('activity-types.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $activityType = ActivityType::findOrFail($id);

        return view('activity_type.edit', compact('activityType'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $activityType = ActivityType::findOrFail($id);
        $activityType->update($request->all());

        return redirect()->route('activity-types.index')->with('success', 'Jenis Kegiatan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $activityType = ActivityType::findOrFail($id);
        $activityType->delete();

        return redirect()->route('activity-types.index')->with('success', 'Jenis Kegiatan berhasil dihapus.');
    }
}
