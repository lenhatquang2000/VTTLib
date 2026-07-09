@extends('layouts.admin')

@section('content')
<div class="w-full space-y-4 animate-in fade-in duration-500" x-data="{ activeTab: '{{ request('tab', 'online') }}' }">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <h1 class="text-xl font-bold text-foreground tracking-tight">{{ __('Theo dõi Hệ thống') }}</h1>
            <p class="text-xs text-muted-foreground">{{ __('Giám sát các phiên làm việc trực tuyến và lịch sử ra vào hệ thống.') }}</p>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-500/10 border border-green-500/20 text-green-600 dark:text-green-400 text-xs rounded-sm p-3 flex items-center gap-2 animate-in slide-in-from-top duration-300">
            <i data-lucide="check-circle" class="w-4 h-4 shrink-0"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif
    @if(session('error'))
        <div class="bg-destructive/10 border border-destructive/20 text-destructive text-xs rounded-sm p-3 flex items-center gap-2 animate-in slide-in-from-top duration-300">
            <i data-lucide="alert-circle" class="w-4 h-4 shrink-0"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <!-- Tab Buttons -->
    <div class="flex border-b border-border space-x-2">
        <button @click="activeTab = 'online'" :class="activeTab === 'online' ? 'border-primary text-primary font-bold' : 'border-transparent text-muted-foreground hover:text-foreground'"
            class="px-4 py-2 border-b-2 text-sm transition-all focus:outline-none flex items-center gap-2">
            <i data-lucide="users" class="w-4 h-4"></i>
            {{ __('Trực tuyến hiện tại') }}
            <span class="bg-primary/10 text-primary text-[10px] px-1.5 py-0.5 rounded-full font-bold ml-1">
                {{ count($onlineUsers) }}
            </span>
        </button>
        <button @click="activeTab = 'auth_history'" :class="activeTab === 'auth_history' ? 'border-primary text-primary font-bold' : 'border-transparent text-muted-foreground hover:text-foreground'"
            class="px-4 py-2 border-b-2 text-sm transition-all focus:outline-none flex items-center gap-2">
            <i data-lucide="key-round" class="w-4 h-4"></i>
            {{ __('Lịch sử ra vào') }}
        </button>
        <button @click="activeTab = 'public_access'" :class="activeTab === 'public_access' ? 'border-primary text-primary font-bold' : 'border-transparent text-muted-foreground hover:text-foreground'"
            class="px-4 py-2 border-b-2 text-sm transition-all focus:outline-none flex items-center gap-2">
            <i data-lucide="globe" class="w-4 h-4"></i>
            {{ __('Nhật ký duyệt web công cộng') }}
        </button>
    </div>

    <!-- Active Tab Contents -->
    
    <!-- 1. ONLINE USERS TAB -->
    <div x-show="activeTab === 'online'" class="space-y-4">
        <div class="bg-card rounded-md shadow-sm border border-border overflow-hidden">
            <div class="p-3 bg-muted/20 border-b border-border flex justify-between items-center">
                <span class="text-xs font-bold text-muted-foreground uppercase tracking-wider">{{ __('Phiên trực tuyến (15 phút qua)') }}</span>
                <span class="flex items-center gap-1.5 text-xs text-green-500 font-semibold animate-pulse">
                    <span class="h-2 w-2 rounded-full bg-green-500"></span>
                    {{ __('Thời gian thực') }}
                </span>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-muted/50 text-xs font-semibold uppercase tracking-wider text-muted-foreground border-b border-border">
                        <tr>
                            <th class="py-2.5 px-4">{{ __('Đối tượng') }}</th>
                            <th class="py-2.5 px-4 w-40">{{ __('Vai trò') }}</th>
                            <th class="py-2.5 px-4 w-44">{{ __('Địa chỉ IP') }}</th>
                            <th class="py-2.5 px-4">{{ __('Thiết bị / Trình duyệt') }}</th>
                            <th class="py-2.5 px-4 w-48">{{ __('Hoạt động cuối') }}</th>
                            <th class="py-2.5 px-4 w-28 text-right">{{ __('Hành động') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border">
                        @forelse($onlineUsers as $online)
                        <tr class="table-row-hover">
                            <td class="py-3 px-4 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    @if($online->user)
                                        <span class="w-8 h-8 rounded-full bg-primary/10 border border-primary/20 flex items-center justify-center text-primary font-bold text-sm shrink-0">
                                            {{ substr($online->user->name, 0, 1) }}
                                        </span>
                                        <div>
                                            <div class="text-sm font-semibold text-foreground leading-tight">{{ $online->user->name }}</div>
                                            <div class="text-xs text-muted-foreground font-mono mt-0.5">{{ $online->user->username }}</div>
                                        </div>
                                    @else
                                        <span class="w-8 h-8 rounded-full bg-slate-200 dark:bg-slate-800 border border-border flex items-center justify-center text-muted-foreground shrink-0">
                                            <i data-lucide="user-x" class="w-4 h-4"></i>
                                        </span>
                                        <div>
                                            <div class="text-sm font-semibold text-muted-foreground italic leading-tight">{{ __('Khách vãng lai') }}</div>
                                            <div class="text-[10px] text-muted-foreground font-mono mt-0.5">{{ __('Chưa đăng nhập') }}</div>
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td class="py-3 px-4 whitespace-nowrap">
                                @if($online->user)
                                    @foreach($online->user->roles as $role)
                                        <span class="inline-flex items-center px-2 py-0.5 bg-primary/10 text-primary text-[10px] font-bold uppercase rounded-sm border border-primary/20">
                                            {{ $role->name }}
                                        </span>
                                    @endforeach
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 bg-muted text-muted-foreground text-[10px] font-bold uppercase rounded-sm border border-border">
                                        {{ __('Visitor') }}
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 px-4 whitespace-nowrap font-mono text-xs text-foreground font-medium">
                                {{ $online->ip_address }}
                            </td>
                            <td class="py-3 px-4 whitespace-nowrap">
                                <div class="flex flex-col text-xs">
                                    <div class="flex items-center gap-1.5 text-foreground font-medium">
                                        @if($online->device_type === 'desktop')
                                            <i data-lucide="monitor" class="w-3.5 h-3.5 text-muted-foreground"></i>
                                        @elseif($online->device_type === 'tablet')
                                            <i data-lucide="tablet" class="w-3.5 h-3.5 text-muted-foreground"></i>
                                        @else
                                            <i data-lucide="smartphone" class="w-3.5 h-3.5 text-muted-foreground"></i>
                                        @endif
                                        <span>{{ $online->platform ?: 'Unknown OS' }}</span>
                                    </div>
                                    <span class="text-muted-foreground text-[10px] ml-5">{{ $online->browser ?: 'Unknown Browser' }}</span>
                                </div>
                            </td>
                            <td class="py-3 px-4 whitespace-nowrap text-xs text-muted-foreground">
                                <div class="flex items-center gap-1.5">
                                    <span class="h-1.5 w-1.5 rounded-full bg-green-500 animate-pulse"></span>
                                    <span>{{ \Carbon\Carbon::createFromTimestamp($online->last_activity)->diffForHumans() }}</span>
                                </div>
                            </td>
                            <td class="py-3 px-4 text-right whitespace-nowrap">
                                @if(session()->getId() !== $online->session_id)
                                    <form action="{{ route('admin.monitoring.kick', $online->session_id) }}" method="POST" class="inline"
                                        onsubmit="return confirm('Bạn có chắc chắn muốn ngắt kết nối phiên làm việc này?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-destructive hover:bg-destructive/10 p-1.5 rounded-sm transition-colors" title="{{ __('Ép buộc đăng xuất') }}">
                                            <i data-lucide="log-out" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                                @else
                                    <span class="text-[10px] text-primary font-bold italic mr-2">{{ __('Phiên của bạn') }}</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <i data-lucide="users" class="w-8 h-8 text-muted-foreground mb-2"></i>
                                    <p class="text-muted-foreground text-sm font-bold">{{ __('Không tìm thấy phiên làm việc hoạt động nào.') }}</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- 2. AUTH HISTORY TAB -->
    <div x-show="activeTab === 'auth_history'" class="space-y-4">
        <!-- Filter Bar -->
        <div class="bg-card rounded-md shadow-sm border border-border overflow-hidden">
            <div class="p-3 bg-muted/30 border-b border-border">
                <form action="{{ route('admin.monitoring.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-2 items-end">
                    <input type="hidden" name="tab" value="auth_history">
                    
                    <div class="space-y-1">
                        <label class="block text-[10px] font-bold text-muted-foreground uppercase tracking-wider">{{ __('Tài khoản / Khách') }}</label>
                        <input type="text" name="username" value="{{ request('username') }}" placeholder="{{ __('Tìm tên đăng nhập...') }}" 
                            class="w-full h-9 px-3 py-1.5 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all">
                    </div>

                    <div class="space-y-1">
                        <label class="block text-[10px] font-bold text-muted-foreground uppercase tracking-wider">{{ __('Sự kiện') }}</label>
                        <select name="action" class="w-full h-9 px-3 py-1.5 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all">
                            <option value="">{{ __('Tất cả sự kiện') }}</option>
                            <option value="auth_login" {{ request('action') == 'auth_login' ? 'selected' : '' }}>{{ __('Đăng nhập thành công') }}</option>
                            <option value="auth_logout" {{ request('action') == 'auth_logout' ? 'selected' : '' }}>{{ __('Đăng xuất') }}</option>
                            <option value="auth_failed" {{ request('action') == 'auth_failed' ? 'selected' : '' }}>{{ __('Đăng nhập thất bại') }}</option>
                        </select>
                    </div>

                    <div class="space-y-1">
                        <label class="block text-[10px] font-bold text-muted-foreground uppercase tracking-wider">{{ __('Địa chỉ IP') }}</label>
                        <input type="text" name="ip_address" value="{{ request('ip_address') }}" placeholder="{{ __('Tìm IP...') }}" 
                            class="w-full h-9 px-3 py-1.5 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all">
                    </div>

                    <div class="space-y-1">
                        <label class="block text-[10px] font-bold text-muted-foreground uppercase tracking-wider">{{ __('Từ ngày') }}</label>
                        <input type="date" name="date_from" value="{{ request('date_from') }}" 
                            class="w-full h-9 px-3 py-1.5 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all">
                    </div>

                    <div class="flex gap-2">
                        <button type="submit" class="btn-compact-primary flex-1 h-9">
                            <i data-lucide="filter" class="w-4 h-4 mr-1"></i>
                            {{ __('Lọc') }}
                        </button>
                        @if(request()->anyFilled(['username', 'action', 'ip_address', 'date_from']))
                            <a href="{{ route('admin.monitoring.index', ['tab' => 'auth_history']) }}" class="btn-compact-secondary w-9 h-9 flex items-center justify-center p-0" title="{{ __('Xoá lọc') }}">
                                <i data-lucide="x" class="w-4 h-4"></i>
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-muted/50 text-xs font-semibold uppercase tracking-wider text-muted-foreground border-b border-border">
                        <tr>
                            <th class="py-2.5 px-4">{{ __('Người dùng') }}</th>
                            <th class="py-2.5 px-4 w-44">{{ __('Loại sự kiện') }}</th>
                            <th class="py-2.5 px-4 w-40">{{ __('IP Address') }}</th>
                            <th class="py-2.5 px-4">{{ __('Thông tin thiết bị') }}</th>
                            <th class="py-2.5 px-4 w-48">{{ __('Thời gian') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border">
                        @forelse($authLogs as $log)
                        <tr class="table-row-hover">
                            <td class="py-3 px-4 whitespace-nowrap">
                                <div class="flex items-center gap-2.5">
                                    @if($log->user)
                                        <span class="w-7 h-7 rounded-full bg-primary/10 border border-primary/20 flex items-center justify-center text-primary font-bold text-xs shrink-0">
                                            {{ substr($log->user->name, 0, 1) }}
                                        </span>
                                        <div>
                                            <div class="text-sm font-semibold text-foreground leading-tight">{{ $log->user->name }}</div>
                                            <div class="text-[10px] text-muted-foreground font-mono mt-0.5">{{ $log->user->username }}</div>
                                        </div>
                                    @else
                                        <span class="w-7 h-7 rounded-full bg-slate-100 dark:bg-slate-900 border border-border flex items-center justify-center text-muted-foreground shrink-0">
                                            <i data-lucide="user-minus" class="w-3.5 h-3.5"></i>
                                        </span>
                                        <div>
                                            <div class="text-sm font-semibold text-foreground leading-tight">{{ $log->details['username'] ?? __('Unknown') }}</div>
                                            <div class="text-[10px] text-destructive bg-destructive/10 border border-destructive/20 rounded-sm px-1.5 py-0.5 inline-block mt-0.5 leading-none text-[9px] font-bold uppercase">{{ __('Khách / Sai Auth') }}</div>
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td class="py-3 px-4 whitespace-nowrap">
                                @if($log->action === 'auth_login')
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-green-500/10 text-green-600 dark:text-green-400 text-[10px] font-bold uppercase rounded-sm border border-green-500/20">
                                        <span class="h-1.5 w-1.5 rounded-full bg-green-500"></span>
                                        {{ __('Đăng nhập') }}
                                    </span>
                                @elseif($log->action === 'auth_logout')
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-muted text-muted-foreground text-[10px] font-bold uppercase rounded-sm border border-border">
                                        <span class="h-1.5 w-1.5 rounded-full bg-muted-foreground"></span>
                                        {{ __('Đăng xuất') }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-destructive/10 text-destructive text-[10px] font-bold uppercase rounded-sm border border-destructive/20" title="{{ $log->details['reason'] ?? '' }}">
                                        <span class="h-1.5 w-1.5 rounded-full bg-destructive"></span>
                                        {{ __('Thất bại') }}
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 px-4 whitespace-nowrap font-mono text-xs text-foreground font-medium">
                                {{ $log->ip_address }}
                            </td>
                            <td class="py-3 px-4 whitespace-nowrap">
                                <div class="flex flex-col text-xs">
                                    <span class="text-foreground font-medium flex items-center gap-1">
                                        @if(($log->details['device'] ?? '') === 'Mobile')
                                            <i data-lucide="smartphone" class="w-3.5 h-3.5 text-muted-foreground"></i>
                                        @elseif(($log->details['device'] ?? '') === 'Tablet')
                                            <i data-lucide="tablet" class="w-3.5 h-3.5 text-muted-foreground"></i>
                                        @else
                                            <i data-lucide="monitor" class="w-3.5 h-3.5 text-muted-foreground"></i>
                                        @endif
                                        {{ $log->details['platform'] ?? 'OS' }}
                                    </span>
                                    <span class="text-muted-foreground text-[10px] ml-5">{{ $log->details['browser'] ?? 'Browser' }}</span>
                                </div>
                            </td>
                            <td class="py-3 px-4 whitespace-nowrap text-xs text-muted-foreground">
                                {{ $log->created_at->format('H:i:s d/m/Y') }} ({{ $log->created_at->diffForHumans() }})
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <i data-lucide="key-round" class="w-8 h-8 text-muted-foreground mb-2"></i>
                                    <p class="text-muted-foreground text-sm font-bold">{{ __('Không tìm thấy lịch sử ra vào nào.') }}</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($authLogs->hasPages())
            <div class="px-4 py-3 bg-muted/30 border-t border-border">
                {{ $authLogs->links() }}
            </div>
            @endif
        </div>
    </div>

    <!-- 3. PUBLIC ACCESS TAB -->
    <div x-show="activeTab === 'public_access'" class="space-y-4">
        <!-- Filter Bar -->
        <div class="bg-card rounded-md shadow-sm border border-border overflow-hidden">
            <div class="p-3 bg-muted/30 border-b border-border">
                <form action="{{ route('admin.monitoring.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-2 items-end">
                    <input type="hidden" name="tab" value="public_access">
                    
                    <div class="space-y-1">
                        <label class="block text-[10px] font-bold text-muted-foreground uppercase tracking-wider">{{ __('Địa chỉ IP') }}</label>
                        <input type="text" name="public_ip" value="{{ request('public_ip') }}" placeholder="{{ __('Tìm IP...') }}" 
                            class="w-full h-9 px-3 py-1.5 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all">
                    </div>

                    <div class="space-y-1">
                        <label class="block text-[10px] font-bold text-muted-foreground uppercase tracking-wider">{{ __('Đường dẫn URL') }}</label>
                        <input type="text" name="public_url" value="{{ request('public_url') }}" placeholder="{{ __('Đường dẫn truy cập...') }}" 
                            class="w-full h-9 px-3 py-1.5 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all">
                    </div>

                    <div class="space-y-1 flex items-center h-9 pl-1">
                        <label class="flex items-center gap-2 cursor-pointer select-none">
                            <input type="checkbox" name="is_guest" value="1" {{ request('is_guest') == '1' ? 'checked' : '' }}
                                class="rounded border-input text-primary focus:ring-primary h-4 w-4">
                            <span class="text-xs font-semibold text-foreground">{{ __('Chỉ hiển thị Khách vãng lai') }}</span>
                        </label>
                    </div>

                    <div class="flex gap-2">
                        <button type="submit" class="btn-compact-primary flex-1 h-9">
                            <i data-lucide="filter" class="w-4 h-4 mr-1"></i>
                            {{ __('Lọc') }}
                        </button>
                        @if(request()->anyFilled(['public_ip', 'public_url', 'is_guest']))
                            <a href="{{ route('admin.monitoring.index', ['tab' => 'public_access']) }}" class="btn-compact-secondary w-9 h-9 flex items-center justify-center p-0" title="{{ __('Xoá lọc') }}">
                                <i data-lucide="x" class="w-4 h-4"></i>
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-muted/50 text-xs font-semibold uppercase tracking-wider text-muted-foreground border-b border-border">
                        <tr>
                            <th class="py-2.5 px-4 w-48">{{ __('Nhận dạng') }}</th>
                            <th class="py-2.5 px-4 w-40">{{ __('IP Address') }}</th>
                            <th class="py-2.5 px-4">{{ __('URL đã xem') }}</th>
                            <th class="py-2.5 px-4 w-52">{{ __('Thiết bị / Trình duyệt') }}</th>
                            <th class="py-2.5 px-4 w-40">{{ __('Thời gian') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border">
                        @forelse($publicLogs as $plog)
                        <tr class="table-row-hover">
                            <td class="py-3 px-4 whitespace-nowrap">
                                @if($plog->user_id)
                                    <div class="flex items-center gap-2">
                                        <span class="w-6 h-6 rounded-full bg-primary/10 border border-primary/20 flex items-center justify-center text-primary font-bold text-[10px] shrink-0">
                                            {{ substr($plog->user_name, 0, 1) }}
                                        </span>
                                        <div class="min-w-0">
                                            <div class="text-xs font-bold text-foreground truncate">{{ $plog->user_name }}</div>
                                            <div class="text-[9px] text-muted-foreground font-mono mt-0.5 truncate">{{ $plog->user_username }}</div>
                                        </div>
                                    </div>
                                @else
                                    <div class="flex items-center gap-2">
                                        <span class="w-6 h-6 rounded-full bg-slate-100 dark:bg-slate-900 border border-border flex items-center justify-center text-muted-foreground shrink-0">
                                            <i data-lucide="user-x" class="w-3.5 h-3.5"></i>
                                        </span>
                                        <div>
                                            <div class="text-xs font-semibold text-muted-foreground italic leading-none">{{ __('Khách vãng lai') }}</div>
                                        </div>
                                    </div>
                                @endif
                            </td>
                            <td class="py-3 px-4 whitespace-nowrap font-mono text-xs text-foreground font-medium">
                                {{ $plog->ip_address }}
                            </td>
                            <td class="py-3 px-4">
                                <div class="text-xs text-foreground font-mono break-all max-w-sm truncate" title="{{ $plog->url }}">
                                    {{ $plog->url }}
                                </div>
                                @if($plog->referer)
                                    <div class="text-[10px] text-muted-foreground truncate max-w-xs mt-0.5" title="{{ $plog->referer }}">
                                        Ref: {{ $plog->referer }}
                                    </div>
                                @endif
                            </td>
                            <td class="py-3 px-4 whitespace-nowrap">
                                <div class="flex flex-col text-[11px]">
                                    <span class="text-foreground font-semibold flex items-center gap-1">
                                        @if($plog->device_type === 'mobile')
                                            <i data-lucide="smartphone" class="w-3 h-3 text-muted-foreground"></i>
                                        @elseif($plog->device_type === 'tablet')
                                            <i data-lucide="tablet" class="w-3 h-3 text-muted-foreground"></i>
                                        @else
                                            <i data-lucide="monitor" class="w-3 h-3 text-muted-foreground"></i>
                                        @endif
                                        {{ $plog->platform ?: 'OS' }}
                                    </span>
                                    <span class="text-muted-foreground text-[10px] ml-4">{{ $plog->browser ?: 'Browser' }}</span>
                                </div>
                            </td>
                            <td class="py-3 px-4 whitespace-nowrap text-xs text-muted-foreground">
                                {{ \Carbon\Carbon::parse($plog->created_at)->format('H:i:s d/m/Y') }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <i data-lucide="globe" class="w-8 h-8 text-muted-foreground mb-2"></i>
                                    <p class="text-muted-foreground text-sm font-bold">{{ __('Không tìm thấy lịch sử duyệt web nào.') }}</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($publicLogs->hasPages())
            <div class="px-4 py-3 bg-muted/30 border-t border-border">
                {{ $publicLogs->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Initialize Lucide icons on tab switch as needed
        Alpine.effect(() => {
            lucide.createIcons();
        });
    });
</script>
@endsection
