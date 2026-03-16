<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = User::with(['workUnit', 'profession']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('employee_id', 'like', "%{$search}%");
            });
        }

        $users = $query->paginate(10);
        return response()->json($users);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'               => 'required|string|max:255',
            'email'              => 'required|string|email|max:255|unique:users',
            'password'           => 'required|string|min:8',
            'employee_id'        => 'nullable|string',
            'phone_number'       => 'nullable|string',
            'work_unit_id'       => 'nullable|exists:work_units,id',
            'profession_id'      => 'nullable|exists:professions,id',
            'position_id'        => 'nullable|exists:positions,id',
            'employment_type_id' => 'nullable|exists:employment_types,id',
            'jpl_target'         => 'nullable|integer',
        ]);

        $data = $request->all();
        $data['password'] = bcrypt($data['password']);

        $user = User::create($data);

        return response()->json($user, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $user = User::with(['workUnit', 'profession'])->find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        return response()->json($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $request->validate([
            'name'               => 'sometimes|required|string|max:255',
            'email'              => 'sometimes|required|string|email|max:255|unique:users,email,' . $user->id,
            'employee_id'        => 'nullable|string',
            'phone_number'       => 'nullable|string',
            'work_unit_id'       => 'nullable|exists:work_units,id',
            'profession_id'      => 'nullable|exists:professions,id',
            'position_id'        => 'nullable|exists:positions,id',
            'employment_type_id' => 'nullable|exists:employment_types,id',
            'jpl_target'         => 'nullable|integer',
        ]);

        $data = $request->all();
        if ($request->filled('password')) {
            $data['password'] = bcrypt($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);

        return response()->json($user);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $user->delete();

        return response()->json(['message' => 'User deleted successfully'], 200);
    }
}
