@extends('layouts.root')

@section('content')
    <div class="max-w-4xl mx-auto space-y-6 animate-in slide-in-from-bottom-4 duration-500">
        @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-600 p-4 rounded-2xl text-sm font-medium flex items-center shadow-sm">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="bg-rose-50 border border-rose-200 text-rose-600 p-4 rounded-2xl text-sm font-medium flex items-center shadow-sm">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                {{ session('error') }}
            </div>
        @endif

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
                        <input type="password" name="password" 
                            placeholder="{{ __('Leave_blank_to_keep_current') }}"
                            class="w-full bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 rounded-2xl p-4 text-sm font-medium focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all">
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider pl-1">{{ __('Optional_Min_6_characters') }}</p>
                    </div>

                    <!-- Password Confirmation -->
                    <div class="space-y-2">
                        <label class="text-xs font-black text-slate-400 uppercase tracking-widest pl-1">{{ __('Confirm_Password') }}</label>
                        <input type="password" name="password_confirmation" 
                            placeholder="{{ __('Confirm_new_password') }}"
                            class="w-full bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 rounded-2xl p-4 text-sm font-medium focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all">
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
@endsection
