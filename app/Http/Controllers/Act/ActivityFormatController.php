<?php

namespace App\Http\Controllers\Act;

use App\Http\Controllers\Controller;
use App\Models\Act\ActivityFormat;
use Illuminate\Http\Request;

class ActivityFormatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $activityFormats = ActivityFormat::paginate(10);

        return view('activity_format.index', compact('activityFormats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('activity_format.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        ActivityFormat::create($request->all());

        return redirect()->route('activity-formats.index')->with('success', 'Bentuk Kegiatan berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        return redirect()->route('activity-formats.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $activityFormat = ActivityFormat::findOrFail($id);

        return view('activity_format.edit', compact('activityFormat'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $activityFormat = ActivityFormat::findOrFail($id);
        $activityFormat->update($request->all());

        return redirect()->route('activity-formats.index')->with('success', 'Bentuk Kegiatan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $activityFormat = ActivityFormat::findOrFail($id);
        $activityFormat->delete();

        return redirect()->route('activity-formats.index')->with('success', 'Bentuk Kegiatan berhasil dihapus.');
    }
}
