@extends('layouts.admin')

@section('content')
<div class="w-full space-y-4 animate-in fade-in duration-500 pb-8">
    @if(session('success'))
        <div class="p-3 bg-emerald-500/10 border border-emerald-500/20 text-emerald-600 dark:text-emerald-400 text-xs font-mono rounded-sm">
            [OK] {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="p-3 bg-destructive/10 border border-destructive/20 text-destructive text-xs font-mono rounded-sm">
            [ERROR] {{ session('error') }}
        </div>
    @endif

    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
        <div>
            <h1 class="text-lg font-bold text-foreground tracking-tight">{{ __('Circulation Policies') }}</h1>
            <p class="text-xs text-muted-foreground mt-0.5">{{ __('Manage borrow, return, reading room and holds policies') }}</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.circulation.policies.create') }}" class="btn-compact-primary">
                <i data-lucide="plus" class="w-4 h-4 mr-1"></i>
                <span>{{ __('Add Policy') }}</span>
            </a>
        </div>
    </div>

    <!-- Policies Table -->
    <div class="bg-card text-foreground rounded-md border border-border shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-muted/50 border-b border-border text-muted-foreground uppercase font-bold text-[10px] tracking-wider">
                    <tr>
                        <th class="py-2 px-3">{{ __('Policy Name') }}</th>
                        <th class="py-2 px-3 w-40">{{ __('Patron Group') }}</th>
                        <th class="py-2 px-3 w-28 text-center">{{ __('Borrow') }}</th>
                        <th class="py-2 px-3 w-28 text-center">{{ __('Reading Room') }}</th>
                        <th class="py-2 px-3 w-28 text-center">{{ __('Holds') }}</th>
                        <th class="py-2 px-3 w-28 text-center">{{ __('Status') }}</th>
                        <th class="py-2 px-3 w-44 text-right">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @forelse($policies as $policy)
                    <tr class="table-row-hover group">
                        <td class="py-2 px-3">
                            <div class="font-bold text-xs">{{ $policy->name }}</div>
                            @if($policy->notes)
                                <div class="text-[10px] text-muted-foreground truncate max-w-xs mt-0.5">{{ Str::limit($policy->notes, 50) }}</div>
                            @endif
                            @if(str_contains($policy->notes ?? '', '[ĐÁNH DẤU XÓA:'))
                                <div class="inline-flex items-center gap-1 text-[10px] text-destructive font-semibold mt-1">
                                    <i data-lucide="alert-triangle" class="w-3.5 h-3.5"></i>
                                    <span>{{ __('Marked for deletion') }}</span>
                                </div>
                            @endif
                        </td>
                        <td class="py-2 px-3">
                            <span class="px-1.5 py-0.5 bg-primary/10 text-primary text-xs font-semibold rounded-sm border border-primary/20">
                                {{ $policy->patronGroup->name ?? 'N/A' }}
                            </span>
                        </td>
                        <td class="py-2 px-3 text-center">
                            <div class="text-[11px] font-medium leading-tight">
                                <div>{{ $policy->max_items }} {{ __('books') }}</div>
                                <div class="text-muted-foreground mt-0.5">{{ $policy->max_loan_days }} {{ __('days') }}</div>
                            </div>
                        </td>
                        <td class="py-2 px-3 text-center">
                            @if($policy->can_use_reading_room)
                                <div class="text-[11px] font-medium leading-tight">
                                    <span class="text-emerald-600 dark:text-emerald-400 font-bold">{{ __('Yes') }}</span>
                                    <div class="text-muted-foreground mt-0.5">{{ $policy->max_reading_room_items }} {{ __('documents') }}</div>
                                </div>
                            @else
                                <span class="text-destructive font-semibold text-xs">{{ __('No') }}</span>
                            @endif
                        </td>
                        <td class="py-2 px-3 text-center">
                            @if($policy->can_place_hold)
                                <div class="text-[11px] font-medium leading-tight">
                                    <span class="text-emerald-600 dark:text-emerald-400 font-bold">{{ __('Yes') }}</span>
                                    <div class="text-muted-foreground mt-0.5">{{ $policy->max_holds }} {{ __('giữ lại') }}</div>
                                </div>
                            @else
                                <span class="text-destructive font-semibold text-xs">{{ __('No') }}</span>
                            @endif
                        </td>
                        <td class="py-2 px-3 text-center">
                            @if($policy->is_active)
                                <span class="px-1.5 py-0.5 bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 text-[10px] font-bold rounded-sm border border-emerald-500/20">
                                    {{ __('Active') }}
                                </span>
                            @else
                                <span class="px-1.5 py-0.5 bg-muted text-muted-foreground text-[10px] font-bold rounded-sm border border-border">
                                    {{ __('Disabled') }}
                                </span>
                            @endif
                        </td>
                        <td class="py-2 px-3 text-right">
                            <div class="flex justify-end items-center gap-1">
                                <a href="{{ route('admin.circulation.policies.show', $policy) }}" 
                                   class="btn-icon-compact text-primary" 
                                   title="{{ __('View') }}">
                                    <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                                </a>
                                <a href="{{ route('admin.circulation.policies.edit', $policy) }}" 
                                   class="btn-icon-compact text-amber-500" 
                                   title="{{ __('Edit') }}">
                                    <i data-lucide="edit-2" class="w-3.5 h-3.5"></i>
                                </a>
                                <form action="{{ route('admin.circulation.policies.toggle', $policy) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" 
                                            class="btn-icon-compact text-purple-500" 
                                            title="{{ $policy->is_active ? __('Disable') : __('Active') }}">
                                        <i data-lucide="{{ $policy->is_active ? 'pause' : 'play' }}" class="w-3.5 h-3.5"></i>
                                    </button>
                                </form>
                                <form action="{{ route('admin.circulation.policies.duplicate', $policy) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" 
                                            class="btn-icon-compact text-emerald-500" 
                                            title="{{ __('Duplicate') }}">
                                        <i data-lucide="copy" class="w-3.5 h-3.5"></i>
                                    </button>
                                </form>
                                @if(str_contains($policy->notes ?? '', '[ĐÁNH DẤU XÓA:'))
                                    <!-- Force delete for marked policies -->
                                    <form action="{{ route('admin.circulation.policies.force-delete', $policy) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" 
                                                class="btn-icon-danger submit-delete-btn" 
                                                data-message="{{ __('WARNING: Force deleting this policy will permanently delete all related data! Are you sure?') }}"
                                                title="{{ __('Force Delete') }}">
                                            <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                        </button>
                                    </form>
                                @else
                                    <!-- Regular delete for policies -->
                                    <form action="{{ route('admin.circulation.policies.destroy', $policy) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" 
                                                class="btn-icon-danger submit-delete-btn" 
                                                data-message="{{ __('Are you sure you want to delete this policy?') }}"
                                                title="{{ __('Delete') }}">
                                            <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-8 text-center text-muted-foreground italic text-xs">
                            <i data-lucide="file-x" class="w-8 h-8 text-muted-foreground mx-auto mb-2 opacity-50"></i>
                            <p>{{ __('No policies found.') }}</p>
                            <a href="{{ route('admin.circulation.policies.create') }}" class="text-primary hover:underline mt-2 inline-block font-bold">
                                {{ __('Add a new policy') }}
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($policies->hasPages())
        <div class="p-3 border-t border-border bg-muted/10">
            {{ $policies->links() }}
        </div>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.submit-delete-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const form = this.closest('form');
            const message = this.getAttribute('data-message');
            
            Swal.fire({
                title: '{{ __("Confirm Delete") }}',
                text: message,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: 'hsl(var(--destructive))',
                cancelButtonColor: 'hsl(var(--muted))',
                confirmButtonText: '{{ __("Delete") }}',
                cancelButtonText: '{{ __("Cancel") }}',
                customClass: {
                    popup: 'bg-card text-foreground border border-border rounded-md p-4',
                    title: 'text-foreground font-bold text-sm',
                    htmlContainer: 'text-muted-foreground text-xs mt-2',
                    confirmButton: 'px-4 py-2 bg-destructive text-destructive-foreground hover:bg-destructive/90 rounded-sm text-xs font-bold uppercase tracking-wider mx-1',
                    cancelButton: 'px-4 py-2 bg-muted text-foreground hover:bg-muted/80 rounded-sm text-xs font-bold uppercase tracking-wider border border-border mx-1'
                },
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
});
</script>
@endsection
