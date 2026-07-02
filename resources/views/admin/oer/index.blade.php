@extends('layouts.admin')

@section('content')
<div class="space-y-4 animate-in fade-in duration-500">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <h1 class="text-xl font-bold text-foreground tracking-tight">{{ __('OER Management') }}</h1>
            <p class="text-sm text-muted-foreground">{{ __('Manage open educational resources.') }}</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.oer.contributions') }}" class="inline-flex items-center justify-center gap-2 px-3 py-1.5 rounded text-xs font-medium transition-all duration-200 active:scale-95 disabled:opacity-50 disabled:pointer-events-none bg-muted text-muted-foreground hover:bg-muted/80 border border-border hover:border-border/80 hover:text-foreground active:bg-muted/60" title="{{ __('Review Contributions') }}">
                <i data-lucide="check-circle" class="w-4 h-4"></i>
                <span class="hidden sm:inline">{{ __('Review Contributions') }}</span>
                <span class="sm:hidden">{{ __('Review') }}</span>
            </a>
            <a href="{{ route('admin.oer.create') }}" class="inline-flex items-center justify-center gap-2 px-3 py-1.5 rounded text-xs font-medium transition-all duration-200 active:scale-95 disabled:opacity-50 disabled:pointer-events-none bg-primary text-primary-foreground hover:bg-primary/90 shadow-sm active:bg-primary/80" title="{{ __('New Resource') }}">
                <i data-lucide="plus" class="w-4 h-4"></i>
                <span class="hidden sm:inline">{{ __('New Resource') }}</span>
                <span class="sm:hidden">{{ __('New') }}</span>
            </a>
        </div>
    </div>

    <!-- Administrative Navigation Tabs -->
    <div class="flex items-center gap-1 p-1 bg-muted/50 rounded-md w-fit border border-border">
        <a href="{{ route('admin.oer.index') }}" class="px-4 py-1.5 rounded-sm text-xs font-semibold transition-all bg-card text-primary shadow-sm">
            {{ __('Resources') }}
        </a>
        <a href="{{ route('admin.oer.contributions') }}" class="px-4 py-1.5 rounded-sm text-xs font-semibold transition-all text-muted-foreground hover:text-foreground">
            {{ __('Contributions') }}
        </a>
    </div>

    <div class="bg-card rounded-md shadow-sm border border-border overflow-hidden">
        <!-- Filter Bar -->
        <div class="p-3 bg-muted/30 border-b border-border">
            <form action="{{ route('admin.oer.index') }}" method="GET" class="flex flex-col sm:flex-row gap-2">
                <!-- Search Input -->
                <div class="relative flex-1 sm:max-w-xs">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none">
                        <i data-lucide="search" class="w-4 h-4 text-muted-foreground"></i>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}" 
                        placeholder="{{ __('Search resources...') }}" 
                        class="block w-full pl-9 pr-3 py-1.5 h-9 text-sm border border-input rounded-sm bg-background text-foreground placeholder-muted-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all">
                </div>
                
                <!-- Type Filter -->
                <select name="type" class="h-9 w-full sm:w-40 px-3 py-1.5 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all">
                    <option value="">{{ __('All Types') }}</option>
                    <option value="document" {{ request('type') == 'document' ? 'selected' : '' }}>{{ __('Document') }}</option>
                    <option value="video" {{ request('type') == 'video' ? 'selected' : '' }}>{{ __('Video') }}</option>
                    <option value="audio" {{ request('type') == 'audio' ? 'selected' : '' }}>{{ __('Audio') }}</option>
                    <option value="interactive" {{ request('type') == 'interactive' ? 'selected' : '' }}>{{ __('Interactive') }}</option>
                </select>

                <!-- Action Buttons -->
                <div class="flex gap-2">
                    <button type="submit" class="inline-flex items-center justify-center gap-2 px-3 h-9 rounded text-xs font-medium transition-all duration-200 active:scale-95 disabled:opacity-50 disabled:pointer-events-none bg-primary text-primary-foreground hover:bg-primary/90 shadow-sm active:bg-primary/80">
                        {{ __('Search') }}
                    </button>

                    @if(request('search') || request('type'))
                        <a href="{{ route('admin.oer.index') }}" 
                            class="inline-flex items-center justify-center gap-2 px-3 h-9 rounded text-xs font-medium transition-all duration-200 active:scale-95 disabled:opacity-50 disabled:pointer-events-none bg-muted text-muted-foreground hover:bg-muted/80 border border-border hover:text-foreground active:bg-muted/60">
                            <i data-lucide="x" class="w-4 h-4"></i>
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
                        <th class="py-2 px-3">{{ __('Title') }}</th>
                        <th class="py-2 px-3">{{ __('Type') }}</th>
                        <th class="py-2 px-3">{{ __('Author') }}</th>
                        <th class="py-2 px-3">{{ __('License') }}</th>
                        <th class="py-2 px-3">{{ __('Status') }}</th>
                        <th class="py-2 px-3 w-32 text-right">{{ __('Operations') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @forelse($resources as $resource)
                        <tr class="table-row-hover group">
                            <td class="py-2 px-3">
                                <div class="flex items-center gap-3">
                                    <div class="h-10 w-8 rounded bg-primary/10 border border-primary/20 flex items-center justify-center shrink-0 overflow-hidden">
                                        @if($resource->cover_path)
                                            <img src="{{ $resource->thumbnail_url }}" alt="" class="h-full w-full object-cover">
                                        @else
                                            <i data-lucide="file-text" class="w-4 h-4 text-primary"></i>
                                        @endif
                                    </div>
                                    <div class="min-w-0">
                                        <div class="text-sm font-semibold text-foreground leading-tight truncate">{{ $resource->title }}</div>
                                        <div class="text-[11px] text-muted-foreground leading-tight truncate">{{ $resource->publisher ?? 'N/A' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-2 px-3">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-sm text-[10px] font-bold border bg-primary/10 text-primary border-primary/20">
                                    {{ $resource->resource_type }}
                                </span>
                            </td>
                            <td class="py-2 px-3">
                                <div class="text-sm text-muted-foreground truncate max-w-[150px]">
                                    {{ $resource->author }}
                                </div>
                            </td>
                            <td class="py-2 px-3">
                                <div class="text-[11px] text-muted-foreground truncate max-w-[100px]">
                                    {{ $resource->license ?? 'N/A' }}
                                </div>
                            </td>
                            <td class="py-2 px-3">
                                @php
                                    $statusClass = match($resource->status) {
                                        'published' => 'bg-green-500/10 text-green-600 dark:text-green-400 border-green-500/20',
                                        'draft' => 'bg-muted text-muted-foreground border-border',
                                        default => 'bg-primary/10 text-primary border-primary/20'
                                    };
                                    $statusLabel = match($resource->status) {
                                        'published' => __('Published'),
                                        'draft' => __('Draft'),
                                        default => $resource->status
                                    };
                                @endphp
                                <span class="inline-flex items-center px-2 py-0.5 rounded-sm text-[10px] font-bold border {{ $statusClass }}">
                                    {{ $statusLabel }}
                                </span>
                            </td>
                            <td class="py-2 px-3 text-right">
                                <div class="flex justify-end items-center gap-1.5">
                                    <a href="{{ route('admin.oer.edit', $resource) }}" class="btn-icon-compact" title="{{ __('Edit') }}">
                                        <i data-lucide="edit-2" class="w-4 h-4"></i>
                                    </a>
                                    <form action="{{ route('admin.oer.destroy', $resource) }}" method="POST" onsubmit="return confirm('{{ __('Delete this resource?') }}')" class="inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn-icon-danger" title="{{ __('Delete') }}">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center">
                                <div class="flex flex-col items-center max-w-sm mx-auto">
                                    <div class="w-12 h-12 bg-muted rounded-full flex items-center justify-center mb-4 border border-border">
                                        <i data-lucide="file-x" class="w-6 h-6 text-muted-foreground"></i>
                                    </div>
                                    <h4 class="text-base font-bold text-foreground">{{ __('No Resources Found') }}</h4>
                                    <p class="text-muted-foreground text-sm mt-1">{{ __('Get started by creating your first OER resource.') }}</p>
                                    <a href="{{ route('admin.oer.create') }}" class="inline-flex items-center justify-center gap-2 px-3 py-1.5 rounded text-xs font-medium transition-all duration-200 active:scale-95 disabled:opacity-50 disabled:pointer-events-none bg-primary text-primary-foreground hover:bg-primary/90 shadow-sm active:bg-primary/80 mt-4">
                                        {{ __('Create Resource') }}
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
                {{ __('Displaying') }} {{ $resources->firstItem() ?? 0 }} - {{ $resources->lastItem() ?? 0 }} {{ __('of') }} {{ $resources->total() }}
            </div>
            <div>
                {{ $resources->links() }}
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        lucide.createIcons();
    });
</script>
@endsection
