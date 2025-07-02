<?php

namespace App\Providers;

use App\Models\Patient;
use App\Models\User;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
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
       
        Gate::define('manage-profile', function ($user, $patient) {
        return $user->id === $patient->user_id; 
    });

    }
}
