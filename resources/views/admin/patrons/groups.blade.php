@extends('layouts.admin')

@section('content')
<div class="w-full space-y-4 animate-in fade-in duration-500">
    <!-- Header Section -->
    <div class="flex items-center gap-3">
        <div class="w-9 h-9 rounded-sm bg-primary/10 border border-primary/20 flex items-center justify-center text-primary">
            <i data-lucide="users" class="w-5 h-5"></i>
        </div>
        <div>
            <h1 class="text-lg font-bold text-foreground tracking-tight">{{ __('Patron Categories') }}</h1>
            <p class="text-xs text-muted-foreground">{{ __('Manage library patron groups and categories') }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-3" x-data="{ showModal: false, editingGroup: null }">
        <!-- New Category Form -->
        <div class="lg:col-span-1">
            <div class="bg-card text-foreground rounded-md border border-border shadow-sm overflow-hidden sticky top-20">
                <div class="p-3 border-b border-border bg-muted/30">
                    <h2 class="text-xs font-bold uppercase tracking-wider text-foreground">{{ __('Add New Category') }}</h2>
                </div>
                <div class="p-3">
                    <form action="{{ route('admin.patrons.groups.store') }}" method="POST" class="space-y-3">
                        @csrf
                        <div class="space-y-1">
                            <label class="block text-[10px] text-muted-foreground uppercase font-bold tracking-wider">{{ __('Category Name') }}</label>
                            <input type="text" name="name" required class="w-full h-9 px-3 py-1.5 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all" placeholder="{{ __('e.g. Student, Faculty') }}">
                        </div>
                        <div class="space-y-1">
                            <label class="block text-[10px] text-muted-foreground uppercase font-bold tracking-wider">{{ __('Code') }}</label>
                            <input type="text" name="code" required class="w-full h-9 px-3 py-1.5 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary font-mono uppercase transition-all" placeholder="{{ __('e.g. STU, FAC') }}">
                        </div>
                        <div class="space-y-1">
                            <label class="block text-[10px] text-muted-foreground uppercase font-bold tracking-wider">{{ __('Display Order') }}</label>
                            <input type="number" name="order" value="0" class="w-full h-9 px-3 py-1.5 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all">
                        </div>
                        <div class="space-y-1">
                            <label class="block text-[10px] text-muted-foreground uppercase font-bold tracking-wider">{{ __('Description') }}</label>
                            <textarea name="description" rows="3" class="w-full px-3 py-1.5 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all"></textarea>
                        </div>
                        <button type="submit" class="btn-compact-primary w-full h-9 justify-center">
                            <i data-lucide="plus" class="w-4 h-4 mr-1"></i>
                            <span>{{ __('Save Category') }}</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Categories List -->
        <div class="lg:col-span-2">
            <div class="bg-card text-foreground rounded-md border border-border shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-muted/50 border-b border-border text-muted-foreground uppercase font-bold text-[10px] tracking-wider">
                            <tr>
                                <th class="w-10 py-2 px-3"></th>
                                <th class="py-2 px-3 w-28">{{ __('Code') }}</th>
                                <th class="py-2 px-3">{{ __('Name') }}</th>
                                <th class="py-2 px-3 w-20">{{ __('Order') }}</th>
                                <th class="py-2 px-3 w-24">{{ __('Status') }}</th>
                                <th class="py-2 px-3 w-24 text-right">{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-border" id="sortable-groups">
                            @forelse($groups as $group)
                            <tr class="table-row-hover group cursor-move" data-id="{{ $group->id }}">
                                <td class="py-2 px-3 text-muted-foreground drag-handle">
                                    <i data-lucide="grip-vertical" class="w-4 h-4"></i>
                                </td>
                                <td class="py-2 px-3 whitespace-nowrap">
                                    <span class="px-1.5 py-0.5 bg-primary/10 text-primary text-xs font-mono font-bold rounded-sm border border-primary/20">{{ $group->code }}</span>
                                </td>
                                <td class="py-2 px-3">
                                    <div class="text-sm font-bold text-foreground leading-tight">{{ $group->name }}</div>
                                    @if($group->description)
                                        <div class="text-xs text-muted-foreground truncate max-w-xs mt-0.5">{{ $group->description }}</div>
                                    @endif
                                </td>
                                <td class="py-2 px-3 text-xs text-muted-foreground group-order">{{ $group->order }}</td>
                                <td class="py-2 px-3">
                                    @if($group->is_active)
                                        <span class="inline-flex items-center px-1.5 py-0.5 bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 text-[9px] uppercase font-bold rounded-sm border border-emerald-500/20">
                                            {{ __('Active') }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-1.5 py-0.5 bg-destructive/10 text-destructive text-[9px] uppercase font-bold rounded-sm border border-destructive/20">
                                            {{ __('Inactive') }}
                                        </span>
                                    @endif
                                </td>
                                <td class="py-2 px-3 text-right">
                                    <div class="flex justify-end items-center gap-1.5">
                                        <button @click="editingGroup = @js($group); showModal = true" class="btn-icon-compact" title="{{ __('Edit') }}">
                                            <i data-lucide="edit-2" class="w-3.5 h-3.5"></i>
                                        </button>
                                        <form action="{{ route('admin.patrons.groups.destroy', $group) }}" method="POST" class="inline" onsubmit="return confirm('{{ __('Are you sure you want to delete this category?') }}')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn-icon-danger" title="{{ __('Del') }}">
                                                <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="py-8 text-center text-muted-foreground italic text-xs">
                                    {{ __('No categories found.') }}
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Edit Modal -->
        <div id="editCategoryModal" x-show="showModal" class="fixed inset-0 z-50 overflow-y-auto" x-cloak>
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="showModal" @click="showModal = false" class="fixed inset-0 transition-opacity bg-slate-950/60 backdrop-blur-sm"></div>

                <div x-show="showModal" class="inline-block align-bottom bg-card text-foreground rounded-md border border-border text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full">
                    <form :action="'{{ url('topsecret/patron-groups') }}/' + editingGroup?.id" method="POST">
                        @csrf @method('PUT')
                        <div class="px-4 py-3 bg-muted/30 border-b border-border flex justify-between items-center">
                            <h3 class="text-sm font-bold text-foreground uppercase tracking-wider">{{ __('Edit Category') }}</h3>
                            <button type="button" @click="showModal = false" class="text-muted-foreground hover:text-foreground">
                                <i data-lucide="x" class="w-5 h-5"></i>
                            </button>
                        </div>
                        <div class="p-4 space-y-3">
                            <div class="space-y-1">
                                <label class="block text-[10px] text-muted-foreground uppercase font-bold tracking-wider">{{ __('Category Name') }}</label>
                                <input type="text" name="name" x-model="editingGroup.name" required class="w-full h-9 px-3 py-1.5 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all">
                            </div>
                            <div class="space-y-1">
                                <label class="block text-[10px] text-muted-foreground uppercase font-bold tracking-wider">{{ __('Code') }}</label>
                                <input type="text" name="code" x-model="editingGroup.code" required class="w-full h-9 px-3 py-1.5 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary font-mono transition-all">
                            </div>
                            <div class="space-y-1">
                                <label class="block text-[10px] text-muted-foreground uppercase font-bold tracking-wider">{{ __('Display Order') }}</label>
                                <input type="number" name="order" x-model="editingGroup.order" class="w-full h-9 px-3 py-1.5 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all">
                            </div>
                            <label class="flex items-center gap-2 cursor-pointer group mt-1">
                                <input type="checkbox" name="is_active" id="is_active_edit" value="1" x-model="editingGroup.is_active" class="w-4 h-4 rounded-sm border-input bg-background text-primary focus:ring-primary">
                                <span class="text-xs text-muted-foreground font-semibold group-hover:text-primary cursor-pointer">{{ __('Active') }}</span>
                            </label>
                            <div class="space-y-1">
                                <label class="block text-[10px] text-muted-foreground uppercase font-bold tracking-wider">{{ __('Description') }}</label>
                                <textarea name="description" x-model="editingGroup.description" rows="3" class="w-full px-3 py-1.5 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all"></textarea>
                            </div>
                        </div>
                        <div class="px-4 py-3 border-t border-border flex justify-end gap-2 bg-muted/10">
                            <button type="button" @click="showModal = false" class="btn-compact-secondary">{{ __('Cancel') }}</button>
                            <button type="submit" class="btn-compact-primary">{{ __('Update Category') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const el = document.getElementById('sortable-groups');
    if (el) {
        Sortable.create(el, {
            animation: 150,
            handle: '.drag-handle',
            ghostClass: 'bg-primary/5',
            onEnd: function() {
                const ids = Array.from(el.querySelectorAll('tr')).map(tr => tr.dataset.id);
                
                // Update display orders in the UI immediately
                el.querySelectorAll('tr').forEach((tr, index) => {
                    tr.querySelector('.group-order').innerText = index + 1;
                });

                // Send to server
                fetch('{{ route("admin.patrons.groups.reorder") }}', {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ ids: ids })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.dispatchEvent(new CustomEvent('toast', {
                            detail: { message: '{{ __("Order updated successfully") }}', type: 'success' }
                        }));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    window.dispatchEvent(new CustomEvent('toast', {
                        detail: { message: '{{ __("Failed to update order") }}', type: 'error' }
                    }));
                });
            }
        });
    }
});
</script>
@endpush
