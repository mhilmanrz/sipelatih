<?php

namespace App\Http\Controllers;

use App\Models\AppSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class AppSettingController extends Controller
{
    public function index(): View
    {
        abort_unless(auth()->user()->hasRole('SuperAdmin'), 403);

        $settings = AppSetting::pluck('value', 'key');

        return view('settings.index', compact('settings'));
    }

    public function update(Request $request): RedirectResponse
    {
        abort_unless(auth()->user()->hasRole('SuperAdmin'), 403);

        $request->validate([
            'app_name' => 'required|string|max:100',
            'app_logo' => 'nullable|image|mimes:png,jpg,jpeg,svg,webp|max:2048',
            'login_image' => 'nullable|image|mimes:png,jpg,jpeg,webp|max:4096',
        ]);

        AppSetting::set('app_name', $request->app_name);

        if ($request->hasFile('app_logo')) {
            $old = AppSetting::get('app_logo');
            if ($old) {
                Storage::disk('public')->delete($old);
            }

            $path = $request->file('app_logo')->store('settings', 'public');
            AppSetting::set('app_logo', $path);
        }

        if ($request->hasFile('login_image')) {
            $old = AppSetting::get('login_image');
            if ($old) {
                Storage::disk('public')->delete($old);
            }

            $path = $request->file('login_image')->store('settings', 'public');
            AppSetting::set('login_image', $path);
        }

        return back()->with('success', 'Pengaturan berhasil disimpan.');
    }

    public function deleteLogo(): RedirectResponse
    {
        abort_unless(auth()->user()->hasRole('SuperAdmin'), 403);

        $old = AppSetting::get('app_logo');
        if ($old) {
            Storage::disk('public')->delete($old);
        }
        AppSetting::set('app_logo', null);

        return back()->with('success', 'Logo berhasil dihapus.');
    }

    public function deleteLoginImage(): RedirectResponse
    {
        abort_unless(auth()->user()->hasRole('SuperAdmin'), 403);

        $old = AppSetting::get('login_image');
        if ($old) {
            Storage::disk('public')->delete($old);
        }
        AppSetting::set('login_image', null);

        return back()->with('success', 'Gambar login berhasil dihapus.');
    }
}
