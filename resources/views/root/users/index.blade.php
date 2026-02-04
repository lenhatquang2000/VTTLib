@extends('layouts.root')

@section('content')
    <div class="space-y-6 animate-in fade-in duration-500">
        <!-- Action Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center p-6 bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-slate-200 dark:border-slate-700 gap-4">
            <div>
                <h2 class="text-xl font-bold text-slate-900 dark:text-white leading-tight">{{ __('User_Privilege_Management') }}</h2>
                <p class="text-sm text-slate-500 mt-1">{{ __('Assign and manage security clearances for system subjects.') }}</p>
            </div>
            <div class="flex flex-wrap gap-3">

                <button onclick="openModal('createUserModal')"
                    class="inline-flex items-center px-5 py-2.5 bg-indigo-600 text-white text-sm font-bold rounded-xl hover:bg-indigo-700 shadow-lg shadow-indigo-100 dark:shadow-none transition transform active:scale-95">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    {{ __('Khởi tạo đối tượng mới') }}
                </button>
                <a href="{{ route('root.users.assign') }}"
                    class="inline-flex items-center px-5 py-2.5 bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-200 text-sm font-bold rounded-xl hover:bg-slate-200 dark:hover:bg-slate-600 transition transform active:scale-95">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                    {{ __('Add_Role_To_Identity') }}
                </a>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-800 p-4 rounded-3xl border border-slate-200 dark:border-slate-700 shadow-sm">
            <form action="{{ route('root.users.index') }}" method="GET" class="flex flex-col md:flex-row gap-3">
                <div class="relative flex-1">
                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </span>
                    <input type="text" name="search" value="{{ $search }}" placeholder="{{ __('Search_User') }}..." 
                        class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 pl-11 pr-4 py-2.5 rounded-2xl text-sm focus:ring-2 focus:ring-indigo-500 outline-none transition">
                </div>
                
                <select name="role_id" onchange="this.form.submit()" 
                    class="bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 px-4 py-2.5 rounded-2xl text-sm focus:ring-2 focus:ring-indigo-500 outline-none transition appearance-none cursor-pointer pr-10 bg-[url('data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20fill%3D%22none%22%20viewBox%3D%220%200%2024%2024%22%20stroke%3D%22currentColor%22%3E%3Cpath%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%20stroke-width%3D%222%22%20d%3D%22M19%209l-7%207-7-7%22%2F%3E%3C%2Fsvg%3E')] bg-[length:1.25rem] bg-[right_0.75rem_center] bg-no-repeat min-w-[150px]">
                    <option value="">{{ __('Tất cả vai trò') }}</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->id }}" {{ $roleId == $role->id ? 'selected' : '' }}>{{ $role->display_name }}</option>
                    @endforeach
                </select>

                <select name="per_page" onchange="this.form.submit()" 
                    class="bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 px-4 py-2.5 rounded-2xl text-sm focus:ring-2 focus:ring-indigo-500 outline-none transition appearance-none cursor-pointer pr-10 bg-[url('data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20fill%3D%22none%22%20viewBox%3D%220%200%2024%2024%22%20stroke%3D%22currentColor%22%3E%3Cpath%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%20stroke-width%3D%222%22%20d%3D%22M19%209l-7%207-7-7%22%2F%3E%3C%2Fsvg%3E')] bg-[length:1.25rem] bg-[right_0.75rem_center] bg-no-repeat">
                    @foreach([10, 20, 50, 100] as $val)
                        <option value="{{ $val }}" {{ $perPage == $val ? 'selected' : '' }}>{{ $val }} {{ __('Per_Page') }}</option>
                    @endforeach
                </select>

                <div class="flex gap-2">
                    <button type="submit" class="flex-1 md:flex-none px-8 py-2.5 bg-indigo-600 text-white text-sm font-bold rounded-2xl hover:bg-indigo-700 shadow-md shadow-indigo-100 dark:shadow-none transition">
                        {{ __('Filter') }}
                    </button>
                    @if($search || $roleId)
                        <a href="{{ route('root.users.index') }}" class="px-4 py-2.5 bg-slate-100 dark:bg-slate-700 text-slate-500 hover:text-rose-500 rounded-2xl transition flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Main Table -->
        <div class="bg-white dark:bg-slate-800 rounded-3xl overflow-hidden border border-slate-200 dark:border-slate-700 shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead>
                        <tr class="bg-slate-50 dark:bg-slate-900/50 text-slate-500 dark:text-slate-400 border-b border-slate-200 dark:border-slate-700 uppercase text-[10px] font-bold tracking-widest">
                            <th class="p-5">{{ __('Reference_ID') }}</th>
                            <th class="p-5">{{ __('Subject_Identity') }}</th>
                            <th class="p-5">{{ __('Username') }}</th>
                            <th class="p-5">{{ __('Assigned_Role') }}</th>
                            <th class="p-5">{{ __('Access_Permissions') }}</th>
                            <th class="p-5 text-right">{{ __('Operations') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700/50">
                        @foreach($roleUsers as $ru)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition">
                                <td class="p-5 font-mono text-xs text-slate-400">#{{ str_pad($ru->id, 5, '0', STR_PAD_LEFT) }}</td>
                                <td class="p-5">
                                    <div class="text-slate-900 dark:text-white font-bold truncate max-w-[150px]" title="{{ $ru->user->name }}">{{ $ru->user->name }}</div>
                                    <div class="text-[11px] text-slate-500 font-medium truncate max-w-[150px]" title="{{ $ru->user->email }}">{{ $ru->user->email }}</div>
                                </td>
                                <td class="p-5">
                                    <span class="text-slate-700 dark:text-slate-300 font-mono text-xs">{{ $ru->user->username ?? '---' }}</span>
                                </td>
                                <td class="p-5">
                                    @php
                                        $roleClass = match($ru->role->name) {
                                            'root' => 'bg-rose-50 dark:bg-rose-900/20 text-rose-600 dark:text-rose-400 border-rose-100 dark:border-rose-900/30',
                                            'admin' => 'bg-amber-50 dark:bg-amber-900/20 text-amber-600 dark:text-amber-400 border-amber-100 dark:border-amber-900/30',
                                            'visitor' => 'bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400 border-emerald-100 dark:border-emerald-900/30',
                                            default => 'bg-slate-50 dark:bg-slate-700 text-slate-500 dark:text-slate-400 border-slate-200'
                                        };
                                    @endphp
                                    <span class="inline-flex items-center px-3 py-1 border rounded-lg text-[10px] uppercase font-heavy tracking-wider {{ $roleClass }}">
                                        {{ $ru->role->display_name }}
                                    </span>
                                </td>
                                <td class="p-5">
                                    <div class="flex items-center space-x-2">
                                        <button
                                            onclick="openSidebarSettings('{{ $ru->id }}', '{{ $ru->user->name }}', '{{ $ru->role->name }}', {{ $ru->sidebars->pluck('sidebar_id') }})"
                                            class="inline-flex items-center px-4 py-2 bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600 dark:text-indigo-400 text-xs font-bold rounded-xl border border-indigo-100 dark:border-indigo-900/30 hover:bg-indigo-600 hover:text-white transition group shadow-sm whitespace-nowrap">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path></svg>
                                            {{ __('Modify_Tabs') }} <span class="ml-2 px-1.5 py-0.5 bg-white dark:bg-slate-800 rounded-md text-[10px]">{{ $ru->sidebars->count() }}</span>
                                        </button>
                                        
                                        <form action="{{ route('root.users.tabs.sync', $ru->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" 
                                                class="inline-flex items-center p-2 bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400 rounded-xl border border-emerald-100 dark:border-emerald-900/30 hover:bg-emerald-600 hover:text-white transition shadow-sm"
                                                title="{{ __('Sync_from_Template') }}">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                                <td class="p-5 text-right">
                                    <div class="flex justify-end items-center space-x-2">
                                        @if($ru->user_id !== Auth::id())
                                            <a href="{{ route('root.users.edit', $ru->user_id) }}" 
                                                class="inline-flex items-center p-2.5 bg-slate-50 dark:bg-slate-700/50 text-slate-600 dark:text-slate-400 rounded-xl hover:bg-indigo-600 hover:text-white transition group shadow-sm"
                                                title="{{ __('Edit') }}">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                            </a>
                                            <form action="{{ route('root.users.roles.remove', $ru->id) }}" method="POST"
                                                onsubmit="return confirm('CRITICAL: Terminate this security clearance?')" class="inline">
                                                @csrf @method('DELETE')
                                                <button type="submit"
                                                    class="inline-flex items-center p-2.5 bg-amber-50 dark:bg-amber-900/20 text-amber-600 dark:text-amber-400 rounded-xl hover:bg-amber-600 hover:text-white transition shadow-sm"
                                                    title="{{ __('Revoke_Role') }}">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636"></path></svg>
                                                </button>
                                            </form>
                                            <form action="{{ route('root.users.destroy', $ru->user_id) }}" method="POST"
                                                onsubmit="return confirm('CRITICAL: Delete this user permanently?')" class="inline">
                                                @csrf @method('DELETE')
                                                <button type="submit"
                                                    class="inline-flex items-center p-2.5 bg-rose-50 dark:bg-rose-900/20 text-rose-600 dark:text-rose-400 rounded-xl hover:bg-rose-600 hover:text-white transition shadow-sm" 
                                                    title="{{ __('Delete') }}">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </form>
                                        @else
                                            <span class="inline-flex items-center px-3 py-1 bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 text-xs font-bold rounded-lg">{{ __('Current_User') }}</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-8 py-5 bg-slate-50 dark:bg-slate-900/30 border-t border-slate-100 dark:border-slate-700 flex flex-col md:flex-row justify-between items-center gap-4">
                <div class="text-xs text-slate-500 font-bold uppercase tracking-wider">
                    {{ __('Showing') }} <span class="text-slate-900 dark:text-white">{{ $roleUsers->firstItem() ?? 0 }}</span> - <span class="text-slate-900 dark:text-white">{{ $roleUsers->lastItem() ?? 0 }}</span> <span class="mx-1">/</span> {{ $roleUsers->total() }} USER SEQUENCES
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
            <div class="bg-white dark:bg-slate-800 rounded-[3rem] shadow-2xl relative overflow-hidden transition-all border border-slate-200 dark:border-slate-700">
                <div class="h-2 w-full bg-indigo-600"></div>
                <div class="p-10">
                    <div class="flex justify-between items-center mb-8">
                        <h3 class="text-2xl font-bold text-slate-900 dark:text-white tracking-tight truncate whitespace-nowrap">{{ __('Khởi tạo đối tượng mới') }}</h3>
                        <button onclick="closeModal('createUserModal')" class="p-2 bg-slate-100 dark:bg-slate-700 text-slate-400 hover:text-rose-500 rounded-full transition-colors flex-shrink-0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>
                    
                    <form action="{{ route('root.users.store') }}" method="POST" class="space-y-6">
                        @csrf
                        <input type="hidden" name="max_id" id="max_id_input">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest pl-1 block truncate whitespace-nowrap">{{ __('Subject_Name') }} (Tên đối tượng)</label>
                                <input type="text" name="name" id="name_input" required class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-2xl p-4 text-sm font-medium focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all">
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest pl-1 block truncate whitespace-nowrap">{{ __('Username') }} (Tên định danh)</label>
                                <input type="text" name="username" id="username_input" required class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-2xl p-4 text-sm font-medium focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all">
                                <p id="username_status" class="text-[10px] font-bold mt-1 pl-1 hidden"></p>
                            </div>
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest pl-1 block truncate whitespace-nowrap">{{ __('Communication_Relay') }} (Email)</label>
                            <input type="email" name="email" required class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-2xl p-4 text-sm font-medium focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all">
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest pl-1 block truncate whitespace-nowrap">{{ __('Security_Cipher') }} (Mật khẩu)</label>
                                <input type="password" name="password" id="password_input" required class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-2xl p-4 text-sm font-medium focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all">
                                <div id="password_requirements" class="mt-2 space-y-1 px-1 hidden">
                                    <p id="req_length" class="text-[9px] font-bold uppercase transition-colors text-slate-400">• Tối thiểu 8 ký tự</p>
                                    <p id="req_case" class="text-[9px] font-bold uppercase transition-colors text-slate-400">• Chứa chữ hoa & chữ thường</p>
                                    <p id="req_number" class="text-[9px] font-bold uppercase transition-colors text-slate-400">• Chứa ít nhất 1 con số</p>
                                    <p id="req_symbol" class="text-[9px] font-bold uppercase transition-colors text-slate-400">• Chứa ít nhất 1 ký tự đặc biệt</p>
                                </div>
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest pl-1 block truncate whitespace-nowrap">{{ __('Clearance_Level') }} (Vai trò)</label>
                                <select name="role_id" required class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-2xl p-4 text-sm font-medium focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all appearance-none bg-[url('data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20fill%3D%22none%22%20viewBox%3D%220%200%2024%2024%22%20stroke%3D%22currentColor%22%3E%3Cpath%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%20stroke-width%3D%222%22%20d%3D%22M19%209l-7%207-7-7%22%2F%3E%3C%2Fsvg%3E')] bg-[length:1.25rem] bg-[right_1rem_center] bg-no-repeat pr-12">
                                    @foreach($roles as $role)
                                        <option value="{{ $role->id }}">{{ $role->display_name }} ({{ strtoupper($role->name) }})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="flex gap-4 pt-8">
                            <button type="button" onclick="closeModal('createUserModal')" class="flex-1 py-4 bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 rounded-2xl text-[10px] font-black uppercase tracking-[0.2em] hover:bg-slate-200 transition">{{ __('Abort') }}</button>
                            <button type="submit" class="flex-1 py-4 bg-indigo-600 text-white rounded-2xl text-[10px] font-black uppercase tracking-[0.2em] hover:bg-indigo-700 shadow-xl shadow-indigo-100 dark:shadow-none transition transform active:scale-95 whitespace-nowrap">{{ __('Khởi tạo') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>



    <!-- SIDEBAR SETTINGS MODAL -->
    <div id="sidebarModal" class="fixed inset-0 z-[100] hidden">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-md" onclick="closeModal('sidebarModal')"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-2xl p-4">
            <div class="bg-white dark:bg-slate-800 rounded-[4rem] shadow-2xl relative overflow-hidden max-h-[90vh] flex flex-col border border-slate-200 dark:border-slate-700">
                <div class="p-10 border-b border-slate-100 dark:border-slate-700 bg-white dark:bg-slate-800 z-10">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="text-3xl font-bold text-slate-900 dark:text-white leading-tight tracking-tight">{{ __('Granular_Access_Controller') }}</h3>
                            <div class="mt-4 flex items-center space-x-3">
                                <span class="bg-indigo-50 dark:bg-indigo-900/20 px-4 py-1.5 rounded-full text-[10px] font-black text-indigo-600 dark:text-indigo-400 uppercase tracking-widest border border-indigo-100 dark:border-indigo-900/30">Target: <span id="modal-subject-name"></span></span>
                                <span class="bg-indigo-50 dark:bg-indigo-900/20 px-4 py-1.5 rounded-full text-[10px] font-black text-indigo-600 dark:text-indigo-400 uppercase tracking-widest border border-indigo-100 dark:border-indigo-900/30">Role: <span id="modal-role-name"></span></span>
                            </div>
                        </div>
                        <button onclick="closeModal('sidebarModal')" class="p-3 bg-slate-100 dark:bg-slate-700 text-slate-400 hover:text-rose-500 rounded-full transition-all">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>

                    <div class="mt-8">
                        <div class="relative group">
                            <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400 group-focus-within:text-indigo-500 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            </span>
                            <input type="text" id="tabSearch" placeholder="{{ __('Search_Tab') }}..." 
                                class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-100 dark:border-slate-700 rounded-[1.5rem] p-4 pl-12 text-sm font-medium outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all">
                        </div>
                    </div>
                </div>

                <div class="flex-1 overflow-y-auto p-10 bg-slate-50/50 dark:bg-slate-950/20 custom-scrollbar">
                    <form id="sidebarTabsForm" method="POST">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5" id="tabsGrid">
                            @foreach($sidebars as $sidebar)
                                <div class="col-span-1 md:col-span-2 space-y-4 mb-6 last:mb-0">
                                    <label class="flex items-center p-5 bg-white dark:bg-slate-800 rounded-[2rem] border border-slate-200 dark:border-slate-700 shadow-sm cursor-pointer hover:border-indigo-500 hover:shadow-xl hover:shadow-indigo-500/5 transition-all group tab-item">
                                        <input type="checkbox" name="sidebar_ids[]" value="{{ $sidebar->id }}"
                                            class="sidebar-checkbox w-6 h-6 rounded-lg border-slate-300 dark:border-slate-600 text-indigo-600 focus:ring-indigo-500 transition shadow-sm">
                                        <div class="ml-5">
                                            <span class="block text-sm font-black text-slate-900 dark:text-white uppercase tracking-wider tab-name">{{ __($sidebar->name) }}</span>
                                            <span class="block text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1.5">Primary Entry Point</span>
                                        </div>
                                    </label>
                                    
                                    @if($sidebar->children->isNotEmpty())
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pl-10">
                                            @foreach($sidebar->children as $child)
                                                <label class="flex items-center p-4 bg-white dark:bg-slate-800 rounded-[1.5rem] border border-slate-100 dark:border-slate-700 cursor-pointer hover:border-indigo-400 hover:shadow-lg hover:shadow-indigo-500/5 transition-all tab-item">
                                                    <input type="checkbox" name="sidebar_ids[]" value="{{ $child->id }}"
                                                        class="sidebar-checkbox w-5 h-5 rounded-md border-slate-300 dark:border-slate-600 text-indigo-600 focus:ring-indigo-500 transition">
                                                    <div class="ml-4">
                                                        <span class="block text-xs font-bold text-slate-700 dark:text-slate-200 uppercase tracking-tight tab-name">{{ __($child->name) }}</span>
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

                <div class="p-10 border-t border-slate-100 dark:border-slate-700 bg-white dark:bg-slate-800 flex flex-col sm:flex-row justify-end gap-4">
                    <button type="button" onclick="closeModal('sidebarModal')" class="px-8 py-4 text-xs font-black text-slate-400 uppercase tracking-[0.2em] hover:text-slate-900 transition">{{ __('Close') }}</button>
                    <button type="button" onclick="document.getElementById('sidebarTabsForm').submit()"
                        class="px-12 py-4.5 bg-indigo-600 text-white rounded-[1.5rem] font-black uppercase text-xs tracking-[0.2em] hover:bg-indigo-700 transition transform active:scale-95 shadow-2xl shadow-indigo-500/20 dark:shadow-none">{{ __('Synchronize_Permissions') }}</button>
                </div>
            </div>
        </div>
    </div>

    <style>
        .custom-pagination nav { display: flex; gap: 0.35rem; }
        .custom-pagination span, .custom-pagination a { 
            padding: 0.65rem 0.95rem; border: none; border-radius: 1rem; color: #64748b; font-size: 0.8rem; font-weight: 800; background: #f8fafc; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .custom-pagination a:hover { background: #e2e8f0; color: #0f172a; transform: translateY(-2px); }
        .custom-pagination .active span { background: #4f46e5; color: #fff; box-shadow: 0 10px 15px -3px rgba(79, 70, 229, 0.4); }
        
        [data-theme="dark"] .custom-pagination span, [data-theme="dark"] .custom-pagination a { background: #1e293b; color: #94a3b8; }
        [data-theme="dark"] .custom-pagination a:hover { background: #334155; color: #fff; }

        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 20px; }
        [data-theme="dark"] .custom-scrollbar::-webkit-scrollbar-thumb { background: #334155; }
        
        @keyframes modal-scale { from { transform: translate(-50%, -45%) scale(0.95); opacity: 0; } to { transform: translate(-50%, -50%) scale(1); opacity: 1; } }
        .animate-modal { animation: modal-scale 0.4s cubic-bezier(0.34, 1.56, 0.64, 1); }
    </style>

    <script>
        function openModal(id) {
            const m = document.getElementById(id);
            m.classList.remove('hidden');
            m.querySelector('.relative').classList.add('animate-modal');
            document.body.style.overflow = 'hidden';
        }

        function closeModal(id) {
            const m = document.getElementById(id);
            m.classList.add('hidden');
            m.querySelector('.relative').classList.remove('animate-modal');
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



        // Search Tabs in Modal
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

        // Username Generation Logic
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
                // Generation logic: first letter of each word + last word
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
            }, 1000);
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
                    usernameStatus.innerText = `Username đã tồn tại, tự động chuyển thành: ${currentGeneratedUsername}`;
                    usernameStatus.className = 'text-[10px] font-bold mt-1 pl-1 text-amber-500';
                    usernameStatus.classList.remove('hidden');
                } else {
                    currentGeneratedUsername = username;
                    maxIdInput.value = data.max_id;
                    usernameInput.value = currentGeneratedUsername;
                    usernameStatus.innerText = 'Username hợp lệ';
                    usernameStatus.className = 'text-[10px] font-bold mt-1 pl-1 text-emerald-500';
                    usernameStatus.classList.remove('hidden');
                }
            } catch (error) {
                console.error('Error validating username:', error);
            }
        }

        usernameInput?.addEventListener('input', function() {
            if (this.value !== currentGeneratedUsername) {
                maxIdInput.value = ''; // User changed it manually, don't send max_id
                usernameStatus.classList.add('hidden');
            } else {
                // If they change it back to exactly what we generated, restore max_id logic if needed
                // But generally change event or re-validation would handle this.
            }
        });

        usernameInput?.addEventListener('change', function() {
            if (this.value !== currentGeneratedUsername) {
                currentGeneratedUsername = ''; // Break link
                maxIdInput.value = '';
                validateUsername(this.value);
            }
        });

        // Password Validation Logic
        const passwordInput = document.getElementById('password_input');
        const passwordReqs = document.getElementById('password_requirements');
        const reqLength = document.getElementById('req_length');
        const reqCase = document.getElementById('req_case');
        const reqNumber = document.getElementById('req_number');
        const reqSymbol = document.getElementById('req_symbol');

        passwordInput?.addEventListener('focus', () => passwordReqs.classList.remove('hidden'));

        passwordInput?.addEventListener('input', function() {
            const val = this.value;
            
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