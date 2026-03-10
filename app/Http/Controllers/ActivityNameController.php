<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Act\ActivityName;

class ActivityNameController extends Controller
{
    public function index()
    {
        $activityNames = ActivityName::paginate(10);
        return view('dictionaries.activity_names.index', compact('activityNames'));
    }

    public function create()
    {
        return view('dictionaries.activity_names.create');
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);
        ActivityName::create($request->all());
        return redirect()->route('activity-names.index')->with('success', 'Nama Kegiatan berhasil ditambahkan.');
    }

    public function edit(ActivityName $activityName)
    {
        return view('dictionaries.activity_names.edit', compact('activityName'));
    }

    public function update(Request $request, ActivityName $activityName)
    {
        $request->validate(['name' => 'required|string|max:255']);
        $activityName->update($request->all());
        return redirect()->route('activity-names.index')->with('success', 'Nama Kegiatan berhasil diperbarui.');
    }

    public function destroy(ActivityName $activityName)
    {
        $activityName->delete();
        return redirect()->route('activity-names.index')->with('success', 'Nama Kegiatan berhasil dihapus.');
    }
}
