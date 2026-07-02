@extends('layouts.admin')

@section('content')
<div id="log-detail-snippet">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
        <div class="space-y-3">
            <div class="bg-muted/30 p-3 rounded-md border border-border">
                <p class="text-[10px] font-bold uppercase tracking-wider text-muted-foreground mb-1">{{ __('Action') }}</p>
                <p class="text-xs font-bold text-primary uppercase">{{ $log->action }}</p>
            </div>
            <div class="bg-muted/30 p-3 rounded-md border border-border">
                <p class="text-[10px] font-bold uppercase tracking-wider text-muted-foreground mb-1">{{ __('User Identity') }}</p>
                <div class="flex items-center gap-2">
                    <span class="w-6 h-6 rounded-full bg-primary/10 border border-primary/20 flex items-center justify-center text-xs font-bold text-primary">{{ substr($log->user?->name ?? '?', 0, 1) }}</span>
                    <span class="text-xs font-bold text-foreground">{{ $log->user?->name ?? 'Guest' }}</span>
                </div>
            </div>
            <div class="bg-muted/30 p-3 rounded-md border border-border">
                <p class="text-[10px] font-bold uppercase tracking-wider text-muted-foreground mb-1">{{ __('IP Address / Method') }}</p>
                <p class="text-xs font-bold text-foreground">{{ $log->ip_address }} • {{ $log->method }} ({{ $log->status_code }})</p>
            </div>
            <div class="bg-muted/30 p-3 rounded-md border border-border">
                <p class="text-[10px] font-bold uppercase tracking-wider text-muted-foreground mb-1">{{ __('Timestamp') }}</p>
                <p class="text-xs font-bold text-foreground">{{ $log->created_at }} ({{ $log->created_at->diffForHumans() }})</p>
            </div>
        </div>
        
        <div class="space-y-3">
            <div class="bg-muted/20 border border-border rounded-md p-3 overflow-hidden">
                <p class="text-[10px] font-bold uppercase tracking-wider text-muted-foreground mb-2">{{ __('Request Data Payload') }}</p>
                <pre class="text-[11px] text-green-600 dark:text-green-400 font-mono overflow-x-auto max-h-40"><code>{{ json_encode($log->request_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</code></pre>
            </div>

            @if(!empty($log->details))
            <div class="bg-muted/20 border border-border rounded-md p-3 overflow-hidden">
                <p class="text-[10px] font-bold uppercase tracking-wider text-muted-foreground mb-2">{{ __('Extra Trace Details') }}</p>
                <pre class="text-[11px] text-blue-600 dark:text-blue-400 font-mono overflow-x-auto max-h-40"><code>{{ json_encode($log->details, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</code></pre>
            </div>
            @endif
        </div>
    </div>
    
    <div class="mt-3 bg-muted/30 p-3 rounded-md border border-border">
        <p class="text-[10px] font-bold uppercase tracking-wider text-muted-foreground mb-1">{{ __('Target Resource URL') }}</p>
        <p class="text-xs font-bold text-foreground break-all select-all">{{ $log->url }}</p>
    </div>
</div>
@endsection
