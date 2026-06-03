@extends('layouts.admin')

@section('content')
<div class="space-y-4 animate-in fade-in duration-500">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <h1 class="text-xl font-bold text-foreground tracking-tight">{{ __('Role Management') }}</h1>
            <p class="text-sm text-muted-foreground">{{ __('Define and manage system access levels.') }}</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.users.index') }}" class="btn-compact-secondary">
                <i data-lucide="users" class="w-4 h-4 mr-1"></i>
                {{ __('Manage Subjects') }}
            </a>
            <a href="{{ route('admin.roles.create') }}" class="btn-compact-primary">
                <i data-lucide="plus" class="w-4 h-4"></i>
                {{ __('New Role') }}
            </a>
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
        <!-- Table View -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-muted/50 text-xs font-semibold uppercase tracking-wider text-muted-foreground border-b border-border">
                    <tr>
                        <th class="py-2 px-3 w-12">{{ __('ID') }}</th>
                        <th class="py-2 px-3">{{ __('Role Identity') }}</th>
                        <th class="py-2 px-3">{{ __('Assigned Subjects') }}</th>
                        <th class="py-2 px-3">{{ __('Default Tabs') }}</th>
                        <th class="py-2 px-3 w-32 text-right">{{ __('Operations') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @foreach($roles as $role)
                        <tr class="table-row-hover group">
                            <td class="py-2 px-3 text-center text-muted-foreground font-medium text-xs">
                                #{{ str_pad($role->id, 3, '0', STR_PAD_LEFT) }}
                            </td>
                            <td class="py-2 px-3">
                                <div class="text-sm font-semibold text-foreground leading-tight">{{ $role->display_name }}</div>
                                <div class="text-[11px] text-muted-foreground font-mono mt-0.5">{{ $role->name }}</div>
                            </td>
                            <td class="py-2 px-3">
                                <div class="flex items-center gap-2">
                                    <span class="inline-flex items-center px-2 py-0.5 bg-primary/10 text-primary text-[10px] font-bold rounded-sm border border-primary/20">
                                        {{ $role->users_count }}
                                    </span>
                                    <span class="text-[10px] text-muted-foreground font-medium">subjects</span>
                                </div>
                            </td>
                            <td class="py-2 px-3">
                                <div class="flex flex-wrap gap-1">
                                    @foreach($role->sidebars->take(5) as $sidebar)
                                        <span class="px-2 py-0.5 bg-muted text-muted-foreground text-[10px] font-medium rounded-sm border border-border">
                                            {{ __($sidebar->name) }}
                                        </span>
                                    @endforeach
                                    @if($role->sidebars->count() > 5)
                                        <span class="text-[10px] text-muted-foreground font-medium self-center">+{{ $role->sidebars->count() - 5 }}</span>
                                    @endif
                                    @if($role->sidebars->count() == 0)
                                        <span class="text-[10px] text-muted-foreground italic font-medium">No default tabs</span>
                                    @endif
                                </div>
                            </td>
                            <td class="py-2 px-3 text-right">
                                <div class="flex justify-end items-center gap-1.5">
                                    <a href="{{ route('admin.roles.edit', $role->id) }}" class="btn-icon-compact" title="{{ __('Edit') }}">
                                        <i data-lucide="edit-2" class="w-4 h-4"></i>
                                    </a>
                                    <form action="{{ route('admin.roles.destroy', $role->id) }}" method="POST" onsubmit="return confirm('{{ __('Delete_Confirmation') }}')" class="inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn-icon-danger" title="{{ __('Delete') }}">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        lucide.createIcons();
    });
</script>
@endsection
