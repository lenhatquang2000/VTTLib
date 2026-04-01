@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center bg-white dark:bg-slate-900 p-6 rounded-xl shadow-sm border border-gray-100 dark:border-slate-800">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 dark:text-slate-100">{{ __('MARC Cataloging Reports') }}</h2>
            <p class="text-sm text-gray-500 dark:text-slate-400 mt-1">{{ __('Analytics and insights for cataloging operations') }}</p>
        </div>
        <a href="{{ route('admin.marc.book') }}"
            class="flex items-center px-4 py-2.5 text-sm font-semibold bg-gray-100 dark:bg-slate-800 text-gray-600 dark:text-slate-400 hover:bg-gray-200 dark:hover:bg-slate-700 rounded-lg transition">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            {{ __('Back to Cataloging') }}
        </a>
    </div>

    <!-- Filters -->
    <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-gray-100 dark:border-slate-800 overflow-hidden">
        <div class="p-6">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                @csrf
                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-2">{{ __('Framework') }}</label>
                    <select name="framework_id"
                        class="w-full px-4 py-2.5 border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 dark:text-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">{{ __('All Frameworks') }}</option>
                        @foreach($frameworks as $framework)
                        <option value="{{ $framework->id }}" {{ request('framework_id') == $framework->id ? 'selected' : '' }}>
                            {{ $framework->name }} ({{ $framework->code }})
                        </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-2">{{ __('Date From') }}</label>
                    <input type="date" name="date_from" value="{{ $dateRange['from']->format('Y-m-d') }}"
                        class="w-full px-4 py-2.5 border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 dark:text-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-2">{{ __('Date To') }}</label>
                    <input type="date" name="date_to" value="{{ $dateRange['to']->format('Y-m-d') }}"
                        class="w-full px-4 py-2.5 border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 dark:text-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <div class="flex items-end">
                    <button type="submit"
                        class="w-full bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2.5 rounded-lg text-sm font-semibold transition">
                        {{ __('Apply Filters') }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Statistics Overview -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-gray-100 dark:border-slate-800 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-slate-400">{{ __('Total Records') }}</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-slate-100">{{ number_format($stats['total_records']) }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center">
                    <i class="fas fa-book text-blue-600 dark:text-blue-400"></i>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-gray-100 dark:border-slate-800 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-slate-400">{{ __('Approved') }}</p>
                    <p class="text-2xl font-bold text-green-600 dark:text-green-400">{{ number_format($stats['by_status']['approved'] ?? 0) }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center">
                    <i class="fas fa-check-circle text-green-600 dark:text-green-400"></i>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-gray-100 dark:border-slate-800 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-slate-400">{{ __('Pending') }}</p>
                    <p class="text-2xl font-bold text-amber-600 dark:text-amber-400">{{ number_format($stats['by_status']['pending'] ?? 0) }}</p>
                </div>
                <div class="w-12 h-12 bg-amber-100 dark:bg-amber-900/30 rounded-lg flex items-center justify-center">
                    <i class="fas fa-clock text-amber-600 dark:text-amber-400"></i>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-gray-100 dark:border-slate-800 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-slate-400">{{ __('Approval Rate') }}</p>
                    <p class="text-2xl font-bold text-indigo-600 dark:text-indigo-400">{{ number_format($stats['approved_rate'], 1) }}%</p>
                </div>
                <div class="w-12 h-12 bg-indigo-100 dark:bg-indigo-900/30 rounded-lg flex items-center justify-center">
                    <i class="fas fa-chart-line text-indigo-600 dark:text-indigo-400"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts and Analytics -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Daily Trend -->
        <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-gray-100 dark:border-slate-800 p-6">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-slate-100 mb-4">{{ __('Daily Cataloging Trend') }}</h3>
            <div class="h-64 flex items-center justify-center text-gray-400 dark:text-slate-500">
                <div class="text-center">
                    <i class="fas fa-chart-area text-4xl mb-2"></i>
                    <p>{{ __('Chart will be displayed here') }}</p>
                </div>
            </div>
        </div>

        <!-- Records by Type -->
        <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-gray-100 dark:border-slate-800 p-6">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-slate-100 mb-4">{{ __('Records by Type') }}</h3>
            <div class="space-y-3">
                @foreach($stats['by_type'] as $type => $count)
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600 dark:text-slate-400">{{ ucfirst($type) }}</span>
                    <div class="flex items-center">
                        <div class="w-32 bg-gray-200 dark:bg-slate-700 rounded-full h-2 mr-3">
                            <div class="bg-indigo-600 h-2 rounded-full" style="width: {{ ($count / $stats['total_records']) * 100 }}%"></div>
                        </div>
                        <span class="text-sm font-medium text-gray-900 dark:text-slate-100">{{ number_format($count) }}</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Productivity Metrics -->
    <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-gray-100 dark:border-slate-800 overflow-hidden">
        <div class="p-6">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-slate-100 mb-4">{{ __('Productivity Metrics') }}</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="text-center">
                    <p class="text-3xl font-bold text-indigo-600 dark:text-indigo-400">{{ number_format($productivity['avg_per_day'], 1) }}</p>
                    <p class="text-sm text-gray-600 dark:text-slate-400 mt-1">{{ __('Avg Records/Day') }}</p>
                </div>
                <div class="text-center">
                    <p class="text-3xl font-bold text-green-600 dark:text-green-400">{{ number_format($quality['completeness_rate'], 1) }}%</p>
                    <p class="text-sm text-gray-600 dark:text-slate-400 mt-1">{{ __('Completeness Rate') }}</p>
                </div>
                <div class="text-center">
                    <p class="text-3xl font-bold text-purple-600 dark:text-purple-400">{{ number_format($quality['avg_fields_per_record'], 1) }}</p>
                    <p class="text-sm text-gray-600 dark:text-slate-400 mt-1">{{ __('Avg Fields/Record') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-gray-100 dark:border-slate-800 overflow-hidden">
        <div class="p-6">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-slate-100 mb-4">{{ __('Recent Cataloging Activity') }}</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 dark:bg-slate-800">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-slate-400 uppercase tracking-wider">{{ __('User') }}</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-slate-400 uppercase tracking-wider">{{ __('Action') }}</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-slate-400 uppercase tracking-wider">{{ __('Time') }}</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-slate-400 uppercase tracking-wider">{{ __('IP Address') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-slate-700">
                        @foreach($recentActivity as $activity)
                        <tr>
                            <td class="px-4 py-3 text-sm text-gray-900 dark:text-slate-100">{{ $activity['user'] }}</td>
                            <td class="px-4 py-3 text-sm text-gray-600 dark:text-slate-400">{{ $activity['action'] }}</td>
                            <td class="px-4 py-3 text-sm text-gray-600 dark:text-slate-400">{{ $activity['created_at'] }}</td>
                            <td class="px-4 py-3 text-sm text-gray-600 dark:text-slate-400">{{ $activity['ip_address'] }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Generate Reports Section -->
    <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-gray-100 dark:border-slate-800 overflow-hidden">
        <div class="p-6">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-slate-100 mb-4">{{ __('Generate Detailed Reports') }}</h3>

            <form id="reportForm" class="space-y-4">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-2">{{ __('Report Type') }}</label>
                        <select name="report_type" required
                            class="w-full px-4 py-2.5 border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 dark:text-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="summary">{{ __('Summary Report') }}</option>
                            <option value="productivity">{{ __('Productivity Report') }}</option>
                            <option value="quality">{{ __('Quality Report') }}</option>
                            <option value="detailed">{{ __('Detailed Records') }}</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-2">{{ __('Format') }}</label>
                        <select name="format" required
                            class="w-full px-4 py-2.5 border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 dark:text-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="web">{{ __('Web View') }}</option>
                            <option value="excel">{{ __('Excel Download') }}</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-2">{{ __('Framework') }}</label>
                        <select name="framework_id"
                            class="w-full px-4 py-2.5 border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 dark:text-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">{{ __('All Frameworks') }}</option>
                            @foreach($frameworks as $framework)
                            <option value="{{ $framework->id }}">{{ $framework->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-2">{{ __('Date From') }}</label>
                        <input type="date" name="date_from" value="{{ $dateRange['from']->format('Y-m-d') }}" required
                            class="w-full px-4 py-2.5 border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 dark:text-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-2">{{ __('Date To') }}</label>
                        <input type="date" name="date_to" value="{{ $dateRange['to']->format('Y-m-d') }}" required
                            class="w-full px-4 py-2.5 border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 dark:text-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                </div>

                <div class="flex space-x-3">
                    <button type="submit"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-lg text-sm font-semibold transition">
                        <i class="fas fa-file-export mr-2"></i>
                        {{ __('Generate Report') }}
                    </button>
                    <button type="button" id="resetReportForm"
                        class="px-6 py-3 bg-gray-100 dark:bg-slate-800 text-gray-600 dark:text-slate-400 hover:bg-gray-200 dark:hover:bg-slate-700 rounded-lg text-sm font-semibold transition">
                        {{ __('Reset') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const reportForm = document.getElementById('reportForm');
        const resetBtn = document.getElementById('resetReportForm');

        // Handle form submission
        reportForm.addEventListener('submit', async function(e) {
            e.preventDefault();

            const formData = new FormData(reportForm);
            const format = formData.get('format');

            if (format === 'excel') {
                // Download Excel file
                const params = new URLSearchParams(formData);
                window.location.href = `{{ route('admin.marc.reports.generate') }}?${params.toString()}`;
            } else {
                // Show web view
                try {
                    const response = await fetch('{{ route('admin.marc.reports.generate') }}', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    });

                    const result = await response.json();

                    if (result.success) {
                        // Open report in new window
                        const reportWindow = window.open('', '_blank');
                        reportWindow.document.write(result.html);
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: "{{ __('Error') }}",
                            text: result.message
                        });
                    }
                } catch (error) {
                    Swal.fire({
                        icon: 'error',
                        title: "{{ __('Error') }}",
                        text: error.message
                    });
                }
            }
        });

        // Reset form
        resetBtn.addEventListener('click', function() {
            reportForm.reset();
        });
    });
</script>
@endpush