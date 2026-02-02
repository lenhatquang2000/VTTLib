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
            <h1 class="text-3xl font-black text-slate-900 tracking-tight">{{ __('Modify Identity') }}</h1>
            <p class="text-slate-500 font-medium">{{ __('Updating access parameters for') }} <span class="text-indigo-600 font-bold">{{ $user->email }}</span></p>
        </div>
        <div class="h-16 w-16 rounded-3xl bg-indigo-50 border-2 border-white shadow-sm flex items-center justify-center text-indigo-600 font-black text-2xl">
            {{ strtoupper(substr($user->name, 0, 1)) }}
        </div>
    </div>

    <!-- Form Container -->
    <div class="bg-white rounded-[2rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden">
        <form action="{{ route('admin.users.update', $user) }}" method="POST" class="p-8 md:p-12">
            @csrf
            @method('PUT')

            <div class="space-y-10">
                <!-- Section 1: Core Information -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-2">
                        <label for="name" class="block text-xs font-black text-slate-400 uppercase tracking-widest pl-1">{{ __('Full Name') }}</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" 
                               class="block w-full px-5 py-4 bg-slate-50 border-2 border-transparent rounded-2xl focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all duration-200 font-bold text-slate-700 @error('name') border-rose-500 bg-rose-50 @enderror">
                        @error('name')
                            <p class="text-[11px] text-rose-500 font-bold mt-1 pl-1 italic">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="email" class="block text-xs font-black text-slate-400 uppercase tracking-widest pl-1">{{ __('Communication Relay') }}</label>
                        <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" 
                               class="block w-full px-5 py-4 bg-slate-50 border-2 border-transparent rounded-2xl focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all duration-200 font-bold text-slate-700 @error('email') border-rose-500 bg-rose-50 @enderror">
                        @error('email')
                            <p class="text-[11px] text-rose-500 font-bold mt-1 pl-1 italic">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Section 2: Account Logistics -->
                <div class="pt-6 border-t border-slate-50 flex flex-col md:flex-row gap-8">
                    <div class="w-full md:w-1/3 space-y-2">
                        <label for="status" class="block text-xs font-black text-slate-400 uppercase tracking-widest pl-1">{{ __('Security Status') }}</label>
                        <div class="relative">
                            <select name="status" id="status" class="appearance-none block w-full px-5 py-4 bg-slate-50 border-2 border-transparent rounded-2xl focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all duration-200 font-bold text-slate-700 cursor-pointer">
                                <option value="active" {{ old('status', $user->status) == 'active' ? 'selected' : '' }}>🟢 {{ __('Authorized') }}</option>
                                <option value="inactive" {{ old('status', $user->status) == 'inactive' ? 'selected' : '' }}>⚪ {{ __('Offline') }}</option>
                                <option value="suspended" {{ old('status', $user->status) == 'suspended' ? 'selected' : '' }}>🔴 {{ __('Revoked') }}</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-400">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </div>
                        </div>
                    </div>

                    <!-- Roles Grid -->
                    <div class="flex-1 space-y-4">
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest pl-1">{{ __('Clearance Level Assignments') }}</label>
                        <div class="grid grid-cols-2 gap-3">
                            @foreach($roles as $role)
                            <label class="relative flex items-center p-4 rounded-2xl border-2 transition-all cursor-pointer {{ $user->roles->contains($role->id) ? 'bg-indigo-50 border-indigo-200 shadow-sm' : 'bg-slate-50 border-transparent hover:border-slate-200' }}">
                                <input type="checkbox" name="roles[]" value="{{ $role->id }}" 
                                       {{ $user->roles->contains($role->id) ? 'checked' : '' }}
                                       class="h-5 w-5 text-indigo-600 focus:ring-indigo-500 border-slate-300 rounded-lg">
                                <div class="ml-3">
                                    <span class="block text-sm font-black {{ $user->roles->contains($role->id) ? 'text-indigo-700' : 'text-slate-600' }}">{{ strtoupper($role->name) }}</span>
                                    <span class="block text-[10px] font-bold text-slate-400">{{ __($role->display_name) }}</span>
                                </div>
                            </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Footer Actions -->
                <div class="pt-10 flex items-center justify-between gap-4">
                    <button type="button" onclick="history.back()" class="px-8 py-4 text-sm font-bold text-slate-400 hover:text-slate-600 transition-colors uppercase tracking-widest">
                        {{ __('Abort') }}
                    </button>
                    <button type="submit" class="inline-flex items-center px-10 py-4 bg-indigo-600 text-white font-black rounded-2xl shadow-lg shadow-indigo-200 hover:bg-indigo-700 hover:-translate-y-1 active:translate-y-0 transition-all duration-200 uppercase tracking-widest text-sm">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        {{ __('Commit Changes') }}
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Audit Trace Placeholder -->
    <div class="p-6 bg-slate-50 rounded-2xl border border-slate-100 flex items-center">
        <div class="p-2 bg-indigo-100 rounded-lg text-indigo-600 mr-4">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">{{ __('Last Subject Update') }}</p>
            <p class="text-sm font-bold text-slate-700">{{ $user->updated_at->diffForHumans() }}</p>
        </div>
    </div>
</div>
@endsection
