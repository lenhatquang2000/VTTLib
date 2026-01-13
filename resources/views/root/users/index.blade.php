@extends('layouts.root')

@section('content')
    <div class="space-y-8">
        @if(session('success'))
            <div class="bg-green-900/20 border border-green-900 text-green-500 p-4 text-xs font-mono">
                [OK] {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="bg-red-900/20 border border-red-900 text-red-500 p-4 text-xs font-mono">
                [ERROR] {{ session('error') }}
            </div>
        @endif

        <!-- Action Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center bg-red-900/5 p-4 border border-red-900/20 rounded gap-4">
            <h2 class="text-sm font-bold uppercase tracking-widest">{{ __('User_Privilege_Management') }}</h2>
            <div class="flex flex-wrap gap-4">
                <button onclick="openModal('createUserModal')"
                    class="bg-red-900 text-black px-4 py-2 text-xs font-bold uppercase hover:bg-red-600 transition">
                    {{ __('Create_New_User') }}
                </button>
                <button onclick="openModal('addRoleModal')"
                    class="bg-transparent border border-red-900 text-red-500 px-4 py-2 text-xs font-bold uppercase hover:bg-red-900/10 transition">
                    {{ __('Add_Role_To_Identity') }}
                </button>
            </div>
        </div>

        <!-- Search & Filter Bar -->
        <div class="flex flex-col md:flex-row gap-4">
            <form action="{{ route('root.users.index') }}" method="GET" class="flex-1 flex gap-2">
                <input type="text" name="search" value="{{ $search }}" placeholder="{{ __('Search_User') }}..." 
                    class="flex-1 bg-black border border-red-900/30 p-2 text-xs text-red-500 focus:border-red-500 outline-none">
                
                <select name="per_page" onchange="this.form.submit()" 
                    class="bg-black border border-red-900/30 p-2 text-xs text-red-500 focus:border-red-500 outline-none">
                    @foreach([10, 20, 50, 100] as $val)
                        <option value="{{ $val }}" {{ $perPage == $val ? 'selected' : '' }}>{{ $val }} {{ __('Per_Page') }}</option>
                    @endforeach
                </select>

                <button type="submit" class="bg-red-900/20 border border-red-900 text-red-500 px-4 text-xs uppercase hover:bg-red-900/40 transition">
                    {{ __('Filter') }}
                </button>
                @if($search)
                    <a href="{{ route('root.users.index') }}" class="bg-red-900/10 border border-red-900/30 text-red-900 px-4 flex items-center text-xs uppercase hover:text-red-500 transition">
                        X
                    </a>
                @endif
            </form>
        </div>

        <!-- Main Table -->
        <div class="card-root rounded overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-xs text-left">
                    <thead class="text-[10px] text-red-900 border-b border-red-900/20 uppercase font-bold text-white">
                        <tr>
                            <th class="p-4">{{ __('Reference_ID') }}</th>
                            <th class="p-4">{{ __('Subject_Identity') }}</th>
                            <th class="p-4">{{ __('Assigned_Role') }}</th>
                            <th class="p-4">{{ __('Access_Permissions') }}</th>
                            <th class="p-4 text-right">{{ __('Operations') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-red-900/10">
                        @foreach($roleUsers as $ru)
                            <tr class="hover:bg-red-900/5 transition">
                                <td class="p-4 font-mono opacity-50">#{{ str_pad($ru->id, 5, '0', STR_PAD_LEFT) }}</td>
                                <td class="p-4">
                                    <div class="text-white font-bold">{{ $ru->user->name }}</div>
                                    <div class="text-[10px] text-red-900 font-mono">{{ $ru->user->email }}</div>
                                </td>
                                <td class="p-4">
                                    @php
                                        $roleClass = match($ru->role->name) {
                                            'root' => 'role-badge-root',
                                            'admin' => 'role-badge-admin',
                                            'visitor' => 'role-badge-visitor',
                                            default => 'bg-gray-900/20 border-gray-600 text-gray-500'
                                        };
                                    @endphp
                                    <span class="px-2 py-1 border text-[10px] uppercase font-bold {{ $roleClass }}">
                                        {{ $ru->role->display_name }} ({{ strtoupper($ru->role->name) }})
                                    </span>
                                </td>
                                <td class="p-4">
                                    @if($ru->role->name === 'admin' || $ru->role->name === 'root')
                                        <button
                                            onclick="openSidebarSettings('{{ $ru->id }}', '{{ $ru->user->name }}', '{{ $ru->role->name }}', {{ $ru->sidebars->pluck('sidebar_id') }})"
                                            class="flex items-center space-x-2 text-red-500 hover:text-white transition group focus:outline-none">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4">
                                                </path>
                                            </svg>
                                            <span
                                                class="underline decoration-red-900 decoration-dotted group-hover:decoration-red-500 hover:text-white group-hover:text-white">{{ __('Modify_Tabs') }}
                                                ({{ $ru->sidebars->count() }})</span>
                                        </button>
                                    @else
                                        <span class="text-red-900/50 italic opacity-50">{{ __('Level_Unauthorized') }}</span>
                                    @endif
                                </td>
                                <td class="p-4 text-right">
                                    @if($ru->user_id !== Auth::id())
                                        <form action="{{ route('root.users.roles.remove', $ru->id) }}" method="POST"
                                            onsubmit="return confirm('CRITICAL: Terminate this security clearance?')">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                class="text-red-900 hover:text-red-500 underline uppercase text-[10px] font-bold">
                                                {{ __('Revoke_Role') }}
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="p-4 border-t border-red-900/10 flex flex-col md:flex-row justify-between items-center gap-4">
                <div class="text-[10px] text-red-900 uppercase">
                    Showing {{ $roleUsers->firstItem() }} to {{ $roleUsers->lastItem() }} of {{ $roleUsers->total() }} sequences
                </div>
                <div class="custom-pagination">
                    {{ $roleUsers->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- CREATE USER MODAL -->
    <div id="createUserModal" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-black/80 backdrop-blur-sm" onclick="closeModal('createUserModal')"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-md">
            <div class="card-root rounded-lg p-8 shadow-2xl relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-1 bg-red-600"></div>
                <h3 class="text-lg font-bold text-white mb-6 uppercase tracking-widest border-b border-red-900/20 pb-4 text-white">
                    {{ __('Initialize_New_Subject') }}</h3>
                <form action="{{ route('root.users.store') }}" method="POST" class="space-y-6">
                    @csrf
                    <div>
                        <label class="block text-[10px] text-red-900 uppercase font-bold mb-2">{{ __('Subject_Name') }}</label>
                        <input type="text" name="name" required
                            class="w-full bg-black border border-red-900/50 p-3 text-red-500 focus:border-red-500 outline-none text-sm transition">
                    </div>
                    <div>
                        <label class="block text-[10px] text-red-900 uppercase font-bold mb-2">{{ __('Communication_Relay') }}</label>
                        <input type="email" name="email" required
                            class="w-full bg-black border border-red-900/50 p-3 text-red-500 focus:border-red-500 outline-none text-sm transition">
                    </div>
                    <div>
                        <label class="block text-[10px] text-red-900 uppercase font-bold mb-2">{{ __('Security_Cipher') }}</label>
                        <input type="password" name="password" required
                            class="w-full bg-black border border-red-900/50 p-3 text-red-500 focus:border-red-500 outline-none text-sm transition">
                    </div>
                    <div>
                        <label class="block text-[10px] text-red-900 uppercase font-bold mb-2">{{ __('Clearance_Level') }}</label>
                        <select name="role_id" required
                            class="w-full bg-black border border-red-900/50 p-3 text-red-500 focus:border-red-500 outline-none text-sm transition appearance-none cursor-pointer">
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}">{{ strtoupper($role->display_name) }} ({{ strtoupper($role->name) }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex space-x-4 pt-4">
                        <button type="button" onclick="closeModal('createUserModal')"
                            class="flex-1 bg-transparent border border-red-900 text-red-900 py-3 uppercase text-xs font-bold hover:bg-red-900/10 transition">{{ __('Abort') }}</button>
                        <button type="submit"
                            class="flex-1 bg-red-900 text-black py-3 uppercase text-xs font-bold hover:bg-red-600 transition">{{ __('Execute_Provision') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ADD ROLE MODAL -->
    <div id="addRoleModal" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-black/80 backdrop-blur-sm" onclick="closeModal('addRoleModal')"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-md">
            <div class="card-root rounded-lg p-8 shadow-2xl relative overflow-hidden">
                <h3
                    class="text-lg font-bold text-white mb-6 uppercase tracking-widest border-b border-red-900/20 pb-4 text-center text-white">
                    {{ __('Escalate_Clearance') }}</h3>
                
                <!-- Internal Search for Identities -->
                <div class="mb-4">
                    <input type="text" id="identitySearch" placeholder="{{ __('Search_User') }}..." 
                        class="w-full bg-black border border-red-900/30 p-2 text-xs text-red-500 focus:border-red-500 outline-none mb-2">
                </div>

                <form action="{{ route('root.users.roles.store') }}" method="POST" class="space-y-6">
                    @csrf
                    <div>
                        <label class="block text-[10px] text-red-900 uppercase font-bold mb-2">{{ __('Target_Identity') }}</label>
                        <select name="user_id" id="identitySelect" required
                            class="w-full bg-black border border-red-900/50 p-3 text-red-500 focus:border-red-500 outline-none text-sm transition">
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] text-red-900 uppercase font-bold mb-2">{{ __('Additional_Privilege') }}</label>
                        <select name="role_id" required
                            class="w-full bg-black border border-red-900/50 p-3 text-red-500 focus:border-red-500 outline-none text-sm transition">
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}">{{ strtoupper($role->display_name) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit"
                        class="w-full bg-red-900 text-black py-3 uppercase text-xs font-bold hover:bg-red-600 transition tracking-widest">{{ __('Inject_Security_Token') }}</button>
                </form>
            </div>
        </div>
    </div>

    <!-- SIDEBAR SETTINGS MODAL -->
    <div id="sidebarModal" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-black/80 backdrop-blur-sm" onclick="closeModal('sidebarModal')"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-xl">
            <div class="card-root rounded-lg p-8 shadow-2xl relative">
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <h3 class="text-xl font-bold text-white uppercase tracking-tighter">{{ __('Granular_Access_Controller') }}</h3>
                        <p class="text-[10px] text-red-900 mt-1 uppercase">Target: <span id="modal-subject-name"
                                class="text-red-500"></span> | Role: <span id="modal-role-name" class="text-red-500"></span>
                        </p>
                    </div>
                    <button onclick="closeModal('sidebarModal')" class="text-red-900 hover:text-red-500 transition focus:outline-none">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                            </path>
                        </svg>
                    </button>
                </div>

                <!-- Internal Tab Search -->
                <div class="mb-4">
                    <input type="text" id="tabSearch" placeholder="{{ __('Search_Tab') }}..." 
                        class="w-full bg-black border border-red-900/30 p-2 text-xs text-red-500 focus:border-red-500 outline-none">
                </div>

                <form id="sidebarTabsForm" method="POST" class="space-y-6">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 max-h-[300px] overflow-y-auto pr-2 custom-scrollbar" id="tabsGrid">
                        @foreach($sidebars as $sidebar)
                            <label
                                class="group relative flex items-center p-4 bg-black/40 border border-red-900/20 rounded cursor-pointer hover:border-red-600 transition-all tab-item">
                                <input type="checkbox" name="sidebar_ids[]" value="{{ $sidebar->id }}"
                                    class="sidebar-checkbox w-4 h-4 rounded border-red-900 bg-black text-red-600 focus:ring-red-500 focus:ring-offset-black transition">
                                <div class="ml-4">
                                    <span class="block text-xs font-bold text-red-500 uppercase tab-name">{{ $sidebar->name }}</span>
                                    <span class="block text-[9px] text-red-900 mt-0.5">{{ $sidebar->route_name }}</span>
                                </div>
                            </label>
                        @endforeach
                    </div>

                    <div class="pt-6 border-t border-red-900/20 flex justify-end space-x-4">
                        <button type="button" onclick="closeModal('sidebarModal')"
                            class="px-6 py-2 text-xs font-bold uppercase text-red-900 hover:text-red-500 transition">{{ __('Close') }}</button>
                        <button type="submit"
                            class="px-8 py-3 bg-red-900 text-black font-bold uppercase text-xs tracking-widest hover:bg-red-600 transition shadow-[0_0_15px_rgba(255,0,0,0.2)]">{{ __('Synchronize_Permissions') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        .custom-pagination nav { display: flex; gap: 0.25rem; }
        .custom-pagination span, .custom-pagination a { 
            padding: 0.5rem 0.75rem; border: 1px solid #450a0a; color: #ef4444; font-size: 0.7rem; font-weight: bold; background: #000;
        }
        .custom-pagination .active span { background: #7f1d1d; color: #000; }
        .custom-pagination a:hover { border-color: #ef4444; }
        [data-theme="light"] .custom-pagination span, [data-theme="light"] .custom-pagination a { 
            border-color: #e2e8f0; color: #0f172a; background: #fff;
        }
        [data-theme="light"] .custom-pagination .active span { background: #3b82f6; color: #fff; border-color: #3b82f6; }
        
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: #1a0202; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #450a0a; border-radius: 10px; }
        [data-theme="light"] .custom-scrollbar::-webkit-scrollbar-track { background: #f1f5f9; }
        [data-theme="light"] .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; }

        /* Role Badge Colors */
        .role-badge-root { background: rgba(127, 29, 29, 0.2); border-color: #ef4444; color: #ef4444; }
        .role-badge-admin { background: rgba(113, 63, 18, 0.2); border-color: #eab308; color: #eab308; }
        .role-badge-visitor { background: rgba(20, 83, 45, 0.2); border-color: #22c55e; color: #22c55e; }

        [data-theme="light"] .role-badge-root { background: #fef2f2; border-color: #f87171; color: #991b1b; }
        [data-theme="light"] .role-badge-admin { background: #fefce8; border-color: #facc15; color: #854d0e; }
        [data-theme="light"] .role-badge-visitor { background: #f0fdf4; border-color: #4ade80; color: #166534; }
    </style>

    <script>
        function openModal(id) {
            document.getElementById(id).classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeModal(id) {
            document.getElementById(id).classList.add('hidden');
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

        // Search Identity in Select
        document.getElementById('identitySearch')?.addEventListener('input', function(e) {
            const search = e.target.value.toLowerCase();
            const select = document.getElementById('identitySelect');
            const options = select.options;
            
            for(let i=0; i<options.length; i++) {
                const text = options[i].text.toLowerCase();
                options[i].style.display = text.includes(search) ? '' : 'none';
            }
        });

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
                document.querySelectorAll('.fixed').forEach(m => m.classList.add('hidden'));
                document.body.style.overflow = '';
            }
        });
    </script>
@endsection