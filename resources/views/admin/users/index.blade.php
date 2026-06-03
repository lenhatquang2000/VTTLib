@extends('layouts.admin')

@section('content')
<div class="space-y-4 animate-in fade-in duration-500">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-card border border-border p-3 rounded-md shadow-sm sticky top-0 z-20 backdrop-blur-md bg-background/95">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded bg-primary/10 flex items-center justify-center text-primary border border-primary/20">
                <i data-lucide="users" class="w-5 h-5"></i>
            </div>
            <div>
                <h1 class="text-lg font-bold text-foreground tracking-tight">{{ __('Identity Command Center') }}</h1>
                <p class="text-xs text-muted-foreground">{{ __('Monitor and manage system identity sequences.') }}</p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <button onclick="openModal('createUserModal')" class="btn-compact-primary h-9">
                <i data-lucide="plus" class="w-4 h-4"></i>
                <span class="hidden sm:inline">{{ __('New Identity') }}</span>
            </button>
        </div>
    </div>

    <!-- Administrative Navigation Tabs -->
    <div class="flex items-center gap-1 p-1 bg-muted/50 border border-border rounded w-fit">
        <a href="{{ route('admin.users.index') }}" class="px-4 py-1.5 rounded-sm text-[11px] font-bold uppercase tracking-wider transition-all {{ Route::is('admin.users.index') ? 'bg-background text-primary shadow-sm border border-border' : 'text-muted-foreground hover:text-foreground hover:bg-muted' }}">
            {{ __('Users List') }}
        </a>
        <a href="{{ route('admin.users.privileges') }}" class="px-4 py-1.5 rounded-sm text-[11px] font-bold uppercase tracking-wider transition-all {{ Route::is('admin.users.privileges') ? 'bg-background text-primary shadow-sm border border-border' : 'text-muted-foreground hover:text-foreground hover:bg-muted' }}">
            {{ __('Privilege Controller') }}
        </a>
        <a href="{{ route('admin.roles.index') }}" class="px-4 py-1.5 rounded-sm text-[11px] font-bold uppercase tracking-wider transition-all {{ Route::is('admin.roles.index') ? 'bg-background text-primary shadow-sm border border-border' : 'text-muted-foreground hover:text-foreground hover:bg-muted' }}">
            {{ __('Role Management') }}
        </a>
    </div>

    
    <div class="bg-card border border-border rounded shadow-sm overflow-hidden">
        <!-- Filter Bar -->
        <div class="p-3 bg-muted/20 border-b border-border">
            <form action="{{ route('admin.users.index') }}" method="GET" class="flex flex-col sm:flex-row gap-2">
                <!-- Search Input -->
                <div class="relative flex-1 group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i data-lucide="search" class="h-3.5 w-3.5 text-muted-foreground group-focus-within:text-primary transition-colors"></i>
                    </div>
                    <input type="text" name="search" value="{{ $search }}" 
                        placeholder="{{ __('Search users...') }}" 
                        class="block w-full pl-9 pr-3 h-9 text-xs border border-input rounded bg-background text-foreground placeholder:text-muted-foreground focus:outline-none focus:ring-1 focus:ring-ring transition-all">
                </div>
                
                <!-- Role Filter -->
                <div class="w-full sm:w-40">
                    <select name="role_id" class="w-full h-9 px-3 text-xs border border-input rounded bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-ring transition-all appearance-none cursor-pointer">
                        <option value="">{{ __('All Roles') }}</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}" {{ $roleId == $role->id ? 'selected' : '' }}>{{ $role->display_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex gap-1.5">
                    <button type="submit" class="btn-compact-primary h-9 flex-1 sm:flex-none">
                        {{ __('Search') }}
                    </button>

                    @if($search || $roleId)
                        <a href="{{ route('admin.users.index') }}" 
                            class="btn-compact-secondary h-9" title="{{ __('Clear Filters') }}">
                            <i data-lucide="x" class="w-4 h-4"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Table View -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-border/50">
                <thead class="bg-muted/50">
                    <tr>
                        <th scope="col" class="px-4 py-2.5 text-left text-[10px] font-bold text-muted-foreground uppercase tracking-widest">{{ __('Identity / Terminal') }}</th>
                        <th scope="col" class="px-4 py-2.5 text-left text-[10px] font-bold text-muted-foreground uppercase tracking-widest hidden md:table-cell">{{ __('Security Clearance') }}</th>
                        <th scope="col" class="px-4 py-2.5 text-left text-[10px] font-bold text-muted-foreground uppercase tracking-widest">{{ __('Status') }}</th>
                        <th scope="col" class="px-4 py-2.5 text-left text-[10px] font-bold text-muted-foreground uppercase tracking-widest hidden sm:table-cell">{{ __('Enrolled') }}</th>
                        <th scope="col" class="px-4 py-2.5 text-right text-[10px] font-bold text-muted-foreground uppercase tracking-widest">{{ __('Operations') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border/40">
                    @forelse($users as $user)
                    <tr class="table-row-hover">
                        <td class="px-4 py-3 whitespace-nowrap">
                            <div class="flex items-center gap-3">
                                <div class="relative flex-shrink-0">
                                    <div class="h-9 w-9 rounded bg-primary/10 border border-primary/20 flex items-center justify-center text-primary font-bold text-sm uppercase">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                    @if($user->status == 'active')
                                    <div class="absolute -bottom-0.5 -right-0.5 w-2.5 h-2.5 bg-emerald-500 border-2 border-background rounded-full shadow-sm"></div>
                                    @endif
                                </div>
                                <div class="min-w-0">
                                    <div class="text-sm font-bold text-foreground leading-tight truncate">{{ $user->name }}</div>
                                    <div class="text-[10px] text-muted-foreground mt-0.5 truncate italic">@ {{ $user->username }} <span class="mx-1 text-border">•</span> {{ $user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3 hidden md:table-cell">
                            <div class="flex flex-wrap items-center gap-1.5">
                                @forelse($user->roles as $role)
                                <div class="relative group/role">
                                    <span class="inline-flex items-center px-2 py-0.5 text-[9px] font-bold uppercase tracking-wider rounded-sm border {{ $role->name == 'root' ? 'bg-destructive/10 text-destructive border-destructive/20' : 'bg-primary/10 text-primary border-primary/20' }}">
                                        {{ $role->display_name }}
                                        
                                        @if($user->id !== Auth::id() || $role->name !== 'root')
                                        <form action="{{ route('admin.users.roles.remove', $role->pivot->id) }}" method="POST" class="ml-1.5 inline-flex items-center">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-muted-foreground hover:text-destructive transition-colors" title="{{ __('Revoke Role') }}">
                                                <i data-lucide="x" class="w-2.5 h-2.5"></i>
                                            </button>
                                        </form>
                                        @endif
                                    </span>
                                </div>
                                @empty
                                <span class="text-[10px] text-muted-foreground italic opacity-60">{{ __('Chưa gán') }}</span>
                                @endforelse
                            </div>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap">
                            @php
                                $statusMap = [
                                    'active' => ['emerald', 'Authorized'],
                                    'inactive' => ['slate', 'Standby'],
                                    'suspended' => ['rose', 'Terminated'],
                                ];
                                [$color, $label] = $statusMap[$user->status] ?? ['indigo', $user->status];
                            @endphp
                            <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-sm border border-{{ $color }}-200 bg-{{ $color }}-50 dark:bg-{{ $color }}-900/10 text-{{ $color }}-700 dark:text-{{ $color }}-400 text-[9px] font-bold uppercase tracking-widest shadow-sm">
                                <span class="w-1 h-1 rounded-full bg-{{ $color }}-500"></span>
                                {{ $label }}
                            </span>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap hidden sm:table-cell">
                            <div class="text-[11px] font-bold text-foreground">
                                {{ $user->created_at ? $user->created_at->format('M d, Y') : 'N/A' }}
                            </div>
                            @if($user->created_at)
                                <div class="text-[9px] text-muted-foreground mt-0.5 uppercase tracking-tighter">{{ $user->created_at->diffForHumans() }}</div>
                            @endif
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-right">
                            <div class="flex justify-end items-center gap-1.5">
                                <button onclick="openSidebarSettings('{{ $user->roles->first()?->pivot->id }}', '{{ $user->name }}', '{{ $user->roles->first()?->name }}', {{ $user->getSidebarTabs()->pluck('id') }})"
                                    class="btn-icon-compact" title="{{ __('Gán quyền hạn (Tabs)') }}">
                                    <i data-lucide="settings-2" class="w-4 h-4"></i>
                                </button>

                                <!-- Role Assign Dropdown -->
                                <div class="relative group/role-select" x-data="{ open: false }">
                                    <button @click="open = !open" class="btn-icon-compact" title="{{ __('Gán vai trò (Roles)') }}">
                                        <i data-lucide="shield-plus" class="w-4 h-4 text-amber-500"></i>
                                    </button>
                                    
                                    <div x-show="open" @click.outside="open = false" x-cloak
                                        class="absolute right-0 top-full mt-1 w-56 bg-card border border-border rounded shadow-lg z-50 overflow-hidden">
                                        <div class="px-3 py-2 border-b border-border bg-muted/30">
                                            <p class="text-[9px] font-bold text-muted-foreground uppercase tracking-widest">{{ __('Escalate Clearance') }}</p>
                                            <p class="text-10px font-bold text-foreground truncate mt-0.5">{{ $user->name }}</p>
                                        </div>
                                        <div class="p-1 space-y-0.5 max-h-48 overflow-y-auto custom-scrollbar">
                                            @php
                                                $availableRoles = $roles->reject(fn($r) => $user->roles->contains($r->id));
                                            @endphp
                                            @forelse($availableRoles as $role)
                                            <form action="{{ route('admin.users.roles.store') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="user_id" value="{{ $user->id }}">
                                                <input type="hidden" name="role_id" value="{{ $role->id }}">
                                                <button type="submit" class="w-full text-left px-3 py-2 text-[10px] font-bold uppercase tracking-tight text-muted-foreground hover:bg-primary hover:text-primary-foreground rounded-sm transition-all flex items-center justify-between group/item">
                                                    {{ $role->display_name }}
                                                    <i data-lucide="plus" class="w-3 h-3 opacity-0 group-hover/item:opacity-100 transition-opacity"></i>
                                                </button>
                                            </form>
                                            @empty
                                            <div class="px-3 py-2 text-[10px] font-bold text-muted-foreground italic text-center opacity-60">{{ __('Full Access Granted') }}</div>
                                            @endforelse
                                        </div>
                                    </div>
                                </div>

                                <a href="{{ route('admin.users.edit', $user) }}" class="btn-icon-compact" title="{{ __('Edit Master Data') }}">
                                    <i data-lucide="edit" class="w-4 h-4 text-blue-500"></i>
                                </a>
                                @if($user->id !== Auth::id())
                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('CRITICAL: Delete this core identity permanently?')" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-icon-danger" title="{{ __('Destroy Identity') }}">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-4 py-16 text-center">
                            <div class="flex flex-col items-center max-w-sm mx-auto">
                                <div class="w-16 h-16 bg-muted rounded flex items-center justify-center mb-4 border border-border opacity-20">
                                    <i data-lucide="fingerprint" class="w-8 h-8 text-muted-foreground"></i>
                                </div>
                                <h4 class="text-base font-bold text-foreground tracking-tight">{{ __('No Identities Detected') }}</h4>
                                <p class="text-xs text-muted-foreground mt-1">{{ __('Try adjusting your filter parameters to locate the subject fingerprint.') }}</p>
                                <a href="{{ route('admin.users.index') }}" class="btn-compact-primary mt-6 px-6">{{ __('Reset Global Filter') }}</a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-4 py-4 bg-muted/20 border-t border-border flex flex-col sm:flex-row justify-between items-center gap-4">
            <div class="text-[10px] text-muted-foreground font-bold uppercase tracking-widest">
                {{ __('Displaying Sequence') }} <span class="text-foreground">{{ $users->firstItem() ?? 0 }}</span> - <span class="text-foreground">{{ $users->lastItem() ?? 0 }}</span> <span class="mx-2 text-border">/</span> {{ $users->total() }} Identitiy records
            </div>
            <div class="custom-pagination">
                {{ $users->links() }}
            </div>
        </div>
    </div>

    <!-- CREATE USER MODAL -->
    <div id="createUserModal" class="fixed inset-0 z-[9999] hidden">
        <div class="absolute inset-0 bg-background/80 backdrop-blur-sm" onclick="closeModal('createUserModal')"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-xl p-4">
            <div class="bg-card rounded-md shadow-lg border border-border overflow-hidden animate-in zoom-in duration-200">
                <div class="h-1 w-full bg-primary"></div>
                <div class="p-5">
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <h3 class="text-lg font-bold text-foreground tracking-tight">{{ __('Initialize Identity') }}</h3>
                            <p class="text-muted-foreground uppercase text-[9px] font-bold tracking-widest mt-0.5">Protocol: MASTER_DATA_ENTRY_V2</p>
                        </div>
                        <button onclick="closeModal('createUserModal')" class="w-8 h-8 flex items-center justify-center text-muted-foreground hover:text-destructive transition-colors rounded-sm hover:bg-muted">
                            <i data-lucide="x" class="w-5 h-5"></i>
                        </button>
                    </div>
                    
                    <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <input type="hidden" name="max_id" id="max_id_input" value="{{ $maxUserId ?? 0 }}">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-bold text-muted-foreground uppercase tracking-widest pl-1 block">{{ __('Full Subject Name') }}</label>
                                <input type="text" name="name" id="name_input" required 
                                    class="input-field h-9 text-xs">
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-bold text-muted-foreground uppercase tracking-widest pl-1 block">{{ __('Identity ID') }} (Username)</label>
                                <input type="text" name="username" id="username_input" required 
                                    class="input-field h-9 text-xs font-mono">
                                <p id="username_status" class="text-[9px] font-bold mt-1 pl-1 hidden"></p>
                            </div>
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-[10px] font-bold text-muted-foreground uppercase tracking-widest pl-1 block">{{ __('Relay Email Address') }}</label>
                            <input type="email" name="email" required 
                                class="input-field h-9 text-xs">
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-bold text-muted-foreground uppercase tracking-widest pl-1 block">{{ __('Master Cipher') }} (Password)</label>
                                <input type="password" name="password" id="password_input" required 
                                    class="input-field h-9 text-xs">
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-bold text-muted-foreground uppercase tracking-widest pl-1 block">{{ __('Initial Clearance') }} (Role)</label>
                                <select name="role_id" required 
                                    class="input-field h-9 text-xs appearance-none">
                                    @foreach($roles as $role)
                                        <option value="{{ $role->id }}">{{ $role->display_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="flex gap-2 pt-4">
                            <button type="button" onclick="closeModal('createUserModal')" class="btn-compact-secondary flex-1 h-10">{{ __('Discard') }}</button>
                            <button type="submit" class="btn-compact-primary flex-1 h-10 uppercase tracking-widest">{{ __('Execute Enrollment') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- SIDEBAR SETTINGS MODAL -->
    <div id="sidebarModal" class="fixed inset-0 z-[9999] hidden">
        <div class="absolute inset-0 bg-background/80 backdrop-blur-sm" onclick="closeModal('sidebarModal')"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-2xl p-4">
            <div class="bg-card rounded-md shadow-lg border border-border overflow-hidden animate-in zoom-in duration-200 flex flex-col max-h-[90vh]">
                <div class="p-5 border-b border-border">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="text-lg font-bold text-foreground tracking-tight">{{ __('Access Control Terminal') }}</h3>
                            <div class="mt-2 flex flex-wrap items-center gap-2">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-sm bg-primary/10 text-primary border border-primary/20 text-[9px] font-bold uppercase tracking-widest">
                                    Target: <span id="modal-subject-name" class="ml-1 font-bold"></span>
                                </span>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-sm bg-amber-100 dark:bg-amber-900/20 text-amber-700 dark:text-amber-400 border border-amber-200 dark:border-amber-900/30 text-[9px] font-bold uppercase tracking-widest">
                                    Role: <span id="modal-role-name" class="ml-1 font-bold"></span>
                                </span>
                            </div>
                        </div>
                        <button onclick="closeModal('sidebarModal')" class="w-8 h-8 flex items-center justify-center text-muted-foreground hover:text-destructive transition-colors rounded-sm hover:bg-muted">
                            <i data-lucide="x" class="w-5 h-5"></i>
                        </button>
                    </div>
                </div>

                <div class="flex-1 overflow-y-auto p-4 custom-scrollbar bg-muted/10">
                    <form id="sidebarTabsForm" method="POST">
                        @csrf
                        <div class="space-y-4">
                            @foreach($sidebars as $sidebar)
                                <div class="space-y-2 pb-4 border-b border-border last:border-0 last:pb-0">
                                    <label class="flex items-center gap-3 p-2 hover:bg-muted/50 rounded-sm transition-colors cursor-pointer group">
                                        <input type="checkbox" name="sidebar_ids[]" value="{{ $sidebar->id }}" 
                                            class="sidebar-checkbox w-4 h-4 rounded-sm text-primary border-input focus:ring-ring focus:ring-offset-0 transition-all cursor-pointer">
                                        <div class="flex items-center gap-2">
                                            <span class="text-muted-foreground group-hover:text-primary transition-colors">
                                                {!! $sidebar->icon !!}
                                            </span>
                                            <span class="text-xs font-bold text-foreground uppercase tracking-wide">{{ __($sidebar->name) }}</span>
                                        </div>
                                    </label>
                                    
                                    @if($sidebar->children->isNotEmpty())
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-1.5 pl-9">
                                            @foreach($sidebar->children as $child)
                                                <label class="flex items-center gap-2.5 p-1.5 hover:bg-muted/50 rounded-sm transition-colors cursor-pointer group">
                                                    <input type="checkbox" name="sidebar_ids[]" value="{{ $child->id }}" 
                                                        class="sidebar-checkbox w-3.5 h-3.5 rounded-sm text-primary border-input focus:ring-ring focus:ring-offset-0 transition-all cursor-pointer">
                                                    <span class="text-[10px] font-medium text-muted-foreground group-hover:text-foreground uppercase transition-colors">{{ __($child->name) }}</span>
                                                </label>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </form>
                </div>

                <div class="p-4 border-t border-border bg-card flex justify-end gap-2">
                    <button type="button" onclick="closeModal('sidebarModal')" class="btn-compact-secondary px-4">{{ __('Discard') }}</button>
                    <button type="button" onclick="document.getElementById('sidebarTabsForm').submit()" class="btn-compact-primary px-6 uppercase tracking-widest">{{ __('Commit Changes') }}</button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    [x-cloak] { display: none !important; }
</style>

<script>
    function openModal(id) {
        document.getElementById(id).classList.remove('hidden');
    }
    function closeModal(id) {
        document.getElementById(id).classList.add('hidden');
    }

    function openSidebarSettings(roleUserId, name, role, assignedIds) {
        if (!roleUserId || roleUserId === 'null') {
            Swal.fire({
                icon: 'warning',
                title: 'No Active Role',
                text: 'Cannot modify tabs for a user without an active role. Please assign a role first.',
                confirmButtonColor: 'hsl(var(--primary))'
            });
            return;
        }
        document.getElementById('modal-subject-name').innerText = name;
        document.getElementById('modal-role-name').innerText = (role || 'N/A').toUpperCase();

        const form = document.getElementById('sidebarTabsForm');
        form.action = `{{ route('admin.users.tabs', ['id' => ':id']) }}`.replace(':id', roleUserId);

        const checkboxes = document.querySelectorAll('.sidebar-checkbox');
        checkboxes.forEach(cb => {
            cb.checked = assignedIds.includes(parseInt(cb.value));
        });
        
        openModal('sidebarModal');
    }

    // Initialize Lucide icons
    document.addEventListener('DOMContentLoaded', function() {
        lucide.createIcons();
    });

    // Auto-generate username from name
    const nameInput = document.getElementById('name_input');
    const usernameInput = document.getElementById('username_input');
    const maxIdInput = document.getElementById('max_id_input');
    const usernameStatus = document.getElementById('username_status');

    if (nameInput && usernameInput) {
        nameInput.addEventListener('blur', function() {
            if (!usernameInput.value && this.value) {
                const base = this.value.toLowerCase()
                    .normalize('NFD').replace(/[\u0300-\u036f]/g, '') // remove accents
                    .replace(/[^\w\s]/gi, '')
                    .split(' ')
                    .map(word => word.charAt(0))
                    .join('');
                
                const nextId = parseInt(maxIdInput.value) + 1;
                usernameInput.value = base + nextId;
                
                // Trigger check
                checkUsername(usernameInput.value);
            }
        });

        usernameInput.addEventListener('input', function() {
            if (this.value.length >= 3) {
                checkUsername(this.value);
            } else {
                usernameStatus.classList.add('hidden');
            }
        });
    }

    function checkUsername(username) {
        fetch(`{{ route('admin.users.check') }}?username=${username}`)
            .then(res => res.json())
            .then(data => {
                usernameStatus.classList.remove('hidden');
                if (data.exists) {
                    usernameStatus.innerText = '⚠️ Identity ID already registered';
                    usernameStatus.className = 'text-[9px] font-bold mt-1 pl-1 text-destructive';
                } else {
                    usernameStatus.innerText = '✅ Identity ID available';
                    usernameStatus.className = 'text-[9px] font-bold mt-1 pl-1 text-emerald-500';
                }
            });
    }

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            document.querySelectorAll('.fixed').forEach(m => {
                if(!m.classList.contains('hidden')) {
                    closeModal(m.id);
                }
            });
        }
    });

    // Re-validate logic for username generation on input
    let debounceTimer;
    nameInput?.addEventListener('input', function() {
        clearTimeout(debounceTimer);
        const name = this.value.trim();
        
        if (!name) {
            usernameInput.value = '';
            usernameStatus?.classList.add('hidden');
            return;
        }

        debounceTimer = setTimeout(() => {
            const words = name.split(/\s+/);
            if (words.length > 0) {
                let baseGenerated = '';
                for (let i = 0; i < words.length - 1; i++) {
                    baseGenerated += words[i].charAt(0);
                }
                baseGenerated += words[words.length - 1];
                
                function removeAccents(str) {
                    return str.normalize('NFD')
                              .replace(/[\u0300-\u036f]/g, '')
                              .replace(/đ/g, 'd').replace(/Đ/g, 'D');
                }

                const sanitized = removeAccents(baseGenerated).toLowerCase().replace(/[^a-z0-9]/g, '');
                checkUsername(sanitized);
                usernameInput.value = sanitized;
            }
        }, 800);
    });
</script>
@endsection
