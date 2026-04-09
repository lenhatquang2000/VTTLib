@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold">{{ __('Thống kê lượt truy cập website') }}</h1>
            <p class="text-sm text-gray-400 mt-1">{{ __('Thống kê truy cập website thư viện theo thời gian') }}</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.circulation.reports.index') }}" class="btn-secondary">
                <i class="fas fa-arrow-left mr-2"></i>{{ __('Quay lại') }}
            </a>
        </div>
    </div>

    <!-- Filter Form -->
    <div class="card-admin p-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium mb-1">{{ __('Từ ngày') }}</label>
                <input type="date" name="start_date" value="{{ $startDate }}" class="input-field w-full">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">{{ __('Đến ngày') }}</label>
                <input type="date" name="end_date" value="{{ $endDate }}" class="input-field w-full">
            </div>
            <div class="flex items-end">
                <button type="submit" class="btn-primary w-full">
                    <i class="fas fa-filter mr-2"></i>{{ __('Lọc') }}
                </button>
            </div>
        </form>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="card-admin p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-400">{{ __('Tổng lượt truy cập') }}</p>
                    <p class="text-2xl font-bold text-blue-400">{{ number_format($stats['total_visits']) }}</p>
                </div>
                <i class="fas fa-globe text-3xl text-blue-400 opacity-20"></i>
            </div>
        </div>
        <div class="card-admin p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-400">{{ __('Session duy nhất') }}</p>
                    <p class="text-2xl font-bold text-green-400">{{ number_format($stats['unique_sessions']) }}</p>
                </div>
                <i class="fas fa-window-restore text-3xl text-green-400 opacity-20"></i>
            </div>
        </div>
        <div class="card-admin p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-400">{{ __('Khách truy cập') }}</p>
                    <p class="text-2xl font-bold text-yellow-400">{{ number_format($stats['unique_visitors']) }}</p>
                </div>
                <i class="fas fa-user-shield text-3xl text-yellow-400 opacity-20"></i>
            </div>
        </div>
        <div class="card-admin p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-400">{{ __('User đã đăng nhập') }}</p>
                    <p class="text-2xl font-bold text-purple-400">{{ number_format($stats['registered_users']) }}</p>
                </div>
                <i class="fas fa-user-check text-3xl text-purple-400 opacity-20"></i>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- By Device Type -->
        <div class="card-admin p-6">
            <h3 class="text-lg font-bold mb-4">{{ __('Theo thiết bị') }}</h3>
            <div class="space-y-2">
                @foreach($stats['by_device_type'] as $device)
                <div class="flex justify-between items-center">
                    <span class="text-sm">{{ $device->device_type }}</span>
                    <div class="flex items-center">
                        <div class="w-32 bg-gray-700 rounded-full h-2 mr-2">
                            <div class="bg-blue-500 h-2 rounded-full" style="width: {{ ($device->count / $stats['total_visits']) * 100 }}%"></div>
                        </div>
                        <span class="text-sm font-medium">{{ $device->count }}</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- By Access Type -->
        <div class="card-admin p-6">
            <h3 class="text-lg font-bold mb-4">{{ __('Theo loại truy cập') }}</h3>
            <div class="space-y-2">
                @foreach($stats['by_access_type'] as $access)
                <div class="flex justify-between items-center">
                    <span class="text-sm">{{ $access->access_type }}</span>
                    <div class="flex items-center">
                        <div class="w-32 bg-gray-700 rounded-full h-2 mr-2">
                            <div class="bg-green-500 h-2 rounded-full" style="width: {{ ($access->count / $stats['total_visits']) * 100 }}%"></div>
                        </div>
                        <span class="text-sm font-medium">{{ $access->count }}</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Browser Statistics -->
    <div class="card-admin p-6">
        <h3 class="text-lg font-bold mb-4">{{ __('Theo trình duyệt') }}</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($stats['by_browser'] as $browser)
            <div class="flex justify-between items-center p-3 bg-gray-800 rounded-lg">
                <span class="text-sm">{{ $browser->browser }}</span>
                <span class="text-sm font-bold">{{ $browser->count }}</span>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Top Pages -->
    <div class="card-admin p-6">
        <h3 class="text-lg font-bold mb-4">{{ __('Trang được truy cập nhiều nhất') }}</h3>
        <div class="space-y-2">
            @foreach($stats['top_pages'] as $page)
            <div class="flex justify-between items-center p-3 bg-gray-800 rounded-lg">
                <div class="flex-1 mr-4">
                    <span class="text-sm truncate block">{{ $page->page_url }}</span>
                </div>
                <span class="text-sm font-bold">{{ $page->count }}</span>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Daily Statistics -->
    <div class="card-admin p-6">
        <h3 class="text-lg font-bold mb-4">{{ __('Thống kê theo ngày') }}</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-700">
                        <th class="text-left py-2">{{ __('Ngày') }}</th>
                        <th class="text-center py-2">{{ __('Lượt truy cập') }}</th>
                        <th class="text-center py-2">{{ __('Session') }}</th>
                        <th class="text-center py-2">{{ (%) }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($stats['daily_stats'] as $daily)
                    <tr class="border-b border-gray-800">
                        <td class="py-2">{{ Carbon::parse($daily->date)->format('d/m/Y') }}</td>
                        <td class="text-center py-2">{{ $daily->visits }}</td>
                        <td class="text-center py-2">{{ $daily->sessions }}</td>
                        <td class="text-center py-2">{{ round(($daily->visits / $stats['total_visits']) * 100, 1) }}%</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Detailed Access Log -->
    <div class="card-admin p-6">
        <h3 class="text-lg font-bold mb-4">{{ __('Chi tiết truy cập') }}</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-700">
                        <th class="text-left py-2">{{ __('Thời gian') }}</th>
                        <th class="text-left py-2">{{ __('Người dùng') }}</th>
                        <th class="text-left py-2">{{ __('IP Address') }}</th>
                        <th class="text-left py-2">{{ __('Thiết bị') }}</th>
                        <th class="text-left py-2">{{ __('Trình duyệt') }}</th>
                        <th class="text-left py-2">{{ __('Loại') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($accesses as $access)
                    <tr class="border-b border-gray-800">
                        <td class="py-2">{{ $access->access_time->format('d/m/Y H:i') }}</td>
                        <td class="py-2">
                            @if($access->user)
                                {{ $access->user->name }}
                            @else
                                <span class="text-gray-400">{{ __('Khách') }}</span>
                            @endif
                        </td>
                        <td class="py-2">{{ $access->ip_address ?? '-' }}</td>
                        <td class="py-2">{{ $access->device_type ?? '-' }}</td>
                        <td class="py-2">{{ $access->browser ?? '-' }}</td>
                        <td class="py-2">
                            <span class="px-2 py-1 text-xs rounded-full bg-blue-900/30 text-blue-400">
                                {{ $access->access_type }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="mt-4">
            {{ $accesses->links() }}
        </div>
    </div>
</div>
@endsection
