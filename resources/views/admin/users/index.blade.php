@extends('layouts.admin')

@section('content')
<div class="space-y-8 animate-in fade-in duration-700">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">{{ __('User Management') }}</h1>
            <p class="text-slate-500 mt-1 text-lg font-medium">{{ __('Monitor and manage system access privileges.') }}</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('root.users.index') }}" class="inline-flex items-center px-5 py-2.5 bg-white border border-slate-200 text-slate-700 font-semibold rounded-xl shadow-sm hover:bg-slate-50 transition-all duration-200 group">
                <svg class="w-5 h-5 mr-2 text-slate-400 group-hover:text-slate-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
                </svg>
                {{ __('Advanced Settings') }}
            </a>
        </div>
    </div>

    <!-- Insight Cards (Optional but adds premium feel) -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-gradient-to-br from-indigo-600 to-indigo-700 rounded-2xl p-6 text-white shadow-xl shadow-indigo-200 overflow-hidden relative group">
            <div class="relative z-10">
                <p class="text-indigo-100 text-sm font-bold uppercase tracking-wider">{{ __('Total Identities') }}</p>
                <h3 class="text-4xl font-black mt-2">{{ $stats['total'] }}</h3>
            </div>
            <svg class="absolute -right-4 -bottom-4 w-32 h-32 text-white/10 transform rotate-12 group-hover:scale-110 transition-transform duration-500" fill="currentColor" viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
        </div>
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 relative overflow-hidden group">
            <p class="text-slate-400 text-sm font-bold uppercase tracking-wider">{{ __('Active Sessions') }}</p>
            <h3 class="text-4xl font-black mt-2 text-slate-900">{{ $stats['active'] }}</h3>
            <div class="absolute right-6 top-6 w-3 h-3 bg-emerald-500 rounded-full animate-pulse"></div>
        </div>
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 relative overflow-hidden group">
            <p class="text-slate-400 text-sm font-bold uppercase tracking-wider">{{ __('New Requests') }}</p>
            <h3 class="text-4xl font-black mt-2 text-slate-900">{{ $stats['new'] }}</h3>
            <svg class="absolute -right-2 -bottom-2 w-20 h-20 text-slate-50 transform -rotate-12" fill="currentColor" viewBox="0 0 24 24"><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/></svg>
        </div>
    </div>

    <!-- Main Container -->
    <div class="bg-white rounded-3xl shadow-sm border border-slate-200/60 overflow-hidden">
        <!-- Filter & Tab Toolbar -->
        <div class="p-6 border-b border-slate-100 space-y-6">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                <!-- Search -->
                <form action="{{ route('admin.users.index') }}" method="GET" class="relative w-full lg:max-w-md group">
                    <input type="hidden" name="role" value="{{ $activeRole }}">
                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-slate-400 group-focus-within:text-indigo-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </span>
                    <input type="text" name="search" value="{{ $search }}" placeholder="{{ __('Search by identity, email or clearance...') }}" 
                           class="block w-full pl-11 pr-4 py-3 bg-slate-50 border-transparent rounded-2xl focus:bg-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all duration-200 text-slate-700 placeholder-slate-400 font-medium">
                </form>

                <!-- Navigation Tabs -->
                <div class="flex p-1.5 bg-slate-100/80 rounded-2xl overflow-x-auto no-scrollbar">
                    <a href="{{ route('admin.users.index', ['role' => 'all', 'search' => $search]) }}"
                       class="px-5 py-2 rounded-xl text-sm font-bold transition-all duration-200 whitespace-nowrap {{ $activeRole == 'all' ? 'bg-white text-indigo-600 shadow-sm' : 'text-slate-500 hover:text-slate-700' }}">
                        {{ __('All Records') }}
                    </a>
                    @foreach($roles as $role)
                        <a href="{{ route('admin.users.index', ['role' => $role->name, 'search' => $search]) }}"
                           class="px-5 py-2 rounded-xl text-sm font-bold transition-all duration-200 whitespace-nowrap {{ $activeRole == $role->name ? 'bg-white text-indigo-600 shadow-sm' : 'text-slate-500 hover:text-slate-700' }}">
                            {{ __($role->display_name) }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Table View -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-100">
                <thead class="bg-slate-50/50">
                    <tr>
                        <th scope="col" class="px-8 py-4 text-left text-xs font-bold text-slate-400 uppercase tracking-[0.1em]">{{ __('Identity') }}</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-slate-400 uppercase tracking-[0.1em]">{{ __('Clearance Units') }}</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-slate-400 uppercase tracking-[0.1em]">{{ __('Status') }}</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-slate-400 uppercase tracking-[0.1em]">{{ __('Enrolled') }}</th>
                        <th scope="col" class="px-8 py-4 text-right text-xs font-bold text-slate-400 uppercase tracking-[0.1em]">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($users as $user)
                    <tr class="group hover:bg-slate-50/80 transition-colors duration-150">
                        <td class="px-8 py-5 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="relative">
                                    <div class="h-12 w-12 rounded-2xl bg-indigo-50 border-2 border-white shadow-sm flex items-center justify-center text-indigo-600 font-black text-lg group-hover:scale-110 transition-transform">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    @if($user->status == 'active')
                                    <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-emerald-500 border-2 border-white rounded-full"></div>
                                    @endif
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-black text-slate-900">{{ $user->name }}</div>
                                    <div class="text-xs font-medium text-slate-400">{{ $user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-5">
                            <div class="flex flex-wrap gap-1.5">
                                @foreach($user->roles as $role)
                                <span class="px-3 py-1 text-[10px] font-black uppercase tracking-wider rounded-lg border {{ $role->name == 'root' ? 'bg-amber-50 text-amber-600 border-amber-100' : 'bg-indigo-50 text-indigo-600 border-indigo-100' }}">
                                    {{ $role->name }}
                                </span>
                                @endforeach
                            </div>
                        </td>
                        <td class="px-6 py-5 whitespace-nowrap">
                            @php
                                $statusMap = [
                                    'active' => ['emerald', 'Active'],
                                    'inactive' => ['slate', 'Offline'],
                                    'suspended' => ['rose', 'Revoked'],
                                ];
                                [$color, $label] = $statusMap[$user->status] ?? ['indigo', $user->status];
                            @endphp
                            <div class="inline-flex items-center px-2.5 py-1 rounded-full bg-{{ $color }}-50 text-{{ $color }}-700 font-bold text-[10px] uppercase tracking-wide">
                                <span class="w-1.5 h-1.5 rounded-full bg-{{ $color }}-500 mr-2 shadow-[0_0_8px_rgba(var(--color-{{ $color }}-500),0.5)]"></span>
                                {{ $label }}
                            </div>
                        </td>
                        <td class="px-6 py-5 whitespace-nowrap">
                            <div class="text-sm font-semibold text-slate-600">{{ $user->created_at->format('M d, Y') }}</div>
                            <div class="text-[10px] text-slate-400 font-bold uppercase tracking-tight">{{ $user->created_at->diffForHumans() }}</div>
                        </td>
                        <td class="px-8 py-5 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('admin.users.edit', $user) }}" class="p-2 text-slate-400 hover:text-indigo-600 hover:bg-white rounded-xl shadow-none hover:shadow-sm border border-transparent hover:border-slate-100 transition-all">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-8 py-20 text-center">
                            <div class="flex flex-col items-center max-w-xs mx-auto">
                                <div class="w-20 h-20 bg-slate-50 rounded-3xl flex items-center justify-center mb-4 border border-slate-100 shadow-inner">
                                    <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                </div>
                                <h4 class="text-lg font-black text-slate-900">{{ __('No Identities Found') }}</h4>
                                <p class="text-sm text-slate-500 font-medium mt-1">{{ __('Try adjusting your filter parameters to locate the subject.') }}</p>
                                <a href="{{ route('admin.users.index') }}" class="mt-6 text-indigo-600 font-bold text-sm hover:underline">{{ __('Clear all filters') }}</a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Footer / Pagination -->
        @if($users->hasPages())
        <div class="px-8 py-6 bg-slate-50/50 border-t border-slate-100">
            {{ $users->links() }}
        </div>
        @endif
    </div>
</div>

<style>
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>
@endsection
