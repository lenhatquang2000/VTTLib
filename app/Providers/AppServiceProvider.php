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
        // Register JSON translations path for client
        $this->app->make('translator')->addJsonPath(resource_path('lang/client'));

        // Tuyệt đối không dùng env() ở đây, chỉ dùng config()
        if (config('app.env') !== 'local' || config('app.force_https')) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

        // Đảm bảo database luôn đúng (Sửa lỗi kẹt cấu hình trên server)
        if (config('database.connections.mysql.database') === 'educationalmaterialssystem') {
            config(['database.connections.mysql.database' => 'vttu_lib']);
        }

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
