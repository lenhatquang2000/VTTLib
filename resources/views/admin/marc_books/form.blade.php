@extends('layouts.admin')

@section('content')
<!-- SweetAlert2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

@php
$initialFields = [];
foreach($definitions as $tag) {
    $existingField = isset($record) ? $record->fields->where('tag', $tag->tag)->first() : null;
    $subfieldsData = [['id' => null, 'code' => '', 'value' => '']];
    $subfieldDefinitions = $tag->subfields
        ->map(fn($s) => ['code' => strtolower(ltrim(trim((string) $s->code), '$')), 'label' => $s->label])
        ->keyBy('code');

    if ($existingField && $existingField->subfields->count() > 0) {
        $subfieldsData = $existingField->subfields->map(fn($s) => [
            'id' => $s->id,
            'code' => strtolower(ltrim(trim((string) $s->code), '$')),
            'value' => $s->value
        ])->toArray();

        foreach ($existingField->subfields as $savedSubfield) {
            $savedCode = strtolower(ltrim(trim((string) $savedSubfield->code), '$'));
            if ($savedCode !== '' && !$subfieldDefinitions->has($savedCode)) {
                $subfieldDefinitions->put($savedCode, ['code' => $savedCode, 'label' => __('Saved')]);
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
        'barcode' => $i->barcode, 
        'accession_number' => $i->accession_number, 
        'storage_type' => $i->storage_type,
        'status' => $i->status,
        'notes' => $i->notes
    ])->toArray()
    : [];
@endphp

<div class="space-y-6 w-full pb-20" x-data="catalogWizard()" x-init="$nextTick(() => debugSubfieldBindings())">
    <!-- Header Section -->
    <div class="flex justify-between items-start bg-white dark:bg-slate-900 p-6 rounded-xl shadow-sm border border-gray-100 dark:border-slate-800">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 dark:text-slate-100">
                {{ isset($record) ? __('Chỉnh sửa bản ghi MARC') : __('Form biên mục MARC21') }}
            </h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                {{ isset($record) ? __('Cập nhật thông tin chi tiết cho bản ghi biên mục #:id', ['id' => $record->id]) : __('Nhập thông tin thư mục và ấn phẩm theo cấu trúc trường MARC21') }}
            </p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.marc.book') }}"
                class="flex items-center px-4 py-2.5 text-sm font-semibold bg-gray-100 dark:bg-slate-800 text-gray-600 dark:text-slate-400 hover:bg-gray-200 dark:hover:bg-slate-700 rounded-lg transition">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                {{ __('Quay lại') }}
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

    <form id="catalogForm" action="{{ isset($record) ? route('admin.marc.book.update', $record->id) : route('admin.marc.book.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @if(isset($record))
            @method('PUT')
        @endif
        <input type="hidden" name="tab" :value="currentStep">

        <!-- Step 1: Leader/Metadata -->
        <div x-show="currentStep === 0" x-cloak class="space-y-6">
            <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-gray-100 dark:border-slate-800 p-8">
                <div class="mb-8">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-slate-100 mb-2">{{ __('Leader bản ghi') }}</h3>
                    <p class="text-sm text-gray-500 dark:text-slate-400">{{ __('Leader là 24 ký tự đầu tiên chứa các thông tin trạng thái, loại bản ghi, v.v.') }}</p>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-stretch">
                    <div class="lg:col-span-8 space-y-8">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                            <div class="md:col-span-2">
                                <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-3">
                                    {{ __('Khung biên mục') }} <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <select x-model="formData.framework" name="framework"
                                        onchange="const url = new URL(window.location); url.searchParams.set('framework_id', this.options[this.selectedIndex].getAttribute('data-id')); window.location.href = url.toString();"
                                        class="w-full px-4 py-3 border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 dark:text-slate-200 rounded-lg text-sm font-semibold focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors appearance-none cursor-pointer">
                                        @foreach($frameworks as $fw)
                                        <option value="{{ $fw->code }}" data-id="{{ $fw->id }}" {{ $frameworkId == $fw->id ? 'selected' : '' }}>
                                            {{ $fw->name }} ({{ $fw->code }})
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                <p class="mt-2 text-[9px] text-gray-400 uppercase tracking-widest font-bold">
                                    {{ __('Trang sẽ tải lại khi thay đổi') }}
                                </p>
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-3">
                                    {{ __('Kiểu tài liệu') }} <span class="text-red-500">*</span>
                                </label>
                                <select x-model="formData.document_type_id" name="document_type_id"
                                    class="w-full px-4 py-3 border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 dark:text-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors appearance-none cursor-pointer">
                                    <option value="">{{ __('Chọn kiểu tài liệu') }}</option>
                                    @foreach($documentTypes as $type)
                                    <option value="{{ $type->id }}" {{ (isset($record) && $record->document_type_id == $type->id) || (!isset($record) && $type->id == (old('document_type_id') ?? null)) ? 'selected' : '' }}>
                                        {{ $type->name }}
                                    </option>
                                    @endforeach
                                </select>
                                <p class="mt-2 text-[9px] text-gray-400">{{ __('Quản lý kiểu tài liệu tại') }} <a href="{{ route('admin.document-types.index') }}" target="_blank" class="text-indigo-600 hover:text-indigo-700 font-semibold">{{ __('Kiểu tài liệu') }}</a></p>
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-3">{{ __('Trạng thái') }}</label>
                                <select x-model="formData.status" name="status"
                                    class="w-full px-4 py-3 border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 dark:text-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors appearance-none cursor-pointer">
                                    <option value="pending">{{ __('Mới') }}</option>
                                    <option value="approved">{{ __('Đã duyệt') }}</option>
                                </select>
                            </div>

                            <div class="flex items-center space-x-3 p-4 bg-indigo-50/50 dark:bg-indigo-900/20 rounded-xl border border-indigo-100 dark:border-indigo-800/50">
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="is_featured" x-model="formData.is_featured" class="sr-only peer">
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 dark:peer-focus:ring-indigo-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-indigo-600"></div>
                                </label>
                                <div class="flex flex-col">
                                    <span class="text-sm font-bold text-vttu-dark dark:text-slate-200">{{ __('Sách nổi bật') }}</span>
                                    <span class="text-[10px] text-vttu-red font-medium italic italic opacity-70">{{ __('Hiển thị ở khu vực nổi bật trên trang chủ') }}</span>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-3">{{ __('Thể loại') }}</label>
                                <select x-model="formData.record_type" name="record_type"
                                    class="w-full px-4 py-3 border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 dark:text-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors appearance-none cursor-pointer">
                                    <option value="book">{{ __('Sách') }}</option>
                                    <option value="article">{{ __('Bài trích') }}</option>
                                    <option value="collection">{{ __('Bộ sưu tập') }}</option>
                                    <option value="file">{{ __('Tập tin máy tính') }}</option>
                                    <option value="address">{{ __('Địa chỉ') }}</option>
                                    <option value="map">{{ __('Bản đồ') }}</option>
                                    <option value="mixed">{{ __('Tài liệu hỗn hợp') }}</option>
                                    <option value="audio">{{ __('Âm thanh') }}</option>
                                    <option value="journal">{{ __('Ấn phẩm định kỳ') }}</option>
                                    <option value="digital">{{ __('Số hóa') }}</option>
                                    <option value="resource">{{ __('Tài liệu số') }}</option>
                                    <option value="video">{{ __('Tài liệu chiếu hình') }}</option>
                                    <option value="visual">{{ __('Thiết bị, vật thể') }}</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-3">{{ __('Kiểu biểu ghi') }}</label>
                                <select x-model="formData.bibliographic_level" name="bibliographic_level"
                                    class="w-full px-4 py-3 border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 dark:text-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors appearance-none cursor-pointer">
                                    @foreach($bibliographicLevels as $level)
                                    <option value="{{ $level->code }}" {{ (isset($record) && $record->bibliographic_level == $level->code) || (!isset($record) && $level->code == 'a') ? 'selected' : '' }}>
                                        {{ app()->getLocale() == 'en' ? $level->name_en : $level->name_vi }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-3">{{ __('Tần suất xuất bản tạp chí') }}</label>
                                <select x-model="formData.serial_frequency" name="serial_frequency"
                                    class="w-full px-4 py-3 border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 dark:text-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors appearance-none cursor-pointer">
                                    <option value="unknown">{{ __('Không xác định') }}</option>
                                    <option value="a">{{ __('Hàng năm') }}</option>
                                    <option value="b">{{ __('Hai tháng/kỳ') }}</option>
                                    <option value="c">{{ __('Hai kỳ/tuần') }}</option>
                                    <option value="d">{{ __('Nhật báo') }}</option>
                                    <option value="e">{{ __('Hai tuần/kỳ') }}</option>
                                    <option value="f">{{ __('Hai kỳ/năm') }}</option>
                                    <option value="g">{{ __('Hai năm/kỳ') }}</option>
                                    <option value="h">{{ __('Ba năm/kỳ') }}</option>
                                    <option value="i">{{ __('Ba kỳ/tuần') }}</option>
                                    <option value="j">{{ __('Ba kỳ/tháng') }}</option>
                                    <option value="m">{{ __('Báo tháng') }}</option>
                                    <option value="q">{{ __('Báo quý') }}</option>
                                    <option value="s">{{ __('Hai kỳ/tháng') }}</option>
                                    <option value="t">{{ __('Ba kỳ/năm') }}</option>
                                    <option value="u">{{ __('Không biết') }}</option>
                                    <option value="w">{{ __('Tuần báo') }}</option>
                                    <option value="z">{{ __('Khác') }}</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-3">{{ __('Loại ngày xuất bản') }}</label>
                                <select x-model="formData.date_type" name="date_type"
                                    class="w-full px-4 py-3 border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 dark:text-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors appearance-none cursor-pointer">
                                    <option value="bc">{{ __('Có liên quan đến ngày trước CN') }}</option>
                                    <option value="c">{{ __('Ấn phẩm tiếp diễn đang xuất bản') }}</option>
                                    <option value="d">{{ __('Ấn phẩm tiếp diễn đã ngừng xuất bản') }}</option>
                                    <option value="e">{{ __('Ngày chi tiết') }}</option>
                                    <option value="i">{{ __('Ngày bao gồm của bộ sưu tập') }}</option>
                                    <option value="k">{{ __('Khoảng năm của phần lớn bộ sưu tập') }}</option>
                                    <option value="m">{{ __('Nhiều ngày') }}</option>
                                    <option value="n">{{ __('Ngày không xác định') }}</option>
                                    <option value="p">{{ __('Ngày phân phối và sản xuất khác nhau') }}</option>
                                    <option value="q">{{ __('Ngày nghi vấn') }}</option>
                                    <option value="r">{{ __('Ngày in lại và ngày gốc') }}</option>
                                    <option value="s">{{ __('Một ngày xác định hoặc có thể') }}</option>
                                    <option value="t">{{ __('Ngày xuất bản và ngày bản quyền') }}</option>
                                    <option value="u">{{ __('Trạng thái ấn phẩm tiếp diễn không xác định') }}</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-3">{{ __('Hình thức nhận (Tạp chí)') }}</label>
                                <select x-model="formData.acquisition_method" name="acquisition_method"
                                    class="w-full px-4 py-3 border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 dark:text-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors appearance-none cursor-pointer">
                                    <option value="vol_date">{{ __('Tập và ngày tháng năm') }}</option>
                                    <option value="untraced">{{ __('Ấn phẩm không theo dõi') }}</option>
                                    <option value="date">{{ __('Ngày tháng năm') }}</option>
                                    <option value="month_year">{{ __('Tháng và năm') }}</option>
                                    <option value="season_year">{{ __('Mùa và năm') }}</option>
                                    <option value="year">{{ __('Năm') }}</option>
                                    <option value="vol">{{ __('Tập') }}</option>
                                    <option value="vol_month_year">{{ __('Tập, tháng và năm') }}</option>
                                    <option value="vol_year">{{ __('Tập và năm') }}</option>
                                    <option value="vol_season_year">{{ __('Tập, mùa và năm') }}</option>
                                    <option value="other">{{ __('Khác') }}</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-3">{{ __('Định dạng tài liệu') }}</label>
                                <select x-model="formData.document_format" name="document_format"
                                    class="w-full px-4 py-3 border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 dark:text-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors appearance-none cursor-pointer">
                                    <option value="none">{{ __('Không có định dạng đặc biệt') }}</option>
                                    <option value="a">{{ __('Vi phim') }}</option>
                                    <option value="b">{{ __('Vi phiếu') }}</option>
                                    <option value="c">{{ __('Vi phiếu mờ') }}</option>
                                    <option value="f">{{ __('Chữ in lớn') }}</option>
                                    <option value="g">{{ __('Chữ nổi') }}</option>
                                    <option value="r">{{ __('Bản sao, bản in thông thường') }}</option>
                                    <option value="s">{{ __('Điện tử') }}</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-3">{{ __('Chuẩn biên mục') }}</label>
                                <select x-model="formData.cataloging_standard" name="cataloging_standard"
                                    class="w-full px-4 py-3 border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 dark:text-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors appearance-none cursor-pointer">
                                    <option value="AACR2">AACR-2</option>
                                    <option value="ISBD">ISBD</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-3">{{ __('Chủ đề') }}</label>
                                <select x-model="formData.subject_category" name="subject_category"
                                    class="w-full px-4 py-3 border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 dark:text-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors appearance-none cursor-pointer">
                                    <option value="Article">{{ __('Bài trích chọn lọc') }}</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="lg:col-span-4 flex flex-col h-full">
                        <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-3 text-center">{{ __('Ảnh bìa') }}</label>
                        <div class="flex-grow flex flex-col items-center justify-start">
                            <div class="relative group cursor-pointer w-full max-w-[280px] mx-auto" @click="$refs.coverInput.click()">
                                <div class="relative w-full aspect-[3/4] rounded-2xl overflow-hidden border-2 border-indigo-100 dark:border-indigo-900/50 shadow-lg bg-gray-50 dark:bg-slate-800 transition-all duration-500 group-hover:shadow-xl group-hover:shadow-indigo-500/10">
                                    <img :src="coverPreview" class="w-full h-full object-contain transition-transform duration-700 group-hover:scale-105">
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-all duration-300 flex flex-col items-center justify-end pb-6">
                                        <div class="bg-white/90 dark:bg-slate-800/90 backdrop-blur-sm text-indigo-600 dark:text-indigo-400 px-4 py-2 rounded-xl text-xs font-bold shadow-xl transform translate-y-4 group-hover:translate-y-0 transition-all duration-300">
                                            {{ __('Thay đổi ảnh bìa') }}
                                        </div>
                                    </div>
                                    <button type="button" @click.stop="removeCover()" class="absolute top-3 right-3 bg-rose-500 hover:bg-rose-600 text-white p-2 rounded-full shadow-lg z-10 opacity-0 group-hover:opacity-100 transition-all">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>
                                <input type="file" name="cover_image" id="cover_image_input" x-ref="coverInput" class="hidden" accept="image/*" @change="previewCover($event)">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Step 2: MARC Fields -->
        <div x-show="currentStep === 1" x-cloak class="space-y-6">
            <div class="flex justify-between items-center bg-indigo-50/50 dark:bg-indigo-900/10 p-4 rounded-xl border border-indigo-100 dark:border-indigo-900/30">
                <div class="text-sm font-bold text-indigo-700 dark:text-indigo-400">
                    <i class="fas fa-plus-circle mr-2"></i>{{ __('Thêm trường tùy chỉnh cho Snapshot này') }}
                </div>
                <div class="flex gap-2">
                    <select id="quick_add_tag" class="text-xs border-gray-300 dark:border-slate-700 rounded-lg bg-white dark:bg-slate-800 dark:text-slate-200">
                        <option value="">-- {{ __('Chọn Tag để thêm') }} --</option>
                        @foreach(\App\Models\MarcTagDefinition::orderBy('tag')->get() as $t)
                            <option value="{{ $t->tag }}">{{ $t->tag }} - {{ $t->label }}</option>
                        @endforeach
                    </select>
                    <button type="button" @click="addTagToSnapshot()" class="px-4 py-2 bg-indigo-600 text-white text-xs font-bold rounded-lg hover:bg-indigo-700 transition-colors">
                        {{ __('Thêm Tag') }}
                    </button>
                </div>
            </div>

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
                                    @click.stop>
                                <input type="text" name="fields[{{ $tag->tag }}][ind2]" x-model="marcFields['{{ $tag->tag }}'].ind2" placeholder="#" maxlength="1"
                                    class="w-7 h-7 p-0 text-center border border-gray-300 dark:border-slate-600 rounded text-xs font-mono uppercase bg-white dark:bg-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-indigo-500 transition-all"
                                    @click.stop>
                            </div>
                        </div>

                        <button type="button" 
                            @click.stop="
                                Swal.fire({
                                    title: '{{ __('Xác nhận xóa Tag?') }}',
                                    text: '{{ __('Bạn có chắc chắn muốn xóa Tag này khỏi bản ghi? Hành động này không thể hoàn tác.') }}',
                                    icon: 'warning',
                                    showCancelButton: true,
                                    confirmButtonColor: '#680102',
                                    cancelButtonColor: '#94a3b8',
                                    confirmButtonText: '{{ __('Xóa ngay') }}',
                                    cancelButtonText: '{{ __('Hủy bỏ') }}',
                                    customClass: { popup: 'rounded-[2rem]' }
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        delete marcFields['{{ $tag->tag }}']; 
                                        $el.closest('.bg-white').remove();
                                        isDirty = true;
                                    }
                                })
                            "
                            class="p-2 text-gray-400 hover:text-rose-500 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </button>

                        <svg class="w-4 h-4 text-gray-400 transition-transform duration-200"
                            :class="expanded ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                </div>

                <div class="p-6" x-show="expanded" x-cloak x-collapse>
                    @if(intval($tag->tag) < 10)
                    <div class="space-y-4">
                        <template x-for="(row, index) in marcFields['{{ $tag->tag }}'].subfields" :key="index">
                            <div class="flex flex-col md:flex-row gap-4 items-start bg-gray-50/50 dark:bg-slate-800/30 p-4 rounded-lg border border-gray-200 dark:border-slate-700">
                                <input type="hidden" :name="'fields[' + '{{ $tag->tag }}' + '][subfields][' + index + '][code]'" value="_">
                                <input type="hidden" :name="'fields[' + '{{ $tag->tag }}' + '][subfields][' + index + '][id]'" x-model="row.id">
                                <div class="w-full flex gap-3 items-center">
                                    <span class="shrink-0 px-3 py-2 bg-gray-100 dark:bg-slate-800 text-gray-500 dark:text-slate-400 rounded-lg text-xs font-mono font-bold">{{ $tag->label }}</span>
                                    <input type="text"
                                        :name="'fields[' + '{{ $tag->tag }}' + '][subfields][' + index + '][value]'"
                                        x-model="row.value"
                                        placeholder="{{ __('Nhập giá trị') }}"
                                        class="flex-1 px-4 py-3 border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-900 dark:text-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 font-mono transition-colors">
                                </div>
                            </div>
                        </template>
                    </div>
                    @else
                    <div class="space-y-4">
                        <template x-for="(row, index) in marcFields['{{ $tag->tag }}'].subfields" :key="index">
                            <div class="flex flex-col md:flex-row gap-4 items-start bg-gray-50/50 dark:bg-slate-800/30 p-4 rounded-lg border border-gray-200 dark:border-slate-700 group hover:border-indigo-200 dark:hover:border-indigo-800 transition-colors">
                                <div class="w-full md:w-1/3">
                                    <div class="relative group/sub">
                                        <select :name="'fields[' + '{{ $tag->tag }}' + '][subfields][' + index + '][code]'"
                                            x-model="row.code"
                                            x-init="row.code = ((row.code ?? '').toString().trim().replace(/^\$/, ''))"
                                            x-effect="$el.value = ((row.code ?? '').toString().trim().replace(/^\$/, '').toLowerCase())"
                                            class="w-full px-4 py-3 border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-900 dark:text-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 font-mono transition-colors appearance-none cursor-pointer">
                                            <option value="">{{ __('Chọn trường con') }}</option>
                                            <template x-for="def in marcFields['{{ $tag->tag }}'].subfieldDefinitions" :key="def.code">
                                                <option :value="def.code" :selected="def.code === ((row.code ?? '').toString().trim().replace(/^\$/, '').toLowerCase())" x-text="'$' + def.code + ' ' + def.label"></option>
                                            </template>
                                            <template x-if="row.code && !marcFields['{{ $tag->tag }}'].subfieldDefinitions.find(d => d.code === row.code)">
                                                <option :value="row.code" selected x-text="'$' + row.code + ' (Tùy chỉnh)'"></option>
                                            </template>
                                        </select>
                                        <button type="button" 
                                            @click="
                                                Swal.fire({
                                                    title: `<span class='text-xl font-black uppercase tracking-tight dark:text-white'>Chỉnh sửa Subfield</span>`,
                                                    html: `
                                                        <div class='text-left space-y-4 px-2 pt-4'>
                                                            <div class='space-y-1.5'>
                                                                <label class='text-[10px] font-black text-indigo-500 uppercase tracking-[0.2em] ml-1'>Mã Subfield (1 ký tự)</label>
                                                                <div class='relative'>
                                                                    <span class='absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 font-mono'>$</span>
                                                                    <input id='swal-input-code' 
                                                                        class='w-full pl-8 pr-4 py-3 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl text-sm font-mono focus:ring-2 focus:ring-indigo-500 transition-all outline-none dark:text-white' 
                                                                        maxlength='1' 
                                                                        value='${row.code}' 
                                                                        placeholder='a'>
                                                                </div>
                                                            </div>
                                                            <div class='space-y-1.5'>
                                                                <label class='text-[10px] font-black text-indigo-500 uppercase tracking-[0.2em] ml-1'>Tên hiển thị / Mô tả</label>
                                                                <input id='swal-input-label' 
                                                                    class='w-full px-4 py-3 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 transition-all outline-none dark:text-white' 
                                                                    value='${getSubfieldLabel('{{ $tag->tag }}', row.code)}' 
                                                                    placeholder='Nhập tên trường con...'>
                                                            </div>
                                                        </div>
                                                    `,
                                                    background: document.documentElement.classList.contains('dark') ? '#0f172a' : '#ffffff',
                                                    showCancelButton: true,
                                                    confirmButtonText: 'Cập nhật',
                                                    cancelButtonText: 'Hủy',
                                                    confirmButtonColor: '#680102',
                                                    cancelButtonColor: '#64748b',
                                                    customClass: {
                                                        popup: 'rounded-3xl border border-gray-100 dark:border-slate-800 shadow-2xl',
                                                        confirmButton: 'rounded-xl px-8 py-3 text-sm font-bold uppercase tracking-widest',
                                                        cancelButton: 'rounded-xl px-8 py-3 text-sm font-bold uppercase tracking-widest'
                                                    },
                                                    focusConfirm: false,
                                                    preConfirm: () => {
                                                        const newCode = document.getElementById('swal-input-code').value.toLowerCase().replace(/^\$/, '').substring(0, 1);
                                                        const newLabel = document.getElementById('swal-input-label').value;
                                                        if (!newCode) {
                                                            Swal.showValidationMessage('Vui lòng nhập mã hiệu');
                                                            return false;
                                                        }
                                                        return { code: newCode, label: newLabel };
                                                    }
                                                }).then((result) => {
                                                    if (result.isConfirmed) {
                                                        const { code, label } = result.value;
                                                        row.code = code;
                                                        const tagData = marcFields['{{ $tag->tag }}'];
                                                        const existingDef = tagData.subfieldDefinitions.find(d => d.code === code);
                                                        if (existingDef) {
                                                            existingDef.label = label;
                                                        } else {
                                                            tagData.subfieldDefinitions.push({ code: code, label: label });
                                                        }
                                                        isDirty = true;
                                                    }
                                                })
                                            "
                                            class="absolute right-8 top-1/2 -translate-y-1/2 text-[10px] bg-indigo-50 dark:bg-indigo-900/40 text-indigo-600 dark:text-indigo-400 px-1.5 py-0.5 rounded font-black opacity-0 group-hover/sub:opacity-100 transition-opacity shadow-sm border border-indigo-100 dark:border-indigo-800">
                                            Đổi mã
                                        </button>
                                    </div>
                                </div>

                                <div class="w-full md:w-2/3 flex gap-3">
                                    <input type="hidden" :name="'fields[' + '{{ $tag->tag }}' + '][subfields][' + index + '][id]'" x-model="row.id">
                                    <input type="text"
                                        :name="'fields[' + '{{ $tag->tag }}' + '][subfields][' + index + '][value]'"
                                        x-model="row.value"
                                        placeholder="{{ __('Nhập giá trị') }}"
                                        class="flex-1 px-4 py-3 border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-900 dark:text-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">

                                    <button type="button" @click="
                                        Swal.fire({
                                            title: '{{ __('Xác nhận xóa?') }}',
                                            text: '{{ __('Xóa trường con này?') }}',
                                            icon: 'question',
                                            showCancelButton: true,
                                            confirmButtonColor: '#680102',
                                            cancelButtonColor: '#94a3b8',
                                            confirmButtonText: '{{ __('Xóa') }}',
                                            cancelButtonText: '{{ __('Hủy') }}',
                                            customClass: { popup: 'rounded-[1.5rem]' }
                                        }).then((result) => {
                                            if (result.isConfirmed) {
                                                marcFields['{{ $tag->tag }}'].subfields.splice(index, 1);
                                                isDirty = true;
                                            }
                                        })
                                    "
                                        class="flex-shrink-0 w-10 h-10 flex items-center justify-center text-gray-400 dark:text-slate-600 hover:text-white hover:bg-rose-500 dark:hover:bg-rose-600 rounded-lg transition-all opacity-0 group-hover:opacity-100">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </template>
                    </div>

                    <button type="button" @click="marcFields['{{ $tag->tag }}'].subfields.push({ id: null, code: '', value: '' })"
                        class="mt-5 inline-flex items-center px-4 py-2.5 text-sm font-bold text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 bg-indigo-50 dark:bg-indigo-900/20 hover:bg-indigo-100 dark:hover:bg-indigo-900/30 rounded-lg transition-all">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        {{ __('Thêm trường con') }}
                    </button>
                    @endif
                </div>
            </div>
            @endforeach
        </div>

        <!-- Step 3: Distribution -->
        <div x-show="currentStep === 2" x-cloak class="space-y-6">
            @include('admin.marc_books.components.items_tab')
        </div>

        <!-- Step 4: Preview -->
        <div x-show="currentStep === 3" x-cloak class="space-y-6">
            @include('admin.marc_books.components.preview_tab')
        </div>

        <!-- Action Buttons -->
        <div class="flex justify-start bg-white dark:bg-slate-900 p-6 rounded-xl shadow-sm border border-gray-100 dark:border-slate-800">
            <button type="button" @click="submitForm()"
                class="px-8 py-3 rounded-xl text-sm font-bold bg-green-600 hover:bg-green-700 text-white transition shadow-lg shadow-green-100 dark:shadow-none flex items-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <span>{{ isset($record) ? __('Cập nhật') : __('Lưu bản ghi mới') }}</span>
            </button>
        </div>
    </form>
</div>

<script>
    function catalogWizard() {
        return {
            currentStep: parseInt(new URLSearchParams(window.location.search).get('tab')) || 0,
            isDirty: false,
            steps: [
                { title: '{{ __("Thông tin Leader") }}' },
                { title: '{{ __("Biên mục") }}' },
                { title: '{{ __("Phân bổ") }}' },
                { title: '{{ __("Xem trước") }}' }
            ],
            formData: {
                framework: "{{ $currentFramework->code ?? ($record?->framework ?? 'AVMARC21') }}",
                status: "{{ $record?->status ?? 'pending' }}",
                is_featured: {{ $record && $record->is_featured ? 'true' : 'false' }},
                subject_category: "{{ $record?->subject_category ?? 'Article' }}",
                record_type: "{{ $record?->record_type ?? 'book' }}",
                bibliographic_level: "{{ $record?->bibliographic_level ?? 'a' }}",
                serial_frequency: "{{ $record?->serial_frequency ?? 'unknown' }}",
                date_type: "{{ $record?->date_type ?? 'bc' }}",
                acquisition_method: "{{ $record?->acquisition_method ?? 'untraced' }}",
                document_format: "{{ $record?->document_format ?? 'none' }}",
                cataloging_standard: "{{ $record?->cataloging_standard ?? 'AACR2' }}",
                document_type_id: {{ $record?->document_type_id ?? 'null' }}
            },
            marcFields: @json($initialFields),
            items: @json($initialItemsData),
            branches: @json($branches),
            editingIndex: null,
            batchQuantity: 1,
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
            coverPreview: "{{ (isset($record) && $record->cover_image) ? asset('storage/' . $record->cover_image) : 'https://placehold.co/600x800/680102/white?text=VTTLib+Book' }}",
            
            init() {
                this.$watch('formData', () => this.isDirty = true);
                this.$watch('marcFields', () => this.isDirty = true);
                this.$watch('items', () => this.isDirty = true);
                
                // Khởi tạo Toast
                window.Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 2000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer)
                        toast.addEventListener('mouseleave', Swal.resumeTimer)
                    }
                });
            },
            
            get leader() {
                let s = '00000';
                s += (this.formData.status === 'pending' ? 'n' : 'c');
                s += (this.formData.record_type === 'book' ? 'a' : 'm');
                s += 'm  a2200000 ';
                s += (this.formData.cataloging_standard === 'AACR2' ? 'i' : 'a');
                s += ' 4500';
                return s;
            },

            addTagToSnapshot() {
                Swal.fire({
                    title: '<span class="text-xl font-black uppercase tracking-tight dark:text-white">Thêm trường MARC mới</span>',
                    input: 'text',
                    inputLabel: 'Nhập số hiệu Tag (ví dụ: 082, 852)',
                    inputPlaceholder: 'Số hiệu tag...',
                    showCancelButton: true,
                    confirmButtonText: 'Thêm ngay',
                    confirmButtonColor: '#680102',
                    background: document.documentElement.classList.contains('dark') ? '#0f172a' : '#ffffff',
                    customClass: {
                        popup: 'rounded-3xl border border-gray-100 dark:border-slate-800 shadow-2xl',
                        confirmButton: 'rounded-xl px-8 py-3 text-sm font-bold uppercase tracking-widest',
                        cancelButton: 'rounded-xl px-8 py-3 text-sm font-bold uppercase tracking-widest'
                    },
                    preConfirm: (value) => {
                        if (!value || isNaN(value)) {
                            Swal.showValidationMessage('Vui lòng nhập số hiệu tag hợp lệ');
                            return false;
                        }
                        return value;
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        const newTag = result.value;
                        const url = new URL(window.location.href);
                        url.searchParams.set('add_tag', newTag);
                        url.searchParams.set('tab', 1);
                        window.location.href = url.toString();
                        window.Toast.fire({ icon: 'success', title: 'Đã thêm trường MARC mới' });
                    }
                });
            },

            removeTag(tag) {
                Swal.fire({
                    title: '{{ __('Xác nhận xóa?') }}',
                    text: '{{ __('Trường MARC') }} ' + tag + ' {{ __('sẽ bị loại bỏ khỏi bản ghi này.') }}',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#680102',
                    confirmButtonText: '{{ __('Đúng, xóa nó!') }}'
                }).then((result) => {
                    if (result.isConfirmed) {
                        delete this.marcFields[tag];
                        this.isDirty = true;
                        window.Toast.fire({ icon: 'success', title: '{{ __('Đã xóa trường') }} ' + tag });
                    }
                });
            },

            addSubfield(tag) {
                this.marcFields[tag].subfields.push({ id: null, code: '', value: '' });
                this.isDirty = true;
                window.Toast.fire({ icon: 'success', title: '{{ __('Đã thêm trường con mới') }}' });
            },

            removeSubfield(tag, index) {
                this.marcFields[tag].subfields.splice(index, 1);
                this.isDirty = true;
                window.Toast.fire({ icon: 'success', title: '{{ __('Đã xóa trường con') }}' });
            },
            
            goToStep(index) {
                if (this.isDirty) {
                    Swal.fire({
                        title: '{{ __('Thay đổi chưa lưu!') }}',
                        text: '{{ __('Bạn có muốn tiếp tục mà không lưu không?') }}',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: '{{ __('Tiếp tục') }}',
                        cancelButtonText: '{{ __('Ở lại') }}'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            this.isDirty = false;
                            this.performGoToStep(index);
                        }
                    });
                } else {
                    this.performGoToStep(index);
                }
            },

            performGoToStep(index) {
                this.currentStep = index;
                const url = new URL(window.location);
                url.searchParams.set('tab', index);
                window.history.pushState({}, '', url);
            },

            previewCover(event) {
                const file = event.target.files[0];
                if (file) this.coverPreview = URL.createObjectURL(file);
            },

            removeCover() {
                this.coverPreview = 'https://placehold.co/600x800/680102/white?text=VTTLib+Book';
                document.getElementById('cover_image_input').value = '';
            },

            addItem() {
                if (!this.newItem.storage_location_id) {
                    Swal.fire({ title: 'Cảnh báo', text: 'Vui lòng chọn kho lưu trữ.', icon: 'warning' });
                    return;
                }

                if (this.editingIndex !== null) {
                    this.items[this.editingIndex] = JSON.parse(JSON.stringify(this.newItem));
                    this.editingIndex = null;
                } else {
                    const quantity = parseInt(this.batchQuantity) || 1;
                    for (let i = 0; i < quantity; i++) {
                        const itemToAdd = JSON.parse(JSON.stringify(this.newItem));
                        // Logic auto-increment barcode/accession if needed could go here
                        this.items.push(itemToAdd);
                    }
                }
                this.resetNewItem();
                this.batchQuantity = 1;
            },

            editItem(index) {
                this.editingIndex = index;
                this.newItem = JSON.parse(JSON.stringify(this.items[index]));
            },

            removeItem(index) {
                Swal.fire({
                    title: 'Xác nhận xóa?',
                    text: 'Xóa bản sách này khỏi hàng đợi?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#680102',
                    confirmButtonText: 'Xóa'
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.items.splice(index, 1);
                        this.isDirty = true;
                    }
                });
            },

            resetNewItem() {
                this.newItem = {
                    id: null, branch_id: '', storage_location_id: '', barcode: '',
                    accession_number: '', storage_type: 'Book', quantity: 1, status: 'available',
                    order_code: '', waits_for_print: false, notes: '', volume_issue: '',
                    day: '', month_season: '', year: '', shelf: '', shelf_position: '',
                    location: '', temporary_location: ''
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
                return Object.values(this.marcFields).filter(f => 
                    f.subfields.some(s => (s.code && s.code.trim() !== '') || (s.value && s.value.trim() !== ''))
                );
            },

            submitForm() {
                const form = document.getElementById('catalogForm');
                const formData = new FormData(form);

                Swal.fire({
                    title: 'Đang lưu...',
                    text: 'Vui lòng chờ trong giây lát',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(async response => {
                    const isJson = response.headers.get('content-type')?.includes('application/json');
                    const data = isJson ? await response.json() : null;

                    if (!response.ok) {
                        throw new Error(data?.message || `Lỗi hệ thống (${response.status})`);
                    }
                    return data;
                })
                .then(data => {
                    if (data && data.success) {
                        this.isDirty = false;
                        Swal.fire({
                            title: 'Thành công!',
                            text: data.message || 'Bản ghi đã được lưu.',
                            icon: 'success',
                            confirmButtonColor: '#680102'
                        }).then(() => {
                            window.location.href = data.redirect || '{{ route('admin.marc.book') }}';
                        });
                    } else {
                        throw new Error(data?.message || 'Có lỗi xảy ra khi lưu bản ghi.');
                    }
                })
                .catch(error => {
                    console.error('Save error:', error);
                    Swal.fire({
                        title: 'Lỗi hệ thống!',
                        text: error.message || 'Không thể kết nối tới máy chủ.',
                        icon: 'error',
                        confirmButtonColor: '#680102'
                    });
                });
            },
            
            debugSubfieldBindings() {}
        }
    }
</script>

<style>
    [x-cloak] { display: none !important; }
</style>
@endsection
