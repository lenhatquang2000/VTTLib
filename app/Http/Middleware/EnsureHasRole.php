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
        if (!$request->user() || !$request->user()->hasRole($role)) {
            // Nếu là admin route mà không có quyền admin
            if ($role === 'admin') {
                return abort(403, 'Unauthorized access to Agent Area.');
            }
            // Nếu là khách mà không có quyền visitor (ví dụ bị khóa hoặc chưa gán quyền)
            return abort(403, 'Access Denied.');
        }

        return $next($request);
    }
}
