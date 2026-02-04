<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\ActivityLog;

class LogActivity
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Only log if authenticated and it's not a simple GET request for common assets
        // or we can just log all non-GET requests for administration/root
        if (auth()->check() && !$request->isMethod('GET')) {
            $action = 'request_' . strtolower($request->method());
            
            // Try to guess a better action name from the route
            if ($request->route()) {
                $action = $request->route()->getName() ?? $action;
            }

            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => $action,
                'method' => $request->method(),
                'url' => $request->fullUrl(),
                'status_code' => $response->getStatusCode(),
                'request_data' => $request->except(['password', 'password_confirmation', '_token']),
                'ip_address' => $request->ip()
            ]);
        }

        return $response;
    }
}
