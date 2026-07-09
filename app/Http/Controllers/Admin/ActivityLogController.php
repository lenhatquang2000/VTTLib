<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $query = ActivityLog::with('user')->latest();

        // Filters
        if ($request->filled('username')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('username', 'like', '%' . $request->username . '%')
                  ->orWhere('name', 'like', '%' . $request->username . '%');
            });
        }

        if ($request->filled('action')) {
            $query->where('action', 'like', '%' . $request->action . '%');
        }

        if ($request->filled('method')) {
            $query->where('method', $request->method);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $logs = $query->paginate(20)->withQueryString();
        
        // Fetch Laravel log entries
        $laravelLogs = $this->parseLaravelLogs();

        return view('admin.activity_logs.index', compact('logs', 'laravelLogs'));
    }

    public function show(ActivityLog $log)
    {
        return view('admin.activity_logs.show', compact('log'));
    }

    /**
     * Clear or filter Laravel logs.
     */
    public function clearLaravelLogs(Request $request)
    {
        $logPath = storage_path('logs/laravel.log');
        if (!file_exists($logPath)) {
            return back()->with('success', 'Tệp log không tồn tại.');
        }

        $type = $request->input('clear_type', 'all');

        if ($type === 'all') {
            file_put_contents($logPath, '');
            return back()->with('success', 'Đã xóa toàn bộ logs thành công.');
        }

        $levelToClear = strtoupper($type); // e.g. "INFO", "WARNING", "ERROR"
        
        $tempPath = storage_path('logs/laravel_temp.log');
        $handle = fopen($logPath, 'r');
        $writeHandle = fopen($tempPath, 'w');
        
        $skipMode = false;
        while (($line = fgets($handle)) !== false) {
            if (preg_match('/^\[\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}\]\s+[a-zA-Z0-9]+\.([A-Z]+):/', $line, $parts)) {
                $level = $parts[1];
                if ($level === $levelToClear) {
                    $skipMode = true; // start skipping this entry and its stack trace
                } else {
                    $skipMode = false;
                }
            }
            
            if (!$skipMode) {
                fwrite($writeHandle, $line);
            }
        }
        
        fclose($handle);
        fclose($writeHandle);
        
        if (file_exists($tempPath)) {
            rename($tempPath, $logPath);
        }
        
        return back()->with('success', "Đã xóa tất cả log có mức độ {$levelToClear} thành công.");
    }

    /**
     * Parse laravel.log file safely.
     */
    private function parseLaravelLogs()
    {
        $logPath = storage_path('logs/laravel.log');
        if (!file_exists($logPath)) {
            return [];
        }

        // Read log file line-by-line using SplFileObject for large files safety
        $file = new \SplFileObject($logPath, 'r');
        $entries = [];
        $currentEntry = null;

        while (!$file->eof()) {
            $line = $file->fgets();
            if (empty(trim($line))) continue;

            if (preg_match('/^\[(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})\]\s+([a-zA-Z0-9]+)\.([A-Z]+):\s+(.*)$/', $line, $parts)) {
                if ($currentEntry) {
                    $entries[] = $currentEntry;
                }
                $currentEntry = [
                    'timestamp' => $parts[1],
                    'env' => $parts[2],
                    'level' => $parts[3],
                    'message' => $parts[4],
                    'stack_trace' => ''
                ];
            } else {
                if ($currentEntry) {
                    $currentEntry['stack_trace'] .= $line;
                } else {
                    $entries[] = [
                        'timestamp' => now()->format('Y-m-d H:i:s'),
                        'env' => 'local',
                        'level' => 'RAW',
                        'message' => $line,
                        'stack_trace' => ''
                    ];
                }
            }
        }

        if ($currentEntry) {
            $entries[] = $currentEntry;
        }

        // Return the latest 200 entries
        return array_slice(array_reverse($entries), 0, 200);
    }
}
