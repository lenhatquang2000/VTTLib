<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureHasRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        $user = $request->user();
        if (!$user) {
            return abort(403, 'Access Denied.');
        }

        if ($role === 'admin') {
            $isAdminOrRoot = $user->hasRole('admin') || $user->hasRole('root');
            $hasAssignedTabs = $user->getSidebarTabs()->isNotEmpty();

            if ($isAdminOrRoot || $hasAssignedTabs) {
                return $next($request);
            }

            // Debug block if requested (can be seen in 403 response or logs)
            // Uncomment for deep debug:
            // dd(['is_admin_root' => $isAdminOrRoot, 'has_tabs' => $hasAssignedTabs, 'roles' => $user->roles->pluck('name')]);
            
            return abort(403, 'Unauthorized access to Agent Area.');
        }

        // Standard role check for other roles (visitor, root, etc.)
        if (!$user->hasRole($role)) {
            return abort(403, 'Access Denied.');
        }

        return $next($request);
    }
}
