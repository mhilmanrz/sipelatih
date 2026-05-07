<?php

namespace App\Http\Controllers\User;

use App\Exports\UsersTemplateExport;
use App\Http\Controllers\Controller;
use App\Jobs\ImportUsersJob;
use App\Models\User\EmploymentType;
use App\Models\User\Positions;
use App\Models\User\Profession;
use App\Models\User\Rank;
use App\Models\User\User;
use App\Models\User\WorkUnit;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = User::with(['workUnit', 'position', 'employmentType', 'profession', 'rank', 'roles'])->doesntHave('roles')->where('email', '!=', 'admin@mail.com');

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
        $ranks = Rank::all();

        return view('user.tambah', compact('workUnits', 'professions', 'positions', 'employmentTypes', 'ranks'));
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
            'rank_id' => 'nullable|exists:ranks,id',
            'npwp' => 'nullable|string|max:255',
            'bank_name' => 'nullable|string|max:255',
            'account_number' => 'nullable|string|max:255',
        ]);

        $data = $request->all();
        $data['password'] = bcrypt($data['password']);

        $user = User::create($data);

        return redirect('/users')->with('success', 'Pegawai berhasil ditambahkan.');
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
        $ranks = Rank::all();

        return view('user.edit', compact('user', 'workUnits', 'professions', 'positions', 'employmentTypes', 'ranks'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,
            'employee_id' => 'nullable|string',
            'phone_number' => 'nullable|string',
            'work_unit_id' => 'nullable|exists:work_units,id',
            'profession_id' => 'nullable|exists:professions,id',
            'position_id' => 'nullable|exists:positions,id',
            'employment_type_id' => 'nullable|exists:employment_types,id',
            'rank_id' => 'nullable|exists:ranks,id',
            'npwp' => 'nullable|string|max:255',
            'bank_name' => 'nullable|string|max:255',
            'account_number' => 'nullable|string|max:255',
        ]);

        $data = $request->all();
        if ($request->filled('password')) {
            $data['password'] = bcrypt($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);

        return redirect('/users')->with('success', 'Pegawai berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect('/users')->with('success', 'Pegawai berhasil dihapus.');
    }

    /**
     * Show the form for importing users via CSV.
     */
    public function importView()
    {
        return view('user.import');
    }

    /**
     * Download the template for importing users.
     */
    public function downloadTemplate()
    {
        return Excel::download(new UsersTemplateExport, 'Template_Import_Pegawai.xlsx');
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
            'file.max' => 'Ukuran file terlalu besar (maks 10MB).',
        ]);

        if ($request->hasFile('file')) {
            try {
                $path = $request->file('file')->store('imports', 'local');
                ImportUsersJob::dispatch($path);

                return redirect('/users')->with('success', 'File berhasil diunggah. Proses import sedang berjalan di antrean (background). Harap periksa beberapa saat lagi.');
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'Terjadi kesalahan saat upload untuk import: '.$e->getMessage());
            }
        }

        return redirect()->back()->with('error', 'Gagal mengunggah file.');
    }
}
