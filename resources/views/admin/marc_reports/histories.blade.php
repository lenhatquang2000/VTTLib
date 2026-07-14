@extends('layouts.admin')

@section('content')
<div class="space-y-4 pb-6">
    <!-- Header Section Card - Căn lề trái, padding nhỏ, border-radius rounded-md theo rule -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between p-3 bg-card border border-border rounded-md gap-3">
        <div>
            <div class="flex items-center gap-2">
                <span class="h-5 w-1 rounded-full bg-primary"></span>
                <h1 class="text-lg font-bold text-foreground tracking-tight">
                    {{ __('Lịch sử xuất dữ liệu') }}
                </h1>
            </div>
            <p class="text-xs text-muted-foreground mt-0.5 pl-3">
                {{ __('Quản lý, tải nhanh và theo dõi thời gian thực hiện các báo cáo.') }}
            </p>
        </div>
        <div class="sm:text-right pl-3 sm:pl-0">
            <a href="{{ route('admin.marc.reports.index') }}" 
               class="inline-flex items-center px-3 py-1.5 bg-primary hover:bg-primary/90 text-primary-foreground font-semibold text-xs rounded transition-all duration-200">
                <svg class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                {{ __('Tạo báo cáo mới') }}
            </a>
        </div>
    </div>

    <!-- Table Container - Padding nhỏ, rounded-md, theo sát style guide -->
    <div class="bg-card border border-border rounded-md shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-muted/50 border-b border-border">
                        <th class="px-3 py-2 text-xs font-bold text-muted-foreground uppercase tracking-wider w-12 text-center">{{ __('STT') }}</th>
                        <th class="px-3 py-2 text-xs font-bold text-muted-foreground uppercase tracking-wider">{{ __('Tên báo cáo') }}</th>
                        <th class="px-3 py-2 text-xs font-bold text-muted-foreground uppercase tracking-wider">{{ __('Tên tệp tin') }}</th>
                        <th class="px-3 py-2 text-xs font-bold text-muted-foreground uppercase tracking-wider w-20 text-center">{{ __('Định dạng') }}</th>
                        <th class="px-3 py-2 text-xs font-bold text-muted-foreground uppercase tracking-wider w-36">{{ __('Thời điểm tạo') }}</th>
                        <th class="px-3 py-2 text-xs font-bold text-muted-foreground uppercase tracking-wider w-32 text-center">{{ __('Thời gian chạy') }}</th>
                        <th class="px-3 py-2 text-xs font-bold text-muted-foreground uppercase tracking-wider w-28 text-center">{{ __('Trạng thái') }}</th>
                        <th class="px-3 py-2 text-xs font-bold text-muted-foreground uppercase tracking-wider w-24 text-center">{{ __('Thao tác') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @forelse ($histories as $index => $history)
                        <tr class="hover:bg-muted/30 border-b border-border transition-colors group">
                            <!-- STT -->
                            <td class="px-3 py-2 text-xs text-muted-foreground font-semibold text-center">
                                {{ ($histories->currentPage() - 1) * $histories->perPage() + $index + 1 }}
                            </td>
                            
                            <!-- Tên báo cáo -->
                            <td class="px-3 py-2">
                                <div class="text-xs font-bold text-foreground">
                                    {{ $history->title }}
                                </div>
                            </td>
                            
                            <!-- Tên tệp tin -->
                            <td class="px-3 py-2">
                                <span class="text-xs font-mono text-muted-foreground block max-w-xs truncate" title="{{ $history->filename }}">
                                    {{ $history->filename }}
                                </span>
                            </td>
                            
                            <!-- Định dạng -->
                            <td class="px-3 py-2 text-center">
                                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider border {{ $history->format === 'csv' ? 'bg-amber-500/10 text-amber-600 dark:text-amber-400 border-amber-500/20' : 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border-emerald-500/20' }}">
                                    {{ $history->format }}
                                </span>
                            </td>
                            
                            <!-- Thời điểm tạo -->
                            <td class="px-3 py-2 text-xs text-muted-foreground">
                                {{ $history->created_at->setTimezone('Asia/Ho_Chi_Minh')->format('H:i:s d/m/Y') }}
                            </td>
                            
                            <!-- Thời gian chạy -->
                            <td class="px-3 py-2 text-center">
                                @if (is_null($history->execution_time_ms))
                                    @if ($history->status === 'pending' || $history->status === 'processing')
                                        <span class="inline-flex items-center text-[10px] text-amber-500 font-semibold italic animate-pulse">
                                            {{ __('Đang chạy...') }}
                                        </span>
                                    @else
                                        <span class="text-muted-foreground/60 text-xs">--</span>
                                    @endif
                                @else
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-bold font-mono bg-muted text-muted-foreground border border-border">
                                        @if ($history->execution_time_ms < 1000)
                                            {{ $history->execution_time_ms }} ms
                                        @else
                                            {{ number_format($history->execution_time_ms / 1000, 2) }} s
                                        @endif
                                    </span>
                                @endif
                            </td>
                            
                            <!-- Trạng thái -->
                            <td class="px-3 py-2 text-center">
                                @if ($history->status === 'pending')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-amber-500/10 text-amber-500">
                                        {{ __('Chờ xử lý') }}
                                    </span>
                                @elseif ($history->status === 'processing')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-blue-500/10 text-blue-500">
                                        {{ __('Đang xuất') }}
                                    </span>
                                @elseif ($history->status === 'completed')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-500/10 text-emerald-600 dark:text-emerald-400">
                                        {{ __('Thành công') }}
                                    </span>
                                @elseif ($history->status === 'failed')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-rose-500/10 text-rose-600 dark:text-rose-400 cursor-help" title="{{ $history->error_message }}">
                                        {{ __('Thất bại') }}
                                    </span>
                                @endif
                            </td>
                            
                            <!-- Thao tác -->
                            <td class="px-3 py-2 text-center">
                                <div class="flex items-center justify-center space-x-1.5">
                                    @if ($history->status === 'completed')
                                        <a href="{{ route('admin.export-histories.download', $history->id) }}" 
                                           class="inline-flex items-center justify-center p-1.5 text-indigo-500 hover:text-indigo-650 dark:hover:text-indigo-400 hover:bg-muted rounded transition-colors"
                                           title="{{ __('Tải xuống') }}">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                            </svg>
                                        </a>
                                    @else
                                        <button disabled 
                                                class="inline-flex items-center justify-center p-1.5 text-muted-foreground/35 cursor-not-allowed">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                            </svg>
                                        </button>
                                    @endif

                                    <form action="{{ route('admin.export-histories.destroy', $history->id) }}" method="POST" onsubmit="return confirm('{{ __('Bạn có chắc chắn muốn xóa bản ghi xuất file này?') }}')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="inline-flex items-center justify-center p-1.5 text-rose-500 hover:bg-rose-500/10 rounded transition-colors"
                                                title="{{ __('Xóa') }}">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-3 py-10 text-center text-muted-foreground">
                                <div class="flex flex-col items-center justify-center space-y-2">
                                    <svg class="w-8 h-8 text-muted-foreground/50" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <p class="text-xs font-bold text-foreground">{{ __('Chưa có lịch sử xuất báo cáo') }}</p>
                                    <p class="text-[11px] text-muted-foreground max-w-xs">{{ __('Các file báo cáo sẽ xuất hiện tại đây sau khi được tạo thành công.') }}</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Phân trang -->
        @if ($histories->hasPages())
            <div class="px-3 py-2 bg-muted/30 border-t border-border">
                {{ $histories->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
