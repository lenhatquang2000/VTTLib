@extends('layouts.admin')

@section('content')
<div class="mx-auto pb-12 font-mono-project">
    <div class="space-y-6 lg:space-y-8">
        <!-- Header Section -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="p-6 flex items-center">
                <div class="flex items-center">
                    <a href="{{ route('admin.marc.book') }}" class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-slate-50 text-slate-500 hover:bg-slate-100 hover:text-slate-900 transition-all mr-4">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    </a>
                    <div>
                        <h2 class="text-xl font-bold text-slate-900 tracking-tight leading-none">{{ __('Record_Header') }}</h2>
                        <p class="text-sm text-slate-500 mt-1 font-medium">{{ __('Review_Instruction') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Metadata Section (Vertical Checklist/Dropdowns) -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <form action="{{ route('admin.marc.book.status', $record->id) }}" method="POST" x-data="{ loading: false }" @submit="loading = true">
                @csrf @method('PUT')
                <div class="p-6">
                    <div class="flex flex-col space-y-4">
                        <!-- ... (giữ nguyên các trường select bên trên) ... -->
                        
                        <!-- Cataloging Framework -->
                        <div class="grid grid-cols-1 sm:grid-cols-4 items-center gap-2 sm:gap-4 py-1">
                            <div class="sm:col-span-1">
                                <label class="font-bold text-slate-400 text-[10px] uppercase tracking-widest mb-0">{{ __('Cataloging_Framework') }}</label>
                            </div>
                            <div class="sm:col-span-3">
                                <select name="framework" class="block w-full bg-slate-50 border-transparent rounded-xl text-slate-900 text-sm focus:ring-2 focus:ring-indigo-500 transition-all">
                                    <option value="AVMARC21" {{ $record->framework === 'AVMARC21' ? 'selected' : '' }}>AVMARC21</option>
                                </select>
                            </div>
                        </div>

                        <!-- Status -->
                        <div class="grid grid-cols-1 sm:grid-cols-4 items-center gap-2 sm:gap-4 py-1">
                            <div class="sm:col-span-1">
                                <label class="font-bold text-slate-400 text-[10px] uppercase tracking-widest mb-0">{{ __('Status') }}</label>
                            </div>
                            <div class="sm:col-span-3">
                                <select name="status" class="block w-full bg-slate-50 border-transparent rounded-xl text-slate-900 text-sm focus:ring-2 focus:ring-indigo-500 transition-all cursor-pointer hover:bg-slate-100">
                                    <option value="pending" {{ $record->status === 'pending' ? 'selected' : '' }}>{{ __('New_Status_Text') }}</option>
                                    <option value="approved" {{ $record->status === 'approved' ? 'selected' : '' }}>{{ __('Approved') }}</option>
                                </select>
                            </div>
                        </div>

                        <!-- Subject Category -->
                        <div class="grid grid-cols-1 sm:grid-cols-4 items-center gap-2 sm:gap-4 py-1">
                            <div class="sm:col-span-1">
                                <label class="font-bold text-slate-400 text-[10px] uppercase tracking-widest mb-0">{{ __('Subject_Category') }}</label>
                            </div>
                            <div class="sm:col-span-3">
                                <select name="subject_category" class="block w-full bg-slate-50 border-transparent rounded-xl text-slate-900 text-sm focus:ring-2 focus:ring-indigo-500 transition-all">
                                    <option value="Article" {{ $record->subject_category === 'Article' ? 'selected' : '' }}>{{ __('Selected_Article') }}</option>
                                </select>
                            </div>
                        </div>

                        <!-- Record Type -->
                        <div class="grid grid-cols-1 sm:grid-cols-4 items-center gap-2 sm:gap-4 py-1">
                            <div class="sm:col-span-1">
                                <label class="font-bold text-slate-400 text-[10px] uppercase tracking-widest mb-0">{{ __('Record_Type') }}</label>
                            </div>
                            <div class="sm:col-span-3">
                                <select name="record_type" class="block w-full bg-slate-50 border-transparent rounded-xl text-slate-900 text-sm focus:ring-2 focus:ring-indigo-500 transition-all">
                                    <option value="book" {{ $record->record_type === 'book' ? 'selected' : '' }}>{{ __('Language_Material_Text') }}</option>
                                </select>
                            </div>
                        </div>

                        <!-- Serial Frequency -->
                        <div class="grid grid-cols-1 sm:grid-cols-4 items-center gap-2 sm:gap-4 py-1">
                            <div class="sm:col-span-1">
                                <label class="font-bold text-slate-400 text-[10px] uppercase tracking-widest mb-0">{{ __('Serial_Frequency') }}</label>
                            </div>
                            <div class="sm:col-span-3">
                                <select name="serial_frequency" class="block w-full bg-slate-50 border-transparent rounded-xl text-slate-900 text-sm focus:ring-2 focus:ring-indigo-500 transition-all">
                                    <option value="unknown" {{ $record->serial_frequency === 'unknown' ? 'selected' : '' }}>{{ __('Unknown_Frequency') }}</option>
                                </select>
                            </div>
                        </div>

                        <!-- Publication Date Type -->
                        <div class="grid grid-cols-1 sm:grid-cols-4 items-center gap-2 sm:gap-4 py-1">
                            <div class="sm:col-span-1">
                                <label class="font-bold text-slate-400 text-[10px] uppercase tracking-widest mb-0">{{ __('Publication_Date_Type') }}</label>
                            </div>
                            <div class="sm:col-span-3">
                                <select name="date_type" class="block w-full bg-slate-50 border-transparent rounded-xl text-slate-900 text-sm focus:ring-2 focus:ring-indigo-500 transition-all">
                                    <option value="bc" {{ $record->date_type === 'bc' ? 'selected' : '' }}>{{ __('BC_Date_Involved') }}</option>
                                </select>
                            </div>
                        </div>

                        <!-- Serial Acquisition Method -->
                        <div class="grid grid-cols-1 sm:grid-cols-4 items-center gap-2 sm:gap-4 py-1">
                            <div class="sm:col-span-1">
                                <label class="font-bold text-slate-400 text-[10px] uppercase tracking-widest mb-0">{{ __('Serial_Acquisition_Method') }}</label>
                            </div>
                            <div class="sm:col-span-3">
                                <select name="acquisition_method" class="block w-full bg-slate-50 border-transparent rounded-xl text-slate-900 text-sm focus:ring-2 focus:ring-indigo-500 transition-all">
                                    <option value="untraced" {{ $record->acquisition_method === 'untraced' ? 'selected' : '' }}>{{ __('Untraced_Serials_Text') }}</option>
                                </select>
                            </div>
                        </div>

                        <!-- Document Format -->
                        <div class="grid grid-cols-1 sm:grid-cols-4 items-center gap-2 sm:gap-4 py-1">
                            <div class="sm:col-span-1">
                                <label class="font-bold text-slate-400 text-[10px] uppercase tracking-widest mb-0">{{ __('Document_Format') }}</label>
                            </div>
                            <div class="sm:col-span-3">
                                <select name="document_format" class="block w-full bg-slate-50 border-transparent rounded-xl text-slate-900 text-sm focus:ring-2 focus:ring-indigo-500 transition-all">
                                    <option value="none" {{ $record->document_format === 'none' ? 'selected' : '' }}>{{ __('None_Format') }}</option>
                                </select>
                            </div>
                        </div>

                        <!-- Cataloging Standard -->
                        <div class="grid grid-cols-1 sm:grid-cols-4 items-center gap-2 sm:gap-4 py-1">
                            <div class="sm:col-span-1">
                                <label class="font-bold text-slate-400 text-[10px] uppercase tracking-widest mb-0">{{ __('Cataloging_Standard') }}</label>
                            </div>
                            <div class="sm:col-span-3">
                                <select name="cataloging_standard" class="block w-full bg-slate-50 border-transparent rounded-xl text-slate-900 text-sm focus:ring-2 focus:ring-indigo-500 transition-all">
                                    <option value="AACR2" {{ $record->cataloging_standard === 'AACR2' ? 'selected' : '' }}>AACR-2</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="mt-8 flex justify-end">
                        <button type="submit" 
                                :disabled="loading"
                                class="inline-flex items-center px-6 py-2.5 bg-indigo-600 border border-transparent rounded-xl font-bold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 active:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all shadow-lg shadow-indigo-100 disabled:opacity-50 disabled:cursor-not-allowed">
                            <span x-show="!loading" class="flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                {{ __('Save Changes') }}
                            </span>
                            <span x-show="loading" class="flex items-center" x-cloak>
                                <svg class="animate-spin -ml-1 mr-3 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Processing...
                            </span>
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- MARC Record Display -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="bg-slate-900 px-6 py-4 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div class="flex items-center space-x-3">
                    <span class="bg-indigo-600 text-[10px] font-bold text-white px-2 py-0.5 rounded uppercase tracking-wider">MARC</span>
                    <h3 class="text-white font-bold text-sm leading-none">{{ __('Cataloging_Info_Quick_View') }}</h3>
                </div>
                <span class="font-mono text-indigo-400 text-xs tracking-widest opacity-80">{{ $record->leader }}</span>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full border-collapse">
                    <thead>
                        <tr class="bg-slate-50 text-left border-b border-slate-100">
                            <th class="pl-8 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] w-64">{{ __('Tag') }}</th>
                            <th class="px-4 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] text-center w-24">{{ __('Ind') }}</th>
                            <th class="py-4 pr-8 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">{{ __('Content_Data') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 font-monospace-custom">
                        @foreach($record->fields as $field)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="pl-8 py-4 whitespace-nowrap align-top">
                                    <div class="flex items-baseline space-x-3">
                                        <span class="text-indigo-600 font-bold tracking-tight">{{ $field->tag }}</span>
                                        <span class="text-[9px] text-slate-400 uppercase font-sans tracking-tight leading-tight">
                                            {{ $definitions->get($field->tag)->label ?? '' }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-4 py-4 text-center border-x border-slate-50 align-top">
                                    <span class="text-slate-400 tracking-[0.2em] text-xs opacity-60 font-mono">{{ $field->indicator1 ?: '#' }}{{ $field->indicator2 ?: '#' }}</span>
                                </td>
                                <td class="py-4 pr-8">
                                    <div class="flex flex-col space-y-2">
                                        @foreach($field->subfields as $sub)
                                            <div class="flex items-start space-x-2.5">
                                                <span class="text-emerald-600 font-bold shrink-0 font-mono">${{ $sub->code }}</span>
                                                <span class="text-slate-700 grow break-words min-w-0 leading-relaxed">{{ $sub->value }}</span>
                                                
                                                @php
                                                    $def = $definitions->get($field->tag);
                                                    $subDef = $def ? $def->subfields->where('code', $sub->code)->first() : null;
                                                @endphp
                                                @if($subDef)
                                                    <span class="shrink-0 bg-slate-100 text-slate-400 text-[8px] px-1.5 py-0.5 rounded font-sans uppercase tracking-widest mt-1">[{{ $subDef->label }}]</span>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Footnote Metadata -->
        <div class="bg-indigo-50/30 rounded-2xl p-8 border border-indigo-50 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-indigo-50 rounded-full blur-3xl -mr-16 -mt-16 opacity-50"></div>
            <h6 class="text-[10px] font-bold text-indigo-400 uppercase tracking-[0.2em] mb-6 relative">{{ __('Record_Metadata') }}</h6>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 relative">
                <div>
                    <span class="block text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1">{{ __('Created_At') }}</span>
                    <span class="text-sm font-bold text-slate-900 font-mono tracking-tight">{{ $record->created_at->format('Y-m-d H:i') }}</span>
                </div>
                <div>
                    <span class="block text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1">{{ __('Last_Modified') }}</span>
                    <span class="text-sm font-bold text-slate-900 font-mono tracking-tight">{{ $record->updated_at->format('Y-m-d H:i') }}</span>
                </div>
                <div>
                    <span class="block text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1">{{ __('Record_Type') }}</span>
                    <span class="text-sm font-bold text-slate-900 uppercase font-mono tracking-tight">{{ $record->record_type }}</span>
                </div>
                <div>
                    <span class="block text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1">{{ __('Control_Number') }}</span>
                    <span class="text-sm font-bold text-indigo-600 font-mono">#{{ $record->id }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .font-mono-project {
        font-family: 'JetBrains Mono', monospace !important;
    }
    .font-monospace-custom {
        font-family: 'JetBrains Mono', monospace !important;
        font-size: 0.875rem;
    }
    input, select, textarea {
        font-family: 'JetBrains Mono', monospace !important;
    }
    .hover-bg-gray:hover {
        background-color: #f1f5f9 !important;
    }
</style>
@endsection
