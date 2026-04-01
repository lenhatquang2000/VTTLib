@extends('layouts.admin')

@section('content')
<div class="space-y-8 animate-in fade-in duration-700">
    <!-- Action Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight leading-tight">{{ __('Privilege Controller') }}</h1>
            <p class="text-lg font-medium text-slate-500 dark:text-slate-400 mt-1">{{ __('Assign and manage security clearances for system subjects.') }}</p>
        </div>
    </div>

    <!-- Administrative Navigation Tabs -->
    <div class="flex items-center space-x-2 p-1.5 bg-slate-100 dark:bg-slate-900 rounded-[2rem] w-fit border border-slate-200 dark:border-slate-800">
        <a href="{{ route('admin.users.index') }}" class="px-8 py-3 rounded-[1.5rem] text-[10px] font-black uppercase tracking-widest transition-all {{ Route::is('admin.users.index') ? 'bg-white dark:bg-slate-800 text-indigo-600 shadow-sm' : 'text-slate-500 hover:text-slate-700 dark:hover:text-slate-300' }}">
            {{ __('Users List') }}
        </a>
        <a href="{{ route('admin.users.privileges') }}" class="px-8 py-3 rounded-[1.5rem] text-[10px] font-black uppercase tracking-widest transition-all {{ Route::is('admin.users.privileges') ? 'bg-white dark:bg-slate-800 text-indigo-600 shadow-sm' : 'text-slate-500 hover:text-slate-700 dark:hover:text-slate-300' }}">
            {{ __('Privilege Controller') }}
        </a>
        <a href="{{ route('admin.roles.index') }}" class="px-8 py-3 rounded-[1.5rem] text-[10px] font-black uppercase tracking-widest transition-all {{ Route::is('admin.roles.index') ? 'bg-white dark:bg-slate-800 text-indigo-600 shadow-sm' : 'text-slate-500 hover:text-slate-700 dark:hover:text-slate-300' }}">
            {{ __('Role Management') }}
        </a>
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
            <form action="{{ route('admin.users.privileges') }}" method="GET" class="flex flex-col lg:flex-row gap-4">
                <div class="relative flex-1 group">
                    <span class="absolute inset-y-0 left-0 pl-5 flex items-center text-slate-400 group-focus-within:text-indigo-500 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </span>
                    <input type="text" name="search" value="{{ $search }}" placeholder="{{ __('Search by identity, email or username...') }}" 
                        class="w-full bg-slate-50 dark:bg-slate-900 border-transparent dark:border-slate-700 pl-14 pr-6 py-4 rounded-[1.25rem] text-sm font-semibold text-slate-700 dark:text-slate-200 focus:bg-white dark:focus:bg-slate-950 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all duration-300">
                </div>
                
                <div class="flex flex-wrap items-center gap-3">
                    <select name="role_id" onchange="this.form.submit()" 
                        class="bg-slate-50 dark:bg-slate-900 border-transparent dark:border-slate-700 px-6 py-4 rounded-[1.25rem] text-sm font-bold text-slate-600 dark:text-slate-400 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none appearance-none cursor-pointer pr-12 bg-[url('data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20fill%3D%22none%22%20viewBox%3D%220%200%2024%2024%22%20stroke%3D%22currentColor%22%3E%3Cpath%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%20stroke-width%3D%222%22%20d%3D%22M19%209l-7%207-7-7%22%2F%3E%3C%2Fsvg%3E')] bg-[length:1.25rem] bg-[right_1rem_center] bg-no-repeat min-w-[180px]">
                        <option value="">{{ __('All Clearances') }}</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}" {{ $roleId == $role->id ? 'selected' : '' }}>{{ $role->display_name }}</option>
                        @endforeach
                    </select>

                    <div class="flex gap-2 min-w-full sm:min-w-0">
                        <button type="submit" class="flex-1 sm:flex-none px-10 py-4 bg-indigo-600 text-white text-sm font-black uppercase tracking-widest rounded-[1.25rem] hover:bg-indigo-700 transition transform active:scale-95 shadow-lg shadow-indigo-100 dark:shadow-none whitespace-nowrap">
                            {{ __('Filter') }}
                        </button>
                        @if($search || $roleId)
                            <a href="{{ route('admin.users.privileges') }}" class="px-5 py-4 bg-rose-50 dark:bg-rose-900/20 text-rose-600 dark:text-rose-400 rounded-[1.25rem] hover:bg-rose-600 hover:text-white transition flex items-center justify-center border border-rose-100 dark:border-rose-900/30">
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
                            <td class="px-6 py-6 font-mono text-xs font-bold text-slate-500 dark:text-slate-400">
                                @ {{ $ru->user->username ?? '---' }}
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
                                        {{ __('Modify Tabs') }} <span class="ml-2 px-2 py-0.5 bg-white dark:bg-slate-800 rounded-lg text-[9px] group-hover/btn:bg-white/20 transition-colors">{{ $ru->sidebars->count() }}</span>
                                    </button>
                                </div>
                            </td>
                            <td class="px-8 py-6 text-right">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('admin.users.edit', $ru->user_id) }}" class="p-2 text-slate-400 hover:text-indigo-600 transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-8 py-24 text-center text-slate-500 italic">{{ __('No privileged identities found.') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-8 py-8 border-t border-slate-100 dark:border-slate-700/50">
            {{ $roleUsers->links() }}
        </div>
    </div>
</div>

<!-- SIDEBAR MODAL -->
<div id="sidebarModal" class="fixed inset-0 z-[100] hidden">
    <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-md" onclick="closeModal('sidebarModal')"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-3xl p-4">
        <div class="bg-white dark:bg-slate-800 rounded-[3rem] shadow-2xl relative overflow-hidden max-h-[90vh] flex flex-col border border-slate-200 dark:border-slate-700 animate-modal">
            <div class="p-10 border-b border-slate-100 dark:border-slate-700 bg-white dark:bg-slate-800">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight">{{ __('Access Control Terminal') }}</h3>
                        <div class="mt-4 flex flex-wrap gap-3">
                            <span class="bg-indigo-50 dark:bg-indigo-900/20 px-4 py-1.5 rounded-xl text-[9px] font-black text-indigo-600 dark:text-indigo-400 border border-indigo-100 dark:border-indigo-900/30 uppercase tracking-widest">Target: <span id="modal-subject-name" class="ml-1 text-slate-900 dark:text-white"></span></span>
                            <span class="bg-amber-50 dark:bg-amber-900/20 px-4 py-1.5 rounded-xl text-[9px] font-black text-amber-600 dark:text-amber-400 border border-amber-100 dark:border-amber-900/30 uppercase tracking-widest">Role: <span id="modal-role-name" class="ml-1 text-slate-900 dark:text-white"></span></span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex-1 overflow-y-auto p-10 bg-slate-50/50 dark:bg-slate-900/20 custom-scrollbar">
                <form id="sidebarTabsForm" method="POST">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($sidebars as $sidebar)
                            <div class="col-span-1 md:col-span-2 space-y-4 mb-6 last:mb-0">
                                <label class="flex items-center p-5 bg-white dark:bg-slate-800 rounded-[2rem] border-2 border-slate-100 dark:border-slate-700 shadow-sm cursor-pointer hover:border-indigo-500 transition-all">
                                    <input type="checkbox" name="sidebar_ids[]" value="{{ $sidebar->id }}" class="sidebar-checkbox w-6 h-6 rounded-lg text-indigo-600">
                                    <div class="ml-4 font-black uppercase text-sm tracking-wider">{{ __($sidebar->name) }}</div>
                                </label>
                                @if($sidebar->children->isNotEmpty())
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 pl-8 border-l-2 border-slate-100 dark:border-slate-700">
                                        @foreach($sidebar->children as $child)
                                            <label class="flex items-center p-4 bg-white dark:bg-slate-800 rounded-xl border border-transparent shadow-sm hover:border-indigo-300 transition-all cursor-pointer">
                                                <input type="checkbox" name="sidebar_ids[]" value="{{ $child->id }}" class="sidebar-checkbox w-4 h-4 rounded text-indigo-600">
                                                <div class="ml-4 text-xs font-bold uppercase">{{ __($child->name) }}</div>
                                            </label>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </form>
            </div>

            <div class="p-10 border-t border-slate-100 dark:border-slate-700 bg-white dark:bg-slate-800 flex justify-end gap-4">
                <button type="button" onclick="closeModal('sidebarModal')" class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest hover:text-rose-500 transition-colors">{{ __('Discard') }}</button>
                <button type="button" onclick="document.getElementById('sidebarTabsForm').submit()" class="px-10 py-4 bg-indigo-600 text-white rounded-2xl font-black uppercase text-[10px] tracking-widest hover:bg-indigo-700 shadow-xl shadow-indigo-500/20 transition transform active:scale-95">{{ __('Commit Changes') }}</button>
            </div>
        </div>
    </div>
</div>

<script>
    function openModal(id) { document.getElementById(id).classList.remove('hidden'); }
    function closeModal(id) { document.getElementById(id).classList.add('hidden'); }

    function openSidebarSettings(roleUserId, name, role, assignedIds) {
        document.getElementById('modal-subject-name').innerText = name;
        document.getElementById('modal-role-name').innerText = role.toUpperCase();
        const form = document.getElementById('sidebarTabsForm');
        form.action = `{{ route('admin.users.tabs', ['id' => ':id']) }}`.replace(':id', roleUserId);
        const checkboxes = document.querySelectorAll('.sidebar-checkbox');
        checkboxes.forEach(cb => { cb.checked = assignedIds.includes(parseInt(cb.value)); });
        openModal('sidebarModal');
    }
</script>

<style>
    .custom-scrollbar::-webkit-scrollbar { width: 6px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
    [data-theme="dark"] .custom-scrollbar::-webkit-scrollbar-thumb { background: #334155; }
    @keyframes modal-scale { from { transform: translate(-50%, -45%) scale(0.95); opacity: 0; } to { transform: translate(-50%, -50%) scale(1); opacity: 1; } }
    .animate-modal { animation: modal-scale 0.4s cubic-bezier(0.16, 1, 0.3, 1); }
</style>
@endsection