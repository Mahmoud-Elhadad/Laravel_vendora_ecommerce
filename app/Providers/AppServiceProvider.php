<?php

namespace App\Providers;

use App\Models\Cat;
use App\Models\UserMessage;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

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
    public function boot(): void
    {
        View::composer('Dashboard.layout.sidebar', function ($view) {
            $unreed_ms = UserMessage::where('view', '0')->count();
            $view->with('unreed_ms', $unreed_ms);
        });

        View::composer('components.navbar', function ($view) {
            $view->with('navbar_categories', Cat::orderBy('name')->get());
        });
    }
}
