@extends('layouts.admin')

@section('content')
<div class="max-w-xl mx-auto px-4 md:px-6 py-4 space-y-4 animate-in slide-in-from-bottom-4 duration-500">
    <!-- Header -->
    <div class="flex items-center justify-between gap-3">
        <div>
            <a href="{{ route('admin.users.index') }}" class="group inline-flex items-center text-xs font-bold text-muted-foreground hover:text-primary transition-colors mb-1.5">
                <i data-lucide="arrow-left" class="w-3.5 h-3.5 mr-1 transform group-hover:-translate-x-1 transition-transform"></i>
                {{ __('Return to Subject Records') }}
            </a>
            <h1 class="text-xl font-bold text-foreground tracking-tight">{{ __('Modify Identity') }}</h1>
            <p class="text-xs text-muted-foreground">{{ __('Updating access parameters for') }} <span class="text-primary font-bold">{{ $user->email }}</span></p>
        </div>
        <div class="h-10 w-10 rounded-full bg-primary/10 border border-primary/20 flex items-center justify-center text-primary font-bold text-sm shrink-0">
            {{ strtoupper(substr($user->name, 0, 1)) }}
        </div>
    </div>

    <!-- Form Container -->
    <div class="bg-card rounded-md border border-border shadow-sm overflow-hidden">
        <form action="{{ route('admin.users.update', $user->id) }}" method="POST" class="p-4 space-y-4">
            @csrf
            @method('PUT')

            <div class="space-y-3">
                <!-- Inputs grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div class="space-y-1">
                        <label class="block text-[10px] font-bold text-muted-foreground uppercase tracking-wider">{{ __('Full Name') }}</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                               class="block w-full px-3 py-1.5 h-9 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all font-medium">
                    </div>

                    <div class="space-y-1">
                        <label class="block text-[10px] font-bold text-muted-foreground uppercase tracking-wider">{{ __('Username') }}</label>
                        <input type="text" name="username" value="{{ old('username', $user->username) }}" required
                               class="block w-full px-3 py-1.5 h-9 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all font-mono font-medium">
                    </div>
                </div>

                <div class="space-y-1">
                    <label class="block text-[10px] font-bold text-muted-foreground uppercase tracking-wider">{{ __('Communication Relay') }} (Email)</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                           class="block w-full px-3 py-1.5 h-9 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all font-medium">
                </div>

                <div class="space-y-1">
                    <label class="block text-[10px] font-bold text-muted-foreground uppercase tracking-wider">{{ __('New Master Cipher') }} (Password)</label>
                    <div class="relative">
                        <input type="password" name="password" id="password_input" placeholder="{{ __('Leave blank to keep current') }}"
                               class="block w-full pl-3 pr-9 py-1.5 h-9 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all font-medium">
                        <button type="button" id="toggle-password-visibility" class="absolute inset-y-0 right-0 pr-3 flex items-center text-muted-foreground hover:text-foreground">
                            <i data-lucide="eye" class="w-4 h-4 eye-icon"></i>
                            <i data-lucide="eye-off" class="w-4 h-4 eye-off-icon hidden"></i>
                        </button>
                    </div>
                </div>

                <!-- Password Requirements Checklist -->
                <div class="mt-2 space-y-1 text-[11px] bg-muted/30 p-2.5 rounded-sm border border-border hidden" id="password-requirements">
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
            </div>

            <!-- Footer Actions -->
            <div class="pt-3 flex items-center justify-between gap-3 border-t border-border mt-4">
                <button type="button" onclick="history.back()" class="btn-compact-secondary px-4 py-1.5">
                    <i data-lucide="x-circle" class="w-4 h-4 mr-1.5"></i>
                    {{ __('Abort') }}
                </button>
                <button type="submit" class="btn-compact-primary px-4 py-1.5">
                    <i data-lucide="check" class="w-4 h-4 mr-1.5"></i>
                    {{ __('Commit Changes') }}
                </button>
            </div>
        </form>
    </div>

    <!-- Audit Trace -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
        <div class="p-3 bg-muted/30 rounded-md border border-border flex items-center gap-3">
            <div class="p-2 bg-primary/10 rounded-sm text-primary">
                <i data-lucide="calendar" class="w-4 h-4"></i>
            </div>
            <div>
                <p class="text-[9px] font-bold text-muted-foreground uppercase tracking-wider">{{ __('Enrolled') }}</p>
                <p class="text-xs font-bold text-foreground">{{ $user->created_at ? $user->created_at->format('M d, Y') : 'N/A' }}</p>
            </div>
        </div>
        <div class="p-3 bg-muted/30 rounded-md border border-border flex items-center gap-3">
            <div class="p-2 bg-green-500/10 rounded-sm text-green-600 dark:text-green-400">
                <i data-lucide="shield-check" class="w-4 h-4"></i>
            </div>
            <div>
                <p class="text-[9px] font-bold text-muted-foreground uppercase tracking-wider">{{ __('Current State') }}</p>
                <p class="text-xs font-bold text-foreground capitalize">{{ $user->status }}</p>
            </div>
        </div>
        <div class="p-3 bg-muted/30 rounded-md border border-border flex items-center gap-3">
            <div class="p-2 bg-amber-500/10 rounded-sm text-amber-600 dark:text-amber-400">
                <i data-lucide="users" class="w-4 h-4"></i>
            </div>
            <div>
                <p class="text-[9px] font-bold text-muted-foreground uppercase tracking-wider">{{ __('Security Roles') }}</p>
                <p class="text-xs font-bold text-foreground">{{ $user->roles->count() }} active zones</p>
            </div>
        </div>
    </div>
</div>

<script>
    const passwordInput = document.getElementById('password_input');
    const toggleVisibilityBtn = document.getElementById('toggle-password-visibility');
    const passReqs = document.getElementById('password-requirements');

    // Requirements indicators
    const reqLength = document.getElementById('req-length');
    const reqMixed = document.getElementById('req-mixed');
    const reqNumbers = document.getElementById('req-numbers');
    const reqSymbols = document.getElementById('req-symbols');

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

    function validatePassword(val) {
        const meetsLength = val.length >= 8;
        const meetsMixed = /[a-z]/.test(val) && /[A-Z]/.test(val);
        const meetsNumbers = /[0-9]/.test(val);
        const meetsSymbols = /[^a-zA-Z0-9]/.test(val);

        updateRequirementUI(reqLength, meetsLength);
        updateRequirementUI(reqMixed, meetsMixed);
        updateRequirementUI(reqNumbers, meetsNumbers);
        updateRequirementUI(reqSymbols, meetsSymbols);
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

    passwordInput?.addEventListener('input', function() {
        const val = this.value;
        if (val) {
            passReqs?.classList.remove('hidden');
            validatePassword(val);
        } else {
            passReqs?.classList.add('hidden');
        }
    });
</script>
@endsection
