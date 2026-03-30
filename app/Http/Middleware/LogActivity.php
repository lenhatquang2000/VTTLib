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
    public function handle(Request $request, Closure $next)
    {
        // Update last activity time for session expiration check
        if (auth()->check()) {
            session(['last_activity' => time()]);
        }

        $response = $next($request);

        // Only log if authenticated and it's not a simple GET request for common assets
        // or we can just log all non-GET requests for administration/root
        if (auth()->check() && !$request->isMethod('GET')) {
            $action = 'request_' . strtolower($request->method());
            
            // Try to guess a better action name from the route
            if ($request->route()) {
                $action = $request->route()->getName() ?? $action;
            }

            $this->logActivity($action, $request);
        }

        return $response;
    }

    /**
     * Log user activity
     */
    private function logActivity(string $action, Request $request)
    {
        try {
            $user = auth()->user();
            
            // Prepare details for patron-related actions
            $details = [];
            $modelType = null;
            $modelId = null;
            
            // Check if this is a patron-related action
            if (str_contains($action, 'patron') || str_contains($request->path(), 'patrons')) {
                $details['category'] = 'patron_management';
                
                // Extract patron ID from request if available
                if ($request->route('id')) {
                    $details['patron_id'] = $request->route('id');
                    $modelType = 'PatronDetail';
                    $modelId = $request->route('id');
                }
                
                // Add request data for POST/PUT/DELETE
                if ($request->hasAny(['name', 'email', 'patron_code', 'status'])) {
                    $details['request_data'] = $request->only(['name', 'email', 'patron_code', 'status']);
                }
            }
            
            ActivityLog::create([
                'user_id' => $user->id,
                'action' => $action,
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'ip_address' => $request->ip(),
                'model_type' => $modelType,
                'model_id' => $modelId,
                'details' => !empty($details) ? $details : null,
                'request_data' => $request->except(['password', 'password_confirmation', '_token']),
                'created_at' => now(),
            ]);
        } catch (\Exception $e) {
            // Silent fail - don't break the request if logging fails
            \Log::error('Activity logging failed: ' . $e->getMessage());
        }
    }
}
