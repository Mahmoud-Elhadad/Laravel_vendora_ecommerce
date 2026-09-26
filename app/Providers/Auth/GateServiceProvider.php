<?php

namespace App\Providers\Auth;

use App\Models\Admin;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class GateServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Gate::define("delete-access" , function(Admin $admin){
            return $admin->role === "super admin";
        });

        Gate::define("update-access" , function(Admin $admin){
            return $admin->role === "super admin" || $admin->role === "admin";
        });

        Gate::define("show-dashboard" , function(Admin $admin){
            return in_array($admin->role , ["super admin" , "admin" , "manager" , "sales" , "support"]);
        });


    }
}
