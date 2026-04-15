<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use Illuminate\Support\Facades\DB;
use Jenssegers\Agent\Agent;

class TrackWebsiteAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Only track successful GET requests for public site (not admin or API)
        if ($request->isMethod('GET') && !str_contains($request->path(), 'topsecret') && !str_contains($request->path(), 'api')) {
            try {
                $deviceType = null;
                $browser = null;
                $platform = null;

                if (class_exists(Agent::class)) {
                    $agent = new Agent();
                    $agent->setUserAgent($request->userAgent());

                    $deviceType = $agent->isMobile() ? 'mobile' : ($agent->isTablet() ? 'tablet' : 'desktop');
                    $browser = $agent->browser();
                    $platform = $agent->platform();
                }

                DB::table('website_access_logs')->insert([
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'url' => $request->fullUrl(),
                    'referer' => $request->headers->get('referer'),
                    'user_id' => auth()->id(),
                    'session_id' => session()->getId(),
                    'device_type' => $deviceType,
                    'browser' => $browser,
                    'platform' => $platform,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } catch (\Throwable $e) {
                // Fail silently to not disrupt user experience
                \Log::error('Website access logging failed: ' . $e->getMessage());
            }
        }

        return $response;
    }
}
