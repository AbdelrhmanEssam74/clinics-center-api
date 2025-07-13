<?php

namespace App\Providers;

use App\Models\Doctor;
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
       
        Gate::define('manage-profile', function (User $user, Patient $patient) {
        return $user->id === $patient->user_id; 
    });

    Gate::define('doctor-profile', function (User $user,Doctor $doctor) {
        return $user->id === $doctor->user_id; 
    });

    }
}
