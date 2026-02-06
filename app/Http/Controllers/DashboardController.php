<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class DashboardController extends Controller
{
    /**
     * Tampilkan dashboard
     */
    public function index()
    {
        // Contoh data statistik
        $data = [
            'totalUser' => User::count(),
            'onlineUser' => User::where('is_online', 1)->count() ?? 0,
        ];

        return view('dashboard.index', compact('data'));
    }

    /**
     * Data calendar (FullCalendar)
     */
    public function calendar()
    {
        $events = [
            [
                'title' => 'Monitoring Diklat',
                'start' => now()->toDateString(),
            ],
            [
                'title' => 'Pengajuan Diklat',
                'start' => now()->addDays(2)->toDateString(),
            ],
        ];

        return response()->json($events);
    }

    /**
     * Ubah password
     */
    public function changePassword(Request $request)
    {
        $request->validate([
            'lastPassword' => 'required',
            'newPassword' => 'required|min:6',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->lastPassword, $user->password)) {
            return response()->json([
                'status' => false,
                'message' => 'Password lama salah'
            ]);
        }

        $user->password = Hash::make($request->newPassword);
        $user->save();

        return response()->json([
            'status' => true,
            'message' => 'Password berhasil diubah'
        ]);
    }
}
