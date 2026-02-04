@extends('layouts.root')

@section('content')
    <div class="space-y-8 animate-in fade-in duration-700">
        <!-- Action Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
            <div>
                <h1 class="text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight leading-tight">{{ __('User_Privilege_Management') }}</h1>
                <p class="text-lg font-medium text-slate-500 mt-1">{{ __('Assign and manage security clearances for system subjects.') }}</p>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('root.users.index') }}"
                    class="inline-flex items-center px-6 py-3 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-200 text-sm font-bold rounded-2xl border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700 transition shadow-sm transform active:scale-95">
                    <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    {{ __('Identity Management') }}
                </a>
            </div>
        </div>

        <!-- Insight Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-gradient-to-br from-indigo-600 to-indigo-700 rounded-[2rem] p-8 text-white shadow-2xl shadow-indigo-500/20 overflow-hidden relative group">
                <div class="relative z-10">
                    <p class="text-indigo-100 text-[10px] font-black uppercase tracking-[0.2em]">{{ __('Total Identities') }}</p>
                    <h3 class="text-5xl font-black mt-3">{{ $stats['total'] }}</h3>
                </div>
                <svg class="absolute -right-6 -bottom-6 w-40 h-40 text-white/10 transform rotate-12 group-hover:scale-110 transition-transform duration-700" fill="currentColor" viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
            </div>
            <div class="bg-white dark:bg-slate-800 rounded-[2rem] p-8 shadow-sm border border-slate-200 dark:border-slate-700 relative overflow-hidden group">
                <p class="text-slate-400 dark:text-slate-500 text-[10px] font-black uppercase tracking-[0.2em]">{{ __('Active Sessions') }}</p>
                <h3 class="text-5xl font-black mt-3 text-slate-900 dark:text-white">{{ $stats['active'] }}</h3>
                <div class="absolute right-8 top-8 w-4 h-4 bg-emerald-500 rounded-full animate-pulse shadow-[0_0_15px_rgba(16,185,129,0.5)]"></div>
            </div>
            <div class="bg-white dark:bg-slate-800 rounded-[2rem] p-8 shadow-sm border border-slate-200 dark:border-slate-700 relative overflow-hidden group">
                <p class="text-slate-400 dark:text-slate-500 text-[10px] font-black uppercase tracking-[0.2em]">{{ __('New Requests') }}</p>
                <h3 class="text-5xl font-black mt-3 text-slate-900 dark:text-white">{{ $stats['new'] }}</h3>
                <svg class="absolute -right-4 -bottom-4 w-28 h-28 text-slate-50 dark:text-slate-700/50 transform -rotate-12 group-hover:rotate-0 transition-transform duration-500" fill="currentColor" viewBox="0 0 24 24"><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/></svg>
            </div>
        </div>

        <!-- Main Content -->
        <div class="bg-white dark:bg-slate-800 rounded-[2.5rem] shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
            <!-- Filter Bar -->
            <div class="p-8 border-b border-slate-100 dark:border-slate-700/50">
                <form action="{{ route('root.users.privileges') }}" method="GET" class="flex flex-col lg:flex-row gap-4">
                    <div class="relative flex-1 group">
                        <span class="absolute inset-y-0 left-0 pl-5 flex items-center text-slate-400 group-focus-within:text-indigo-500 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </span>
                        <input type="text" name="search" value="{{ $search }}" placeholder="{{ __('Search by identity, email or username...') }}" 
                            class="w-full bg-slate-50 dark:bg-slate-900 border-transparent dark:border-slate-700 pl-14 pr-6 py-4 rounded-[1.25rem] text-sm font-semibold text-slate-700 dark:text-slate-200 focus:bg-white dark:focus:bg-slate-950 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all duration-300">
                    </div>
                    
                    <div class="flex flex-wrap items-center gap-3">
                        <select name="role_id" onchange="this.form.submit()" 
                            class="bg-slate-50 dark:bg-slate-900 border-transparent dark:border-slate-700 px-6 py-4 rounded-[1.25rem] text-sm font-bold text-slate-600 dark:text-slate-400 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition appearance-none cursor-pointer pr-12 bg-[url('data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20fill%3D%22none%22%20viewBox%3D%220%200%2024%2024%22%20stroke%3D%22currentColor%22%3E%3Cpath%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%20stroke-width%3D%222%22%20d%3D%22M19%209l-7%207-7-7%22%2F%3E%3C%2Fsvg%3E')] bg-[length:1.25rem] bg-[right_1rem_center] bg-no-repeat min-w-[180px]">
                            <option value="">{{ __('All Clearances') }}</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}" {{ $roleId == $role->id ? 'selected' : '' }}>{{ $role->display_name }}</option>
                            @endforeach
                        </select>

                        <select name="per_page" onchange="this.form.submit()" 
                            class="bg-slate-50 dark:bg-slate-900 border-transparent dark:border-slate-700 px-6 py-4 rounded-[1.25rem] text-sm font-bold text-slate-600 dark:text-slate-400 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition appearance-none cursor-pointer pr-12 bg-[url('data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20fill%3D%22none%22%20viewBox%3D%220%200%2024%2024%22%20stroke%3D%22currentColor%22%3E%3Cpath%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%20stroke-width%3D%222%22%20d%3D%22M19%209l-7%207-7-7%22%2F%3E%3C%2Fsvg%3E')] bg-[length:1.25rem] bg-[right_1rem_center] bg-no-repeat">
                            @foreach([10, 20, 50, 100] as $val)
                                <option value="{{ $val }}" {{ $perPage == $val ? 'selected' : '' }}>{{ $val }} / Page</option>
                            @endforeach
                        </select>

                        <div class="flex gap-2 min-w-full sm:min-w-0">
                            <button type="submit" class="flex-1 sm:flex-none px-10 py-4 bg-indigo-600 text-white text-sm font-black uppercase tracking-widest rounded-[1.25rem] hover:bg-indigo-700 transition transform active:scale-95 shadow-lg shadow-indigo-100 dark:shadow-none whitespace-nowrap">
                                {{ __('Filter') }}
                            </button>
                            @if($search || $roleId)
                                <a href="{{ route('root.users.index') }}" class="px-5 py-4 bg-rose-50 dark:bg-rose-900/20 text-rose-600 dark:text-rose-400 rounded-[1.25rem] hover:bg-rose-600 hover:text-white transition flex items-center justify-center border border-rose-100 dark:border-rose-900/30">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </a>
                            @endif
                        </div>
                    </div>
                </form>
            </div>

            <!-- Main Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead>
                        <tr class="bg-slate-50/80 dark:bg-slate-900/50 text-slate-400 dark:text-slate-500 border-b border-slate-100 dark:border-slate-700/50 uppercase text-[10px] font-black tracking-[0.2em]">
                            <th class="px-8 py-6">{{ __('Identity') }}</th>
                            <th class="px-6 py-6">{{ __('Username') }}</th>
                            <th class="px-6 py-6">{{ __('Clearance Level') }}</th>
                            <th class="px-6 py-6">{{ __('Status / Permissions') }}</th>
                            <th class="px-8 py-6 text-right">{{ __('Operations') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 dark:divide-slate-700/50">
                        @forelse($roleUsers as $ru)
                            <tr class="group hover:bg-slate-50/50 dark:hover:bg-slate-900/30 transition-all duration-200">
                                <td class="px-8 py-6 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="relative group-hover:scale-110 transition-transform duration-300">
                                            <div class="h-12 w-12 rounded-2xl bg-indigo-50 dark:bg-indigo-900/30 border-2 border-white dark:border-slate-800 shadow-sm flex items-center justify-center text-indigo-600 dark:text-indigo-400 font-black text-lg uppercase">
                                                {{ substr($ru->user->name, 0, 1) }}
                                            </div>
                                            <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-emerald-500 border-2 border-white dark:border-slate-800 rounded-full shadow-sm"></div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-black text-slate-900 dark:text-white leading-tight">{{ $ru->user->name }}</div>
                                            <div class="text-xs font-bold text-slate-400 mt-0.5">{{ $ru->user->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-6">
                                    <span class="text-slate-500 dark:text-slate-400 font-mono text-xs font-bold">@ {{ $ru->user->username ?? '---' }}</span>
                                </td>
                                <td class="px-6 py-6">
                                    @php
                                        $roleClass = match($ru->role->name) {
                                            'root' => 'bg-rose-50 dark:bg-rose-900/30 text-rose-600 dark:text-rose-400 border-rose-100 dark:border-rose-900/30',
                                            'admin' => 'bg-amber-50 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 border-amber-100 dark:border-amber-900/30',
                                            'visitor' => 'bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 border-emerald-100 dark:border-emerald-900/30',
                                            default => 'bg-slate-50 dark:bg-slate-700 text-slate-500 dark:text-slate-400 border-slate-200'
                                        };
                                    @endphp
                                    <span class="inline-flex items-center px-4 py-1.5 border rounded-xl text-[10px] font-black uppercase tracking-[0.15em] {{ $roleClass }}">
                                        {{ $ru->role->display_name }}
                                    </span>
                                </td>
                                <td class="px-6 py-6">
                                    <div class="flex items-center gap-3">
                                        <button
                                            onclick="openSidebarSettings('{{ $ru->id }}', '{{ $ru->user->name }}', '{{ $ru->role->name }}', {{ $ru->sidebars->pluck('sidebar_id') }})"
                                            class="inline-flex items-center px-4 py-2 bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600 dark:text-indigo-400 text-[10px] font-black uppercase tracking-widest rounded-xl border border-indigo-100 dark:border-indigo-900/30 hover:bg-indigo-600 hover:text-white transition group/btn shadow-sm whitespace-nowrap">
                                            <svg class="w-3.5 h-3.5 mr-2 group-hover/btn:rotate-180 transition-transform duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path></svg>
                                            {{ __('Modify_Tabs') }} <span class="ml-2 px-2 py-0.5 bg-white dark:bg-slate-800 rounded-lg text-[9px] group-hover/btn:bg-white/20 transition-colors">{{ $ru->sidebars->count() }}</span>
                                        </button>
                                        
                                        <form action="{{ route('root.users.tabs.sync', $ru->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" 
                                                class="inline-flex items-center p-2.5 bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400 rounded-xl border border-emerald-100 dark:border-emerald-900/30 hover:bg-emerald-600 hover:text-white transition shadow-sm group/sync"
                                                title="{{ __('Sync_from_Template') }}">
                                                <svg class="w-4 h-4 group-hover/sync:rotate-180 transition-transform duration-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                                <td class="px-8 py-6 text-right">
                                    <div class="flex justify-end items-center gap-2">
                                        @if($ru->user_id !== Auth::id())
                                            <a href="{{ route('root.users.edit', $ru->user_id) }}" 
                                                class="inline-flex items-center p-3 bg-slate-50 dark:bg-slate-800 text-slate-400 dark:text-slate-500 rounded-xl hover:bg-white dark:hover:bg-slate-700 hover:text-indigo-600 dark:hover:text-indigo-400 transition-all shadow-sm group/op"
                                                title="{{ __('Edit') }}">
                                                <svg class="w-4 h-4 group-hover/op:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                            </a>
                                            <form action="{{ route('root.users.roles.remove', $ru->id) }}" method="POST"
                                                onsubmit="return confirm('CRITICAL: Terminate this security clearance?')" class="inline">
                                                @csrf @method('DELETE')
                                                <button type="submit"
                                                    class="inline-flex items-center p-3 bg-amber-50 dark:bg-amber-900/20 text-amber-400 dark:text-amber-600 rounded-xl hover:bg-amber-600 hover:text-white transition-all shadow-sm group/op"
                                                    title="{{ __('Revoke_Role') }}">
                                                    <svg class="w-4 h-4 group-hover/op:rotate-90 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636"></path></svg>
                                                </button>
                                            </form>
                                            <form action="{{ route('root.users.destroy', $ru->user_id) }}" method="POST"
                                                onsubmit="return confirm('CRITICAL: Delete this user permanently?')" class="inline">
                                                @csrf @method('DELETE')
                                                <button type="submit"
                                                    class="inline-flex items-center p-3 bg-rose-50 dark:bg-rose-900/20 text-rose-400 dark:text-rose-600 rounded-xl hover:bg-rose-600 hover:text-white transition-all shadow-sm group/op" 
                                                    title="{{ __('Delete') }}">
                                                    <svg class="w-4 h-4 group-hover/op:scale-125 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </form>
                                        @else
                                            <span class="inline-flex items-center px-4 py-2 bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 text-[10px] font-black uppercase tracking-widest rounded-xl border border-indigo-100 dark:border-indigo-900/30 shadow-sm">{{ __('Current Session') }}</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-8 py-24 text-center">
                                    <div class="flex flex-col items-center max-w-xs mx-auto">
                                        <div class="w-24 h-24 bg-slate-50 dark:bg-slate-900/50 rounded-[2.5rem] flex items-center justify-center mb-6 border border-slate-100 dark:border-slate-700 shadow-inner">
                                            <svg class="w-10 h-10 text-slate-300 dark:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                        </div>
                                        <h4 class="text-xl font-black text-slate-900 dark:text-white tracking-tight">{{ __('No Identities Found') }}</h4>
                                        <p class="text-sm font-medium text-slate-500 mt-2">{{ __('Try adjusting your filter parameters to locate the subject.') }}</p>
                                        <a href="{{ route('root.users.index') }}" class="mt-8 text-indigo-600 dark:text-indigo-400 font-black text-xs uppercase tracking-[0.2em] hover:underline">{{ __('Reset Database Filters') }}</a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Footer / Pagination -->
            <div class="px-8 py-8 bg-slate-50/50 dark:bg-slate-900/30 border-t border-slate-100 dark:border-slate-700/50 flex flex-col md:flex-row justify-between items-center gap-6">
                <div class="text-[10px] text-slate-400 font-extrabold uppercase tracking-[0.2em]">
                    {{ __('Showing') }} <span class="text-slate-900 dark:text-white">{{ $roleUsers->firstItem() ?? 0 }}</span> - <span class="text-slate-900 dark:text-white">{{ $roleUsers->lastItem() ?? 0 }}</span> <span class="mx-2">/</span> {{ $roleUsers->total() }} USER SEQUENCES
                </div>
                <div class="custom-pagination">
                    {{ $roleUsers->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Modals -->
    <!-- CREATE USER MODAL -->
    <div id="createUserModal" class="fixed inset-0 z-[100] hidden">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-md" onclick="closeModal('createUserModal')"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-2xl p-4">
            <div class="bg-white dark:bg-slate-800 rounded-[3.5rem] shadow-2xl relative overflow-hidden transition-all border border-slate-200 dark:border-slate-700 animate-modal">
                <div class="h-2.5 w-full bg-indigo-600"></div>
                <div class="p-12">
                    <div class="flex justify-between items-center mb-10">
                        <div>
                            <h3 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight leading-tight">{{ __('Initialize New Subject') }}</h3>
                            <p class="text-slate-500 font-medium mt-1">Register a new identity in the security database.</p>
                        </div>
                        <button onclick="closeModal('createUserModal')" class="p-3 bg-slate-100 dark:bg-slate-700 text-slate-400 hover:text-rose-500 rounded-full transition-all flex-shrink-0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>
                    
                    <form action="{{ route('root.users.store') }}" method="POST" class="space-y-8">
                        @csrf
                        <input type="hidden" name="max_id" id="max_id_input">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest pl-2 block">{{ __('Subject_Name') }} (Tên đối tượng)</label>
                                <input type="text" name="name" id="name_input" required 
                                    class="w-full bg-slate-50 dark:bg-slate-900 border-2 border-transparent focus:border-indigo-500 rounded-[1.25rem] p-5 text-sm font-bold text-slate-900 dark:text-white focus:ring-8 focus:ring-indigo-500/5 outline-none transition-all duration-300">
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest pl-2 block">{{ __('Username') }} (Tên định danh)</label>
                                <input type="text" name="username" id="username_input" required 
                                    class="w-full bg-slate-50 dark:bg-slate-900 border-2 border-transparent focus:border-indigo-500 rounded-[1.25rem] p-5 text-sm font-bold text-slate-900 dark:text-white focus:ring-8 focus:ring-indigo-500/5 outline-none transition-all duration-300 font-mono">
                                <p id="username_status" class="text-[10px] font-black mt-2 pl-2 hidden"></p>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest pl-2 block">{{ __('Communication_Relay') }} (Email)</label>
                            <input type="email" name="email" required 
                                class="w-full bg-slate-50 dark:bg-slate-900 border-2 border-transparent focus:border-indigo-500 rounded-[1.5rem] p-5 text-sm font-bold text-slate-900 dark:text-white focus:ring-8 focus:ring-indigo-500/5 outline-none transition-all duration-300">
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest pl-2 block">{{ __('Security_Cipher') }} (Mật khẩu)</label>
                                <input type="password" name="password" id="password_input" required 
                                    class="w-full bg-slate-50 dark:bg-slate-900 border-2 border-transparent focus:border-indigo-500 rounded-[1.25rem] p-5 text-sm font-bold text-slate-900 dark:text-white focus:ring-8 focus:ring-indigo-500/5 outline-none transition-all duration-300">
                                <div id="password_requirements" class="mt-4 space-y-2 px-2 hidden scale-in">
                                    <p id="req_length" class="text-[9px] font-black uppercase tracking-wider transition-colors text-slate-400">• Tối thiểu 8 ký tự</p>
                                    <p id="req_case" class="text-[9px] font-black uppercase tracking-wider transition-colors text-slate-400">• Chứa chữ hoa & chữ thường</p>
                                    <p id="req_number" class="text-[9px] font-black uppercase tracking-wider transition-colors text-slate-400">• Chứa ít nhất 1 con số</p>
                                    <p id="req_symbol" class="text-[9px] font-black uppercase tracking-wider transition-colors text-slate-400">• Chứa ít nhất 1 ký tự đặc biệt</p>
                                </div>
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest pl-2 block">{{ __('Clearance_Level') }} (Vai trò)</label>
                                <select name="role_id" required 
                                    class="w-full bg-slate-50 dark:bg-slate-900 border-2 border-transparent focus:border-indigo-500 rounded-[1.25rem] p-5 text-sm font-black text-slate-700 dark:text-slate-300 focus:ring-8 focus:ring-indigo-500/5 outline-none transition-all duration-300 appearance-none bg-[url('data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20fill%3D%22none%22%20viewBox%3D%220%200%2024%2024%22%20stroke%3D%22currentColor%22%3E%3Cpath%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%20stroke-width%3D%222%22%20d%3D%22M19%209l-7%207-7-7%22%2F%3E%3C%2Fsvg%3E')] bg-[length:1.25rem] bg-[right_1.5rem_center] bg-no-repeat pr-12">
                                    @foreach($roles as $role)
                                        <option value="{{ $role->id }}">{{ $role->display_name }} ({{ strtoupper($role->name) }})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="flex gap-4 pt-10">
                            <button type="button" onclick="closeModal('createUserModal')" class="flex-1 py-5 bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-300 rounded-[1.5rem] text-[10px] font-black uppercase tracking-[0.2em] hover:bg-slate-200 transition duration-300">{{ __('Abort') }}</button>
                            <button type="submit" class="flex-1 py-5 bg-indigo-600 text-white rounded-[1.5rem] text-[10px] font-black uppercase tracking-[0.2em] hover:bg-indigo-700 shadow-2xl shadow-indigo-500/30 dark:shadow-none transition transform active:scale-95 whitespace-nowrap">{{ __('Thực thi Khởi tạo') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- SIDEBAR SETTINGS MODAL -->
    <div id="sidebarModal" class="fixed inset-0 z-[100] hidden">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-md" onclick="closeModal('sidebarModal')"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-3xl p-4">
            <div class="bg-white dark:bg-slate-800 rounded-[4.5rem] shadow-2xl relative overflow-hidden max-h-[90vh] flex flex-col border border-slate-200 dark:border-slate-700 animate-modal">
                <div class="p-12 border-b border-slate-100 dark:border-slate-700 bg-white dark:bg-slate-800 z-10">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="text-4xl font-black text-slate-900 dark:text-white leading-tight tracking-tight">{{ __('Granular Access Controller') }}</h3>
                            <div class="mt-6 flex flex-wrap items-center gap-4">
                                <span class="bg-indigo-50 dark:bg-indigo-900/40 px-5 py-2 rounded-2xl text-[10px] font-black text-indigo-600 dark:text-indigo-400 uppercase tracking-widest border border-indigo-100 dark:border-indigo-900/30 shadow-sm">Target: <span id="modal-subject-name" class="ml-1 text-slate-900 dark:text-white font-black"></span></span>
                                <span class="bg-indigo-50 dark:bg-indigo-900/40 px-5 py-2 rounded-2xl text-[10px] font-black text-indigo-600 dark:text-indigo-400 uppercase tracking-widest border border-indigo-100 dark:border-indigo-900/30 shadow-sm">Role Clearance: <span id="modal-role-name" class="ml-1 text-slate-900 dark:text-white font-black"></span></span>
                            </div>
                        </div>
                        <button onclick="closeModal('sidebarModal')" class="p-4 bg-slate-100 dark:bg-slate-700 text-slate-400 hover:text-rose-500 rounded-full transition-all hover:rotate-90 duration-500">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>

                    <div class="mt-10">
                        <div class="relative group">
                            <span class="absolute inset-y-0 left-0 pl-5 flex items-center text-slate-400 group-focus-within:text-indigo-500 transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            </span>
                            <input type="text" id="tabSearch" placeholder="{{ __('Search Entry Points') }}..." 
                                class="w-full bg-slate-50 dark:bg-slate-900 border-2 border-transparent focus:border-indigo-500 rounded-[2rem] p-6 pl-16 text-sm font-bold outline-none focus:ring-8 focus:ring-indigo-500/5 transition-all duration-300">
                        </div>
                    </div>
                </div>

                <div class="flex-1 overflow-y-auto p-12 bg-slate-50/50 dark:bg-slate-950/20 custom-scrollbar">
                    <form id="sidebarTabsForm" method="POST">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6" id="tabsGrid">
                            @foreach($sidebars as $sidebar)
                                <div class="col-span-1 md:col-span-2 space-y-5 mb-8 last:mb-0">
                                    <label class="flex items-center p-6 bg-white dark:bg-slate-800 rounded-[2.5rem] border-2 border-slate-100 dark:border-slate-700 shadow-sm cursor-pointer hover:border-indigo-500 hover:shadow-2xl hover:shadow-indigo-500/10 transition-all group tab-item transform active:scale-[0.98]">
                                        <input type="checkbox" name="sidebar_ids[]" value="{{ $sidebar->id }}"
                                            class="sidebar-checkbox w-7 h-7 rounded-xl border-slate-300 dark:border-slate-600 text-indigo-600 focus:ring-indigo-500 transition-all shadow-sm">
                                        <div class="ml-6">
                                            <span class="block text-base font-black text-slate-900 dark:text-white uppercase tracking-wider tab-name">{{ __($sidebar->name) }}</span>
                                            <span class="block text-[10px] text-slate-400 font-extrabold uppercase tracking-widest mt-2">Primary Access Terminal</span>
                                        </div>
                                    </label>
                                    
                                    @if($sidebar->children->isNotEmpty())
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pl-12 border-l-4 border-slate-100 dark:border-slate-700 ml-4">
                                            @foreach($sidebar->children as $child)
                                                <label class="flex items-center p-5 bg-white dark:bg-slate-800 rounded-[2rem] border-2 border-transparent hover:border-indigo-300 dark:hover:border-indigo-800 cursor-pointer shadow-sm hover:shadow-xl transition-all tab-item transform active:scale-[0.98]">
                                                    <input type="checkbox" name="sidebar_ids[]" value="{{ $child->id }}"
                                                        class="sidebar-checkbox w-5 h-5 rounded-lg border-slate-300 dark:border-slate-600 text-indigo-600 focus:ring-indigo-500 transition-all">
                                                    <div class="ml-5">
                                                        <span class="block text-xs font-black text-slate-700 dark:text-slate-200 uppercase tracking-tight tab-name">{{ __($child->name) }}</span>
                                                    </div>
                                                </label>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </form>
                </div>

                <div class="p-12 border-t border-slate-100 dark:border-slate-700 bg-white dark:bg-slate-800 flex flex-col sm:flex-row justify-end items-center gap-6">
                    <button type="button" onclick="closeModal('sidebarModal')" class="px-10 py-4 text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] hover:text-rose-500 transition-colors focus:outline-none">{{ __('Close Terminal') }}</button>
                    <button type="button" onclick="document.getElementById('sidebarTabsForm').submit()"
                        class="px-14 py-5 bg-indigo-600 text-white rounded-[2rem] font-black uppercase text-[10px] tracking-[0.25em] hover:bg-indigo-700 shadow-2xl shadow-indigo-500/30 dark:shadow-none transition transform active:scale-95 flex items-center">
                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        {{ __('Commit Changes') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <style>
        .custom-pagination nav { display: flex; gap: 0.5rem; }
        .custom-pagination span, .custom-pagination a { 
            padding: 0.8rem 1.25rem; border: none; border-radius: 1.25rem; color: #64748b; font-size: 0.75rem; font-weight: 900; background: #f1f5f9; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .custom-pagination a:hover { background: #e2e8f0; color: #0f172a; transform: translateY(-3px) scale(1.05); box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1); }
        .custom-pagination .active span { background: #4f46e5; color: #fff; box-shadow: 0 15px 25px -5px rgba(79, 70, 229, 0.4); transform: translateY(-3px); }
        
        [data-theme="dark"] .custom-pagination span, [data-theme="dark"] .custom-pagination a { background: #1e293b; color: #94a3b8; }
        [data-theme="dark"] .custom-pagination a:hover { background: #334155; color: #fff; }

        .custom-scrollbar::-webkit-scrollbar { width: 8px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 100px; border: 3px solid transparent; background-clip: content-box; }
        [data-theme="dark"] .custom-scrollbar::-webkit-scrollbar-thumb { background: #334155; border: 3px solid transparent; background-clip: content-box; }
        
        @keyframes modal-scale { from { transform: translate(-50%, -45%) scale(0.9); opacity: 0; filter: blur(10px); } to { transform: translate(-50%, -50%) scale(1); opacity: 1; filter: blur(0); } }
        .animate-modal { animation: modal-scale 0.5s cubic-bezier(0.19, 1, 0.22, 1); }

        .scale-in { animation: scale-in 0.3s ease-out; }
        @keyframes scale-in { from { transform: scale(0.95); opacity: 0; } to { transform: scale(1); opacity: 1; } }
    </style>

    <script>
        function openModal(id) {
            const m = document.getElementById(id);
            m.classList.remove('hidden');
            m.querySelector('.animate-modal')?.classList.add('animate-modal');
            document.body.style.overflow = 'hidden';
        }

        function closeModal(id) {
            const m = document.getElementById(id);
            m.classList.add('hidden');
            document.body.style.overflow = '';
        }

        function openSidebarSettings(roleUserId, name, role, assignedIds) {
            document.getElementById('modal-subject-name').innerText = name;
            document.getElementById('modal-role-name').innerText = role.toUpperCase();

            const form = document.getElementById('sidebarTabsForm');
            form.action = `/root/users/roles/${roleUserId}/tabs`;

            const checkboxes = document.querySelectorAll('.sidebar-checkbox');
            checkboxes.forEach(cb => {
                cb.checked = assignedIds.includes(parseInt(cb.value));
            });

            openModal('sidebarModal');
        }

        // Search Entry Points in Modal
        document.getElementById('tabSearch')?.addEventListener('input', function(e) {
            const search = e.target.value.toLowerCase();
            const items = document.querySelectorAll('.tab-item');
            
            items.forEach(item => {
                const name = item.querySelector('.tab-name').innerText.toLowerCase();
                item.style.display = name.includes(search) ? '' : 'none';
            });
        });

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                document.querySelectorAll('.fixed').forEach(m => {
                    if(!m.classList.contains('hidden')) {
                        closeModal(m.id);
                    }
                });
            }
        });

        // Username Generation Logic (Enhanced)
        function removeAccents(str) {
            return str.normalize('NFD')
                      .replace(/[\u0300-\u036f]/g, '')
                      .replace(/đ/g, 'd').replace(/Đ/g, 'D');
        }

        const nameInput = document.getElementById('name_input');
        const usernameInput = document.getElementById('username_input');
        const usernameStatus = document.getElementById('username_status');
        const maxIdInput = document.getElementById('max_id_input');
        let currentGeneratedUsername = '';

        let debounceTimer;
        nameInput?.addEventListener('input', function() {
            clearTimeout(debounceTimer);
            const name = this.value.trim();
            
            if (!name) {
                usernameInput.value = '';
                currentGeneratedUsername = '';
                maxIdInput.value = '';
                usernameStatus.classList.add('hidden');
                return;
            }

            debounceTimer = setTimeout(() => {
                const words = name.split(/\s+/);
                if (words.length > 0) {
                    let baseGenerated = '';
                    for (let i = 0; i < words.length - 1; i++) {
                        baseGenerated += words[i].charAt(0);
                    }
                    baseGenerated += words[words.length - 1];
                    
                    const sanitized = removeAccents(baseGenerated).toLowerCase().replace(/[^a-z0-9]/g, '');
                    validateUsername(sanitized);
                }
            }, 800);
        });

        async function validateUsername(username) {
            if (!username) return;

            try {
                const response = await fetch(`/root/users/check-username?username=${username}`);
                const data = await response.json();
                
                if (data.exists) {
                    currentGeneratedUsername = username + (data.max_id + 1);
                    maxIdInput.value = data.max_id;
                    usernameInput.value = currentGeneratedUsername;
                    usernameStatus.innerText = `[COLLISION] Redirected to: ${currentGeneratedUsername}`;
                    usernameStatus.className = 'text-[9px] font-black mt-2 pl-2 text-amber-500 uppercase tracking-wider';
                    usernameStatus.classList.remove('hidden');
                } else {
                    currentGeneratedUsername = username;
                    maxIdInput.value = data.max_id;
                    usernameInput.value = currentGeneratedUsername;
                    usernameStatus.innerText = '[VALID] Identity Sequence Ready';
                    usernameStatus.className = 'text-[9px] font-black mt-2 pl-2 text-emerald-500 uppercase tracking-wider';
                    usernameStatus.classList.remove('hidden');
                }
            } catch (error) {
                console.error('Error validating username:', error);
            }
        }

        usernameInput?.addEventListener('input', function() {
            if (this.value !== currentGeneratedUsername) {
                maxIdInput.value = '';
                usernameStatus.classList.add('hidden');
            }
        });

        usernameInput?.addEventListener('change', function() {
            if (this.value !== currentGeneratedUsername) {
                currentGeneratedUsername = '';
                maxIdInput.value = '';
                validateUsername(this.value);
            }
        });

        // Password Validation Logic
        const passInput = document.getElementById('password_input');
        const passReqs = document.getElementById('password_requirements');
        const rLen = document.getElementById('req_length');
        const rCase = document.getElementById('req_case');
        const rNum = document.getElementById('req_number');
        const rSym = document.getElementById('req_symbol');

        passInput?.addEventListener('focus', () => passReqs.classList.remove('hidden'));

        passInput?.addEventListener('input', function() {
            const v = this.value;
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