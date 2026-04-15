<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StatisticsController extends Controller
{
    public function index(Request $request)
    {
        $days = $request->get('days', 30);
        $startDate = Carbon::now()->subDays($days);

        // 1. Visitor Statistics (Line Chart)
        $visitData = DB::table('website_access_logs')
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
            ->where('created_at', '>=', $startDate)
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // 2. Device Type (Pie Chart)
        $deviceData = DB::table('website_access_logs')
            ->select('device_type', DB::raw('count(*) as count'))
            ->where('created_at', '>=', $startDate)
            ->groupBy('device_type')
            ->get();

        // 3. Most Viewed News (Bar Chart)
        $topNews = DB::table('news')
            ->select('title', 'view_count')
            ->orderBy('view_count', 'desc')
            ->limit(10)
            ->get();

        // 4. Summary Stats
        $stats = [
            'total_visits' => DB::table('website_access_logs')->count(),
            'today_visits' => DB::table('website_access_logs')->whereDate('created_at', Carbon::today())->count(),
            'total_news' => DB::table('news')->count(),
            'total_users' => DB::table('users')->count(),
        ];

        return view('admin.statistics.index', compact('visitData', 'deviceData', 'topNews', 'stats', 'days'));
    }
}
