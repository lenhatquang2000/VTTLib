<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Jenssegers\Agent\Agent;

class SystemMonitoringController extends Controller
{
    /**
     * Show the system monitoring dashboard.
     */
    public function index(Request $request)
    {
        // 1. Fetch online sessions from the database
        $activeThreshold = now()->subMinutes(15)->getTimestamp();
        
        $sessions = DB::table('sessions')
            ->where('last_activity', '>=', $activeThreshold)
            ->orderBy('last_activity', 'desc')
            ->get();
            
        // Map user details and parse user agent
        $onlineUsers = $sessions->map(function ($session) {
            $user = null;
            if ($session->user_id) {
                $user = User::find($session->user_id);
            }
            
            // Parse User Agent using Jenssegers\Agent
            $agent = new Agent();
            $agent->setUserAgent($session->user_agent);
            
            $browser = $agent->browser();
            $platform = $agent->platform(); // OS
            $deviceType = $agent->isMobile() ? 'mobile' : ($agent->isTablet() ? 'tablet' : 'desktop');
            
            return (object) [
                'session_id' => $session->id,
                'user_id' => $session->user_id,
                'user' => $user,
                'ip_address' => $session->ip_address,
                'last_activity' => $session->last_activity,
                'browser' => $browser,
                'platform' => $platform,
                'device_type' => $deviceType,
            ];
        });

        // 2. Fetch authentication history logs (auth_login, auth_logout, auth_failed)
        $authQuery = ActivityLog::with('user')
            ->whereIn('action', ['auth_login', 'auth_logout', 'auth_failed'])
            ->latest();

        // Filters for auth history
        if ($request->filled('username')) {
            $authQuery->where(function($q) use ($request) {
                $q->whereHas('user', function ($uq) use ($request) {
                    $uq->where('username', 'like', '%' . $request->username . '%')
                      ->orWhere('name', 'like', '%' . $request->username . '%');
                })->orWhere('details->username', 'like', '%' . $request->username . '%');
            });
        }
        
        if ($request->filled('action')) {
            $authQuery->where('action', $request->action);
        }
        
        if ($request->filled('ip_address')) {
            $authQuery->where('ip_address', 'like', '%' . $request->ip_address . '%');
        }

        if ($request->filled('date_from')) {
            $authQuery->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $authQuery->whereDate('created_at', '<=', $request->date_to);
        }

        $authLogs = $authQuery->paginate(15, ['*'], 'auth_page')->withQueryString();

        // 3. Fetch guest/public website access logs (from website_access_logs)
        $publicQuery = DB::table('website_access_logs')
            ->leftJoin('users', 'website_access_logs.user_id', '=', 'users.id')
            ->select('website_access_logs.*', 'users.name as user_name', 'users.username as user_username')
            ->latest();

        if ($request->filled('public_ip')) {
            $publicQuery->where('website_access_logs.ip_address', 'like', '%' . $request->public_ip . '%');
        }

        if ($request->filled('public_url')) {
            $publicQuery->where('website_access_logs.url', 'like', '%' . $request->public_url . '%');
        }
        
        if ($request->filled('is_guest') && $request->is_guest == '1') {
            $publicQuery->whereNull('website_access_logs.user_id');
        }

        $publicLogs = $publicQuery->paginate(15, ['*'], 'public_page')->withQueryString();

        return view('admin.monitoring.index', compact('onlineUsers', 'authLogs', 'publicLogs'));
    }

    /**
     * Terminate the given session (Force Logout).
     */
    public function kickSession(Request $request, $id)
    {
        try {
            DB::table('sessions')->where('id', $id)->delete();
            return back()->with('success', 'Đã ép buộc đăng xuất phiên làm việc thành công.');
        } catch (\Exception $e) {
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }
}
