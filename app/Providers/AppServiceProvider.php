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

        // Event Listeners for Authentication Monitoring
        \Illuminate\Support\Facades\Event::listen(
            \Illuminate\Auth\Events\Login::class,
            function (\Illuminate\Auth\Events\Login $event) {
                try {
                    $agent = new \Jenssegers\Agent\Agent();
                    $agent->setUserAgent(request()->userAgent());
                    
                    \App\Models\ActivityLog::create([
                        'user_id' => $event->user->id,
                        'action' => 'auth_login',
                        'method' => request()->method(),
                        'url' => request()->fullUrl(),
                        'ip_address' => request()->ip(),
                        'details' => [
                            'username' => $event->user->username,
                            'name' => $event->user->name,
                            'browser' => $agent->browser(),
                            'platform' => $agent->platform(),
                            'device' => $agent->isMobile() ? 'Mobile' : ($agent->isTablet() ? 'Tablet' : 'Desktop'),
                            'user_agent' => request()->userAgent(),
                        ],
                    ]);
                } catch (\Exception $e) {
                    \Log::error('Auth login logging failed: ' . $e->getMessage());
                }
            }
        );

        \Illuminate\Support\Facades\Event::listen(
            \Illuminate\Auth\Events\Logout::class,
            function (\Illuminate\Auth\Events\Logout $event) {
                try {
                    if ($event->user) {
                        $agent = new \Jenssegers\Agent\Agent();
                        $agent->setUserAgent(request()->userAgent());
                        
                        \App\Models\ActivityLog::create([
                            'user_id' => $event->user->id,
                            'action' => 'auth_logout',
                            'method' => request()->method(),
                            'url' => request()->fullUrl(),
                            'ip_address' => request()->ip(),
                            'details' => [
                                'username' => $event->user->username,
                                'name' => $event->user->name,
                                'browser' => $agent->browser(),
                                'platform' => $agent->platform(),
                                'device' => $agent->isMobile() ? 'Mobile' : ($agent->isTablet() ? 'Tablet' : 'Desktop'),
                                'user_agent' => request()->userAgent(),
                            ],
                        ]);
                    }
                } catch (\Exception $e) {
                    \Log::error('Auth logout logging failed: ' . $e->getMessage());
                }
            }
        );

        \Illuminate\Support\Facades\Event::listen(
            \Illuminate\Auth\Events\Failed::class,
            function (\Illuminate\Auth\Events\Failed $event) {
                try {
                    $agent = new \Jenssegers\Agent\Agent();
                    $agent->setUserAgent(request()->userAgent());
                    
                    \App\Models\ActivityLog::create([
                        'user_id' => null,
                        'action' => 'auth_failed',
                        'method' => request()->method(),
                        'url' => request()->fullUrl(),
                        'ip_address' => request()->ip(),
                        'details' => [
                            'username' => $event->credentials['username'] ?? ($event->credentials['email'] ?? 'unknown'),
                            'browser' => $agent->browser(),
                            'platform' => $agent->platform(),
                            'device' => $agent->isMobile() ? 'Mobile' : ($agent->isTablet() ? 'Tablet' : 'Desktop'),
                            'user_agent' => request()->userAgent(),
                            'reason' => 'Sai thông tin đăng nhập',
                        ],
                    ]);
                } catch (\Exception $e) {
                    \Log::error('Auth failed logging failed: ' . $e->getMessage());
                }
            }
        );
    }
}
