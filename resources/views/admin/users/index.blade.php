@extends('layouts.admin')

@section('content')
<div class="space-y-4 animate-in fade-in duration-500">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <h1 class="text-xl font-bold text-foreground tracking-tight">{{ __('User Management') }}</h1>
            <p class="text-sm text-muted-foreground">{{ __('Monitor and manage system identity sequences.') }}</p>
        </div>
        <div class="flex items-center gap-2">
            <button onclick="openModal('createUserModal')" class="btn-compact-primary">
                <i data-lucide="plus" class="w-4 h-4"></i>
                {{ __('New User') }}
            </button>
        </div>
    </div>

    <!-- Administrative Navigation Tabs -->
    <div class="flex items-center gap-1 p-1 bg-muted/50 rounded-md w-fit border border-border">
        <a href="{{ route('admin.users.index') }}" class="px-4 py-1.5 rounded-sm text-xs font-semibold transition-all {{ Route::is('admin.users.index') ? 'bg-card text-primary shadow-sm' : 'text-muted-foreground hover:text-foreground' }}">
            {{ __('Users List') }}
        </a>
        <a href="{{ route('admin.roles.index') }}" class="px-4 py-1.5 rounded-sm text-xs font-semibold transition-all {{ Route::is('admin.roles.index') ? 'bg-card text-primary shadow-sm' : 'text-muted-foreground hover:text-foreground' }}">
            {{ __('Role Management') }}
        </a>
    </div>

    <div class="bg-card rounded-md shadow-sm border border-border overflow-hidden">
        <!-- Filter Bar -->
        <div class="p-3 bg-muted/30 border-b border-border">
            <form action="{{ route('admin.users.index') }}" method="GET" class="flex flex-col sm:flex-row gap-2">
                <!-- Search Input -->
                <div class="relative flex-1 sm:max-w-xs">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none">
                        <i data-lucide="search" class="w-4 h-4 text-muted-foreground"></i>
                    </div>
                    <input type="text" name="search" value="{{ $search }}" 
                        placeholder="{{ __('Search users...') }}" 
                        class="block w-full pl-9 pr-3 py-1.5 h-9 text-sm border border-input rounded-sm bg-background text-foreground placeholder-muted-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all">
                </div>
                
                <!-- Role Filter -->
                <select name="role_id" class="h-9 w-full sm:w-40 px-3 py-1.5 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all">
                    <option value="">{{ __('All Roles') }}</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->id }}" {{ $roleId == $role->id ? 'selected' : '' }}>{{ $role->display_name }}</option>
                    @endforeach
                </select>

                <!-- Action Buttons -->
                <div class="flex gap-2">
                    <button type="submit" class="btn-compact-primary h-9 px-4">
                        {{ __('Search') }}
                    </button>

                    @if($search || $roleId)
                        <a href="{{ route('admin.users.index') }}" 
                            class="btn-compact-secondary h-9 px-4">
                            <i data-lucide="x" class="w-4 h-4 mr-1"></i>
                            {{ __('Clear') }}
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
                        <th class="py-2 px-3 w-12 text-center">#</th>
                        <th class="py-2 px-3">{{ __('Identity / Terminal') }}</th>
                        <th class="py-2 px-3">{{ __('Clearance') }}</th>
                        <th class="py-2 px-3 w-32">{{ __('Status') }}</th>
                        <th class="py-2 px-3 w-40">{{ __('Enrolled') }}</th>
                        <th class="py-2 px-3 w-48 text-right">{{ __('Operations') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @forelse($users as $index => $user)
                    <tr class="table-row-hover group">
                        <td class="py-2 px-3 text-center text-muted-foreground font-medium text-xs">
                            {{ $users->firstItem() + $index }}
                        </td>
                        <td class="py-2 px-3">
                            <div class="flex items-center gap-3">
                                <div class="h-8 w-8 rounded-full bg-primary/10 border border-primary/20 flex items-center justify-center text-primary font-bold text-xs uppercase shrink-0">
                                    {{ substr($user->name, 0, 1) }}
                                </div>
                                <div class="min-w-0">
                                    <div class="text-sm font-semibold text-foreground leading-tight truncate">{{ $user->name }}</div>
                                    <div class="text-[11px] text-muted-foreground leading-tight truncate">@ {{ $user->username }} <span class="mx-1">•</span> {{ $user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="py-2 px-3">
                            <div class="flex flex-wrap gap-1">
                                @forelse($user->roles as $role)
                                <span class="inline-flex items-center px-2 py-0.5 text-[10px] font-medium rounded-sm border {{ $role->name == 'root' ? 'bg-destructive/10 text-destructive border-destructive/20' : 'bg-primary/10 text-primary border-primary/20' }}">
                                    {{ $role->display_name }}
                                    @if($user->id !== Auth::id() || $role->name !== 'root')
                                    <form action="{{ route('admin.users.roles.remove', $role->pivot->id) }}" method="POST" class="ml-1 inline-flex items-center">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="hover:text-destructive transition-colors">
                                            <i data-lucide="x" class="w-2.5 h-2.5"></i>
                                        </button>
                                    </form>
                                    @endif
                                </span>
                                @empty
                                <span class="text-[10px] text-muted-foreground italic opacity-70">{{ __('Unassigned') }}</span>
                                @endforelse
                            </div>
                        </td>
                        <td class="py-2 px-3">
                            @php
                                $statusMap = [
                                    'active' => ['bg-green-500/10 text-green-600 dark:text-green-400 border-green-500/20', 'Authorized'],
                                    'inactive' => ['bg-muted text-muted-foreground border-border', 'Standby'],
                                    'suspended' => ['bg-destructive/10 text-destructive border-destructive/20', 'Terminated'],
                                ];
                                [$statusClass, $statusLabel] = $statusMap[$user->status] ?? ['bg-primary/10 text-primary border-primary/20', $user->status];
                            @endphp
                            <span class="inline-flex items-center px-2 py-0.5 rounded-sm text-[10px] font-bold border {{ $statusClass }}">
                                {{ $statusLabel }}
                            </span>
                        </td>
                        <td class="py-2 px-3">
                            <div class="text-[11px] font-medium text-foreground">
                                {{ $user->created_at ? $user->created_at->format('M d, Y') : 'N/A' }}
                            </div>
                            @if($user->created_at)
                                <div class="text-[10px] text-muted-foreground opacity-70">{{ $user->created_at->diffForHumans() }}</div>
                            @endif
                        </td>
                        <td class="py-2 px-3 text-right">
                            <div class="flex justify-end items-center gap-1.5">


                                <!-- Role Assign Dropdown (using Alpine for compactness) -->
                                <div x-data="{ open: false }" class="relative" :class="open ? 'z-50' : 'z-10'">
                                    <button @click="open = !open" @click.away="open = false" 
                                        class="btn-icon-compact" title="{{ __('Assign Role') }}">
                                        <i data-lucide="user-plus" class="w-4 h-4"></i>
                                    </button>
                                    
                                    <div x-show="open" x-transition class="absolute right-0 top-full mt-1 w-48 bg-card rounded-md shadow-lg border border-border z-50 p-1">
                                        <div class="px-2 py-1.5 border-b border-border mb-1">
                                            <p class="text-[10px] font-bold text-muted-foreground uppercase">{{ __('Assign Clearance') }}</p>
                                        </div>
                                        <div class="space-y-0.5 max-h-40 overflow-y-auto custom-scrollbar">
                                            @php
                                                $availableRoles = $roles->reject(fn($r) => $user->roles->contains($r->id));
                                            @endphp
                                            @forelse($availableRoles as $role)
                                            <form action="{{ route('admin.users.roles.store') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="user_id" value="{{ $user->id }}">
                                                <input type="hidden" name="role_id" value="{{ $role->id }}">
                                                <button type="submit" class="w-full text-left px-2 py-1.5 text-xs font-medium hover:bg-muted rounded transition-colors flex items-center justify-between">
                                                    {{ $role->display_name }}
                                                    <i data-lucide="plus" class="w-3 h-3"></i>
                                                </button>
                                            </form>
                                            @empty
                                            <div class="px-2 py-2 text-[10px] font-medium text-muted-foreground italic text-center">{{ __('Full Access Granted') }}</div>
                                            @endforelse
                                        </div>
                                    </div>
                                </div>

                                <a href="{{ route('admin.users.edit', $user) }}" class="btn-icon-compact" title="{{ __('Edit') }}">
                                    <i data-lucide="edit-2" class="w-4 h-4"></i>
                                </a>

                                @if($user->id !== Auth::id())
                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('CRITICAL: Delete this identity permanently?')" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-icon-danger" title="{{ __('Delete') }}">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-12 text-center">
                            <div class="flex flex-col items-center max-w-sm mx-auto">
                                <div class="w-12 h-12 bg-muted rounded-full flex items-center justify-center mb-4 border border-border">
                                    <i data-lucide="search-x" class="w-6 h-6 text-muted-foreground"></i>
                                </div>
                                <h4 class="text-base font-bold text-foreground">{{ __('No Identities Detected') }}</h4>
                                <p class="text-muted-foreground text-sm mt-1">{{ __('Try adjusting your filters.') }}</p>
                                <a href="{{ route('admin.users.index') }}" class="btn-compact-primary mt-4">
                                    {{ __('Reset Filters') }}
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-4 py-3 bg-muted/30 border-t border-border flex flex-col sm:flex-row justify-between items-center gap-4">
            <div class="text-[10px] text-muted-foreground font-bold uppercase tracking-wider">
                {{ __('Displaying') }} {{ $users->firstItem() ?? 0 }} - {{ $users->lastItem() ?? 0 }} {{ __('of') }} {{ $users->total() }}
            </div>
            <div>
                {{ $users->links() }}
            </div>
        </div>
    </div>

    <!-- CREATE USER MODAL -->
    <div id="createUserModal" class="fixed inset-0 z-[9999] hidden">
        <div class="absolute inset-0 bg-background/80 backdrop-blur-sm" onclick="closeModal('createUserModal')"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-md p-4">
            <div class="bg-card rounded-md shadow-lg relative overflow-hidden transition-all border border-border flex flex-col">
                <div class="p-4 border-b border-border flex justify-between items-center">
                    <div>
                        <h3 class="text-base font-bold text-foreground leading-tight">{{ __('Initialize Identity') }}</h3>
                        <p class="text-[10px] text-muted-foreground uppercase font-bold tracking-widest mt-0.5">Protocol: MASTER_DATA_ENTRY_V2</p>
                    </div>
                    <button onclick="closeModal('createUserModal')" class="btn-icon-compact">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </button>
                </div>
                
                <form action="{{ route('admin.users.store') }}" method="POST" class="p-4 space-y-3">
                    @csrf
                    <input type="hidden" name="max_id" id="max_id_input" value="{{ $maxUserId ?? 0 }}">
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div class="space-y-1">
                            <label class="text-[10px] font-bold text-muted-foreground uppercase tracking-wider block">{{ __('Full Name') }}</label>
                            <input type="text" name="name" id="name_input" required 
                                class="w-full h-9 px-3 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all">
                        </div>
                        <div class="space-y-1">
                            <label class="text-[10px] font-bold text-muted-foreground uppercase tracking-wider block">{{ __('Username') }}</label>
                            <input type="text" name="username" id="username_input" required 
                                class="w-full h-9 px-3 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all font-mono">
                            <p id="username_status" class="text-[9px] font-bold mt-1 hidden"></p>
                        </div>
                    </div>

                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-muted-foreground uppercase tracking-wider block">{{ __('Email Address') }}</label>
                        <input type="email" name="email" required 
                            class="w-full h-9 px-3 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div class="space-y-1">
                            <label class="text-[10px] font-bold text-muted-foreground uppercase tracking-wider block">{{ __('Password') }}</label>
                            <div class="relative">
                                <input type="password" name="password" id="password_input" required 
                                    class="w-full h-9 pl-3 pr-9 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all">
                                <button type="button" id="toggle-password-visibility" class="absolute inset-y-0 right-0 pr-3 flex items-center text-muted-foreground hover:text-foreground">
                                    <i data-lucide="eye" class="w-4 h-4 eye-icon"></i>
                                    <i data-lucide="eye-off" class="w-4 h-4 eye-off-icon hidden"></i>
                                </button>
                            </div>
                            <button type="button" id="generate-password-btn" class="btn-compact-secondary text-[10px] w-full mt-1.5 py-1">
                                <i data-lucide="key" class="w-3.5 h-3.5 mr-1"></i>
                                {{ __('Auto Generate Password') }}
                            </button>
                        </div>
                        <div class="space-y-1">
                            <label class="text-[10px] font-bold text-muted-foreground uppercase tracking-wider block">{{ __('Initial Role') }}</label>
                            <select name="role_id" required 
                                class="w-full h-9 px-3 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all">
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}">{{ $role->display_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Password Requirements Checklist -->
                    <div class="mt-2 space-y-1 text-[11px] bg-muted/30 p-2.5 rounded border border-border" id="password-requirements">
                        <div class="font-bold text-muted-foreground uppercase tracking-wide text-[9px] mb-1">{{ __('Requirements:') }}</div>
                        <div id="req-length" class="flex items-center gap-1.5 text-muted-foreground transition-all duration-200">
                            <i data-lucide="circle" class="w-3 h-3 text-slate-400 circle-icon"></i>
                            <i data-lucide="check-circle" class="w-3 h-3 text-green-500 check-icon hidden"></i>
                            <span>{{ __('At least 8 characters') }}</span>
                        </div>
                        <div id="req-mixed" class="flex items-center gap-1.5 text-muted-foreground transition-all duration-200">
                            <i data-lucide="circle" class="w-3 h-3 text-slate-400 circle-icon"></i>
                            <i data-lucide="check-circle" class="w-3 h-3 text-green-500 check-icon hidden"></i>
                            <span>{{ __('At least one uppercase and one lowercase letter') }}</span>
                        </div>
                        <div id="req-numbers" class="flex items-center gap-1.5 text-muted-foreground transition-all duration-200">
                            <i data-lucide="circle" class="w-3 h-3 text-slate-400 circle-icon"></i>
                            <i data-lucide="check-circle" class="w-3 h-3 text-green-500 check-icon hidden"></i>
                            <span>{{ __('At least one number') }}</span>
                        </div>
                        <div id="req-symbols" class="flex items-center gap-1.5 text-muted-foreground transition-all duration-200">
                            <i data-lucide="circle" class="w-3 h-3 text-slate-400 circle-icon"></i>
                            <i data-lucide="check-circle" class="w-3 h-3 text-green-500 check-icon hidden"></i>
                            <span>{{ __('At least one symbol') }}</span>
                        </div>
                    </div>

                    <!-- Password Copy Area -->
                    <div id="copy-container" class="mt-2 space-y-1 hidden">
                        <div class="flex items-center gap-2 p-2 bg-emerald-50 dark:bg-emerald-950/10 border border-emerald-200 dark:border-emerald-900/30 rounded">
                            <div class="min-w-0 flex-1">
                                <p class="text-[9px] text-emerald-600 dark:text-emerald-400 font-bold uppercase tracking-wider">{{ __('Generated Password:') }}</p>
                                <p id="password-display" class="font-mono text-sm text-foreground select-all break-all"></p>
                            </div>
                            <button type="button" id="copy-password-btn" class="btn-compact-primary text-[10px] whitespace-nowrap px-3 py-1.5 flex items-center gap-1">
                                <i data-lucide="copy" class="w-3.5 h-3.5 copy-btn-icon"></i>
                                <i data-lucide="check" class="w-3.5 h-3.5 check-btn-icon hidden"></i>
                                <span class="copy-btn-text">{{ __('Copy') }}</span>
                            </button>
                        </div>
                        <p id="copy-helper-text" class="text-[9px] text-amber-600 dark:text-amber-400 font-bold mt-1">
                            {{ __('Please copy the password before submitting.') }}
                        </p>
                    </div>

                    <div class="flex gap-2 pt-4">
                        <button type="button" onclick="closeModal('createUserModal')" class="btn-compact-secondary flex-1">{{ __('Discard') }}</button>
                        <button type="submit" id="submit-create-user-btn" class="btn-compact-primary flex-1">{{ __('Enroll Subject') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- SIDEBAR SETTINGS MODAL -->
    <div id="sidebarModal" class="fixed inset-0 z-[9999] hidden">
        <div class="absolute inset-0 bg-background/80 backdrop-blur-sm" onclick="closeModal('sidebarModal')"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-2xl p-4">
            <div class="bg-card rounded-md shadow-lg relative overflow-hidden max-h-[90vh] flex flex-col border border-border">
                <div class="p-4 border-b border-border bg-card">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="text-base font-bold text-foreground leading-tight">{{ __('Access Control Terminal') }}</h3>
                            <div class="mt-2 flex flex-wrap gap-2">
                                <span class="bg-primary/10 px-2 py-0.5 rounded-sm text-[10px] font-bold text-primary uppercase border border-primary/20">Target: <span id="modal-subject-name" class="ml-1 text-foreground"></span></span>
                                <span class="bg-amber-500/10 px-2 py-0.5 rounded-sm text-[10px] font-bold text-amber-600 dark:text-amber-400 uppercase border border-amber-500/20">Role: <span id="modal-role-name" class="ml-1 text-foreground"></span></span>
                            </div>
                        </div>
                        <button onclick="closeModal('sidebarModal')" class="btn-icon-compact">
                            <i data-lucide="x" class="w-4 h-4"></i>
                        </button>
                    </div>
                </div>

                <div class="flex-1 overflow-y-auto p-4 bg-muted/10 custom-scrollbar">
                    <form id="sidebarTabsForm" method="POST">
                        @csrf
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @foreach($sidebars as $sidebar)
                                <div class="space-y-2 mb-4">
                                    <label class="flex items-center gap-3 p-2 bg-card rounded border border-border hover:border-primary/50 transition-all cursor-pointer group">
                                        <input type="checkbox" name="sidebar_ids[]" value="{{ $sidebar->id }}" class="sidebar-checkbox w-4 h-4 rounded-sm text-primary border-input bg-background focus:ring-primary focus:ring-offset-background">
                                        <span class="text-xs font-bold text-foreground uppercase tracking-wide">{{ __($sidebar->name) }}</span>
                                    </label>
                                    
                                    @if($sidebar->children->isNotEmpty())
                                        <div class="grid grid-cols-1 gap-1.5 pl-6 border-l border-border ml-4">
                                            @foreach($sidebar->children as $child)
                                                <label class="flex items-center gap-2.5 p-1.5 hover:bg-muted/50 rounded-sm cursor-pointer transition-all">
                                                    <input type="checkbox" name="sidebar_ids[]" value="{{ $child->id }}" class="sidebar-checkbox w-3.5 h-3.5 rounded-sm text-primary border-input bg-background focus:ring-primary">
                                                    <span class="text-[11px] font-medium text-muted-foreground hover:text-foreground transition-colors">{{ __($child->name) }}</span>
                                                </label>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </form>
                </div>

                <div class="p-3 border-t border-border bg-card flex justify-end gap-2">
                    <button type="button" onclick="closeModal('sidebarModal')" class="btn-compact-secondary">{{ __('Discard') }}</button>
                    <button type="button" onclick="document.getElementById('sidebarTabsForm').submit()" class="btn-compact-primary">{{ __('Commit Changes') }}</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function openModal(id) {
        document.getElementById(id).classList.remove('hidden');
        lucide.createIcons();
    }
    
    function closeModal(id) {
        document.getElementById(id).classList.add('hidden');
    }

    // Close modals on Escape key
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            document.querySelectorAll('.fixed:not(.hidden)').forEach(modal => {
                closeModal(modal.id);
            });
        }
    });

    function openSidebarSettings(roleUserId, name, role, assignedIds) {
        if (!roleUserId || roleUserId === 'null') {
            Swal.fire({
                icon: 'warning',
                title: 'No Role Assigned',
                text: 'Cannot modify tabs for a user without an active role.',
                customClass: {
                    popup: 'rounded-md',
                    confirmButton: 'btn-compact-primary'
                }
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

    // Username Generation Logic
    const nameInput = document.getElementById('name_input');
    const usernameInput = document.getElementById('username_input');
    const usernameStatus = document.getElementById('username_status');
    
    let debounceTimer;
    nameInput?.addEventListener('input', function() {
        if (usernameInput.value === '' || usernameInput.dataset.auto === 'true') {
            clearTimeout(debounceTimer);
            const name = this.value.trim();
            if (!name) {
                usernameInput.value = '';
                usernameStatus?.classList.add('hidden');
                return;
            }

            debounceTimer = setTimeout(() => {
                let username = name.toLowerCase()
                    .normalize('NFD').replace(/[\u0300-\u036f]/g, '')
                    .replace(/[^\w\s]/gi, '')
                    .replace(/\s+/g, '.')
                    .trim();
                
                if (username) {
                    usernameInput.value = username;
                    usernameInput.dataset.auto = 'true';
                    checkUsername(username);
                }
            }, 500);
        }
    });

    usernameInput?.addEventListener('input', function() {
        this.dataset.auto = 'false';
        checkUsername(this.value);
    });

    async function checkUsername(username) {
        if (username.length < 3) return;
        try {
            const response = await fetch(`{{ route('admin.users.check') }}?username=${username}`);
            const data = await response.json();
            if (usernameStatus) {
                usernameStatus.classList.remove('hidden');
                if (!data.exists) {
                    usernameStatus.innerText = '✓ ' + '{{ __("Available") }}';
                    usernameStatus.className = 'text-[9px] font-bold mt-1 text-green-600 uppercase';
                } else {
                    usernameStatus.innerText = '✕ ' + '{{ __("Taken") }}';
                    usernameStatus.className = 'text-[9px] font-bold mt-1 text-destructive uppercase';
                }
            }
        } catch (error) {
            console.error('Error checking username:', error);
        }
    }

    // Password Live Validation & Generation Logic
    const passwordInput = document.getElementById('password_input');
    const toggleVisibilityBtn = document.getElementById('toggle-password-visibility');
    const generateBtn = document.getElementById('generate-password-btn');
    const copyContainer = document.getElementById('copy-container');
    const passwordDisplay = document.getElementById('password-display');
    const copyBtn = document.getElementById('copy-password-btn');
    const submitBtn = document.getElementById('submit-create-user-btn');

    // Requirements indicators
    const reqLength = document.getElementById('req-length');
    const reqMixed = document.getElementById('req-mixed');
    const reqNumbers = document.getElementById('req-numbers');
    const reqSymbols = document.getElementById('req-symbols');

    let hasCopied = false;

    // Toggle password visibility
    toggleVisibilityBtn?.addEventListener('click', function() {
        const isPassword = passwordInput.type === 'password';
        passwordInput.type = isPassword ? 'text' : 'password';
        
        const eyeIcon = this.querySelector('.eye-icon');
        const eyeOffIcon = this.querySelector('.eye-off-icon');
        
        if (isPassword) {
            eyeIcon?.classList.add('hidden');
            eyeOffIcon?.classList.remove('hidden');
        } else {
            eyeIcon?.classList.remove('hidden');
            eyeOffIcon?.classList.add('hidden');
        }
    });

    function generateStrongPassword() {
        const length = 12;
        const uppercase = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        const lowercase = "abcdefghijklmnopqrstuvwxyz";
        const numbers = "0123456789";
        const symbols = "!@#$%^&*()_+~`|}{[]:;?><,./-=";
        
        let password = '';
        password += uppercase[Math.floor(Math.random() * uppercase.length)];
        password += lowercase[Math.floor(Math.random() * lowercase.length)];
        password += numbers[Math.floor(Math.random() * numbers.length)];
        password += symbols[Math.floor(Math.random() * symbols.length)];
        
        const allChars = uppercase + lowercase + numbers + symbols;
        for (let i = password.length; i < length; i++) {
            password += allChars[Math.floor(Math.random() * allChars.length)];
        }
        
        return password.split('').sort(() => 0.5 - Math.random()).join('');
    }

    function validatePassword(val) {
        const meetsLength = val.length >= 8;
        const meetsMixed = /[a-z]/.test(val) && /[A-Z]/.test(val);
        const meetsNumbers = /[0-9]/.test(val);
        const meetsSymbols = /[^a-zA-Z0-9]/.test(val);

        updateRequirementUI(reqLength, meetsLength);
        updateRequirementUI(reqMixed, meetsMixed);
        updateRequirementUI(reqNumbers, meetsNumbers);
        updateRequirementUI(reqSymbols, meetsSymbols);

        return meetsLength && meetsMixed && meetsNumbers && meetsSymbols;
    }

    function updateRequirementUI(element, isMet) {
        if (!element) return;
        const circleIcon = element.querySelector('.circle-icon');
        const checkIcon = element.querySelector('.check-icon');

        if (isMet) {
            element.classList.remove('text-muted-foreground');
            element.classList.add('text-green-500');
            circleIcon?.classList.add('hidden');
            checkIcon?.classList.remove('hidden');
        } else {
            element.classList.remove('text-green-500');
            element.classList.add('text-muted-foreground');
            circleIcon?.classList.remove('hidden');
            checkIcon?.classList.add('hidden');
        }
    }

    function checkSubmitState() {
        const val = passwordInput ? passwordInput.value : '';
        const isValid = validatePassword(val);

        if (val && isValid && hasCopied) {
            submitBtn.removeAttribute('disabled');
            submitBtn.classList.remove('opacity-50', 'pointer-events-none');
        } else {
            submitBtn.setAttribute('disabled', 'true');
            submitBtn.classList.add('opacity-50', 'pointer-events-none');
        }
    }

    function resetCopyButton() {
        const copyIcon = copyBtn?.querySelector('.copy-btn-icon');
        const checkIcon = copyBtn?.querySelector('.check-btn-icon');
        const textSpan = copyBtn?.querySelector('.copy-btn-text');

        copyIcon?.classList.remove('hidden');
        checkIcon?.classList.add('hidden');
        if (textSpan) textSpan.textContent = '{{ __("Copy") }}';
    }

    passwordInput?.addEventListener('input', function() {
        const val = this.value;
        hasCopied = false; // Reset copy flag when password changes

        if (val) {
            copyContainer.classList.remove('hidden');
            passwordDisplay.textContent = val;
        } else {
            copyContainer.classList.add('hidden');
        }

        resetCopyButton();
        checkSubmitState();
    });

    generateBtn?.addEventListener('click', function() {
        const newPassword = generateStrongPassword();
        passwordInput.value = newPassword;
        hasCopied = false;

        copyContainer.classList.remove('hidden');
        passwordDisplay.textContent = newPassword;

        resetCopyButton();
        checkSubmitState();
    });

    copyBtn?.addEventListener('click', function() {
        const val = passwordInput.value;
        if (!val) return;

        navigator.clipboard.writeText(val).then(() => {
            hasCopied = true;
            
            const copyIcon = this.querySelector('.copy-btn-icon');
            const checkIcon = this.querySelector('.check-btn-icon');
            const textSpan = this.querySelector('.copy-btn-text');

            copyIcon?.classList.add('hidden');
            checkIcon?.classList.remove('hidden');
            if (textSpan) textSpan.textContent = '{{ __("Copied!") }}';

            checkSubmitState();
        }).catch(err => {
            console.error('Failed to copy: ', err);
        });
    });

    // Initial check
    checkSubmitState();

    document.addEventListener('DOMContentLoaded', () => {
        lucide.createIcons();
    });
</script>
@endsection
