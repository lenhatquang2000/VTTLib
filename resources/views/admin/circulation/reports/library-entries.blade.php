@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold">{{ __('Số lượng bạn đọc vào thư viện') }}</h1>
            <p class="text-sm text-gray-400 mt-1">{{ __('Thống kê lượt ra vào thư viện theo thời gian') }}</p>
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
                    <p class="text-sm text-gray-400">{{ __('Tổng lượt vào') }}</p>
                    <p class="text-2xl font-bold text-blue-400">{{ number_format($stats['total_entries']) }}</p>
                </div>
                <i class="fas fa-door-open text-3xl text-blue-400 opacity-20"></i>
            </div>
        </div>
        <div class="card-admin p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-400">{{ __('Bạn đọc duy nhất') }}</p>
                    <p class="text-2xl font-bold text-green-400">{{ number_format($stats['unique_patrons']) }}</p>
                </div>
                <i class="fas fa-users text-3xl text-green-400 opacity-20"></i>
            </div>
        </div>
        <div class="card-admin p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-400">{{ __('Thời gian trung bình') }}</p>
                    <p class="text-2xl font-bold text-yellow-400">{{ round($stats['average_duration'] ?? 0) }} {{ __('phút') }}</p>
                </div>
                <i class="fas fa-clock text-3xl text-yellow-400 opacity-20"></i>
            </div>
        </div>
        <div class="card-admin p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-400">{{ __('Trung bình/ngày') }}</p>
                    <p class="text-2xl font-bold text-purple-400">{{ round($stats['total_entries'] / max(Carbon::parse($startDate)->diffInDays(Carbon::parse($endDate)) + 1, 1)) }}</p>
                </div>
                <i class="fas fa-chart-line text-3xl text-purple-400 opacity-20"></i>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- By Purpose -->
        <div class="card-admin p-6">
            <h3 class="text-lg font-bold mb-4">{{ __('Theo mục đích') }}</h3>
            <div class="space-y-2">
                @foreach($stats['by_purpose'] as $purpose)
                <div class="flex justify-between items-center">
                    <span class="text-sm">{{ $purpose->purpose }}</span>
                    <div class="flex items-center">
                        <div class="w-32 bg-gray-700 rounded-full h-2 mr-2">
                            <div class="bg-blue-500 h-2 rounded-full" style="width: {{ ($purpose->count / $stats['total_entries']) * 100 }}%"></div>
                        </div>
                        <span class="text-sm font-medium">{{ $purpose->count }}</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- By Branch -->
        <div class="card-admin p-6">
            <h3 class="text-lg font-bold mb-4">{{ __('Theo chi nhánh') }}</h3>
            <div class="space-y-2">
                @foreach($stats['by_branch'] as $branch)
                <div class="flex justify-between items-center">
                    <span class="text-sm">{{ $branch->name }}</span>
                    <div class="flex items-center">
                        <div class="w-32 bg-gray-700 rounded-full h-2 mr-2">
                            <div class="bg-green-500 h-2 rounded-full" style="width: {{ ($branch->count / $stats['total_entries']) * 100 }}%"></div>
                        </div>
                        <span class="text-sm font-medium">{{ $branch->count }}</span>
                    </div>
                </div>
                @endforeach
            </div>
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
                        <th class="text-center py-2">{{ __('Số lượt') }}</th>
                        <th class="text-center py-2">{{ (%) }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($stats['daily_stats'] as $daily)
                    <tr class="border-b border-gray-800">
                        <td class="py-2">{{ Carbon::parse($daily->date)->format('d/m/Y') }}</td>
                        <td class="text-center py-2">{{ $daily->count }}</td>
                        <td class="text-center py-2">{{ round(($daily->count / $stats['total_entries']) * 100, 1) }}%</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Detailed Entries -->
    <div class="card-admin p-6">
        <h3 class="text-lg font-bold mb-4">{{ __('Chi tiết lượt ra vào') }}</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-700">
                        <th class="text-left py-2">{{ __('Thời gian vào') }}</th>
                        <th class="text-left py-2">{{ __('Bạn đọc') }}</th>
                        <th class="text-left py-2">{{ __('Mục đích') }}</th>
                        <th class="text-left py-2">{{ __('Chi nhánh') }}</th>
                        <th class="text-left py-2">{{ __('Thời gian ở lại') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($entries as $entry)
                    <tr class="border-b border-gray-800">
                        <td class="py-2">{{ $entry->entry_time->format('d/m/Y H:i') }}</td>
                        <td class="py-2">
                            @if($entry->patron)
                                {{ $entry->patron->display_name ?? $entry->patron->user->name }}
                                <br>
                                <span class="text-xs text-gray-400">{{ $entry->patron->patron_code }}</span>
                            @else
                                <span class="text-gray-400">{{ __('Khách vãng lai') }}</span>
                            @endif
                        </td>
                        <td class="py-2">{{ $entry->purpose ?? '-' }}</td>
                        <td class="py-2">{{ $entry->branch?->name ?? '-' }}</td>
                        <td class="py-2">{{ $entry->getFormattedDuration() }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="mt-4">
            {{ $entries->links() }}
        </div>
    </div>
</div>
@endsection
