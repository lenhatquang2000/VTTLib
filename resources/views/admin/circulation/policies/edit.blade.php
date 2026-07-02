@extends('layouts.admin')

@section('content')
<div class="w-full space-y-4 animate-in fade-in duration-500 pb-8">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <h1 class="text-lg font-bold text-foreground tracking-tight">{{ __('Edit Circulation Policy') }}</h1>
            <p class="text-xs text-muted-foreground mt-0.5">{{ $policy->name }} - {{ __('Update policy settings') }}</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.circulation.policies.show', $policy) }}" class="btn-compact-secondary">
                <i data-lucide="eye" class="w-4 h-4 mr-1"></i>
                <span>{{ __('View') }}</span>
            </a>
            <a href="{{ route('admin.circulation.policies.index') }}" class="btn-compact-secondary">
                <i data-lucide="arrow-left" class="w-4 h-4 mr-1"></i>
                <span>{{ __('Back') }}</span>
            </a>
        </div>
    </div>

    <!-- Form -->
    <form action="{{ route('admin.circulation.policies.update', $policy) }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')
        
        <!-- Basic Information -->
        <div class="bg-card text-foreground rounded-md border border-border shadow-sm overflow-hidden">
            <div class="p-3 border-b border-border bg-muted/30">
                <h2 class="text-xs font-bold uppercase tracking-wider text-foreground">{{ __('Basic Information') }}</h2>
            </div>
            
            <div class="p-3 space-y-3">
                @if($errors->any())
                <div class="p-3 bg-destructive/10 border border-destructive/20 text-destructive text-xs rounded-sm space-y-1">
                    <h3 class="font-bold">{{ __('Something went wrong:') }}</h3>
                    <ul class="list-disc pl-4 space-y-0.5">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div class="space-y-1">
                        <label class="block text-[10px] text-muted-foreground uppercase font-bold tracking-wider">{{ __('Policy Name') }} <span class="text-destructive">*</span></label>
                        <input type="text" name="name" required value="{{ $policy->name }}"
                               class="w-full h-9 px-3 py-1.5 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all">
                    </div>
                    
                    <div class="space-y-1">
                        <label class="block text-[10px] text-muted-foreground uppercase font-bold tracking-wider">{{ __('Patron Group') }} <span class="text-destructive">*</span></label>
                        <select name="patron_group_id" required 
                                class="w-full h-9 px-3 py-1.5 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all">
                            <option value="">{{ __('-- Select patron group --') }}</option>
                            @foreach($patronGroups as $group)
                            <option value="{{ $group->id }}" {{ $policy->patron_group_id == $group->id ? 'selected' : '' }}>
                                {{ $group->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <div class="space-y-1">
                    <label class="block text-[10px] text-muted-foreground uppercase font-bold tracking-wider">{{ __('Notes') }}</label>
                    <textarea name="notes" rows="2" 
                              class="w-full px-3 py-1.5 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all">{{ $policy->notes }}</textarea>
                </div>
            </div>
        </div>

        <!-- Loan Settings -->
        <div class="bg-card text-foreground rounded-md border border-border shadow-sm overflow-hidden">
            <div class="p-3 border-b border-border bg-muted/30">
                <h2 class="text-xs font-bold uppercase tracking-wider text-foreground">{{ __('Loan Settings') }}</h2>
            </div>
            
            <div class="p-3">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                    <div class="space-y-1">
                        <label class="block text-[10px] text-muted-foreground uppercase font-bold tracking-wider">{{ __('Max Borrow Days') }} <span class="text-destructive">*</span></label>
                        <input type="number" name="max_loan_days" required min="1" max="365" value="{{ $policy->max_loan_days }}"
                               class="w-full h-9 px-3 py-1.5 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all">
                    </div>
                    
                    <div class="space-y-1">
                        <label class="block text-[10px] text-muted-foreground uppercase font-bold tracking-wider">{{ __('Max Borrow Books') }} <span class="text-destructive">*</span></label>
                        <input type="number" name="max_items" required min="1" max="50" value="{{ $policy->max_items }}"
                               class="w-full h-9 px-3 py-1.5 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all">
                    </div>
                    
                    <div class="space-y-1">
                        <label class="block text-[10px] text-muted-foreground uppercase font-bold tracking-wider">{{ __('Max Renewals') }} <span class="text-destructive">*</span></label>
                        <input type="number" name="max_renewals" required min="0" max="10" value="{{ $policy->max_renewals }}"
                               class="w-full h-9 px-3 py-1.5 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all">
                    </div>
                    
                    <div class="space-y-1">
                        <label class="block text-[10px] text-muted-foreground uppercase font-bold tracking-wider">{{ __('Renewal Days') }} <span class="text-destructive">*</span></label>
                        <input type="number" name="renewal_days" required min="1" max="90" value="{{ $policy->renewal_days }}"
                               class="w-full h-9 px-3 py-1.5 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all">
                    </div>
                </div>
            </div>
        </div>

        <!-- Fine Settings -->
        <div class="bg-card text-foreground rounded-md border border-border shadow-sm overflow-hidden">
            <div class="p-3 border-b border-border bg-muted/30">
                <h2 class="text-xs font-bold uppercase tracking-wider text-foreground">{{ __('Fine Settings') }}</h2>
            </div>
            
            <div class="p-3">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                    <div class="space-y-1">
                        <label class="block text-[10px] text-muted-foreground uppercase font-bold tracking-wider">{{ __('Fine per day (VND)') }} <span class="text-destructive">*</span></label>
                        <input type="number" name="fine_per_day" required min="0" max="100000" step="100" value="{{ $policy->fine_per_day }}"
                               class="w-full h-9 px-3 py-1.5 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all">
                    </div>
                    
                    <div class="space-y-1">
                        <label class="block text-[10px] text-muted-foreground uppercase font-bold tracking-wider">{{ __('Max Fine (VND)') }} <span class="text-destructive">*</span></label>
                        <input type="number" name="max_fine" required min="0" max="1000000" step="100" value="{{ $policy->max_fine }}"
                               class="w-full h-9 px-3 py-1.5 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all">
                    </div>
                    
                    <div class="space-y-1">
                        <label class="block text-[10px] text-muted-foreground uppercase font-bold tracking-wider">{{ __('Grace Period Days') }} <span class="text-destructive">*</span></label>
                        <input type="number" name="grace_period_days" required min="0" max="30" value="{{ $policy->grace_period_days }}"
                               class="w-full h-9 px-3 py-1.5 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all">
                    </div>
                    
                    <div class="space-y-1">
                        <label class="block text-[10px] text-muted-foreground uppercase font-bold tracking-wider">{{ __('Max Outstanding Debt (VND)') }} <span class="text-destructive">*</span></label>
                        <input type="number" name="max_outstanding_fine" required min="0" max="1000000" step="100" value="{{ $policy->max_outstanding_fine }}"
                               class="w-full h-9 px-3 py-1.5 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all">
                    </div>
                </div>
            </div>
        </div>

        <!-- Reservation Settings -->
        <div class="bg-card text-foreground rounded-md border border-border shadow-sm overflow-hidden">
            <div class="p-3 border-b border-border bg-muted/30">
                <h2 class="text-xs font-bold uppercase tracking-wider text-foreground">{{ __('Hold/Reserve Settings') }}</h2>
            </div>
            
            <div class="p-3 space-y-3">
                <label class="flex items-center gap-2 cursor-pointer group w-fit">
                    <input type="checkbox" name="can_reserve" {{ $policy->can_reserve ? 'checked' : '' }}
                           class="w-4 h-4 rounded-sm border-input bg-background text-primary focus:ring-primary">
                    <span class="text-xs text-muted-foreground font-semibold group-hover:text-primary transition-colors">{{ __('Allow book hold requests') }}</span>
                </label>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div class="space-y-1">
                        <label class="block text-[10px] text-muted-foreground uppercase font-bold tracking-wider">{{ __('Max Hold Requests') }} <span class="text-destructive">*</span></label>
                        <input type="number" name="max_reservations" required min="0" max="20" value="{{ $policy->max_reservations }}"
                               class="w-full h-9 px-3 py-1.5 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all">
                    </div>
                    
                    <div class="space-y-1">
                        <label class="block text-[10px] text-muted-foreground uppercase font-bold tracking-wider">{{ __('Hold duration (days)') }} <span class="text-destructive">*</span></label>
                        <input type="number" name="reservation_hold_days" required min="1" max="30" value="{{ $policy->reservation_hold_days }}"
                               class="w-full h-9 px-3 py-1.5 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all">
                    </div>
                </div>
            </div>
        </div>

        <!-- Reading Room Policies -->
        <div class="bg-card text-foreground rounded-md border border-border shadow-sm overflow-hidden">
            <div class="p-3 border-b border-border bg-muted/30">
                <h2 class="text-xs font-bold uppercase tracking-wider text-foreground">{{ __('Reading Room Policies') }}</h2>
            </div>
            
            <div class="p-3 space-y-3">
                <label class="flex items-center gap-2 cursor-pointer group w-fit">
                    <input type="checkbox" name="can_use_reading_room" {{ $policy->can_use_reading_room ? 'checked' : '' }}
                           class="w-4 h-4 rounded-sm border-input bg-background text-primary focus:ring-primary">
                    <span class="text-xs text-muted-foreground font-semibold group-hover:text-primary transition-colors">{{ __('Allow reading room checkouts') }}</span>
                </label>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                    <div class="space-y-1">
                        <label class="block text-[10px] text-muted-foreground uppercase font-bold tracking-wider">{{ __('Max items') }} <span class="text-destructive">*</span></label>
                        <input type="number" name="max_reading_room_items" required min="0" max="20" value="{{ $policy->max_reading_room_items }}"
                               class="w-full h-9 px-3 py-1.5 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all">
                    </div>
                    
                    <div class="space-y-1">
                        <label class="block text-[10px] text-muted-foreground uppercase font-bold tracking-wider">{{ __('Loan hours') }} <span class="text-destructive">*</span></label>
                        <input type="number" name="reading_room_hours" required min="1" max="24" value="{{ $policy->reading_room_hours }}"
                               class="w-full h-9 px-3 py-1.5 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all">
                    </div>
                    
                    <div class="space-y-1">
                        <label class="block text-[10px] text-muted-foreground uppercase font-bold tracking-wider">{{ __('Default return time') }} <span class="text-destructive">*</span></label>
                        <input type="time" name="reading_room_due_time" value="{{ $policy->reading_room_due_time && $policy->reading_room_due_time !== '00:00:00' ? date('H:i', strtotime($policy->reading_room_due_time)) : '' }}"
                               class="w-full h-9 px-3 py-1.5 text-sm border {{ $errors->has('reading_room_due_time') ? 'border-destructive' : 'border-input' }} rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all">
                        @if($errors->has('reading_room_due_time'))
                        <p class="text-xs text-destructive mt-1">{{ $errors->first('reading_room_due_time') }}</p>
                        @endif
                    </div>
                    
                    <div class="space-y-1">
                        <label class="block text-[10px] text-muted-foreground uppercase font-bold tracking-wider">{{ __('Fine per hour (VND)') }} <span class="text-destructive">*</span></label>
                        <input type="number" name="reading_room_fine_per_hour" required min="0" max="100000" step="100" value="{{ $policy->reading_room_fine_per_hour }}"
                               class="w-full h-9 px-3 py-1.5 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all">
                    </div>
                </div>
                
                <div class="space-y-1">
                    <label class="block text-[10px] text-muted-foreground uppercase font-bold tracking-wider">{{ __('Max reading room fine (VND)') }} <span class="text-destructive">*</span></label>
                    <input type="number" name="reading_room_max_fine" required min="0" max="1000000" step="100" value="{{ $policy->reading_room_max_fine }}"
                           class="w-full sm:w-1/2 lg:w-1/4 h-9 px-3 py-1.5 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all">
                </div>
            </div>
        </div>

        <!-- Hold/Reserve Settings -->
        <div class="bg-card text-foreground rounded-md border border-border shadow-sm overflow-hidden">
            <div class="p-3 border-b border-border bg-muted/30">
                <h2 class="text-xs font-bold uppercase tracking-wider text-foreground">{{ __('Hold Settings') }}</h2>
            </div>
            
            <div class="p-3 space-y-3">
                <label class="flex items-center gap-2 cursor-pointer group w-fit">
                    <input type="checkbox" name="can_place_hold" {{ $policy->can_place_hold ? 'checked' : '' }}
                           class="w-4 h-4 rounded-sm border-input bg-background text-primary focus:ring-primary">
                    <span class="text-xs text-muted-foreground font-semibold group-hover:text-primary transition-colors">{{ __('Allow book hold requests') }}</span>
                </label>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                    <div class="space-y-1">
                        <label class="block text-[10px] text-muted-foreground uppercase font-bold tracking-wider">{{ __('Max Holds') }} <span class="text-destructive">*</span></label>
                        <input type="number" name="max_holds" required min="0" max="20" value="{{ $policy->max_holds }}"
                               class="w-full h-9 px-3 py-1.5 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all">
                    </div>
                    
                    <div class="space-y-1">
                        <label class="block text-[10px] text-muted-foreground uppercase font-bold tracking-wider">{{ __('Expiry Days') }} <span class="text-destructive">*</span></label>
                        <input type="number" name="hold_expiry_days" required min="1" max="90" value="{{ $policy->hold_expiry_days }}"
                               class="w-full h-9 px-3 py-1.5 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all">
                    </div>
                    
                    <div class="space-y-1">
                        <label class="block text-[10px] text-muted-foreground uppercase font-bold tracking-wider">{{ __('Notification Days') }} <span class="text-destructive">*</span></label>
                        <input type="number" name="hold_notification_days" required min="0" max="30" value="{{ $policy->hold_notification_days }}"
                               class="w-full h-9 px-3 py-1.5 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all">
                    </div>
                    
                    <div class="space-y-1">
                        <label class="block text-[10px] text-muted-foreground uppercase font-bold tracking-wider">{{ __('Cancellation Fee') }} <span class="text-destructive">*</span></label>
                        <input type="number" name="hold_cancellation_fee" required min="0" max="100000" step="100" value="{{ $policy->hold_cancellation_fee }}"
                               class="w-full h-9 px-3 py-1.5 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all">
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 pt-2 border-t border-border/50">
                    <label class="flex items-center gap-2 cursor-pointer group w-fit h-9">
                        <input type="checkbox" name="allow_hold_renewal" {{ $policy->allow_hold_renewal ? 'checked' : '' }}
                               class="w-4 h-4 rounded-sm border-input bg-background text-primary focus:ring-primary">
                        <span class="text-xs text-muted-foreground font-semibold group-hover:text-primary transition-colors">{{ __('Allow hold renewals') }}</span>
                    </label>
                    
                    <div class="space-y-1">
                        <label class="block text-[10px] text-muted-foreground uppercase font-bold tracking-wider">{{ __('Max Renewals') }} <span class="text-destructive">*</span></label>
                        <input type="number" name="max_hold_renewals" required min="0" max="10" value="{{ $policy->max_hold_renewals }}"
                               class="w-full h-9 px-3 py-1.5 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all">
                    </div>
                </div>
            </div>
        </div>

        <!-- Status Card -->
        <div class="bg-card text-foreground rounded-md border border-border shadow-sm p-3">
            <label class="flex items-center gap-2 cursor-pointer group w-fit">
                <input type="checkbox" name="is_active" {{ $policy->is_active ? 'checked' : '' }}
                       class="w-4 h-4 rounded-sm border-input bg-background text-primary focus:ring-primary">
                <span class="text-xs text-muted-foreground font-semibold group-hover:text-primary transition-colors">{{ __('Activate policy') }}</span>
            </label>
        </div>

        <!-- Actions -->
        <div class="flex justify-end items-center gap-2 mt-6 pt-4 border-t border-border/50">
            <a href="{{ route('admin.circulation.policies.index') }}" class="btn-compact-secondary">
                {{ __('Cancel') }}
            </a>
            <button type="submit" class="inline-flex items-center gap-1.5 bg-primary text-primary-foreground hover:bg-primary/90 px-4 py-2 rounded-sm text-xs font-bold uppercase tracking-widest transition-all active:scale-95 shadow-sm">
                <i data-lucide="save" class="w-4 h-4"></i>
                <span>{{ __('Update Policy') }}</span>
            </button>
        </div>
    </form>
</div>
@endsection
