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
            <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-gray-100 dark:border-slate-800 p-8">
                <div class="mb-8">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-slate-100 mb-2">{{ __('Distribution_Info') }}</h3>
                    <p class="text-sm text-gray-500 dark:text-slate-400">Thêm thông tin về bản sách và vị trí lưu trữ</p>
                </div>
                
                <div x-data="{ items: itemsData }">
                    <div class="space-y-5">
                        <template x-for="(item, index) in items" :key="index">
                            <div class="bg-gradient-to-br from-gray-50 to-gray-100 dark:from-slate-800/50 dark:to-slate-800/30 p-6 rounded-xl border-2 border-gray-200 dark:border-slate-700 hover:border-indigo-300 dark:hover:border-indigo-600 transition-all">
                                <div class="flex items-center justify-between mb-5">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 bg-indigo-600 rounded-lg flex items-center justify-center">
                                            <span class="text-white font-bold" x-text="index + 1"></span>
                                        </div>
                                        <h4 class="text-sm font-bold text-gray-700 dark:text-slate-300">Bản sách #<span x-text="index + 1"></span></h4>
                                    </div>
                                    <button type="button" @click="items.splice(index, 1)" x-show="items.length > 1"
                                        class="px-4 py-2 text-xs font-bold text-red-600 hover:text-white hover:bg-red-600 border-2 border-red-600 rounded-lg transition-all">
                                        {{ __('Remove_Item') }}
                                    </button>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                                    <div>
                                        <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-3">{{ __('Document_Type') }}</label>
                                        <input type="hidden" :name="'items[' + index + '][id]'" x-model="item.id">
                                        <select :name="'items[' + index + '][document_type_id]'" x-model="item.document_type_id"
                                            class="w-full px-4 py-3 border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-900 dark:text-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors appearance-none cursor-pointer">
                                            <option value="">{{ __('Select') }}</option>
                                            @foreach($documentTypes as $type)
                                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-3">{{ __('Storage_Location') }}</label>
                                        <select :name="'items[' + index + '][storage_location_id]'" x-model="item.storage_location_id"
                                            class="w-full px-4 py-3 border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-900 dark:text-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors appearance-none cursor-pointer">
                                            <option value="">{{ __('Select') }}</option>
                                            @foreach($locations as $location)
                                                <option value="{{ $location->id }}">{{ $location->branch->name ?? '' }} - {{ $location->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-3">{{ __('Quantity') }}</label>
                                        <input type="number" :name="'items[' + index + '][quantity]'" x-model="item.quantity" min="1"
                                            class="w-full px-4 py-3 border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-900 dark:text-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>

                    <button type="button" @click="items.push({ id: null, document_type_id: '', storage_location_id: '', quantity: 1 })"
                        class="mt-6 w-full md:w-auto inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 text-white rounded-xl text-sm font-bold shadow-lg shadow-indigo-200 dark:shadow-none transition-all">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                        {{ __('Add_Item') }}
                    </button>
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
        ? $record->items->map(fn($i) => ['id' => $i->id, 'document_type_id' => $i->document_type_id, 'storage_location_id' => $i->storage_location_id, 'quantity' => 1, 'barcode' => $i->barcode, 'accession_number' => $i->accession_number])
        : [['id' => null, 'document_type_id' => '', 'storage_location_id' => '', 'quantity' => 1, 'barcode' => '', 'accession_number' => '']];
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
