<?php

namespace App\Http\Controllers\User;

use App\Exports\AccountsTemplateExport;
use App\Http\Controllers\Controller;
use App\Jobs\ImportAccountsJob;
use App\Models\User\User;
use App\Models\User\WorkUnit;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Permission\Models\Role;

class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = User::with(['workUnit', 'position', 'employmentType', 'profession', 'roles'])->has('roles');

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

        return view('account.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $workUnits = WorkUnit::all();
        $roles = Role::all();

        return view('account.tambah', compact('workUnits', 'roles'));
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
            'work_unit_id' => 'nullable|exists:work_units,id',
            'role' => 'required|string|exists:roles,name',
        ]);

        $data = $request->only('name', 'email', 'password', 'work_unit_id');
        $data['password'] = bcrypt($data['password']);

        $user = User::create($data);

        if ($request->filled('role')) {
            $user->assignRole($request->role);
        }

        return redirect('/accounts')->with('success', 'Akun berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // Currently not used in views, redirect to index
        return redirect('/accounts');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $workUnits = WorkUnit::all();
        $roles = Role::all();

        return view('account.edit', compact('user', 'workUnits', 'roles'));
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
            'password' => 'nullable|string|min:8',
            'work_unit_id' => 'nullable|exists:work_units,id',
            'role' => 'required|string|exists:roles,name',
        ]);

        $data = $request->only('name', 'email', 'work_unit_id');
        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }

        $user->update($data);
        $user->syncRoles([$request->role]);

        return redirect('/accounts')->with('success', 'Akun berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect('/accounts')->with('success', 'Akun berhasil dihapus.');
    }

    /**
     * Show the form for importing accounts.
     */
    public function importView()
    {
        return view('account.import');
    }

    /**
     * Download the template for importing accounts.
     */
    public function downloadTemplate()
    {
        return Excel::download(new AccountsTemplateExport, 'Template_Import_Akun.xlsx');
    }

    /**
     * Handle the import request and dispatch the job.
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:10240',
        ], [
            'file.required' => 'Pilih file terlebih dahulu.',
            'file.mimes' => 'Format file harus berupa Excel (.xlsx, .xls) atau CSV.',
            'file.max' => 'Ukuran file terlalu besar (maks 10MB).',
        ]);

        if ($request->hasFile('file')) {
            try {
                $path = $request->file('file')->store('imports', 'local');
                ImportAccountsJob::dispatch($path);

                return redirect('/accounts')->with('success', 'File berhasil diunggah. Proses import sedang berjalan di antrean (background). Harap periksa beberapa saat lagi.');
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'Terjadi kesalahan saat upload untuk import: '.$e->getMessage());
            }
        }

        return redirect()->back()->with('error', 'Gagal mengunggah file.');
    }
}
