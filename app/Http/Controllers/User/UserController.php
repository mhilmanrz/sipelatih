<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User\User;
use App\Models\User\WorkUnit;
use App\Models\User\Profession;
use App\Models\User\Positions;
use App\Models\User\EmploymentType;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::with(['workUnit', 'position', 'employmentType', 'profession'])->paginate(10);
        return view('user.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $workUnits = WorkUnit::all();
        $professions = Profession::all();
        $positions = Positions::all();
        $employmentTypes = EmploymentType::all();
        return view('user.tambah', compact('workUnits', 'professions', 'positions', 'employmentTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'employee_id' => 'nullable|string',
            'phone_number' => 'nullable|string',
            'work_unit_id' => 'nullable|exists:work_units,id',
            'profession_id' => 'nullable|exists:professions,id',
            'position_id' => 'nullable|exists:positions,id',
            'employment_type_id' => 'nullable|exists:employment_types,id',
        ]);

        $data = $request->all();
        $data['password'] = bcrypt($data['password']);

        User::create($data);
        return redirect('/users')->with('success', 'User berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // Currently not used in views, redirect to index
        return redirect('/users');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $workUnits = WorkUnit::all();
        $professions = Profession::all();
        $positions = Positions::all();
        $employmentTypes = EmploymentType::all();
        return view('user.edit', compact('user', 'workUnits', 'professions', 'positions', 'employmentTypes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'employee_id' => 'nullable|string',
            'phone_number' => 'nullable|string',
            'work_unit_id' => 'nullable|exists:work_units,id',
            'profession_id' => 'nullable|exists:professions,id',
            'position_id' => 'nullable|exists:positions,id',
            'employment_type_id' => 'nullable|exists:employment_types,id',
        ]);

        $data = $request->all();
        if ($request->filled('password')) {
            $data['password'] = bcrypt($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);
        return redirect('/users')->with('success', 'User berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect('/users')->with('success', 'User berhasil dihapus.');
    }
}
