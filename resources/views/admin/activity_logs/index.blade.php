@extends('layouts.admin')

@section('content')
<div class="w-full space-y-4 animate-in fade-in duration-500">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <h1 class="text-xl font-bold text-foreground tracking-tight">{{ __('Activity Logs') }}</h1>
            <p class="text-xs text-muted-foreground">{{ __('Monitor system actions and user operations.') }}</p>
        </div>
    </div>

    <div class="bg-card rounded-md shadow-sm border border-border overflow-hidden">
        <!-- Filter Bar -->
        <div class="p-3 bg-muted/30 border-b border-border">
            <form action="{{ route('admin.activity-logs.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-6 gap-2 items-end">
                <!-- Username search -->
                <div class="space-y-1">
                    <label class="block text-[10px] font-bold text-muted-foreground uppercase tracking-wider">{{ __('User Identity') }}</label>
                    <input type="text" name="username" value="{{ request('username') }}" placeholder="{{ __('Search username...') }}" 
                        class="w-full h-9 px-3 py-1.5 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all">
                </div>

                <!-- Action input -->
                <div class="space-y-1">
                    <label class="block text-[10px] font-bold text-muted-foreground uppercase tracking-wider">{{ __('Action') }}</label>
                    <input type="text" name="action" value="{{ request('action') }}" placeholder="{{ __('Action (e.g. create)') }}" 
                        class="w-full h-9 px-3 py-1.5 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all">
                </div>

                <!-- Method select -->
                <div class="space-y-1">
                    <label class="block text-[10px] font-bold text-muted-foreground uppercase tracking-wider">{{ __('Method') }}</label>
                    <div class="relative">
                        <select name="method" onchange="this.form.submit()" 
                            class="w-full h-9 px-3 py-1.5 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all appearance-none cursor-pointer">
                            <option value="">{{ __('All Methods') }}</option>
                            <option value="POST" {{ request('method') == 'POST' ? 'selected' : '' }}>POST</option>
                            <option value="PUT" {{ request('method') == 'PUT' ? 'selected' : '' }}>PUT</option>
                            <option value="PATCH" {{ request('method') == 'PATCH' ? 'selected' : '' }}>PATCH</option>
                            <option value="DELETE" {{ request('method') == 'DELETE' ? 'selected' : '' }}>DELETE</option>
                            <option value="GET" {{ request('method') == 'GET' ? 'selected' : '' }}>GET</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-muted-foreground">
                            <i data-lucide="chevron-down" class="w-4 h-4"></i>
                        </div>
                    </div>
                </div>

                <!-- Date from input -->
                <div class="space-y-1">
                    <label class="block text-[10px] font-bold text-muted-foreground uppercase tracking-wider">{{ __('From Date') }}</label>
                    <input type="date" name="date_from" value="{{ request('date_from') }}" 
                        class="w-full h-9 px-3 py-1.5 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all">
                </div>

                <!-- Date to input -->
                <div class="space-y-1">
                    <label class="block text-[10px] font-bold text-muted-foreground uppercase tracking-wider">{{ __('To Date') }}</label>
                    <input type="date" name="date_to" value="{{ request('date_to') }}" 
                        class="w-full h-9 px-3 py-1.5 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all">
                </div>

                <!-- Action buttons -->
                <div class="flex gap-2">
                    <button type="submit" class="btn-compact-primary flex-1 h-9">
                        <i data-lucide="filter" class="w-4 h-4 mr-1"></i>
                        {{ __('Filter') }}
                    </button>
                    @if(request()->anyFilled(['username', 'action', 'method', 'date_from', 'date_to']))
                        <a href="{{ route('admin.activity-logs.index') }}" class="btn-compact-secondary w-9 h-9 flex items-center justify-center p-0" title="{{ __('Clear') }}">
                            <i data-lucide="x" class="w-4 h-4"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Table View -->
        <div class="overflow-x-auto min-h-[250px]">
            <table class="w-full text-left border-collapse">
                <thead class="bg-muted/50 text-xs font-semibold uppercase tracking-wider text-muted-foreground border-b border-border">
                    <tr>
                        <th class="py-2 px-3">{{ __('User') }}</th>
                        <th class="py-2 px-3 w-32">{{ __('Action') }}</th>
                        <th class="py-2 px-3 w-40">{{ __('Method / Status') }}</th>
                        <th class="py-2 px-3">{{ __('URL') }}</th>
                        <th class="py-2 px-3 w-40">{{ __('Time') }}</th>
                        <th class="py-2 px-3 w-24 text-right">{{ __('Details') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @forelse($logs as $log)
                    <tr class="table-row-hover group">
                        <td class="py-2 px-3 whitespace-nowrap">
                            <div class="flex items-center gap-2.5">
                                <span class="w-7 h-7 rounded-full bg-primary/10 border border-primary/20 flex items-center justify-center text-primary font-bold text-xs shrink-0">
                                    {{ substr($log->user?->name ?? '?', 0, 1) }}
                                </span>
                                <div class="min-w-0">
                                    <div class="text-sm font-semibold text-foreground leading-tight truncate">{{ $log->user?->name ?? 'Guest' }}</div>
                                    <div class="text-[10px] text-muted-foreground font-mono leading-none mt-0.5">{{ $log->ip_address }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="py-2 px-3 whitespace-nowrap">
                            <span class="inline-flex items-center px-1.5 py-0.5 bg-muted text-muted-foreground text-[10px] font-bold uppercase rounded-sm border border-border">
                                {{ $log->action }}
                            </span>
                        </td>
                        <td class="py-2 px-3 whitespace-nowrap">
                            <div class="flex items-center gap-1.5">
                                <span class="px-1.5 py-0.5 text-[9px] font-bold uppercase rounded-sm {{ 
                                    $log->method == 'DELETE' ? 'bg-destructive/10 text-destructive border border-destructive/20' : 
                                    ($log->method == 'POST' ? 'bg-green-500/10 text-green-600 dark:text-green-400 border border-green-500/20' : 'bg-primary/10 text-primary border border-primary/20') 
                                }}">
                                    {{ $log->method }}
                                </span>
                                @if($log->status_code)
                                    <span class="text-[10px] font-bold {{ $log->status_code >= 400 ? 'text-destructive' : 'text-green-500' }}">
                                        {{ $log->status_code }}
                                    </span>
                                @endif
                            </div>
                        </td>
                        <td class="py-2 px-3">
                            <div class="text-[11px] text-muted-foreground font-mono truncate max-w-[250px]" title="{{ $log->url }}">
                                {{ $log->url }}
                            </div>
                        </td>
                        <td class="py-2 px-3 whitespace-nowrap text-xs text-muted-foreground">
                            {{ $log->created_at->diffForHumans() }}
                        </td>
                        <td class="py-2 px-3 text-right">
                            <button onclick="openLogModal({{ $log->id }})" class="btn-icon-compact" title="{{ __('Details') }}">
                                <i data-lucide="eye" class="w-4 h-4"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-12 text-center">
                            <div class="flex flex-col items-center max-w-sm mx-auto">
                                <div class="w-12 h-12 bg-muted rounded-full flex items-center justify-center mb-4 border border-border">
                                    <i data-lucide="search-x" class="w-6 h-6 text-muted-foreground"></i>
                                </div>
                                <p class="text-muted-foreground text-sm font-bold">{{ __('No activities found in the signal stream.') }}</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($logs->hasPages())
        <div class="px-4 py-3 bg-muted/30 border-t border-border">
            {{ $logs->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Log Details Modal -->
<div id="logModal" class="hidden fixed inset-0 z-[100] overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity bg-slate-950/80 backdrop-blur-sm" onclick="closeLogModal()"></div>
        <div class="inline-block w-full max-w-2xl my-8 overflow-hidden text-left align-middle transition-all transform bg-card border border-border rounded-md shadow-2xl">
            <div class="p-4">
                <div class="flex items-center justify-between pb-3 border-b border-border mb-4">
                    <h3 class="text-sm font-bold text-foreground uppercase tracking-wider">{{ __('Activity Payload Analysis') }}</h3>
                    <button onclick="closeLogModal()" class="text-muted-foreground hover:text-foreground transition-colors">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>
                
                <div id="logDetailContent" class="space-y-4">
                    <!-- Dynamic content will flow here -->
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function openLogModal(logId) {
        const modal = document.getElementById('logModal');
        const content = document.getElementById('logDetailContent');
        modal.classList.remove('hidden');
        content.innerHTML = '<div class="flex justify-center py-12"><div class="animate-spin rounded-full h-8 w-8 border-2 border-primary border-t-transparent"></div></div>';

        fetch(`{{ route('admin.activity-logs.show', ['log' => ':id']) }}`.replace(':id', logId))
            .then(response => response.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const detailHtml = doc.getElementById('log-detail-snippet').innerHTML;
                content.innerHTML = detailHtml;
                lucide.createIcons({ parent: content });
            });
    }

    function closeLogModal() {
        document.getElementById('logModal').classList.add('hidden');
    }
</script>
@endsection
