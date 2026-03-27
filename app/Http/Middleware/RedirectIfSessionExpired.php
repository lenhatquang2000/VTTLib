<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfSessionExpired
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // If user is authenticated and session has expired
        if (Auth::check() && $this->sessionExpired($request)) {
            // Clear the session
            Auth::logout();
            
            // Redirect to home page with a message
            return redirect()->route('client.home')
                ->with('message', 'Your session has expired. Please log in again.');
        }

        return $next($request);
    }

    /**
     * Check if session has expired
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function sessionExpired($request)
    {
        $lastActivity = session('last_activity');
        
        // If no last activity recorded, consider session expired
        if (!$lastActivity) {
            return true;
        }

        // Session lifetime in minutes (from config)
        $lifetime = config('session.lifetime', 120); // 120 minutes default
        
        // Check if session has expired
        return (time() - $lastActivity) > ($lifetime * 60);
    }
}
