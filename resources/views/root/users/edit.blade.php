@extends('layouts.root')

@section('content')
    <div class="max-w-4xl mx-auto space-y-6 animate-in slide-in-from-bottom-4 duration-500">
        <!-- Header -->
        <div class="flex items-center justify-between pb-2">
            <div>
                <a href="{{ route('root.users.index') }}" 
                    class="inline-flex items-center text-xs font-bold text-slate-500 hover:text-indigo-600 transition-colors uppercase tracking-widest mb-3 group">
                    <svg class="w-4 h-4 mr-1.5 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    {{ __('Back_to_List') }}
                </a>
                <h1 class="text-3xl font-bold text-slate-900 dark:text-white tracking-tight">{{ __('Edit_User') }}: <span class="text-indigo-600 dark:text-indigo-400">{{ $user->name }}</span></h1>
            </div>
        </div>

        <!-- Edit Form -->
        <div class="bg-white dark:bg-slate-800 rounded-[2.5rem] border border-slate-200 dark:border-slate-700 p-10 shadow-sm transition-all">
            <form action="{{ route('root.users.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Name -->
                    <div class="space-y-2">
                        <label class="text-xs font-black text-slate-400 uppercase tracking-widest pl-1">{{ __('Full_Name') }}</label>
                        <input type="text" name="name" value="{{ $user->name }}" required
                            class="w-full bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 rounded-2xl p-4 text-sm font-medium focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all">
                    </div>

                    <!-- Username -->
                    <div class="space-y-2">
                        <label class="text-xs font-black text-slate-400 uppercase tracking-widest pl-1">{{ __('Username') }}</label>
                        <input type="text" name="username" value="{{ $user->username }}" required
                            class="w-full bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 rounded-2xl p-4 text-sm font-medium focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all">
                    </div>

                    <!-- Email -->
                    <div class="space-y-2">
                        <label class="text-xs font-black text-slate-400 uppercase tracking-widest pl-1">{{ __('Email_Address') }}</label>
                        <input type="email" name="email" value="{{ $user->email }}" required
                            class="w-full bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 rounded-2xl p-4 text-sm font-medium focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all">
                    </div>

                    <!-- Password -->
                    <div class="space-y-2">
                        <label class="text-xs font-black text-slate-400 uppercase tracking-widest pl-1">{{ __('New_Password') }}</label>
                        <input type="password" name="password" id="password_input"
                            placeholder="{{ __('Leave_blank_to_keep_current') }}"
                            class="w-full bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 rounded-2xl p-4 text-sm font-medium focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all">
                        <div id="password_requirements" class="mt-2 space-y-1 px-1 hidden">
                            <p id="req_length" class="text-[9px] font-bold uppercase transition-colors text-slate-400">• Tối thiểu 8 ký tự</p>
                            <p id="req_case" class="text-[9px] font-bold uppercase transition-colors text-slate-400">• Chứa chữ hoa & chữ thường</p>
                            <p id="req_number" class="text-[9px] font-bold uppercase transition-colors text-slate-400">• Chứa ít nhất 1 con số</p>
                            <p id="req_symbol" class="text-[9px] font-bold uppercase transition-colors text-slate-400">• Chứa ít nhất 1 ký tự đặc biệt</p>
                        </div>
                    </div>

                    <!-- Password Confirmation -->
                    <div class="space-y-2">
                        <label class="text-xs font-black text-slate-400 uppercase tracking-widest pl-1">{{ __('Confirm_Password') }}</label>
                        <input type="password" name="password_confirmation" 
                            placeholder="{{ __('Confirm_new_password') }}"
                            class="w-full bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 rounded-2xl p-4 text-sm font-medium focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all">
                    </div>
                </div>

                <!-- Roles Selection -->
                <div class="mt-10 space-y-4">
                    <label class="text-xs font-black text-slate-400 uppercase tracking-widest pl-1 block">{{ __('Security_Clearance_Configuration') }}</label>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        @foreach($roles as $role)
                        <label class="relative flex items-center p-4 bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 rounded-2xl cursor-pointer hover:border-indigo-500 transition-all group">
                            <input type="checkbox" name="roles[]" value="{{ $role->id }}" 
                                {{ $user->roles->contains($role->id) ? 'checked' : '' }}
                                class="w-5 h-5 rounded-lg border-slate-300 text-indigo-600 focus:ring-indigo-500 transition-all">
                            <div class="ml-3">
                                <span class="block text-sm font-black text-slate-700 dark:text-slate-200 group-hover:text-indigo-600 transition-colors">{{ $role->display_name }}</span>
                                <span class="block text-[9px] font-bold text-slate-400 uppercase tracking-tighter">{{ $role->name }}</span>
                            </div>
                        </label>
                        @endforeach
                    </div>
                </div>

                <!-- User Info Cards -->
                <div class="mt-10 grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="p-5 bg-slate-50 dark:bg-slate-900/50 border border-slate-100 dark:border-slate-700 rounded-2xl">
                        <span class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">{{ __('User_ID') }}</span>
                        <span class="text-sm font-mono font-bold text-slate-600 dark:text-slate-300">#{{ str_pad($user->id, 5, '0', STR_PAD_LEFT) }}</span>
                    </div>
                    <div class="p-5 bg-slate-50 dark:bg-slate-900/50 border border-slate-100 dark:border-slate-700 rounded-2xl">
                        <span class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">{{ __('Created') }}</span>
                        <span class="text-sm font-bold text-slate-600 dark:text-slate-300">{{ $user->created_at->format('M d, Y') }}</span>
                    </div>
                    <div class="p-5 bg-slate-50 dark:bg-slate-900/50 border border-slate-100 dark:border-slate-700 rounded-2xl">
                        <span class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">{{ __('Active_Roles') }}</span>
                        <div class="flex flex-wrap gap-1 mt-1">
                            @foreach($user->roles as $role)
                                <span class="px-2 py-0.5 bg-indigo-50 dark:bg-indigo-900/40 text-indigo-600 dark:text-indigo-400 text-[9px] font-black uppercase rounded">
                                    {{ $role->display_name }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="mt-10 pt-8 border-t border-slate-100 dark:border-slate-700 flex flex-col sm:flex-row gap-4">
                    <button type="submit" 
                        class="flex-1 sm:flex-none px-10 py-4 bg-indigo-600 text-white text-xs font-black uppercase tracking-[0.2em] rounded-2xl hover:bg-indigo-700 shadow-xl shadow-indigo-500/20 transition transform active:scale-95 group">
                        <span class="flex items-center justify-center">
                            {{ __('Update_User') }}
                            <svg class="w-4 h-4 ml-2 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                        </span>
                    </button>
                    <a href="{{ route('root.users.index') }}" 
                        class="flex-1 sm:flex-none px-10 py-4 bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-300 text-xs font-black uppercase tracking-[0.2em] rounded-2xl hover:bg-slate-200 text-center transition">
                        {{ __('Cancel') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
    <script>
        const passwordInput = document.getElementById('password_input');
        const passwordReqs = document.getElementById('password_requirements');
        const reqLength = document.getElementById('req_length');
        const reqCase = document.getElementById('req_case');
        const reqNumber = document.getElementById('req_number');
        const reqSymbol = document.getElementById('req_symbol');

        passwordInput?.addEventListener('focus', () => passwordReqs.classList.remove('hidden'));

        passwordInput?.addEventListener('input', function() {
            const val = this.value;
            
            if (!val) {
                passwordReqs.classList.add('hidden');
                return;
            }
            passwordReqs.classList.remove('hidden');

            // Length check
            if (val.length >= 8) {
                reqLength.classList.replace('text-slate-400', 'text-emerald-500');
            } else {
                reqLength.classList.remove('text-emerald-500');
                reqLength.classList.add('text-slate-400');
            }

            // Case check
            if (/[a-z]/.test(val) && /[A-Z]/.test(val)) {
                reqCase.classList.replace('text-slate-400', 'text-emerald-500');
            } else {
                reqCase.classList.remove('text-emerald-500');
                reqCase.classList.add('text-slate-400');
            }

            // Number check
            if (/\d/.test(val)) {
                reqNumber.classList.replace('text-slate-400', 'text-emerald-500');
            } else {
                reqNumber.classList.remove('text-emerald-500');
                reqNumber.classList.add('text-slate-400');
            }

            // Symbol check
            if (/[!@#$%^&*(),.?":{}|<>]/.test(val)) {
                reqSymbol.classList.replace('text-slate-400', 'text-emerald-500');
            } else {
                reqSymbol.classList.remove('text-emerald-500');
                reqSymbol.classList.add('text-slate-400');
            }
        });
    </script>
@endsection
