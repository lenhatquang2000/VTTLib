@extends('layouts.admin')

@section('content')
<div class="w-full space-y-4 animate-in fade-in duration-500">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <h1 class="text-xl font-bold text-foreground tracking-tight">{{ __('Nhật ký hoạt động') }}</h1>
            <p class="text-xs text-muted-foreground">{{ __('Giám sát các thao tác người dùng trên cơ sở dữ liệu và luồng ghi nhận hệ thống.') }}</p>
        </div>
    </div>

    <!-- Tab Selector -->
    <div class="flex items-center gap-1 p-1 bg-muted/50 rounded-md w-fit border border-border">
        <button type="button" onclick="switchTab('db-logs')" id="tab-db-logs-btn" class="px-4 py-1.5 rounded-sm text-xs font-semibold transition-all bg-card text-primary shadow-sm">
            Nhật ký hoạt động (Database)
        </button>
        <button type="button" onclick="switchTab('file-logs')" id="tab-file-logs-btn" class="px-4 py-1.5 rounded-sm text-xs font-semibold transition-all text-muted-foreground hover:text-foreground">
            Laravel Logs (laravel.log)
        </button>
    </div>

    <!-- Tab 1: Database Logs Container -->
    <div id="db-logs-container" class="bg-card rounded-md shadow-sm border border-border overflow-hidden">
        <!-- Filter Bar -->
        <div class="p-3 bg-muted/30 border-b border-border">
            <form action="{{ route('admin.activity-logs.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-7 gap-2 items-end">
                <input type="hidden" name="tab" value="db-logs">
                <div class="space-y-1">
                    <label class="block text-[10px] font-bold text-muted-foreground uppercase tracking-wider">{{ __('User Identity') }}</label>
                    <input type="text" name="username" value="{{ request('username') }}" placeholder="{{ __('Search username...') }}" 
                        class="w-full h-9 px-3 py-1.5 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all">
                </div>

                <div class="space-y-1">
                    <label class="block text-[10px] font-bold text-muted-foreground uppercase tracking-wider">{{ __('Action') }}</label>
                    <input type="text" name="action" value="{{ request('action') }}" placeholder="{{ __('Action (e.g. create)') }}" 
                        class="w-full h-9 px-3 py-1.5 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all">
                </div>

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

                <div class="space-y-1">
                    <label class="block text-[10px] font-bold text-muted-foreground uppercase tracking-wider">{{ __('From Date') }}</label>
                    <input type="date" name="date_from" value="{{ request('date_from') }}" 
                        class="w-full h-9 px-3 py-1.5 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all">
                </div>

                <div class="space-y-1">
                    <label class="block text-[10px] font-bold text-muted-foreground uppercase tracking-wider">{{ __('To Date') }}</label>
                    <input type="date" name="date_to" value="{{ request('date_to') }}" 
                        class="w-full h-9 px-3 py-1.5 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all">
                </div>

                <div class="space-y-1">
                    <label class="block text-[10px] font-bold text-muted-foreground uppercase tracking-wider">Sắp xếp</label>
                    <div class="relative">
                        <select name="sort_order" onchange="this.form.submit()" 
                            class="w-full h-9 px-3 py-1.5 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all appearance-none cursor-pointer">
                            <option value="desc" {{ request('sort_order', 'desc') == 'desc' ? 'selected' : '' }}>Mới nhất trước</option>
                            <option value="asc" {{ request('sort_order') == 'asc' ? 'selected' : '' }}>Cũ nhất trước</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-muted-foreground">
                            <i data-lucide="chevron-down" class="w-4 h-4"></i>
                        </div>
                    </div>
                </div>

                <div class="flex gap-2">
                    <button type="submit" class="btn-compact-primary flex-1 h-9">
                        <i data-lucide="filter" class="w-4 h-4 mr-1"></i>
                        {{ __('Filter') }}
                    </button>
                    @if(request()->anyFilled(['username', 'action', 'method', 'date_from', 'date_to', 'sort_order']))
                        <a href="{{ route('admin.activity-logs.index', ['tab' => 'db-logs']) }}" class="btn-compact-secondary w-9 h-9 flex items-center justify-center p-0" title="{{ __('Clear') }}">
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

    <!-- Tab 2: Laravel Logs File Container -->
    <div id="file-logs-container" class="hidden bg-card rounded-md shadow-sm border border-border overflow-hidden">
        <!-- Actions & Cleaning Form -->
        <div class="p-3 bg-muted/30 border-b border-border space-y-3">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                <div>
                    <h3 class="text-xs font-bold text-foreground uppercase tracking-wider">{{ __('Luồng thông tin ghi nhận từ file (laravel.log)') }}</h3>
                    <p class="text-[10px] text-muted-foreground mt-0.5">{{ __('Hiển thị tối đa 200 bản ghi lỗi và hoạt động gần nhất lưu trữ trên ổ cứng.') }}</p>
                </div>
            </div>
            
            <div class="flex flex-col md:flex-row justify-between items-stretch md:items-center gap-3 pt-2 border-t border-border/50">
                <!-- GET Filter / Sort Form -->
                <form action="{{ route('admin.activity-logs.index') }}" method="GET" class="flex flex-wrap items-center gap-3">
                    <input type="hidden" name="tab" value="file-logs">
                    
                    <div class="flex items-center gap-1.5">
                        <span class="text-[10px] font-bold text-muted-foreground uppercase tracking-wider">Mức độ</span>
                        <div class="relative">
                            <select name="level" onchange="this.form.submit()" class="h-9 pl-3 pr-8 text-xs border border-input rounded-sm bg-background text-foreground cursor-pointer focus:outline-none appearance-none w-28">
                                <option value="">Tất cả</option>
                                <option value="info" {{ request('level') == 'info' ? 'selected' : '' }}>INFO</option>
                                <option value="warning" {{ request('level') == 'warning' ? 'selected' : '' }}>WARNING</option>
                                <option value="error" {{ request('level') == 'error' ? 'selected' : '' }}>ERROR</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 pr-2 flex items-center pointer-events-none text-muted-foreground">
                                <i data-lucide="chevron-down" class="w-3.5 h-3.5"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-1.5">
                        <span class="text-[10px] font-bold text-muted-foreground uppercase tracking-wider">Sắp xếp</span>
                        <div class="relative">
                            <select name="sort_order" onchange="this.form.submit()" class="h-9 pl-3 pr-8 text-xs border border-input rounded-sm bg-background text-foreground cursor-pointer focus:outline-none appearance-none w-36">
                                <option value="desc" {{ request('sort_order', 'desc') == 'desc' ? 'selected' : '' }}>Mới nhất trước</option>
                                <option value="asc" {{ request('sort_order') == 'asc' ? 'selected' : '' }}>Cũ nhất trước</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 pr-2 flex items-center pointer-events-none text-muted-foreground">
                                <i data-lucide="chevron-down" class="w-3.5 h-3.5"></i>
                            </div>
                        </div>
                    </div>
                </form>

                <!-- POST Clear Actions Form -->
                <form action="{{ route('admin.activity-logs.clear-laravel-logs') }}?tab=file-logs" method="POST" class="flex flex-wrap items-center gap-1.5 justify-end">
                    @csrf
                    <button type="submit" name="clear_type" value="info" onclick="return confirm('Bạn có chắc chắn muốn xóa tất cả logs INFO?')" class="h-8 px-2.5 text-[10px] font-bold uppercase rounded-sm bg-blue-500/10 text-blue-600 dark:text-blue-400 hover:bg-blue-500/20 border border-blue-500/20 transition-all flex items-center gap-1">
                        <i data-lucide="info" class="w-3.5 h-3.5"></i> Xoá INFO
                    </button>
                    
                    <button type="submit" name="clear_type" value="warning" onclick="return confirm('Bạn có chắc chắn muốn xóa tất cả logs WARNING?')" class="h-8 px-2.5 text-[10px] font-bold uppercase rounded-sm bg-amber-500/10 text-amber-600 dark:text-amber-400 hover:bg-amber-500/20 border border-amber-500/20 transition-all flex items-center gap-1">
                        <i data-lucide="alert-triangle" class="w-3.5 h-3.5"></i> Xoá WARNING
                    </button>
                    
                    <button type="submit" name="clear_type" value="error" onclick="return confirm('Bạn có chắc chắn muốn xóa tất cả logs ERROR?')" class="h-8 px-2.5 text-[10px] font-bold uppercase rounded-sm bg-rose-500/10 text-rose-600 dark:text-rose-400 hover:bg-rose-500/20 border border-rose-500/20 transition-all flex items-center gap-1">
                        <i data-lucide="alert-circle" class="w-3.5 h-3.5"></i> Xoá ERROR
                    </button>

                    <button type="submit" name="clear_type" value="all" onclick="return confirm('Xác nhận xóa TOÀN BỘ logs? Hành động này không thể hoàn tác.')" class="h-8 px-3 text-[10px] font-bold uppercase rounded-sm bg-red-600 text-white hover:bg-red-700 transition-all flex items-center gap-1.5 shadow-sm">
                        <i data-lucide="trash-2" class="w-3.5 h-3.5 text-white"></i> Xoá Toàn Bộ
                    </button>
                </form>
            </div>
        </div>

        <!-- Table View -->
        <div class="overflow-x-auto min-h-[250px]">
            <table class="w-full text-left border-collapse">
                <thead class="bg-muted/50 text-xs font-semibold uppercase tracking-wider text-muted-foreground border-b border-border">
                    <tr>
                        <th class="py-2 px-3 w-40">Mức độ / Env</th>
                        <th class="py-2 px-3 w-48">Thời gian</th>
                        <th class="py-2 px-3">Nội dung thông điệp</th>
                        <th class="py-2 px-3 w-24 text-right">Chi tiết</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border font-mono text-[11px]">
                    @forelse($laravelLogs as $index => $l)
                        <tr class="table-row-hover">
                            <td class="py-2 px-3 whitespace-nowrap">
                                <span class="inline-flex items-center px-1.5 py-0.5 text-[9px] font-bold uppercase rounded-sm {{
                                    $l['level'] === 'ERROR' ? 'bg-destructive/10 text-destructive border border-destructive/20' :
                                    ($l['level'] === 'WARNING' ? 'bg-amber-500/10 text-amber-600 dark:text-amber-400 border border-amber-500/20' :
                                    ($l['level'] === 'INFO' ? 'bg-blue-500/10 text-blue-600 dark:text-blue-400 border border-blue-500/20' : 'bg-muted text-muted-foreground border border-border'))
                                }}">
                                    {{ $l['level'] }}
                                </span>
                                <span class="text-muted-foreground/60 ml-1">[{{ $l['env'] }}]</span>
                            </td>
                            <td class="py-2 px-3 text-muted-foreground whitespace-nowrap">
                                {{ $l['timestamp'] }}
                            </td>
                            <td class="py-2 px-3 text-foreground break-all">
                                {{ $l['message'] }}
                            </td>
                            <td class="py-2 px-3 text-right">
                                @if(!empty($l['stack_trace']))
                                    <button type="button" onclick="toggleStackTrace({{ $index }})" class="btn-icon-compact" title="Xem chi tiết Exception / Stack trace">
                                        <i data-lucide="chevron-down" id="trace-chevron-{{ $index }}" class="w-4 h-4 transition-transform duration-200"></i>
                                    </button>
                                @else
                                    <span class="text-muted-foreground/40 text-[9px] italic">Không có</span>
                                @endif
                            </td>
                        </tr>
                        @if(!empty($l['stack_trace']))
                            <tr id="trace-row-{{ $index }}" class="hidden bg-muted/20">
                                <td colspan="4" class="p-3">
                                    <pre class="bg-card border border-border p-3 rounded-sm text-muted-foreground max-h-96 overflow-y-auto custom-scrollbar whitespace-pre-wrap break-words text-[10px] leading-relaxed">{{ $l['stack_trace'] }}</pre>
                                </td>
                            </tr>
                        @endif
                    @empty
                        <tr>
                            <td colspan="4" class="py-12 text-center text-muted-foreground italic">
                                Không có log hệ thống nào được ghi nhận trong file.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
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

@push('styles')
<style>
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: hsl(var(--border)); border-radius: 2px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: hsl(var(--muted-foreground)); }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }

        // Auto-switch to tab if present in URL query
        const urlParams = new URLSearchParams(window.location.search);
        const activeTab = urlParams.get('tab') || 'db-logs';
        switchTab(activeTab, false);

        // Listen for history back/forward
        window.addEventListener('popstate', () => {
            const currentParams = new URLSearchParams(window.location.search);
            const targetTab = currentParams.get('tab') || 'db-logs';
            switchTab(targetTab, false);
        });
    });

    function switchTab(tabId, updateUrl = true) {
        if (updateUrl) {
            const url = new URL(window.location.href);
            url.searchParams.set('tab', tabId);
            window.history.pushState({ path: url.href }, '', url.href);
        }

        if (tabId === 'db-logs') {
            document.getElementById('db-logs-container').classList.remove('hidden');
            document.getElementById('file-logs-container').classList.add('hidden');
            
            document.getElementById('tab-db-logs-btn').className = 'px-4 py-1.5 rounded-sm text-xs font-semibold transition-all bg-card text-primary shadow-sm';
            document.getElementById('tab-file-logs-btn').className = 'px-4 py-1.5 rounded-sm text-xs font-semibold transition-all text-muted-foreground hover:text-foreground';
        } else {
            document.getElementById('db-logs-container').classList.add('hidden');
            document.getElementById('file-logs-container').classList.remove('hidden');
            
            document.getElementById('tab-db-logs-btn').className = 'px-4 py-1.5 rounded-sm text-xs font-semibold transition-all text-muted-foreground hover:text-foreground';
            document.getElementById('tab-file-logs-btn').className = 'px-4 py-1.5 rounded-sm text-xs font-semibold transition-all bg-card text-primary shadow-sm';
        }
    }

    function toggleStackTrace(index) {
        const row = document.getElementById('trace-row-' + index);
        const chevron = document.getElementById('trace-chevron-' + index);
        
        if (row.classList.contains('hidden')) {
            row.classList.remove('hidden');
            chevron.classList.add('rotate-180');
        } else {
            row.classList.add('hidden');
            chevron.classList.remove('rotate-180');
        }
    }

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
@endpush
@endsection
