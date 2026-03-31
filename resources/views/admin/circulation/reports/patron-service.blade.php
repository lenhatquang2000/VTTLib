@extends('layouts.admin')

@section('content')
<div class="report-detail-page bg-white dark:bg-slate-900 rounded-lg shadow-xl overflow-hidden">
    <div class="p-6 border-b border-indigo-200 dark:border-indigo-900/30 flex justify-between items-center bg-indigo-50 dark:bg-indigo-950/20">
        <div>
            <h1 class="text-2xl font-black text-indigo-800 dark:text-indigo-400 uppercase tracking-tight flex items-center">
                <i class="fa-solid fa-users-gear mr-3 opacity-70"></i>
                {{ __('Patron Service Report') }}
            </h1>
            <p class="text-sm text-indigo-600/70 dark:text-indigo-400/50 mt-1">
                {{ __('Statistical analysis of patron groups and service activities.') }}
            </p>
        </div>
        <a href="{{ route('admin.circulation.reports.index') }}" class="px-4 py-2 bg-slate-200 dark:bg-slate-800 text-slate-700 dark:text-slate-300 rounded-lg font-bold text-xs uppercase tracking-widest hover:bg-slate-300 dark:hover:bg-slate-700 transition-all shadow-sm">
            <i class="fa-solid fa-arrow-left mr-2"></i>{{ __('Back') }}
        </a>
    </div>

    <div class="p-8 grid grid-cols-1 lg:grid-cols-2 gap-8">
        {{-- Patron Groups Distribution --}}
        <div class="card-admin p-6 rounded-xl bg-white dark:bg-slate-800/50 border border-slate-100 dark:border-slate-700">
            <h3 class="text-sm font-black uppercase tracking-widest text-slate-500 mb-6 border-b border-slate-100 dark:border-slate-700 pb-2">
                {{ __('Patron Group Distribution') }}
            </h3>
            <div class="h-[300px] flex items-center justify-center">
                <canvas id="patronGroupsChart"></canvas>
            </div>
        </div>

        {{-- Loan Activity Trend --}}
        <div class="card-admin p-6 rounded-xl bg-white dark:bg-slate-800/50 border border-slate-100 dark:border-slate-700">
            <h3 class="text-sm font-black uppercase tracking-widest text-slate-500 mb-6 border-b border-slate-100 dark:border-slate-700 pb-2">
                {{ __('Loan Activity (Last 30 Days)') }}
            </h3>
            <div class="h-[300px]">
                <canvas id="loanTrendChart"></canvas>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Patron Groups Chart
        const stats = @json($stats);
        new Chart(document.getElementById('patronGroupsChart'), {
            type: 'doughnut',
            data: {
                labels: stats.map(s => s.group_name),
                datasets: [{
                    data: stats.map(s => s.total),
                    backgroundColor: [
                        '#6366f1', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#06b6d4'
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom', labels: { color: localStorage.getItem('theme') === 'dark' ? '#94a3b8' : '#475569' } }
                }
            }
        });

        // Loan Trend Chart
        const loanStats = @json($loanStats);
        new Chart(document.getElementById('loanTrendChart'), {
            type: 'line',
            data: {
                labels: loanStats.map(s => s.date),
                datasets: [{
                    label: '{{ __('New Loans') }}',
                    data: loanStats.map(s => s.count),
                    borderColor: '#6366f1',
                    backgroundColor: 'rgba(99, 102, 241, 0.1)',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: { ticks: { color: '#94a3b8' } },
                    y: { beginAtZero: true, ticks: { color: '#94a3b8' } }
                },
                plugins: {
                    legend: { display: false }
                }
            }
        });
    });
</script>
@endpush
@endsection
