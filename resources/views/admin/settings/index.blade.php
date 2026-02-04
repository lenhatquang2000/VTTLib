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
            <div class="flex-1">
                <h1 class="text-3xl font-extrabold text-slate-900 dark:text-slate-100 tracking-tight transition-colors">{{ __('System Settings') }}</h1>
                <p class="text-slate-500 dark:text-slate-400 font-medium transition-colors">{{ __('Configure library information and system rules.') }}</p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('root.users.index') }}" class="flex items-center space-x-2 px-6 py-2.5 bg-slate-900 dark:bg-slate-100 text-white dark:text-slate-900 rounded-2xl text-xs font-bold uppercase tracking-widest hover:scale-105 transition-all shadow-xl shadow-slate-200 dark:shadow-none">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                    </svg>
                    <span>{{ __('Advanced Settings') }}</span>
                </a>
            </div>
        </div>

        <div x-data="{ activeTab: 'general' }" class="space-y-8">
            <!-- TABS NAVIGATION -->
            <div class="flex flex-wrap gap-2 p-1.5 bg-slate-100 dark:bg-slate-800/50 rounded-2xl w-fit transition-colors">
                <button @click="activeTab = 'general'" 
                        :class="activeTab === 'general' ? 'bg-white dark:bg-slate-900 text-indigo-600 dark:text-indigo-400 shadow-sm' : 'text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200'"
                        class="px-6 py-2.5 text-[10px] font-extrabold uppercase tracking-widest rounded-xl transition-all duration-200">
                    {{ __('General Settings') }}
                </button>
                <button @click="activeTab = 'policies'" 
                        :class="activeTab === 'policies' ? 'bg-white dark:bg-slate-900 text-indigo-600 dark:text-indigo-400 shadow-sm' : 'text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200'"
                        class="px-6 py-2.5 text-[10px] font-extrabold uppercase tracking-widest rounded-xl transition-all duration-200">
                    {{ __('Library Policies') }}
                </button>
                <button @click="activeTab = 'infrastructure'" 
                        :class="activeTab === 'infrastructure' ? 'bg-white dark:bg-slate-900 text-indigo-600 dark:text-indigo-400 shadow-sm' : 'text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200'"
                        class="px-6 py-2.5 text-[10px] font-extrabold uppercase tracking-widest rounded-xl transition-all duration-200">
                    {{ __('Infrastructure') }}
                </button>
                <button @click="activeTab = 'suppliers'" 
                        :class="activeTab === 'suppliers' ? 'bg-white dark:bg-slate-900 text-indigo-600 dark:text-indigo-400 shadow-sm' : 'text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200'"
                        class="px-6 py-2.5 text-[10px] font-extrabold uppercase tracking-widest rounded-xl transition-all duration-200">
                    {{ __('Suppliers') }}
                </button>
            </div>

            <!-- GENERAL TAB CONTENT -->
            <div x-show="activeTab === 'general'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="grid grid-cols-1 lg:grid-cols-2 gap-8">

            <!-- LIBRARY INFORMATION SECTION -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-800 overflow-hidden transition-colors">
                <div class="p-6 border-b border-slate-50 dark:border-slate-800 flex items-center space-x-3 bg-slate-50/50 dark:bg-slate-800/50 transition-colors">
                    <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                    <h2 class="text-sm font-extrabold uppercase tracking-widest text-slate-700 dark:text-slate-300 transition-colors">{{ __('Library Information') }}</h2>
                </div>

                <div class="p-6">
                    <form action="{{ route('admin.settings.library.update') }}" method="POST" class="space-y-4">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="space-y-1">
                                <label class="text-[10px] text-slate-400 dark:text-slate-500 uppercase font-bold tracking-wider">{{ __('Library Name (VI)') }}</label>
                                <input type="text" name="library_name_vi" value="{{ \App\Models\SystemSetting::get('library_name_vi') }}"
                                    class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl p-3 text-sm text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all">
                            </div>
                            <div class="space-y-1">
                                <label class="text-[10px] text-slate-400 dark:text-slate-500 uppercase font-bold tracking-wider">{{ __('Library Name (EN)') }}</label>
                                <input type="text" name="library_name_en" value="{{ \App\Models\SystemSetting::get('library_name_en') }}"
                                    class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl p-3 text-sm text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all">
                            </div>
                        </div>

                        <div class="space-y-1">
                            <label class="text-[10px] text-slate-400 dark:text-slate-500 uppercase font-bold tracking-wider">{{ __('Official Address') }}</label>
                            <input type="text" name="address" value="{{ \App\Models\SystemSetting::get('address') }}"
                                class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl p-3 text-sm text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all">
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="space-y-1">
                                <label class="text-[10px] text-slate-400 dark:text-slate-500 uppercase font-bold tracking-wider">{{ __('Phone') }}</label>
                                <input type="text" name="phone" value="{{ \App\Models\SystemSetting::get('phone') }}"
                                    class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl p-3 text-sm text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all">
                            </div>
                            <div class="space-y-1">
                                <label class="text-[10px] text-slate-400 dark:text-slate-500 uppercase font-bold tracking-wider">{{ __('Email') }}</label>
                                <input type="email" name="email" value="{{ \App\Models\SystemSetting::get('email') }}"
                                    class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl p-3 text-sm text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all">
                            </div>
                        </div>

                        <div class="space-y-1">
                            <label class="text-[10px] text-slate-400 dark:text-slate-500 uppercase font-bold tracking-wider">{{ __('Website') }}</label>
                            <input type="url" name="website" value="{{ \App\Models\SystemSetting::get('website') }}"
                                class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl p-3 text-sm text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all">
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
            <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-800 overflow-hidden transition-colors">
                <div class="p-6 border-b border-slate-50 dark:border-slate-800 flex items-center justify-between bg-slate-50/50 dark:bg-slate-800/50 transition-colors">
                    <div class="flex items-center space-x-3">
                        <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path>
                        </svg>
                        <h2 class="text-sm font-extrabold uppercase tracking-widest text-slate-700 dark:text-slate-300 transition-colors">{{ __('Barcode Rules') }}</h2>
                    </div>
                    <button onclick="openModal('createBarcodeModal')" class="bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 px-4 py-2 rounded-xl text-[10px] font-bold uppercase hover:bg-indigo-600 hover:text-white transition-all transition-colors">
                        {{ __('New Rule') }}
                    </button>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="text-slate-400 dark:text-slate-500 border-b border-slate-50 dark:border-slate-800 uppercase font-bold text-[10px] tracking-wider transition-colors">
                            <tr>
                                <th class="p-4">{{ __('Rule Name') }}</th>
                                <th class="p-4">{{ __('Pattern') }}</th>
                                <th class="p-4">{{ __('Current') }}</th>
                                <th class="p-4">{{ __('Status') }}</th>
                                <th class="p-4 text-right">{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50 dark:divide-slate-800/50">
                            @foreach($barcodeConfigs as $config)
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-colors">
                                    <td class="p-4 text-slate-900 dark:text-slate-100 font-bold">{{ $config->name }}</td>
                                    <td class="p-4 font-mono text-xs">
                                        <span class="text-indigo-600 dark:text-indigo-400 font-bold">{{ $config->prefix }}</span>
                                        <span class="text-slate-300 dark:text-slate-600">{{ str_repeat('0', $config->length) }}</span>
                                    </td>
                                    <td class="p-4 font-mono text-xs text-slate-600 dark:text-slate-400">{{ $config->current_number }}</td>
                                    <td class="p-4">
                                        @if($config->is_active)
                                            <span class="px-2 py-1 bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 text-[9px] uppercase font-bold rounded-lg border border-emerald-100 dark:border-emerald-500/20">{{ __('ACTIVE') }}</span>
                                        @else
                                            <span class="px-2 py-1 bg-slate-50 dark:bg-slate-800 text-slate-400 dark:text-slate-500 text-[9px] uppercase font-bold rounded-lg">{{ __('STDBY') }}</span>
                                        @endif
                                    </td>
                                    <td class="p-4 text-right space-x-3">
                                        <button onclick="openEditBarcodeModal({{ $config }})" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 font-bold text-xs uppercase px-2 py-1 hover:bg-indigo-50 dark:hover:bg-indigo-500/10 rounded-lg transition-all">{{ __('Edit') }}</button>
                                        <form action="{{ route('admin.settings.barcode.destroy', $config->id) }}" method="POST" class="inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-rose-600 dark:text-rose-400 hover:text-rose-800 dark:hover:text-rose-300 font-bold text-xs uppercase px-2 py-1 hover:bg-rose-50 dark:hover:bg-rose-500/10 rounded-lg transition-all" onclick="return confirm('{{ __('Terminate this rule?') }}')">{{ __('Del') }}</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            @if($barcodeConfigs->isEmpty())
                                <tr>
                                    <td colspan="5" class="p-8 text-center text-slate-400 dark:text-slate-600 italic text-xs">{{ __('No rules defined yet.') }}</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                <div class="m-6 p-4 bg-amber-50 dark:bg-amber-500/10 rounded-xl border border-amber-100 dark:border-amber-500/20 flex items-start space-x-3 transition-colors">
                    <svg class="w-4 h-4 text-amber-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-[10px] text-amber-700 dark:text-amber-400 italic leading-relaxed transition-colors">
                        {{ __('Only one rule per target type (Book Item / Patron) can be active at once. Activating a new rule will suspend the previous one.') }}
                    </p>
                </div>
            </div>
            </div>

            <!-- LIBRARY POLICIES TAB -->
            <div x-show="activeTab === 'policies'" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-800 overflow-hidden transition-colors">
                    <div x-data="{ subTab: 'general' }">
                        <!-- Sub-tabs Navigation -->
                        <div class="px-6 py-4 border-b border-slate-50 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/50 flex flex-wrap gap-4 transition-colors">
                            <button @click="subTab = 'general'" :class="subTab === 'general' ? 'text-indigo-600 dark:text-indigo-400 border-b-2 border-indigo-600 dark:border-indigo-400' : 'text-slate-400 border-b-2 border-transparent hover:text-slate-600'" class="pb-2 text-xs font-bold uppercase tracking-wider transition-all">{{ __('General Policy') }}</button>
                            <button @click="subTab = 'holidays'" :class="subTab === 'holidays' ? 'text-indigo-600 dark:text-indigo-400 border-b-2 border-indigo-600 dark:border-indigo-400' : 'text-slate-400 border-b-2 border-transparent hover:text-slate-600'" class="pb-2 text-xs font-bold uppercase tracking-wider transition-all">{{ __('Holidays') }}</button>
                            <button @click="subTab = 'services'" :class="subTab === 'services' ? 'text-indigo-600 dark:text-indigo-400 border-b-2 border-indigo-600 dark:border-indigo-400' : 'text-slate-400 border-b-2 border-transparent hover:text-slate-600'" class="pb-2 text-xs font-bold uppercase tracking-wider transition-all">{{ __('Services') }}</button>
                        </div>

                        <div class="p-8">
                            <!-- Policy: General Tab -->
                            <div x-show="subTab === 'general'">
                                <form action="{{ route('admin.settings.policy.update') }}" method="POST">
                                    @csrf
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-6">
                                        <!-- Column 1 -->
                                        <div class="space-y-6">
                                            <div class="flex items-center justify-between group">
                                                <label class="text-xs font-bold text-slate-600 dark:text-slate-400">{{ __('Loan Time Unit') }}</label>
                                                <select name="loan_time_unit" class="w-1/2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-2 text-sm text-slate-900 dark:text-slate-100 outline-none focus:ring-2 focus:ring-indigo-500/20 transition-all">
                                                    <option value="day" {{ \App\Models\SystemSetting::get('loan_time_unit') == 'day' ? 'selected' : '' }}>{{ __('By Day') }}</option>
                                                    <option value="hour" {{ \App\Models\SystemSetting::get('loan_time_unit') == 'hour' ? 'selected' : '' }}>{{ __('By Hour') }}</option>
                                                </select>
                                            </div>

                                            <div class="flex items-center justify-between group">
                                                <label class="text-xs font-bold text-slate-600 dark:text-slate-400">{{ __('Opening Time') }}</label>
                                                <input type="time" name="opening_time" value="{{ \App\Models\SystemSetting::get('opening_time', '07:30') }}" class="w-1/2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-2 text-sm text-slate-900 dark:text-slate-100 outline-none focus:ring-2 focus:ring-indigo-500/20 transition-all">
                                            </div>

                                            <div class="flex items-center justify-between group">
                                                <label class="text-xs font-bold text-slate-600 dark:text-slate-400">{{ __('Fine Notification Time') }}</label>
                                                <input type="time" name="fine_notification_time" value="{{ \App\Models\SystemSetting::get('fine_notification_time', '17:00') }}" class="w-1/2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-2 text-sm text-slate-900 dark:text-slate-100 outline-none focus:ring-2 focus:ring-indigo-500/20 transition-all">
                                            </div>

                                            <div class="flex items-center justify-between group">
                                                <label class="text-xs font-bold text-slate-600 dark:text-slate-400">{{ __('Grace Period (Days)') }}</label>
                                                <input type="number" name="grace_period" value="{{ \App\Models\SystemSetting::get('grace_period', 1) }}" class="w-1/2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-2 text-sm text-slate-900 dark:text-slate-100 outline-none focus:ring-2 focus:ring-indigo-500/20 transition-all">
                                            </div>

                                            <div class="flex items-center justify-between group">
                                                <label class="text-xs font-bold text-slate-600 dark:text-slate-400">{{ __('Default Replacement Cost') }}</label>
                                                <input type="number" name="default_replacement_cost" value="{{ \App\Models\SystemSetting::get('default_replacement_cost', 0) }}" class="w-1/2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-2 text-sm text-slate-900 dark:text-slate-100 outline-none focus:ring-2 focus:ring-indigo-500/20 transition-all">
                                            </div>

                                            <div class="flex items-center justify-between group">
                                                <label class="text-xs font-bold text-slate-600 dark:text-slate-400">{{ __('Fine Rate (Urgent)') }}</label>
                                                <input type="number" name="urgent_fine_rate" value="{{ \App\Models\SystemSetting::get('urgent_fine_rate', 2000) }}" class="w-1/2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-2 text-sm text-slate-900 dark:text-slate-100 outline-none focus:ring-2 focus:ring-indigo-500/20 transition-all">
                                            </div>
                                        </div>

                                        <!-- Column 2 -->
                                        <div class="space-y-6">
                                            <div class="flex items-center justify-between group invisible">
                                                <label class="text-xs font-bold text-slate-600 dark:text-slate-400">-</label>
                                                <div class="w-1/2"></div>
                                            </div>

                                            <div class="flex items-center justify-between group">
                                                <label class="text-xs font-bold text-slate-600 dark:text-slate-400">{{ __('Closing Time') }}</label>
                                                <input type="time" name="closing_time" value="{{ \App\Models\SystemSetting::get('closing_time', '17:00') }}" class="w-1/2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-2 text-sm text-slate-900 dark:text-slate-100 outline-none focus:ring-2 focus:ring-indigo-500/20 transition-all">
                                            </div>

                                            <div class="flex items-center justify-between group">
                                                <label class="text-xs font-bold text-slate-600 dark:text-slate-400">{{ __('Debt Notification Time') }}</label>
                                                <input type="time" name="debt_notification_time" value="{{ \App\Models\SystemSetting::get('debt_notification_time', '17:00') }}" class="w-1/2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-2 text-sm text-slate-900 dark:text-slate-100 outline-none focus:ring-2 focus:ring-indigo-500/20 transition-all">
                                            </div>

                                            <div class="flex items-center justify-between group">
                                                <label class="text-xs font-bold text-slate-600 dark:text-slate-400">{{ __('Fine Period (Days)') }}</label>
                                                <input type="number" name="fine_period" value="{{ \App\Models\SystemSetting::get('fine_period', 2) }}" class="w-1/2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-2 text-sm text-slate-900 dark:text-slate-100 outline-none focus:ring-2 focus:ring-indigo-500/20 transition-all">
                                            </div>

                                            <div class="flex items-center justify-between group">
                                                <label class="text-xs font-bold text-slate-600 dark:text-slate-400">{{ __('Default Processing Cost') }}</label>
                                                <input type="number" name="default_processing_cost" value="{{ \App\Models\SystemSetting::get('default_processing_cost', 0) }}" class="w-1/2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-2 text-sm text-slate-900 dark:text-slate-100 outline-none focus:ring-2 focus:ring-indigo-500/20 transition-all">
                                            </div>

                                            <div class="flex items-center justify-between group">
                                                <label class="text-xs font-bold text-slate-600 dark:text-slate-400">{{ __('Fine Rate (Normal)') }}</label>
                                                <input type="number" name="normal_fine_rate" value="{{ \App\Models\SystemSetting::get('normal_fine_rate', 1000) }}" class="w-1/2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-2 text-sm text-slate-900 dark:text-slate-100 outline-none focus:ring-2 focus:ring-indigo-500/20 transition-all">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mt-8 pt-8 border-t border-slate-50 dark:border-slate-800">
                                        <button type="submit" class="bg-indigo-600 text-white px-8 py-3 rounded-xl text-sm font-extrabold uppercase tracking-widest hover:bg-indigo-500 shadow-xl shadow-indigo-100 dark:shadow-none transition-all active:scale-95">
                                            {{ __('Save Policy') }}
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- INFRASTRUCTURE TAB CONTENT -->
            <div x-show="activeTab === 'infrastructure'" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- BRANCH MANAGEMENT SECTION -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-800 overflow-hidden transition-colors">
                <div class="p-6 border-b border-slate-50 dark:border-slate-800 flex items-center justify-between bg-slate-50/50 dark:bg-slate-800/50 transition-colors">
                    <div class="flex items-center space-x-3">
                        <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <h2 class="text-sm font-extrabold uppercase tracking-widest text-slate-700 dark:text-slate-300 transition-colors">{{ __('Branch Management') }}</h2>
                    </div>
                    <button onclick="openModal('createBranchModal')" class="bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 px-4 py-2 rounded-xl text-[10px] font-bold uppercase hover:bg-indigo-600 hover:text-white transition-all transition-colors">
                        {{ __('New Branch') }}
                    </button>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="text-slate-400 dark:text-slate-500 border-b border-slate-50 dark:border-slate-800 uppercase font-bold text-[10px] tracking-wider transition-colors">
                            <tr>
                                <th class="p-4">{{ __('Name') }}</th>
                                <th class="p-4">{{ __('Code') }}</th>
                                <th class="p-4 text-right">{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50 dark:divide-slate-800/50 text-slate-700 dark:text-slate-300">
                            @foreach($branches as $branch)
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-colors">
                                    <td class="p-4 font-bold text-slate-900 dark:text-slate-100">{{ $branch->name }}</td>
                                    <td class="p-4 font-mono text-xs text-indigo-600 dark:text-indigo-400 font-bold uppercase tracking-wider">{{ $branch->code }}</td>
                                    <td class="p-4 text-right space-x-2">
                                        <form action="{{ route('admin.settings.branches.destroy', $branch->id) }}" method="POST" class="inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-rose-600 dark:text-rose-400 hover:text-rose-800 dark:hover:text-rose-300 font-bold text-xs uppercase px-2 py-1 hover:bg-rose-50 dark:hover:bg-rose-500/10 rounded-lg transition-all" onclick="return confirm('{{ __('Delete this branch?') }}')">{{ __('Del') }}</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- STORAGE LOCATION MANAGEMENT SECTION -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-800 overflow-hidden transition-colors">
                <div class="p-6 border-b border-slate-50 dark:border-slate-800 flex items-center justify-between bg-slate-50/50 dark:bg-slate-800/50 transition-colors">
                    <div class="flex items-center space-x-3">
                        <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"></path>
                        </svg>
                        <h2 class="text-sm font-extrabold uppercase tracking-widest text-slate-700 dark:text-slate-300 transition-colors">{{ __('Storage Locations') }}</h2>
                    </div>
                    @if($branches->isNotEmpty())
                        <button onclick="openModal('createLocationModal')" class="bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 px-4 py-2 rounded-xl text-[10px] font-bold uppercase hover:bg-indigo-600 hover:text-white transition-all transition-colors">
                            {{ __('New Location') }}
                        </button>
                    @endif
                </div>

                <div class="overflow-x-auto max-h-[400px] overflow-y-auto custom-scrollbar">
                    <table class="w-full text-sm text-left">
                        <thead class="text-slate-400 dark:text-slate-500 border-b border-slate-50 dark:border-slate-800 uppercase font-bold text-[10px] tracking-wider sticky top-0 bg-white dark:bg-slate-900 shadow-sm z-10 transition-colors">
                            <tr>
                                <th class="p-4">{{ __('Name') }}</th>
                                <th class="p-4">{{ __('Branch') }}</th>
                                <th class="p-4 text-right">{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50 dark:divide-slate-800/50 text-slate-700 dark:text-slate-300">
                            @foreach($branches as $branch)
                                @foreach($branch->storageLocations as $location)
                                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-colors">
                                        <td class="p-4">
                                            <div class="font-bold text-slate-900 dark:text-slate-100">{{ $location->name }}</div>
                                            <div class="text-[10px] font-mono text-slate-400 dark:text-slate-500 font-bold uppercase">{{ $location->code }}</div>
                                        </td>
                                        <td class="p-4">
                                            <span class="px-2 py-0.5 bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 text-[10px] font-bold rounded-lg">{{ $branch->name }}</span>
                                        </td>
                                        <td class="p-4 text-right">
                                            <form action="{{ route('admin.settings.locations.destroy', $location->id) }}" method="POST" class="inline">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="text-rose-600 dark:text-rose-400 hover:text-rose-800 dark:hover:text-rose-300 font-bold text-xs uppercase px-2 py-1 hover:bg-rose-50 dark:hover:bg-rose-500/10 rounded-lg transition-all" onclick="return confirm('{{ __('Delete this location?') }}')">{{ __('Del') }}</button>
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
            <!-- SUPPLIERS TAB CONTENT -->
            <div x-show="activeTab === 'suppliers'" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-800 overflow-hidden transition-colors">
                    <div class="p-6 border-b border-slate-50 dark:border-slate-800 flex items-center justify-between bg-slate-50/50 dark:bg-slate-800/50 transition-colors">
                        <div class="flex items-center space-x-3">
                            <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                            <h2 class="text-sm font-extrabold uppercase tracking-widest text-slate-700 dark:text-slate-300 transition-colors">{{ __('Supplier Management') }}</h2>
                        </div>
                        <button onclick="openModal('createSupplierModal')" class="bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 px-4 py-2 rounded-xl text-[10px] font-bold uppercase hover:bg-indigo-600 hover:text-white transition-all">
                            {{ __('New Supplier') }}
                        </button>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <thead class="text-slate-400 dark:text-slate-500 border-b border-slate-50 dark:border-slate-800 uppercase font-bold text-[10px] tracking-wider transition-colors">
                                <tr>
                                    <th class="p-4">{{ __('Supplier') }}</th>
                                    <th class="p-4">{{ __('Contact') }}</th>
                                    <th class="p-4">{{ __('Email/Phone') }}</th>
                                    <th class="p-4">{{ __('Status') }}</th>
                                    <th class="p-4 text-right">{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50 dark:divide-slate-800/50 text-slate-700 dark:text-slate-300">
                                @forelse($suppliers as $supplier)
                                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-colors">
                                        <td class="p-4">
                                            <div class="font-bold text-slate-900 dark:text-slate-100">{{ $supplier->name }}</div>
                                            <div class="text-[10px] font-mono text-slate-400 dark:text-slate-500 font-bold uppercase">{{ $supplier->code }}</div>
                                        </td>
                                        <td class="p-4 text-xs">{{ $supplier->contact_name ?? '-' }}</td>
                                        <td class="p-4 text-xs">
                                            <div>{{ $supplier->email ?? '-' }}</div>
                                            <div class="text-slate-400">{{ $supplier->phone ?? '-' }}</div>
                                        </td>
                                        <td class="p-4">
                                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $supplier->is_active ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-500/10 dark:text-emerald-400' : 'bg-slate-100 text-slate-800 dark:bg-slate-800 dark:text-slate-500' }}">
                                                {{ $supplier->is_active ? __('Active') : __('Inactive') }}
                                            </span>
                                        </td>
                                        <td class="p-4 text-right space-x-2">
                                            <button onclick="openEditSupplierModal({{ $supplier }})" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 font-bold text-xs uppercase px-2 py-1 hover:bg-indigo-50 dark:hover:bg-indigo-500/10 rounded-lg transition-all">{{ __('Edit') }}</button>
                                            <form action="{{ route('admin.settings.suppliers.destroy', $supplier->id) }}" method="POST" class="inline">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="text-rose-600 dark:text-rose-400 hover:text-rose-800 font-bold text-xs uppercase px-2 py-1 hover:bg-rose-50 dark:hover:bg-rose-500/10 rounded-lg transition-all" onclick="return confirm('{{ __('Delete this supplier?') }}')">{{ __('Del') }}</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="p-8 text-center text-slate-400 dark:text-slate-600 italic text-xs">{{ __('No suppliers defined yet.') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
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
        function openEditSupplierModal(supplier) {
            document.getElementById('editSupplierForm').action = `/topsecret/settings/suppliers/${supplier.id}`;
            document.getElementById('edit_sup_name').value = supplier.name;
            document.getElementById('edit_sup_code').value = supplier.code;
            document.getElementById('edit_sup_contact').value = supplier.contact_name || '';
            document.getElementById('edit_sup_phone').value = supplier.phone || '';
            document.getElementById('edit_sup_email').value = supplier.email || '';
            document.getElementById('edit_sup_is_active').checked = supplier.is_active;
            openModal('editSupplierModal');
        }
    </script>
@endsection
