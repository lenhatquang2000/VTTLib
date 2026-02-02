<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;
use App\Observers\UserObserver;

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
        // Register observers
        User::observe(UserObserver::class);
        
        // Implicitly grant "super-admin" role all permissions
        // This works in the app by using gate-related helpers like auth()->user()->can()
        Gate::before(function (User $user, string $ability) {
            if ($user->hasRole('root')) {
                return true;
            }
        });
    }
}
