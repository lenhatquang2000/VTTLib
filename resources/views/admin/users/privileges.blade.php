@extends('layouts.admin')

@section('content')
<div class="space-y-4 animate-in fade-in duration-500">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <h1 class="text-xl font-bold text-foreground tracking-tight">{{ __('Privilege Controller') }}</h1>
            <p class="text-sm text-muted-foreground">{{ __('Assign and manage security clearances for system subjects.') }}</p>
        </div>
    </div>

    <!-- Administrative Navigation Tabs -->
    <div class="flex items-center gap-1 p-1 bg-muted/50 rounded-md w-fit border border-border">
        <a href="{{ route('admin.users.index') }}" class="px-4 py-1.5 rounded-sm text-xs font-semibold transition-all {{ Route::is('admin.users.index') ? 'bg-card text-primary shadow-sm' : 'text-muted-foreground hover:text-foreground' }}">
            {{ __('Users List') }}
        </a>
        <a href="{{ route('admin.users.privileges') }}" class="px-4 py-1.5 rounded-sm text-xs font-semibold transition-all {{ Route::is('admin.users.privileges') ? 'bg-card text-primary shadow-sm' : 'text-muted-foreground hover:text-foreground' }}">
            {{ __('Privilege Controller') }}
        </a>
        <a href="{{ route('admin.roles.index') }}" class="px-4 py-1.5 rounded-sm text-xs font-semibold transition-all {{ Route::is('admin.roles.index') ? 'bg-card text-primary shadow-sm' : 'text-muted-foreground hover:text-foreground' }}">
            {{ __('Role Management') }}
        </a>
    </div>

    <div class="bg-card rounded-md shadow-sm border border-border overflow-hidden">
        <!-- Filter Bar -->
        <div class="p-3 bg-muted/30 border-b border-border">
            <form action="{{ route('admin.users.privileges') }}" method="GET" class="flex flex-col sm:flex-row gap-2">
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
                        <a href="{{ route('admin.users.privileges') }}" 
                            class="btn-compact-secondary h-9 px-4">
                            <i data-lucide="x" class="w-4 h-4 mr-1"></i>
                            {{ __('Clear') }}
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Table View -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-muted/50 text-xs font-semibold uppercase tracking-wider text-muted-foreground border-b border-border">
                    <tr>
                        <th class="py-2 px-3">{{ __('Identity') }}</th>
                        <th class="py-2 px-3">{{ __('Username') }}</th>
                        <th class="py-2 px-3">{{ __('Clearance Level') }}</th>
                        <th class="py-2 px-3">{{ __('Status / Permissions') }}</th>
                        <th class="py-2 px-3 w-32 text-right">{{ __('Operations') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @forelse($roleUsers as $ru)
                        <tr class="table-row-hover group">
                            <td class="py-2 px-3">
                                <div class="flex items-center gap-3">
                                    <div class="h-8 w-8 rounded-full bg-primary/10 border border-primary/20 flex items-center justify-center text-primary font-bold text-xs uppercase shrink-0">
                                        {{ substr($ru->user->name, 0, 1) }}
                                    </div>
                                    <div class="min-w-0">
                                        <div class="text-sm font-semibold text-foreground leading-tight truncate">{{ $ru->user->name }}</div>
                                        <div class="text-[11px] text-muted-foreground leading-tight truncate">{{ $ru->user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-2 px-3 font-mono text-xs text-muted-foreground">
                                @ {{ $ru->user->username ?? '---' }}
                            </td>
                            <td class="py-2 px-3">
                                @php
                                    $roleClass = match($ru->role->name) {
                                        'root' => 'bg-destructive/10 text-destructive border-destructive/20',
                                        'admin' => 'bg-amber-500/10 text-amber-600 dark:text-amber-400 border-amber-500/20',
                                        'visitor' => 'bg-green-500/10 text-green-600 dark:text-green-400 border-green-500/20',
                                        default => 'bg-primary/10 text-primary border-primary/20'
                                    };
                                @endphp
                                <span class="inline-flex items-center px-2 py-0.5 rounded-sm text-[10px] font-bold border {{ $roleClass }}">
                                    {{ $ru->role->display_name }}
                                </span>
                            </td>
                            <td class="py-2 px-3">
                                <button
                                    onclick="openSidebarSettings('{{ $ru->id }}', '{{ $ru->user->name }}', '{{ $ru->role->name }}', {{ $ru->sidebars->pluck('sidebar_id') }})"
                                    class="btn-compact-primary text-[10px] px-3 py-1.5">
                                    <i data-lucide="settings-2" class="w-3.5 h-3.5 mr-1"></i>
                                    {{ __('Modify Tabs') }} <span class="ml-1 px-1.5 py-0.5 bg-background rounded-sm text-[9px]">{{ $ru->sidebars->count() }}</span>
                                </button>
                            </td>
                            <td class="py-2 px-3 text-right">
                                <div class="flex justify-end items-center gap-1.5">
                                    <a href="{{ route('admin.users.edit', $ru->user_id) }}" class="btn-icon-compact" title="{{ __('Edit') }}">
                                        <i data-lucide="edit-2" class="w-4 h-4"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center">
                                <div class="flex flex-col items-center max-w-sm mx-auto">
                                    <div class="w-12 h-12 bg-muted rounded-full flex items-center justify-center mb-4 border border-border">
                                        <i data-lucide="search-x" class="w-6 h-6 text-muted-foreground"></i>
                                    </div>
                                    <h4 class="text-base font-bold text-foreground">{{ __('No Privileged Identities Found') }}</h4>
                                    <p class="text-muted-foreground text-sm mt-1">{{ __('Try adjusting your filters.') }}</p>
                                    <a href="{{ route('admin.users.privileges') }}" class="btn-compact-primary mt-4">
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
                {{ __('Displaying') }} {{ $roleUsers->firstItem() ?? 0 }} - {{ $roleUsers->lastItem() ?? 0 }} {{ __('of') }} {{ $roleUsers->total() }}
            </div>
            <div>
                {{ $roleUsers->links() }}
            </div>
        </div>
    </div>
</div>

<!-- SIDEBAR MODAL -->
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
        document.getElementById('modal-subject-name').innerText = name;
        document.getElementById('modal-role-name').innerText = (role || 'N/A').toUpperCase();
        const form = document.getElementById('sidebarTabsForm');
        form.action = `{{ route('admin.users.tabs', ['id' => ':id']) }}`.replace(':id', roleUserId);
        const checkboxes = document.querySelectorAll('.sidebar-checkbox');
        checkboxes.forEach(cb => { cb.checked = assignedIds.includes(parseInt(cb.value)); });
        openModal('sidebarModal');
    }

    document.addEventListener('DOMContentLoaded', () => {
        lucide.createIcons();
    });
</script>
@endsection