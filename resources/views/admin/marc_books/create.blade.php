@extends('layouts.admin')

@section('content')
<div class="space-y-6 w-full pb-20" x-data="catalogWizard()">
    <!-- Header Section -->
    <div class="flex justify-between items-center bg-white dark:bg-slate-900 p-6 rounded-xl shadow-sm border border-gray-100 dark:border-slate-800">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 dark:text-slate-100">{{ __('MARC21_Cataloging_Form') }}</h2>
            <p class="text-sm text-gray-500 dark:text-slate-400 mt-1">{{ __('Cataloging_Instruction') }}</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.marc.book') }}"
                class="px-5 py-2.5 rounded-lg text-sm font-semibold bg-gray-100 dark:bg-slate-800 text-gray-600 dark:text-slate-400 hover:bg-gray-200 dark:hover:bg-slate-700 transition">{{ __('Cancel') }}</a>
        </div>
    </div>

    <!-- Progress Steps -->
    <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-gray-100 dark:border-slate-800 p-6">
        <div class="flex items-center justify-between">
            <template x-for="(step, index) in steps" :key="index">
                <div class="flex items-center flex-1">
                    <div class="flex flex-col items-center flex-1">
                        <div class="flex items-center justify-center w-12 h-12 rounded-full transition-all duration-300"
                            :class="currentStep > index ? 'bg-green-500 text-white' : currentStep === index ? 'bg-indigo-600 text-white' : 'bg-gray-200 dark:bg-slate-700 text-gray-500 dark:text-slate-400'">
                            <template x-if="currentStep > index">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </template>
                            <template x-if="currentStep <= index">
                                <span class="font-bold" x-text="index + 1"></span>
                            </template>
                        </div>
                        <span class="mt-2 text-xs font-semibold text-center" 
                            :class="currentStep >= index ? 'text-indigo-600 dark:text-indigo-400' : 'text-gray-400 dark:text-slate-500'"
                            x-text="step.title"></span>
                    </div>
                    <div x-show="index < steps.length - 1" 
                        class="flex-1 h-1 mx-2 transition-all duration-300"
                        :class="currentStep > index ? 'bg-green-500' : 'bg-gray-200 dark:bg-slate-700'"></div>
                </div>
            </template>
        </div>
    </div>

    <form id="catalogForm" action="{{ route('admin.marc.book.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

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

                    <!-- Framework -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-3">{{ __('Cataloging_Framework') }}</label>
                        <select x-model="formData.framework" name="framework" 
                            class="w-full px-4 py-3 border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 dark:text-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors appearance-none cursor-pointer">
                            <option value="AVMARC21">AVMARC21</option>
                        </select>
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
                            <div class="flex space-x-1">
                                <input type="text" name="fields[{{ $tag->tag }}][ind1]" placeholder="#" maxlength="1"
                                    class="w-8 h-8 p-0 text-center border-gray-200 dark:border-slate-700 rounded text-xs font-mono uppercase bg-white dark:bg-slate-800 dark:text-slate-100 focus:ring-1 focus:ring-indigo-500"
                                    @click.stop>
                                <input type="text" name="fields[{{ $tag->tag }}][ind2]" placeholder="#" maxlength="1"
                                    class="w-8 h-8 p-0 text-center border-gray-200 dark:border-slate-700 rounded text-xs font-mono uppercase bg-white dark:bg-slate-800 dark:text-slate-100 focus:ring-1 focus:ring-indigo-500"
                                    @click.stop>
                            </div>
                            <svg class="w-4 h-4 text-gray-400 transition-transform duration-200"
                                :class="expanded ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </div>

                    <div class="p-6" x-show="expanded" x-cloak x-collapse>
                        <div x-data="{ 
                            rows: [{ code: '', value: '' }],
                            definitions: {{ $tag->subfields->map(fn($s) => ['code' => $s->code, 'label' => $s->label])->toJson() }}
                        }">
                            <div class="space-y-4">
                                <template x-for="(row, index) in rows" :key="index">
                                    <div class="flex flex-col md:flex-row gap-4 items-start bg-gray-50/50 dark:bg-slate-800/30 p-4 rounded-lg border border-gray-200 dark:border-slate-700 group hover:border-indigo-200 dark:hover:border-indigo-800 transition-colors">
                                        <!-- Subfield Selector -->
                                        <div class="w-full md:w-1/3">
                                            <select :name="'fields[' + '{{ $tag->tag }}' + '][subfields][' + index + '][code]'" 
                                                    x-model="row.code"
                                                    class="w-full px-4 py-3 border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-900 dark:text-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 font-mono transition-colors appearance-none cursor-pointer">
                                                <option value="">{{ __('Select_Subfield') }}</option>
                                                <template x-for="def in definitions" :key="def.code">
                                                    <option :value="def.code" x-text="'$' + def.code + ' ' + def.label"></option>
                                                </template>
                                            </select>
                                        </div>

                                        <!-- Value Input -->
                                        <div class="w-full md:w-2/3 flex gap-3">
                                            <input type="text" 
                                                   :name="'fields[' + '{{ $tag->tag }}' + '][subfields][' + index + '][value]'" 
                                                   x-model="row.value"
                                                   placeholder="{{ __('Enter_Value') }}"
                                                   class="flex-1 px-4 py-3 border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-900 dark:text-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                                            
                                            <button type="button" @click="rows.splice(index, 1)" 
                                                class="flex-shrink-0 w-10 h-10 flex items-center justify-center text-gray-400 dark:text-slate-600 hover:text-white hover:bg-rose-500 dark:hover:bg-rose-600 rounded-lg transition-all opacity-0 group-hover:opacity-100">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </div>
                                    </div>
                                </template>
                            </div>

                            <button type="button" @click="rows.push({ code: '', value: '' })" 
                                    class="mt-5 inline-flex items-center px-4 py-2.5 text-sm font-bold text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 bg-indigo-50 dark:bg-indigo-900/20 hover:bg-indigo-100 dark:hover:bg-indigo-900/30 rounded-lg transition-all">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                {{ __('Add_Subfield') }}
                            </button>
                        </div>
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
                
                <div x-data="{ items: [{ document_type_id: '', storage_location_id: '', quantity: 1, barcode: '', accession_number: '' }] }">
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

                    <button type="button" @click="items.push({ document_type_id: '', storage_location_id: '', quantity: 1 })"
                        class="mt-6 w-full md:w-auto inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 text-white rounded-xl text-sm font-bold shadow-lg shadow-indigo-200 dark:shadow-none transition-all">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                        {{ __('Add_Item') }}
                    </button>
                </div>
            </div>
        </div>

        <!-- Step 4: MARC Preview -->
        <div x-show="currentStep === 3" x-cloak class="space-y-6">
            <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-gray-100 dark:border-slate-800 p-6">
                <h3 class="text-lg font-bold text-gray-800 dark:text-slate-100 mb-6">{{ __('MARC_Preview') }}</h3>
                <div class="bg-slate-50 dark:bg-slate-800/50 p-4 rounded-lg">
                    <p class="text-sm text-gray-600 dark:text-slate-400">{{ __('Preview_will_be_generated_after_submission') }}</p>
                </div>
            </div>
        </div>

        <!-- Navigation Buttons -->
        <div class="flex justify-between bg-white dark:bg-slate-900 p-6 rounded-xl shadow-sm border border-gray-100 dark:border-slate-800">
            <button type="button" @click="prevStep" x-show="currentStep > 0"
                class="px-6 py-2.5 rounded-lg text-sm font-semibold bg-gray-100 dark:bg-slate-800 text-gray-600 dark:text-slate-400 hover:bg-gray-200 dark:hover:bg-slate-700 transition">
                {{ __('Previous') }}
            </button>
            <div class="flex-1"></div>
            <button type="button" @click="nextStep" x-show="currentStep < 3"
                class="px-6 py-2.5 rounded-lg text-sm font-semibold bg-indigo-600 hover:bg-indigo-700 text-white transition shadow-lg shadow-indigo-100 dark:shadow-none">
                {{ __('Next') }}
            </button>
            <button type="submit" x-show="currentStep === 3"
                class="px-6 py-2.5 rounded-lg text-sm font-semibold bg-green-600 hover:bg-green-700 text-white transition shadow-lg shadow-green-100 dark:shadow-none">
                {{ __('Save_Record') }}
            </button>
        </div>
    </form>
</div>

<script>
function catalogWizard() {
    return {
        currentStep: 0,
        steps: [
            { title: '{{ __("Leader_Info") }}' },
            { title: '{{ __("Cataloging") }}' },
            { title: '{{ __("Distribution") }}' },
            { title: '{{ __("Preview") }}' }
        ],
        formData: {
            framework: 'AVMARC21',
            status: 'pending',
            subject_category: 'Article',
            record_type: 'book',
            serial_frequency: 'unknown',
            date_type: 'bc',
            acquisition_method: 'untraced',
            document_format: 'none',
            cataloging_standard: 'AACR2'
        },
        nextStep() {
            if (this.currentStep < this.steps.length - 1) {
                this.currentStep++;
            }
        },
        prevStep() {
            if (this.currentStep > 0) {
                this.currentStep--;
            }
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