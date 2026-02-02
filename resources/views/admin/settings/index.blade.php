@extends('layouts.admin')

@section('content')
    <div class="space-y-8 pb-12">
        <div class="flex items-center space-x-4 mb-6">
            <div class="w-12 h-12 rounded-xl bg-indigo-600 flex items-center justify-center shadow-lg shadow-indigo-200">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
            </div>
            <div>
                <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">{{ __('System Settings') }}</h1>
                <p class="text-slate-500 font-medium">{{ __('Configure library information and system rules.') }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- LIBRARY INFORMATION SECTION -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="p-6 border-b border-slate-50 flex items-center space-x-3 bg-slate-50/50">
                    <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                    <h2 class="text-sm font-extrabold uppercase tracking-widest text-slate-700">{{ __('Library Information') }}</h2>
                </div>

                <div class="p-6">
                    <form action="{{ route('admin.settings.library.update') }}" method="POST" class="space-y-4">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="space-y-1">
                                <label class="text-[10px] text-slate-400 uppercase font-bold tracking-wider">{{ __('Library Name (VI)') }}</label>
                                <input type="text" name="library_name_vi" value="{{ \App\Models\SystemSetting::get('library_name_vi') }}"
                                    class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all">
                            </div>
                            <div class="space-y-1">
                                <label class="text-[10px] text-slate-400 uppercase font-bold tracking-wider">{{ __('Library Name (EN)') }}</label>
                                <input type="text" name="library_name_en" value="{{ \App\Models\SystemSetting::get('library_name_en') }}"
                                    class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all">
                            </div>
                        </div>

                        <div class="space-y-1">
                            <label class="text-[10px] text-slate-400 uppercase font-bold tracking-wider">{{ __('Official Address') }}</label>
                            <input type="text" name="address" value="{{ \App\Models\SystemSetting::get('address') }}"
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all">
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="space-y-1">
                                <label class="text-[10px] text-slate-400 uppercase font-bold tracking-wider">{{ __('Phone') }}</label>
                                <input type="text" name="phone" value="{{ \App\Models\SystemSetting::get('phone') }}"
                                    class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all">
                            </div>
                            <div class="space-y-1">
                                <label class="text-[10px] text-slate-400 uppercase font-bold tracking-wider">{{ __('Email') }}</label>
                                <input type="email" name="email" value="{{ \App\Models\SystemSetting::get('email') }}"
                                    class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all">
                            </div>
                        </div>

                        <div class="space-y-1">
                            <label class="text-[10px] text-slate-400 uppercase font-bold tracking-wider">{{ __('Website') }}</label>
                            <input type="url" name="website" value="{{ \App\Models\SystemSetting::get('website') }}"
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all">
                        </div>

                        <div class="pt-4">
                            <button type="submit" class="w-full bg-indigo-600 text-white py-3 rounded-xl text-sm font-bold uppercase hover:bg-indigo-500 shadow-lg shadow-indigo-100 transition-all active:scale-[0.98]">
                                {{ __('Update Information') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- BARCODE CONFIGURATION SECTION -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="p-6 border-b border-slate-50 flex items-center justify-between bg-slate-50/50">
                    <div class="flex items-center space-x-3">
                        <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path>
                        </svg>
                        <h2 class="text-sm font-extrabold uppercase tracking-widest text-slate-700">{{ __('Barcode Rules') }}</h2>
                    </div>
                    <button onclick="openModal('createBarcodeModal')" class="bg-indigo-50 text-indigo-600 px-4 py-2 rounded-xl text-[10px] font-bold uppercase hover:bg-indigo-600 hover:text-white transition-all">
                        {{ __('New Rule') }}
                    </button>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="text-slate-400 border-b border-slate-50 uppercase font-bold text-[10px] tracking-wider">
                            <tr>
                                <th class="p-4">{{ __('Rule Name') }}</th>
                                <th class="p-4">{{ __('Pattern') }}</th>
                                <th class="p-4">{{ __('Current') }}</th>
                                <th class="p-4">{{ __('Status') }}</th>
                                <th class="p-4 text-right">{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @foreach($barcodeConfigs as $config)
                                <tr class="hover:bg-slate-50/50 transition">
                                    <td class="p-4 text-slate-900 font-bold">{{ $config->name }}</td>
                                    <td class="p-4 font-mono text-xs">
                                        <span class="text-indigo-600 font-bold">{{ $config->prefix }}</span>
                                        <span class="text-slate-300">{{ str_repeat('0', $config->length) }}</span>
                                    </td>
                                    <td class="p-4 font-mono text-xs text-slate-600">{{ $config->current_number }}</td>
                                    <td class="p-4">
                                        @if($config->is_active)
                                            <span class="px-2 py-1 bg-emerald-50 text-emerald-600 text-[9px] uppercase font-bold rounded-lg border border-emerald-100">{{ __('ACTIVE') }}</span>
                                        @else
                                            <span class="px-2 py-1 bg-slate-50 text-slate-400 text-[9px] uppercase font-bold rounded-lg">{{ __('STDBY') }}</span>
                                        @endif
                                    </td>
                                    <td class="p-4 text-right space-x-3">
                                        <button onclick="openEditBarcodeModal({{ $config }})" class="text-indigo-600 hover:text-indigo-800 font-bold text-xs uppercase px-2 py-1 hover:bg-indigo-50 rounded-lg transition-all">{{ __('Edit') }}</button>
                                        <form action="{{ route('admin.settings.barcode.destroy', $config->id) }}" method="POST" class="inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-rose-600 hover:text-rose-800 font-bold text-xs uppercase px-2 py-1 hover:bg-rose-50 rounded-lg transition-all" onclick="return confirm('{{ __('Terminate this rule?') }}')">{{ __('Del') }}</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            @if($barcodeConfigs->isEmpty())
                                <tr>
                                    <td colspan="5" class="p-8 text-center text-slate-400 italic text-xs">{{ __('No rules defined yet.') }}</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                <div class="m-6 p-4 bg-amber-50 rounded-xl border border-amber-100 flex items-start space-x-3">
                    <svg class="w-4 h-4 text-amber-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-[10px] text-amber-700 italic leading-relaxed">
                        {{ __('Only one rule per target type (Book Item / Patron) can be active at once. Activating a new rule will suspend the previous one.') }}
                    </p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- BRANCH MANAGEMENT SECTION -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="p-6 border-b border-slate-50 flex items-center justify-between bg-slate-50/50">
                    <div class="flex items-center space-x-3">
                        <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <h2 class="text-sm font-extrabold uppercase tracking-widest text-slate-700">{{ __('Branch Management') }}</h2>
                    </div>
                    <button onclick="openModal('createBranchModal')" class="bg-indigo-50 text-indigo-600 px-4 py-2 rounded-xl text-[10px] font-bold uppercase hover:bg-indigo-600 hover:text-white transition-all">
                        {{ __('New Branch') }}
                    </button>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="text-slate-400 border-b border-slate-50 uppercase font-bold text-[10px] tracking-wider">
                            <tr>
                                <th class="p-4">{{ __('Name') }}</th>
                                <th class="p-4">{{ __('Code') }}</th>
                                <th class="p-4 text-right">{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50 text-slate-700">
                            @foreach($branches as $branch)
                                <tr class="hover:bg-slate-50/50 transition">
                                    <td class="p-4 font-bold">{{ $branch->name }}</td>
                                    <td class="p-4 font-mono text-xs text-indigo-600 font-bold uppercase tracking-wider">{{ $branch->code }}</td>
                                    <td class="p-4 text-right space-x-2">
                                        <form action="{{ route('admin.settings.branches.destroy', $branch->id) }}" method="POST" class="inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-rose-600 hover:text-rose-800 font-bold text-xs uppercase px-2 py-1 hover:bg-rose-50 rounded-lg transition-all" onclick="return confirm('{{ __('Delete this branch?') }}')">{{ __('Del') }}</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- STORAGE LOCATION MANAGEMENT SECTION -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="p-6 border-b border-slate-50 flex items-center justify-between bg-slate-50/50">
                    <div class="flex items-center space-x-3">
                        <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"></path>
                        </svg>
                        <h2 class="text-sm font-extrabold uppercase tracking-widest text-slate-700">{{ __('Storage Locations') }}</h2>
                    </div>
                    @if($branches->isNotEmpty())
                        <button onclick="openModal('createLocationModal')" class="bg-indigo-50 text-indigo-600 px-4 py-2 rounded-xl text-[10px] font-bold uppercase hover:bg-indigo-600 hover:text-white transition-all">
                            {{ __('New Location') }}
                        </button>
                    @endif
                </div>

                <div class="overflow-x-auto max-h-[400px] overflow-y-auto custom-scrollbar">
                    <table class="w-full text-sm text-left">
                        <thead class="text-slate-400 border-b border-slate-50 uppercase font-bold text-[10px] tracking-wider sticky top-0 bg-white shadow-sm z-10">
                            <tr>
                                <th class="p-4">{{ __('Name') }}</th>
                                <th class="p-4">{{ __('Branch') }}</th>
                                <th class="p-4 text-right">{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50 text-slate-700">
                            @foreach($branches as $branch)
                                @foreach($branch->storageLocations as $location)
                                    <tr class="hover:bg-slate-50/50 transition">
                                        <td class="p-4">
                                            <div class="font-bold">{{ $location->name }}</div>
                                            <div class="text-[10px] font-mono text-slate-400 font-bold uppercase">{{ $location->code }}</div>
                                        </td>
                                        <td class="p-4">
                                            <span class="px-2 py-0.5 bg-indigo-50 text-indigo-600 text-[10px] font-bold rounded-lg">{{ $branch->name }}</span>
                                        </td>
                                        <td class="p-4 text-right">
                                            <form action="{{ route('admin.settings.locations.destroy', $location->id) }}" method="POST" class="inline">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="text-rose-600 hover:text-rose-800 font-bold text-xs uppercase px-2 py-1 hover:bg-rose-50 rounded-lg transition-all" onclick="return confirm('{{ __('Delete this location?') }}')">{{ __('Del') }}</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- MODALS -->
    @include('admin.settings.modals')

    <script>
        function openModal(id) {
            document.getElementById(id).classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }
        function closeModal(id) {
            document.getElementById(id).classList.add('hidden');
            document.body.style.overflow = 'auto';
        }
        function openEditBarcodeModal(config) {
            document.getElementById('editBarcodeForm').action = `/topsecret/settings/barcode/${config.id}`;
            document.getElementById('edit_name').value = config.name;
            document.getElementById('edit_prefix').value = config.prefix || '';
            document.getElementById('edit_length').value = config.length;
            document.getElementById('edit_is_active').checked = config.is_active;
            openModal('editBarcodeModal');
        }
    </script>
@endsection
