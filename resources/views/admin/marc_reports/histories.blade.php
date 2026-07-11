@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-white leading-tight">
                {{ __('Lịch sử xuất dữ liệu') }}
            </h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
                {{ __('Danh sách các tệp báo cáo Excel/CSV đã yêu cầu khởi tạo và tải xuống dưới nền.') }}
            </p>
        </div>
        <div class="mt-4 md:mt-0">
            <a href="{{ route('admin.marc.reports.index') }}" 
               class="inline-flex items-center px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-sm rounded-xl shadow-lg shadow-indigo-500/20 transition-all duration-300">
                <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                {{ __('Tạo xuất báo cáo mới') }}
            </a>
        </div>
    </div>

    <!-- Table Card -->
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-800/50 border-b border-slate-100 dark:border-slate-850">
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider w-16">{{ __('STT') }}</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">{{ __('Tên báo cáo') }}</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">{{ __('Tên tệp tin') }}</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider w-24 text-center">{{ __('Định dạng') }}</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider w-40">{{ __('Thời điểm tạo') }}</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider w-36">{{ __('Trạng thái') }}</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider w-28 text-center">{{ __('Thao tác') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60">
                    @forelse ($histories as $index => $history)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-colors">
                            <td class="px-6 py-4 text-sm text-slate-500 dark:text-slate-400 font-medium">
                                {{ ($histories->currentPage() - 1) * $histories->perPage() + $index + 1 }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm font-semibold text-slate-800 dark:text-slate-200">
                                    {{ $history->title }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-xs text-slate-500 dark:text-slate-400 block max-w-xs truncate" title="{{ $history->filename }}">
                                    {{ $history->filename }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center px-2 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider {{ $history->format === 'csv' ? 'bg-amber-50 dark:bg-amber-500/10 text-amber-600 dark:text-amber-400' : 'bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400' }}">
                                    {{ $history->format }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-500 dark:text-slate-400">
                                {{ $history->created_at->setTimezone('Asia/Ho_Chi_Minh')->format('H:i:s d/m/Y') }}
                            </td>
                            <td class="px-6 py-4">
                                @if ($history->status === 'pending')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-50 dark:bg-amber-500/10 text-amber-500">
                                        <svg class="animate-spin -ml-1 mr-1.5 h-3 w-3 text-amber-500" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        {{ __('Đang chờ...') }}
                                    </span>
                                @elseif ($history->status === 'processing')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-50 dark:bg-blue-500/10 text-blue-500">
                                        <svg class="animate-spin -ml-1 mr-1.5 h-3 w-3 text-blue-500" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        {{ __('Đang xuất file...') }}
                                    </span>
                                @elseif ($history->status === 'completed')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400">
                                        <svg class="w-3.5 h-3.5 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                        </svg>
                                        {{ __('Thành công') }}
                                    </span>
                                @elseif ($history->status === 'failed')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-rose-50 dark:bg-rose-500/10 text-rose-600 dark:text-rose-400 cursor-help" title="{{ $history->error_message }}">
                                        <svg class="w-3.5 h-3.5 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                        </svg>
                                        {{ __('Thất bại') }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if ($history->status === 'completed')
                                    <a href="{{ route('admin.export-histories.download', $history->id) }}" 
                                       class="inline-flex items-center justify-center p-2 text-indigo-600 dark:text-indigo-400 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-xl transition-all duration-300 group"
                                       title="{{ __('Tải xuống') }}">
                                        <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                        </svg>
                                    </a>
                                @else
                                    <button disabled 
                                            class="inline-flex items-center justify-center p-2 text-slate-300 dark:text-slate-700 cursor-not-allowed">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                        </svg>
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-slate-400 dark:text-slate-500">
                                <svg class="w-12 h-12 mx-auto mb-4 text-slate-300 dark:text-slate-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                {{ __('Chưa có yêu cầu xuất file nào.') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if ($histories->hasPages())
            <div class="px-6 py-4 bg-slate-50 dark:bg-slate-800/30 border-t border-slate-100 dark:border-slate-800">
                {{ $histories->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
