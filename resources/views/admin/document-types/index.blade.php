@extends('layouts.admin')

@section('content')
<div class="space-y-8 animate-in fade-in duration-700">
    <!-- Notifications -->
    @if(session('success'))
        <div class="bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-100 dark:border-emerald-500/20 text-emerald-600 dark:text-emerald-400 p-4 rounded-xl text-sm font-bold flex items-center space-x-3">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif
    
    @if(session('error'))
        <div class="bg-rose-50 dark:bg-rose-500/10 border border-rose-100 dark:border-rose-500/20 text-rose-600 dark:text-rose-400 p-4 rounded-xl text-sm font-bold flex items-center space-x-3">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div class="flex items-center space-x-4">
            <div class="w-12 h-12 rounded-xl bg-indigo-600 flex items-center justify-center shadow-lg shadow-indigo-200 dark:shadow-none">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                </svg>
            </div>
            <div>
                <h1 class="text-3xl font-extrabold text-slate-900 dark:text-slate-100 tracking-tight">{{ __('Document_Types') }}</h1>
                <p class="text-slate-500 dark:text-slate-400 font-medium mt-1">{{ __('Manage_document_types_for_library_materials') }}</p>
            </div>
        </div>
        <button @click="$dispatch('open-modal', 'add-doc-type')" class="inline-flex items-center px-6 py-3 bg-indigo-600 text-white font-black rounded-xl hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-200 dark:shadow-none text-[10px] uppercase tracking-widest">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
            {{ __('Add_Document_Type') }}
        </button>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <!-- Table Column -->
        <div class="lg:col-span-12">
            <div class="bg-white dark:bg-slate-900 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-800 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-slate-50/50 dark:bg-slate-800/50 border-b border-slate-100 dark:border-slate-800 text-left">
                                <th class="px-6 py-4 w-12 text-xs font-bold text-slate-400 uppercase tracking-widest text-center">#</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest">{{ __('Document Type') }}</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest">{{ __('Code') }}</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest">{{ __('MARC Type') }}</th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest">{{ __('Loan_Days') }}</th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest">{{ __('Loanable') }}</th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest">{{ __('Status') }}</th>
                                <th class="px-6 py-4 text-right text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest">{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50 dark:divide-slate-800" id="sortable-doc-types">
                            @forelse($documentTypes as $type)
                            <tr class="hover:bg-slate-50/30 dark:hover:bg-slate-800/30 transition-colors group" data-id="{{ $type->id }}">
                                <td class="px-6 py-4 cursor-move text-slate-300 dark:text-slate-700 hover:text-indigo-500 drag-handle">
                                    <svg class="w-5 h-5 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/></svg>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 rounded-xl bg-slate-50 dark:bg-slate-800 flex items-center justify-center text-slate-400 group-hover:bg-indigo-50 dark:group-hover:bg-indigo-500/10 group-hover:text-indigo-600 transition-all">
                                            @if($type->icon)
                                                <i data-lucide="{{ $type->icon }}" class="w-5 h-5"></i>
                                            @else
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                            @endif
                                        </div>
                                        <div>
                                            <div class="text-sm font-bold text-slate-900 dark:text-slate-100">{{ $type->name }}</div>
                                            <div class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 line-clamp-1 max-w-[200px]">{{ $type->description }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <code class="text-[10px] bg-slate-100 dark:bg-slate-800 px-2 py-1 rounded-md text-slate-600 dark:text-slate-300 font-mono inline-block font-bold">
                                        {{ $type->code }}
                                    </code>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-[10px] font-black uppercase tracking-widest text-slate-400 whitespace-nowrap">
                                        {{ $type->marc_type ?: 'Not defined' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="inline-flex flex-col items-center">
                                        <span class="text-sm font-black text-slate-800 dark:text-slate-200">{{ $type->default_loan_days }}</span>
                                        <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest">{{ __('Days') }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center px-2 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider {{ $type->is_loanable ? 'bg-indigo-50 text-indigo-600 dark:bg-indigo-500/10 dark:text-indigo-400' : 'bg-slate-50 text-slate-400 dark:bg-slate-800 dark:text-slate-500' }}">
                                        {{ $type->is_loanable ? __('Yes') : __('No') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider {{ $type->is_active ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400' : 'bg-rose-100 text-rose-700 dark:bg-rose-500/10 dark:text-rose-400' }}">
                                        {{ $type->is_active ? __('Active') : __('Inactive') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right space-x-2 whitespace-nowrap">
                                    <button @click="$dispatch('open-modal', 'edit-doc-type'); $dispatch('set-edit-doc-type', @js($type))" class="p-2 text-slate-400 hover:text-amber-600 transition-colors bg-slate-50 dark:bg-slate-800 rounded-lg" title="{{ __('Edit') }}">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </button>
                                    <form action="{{ route('admin.document-types.destroy', $type) }}" method="POST" class="inline-block" onsubmit="return confirm(@js(__('Delete_this_document_type?')))">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="p-2 text-slate-400 hover:text-rose-600 transition-colors bg-slate-50 dark:bg-slate-800 rounded-lg" title="{{ __('Delete') }}">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="px-6 py-12 text-center text-slate-400 dark:text-slate-500">
                                    <div class="flex flex-col items-center">
                                        <svg class="w-12 h-12 text-slate-200 dark:text-slate-800 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                        <p class="text-sm font-medium">{{ __('No_document_types_found') }}</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Info Column -->
        <div class="lg:col-span-12">
             <div class="bg-indigo-50 dark:bg-indigo-900/20 p-8 rounded-[3rem] border border-indigo-100 dark:border-indigo-500/20">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                    <div class="max-w-2xl">
                        <h3 class="text-xl font-black text-indigo-900 dark:text-indigo-300 mb-2 uppercase tracking-tight">{{ __('Understanding_Loan_Days') }}</h3>
                        <p class="text-indigo-600 dark:text-indigo-400/80 text-sm font-medium leading-relaxed">
                            {{ __('Loan_Days_defined_here_acts_as_the_base_duration_for_circulation') }}. 
                            {{ __('It_ensures_that_items_of_this_type_have_a_sensible_default_if_no_specific_policy_is_assigned') }}.
                        </p>
                    </div>
                </div>
                
                <div class="mt-8">
                    <h4 class="text-[10px] font-black text-indigo-400 dark:text-indigo-500 uppercase tracking-[0.2em] mb-4">{{ __('MARC21_Type_Reference') }} (Leader/06)</h4>
                    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-3">
                        @php
                            $marcCodes = [
                                'a' => 'Language material', 'c' => 'Notated music', 'e' => 'Cartographic',
                                'g' => 'Projected medium', 'i' => 'Sound recording', 'j' => 'Musical sound',
                                'k' => '2D graphic', 'm' => 'Computer file', 'o' => 'Kit',
                                'p' => 'Mixed materials', 'r' => '3D artifact', 's' => 'Serial'
                            ];
                        @endphp
                        @foreach($marcCodes as $code => $label)
                            <div class="bg-white dark:bg-slate-900 px-4 py-3 rounded-2xl border border-indigo-100 dark:border-indigo-500/10 flex items-center space-x-2">
                                <code class="text-xs font-black text-indigo-600 dark:text-indigo-400 font-mono">{{ $code }}</code>
                                <span class="text-[10px] text-slate-500 dark:text-slate-400 font-bold tracking-tight">{{ __($label) }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Manager -->
<div x-data="{ 
    showAdd: false, 
    showEdit: false, 
    docType: {},
    init() {
        window.addEventListener('open-modal', (e) => {
            if (e.detail === 'add-doc-type') this.showAdd = true;
            if (e.detail === 'edit-doc-type') this.showEdit = true;
        });
        window.addEventListener('set-edit-doc-type', (e) => {
            this.docType = e.detail;
            this.docType.is_loanable = !!this.docType.is_loanable;
            this.docType.is_active = !!this.docType.is_active;
        });
    }
}">
    <!-- Add Modal -->
    <template x-if="showAdd">
        <div class="fixed inset-0 z-[100] overflow-y-auto px-4 py-6 sm:px-0 flex items-center justify-center animate-in fade-in duration-300">
            <div class="fixed inset-0 transition-opacity bg-slate-900/60 backdrop-blur-sm" @click="showAdd = false"></div>
            
            <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] shadow-2xl border border-slate-100 dark:border-slate-800 overflow-hidden w-full max-w-xl relative">
                <form action="{{ route('admin.document-types.store') }}" method="POST" class="p-8 md:p-10">
                    @csrf
                    <div class="flex justify-between items-center mb-8">
                        <div>
                            <h3 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight uppercase">{{ __('Expand Typology') }}</h3>
                            <p class="text-slate-500 text-xs font-bold">{{ __('Define a new category of library material') }}</p>
                        </div>
                        <button type="button" @click="showAdd = false" class="p-2.5 bg-slate-50 dark:bg-slate-800 text-slate-400 hover:text-rose-500 transition-colors rounded-2xl">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                             <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] pl-1">{{ __('Classification Name') }} *</label>
                             <input type="text" name="name" required class="w-full px-5 py-4 bg-slate-50 dark:bg-slate-950 border-2 border-transparent rounded-2xl focus:bg-white dark:focus:bg-slate-800 focus:border-indigo-500 font-bold transition-all text-sm" placeholder="e.g. Rare Manuscript">
                        </div>
                        <div class="space-y-2">
                             <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] pl-1">{{ __('System Code') }} *</label>
                             <input type="text" name="code" required class="w-full px-5 py-4 bg-slate-50 dark:bg-slate-950 border-2 border-transparent rounded-2xl focus:bg-white dark:focus:bg-slate-800 focus:border-indigo-500 font-mono font-bold transition-all text-sm" placeholder="RMAN" maxlength="20">
                        </div>
                        <div class="space-y-2 text-left">
                             <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] pl-1">{{ __('MARC Selector') }} (L/06)</label>
                             <input type="text" name="marc_type" class="w-full px-5 py-4 bg-slate-50 dark:bg-slate-950 border-2 border-transparent rounded-2xl focus:bg-white dark:focus:bg-slate-800 focus:border-indigo-500 font-bold transition-all text-sm" placeholder="a">
                        </div>
                        <div class="space-y-2">
                             <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] pl-1">{{ __('Glyph Identifier') }} (Icon)</label>
                             <input type="text" name="icon" class="w-full px-5 py-4 bg-slate-50 dark:bg-slate-950 border-2 border-transparent rounded-2xl focus:bg-white dark:focus:bg-slate-800 focus:border-indigo-500 font-bold transition-all text-sm" placeholder="scroll">
                        </div>
                        <div class="md:col-span-2 space-y-2">
                             <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] pl-1">{{ __('Typology Description') }}</label>
                             <textarea name="description" rows="2" class="w-full px-5 py-4 bg-slate-50 dark:bg-slate-950 border-2 border-transparent rounded-2xl focus:bg-white dark:focus:bg-slate-800 focus:border-indigo-500 font-bold transition-all text-sm"></textarea>
                        </div>
                        <div class="space-y-2">
                             <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] pl-1">{{ __('Base Loan Retention') }} (Days)</label>
                             <input type="number" name="default_loan_days" value="14" required min="0" max="365" class="w-full px-5 py-4 bg-slate-50 dark:bg-slate-950 border-2 border-transparent rounded-2xl focus:bg-white dark:focus:bg-slate-800 focus:border-indigo-500 font-bold transition-all text-sm">
                        </div>
                        <div class="space-y-2">
                             <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] pl-1">{{ __('Sequence Rank') }}</label>
                             <input type="number" name="order" value="0" min="0" class="w-full px-5 py-4 bg-slate-50 dark:bg-slate-950 border-2 border-transparent rounded-2xl focus:bg-white dark:focus:bg-slate-800 focus:border-indigo-500 font-bold transition-all text-sm">
                        </div>
                        <div class="md:col-span-2 flex items-center space-x-6 px-2">
                            <label class="flex items-center space-x-3 cursor-pointer group">
                                <input type="checkbox" name="is_loanable" value="1" checked class="rounded-lg border-slate-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <span class="text-xs font-black text-slate-500 uppercase tracking-widest group-hover:text-indigo-600 transition-colors">{{ __('Allow Circulation') }}</span>
                            </label>
                            <label class="flex items-center space-x-3 cursor-pointer group">
                                <input type="checkbox" name="is_active" value="1" checked class="rounded-lg border-slate-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <span class="text-xs font-black text-slate-500 uppercase tracking-widest group-hover:text-indigo-600 transition-colors">{{ __('Operational') }}</span>
                            </label>
                        </div>
                    </div>

                    <div class="mt-10 flex items-center justify-between gap-4 border-t border-slate-50 dark:border-slate-800 pt-8">
                        <button type="button" @click="showAdd = false" class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest hover:text-rose-500 transition-colors">
                            {{ __('Abort') }}
                        </button>
                        <button type="submit" class="px-10 py-4 bg-indigo-600 text-white font-black rounded-3xl shadow-xl shadow-indigo-200 dark:shadow-none hover:bg-indigo-700 transition-all uppercase tracking-widest text-[10px]">
                            {{ __('Commit Definition') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </template>

    <!-- Edit Modal -->
    <template x-if="showEdit">
        <div class="fixed inset-0 z-[100] overflow-y-auto px-4 py-6 sm:px-0 flex items-center justify-center animate-in fade-in duration-300">
            <div class="fixed inset-0 transition-opacity bg-slate-900/60 backdrop-blur-sm" @click="showEdit = false"></div>
            
            <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] shadow-2xl border border-slate-100 dark:border-slate-800 overflow-hidden w-full max-w-xl relative">
                <form :action="'{{ url('topsecret/document-types') }}/' + docType.id" method="POST" class="p-8 md:p-10">
                    @csrf @method('PUT')
                    <div class="flex justify-between items-center mb-8">
                        <div>
                            <h3 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight uppercase">{{ __('Modify Typology') }}</h3>
                            <p class="text-slate-500 text-xs font-bold">{{ __('Updating definition for') }} <span class="text-indigo-600" x-text="docType.name"></span></p>
                        </div>
                        <button type="button" @click="showEdit = false" class="p-2.5 bg-slate-50 dark:bg-slate-800 text-slate-400 hover:text-rose-500 transition-colors rounded-2xl">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                             <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] pl-1">{{ __('Classification Name') }} *</label>
                             <input type="text" name="name" x-model="docType.name" required class="w-full px-5 py-4 bg-slate-50 dark:bg-slate-950 border-2 border-transparent rounded-2xl focus:bg-white dark:focus:bg-slate-800 focus:border-indigo-500 font-bold transition-all text-sm">
                        </div>
                        <div class="space-y-2">
                             <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] pl-1">{{ __('System Code') }} *</label>
                             <input type="text" name="code" x-model="docType.code" required class="w-full px-5 py-4 bg-slate-50 dark:bg-slate-950 border-2 border-transparent rounded-2xl focus:bg-white dark:focus:bg-slate-800 focus:border-indigo-500 font-mono font-bold transition-all text-sm" maxlength="20">
                        </div>
                        <div class="space-y-2 text-left">
                             <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] pl-1">{{ __('MARC Selector') }} (L/06)</label>
                             <input type="text" name="marc_type" x-model="docType.marc_type" class="w-full px-5 py-4 bg-slate-50 dark:bg-slate-950 border-2 border-transparent rounded-2xl focus:bg-white dark:focus:bg-slate-800 focus:border-indigo-500 font-bold transition-all text-sm">
                        </div>
                        <div class="space-y-2">
                             <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] pl-1">{{ __('Glyph Identifier') }} (Icon)</label>
                             <input type="text" name="icon" x-model="docType.icon" class="w-full px-5 py-4 bg-slate-50 dark:bg-slate-950 border-2 border-transparent rounded-2xl focus:bg-white dark:focus:bg-slate-800 focus:border-indigo-500 font-bold transition-all text-sm">
                        </div>
                        <div class="md:col-span-2 space-y-2">
                             <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] pl-1">{{ __('Typology Description') }}</label>
                             <textarea name="description" x-model="docType.description" rows="2" class="w-full px-5 py-4 bg-slate-50 dark:bg-slate-950 border-2 border-transparent rounded-2xl focus:bg-white dark:focus:bg-slate-800 focus:border-indigo-500 font-bold transition-all text-sm"></textarea>
                        </div>
                        <div class="space-y-2">
                             <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] pl-1">{{ __('Base Loan Retention') }} (Days)</label>
                             <input type="number" name="default_loan_days" x-model="docType.default_loan_days" required min="0" max="365" class="w-full px-5 py-4 bg-slate-50 dark:bg-slate-950 border-2 border-transparent rounded-2xl focus:bg-white dark:focus:bg-slate-800 focus:border-indigo-500 font-bold transition-all text-sm">
                        </div>
                        <div class="space-y-2">
                             <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] pl-1">{{ __('Sequence Rank') }}</label>
                             <input type="number" name="order" x-model="docType.order" min="0" class="w-full px-5 py-4 bg-slate-50 dark:bg-slate-950 border-2 border-transparent rounded-2xl focus:bg-white dark:focus:bg-slate-800 focus:border-indigo-500 font-bold transition-all text-sm">
                        </div>
                        <div class="md:col-span-2 flex items-center space-x-6 px-2">
                            <label class="flex items-center space-x-3 cursor-pointer group">
                                <input type="checkbox" name="is_loanable" x-model="docType.is_loanable" value="1" class="rounded-lg border-slate-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <span class="text-xs font-black text-slate-500 uppercase tracking-widest group-hover:text-indigo-600 transition-colors">{{ __('Allow Circulation') }}</span>
                            </label>
                            <label class="flex items-center space-x-3 cursor-pointer group">
                                <input type="checkbox" name="is_active" x-model="docType.is_active" value="1" class="rounded-lg border-slate-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <span class="text-xs font-black text-slate-500 uppercase tracking-widest group-hover:text-indigo-600 transition-colors">{{ __('Operational') }}</span>
                            </label>
                        </div>
                    </div>

                    <div class="mt-10 flex items-center justify-between gap-4 border-t border-slate-50 dark:border-slate-800 pt-8">
                        <button type="button" @click="showEdit = false" class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest hover:text-rose-500 transition-colors">
                            {{ __('Abort') }}
                        </button>
                        <button type="submit" class="px-10 py-4 bg-amber-500 hover:bg-amber-600 text-white font-black rounded-3xl shadow-xl shadow-amber-200 dark:shadow-none transition-all uppercase tracking-widest text-[10px]">
                            {{ __('Commit Changes') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </template>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script src="https://unpkg.com/lucide@latest"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        lucide.createIcons();
        
        const el = document.getElementById('sortable-doc-types');
        if (el) {
            Sortable.create(el, {
                handle: '.drag-handle',
                animation: 150,
                ghostClass: 'bg-indigo-50',
                onEnd: function() {
                    let orders = [];
                    el.querySelectorAll('tr').forEach((row, index) => {
                        orders.push({
                            id: row.dataset.id,
                            order: index
                        });
                    });

                    fetch('{{ route('admin.document-types.order') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        },
                        body: JSON.stringify({ orders: orders })
                    })
                    .then(r => r.json())
                    .then(data => {
                        if (data.success) {
                            window.dispatchEvent(new CustomEvent('toast', {
                                detail: { message: '{{ __("Order updated successfully") }}', type: 'success' }
                            }));
                        }
                    });
                }
            });
        }
    });

</script>
@endpush
@endsection
