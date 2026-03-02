@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto space-y-8 animate-in slide-in-from-bottom-4 duration-500">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <a href="{{ route('admin.users.index') }}" class="group inline-flex items-center text-sm font-bold text-slate-400 hover:text-indigo-600 transition-colors mb-2">
                <svg class="w-4 h-4 mr-2 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                {{ __('Return to Subject Records') }}
            </a>
            <h1 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight">{{ __('Modify Identity') }}</h1>
            <p class="text-slate-500 font-medium">{{ __('Updating access parameters for') }} <span class="text-indigo-600 font-bold">{{ $user->email }}</span></p>
        </div>
        <div class="h-16 w-16 rounded-3xl bg-indigo-50 dark:bg-indigo-900/30 border-2 border-white dark:border-slate-800 shadow-sm flex items-center justify-center text-indigo-600 dark:text-indigo-400 font-black text-2xl">
            {{ strtoupper(substr($user->name, 0, 1)) }}
        </div>
    </div>

    <!-- Form Container -->
    <div class="bg-white dark:bg-slate-800 rounded-[2.5rem] shadow-xl shadow-slate-200/50 dark:shadow-none border border-slate-100 dark:border-slate-700 overflow-hidden">
        <form action="{{ route('admin.users.update', $user->id) }}" method="POST" class="p-8 md:p-12">
            @csrf
            @method('PUT')

            <div class="space-y-10">
                <!-- Core Information -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-2">
                        <label class="block text-xs font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest pl-1">{{ __('Full Name') }}</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                               class="block w-full px-5 py-4 bg-slate-50 dark:bg-slate-950 border-2 border-transparent rounded-2xl focus:bg-white dark:focus:bg-slate-900 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all duration-200 font-bold text-slate-700 dark:text-slate-200">
                    </div>

                    <div class="space-y-2">
                        <label class="block text-xs font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest pl-1">{{ __('Username') }}</label>
                        <input type="text" name="username" value="{{ old('username', $user->username) }}" required
                               class="block w-full px-5 py-4 bg-slate-50 dark:bg-slate-950 border-2 border-transparent rounded-2xl focus:bg-white dark:focus:bg-slate-900 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all duration-200 font-bold text-slate-700 dark:text-slate-200">
                    </div>

                    <div class="space-y-2">
                        <label class="block text-xs font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest pl-1">{{ __('Communication Relay') }} (Email)</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                               class="block w-full px-5 py-4 bg-slate-50 dark:bg-slate-950 border-2 border-transparent rounded-2xl focus:bg-white dark:focus:bg-slate-900 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all duration-200 font-bold text-slate-700 dark:text-slate-200">
                    </div>

                    <div class="space-y-2">
                        <label class="block text-xs font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest pl-1">{{ __('New Master Cipher') }} (Password)</label>
                        <input type="password" name="password" id="password_input" placeholder="{{ __('Leave blank to keep current') }}"
                               class="block w-full px-5 py-4 bg-slate-50 dark:bg-slate-950 border-2 border-transparent rounded-2xl focus:bg-white dark:focus:bg-slate-900 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all duration-200 font-bold text-slate-700 dark:text-slate-200">
                        <div id="password_requirements" class="mt-4 space-y-2 px-1 hidden scale-in">
                            <p id="req_length" class="text-[9px] font-black uppercase tracking-wider transition-colors text-slate-400">• Tối thiểu 8 ký tự</p>
                            <p id="req_case" class="text-[9px] font-black uppercase tracking-wider transition-colors text-slate-400">• Chứa chữ hoa & chữ thường</p>
                            <p id="req_number" class="text-[9px] font-black uppercase tracking-wider transition-colors text-slate-400">• Chứa ít nhất 1 con số</p>
                            <p id="req_symbol" class="text-[9px] font-black uppercase tracking-wider transition-colors text-slate-400">• Chứa ít nhất 1 ký tự đặc biệt</p>
                        </div>
                    </div>
                </div>

                <!-- Footer Actions -->
                <div class="pt-10 flex items-center justify-between gap-4 border-t border-slate-50 dark:border-slate-700/50">
                    <button type="button" onclick="history.back()" class="px-10 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] hover:text-rose-500 transition-colors focus:outline-none">
                        {{ __('Abort') }}
                    </button>
                    <button type="submit" class="inline-flex items-center px-12 py-5 bg-indigo-600 text-white font-black rounded-3xl shadow-2xl shadow-indigo-500/30 hover:bg-indigo-700 hover:-translate-y-1 active:translate-y-0 transition-all duration-300 uppercase tracking-[0.2em] text-[10px]">
                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        {{ __('Commit Changes') }}
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Audit Trace -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="p-6 bg-slate-50 dark:bg-slate-900/50 rounded-2xl border border-slate-100 dark:border-slate-800 flex items-center">
            <div class="p-2.5 bg-indigo-100 dark:bg-indigo-500/10 rounded-xl text-indigo-600 dark:text-indigo-400 mr-4">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <p class="text-[9px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest">{{ __('Enrolled') }}</p>
                <p class="text-sm font-bold text-slate-700 dark:text-slate-300">{{ $user->created_at->format('M d, Y') }}</p>
            </div>
        </div>
        <div class="p-6 bg-slate-50 dark:bg-slate-900/50 rounded-2xl border border-slate-100 dark:border-slate-800 flex items-center">
            <div class="p-2.5 bg-emerald-100 dark:bg-emerald-500/10 rounded-xl text-emerald-600 dark:text-emerald-400 mr-4">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
            </div>
            <div>
                <p class="text-[9px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest">{{ __('Current State') }}</p>
                <p class="text-sm font-bold text-slate-700 dark:text-slate-300 capitalize">{{ $user->status }}</p>
            </div>
        </div>
        <div class="p-6 bg-slate-50 dark:bg-slate-900/50 rounded-2xl border border-slate-100 dark:border-slate-800 flex items-center">
            <div class="p-2.5 bg-amber-100 dark:bg-amber-500/10 rounded-xl text-amber-600 dark:text-amber-400 mr-4">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
            </div>
            <div>
                <p class="text-[9px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest">{{ __('Security Roles') }}</p>
                <p class="text-sm font-bold text-slate-700 dark:text-slate-300">{{ $user->roles->count() }} active zones</p>
            </div>
        </div>
    </div>
</div>

<style>
    .scale-in { animation: scale-in 0.3s ease-out; }
    @keyframes scale-in { from { transform: scale(0.95); opacity: 0; } to { transform: scale(1); opacity: 1; } }
</style>

<script>
    const passInput = document.getElementById('password_input');
    const passReqs = document.getElementById('password_requirements');
    const rLen = document.getElementById('req_length');
    const rCase = document.getElementById('req_case');
    const rNum = document.getElementById('req_number');
    const rSym = document.getElementById('req_symbol');

    passInput?.addEventListener('focus', () => passReqs.classList.remove('hidden'));

    passInput?.addEventListener('input', function() {
        const v = this.value;
        if (!v) {
            passReqs.classList.add('hidden');
            return;
        }
        passReqs.classList.remove('hidden');

        rLen.classList.toggle('text-emerald-500', v.length >= 8);
        rLen.classList.toggle('text-slate-400', v.length < 8);
        
        rCase.classList.toggle('text-emerald-500', /[a-z]/.test(v) && /[A-Z]/.test(v));
        rCase.classList.toggle('text-slate-400', !(/[a-z]/.test(v) && /[A-Z]/.test(v)));
        
        rNum.classList.toggle('text-emerald-500', /\d/.test(v));
        rNum.classList.toggle('text-slate-400', !(/\d/.test(v)));
        
        rSym.classList.toggle('text-emerald-500', /[!@#$%^&*(),.?":{}|<>]/.test(v));
        rSym.classList.toggle('text-slate-400', !(/[!@#$%^&*(),.?":{}|<>]/.test(v)));
    });
</script>
@endsection
