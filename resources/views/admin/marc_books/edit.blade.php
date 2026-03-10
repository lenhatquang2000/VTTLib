@extends('layouts.admin')

@section('content')
<div class="space-y-6 w-full pb-20" x-data="catalogWizard()" x-init="$nextTick(() => debugSubfieldBindings())">
    <!-- Header Section -->
    <div class="flex justify-between items-start bg-white dark:bg-slate-900 p-6 rounded-xl shadow-sm border border-gray-100 dark:border-slate-800">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 dark:text-slate-100">{{ __('Modify_MARC_Record') }}</h2>
            <p class="text-sm text-gray-500 dark:text-slate-400 mt-1">{{ __('Update_Instruction', ['id' => $record->id]) }}</p>
            <div class="mt-4 flex flex-col gap-2 md:flex-row md:items-center">
                <label class="text-sm font-bold text-gray-700 dark:text-slate-300 mb-0">{{ __('Cataloging_Framework') }}</label>
                <select x-model="formData.framework" name="framework"
                    onchange="window.location.href = '?framework_id=' + (this.options[this.selectedIndex].getAttribute('data-id'))"
                    class="w-full md:w-[360px] px-4 py-2.5 border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 dark:text-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors appearance-none cursor-pointer">
                    @foreach($frameworks as $fw)
                        <option value="{{ $fw->code }}" data-id="{{ $fw->id }}" {{ $frameworkId == $fw->id ? 'selected' : '' }}>
                            {{ $fw->name }} ({{ $fw->code }})
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.marc.book') }}"
                class="flex items-center px-4 py-2.5 text-sm font-semibold bg-gray-100 dark:bg-slate-800 text-gray-600 dark:text-slate-400 hover:bg-gray-200 dark:hover:bg-slate-700 rounded-lg transition">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Quay lại
            </a>
        </div>
    </div>

    <!-- Tabs Navigation -->
    <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-gray-100 dark:border-slate-800 overflow-hidden">
        <div class="flex border-b border-gray-200 dark:border-slate-700">
            <template x-for="(step, index) in steps" :key="index">
                <button type="button" 
                    @click="goToStep(index)"
                    class="flex-1 py-4 px-6 text-sm font-semibold transition-all duration-200 focus:outline-none flex items-center justify-center space-x-3"
                    :class="currentStep === index ? 'bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600 dark:text-indigo-400 border-b-2 border-indigo-600 dark:border-indigo-400' : 'text-gray-500 dark:text-slate-400 hover:text-gray-700 dark:hover:text-slate-300 hover:bg-gray-50 dark:hover:bg-slate-800'">
                    <span class="flex items-center justify-center w-6 h-6 rounded-full text-xs font-bold"
                          :class="currentStep === index ? 'bg-indigo-600 text-white' : 'bg-gray-200 dark:bg-slate-700 text-gray-600 dark:text-slate-400'"
                          x-text="index + 1"></span>
                    <span x-text="step.title"></span>
                </button>
            </template>
        </div>
    </div>

    <form id="catalogForm" action="{{ route('admin.marc.book.update', $record->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')
        <input type="hidden" name="tab" :value="currentStep">

        <!-- Step 1: Đầu biểu (Leader/Metadata) -->
        <div x-show="currentStep === 0" x-cloak class="space-y-6">
            <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-gray-100 dark:border-slate-800 p-8">
                <div class="mb-8">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-slate-100 mb-2">{{ __('Record_Leader') }}</h3>
                    <p class="text-sm text-gray-500 dark:text-slate-400">{{ __('Leader_Instruction') }}</p>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                    <!-- Cover Image -->
                    <div class="md:col-span-2 pb-4 border-b border-gray-100 dark:border-slate-800">
                        <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-3">{{ __('Cover_Image') }}</label>
                        <input type="file" name="cover_image" accept="image/*" 
                            class="w-full px-4 py-3 border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 dark:text-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                    </div>

                    <!-- Status -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-3">{{ __('Status') }}</label>
                        <select x-model="formData.status" name="status" 
                            class="w-full px-4 py-3 border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 dark:text-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors appearance-none cursor-pointer">
                            <option value="pending">{{ __('New_Status_Text') }}</option>
                            <option value="approved">{{ __('Approved') }}</option>
                        </select>
                    </div>

                    <!-- Subject Category -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-3">{{ __('Subject_Category') }}</label>
                        <select x-model="formData.subject_category" name="subject_category" 
                            class="w-full px-4 py-3 border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 dark:text-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors appearance-none cursor-pointer">
                            <option value="Article">{{ __('Selected_Article') }}</option>
                        </select>
                    </div>

                    <!-- Record Type -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-3">{{ __('Record_Type') }}</label>
                        <select x-model="formData.record_type" name="record_type" 
                            class="w-full px-4 py-3 border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 dark:text-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors appearance-none cursor-pointer">
                            <option value="book">{{ __('Language_Material_Text') }}</option>
                        </select>
                    </div>

                    <!-- Serial Frequency -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-3">{{ __('Serial_Frequency') }}</label>
                        <select x-model="formData.serial_frequency" name="serial_frequency" 
                            class="w-full px-4 py-3 border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 dark:text-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors appearance-none cursor-pointer">
                            <option value="unknown">{{ __('Unknown_Frequency') }}</option>
                        </select>
                    </div>

                    <!-- Publication Date Type -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-3">{{ __('Publication_Date_Type') }}</label>
                        <select x-model="formData.date_type" name="date_type" 
                            class="w-full px-4 py-3 border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 dark:text-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors appearance-none cursor-pointer">
                            <option value="bc">{{ __('BC_Date_Involved') }}</option>
                        </select>
                    </div>

                    <!-- Serial Acquisition Method -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-3">{{ __('Serial_Acquisition_Method') }}</label>
                        <select x-model="formData.acquisition_method" name="acquisition_method" 
                            class="w-full px-4 py-3 border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 dark:text-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors appearance-none cursor-pointer">
                            <option value="untraced">{{ __('Untraced_Serials_Text') }}</option>
                        </select>
                    </div>

                    <!-- Document Format -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-3">{{ __('Document_Format') }}</label>
                        <select x-model="formData.document_format" name="document_format" 
                            class="w-full px-4 py-3 border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 dark:text-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors appearance-none cursor-pointer">
                            <option value="none">{{ __('None_Format') }}</option>
                        </select>
                    </div>

                    <!-- Cataloging Standard -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-3">{{ __('Cataloging_Standard') }}</label>
                        <select x-model="formData.cataloging_standard" name="cataloging_standard" 
                            class="w-full px-4 py-3 border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 dark:text-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors appearance-none cursor-pointer">
                            <option value="AACR2">AACR-2</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Step 2: Biên mục (MARC Fields) -->
        <div x-show="currentStep === 1" x-cloak class="space-y-6">
            @foreach($definitions as $tag)
                <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-gray-100 dark:border-slate-800 overflow-hidden"
                    x-data="{ expanded: {{ $tag->tag == '245' ? 'true' : 'false' }} }">
                    <div class="p-4 bg-gray-50/50 dark:bg-slate-800/50 flex justify-between items-center cursor-pointer border-b border-gray-50 dark:border-slate-800"
                        @click="expanded = !expanded">
                        <div class="flex items-center space-x-4">
                            <span class="bg-gray-800 dark:bg-slate-950 text-white px-3 py-1 rounded font-mono font-bold">{{ $tag->tag }}</span>
                            <h4 class="font-bold text-gray-700 dark:text-slate-200 uppercase text-xs">{{ $tag->label }}</h4>
                        </div>
                        <div class="flex items-center space-x-4">
                            <div class="flex flex-col items-center">
                                <span class="text-[8px] text-slate-500 dark:text-slate-400 font-bold uppercase mb-1">Ind</span>
                                <div class="flex space-x-1">
                                    <input type="text" name="fields[{{ $tag->tag }}][ind1]" x-model="marcFields['{{ $tag->tag }}'].ind1" placeholder="#" maxlength="1"
                                        class="w-7 h-7 p-0 text-center border border-gray-300 dark:border-slate-600 rounded text-xs font-mono uppercase bg-white dark:bg-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-indigo-500 transition-all"
                                        title="{{ __('Indicator') }} 1"
                                        @click.stop>
                                    <input type="text" name="fields[{{ $tag->tag }}][ind2]" x-model="marcFields['{{ $tag->tag }}'].ind2" placeholder="#" maxlength="1"
                                        class="w-7 h-7 p-0 text-center border border-gray-300 dark:border-slate-600 rounded text-xs font-mono uppercase bg-white dark:bg-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-indigo-500 transition-all"
                                        title="{{ __('Indicator') }} 2"
                                        @click.stop>
                                </div>
                            </div>
                            <svg class="w-4 h-4 text-gray-400 transition-transform duration-200"
                                :class="expanded ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </div>

                    <div class="p-6" x-show="expanded" x-cloak x-collapse>
                        <div class="space-y-4">
                            <template x-for="(row, index) in marcFields['{{ $tag->tag }}'].subfields" :key="index">
                                <div class="flex flex-col md:flex-row gap-4 items-start bg-gray-50/50 dark:bg-slate-800/30 p-4 rounded-lg border border-gray-200 dark:border-slate-700 group hover:border-indigo-200 dark:hover:border-indigo-800 transition-colors">
                                    <!-- Subfield Selector -->
                                    <div class="w-full md:w-1/3">
                                        <select :name="'fields[' + '{{ $tag->tag }}' + '][subfields][' + index + '][code]'" 
                                                x-model="row.code"
                                                x-init="row.code = ((row.code ?? '').toString().trim().replace(/^\$/, ''))"
                                                x-effect="$el.value = ((row.code ?? '').toString().trim().replace(/^\$/, '').toLowerCase())"
                                                class="w-full px-4 py-3 border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-900 dark:text-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 font-mono transition-colors appearance-none cursor-pointer">
                                            <option value="">{{ __('Select_Subfield') }}</option>
                                            <template x-for="def in marcFields['{{ $tag->tag }}'].subfieldDefinitions" :key="def.code">
                                                <option :value="def.code" :selected="def.code === ((row.code ?? '').toString().trim().replace(/^\$/, '').toLowerCase())" x-text="'$' + def.code + ' ' + def.label"></option>
                                            </template>
                                        </select>
                                    </div>

                                    <!-- Value Input -->
                                    <div class="w-full md:w-2/3 flex gap-3">
                                        <input type="hidden" :name="'fields[' + '{{ $tag->tag }}' + '][subfields][' + index + '][id]'" x-model="row.id">
                                        <input type="text" 
                                               :name="'fields[' + '{{ $tag->tag }}' + '][subfields][' + index + '][value]'" 
                                               x-model="row.value"
                                               placeholder="{{ __('Enter_Value') }}"
                                               class="flex-1 px-4 py-3 border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-900 dark:text-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                                        
                                        <button type="button" @click="marcFields['{{ $tag->tag }}'].subfields.splice(index, 1)" 
                                            class="flex-shrink-0 w-10 h-10 flex items-center justify-center text-gray-400 dark:text-slate-600 hover:text-white hover:bg-rose-500 dark:hover:bg-rose-600 rounded-lg transition-all opacity-0 group-hover:opacity-100">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <button type="button" @click="marcFields['{{ $tag->tag }}'].subfields.push({ id: null, code: '', value: '' })" 
                                class="mt-5 inline-flex items-center px-4 py-2.5 text-sm font-bold text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 bg-indigo-50 dark:bg-indigo-900/20 hover:bg-indigo-100 dark:hover:bg-indigo-900/30 rounded-lg transition-all">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                            {{ __('Add_Subfield') }}
                        </button>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Step 3: Phân phối (Distribution) -->
        <div x-show="currentStep === 2" x-cloak class="space-y-6">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
                <!-- Hidden inputs for form submission -->
                <div class="hidden">
                    <template x-for="(item, index) in items" :key="index">
                        <div>
                            <input type="hidden" :name="'items['+index+'][id]'" :value="item.id">
                            <input type="hidden" :name="'items['+index+'][branch_id]'" :value="item.branch_id">
                            <input type="hidden" :name="'items['+index+'][storage_location_id]'" :value="item.storage_location_id">
                            <input type="hidden" :name="'items['+index+'][barcode]'" :value="item.barcode">
                            <input type="hidden" :name="'items['+index+'][accession_number]'" :value="item.accession_number">
                            <input type="hidden" :name="'items['+index+'][storage_type]'" :value="item.storage_type">
                            <input type="hidden" :name="'items['+index+'][quantity]'" :value="item.quantity">
                            <input type="hidden" :name="'items['+index+'][status]'" :value="item.status">
                            <input type="hidden" :name="'items['+index+'][order_code]'" :value="item.order_code">
                            <input type="hidden" :name="'items['+index+'][waits_for_print]'" :value="item.waits_for_print ? 1 : 0">
                            <input type="hidden" :name="'items['+index+'][notes]'" :value="item.notes">
                            <input type="hidden" :name="'items['+index+'][volume_issue]'" :value="item.volume_issue">
                            <input type="hidden" :name="'items['+index+'][day]'" :value="item.day">
                            <input type="hidden" :name="'items['+index+'][month_season]'" :value="item.month_season">
                            <input type="hidden" :name="'items['+index+'][year]'" :value="item.year">
                            <input type="hidden" :name="'items['+index+'][shelf]'" :value="item.shelf">
                            <input type="hidden" :name="'items['+index+'][shelf_position]'" :value="item.shelf_position">
                            <input type="hidden" :name="'items['+index+'][location]'" :value="item.location">
                            <input type="hidden" :name="'items['+index+'][temporary_location]'" :value="item.temporary_location">
                        </div>
                    </template>
                </div>

                <!-- LEFT: Add/Edit Form -->
                <div class="lg:col-span-4 bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-gray-100 dark:border-slate-800 overflow-hidden sticky top-6">
                    <div class="p-5 border-b border-gray-100 dark:border-slate-800 bg-gray-50/50 dark:bg-slate-800/50">
                        <h3 class="text-sm font-bold text-gray-800 dark:text-slate-100 uppercase tracking-wider flex items-center">
                            <span class="w-2 h-2 bg-indigo-500 rounded-full mr-2" :class="editingIndex !== null ? 'animate-pulse' : ''"></span>
                            <span x-text="editingIndex !== null ? '{{ __('Modify Item') }}' : '{{ __('Add New Item') }}'"></span>
                        </h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="grid grid-cols-1 gap-4">
                            <div class="space-y-1">
                                <label class="block text-[10px] font-bold text-gray-500 dark:text-slate-400 uppercase tracking-widest">{{ __('Barcode') }}</label>
                                <input type="text" x-model="newItem.barcode"
                                    class="w-full px-4 py-2.5 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-lg text-sm font-mono focus:ring-2 focus:ring-indigo-500 transition-all"
                                    placeholder="{{ $nextBarcode ?? 'AUTO' }}">
                            </div>
                            <div class="space-y-1">
                                <label class="block text-[10px] font-bold text-gray-500 dark:text-slate-400 uppercase tracking-widest">{{ __('Accession Number') }}</label>
                                <input type="text" x-model="newItem.accession_number"
                                    class="w-full px-4 py-2.5 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-lg text-sm font-mono focus:ring-2 focus:ring-indigo-500 transition-all"
                                    placeholder="ACC-XXXXXX">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div class="space-y-1">
                                <label class="block text-[10px] font-bold text-gray-500 dark:text-slate-400 uppercase tracking-widest">{{ __('Branch') }}</label>
                                <select x-model="newItem.branch_id" 
                                    class="w-full px-4 py-2.5 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 appearance-none">
                                    <option value="">-- {{ __('Select') }} --</option>
                                    <template x-for="branch in branches" :key="branch.id">
                                        <option :value="branch.id" x-text="branch.name"></option>
                                    </template>
                                </select>
                            </div>
                            <div class="space-y-1">
                                <label class="block text-[10px] font-bold text-gray-500 dark:text-slate-400 uppercase tracking-widest">{{ __('Storage Location') }}</label>
                                <select x-model="newItem.storage_location_id" :disabled="!newItem.branch_id"
                                    class="w-full px-4 py-2.5 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 appearance-none disabled:opacity-50">
                                    <option value="">-- {{ __('Select') }} --</option>
                                    <template x-for="loc in activeLocations" :key="loc.id">
                                        <option :value="loc.id" x-text="loc.name"></option>
                                    </template>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div class="space-y-1">
                                <label class="block text-[10px] font-bold text-gray-500 dark:text-slate-400 uppercase tracking-widest">{{ __('Storage Type') }}</label>
                                <select x-model="newItem.storage_type" 
                                    class="w-full px-4 py-2.5 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 appearance-none">
                                    <option value="Book">{{ __('Book') }}</option>
                                    <option value="Daily newspaper">{{ __('Daily newspaper') }}</option>
                                    <option value="Magazine">{{ __('Magazine') }}</option>
                                </select>
                            </div>
                            <div class="space-y-1">
                                <label class="block text-[10px] font-bold text-gray-500 dark:text-slate-400 uppercase tracking-widest">{{ __('Status') }}</label>
                                <select x-model="newItem.status" 
                                    class="w-full px-4 py-2.5 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 appearance-none">
                                    <option value="available">{{ __('Available') }}</option>
                                    <option value="borrowed">{{ __('Borrowed') }}</option>
                                    <option value="lost">{{ __('Lost') }}</option>
                                    <option value="damaged">{{ __('Damaged') }}</option>
                                </select>
                            </div>
                        </div>

                        <div class="p-4 bg-indigo-50/30 dark:bg-indigo-900/10 rounded-xl border border-indigo-100 dark:border-indigo-900/30 space-y-4">
                            <div class="flex justify-between items-center">
                                <span class="text-[9px] font-bold text-indigo-600 dark:text-indigo-400 uppercase tracking-widest">{{ __('Technical Specs') }}</span>
                                <div class="flex items-center">
                                    <input type="checkbox" x-model="newItem.waits_for_print" id="edit_waits_for_print" class="rounded text-indigo-600 focus:ring-0 w-3 h-3">
                                    <label for="edit_waits_for_print" class="ml-1.5 text-[8px] font-bold text-gray-500 uppercase">{{ __('Wait for print') }}</label>
                                </div>
                            </div>
                            <div class="grid grid-cols-3 gap-2">
                                <input type="number" x-model="newItem.day" placeholder="DD" class="bg-white dark:bg-slate-800 border-gray-200 dark:border-slate-700 text-xs rounded py-1.5 text-center px-1">
                                <input type="text" x-model="newItem.month_season" placeholder="MM/S" class="bg-white dark:bg-slate-800 border-gray-200 dark:border-slate-700 text-xs rounded py-1.5 text-center px-1">
                                <input type="number" x-model="newItem.year" placeholder="YYYY" class="bg-white dark:bg-slate-800 border-gray-200 dark:border-slate-700 text-xs rounded py-1.5 text-center px-1">
                            </div>
                            <div class="grid grid-cols-2 gap-2">
                                <input type="text" x-model="newItem.shelf" placeholder="{{ __('Shelf') }}" class="bg-white dark:bg-slate-800 border-gray-200 dark:border-slate-700 text-xs rounded py-1.5 px-3">
                                <input type="text" x-model="newItem.shelf_position" placeholder="{{ __('Position') }}" class="bg-white dark:bg-slate-800 border-gray-200 dark:border-slate-700 text-xs rounded py-1.5 px-3">
                            </div>
                        </div>

                        <textarea x-model="newItem.notes" rows="2" class="w-full px-4 py-2.5 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-lg text-sm" placeholder="{{ __('Notes') }}..."></textarea>

                        <div class="flex gap-2 pt-2">
                            <button type="button" @click="addItem()" class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 rounded-xl transition-all shadow-sm flex items-center justify-center">
                                <svg x-show="editingIndex === null" class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                <svg x-show="editingIndex !== null" class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                <span x-text="editingIndex !== null ? '{{ __('Update Item') }}' : '{{ __('Include Item') }}'"></span>
                            </button>
                            <button type="button" x-show="editingIndex !== null" @click="resetNewItem()" class="px-4 py-3 bg-gray-100 dark:bg-slate-800 text-gray-600 dark:text-slate-400 rounded-xl hover:bg-gray-200 transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- RIGHT: List Table -->
                <div class="lg:col-span-8 bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-gray-100 dark:border-slate-800 overflow-hidden">
                    <div class="p-5 border-b border-gray-100 dark:border-slate-800 bg-gray-50/50 dark:bg-slate-800/50 flex justify-between items-center">
                        <h3 class="text-sm font-bold text-gray-800 dark:text-slate-100 uppercase tracking-wider">{{ __('Items in Queue') }}</h3>
                        <span class="bg-indigo-100 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-400 text-[10px] font-bold px-2.5 py-1 rounded-full" x-text="items.length + ' {{ __('Items') }}'"></span>
                    </div>
                    <div class="overflow-x-auto min-h-[400px]">
                        <table class="w-full text-left">
                            <thead class="bg-gray-50 dark:bg-slate-800/50 text-[10px] font-bold text-gray-400 dark:text-slate-500 uppercase tracking-widest border-b border-gray-100 dark:border-slate-800">
                                <tr>
                                    <th class="px-6 py-3">{{ __('Identification') }}</th>
                                    <th class="px-6 py-3">{{ __('Storage') }}</th>
                                    <th class="px-6 py-3">{{ __('Status') }}</th>
                                    <th class="px-6 py-3"></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50 dark:divide-slate-800">
                                <template x-for="(item, index) in items" :key="index">
                                    <tr class="hover:bg-gray-50 dark:hover:bg-slate-800/50 transition-colors group">
                                        <td class="px-6 py-4">
                                            <div class="flex flex-col">
                                                <span class="text-sm font-bold text-gray-800 dark:text-slate-200 font-mono" x-text="item.barcode || 'AUTO'"></span>
                                                <span class="text-[10px] text-gray-400 dark:text-slate-500 font-mono" x-text="'#' + item.accession_number"></span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex flex-col">
                                                <span class="text-xs font-semibold text-gray-700 dark:text-slate-300" x-text="item.storage_type"></span>
                                                <div class="flex items-center space-x-1 mt-1">
                                                    <span class="text-[9px] px-1.5 py-0.5 bg-gray-100 dark:bg-slate-800 text-gray-600 dark:text-slate-400 rounded" x-text="getBranchName(item.branch_id)"></span>
                                                    <span class="text-[9px] px-1.5 py-0.5 bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600 dark:text-indigo-400 rounded" x-text="getLocationName(item.branch_id, item.storage_location_id)"></span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[9px] font-bold uppercase tracking-wider"
                                                :class="{
                                                    'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400': item.status === 'available',
                                                    'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400': item.status === 'borrowed',
                                                    'bg-rose-100 text-rose-700 dark:bg-rose-900/30 dark:text-rose-400': item.status === 'lost',
                                                    'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400': item.status === 'damaged'
                                                }">
                                                <span class="w-1 h-1 rounded-full bg-current mr-1.5"></span>
                                                <span x-text="item.status"></span>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <div class="flex items-center justify-end space-x-2">
                                                <button type="button" @click="editItem(index)" class="p-2 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 rounded-lg transition-all">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                                </button>
                                                <button type="button" @click="removeItem(index)" class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-all">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                </template>
                                <template x-if="items.length === 0">
                                    <tr>
                                        <td colspan="4" class="px-6 py-20 text-center">
                                            <div class="flex flex-col items-center">
                                                <svg class="w-12 h-12 text-gray-200 dark:text-slate-800 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0a2 2 0 01-2 2H6a2 2 0 01-2-2m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                                <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">{{ __('No items added yet') }}</span>
                                            </div>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Step 4: MARC Preview -->
        <div x-show="currentStep === 3" x-cloak class="space-y-6">
            <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-gray-100 dark:border-slate-800 overflow-hidden">
                <div class="bg-slate-900 px-6 py-4 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                    <div class="flex items-center space-x-3">
                        <span class="bg-indigo-600 text-[10px] font-bold text-white px-2 py-0.5 rounded uppercase tracking-wider">MARC PREVIEW</span>
                        <h3 class="text-white font-bold text-sm leading-none">{{ __('MARC21_Cataloging_Form') }}</h3>
                    </div>
                    <span class="font-mono text-indigo-400 text-xs tracking-widest opacity-80">00000nam a2200000 i 4500</span>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full border-collapse">
                        <thead>
                            <tr class="bg-slate-50 dark:bg-slate-800/50 text-left border-b border-slate-100 dark:border-slate-800">
                                <th class="pl-8 py-4 text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em] w-64">{{ __('Tag') }}</th>
                                <th class="px-4 py-4 text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em] text-center w-24">{{ __('Ind') }}</th>
                                <th class="py-4 pr-8 text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em]">{{ __('Content_Data') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50 dark:divide-slate-800 font-mono text-sm">
                            <!-- Leader row (Always shown) -->
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="pl-8 py-4 whitespace-nowrap align-top">
                                    <div class="flex items-baseline space-x-3">
                                        <span class="text-indigo-600 font-bold tracking-tight">000</span>
                                        <span class="text-[9px] text-slate-400 uppercase font-sans tracking-tight leading-tight">LEADER</span>
                                    </div>
                                </td>
                                <td class="px-4 py-4 text-center border-x border-slate-50 dark:border-slate-800 align-top">
                                    <span class="text-slate-400 tracking-[0.2em] text-xs opacity-60 font-mono">##</span>
                                </td>
                                <td class="py-4 pr-8">
                                    <span class="text-slate-700 dark:text-slate-300 font-mono" x-text="'00000nam a2200000 i 4500'"></span>
                                </td>
                            </tr>

                            <!-- Dynamic MARC Fields -->
                            <template x-for="field in getActiveFields()" :key="field.tag">
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="pl-8 py-4 whitespace-nowrap align-top">
                                        <div class="flex items-baseline space-x-3">
                                            <span class="text-indigo-600 font-bold tracking-tight" x-text="field.tag"></span>
                                            <span class="text-[9px] text-slate-400 uppercase font-sans tracking-tight leading-tight" x-text="field.label"></span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 text-center border-x border-slate-50 dark:border-slate-800 align-top">
                                        <span class="text-slate-400 tracking-[0.2em] text-xs opacity-60 font-mono" x-text="(field.ind1?.trim() || '#') + (field.ind2?.trim() || '#')"></span>
                                    </td>
                                    <td class="py-4 pr-8">
                                        <div class="flex flex-col space-y-2">
                                            <template x-for="(sub, subIdx) in field.subfields.filter(s => s.code || s.value)" :key="subIdx">
                                                <div class="flex items-start space-x-2.5">
                                                    <span class="text-emerald-600 dark:text-emerald-400 font-bold shrink-0 font-mono" x-text="'$' + (sub.code || '?')"></span>
                                                    <span class="text-slate-700 dark:text-slate-300 grow break-words min-w-0 leading-relaxed" x-text="sub.value || '...'"></span>
                                                    <span class="shrink-0 bg-slate-100 dark:bg-slate-800 text-slate-400 dark:text-slate-500 text-[8px] px-1.5 py-0.5 rounded font-sans uppercase tracking-widest mt-1" 
                                                          x-show="sub.code && getSubfieldLabel(field.tag, sub.code)"
                                                          x-text="'[' + getSubfieldLabel(field.tag, sub.code) + ']'"></span>
                                                </div>
                                            </template>
                                        </div>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex justify-end bg-white dark:bg-slate-900 p-6 rounded-xl shadow-sm border border-gray-100 dark:border-slate-800">
            <button type="button" @click="submitForm()"
                class="px-8 py-3 rounded-xl text-sm font-bold bg-green-600 hover:bg-green-700 text-white transition shadow-lg shadow-green-100 dark:shadow-none flex items-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <span>{{ __('Save_Record') }}</span>
            </button>
        </div>
    </form>
</div>

@php
    $initialFields = [];
    foreach($definitions as $tag) {
        $existingField = $record->fields->where('tag', $tag->tag)->first();
        $subfieldsData = [['id' => null, 'code' => '', 'value' => '']];
        $subfieldDefinitions = $tag->subfields
            ->map(fn($s) => ['code' => strtolower(ltrim(trim((string) $s->code), '$')), 'label' => $s->label])
            ->keyBy('code');
        
        if ($existingField && $existingField->subfields->count() > 0) {
            $subfieldsData = $existingField->subfields->map(function($s) {
                return [
                    'id' => $s->id,
                    'code' => strtolower(ltrim(trim((string) $s->code), '$')),
                    'value' => $s->value
                ];
            })->toArray();

            // Ensure every saved subfield code exists in the select options.
            foreach ($existingField->subfields as $savedSubfield) {
                $savedCode = strtolower(ltrim(trim((string) $savedSubfield->code), '$'));
                if ($savedCode !== '' && !$subfieldDefinitions->has($savedCode)) {
                    $subfieldDefinitions->put($savedCode, [
                        'code' => $savedCode,
                        'label' => __('Saved')
                    ]);
                }
            }
        }

        $initialFields[$tag->tag] = [
            'tag' => $tag->tag,
            'label' => $tag->label,
            'ind1' => $existingField->indicator1 ?? ' ',
            'ind2' => $existingField->indicator2 ?? ' ',
            'subfields' => $subfieldsData,
            'subfieldDefinitions' => $subfieldDefinitions->values()
        ];
    }
    
    $initialItemsData = (isset($record) && $record->items->count() > 0) 
        ? $record->items->map(fn($i) => [
            'id' => $i->id, 
            'branch_id' => $i->branch_id,
            'storage_location_id' => $i->storage_location_id, 
            'quantity' => $i->quantity, 
            'barcode' => $i->barcode, 
            'accession_number' => $i->accession_number,
            'storage_type' => $i->storage_type,
            'status' => $i->status,
            'order_code' => $i->order_code,
            'waits_for_print' => $i->waits_for_print,
            'notes' => $i->notes,
            'volume_issue' => $i->volume_issue,
            'day' => $i->day,
            'month_season' => $i->month_season,
            'year' => $i->year,
            'shelf' => $i->shelf,
            'shelf_position' => $i->shelf_position,
            'location' => $i->location,
            'temporary_location' => $i->temporary_location
        ])
        : [];
@endphp
<script>
const itemsData = @json($initialItemsData);

function catalogWizard() {
    return {
        currentStep: parseInt(new URLSearchParams(window.location.search).get('tab')) || 0,
        steps: [
            { title: '{{ __("Leader_Info") }}' },
            { title: '{{ __("Cataloging") }}' },
            { title: '{{ __("Distribution") }}' },
            { title: '{{ __("Preview") }}' }
        ],
        init() {
            // Auto-scroll to tabs section when page loads with tab parameter
            const tabParam = new URLSearchParams(window.location.search).get('tab');
            if (tabParam !== null) {
                this.$nextTick(() => {
                    const tabsElement = document.querySelector('.bg-white.dark\\:bg-slate-900.rounded-xl.shadow-sm.border');
                    if (tabsElement) {
                        tabsElement.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    }
                });
            }
        },
        formData: {
            framework: "{{ $record->framework ?? ($frameworks->where('id', $frameworkId ?? null)->first()->code ?? 'AVMARC21') }}",
            status: "{{ $record->status ?? 'pending' }}",
            subject_category: "{{ $record->subject_category ?? 'Article' }}",
            record_type: "{{ $record->record_type ?? 'book' }}",
            serial_frequency: "{{ $record->serial_frequency ?? 'unknown' }}",
            date_type: "{{ $record->date_type ?? 'bc' }}",
            acquisition_method: "{{ $record->acquisition_method ?? 'untraced' }}",
            document_format: "{{ $record->document_format ?? 'none' }}",
            cataloging_standard: "{{ $record->cataloging_standard ?? 'AACR2' }}"
        },
        marcFields: @json($initialFields),
        items: @json($initialItemsData),
        branches: @json($branches),
        editingIndex: null,
        newItem: {
            id: null,
            branch_id: '',
            storage_location_id: '',
            barcode: '',
            accession_number: '',
            storage_type: 'Book',
            quantity: 1,
            status: 'available',
            order_code: '',
            waits_for_print: false,
            notes: '',
            volume_issue: '',
            day: '',
            month_season: '',
            year: '',
            shelf: '',
            shelf_position: '',
            location: '',
            temporary_location: ''
        },
        get activeLocations() {
            if (!this.newItem.branch_id) return [];
            const branch = this.branches.find(b => b.id == this.newItem.branch_id);
            return branch ? branch.storage_locations : [];
        },
        getBranchName(id) {
            const branch = this.branches.find(b => b.id == id);
            return branch ? branch.name : '-';
        },
        getLocationName(branchId, locationId) {
            const branch = this.branches.find(b => b.id == branchId);
            if (!branch) return '-';
            const loc = branch.storage_locations.find(l => l.id == locationId);
            return loc ? loc.name : '-';
        },
        addItem() {
            if (!this.newItem.storage_location_id) {
                alert('Vui lòng chọn vị trí lưu trữ');
                return;
            }
            if (this.editingIndex !== null) {
                this.items[this.editingIndex] = JSON.parse(JSON.stringify(this.newItem));
                this.editingIndex = null;
            } else {
                this.items.push(JSON.parse(JSON.stringify(this.newItem)));
            }
            this.resetNewItem();
        },
        editItem(index) {
            this.editingIndex = index;
            this.newItem = JSON.parse(JSON.stringify(this.items[index]));
        },
        removeItem(index) {
            if (confirm('Bạn có chắc chắn muốn xóa bản sách này?')) {
                this.items.splice(index, 1);
            }
        },
        resetNewItem() {
            this.newItem = {
                id: null,
                branch_id: '',
                storage_location_id: '',
                barcode: '',
                accession_number: '',
                storage_type: 'Book',
                quantity: 1,
                status: 'available',
                order_code: '',
                waits_for_print: false,
                notes: '',
                volume_issue: '',
                day: '',
                month_season: '',
                year: '',
                shelf: '',
                shelf_position: '',
                location: '',
                temporary_location: ''
            };
            this.editingIndex = null;
        },
        getSubfieldLabel(tag, code) {
            if (!this.marcFields[tag]) return '';
            const normalizedCode = (code ?? '').toString().trim().replace(/^\$/, '').toLowerCase();
            const def = this.marcFields[tag].subfieldDefinitions.find(d => d.code === normalizedCode);
            return def ? def.label : '';
        },
        getActiveFields() {
            // Lấy các field có ít nhất 1 subfield đã chọn code hoặc đã nhập value
            return Object.values(this.marcFields).filter(f => {
                return f.subfields.some(s => (s.code && s.code.trim() !== '') || (s.value && s.value.trim() !== ''));
            });
        },
        debugSubfieldBindings() {
            try {
                console.group('[MARC DEBUG] Subfield bindings');
                Object.entries(this.marcFields).forEach(([tag, field]) => {
                    const definitions = (field.subfieldDefinitions || []).map(d => ({
                        code: (d.code ?? '').toString(),
                        label: d.label ?? ''
                    }));
                    (field.subfields || []).forEach((sub, idx) => {
                        const rawCode = (sub.code ?? '').toString();
                        const normalizedCode = rawCode.trim().replace(/^\$/, '').toLowerCase();
                        const matched = definitions.find(d => d.code === normalizedCode);
                        console.log({
                            tag,
                            rowIndex: idx,
                            rawCode,
                            normalizedCode,
                            value: sub.value ?? '',
                            hasDefinitions: definitions.length > 0,
                            definitionCodes: definitions.map(d => d.code),
                            matchedCode: matched?.code ?? null,
                            matchedLabel: matched?.label ?? null
                        });
                    });
                });
                console.groupEnd();
            } catch (e) {
                console.error('[MARC DEBUG] Failed to inspect subfield bindings', e);
            }
        },
        goToStep(index) {
            this.currentStep = index;
            // Update URL without refresh
            const url = new URL(window.location);
            url.searchParams.set('tab', index);
            window.history.pushState({}, '', url);

            if (index === 1) {
                this.$nextTick(() => this.debugSubfieldBindings());
            }
        },
        nextStep() {
            this.goToStep(this.currentStep + 1);
        },
        prevStep() {
            this.goToStep(this.currentStep - 1);
        },
        submitForm() {
            document.getElementById('catalogForm').submit();
        }
    }
}
</script>

<style>
    [x-cloak] {
        display: none !important;
    }
</style>
@endsection
