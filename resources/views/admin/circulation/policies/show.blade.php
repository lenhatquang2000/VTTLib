@extends('layouts.admin')

@section('content')
<div class="w-full space-y-4 animate-in fade-in duration-500 pb-8">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <h1 class="text-lg font-bold text-foreground tracking-tight">{{ $policy->name }}</h1>
            <p class="text-xs text-muted-foreground mt-0.5">
                <span class="font-semibold">{{ __('Patron Group') }}:</span> {{ $policy->patronGroup->name ?? 'N/A' }}
                @if($policy->notes)
                <span class="mx-2 text-muted-foreground/30">|</span> <span class="font-semibold">{{ __('Notes') }}:</span> {{ $policy->notes }}
                @endif
            </p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.circulation.policies.edit', $policy) }}" class="btn-compact-secondary">
                <i data-lucide="edit-2" class="w-4 h-4 mr-1"></i>
                <span>{{ __('Edit') }}</span>
            </a>
            <a href="{{ route('admin.circulation.policies.index') }}" class="btn-compact-secondary">
                <i data-lucide="arrow-left" class="w-4 h-4 mr-1"></i>
                <span>{{ __('Back') }}</span>
            </a>
        </div>
    </div>

    <!-- Status Badge -->
    <div class="flex items-center gap-2">
        @if($policy->is_active)
            <span class="inline-flex items-center gap-1.5 px-2 py-1 bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 text-xs font-semibold rounded-sm border border-emerald-500/20">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                </span>
                {{ __('Active') }}
            </span>
        @else
            <span class="inline-flex items-center gap-1.5 px-2 py-1 bg-muted text-muted-foreground text-xs font-semibold rounded-sm border border-border">
                <span class="h-2 w-2 rounded-full bg-muted-foreground/50"></span>
                {{ __('Disabled') }}
            </span>
        @endif
    </div>

    <!-- Policy Summary -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-3">
        <!-- Loan Settings -->
        <div class="bg-card text-foreground rounded-md border border-border shadow-sm overflow-hidden">
            <div class="p-3 border-b border-border bg-muted/30 flex items-center gap-2">
                <i data-lucide="book-open" class="w-4 h-4 text-primary"></i>
                <h3 class="text-xs font-bold uppercase tracking-wider">{{ __('Loan Settings') }}</h3>
            </div>
            <div class="p-3 space-y-2">
                <div class="flex justify-between items-center py-1.5 border-b border-border/50 text-xs">
                    <span class="font-medium text-muted-foreground">{{ __('Max Loan Days') }}</span>
                    <span class="font-bold text-foreground">{{ $policySummary['loan']['max_days'] }} {{ __('days') }}</span>
                </div>
                <div class="flex justify-between items-center py-1.5 border-b border-border/50 text-xs">
                    <span class="font-medium text-muted-foreground">{{ __('Max Books') }}</span>
                    <span class="font-bold text-foreground">{{ $policySummary['loan']['max_items'] }} {{ __('books') }}</span>
                </div>
                <div class="flex justify-between items-center py-1.5 border-b border-border/50 text-xs">
                    <span class="font-medium text-muted-foreground">{{ __('Max Renewals') }}</span>
                    <span class="font-bold text-foreground">{{ $policySummary['loan']['max_renewals'] }} {{ __('times') }}</span>
                </div>
                <div class="flex justify-between items-center py-1.5 text-xs">
                    <span class="font-medium text-muted-foreground">{{ __('Renewal Days') }}</span>
                    <span class="font-bold text-foreground">{{ $policySummary['loan']['renewal_days'] }} {{ __('days') }}</span>
                </div>
            </div>
        </div>

        <!-- Fine Settings -->
        <div class="bg-card text-foreground rounded-md border border-border shadow-sm overflow-hidden">
            <div class="p-3 border-b border-border bg-muted/30 flex items-center gap-2">
                <i data-lucide="coins" class="w-4 h-4 text-amber-500"></i>
                <h3 class="text-xs font-bold uppercase tracking-wider">{{ __('Fine Settings') }}</h3>
            </div>
            <div class="p-3 space-y-2">
                <div class="flex justify-between items-center py-1.5 border-b border-border/50 text-xs">
                    <span class="font-medium text-muted-foreground">{{ __('Fine/Day') }}</span>
                    <span class="font-bold text-foreground">{{ number_format($policySummary['fines']['per_day']) }} VND</span>
                </div>
                <div class="flex justify-between items-center py-1.5 border-b border-border/50 text-xs">
                    <span class="font-medium text-muted-foreground">{{ __('Max Fine') }}</span>
                    <span class="font-bold text-foreground">{{ number_format($policySummary['fines']['max_fine']) }} VND</span>
                </div>
                <div class="flex justify-between items-center py-1.5 border-b border-border/50 text-xs">
                    <span class="font-medium text-muted-foreground">{{ __('Grace Days') }}</span>
                    <span class="font-bold text-foreground">{{ $policySummary['fines']['grace_period'] }} {{ __('days') }}</span>
                </div>
                <div class="flex justify-between items-center py-1.5 text-xs">
                    <span class="font-medium text-muted-foreground">{{ __('Max Outstanding Debt') }}</span>
                    <span class="font-bold text-foreground">{{ number_format($policySummary['fines']['max_outstanding']) }} VND</span>
                </div>
            </div>
        </div>

        <!-- Reading Room Settings -->
        <div class="bg-card text-foreground rounded-md border border-border shadow-sm overflow-hidden">
            <div class="p-3 border-b border-border bg-muted/30 flex items-center gap-2">
                <i data-lucide="armchair" class="w-4 h-4 text-purple-500"></i>
                <h3 class="text-xs font-bold uppercase tracking-wider">{{ __('Reading Room Settings') }}</h3>
            </div>
            <div class="p-3">
                @if($policySummary['reading_room']['allowed'])
                    <div class="space-y-2">
                        <div class="flex justify-between items-center py-1.5 border-b border-border/50 text-xs">
                            <span class="font-medium text-muted-foreground">{{ __('Status') }}</span>
                            <span class="px-1.5 py-0.5 bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 text-[10px] font-bold rounded-sm border border-emerald-500/20">
                                {{ __('Allowed') }}
                            </span>
                        </div>
                        <div class="flex justify-between items-center py-1.5 border-b border-border/50 text-xs">
                            <span class="font-medium text-muted-foreground">{{ __('Max Documents') }}</span>
                            <span class="font-bold text-foreground">{{ $policySummary['reading_room']['max_items'] }} {{ __('documents') }}</span>
                        </div>
                        <div class="flex justify-between items-center py-1.5 border-b border-border/50 text-xs">
                            <span class="font-medium text-muted-foreground">{{ __('Max Reading Room Hours') }}</span>
                            <span class="font-bold text-foreground">{{ $policySummary['reading_room']['max_hours'] }} {{ __('hours') }}</span>
                        </div>
                        <div class="flex justify-between items-center py-1.5 border-b border-border/50 text-xs">
                            <span class="font-medium text-muted-foreground">{{ __('Default Return Time') }}</span>
                            <span class="font-bold text-foreground">{{ $policySummary['reading_room']['due_time'] }}</span>
                        </div>
                        <div class="flex justify-between items-center py-1.5 text-xs">
                            <span class="font-medium text-muted-foreground">{{ __('Fine/Hour') }}</span>
                            <span class="font-bold text-foreground">{{ number_format($policySummary['reading_room']['fine_per_hour']) }} VND</span>
                        </div>
                    </div>
                @else
                    <div class="text-center py-6 text-muted-foreground italic text-xs">
                        <i data-lucide="x-circle" class="w-8 h-8 text-destructive mx-auto mb-2 opacity-55"></i>
                        <p>{{ __('Reading room loan is not allowed') }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Hold/Reserve Settings -->
        <div class="bg-card text-foreground rounded-md border border-border shadow-sm overflow-hidden">
            <div class="p-3 border-b border-border bg-muted/30 flex items-center gap-2">
                <i data-lucide="hand" class="w-4 h-4 text-emerald-500"></i>
                <h3 class="text-xs font-bold uppercase tracking-wider">{{ __('Hold Settings') }}</h3>
            </div>
            <div class="p-3">
                @if($policySummary['holds']['allowed'])
                    <div class="space-y-2">
                        <div class="flex justify-between items-center py-1.5 border-b border-border/50 text-xs">
                            <span class="font-medium text-muted-foreground">{{ __('Status') }}</span>
                            <span class="px-1.5 py-0.5 bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 text-[10px] font-bold rounded-sm border border-emerald-500/20">
                                {{ __('Allowed') }}
                            </span>
                        </div>
                        <div class="flex justify-between items-center py-1.5 border-b border-border/50 text-xs">
                            <span class="font-medium text-muted-foreground">{{ __('Max Holds') }}</span>
                            <span class="font-bold text-foreground">{{ $policySummary['holds']['max_holds'] }} {{ __('giữ lại') }}</span>
                        </div>
                        <div class="flex justify-between items-center py-1.5 border-b border-border/50 text-xs">
                            <span class="font-medium text-muted-foreground">{{ __('Expiry Days') }}</span>
                            <span class="font-bold text-foreground">{{ $policySummary['holds']['expiry_days'] }} {{ __('days') }}</span>
                        </div>
                        <div class="flex justify-between items-center py-1.5 border-b border-border/50 text-xs">
                            <span class="font-medium text-muted-foreground">{{ __('Notification Days') }}</span>
                            <span class="font-bold text-foreground">{{ $policySummary['holds']['notification_days'] }} {{ __('days') }}</span>
                        </div>
                        <div class="flex justify-between items-center py-1.5 border-b border-border/50 text-xs">
                            <span class="font-medium text-muted-foreground">{{ __('Cancellation Fee') }}</span>
                            <span class="font-bold text-foreground">{{ number_format($policySummary['holds']['cancellation_fee']) }} VND</span>
                        </div>
                        <div class="flex justify-between items-center py-1.5 text-xs">
                            <span class="font-medium text-muted-foreground">{{ __('Renewal') }}</span>
                            <span class="font-bold text-foreground">
                                @if($policySummary['holds']['can_renew'])
                                    {{ __('Allowed') }} ({{ $policySummary['holds']['max_renewals'] }} {{ __('times') }})
                                @else
                                    {{ __('Not Allowed') }}
                                @endif
                            </span>
                        </div>
                    </div>
                @else
                    <div class="text-center py-6 text-muted-foreground italic text-xs">
                        <i data-lucide="x-circle" class="w-8 h-8 text-destructive mx-auto mb-2 opacity-55"></i>
                        <p>{{ __('Hold request is not allowed') }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Actions -->
    <div class="flex justify-end items-center gap-2 mt-6 pt-4 border-t border-border/50">
        <form action="{{ route('admin.circulation.policies.toggle', $policy) }}" method="POST" class="inline">
            @csrf
            <button type="submit" class="inline-flex items-center gap-1.5 bg-muted text-foreground hover:bg-muted/80 border border-border px-3 py-1.5 rounded-sm text-xs font-bold uppercase tracking-widest transition-all active:scale-95">
                <i data-lucide="{{ $policy->is_active ? 'pause' : 'play' }}" class="w-4 h-4 text-muted-foreground"></i>
                <span>{{ $policy->is_active ? __('Disable') : __('Active') }}</span>
            </button>
        </form>
        
        <form action="{{ route('admin.circulation.policies.duplicate', $policy) }}" method="POST" class="inline">
            @csrf
            <button type="submit" class="inline-flex items-center gap-1.5 bg-muted text-foreground hover:bg-muted/80 border border-border px-3 py-1.5 rounded-sm text-xs font-bold uppercase tracking-widest transition-all active:scale-95">
                <i data-lucide="copy" class="w-4 h-4 text-muted-foreground"></i>
                <span>{{ __('Duplicate') }}</span>
            </button>
        </form>
        
        <a href="{{ route('admin.circulation.policies.edit', $policy) }}" class="inline-flex items-center gap-1.5 bg-primary text-primary-foreground hover:bg-primary/90 px-3 py-1.5 rounded-sm text-xs font-bold uppercase tracking-widest transition-all active:scale-95 shadow-sm">
            <i data-lucide="edit-2" class="w-4 h-4"></i>
            <span>{{ __('Edit Policy') }}</span>
        </a>
    </div>
</div>
@endsection
