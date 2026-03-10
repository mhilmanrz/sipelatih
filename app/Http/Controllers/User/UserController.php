<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User\User;
use App\Models\User\WorkUnit;
use App\Models\User\Profession;
use App\Models\User\Positions;
use App\Models\User\EmploymentType;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\UsersImport;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = User::with(['workUnit', 'position', 'employmentType', 'profession', 'roles']);

        if ($request->has('q') && $request->q != '') {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('employee_id', 'like', "%{$search}%")
                    ->orWhereHas('workUnit', function ($query) use ($search) {
                        $query->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('profession', function ($query) use ($search) {
                        $query->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('position', function ($query) use ($search) {
                        $query->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('employmentType', function ($query) use ($search) {
                        $query->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('roles', function ($query) use ($search) {
                        $query->where('name', 'like', "%{$search}%");
                    });
            });
        }

        $perPage = $request->input('per_page', 10);
        $users = $query->paginate($perPage)->appends($request->all());
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
        $roles = Role::all();
        return view('user.tambah', compact('workUnits', 'professions', 'positions', 'employmentTypes', 'roles'));
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
            'role' => 'nullable|string|exists:roles,name',
        ]);

        $data = $request->except('role');
        $data['password'] = bcrypt($data['password']);

        $user = User::create($data);

        if ($request->filled('role')) {
            $user->assignRole($request->role);
        }

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
        $roles = Role::all();
        return view('user.edit', compact('user', 'workUnits', 'professions', 'positions', 'employmentTypes', 'roles'));
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
            'role' => 'nullable|string|exists:roles,name',
        ]);

        $data = $request->except('role');
        if ($request->filled('password')) {
            $data['password'] = bcrypt($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);

        if ($request->filled('role')) {
            $user->syncRoles([$request->role]);
        }

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

    /**
     * Show the form for importing users via CSV.
     */
    public function importView()
    {
        return view('user.import');
    }

    /**
     * Handle the import request and dispatch the job.
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:10240', // 10MB max
        ], [
            'file.required' => 'Pilih file terlebih dahulu.',
            'file.mimes' => 'Format file harus berupa Excel (.xlsx, .xls) atau CSV.',
            'file.max' => 'Ukuran file terlalu besar (maks 10MB).'
        ]);

        if ($request->hasFile('file')) {
            Excel::import(new UsersImport, $request->file('file'));

            return redirect('/users')->with('success', 'File berhasil diunggah. Proses import sedang berjalan di latar belakang.');
        }

        return redirect()->back()->with('error', 'Gagal mengunggah file.');
    }
}
