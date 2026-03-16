<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User\EmploymentType;
use Illuminate\Http\Request;

class EmploymentTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $employmentTypes = EmploymentType::paginate(10);
        return view('employment_type.index', compact('employmentTypes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('employment_type.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        EmploymentType::create($request->all());
        return redirect()->route('employment-types.index')->with('success', 'Jenis Kepegawaian berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        return redirect()->route('employment-types.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $employmentType = EmploymentType::findOrFail($id);
        return view('employment_type.edit', compact('employmentType'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $employmentType = EmploymentType::findOrFail($id);
        $employmentType->update($request->all());

        return redirect()->route('employment-types.index')->with('success', 'Jenis Kepegawaian berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $employmentType = EmploymentType::findOrFail($id);
        $employmentType->delete();

        return redirect()->route('employment-types.index')->with('success', 'Jenis Kepegawaian berhasil dihapus.');
    }
}
