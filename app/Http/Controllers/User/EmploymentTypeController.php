<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\user\StoreEmploymentTypeRequest;
use App\Http\Requests\user\UpdateEmploymentTypeRequest;
use App\Models\User\EmploymentType;
use Illuminate\Http\Request;

class EmploymentTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = EmploymentType::query();
        if ($request->has('q') && $request->q != '') {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%'.$request->q.'%')
                    ->orWhere('code', 'like', '%'.$request->q.'%');
            });
        }
        $perPage = $request->input('entries', $request->input('per_page', 10));
        $employmentTypes = $query->paginate($perPage)->appends($request->all());

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
    public function store(StoreEmploymentTypeRequest $request)
    {
        EmploymentType::create($request->validated());

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
    public function update(UpdateEmploymentTypeRequest $request, $id)
    {
        $employmentType = EmploymentType::findOrFail($id);
        $employmentType->update($request->validated());

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
