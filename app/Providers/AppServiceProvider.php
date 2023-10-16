<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Admin; // Make sure to import the Admin model

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        View::composer('layouts.app', function ($view) {
            $adminRegistered = Admin::where('user_type', 'admin')->exists();
            $view->with('adminRegistered', $adminRegistered);
        });
    }
}
