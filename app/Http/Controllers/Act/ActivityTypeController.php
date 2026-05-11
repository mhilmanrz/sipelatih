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
    public function index(Request $request)
    {
        $query = ActivityType::query();
        if ($request->has('q') && $request->q != '') {
            $query->where('name', 'like', '%'.$request->q.'%');
        }
        $perPage = $request->input('entries', $request->input('per_page', 10));
        $activityTypes = $query->paginate($perPage)->appends($request->all());

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
            'code' => 'nullable|string|max:255|unique:activity_types,code',
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
            'code' => 'nullable|string|max:255|unique:activity_types,code,'.$id,
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
