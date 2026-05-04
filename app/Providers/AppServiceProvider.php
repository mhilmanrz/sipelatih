<?php

namespace App\Providers;

use App\Models\AppSetting;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        try {
            $settings = AppSetting::pluck('value', 'key');
        } catch (\Exception) {
            $settings = collect();
        }

        View::share('appSettings', $settings);
    }
}
