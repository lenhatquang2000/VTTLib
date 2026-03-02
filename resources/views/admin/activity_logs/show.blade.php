@extends('layouts.admin')

@section('content')
<div id="log-detail-snippet">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <div class="space-y-6">
            <div class="bg-slate-50 dark:bg-slate-800/50 p-6 rounded-3xl">
                <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">{{ __('Action') }}</p>
                <p class="text-sm font-black text-indigo-600 dark:text-indigo-400 uppercase">{{ $log->action }}</p>
            </div>
            <div class="bg-slate-50 dark:bg-slate-800/50 p-6 rounded-3xl">
                <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">{{ __('User Identity') }}</p>
                <div class="flex items-center">
                    <span class="w-8 h-8 rounded-full bg-slate-200 dark:bg-slate-700 flex items-center justify-center text-xs font-black mr-3">{{ substr($log->user?->name ?? '?', 0, 1) }}</span>
                    <span class="text-sm font-bold text-slate-700 dark:text-slate-200">{{ $log->user?->name ?? 'Guest' }}</span>
                </div>
            </div>
            <div class="bg-slate-50 dark:bg-slate-800/50 p-6 rounded-3xl">
                <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">{{ __('IP Address / Method') }}</p>
                <p class="text-sm font-bold text-slate-700 dark:text-slate-200">{{ $log->ip_address }} • {{ $log->method }} ({{ $log->status_code }})</p>
            </div>
            <div class="bg-slate-50 dark:bg-slate-800/50 p-6 rounded-3xl">
                <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">{{ __('Timestamp') }}</p>
                <p class="text-sm font-bold text-slate-700 dark:text-slate-200">{{ $log->created_at }} ({{ $log->created_at->diffForHumans() }})</p>
            </div>
        </div>
        
        <div class="space-y-6">
            <div class="bg-slate-900 rounded-3xl p-8 overflow-hidden">
                <p class="text-[10px] font-black uppercase tracking-widest text-slate-500 mb-4">{{ __('Request Data Payload') }}</p>
                <pre class="text-xs text-emerald-400 font-mono overflow-x-auto"><code>{{ json_encode($log->request_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</code></pre>
            </div>

            @if(!empty($log->details))
            <div class="bg-slate-900 rounded-3xl p-8 overflow-hidden">
                <p class="text-[10px] font-black uppercase tracking-widest text-slate-500 mb-4">{{ __('Extra Trace Details') }}</p>
                <pre class="text-xs text-blue-400 font-mono overflow-x-auto"><code>{{ json_encode($log->details, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</code></pre>
            </div>
            @endif
        </div>
    </div>
    
    <div class="mt-8 bg-slate-50 dark:bg-slate-800/50 p-6 rounded-3xl">
        <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">{{ __('Target Resource URL') }}</p>
        <p class="text-xs font-bold text-slate-600 dark:text-slate-300 break-all select-all">{{ $log->url }}</p>
    </div>
</div>
@endsection
