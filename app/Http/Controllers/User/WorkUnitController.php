<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User\WorkUnit;
use Illuminate\Http\Request;

class WorkUnitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = WorkUnit::query();
        if ($request->has('q') && $request->q != '') {
            $query->where('name', 'like', '%'.$request->q.'%');
        }
        $perPage = $request->input('entries', $request->input('per_page', 10));
        $workUnits = $query->paginate($perPage)->appends($request->all());

        return view('workunit.index', compact('workUnits'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('workunit.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'code' => 'nullable|string|max:255|unique:work_units,code',
            'name' => 'required|string|max:255',
        ]);

        WorkUnit::create($request->all());

        return redirect()->route('work-units.index')->with('success', 'Unit Kerja berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        return redirect()->route('work-units.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $workUnit = WorkUnit::findOrFail($id);

        return view('workunit.edit', compact('workUnit'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'code' => 'nullable|string|max:255|unique:work_units,code,'.$id,
            'name' => 'required|string|max:255',
        ]);

        $workUnit = WorkUnit::findOrFail($id);
        $workUnit->update($request->all());

        return redirect()->route('work-units.index')->with('success', 'Unit Kerja berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $workUnit = WorkUnit::findOrFail($id);
        $workUnit->delete();

        return redirect()->route('work-units.index')->with('success', 'Unit Kerja berhasil dihapus.');
    }
}
