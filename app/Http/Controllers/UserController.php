<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::paginate(10);
        return response()->json($users);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nip'        => 'required|string|unique:users,nip',
            'name'       => 'required|string|max:255',
            'email'      => 'required|email|unique:users,email',
            'password'   => 'required|string|min:6',
            'nik'        => 'nullable|string',
            'status'     => 'nullable|string',
            'department' => 'nullable|string',
            'no_hp'      => 'nullable|string',
            'profession' => 'nullable|string',
            'office'     => 'nullable|string',
            'grade'      => 'nullable|string',
            'position'   => 'nullable|string',
            'jabfung'    => 'nullable|string',
            'npwp'       => 'nullable|string',
            'norek'      => 'nullable|string',
        ]);

        // $validated['password'] = Hash::make($validated['password']);

        $user = User::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'User berhasil ditambahkan',
            'data' => $user
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::find($id);

        if (! $user) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $user
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::find($id);

        if (! $user) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak ditemukan'
            ], 404);
        }

        $validated = $request->validate([
            'nip'        => 'sometimes|string|unique:users,nip,' . $id,
            'name'       => 'sometimes|string|max:255',
            'email'      => 'sometimes|email|unique:users,email,' . $id,
            'password'   => 'sometimes|string|min:6',
            'nik'        => 'nullable|string',
            'status'     => 'nullable|string',
            'department' => 'nullable|string',
            'no_hp'      => 'nullable|string',
            'profession' => 'nullable|string',
            'office'     => 'nullable|string',
            'grade'      => 'nullable|string',
            'position'   => 'nullable|string',
            'jabfung'    => 'nullable|string',
            'npwp'       => 'nullable|string',
            'norek'      => 'nullable|string',
        ]);

        // if (isset($validated['password'])) {
        //     $validated['password'] = Hash::make($validated['password']);
        // }

        $user->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'User berhasil diperbarui',
            'data' => $user
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::find($id);

        if (! $user) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak ditemukan'
            ], 404);
        }

        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'User berhasil dihapus'
        ]);
    }
}
