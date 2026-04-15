@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-slate-100">{{ __('Access Statistics') }}</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">{{ __('Monitor website traffic and user engagement') }}</p>
        </div>
        <div class="flex gap-2">
            <select onchange="window.location.href='?days='+this.value" class="bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-slate-700 dark:text-slate-300 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5">
                <option value="7" {{ $days == 7 ? 'selected' : '' }}>{{ __('Last 7 Days') }}</option>
                <option value="30" {{ $days == 30 ? 'selected' : '' }}>{{ __('Last 30 Days') }}</option>
                <option value="90" {{ $days == 90 ? 'selected' : '' }}>{{ __('Last 90 Days') }}</option>
            </select>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white dark:bg-slate-900 p-6 rounded-xl shadow-sm border border-slate-200 dark:border-slate-800">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 mr-4">
                    <i class="fas fa-users text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-slate-500 dark:text-slate-400">{{ __('Total Visits') }}</p>
                    <h3 class="text-2xl font-bold text-slate-900 dark:text-slate-100">{{ number_format($stats['total_visits']) }}</h3>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-slate-900 p-6 rounded-xl shadow-sm border border-slate-200 dark:border-slate-800">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400 mr-4">
                    <i class="fas fa-user-check text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-slate-500 dark:text-slate-400">{{ __('Today Visits') }}</p>
                    <h3 class="text-2xl font-bold text-slate-900 dark:text-slate-100">{{ number_format($stats['today_visits']) }}</h3>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-slate-900 p-6 rounded-xl shadow-sm border border-slate-200 dark:border-slate-800">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400 mr-4">
                    <i class="fas fa-newspaper text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-slate-500 dark:text-slate-400">{{ __('Total News') }}</p>
                    <h3 class="text-2xl font-bold text-slate-900 dark:text-slate-100">{{ number_format($stats['total_news']) }}</h3>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-slate-900 p-6 rounded-xl shadow-sm border border-slate-200 dark:border-slate-800">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-orange-100 dark:bg-orange-900/30 text-orange-600 dark:text-orange-400 mr-4">
                    <i class="fas fa-user-shield text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-slate-500 dark:text-slate-400">{{ __('Active Users') }}</p>
                    <h3 class="text-2xl font-bold text-slate-900 dark:text-slate-100">{{ number_format($stats['total_users']) }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Visitor Trend -->
        <div class="bg-white dark:bg-slate-900 p-6 rounded-xl shadow-sm border border-slate-200 dark:border-slate-800">
            <h3 class="text-lg font-bold text-slate-900 dark:text-slate-100 mb-4">{{ __('Visitor Trend') }}</h3>
            <div class="h-80">
                <canvas id="visitChart"></canvas>
            </div>
        </div>

        <!-- Device Type -->
        <div class="bg-white dark:bg-slate-900 p-6 rounded-xl shadow-sm border border-slate-200 dark:border-slate-800">
            <h3 class="text-lg font-bold text-slate-900 dark:text-slate-100 mb-4">{{ __('Access by Device') }}</h3>
            <div class="h-80 flex justify-center">
                <canvas id="deviceChart"></canvas>
            </div>
        </div>

        <!-- Top Viewed News -->
        <div class="lg:col-span-2 bg-white dark:bg-slate-900 p-6 rounded-xl shadow-sm border border-slate-200 dark:border-slate-800">
            <h3 class="text-lg font-bold text-slate-900 dark:text-slate-100 mb-4">{{ __('Top Most Viewed News') }}</h3>
            <div class="h-80">
                <canvas id="topNewsChart"></canvas>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // 1. Visit Chart
    const visitCtx = document.getElementById('visitChart').getContext('2d');
    new Chart(visitCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($visitData->pluck('date')) !!},
            datasets: [{
                label: '{{ __("Visits") }}',
                data: {!! json_encode($visitData->pluck('count')) !!},
                borderColor: '#3b82f6',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'bottom' }
            },
            scales: {
                y: { beginAtZero: true, grid: { color: 'rgba(148, 163, 184, 0.1)' } },
                x: { grid: { display: false } }
            }
        }
    });

    // 2. Device Chart
    const deviceCtx = document.getElementById('deviceChart').getContext('2d');
    new Chart(deviceCtx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($deviceData->pluck('device_type')) !!},
            datasets: [{
                data: {!! json_encode($deviceData->pluck('count')) !!},
                backgroundColor: ['#3b82f6', '#10b981', '#f59e0b', '#ef4444']
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'bottom' }
            }
        }
    });

    // 3. Top News Chart
    const newsCtx = document.getElementById('topNewsChart').getContext('2d');
    new Chart(newsCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($topNews->pluck('title')) !!},
            datasets: [{
                label: '{{ __("Views") }}',
                data: {!! json_encode($topNews->pluck('view_count')) !!},
                backgroundColor: '#8b5cf6'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            indexAxis: 'y',
            plugins: {
                legend: { display: false }
            },
            scales: {
                x: { beginAtZero: true, grid: { color: 'rgba(148, 163, 184, 0.1)' } },
                y: { grid: { display: false } }
            }
        }
    });
</script>
@endpush
@endsection
