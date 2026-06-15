@extends('layouts.admin')

@section('content')
<div class="space-y-6" x-data="{
    nameEn: '',
    nameVi: '',
    generatedCode: '',
    generateCode() {
        if (this.nameEn) {
            const words = this.nameEn.trim().split(/\s+/);
            this.generatedCode = words.slice(0, 2).map(w => w.charAt(0).toLowerCase()).join('');
        } else {
            this.generatedCode = '';
        }
    }
}" x-init="
    const oldNameEn = {{ old('name_en') ? json_encode(old('name_en')) : 'null' }};
    const oldCode = {{ old('code') ? json_encode(old('code')) : 'null' }};
    
    if (oldNameEn) {
        nameEn = oldNameEn;
        if (!oldCode) {
            generateCode();
        } else {
            generatedCode = oldCode;
        }
    }
">
    <!-- Header -->
    <div class="flex justify-between items-center bg-white dark:bg-slate-900 p-6 rounded-xl shadow-sm border border-gray-100 dark:border-slate-800">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 dark:text-slate-100">{{ __('Thêm kiểu biểu ghi') }}</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ __('Tạo một kiểu biểu ghi MARC mới') }}</p>
        </div>
        <a href="{{ route('admin.bibliographic-levels.index') }}"
            class="inline-flex items-center px-4 py-2.5 bg-gray-200 dark:bg-slate-700 text-gray-700 dark:text-slate-300 font-semibold rounded-lg hover:bg-gray-300 dark:hover:bg-slate-600 transition">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            {{ __('Quay lại') }}
        </a>
    </div>

    <!-- Form -->
    <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-gray-100 dark:border-slate-800 p-6">
        <form method="POST" action="{{ route('admin.bibliographic-levels.store') }}" class="space-y-6" @submit="
            console.log('Form Submit - nameEn:', nameEn);
            console.log('Form Submit - code:', generatedCode);
            console.log('Form Submit - nameVi:', nameVi);
        ">
            @csrf

            <!-- Name English -->
            <div>
                <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-2">
                    {{ __('Tên (Tiếng Anh)') }} <span class="text-red-500">*</span>
                </label>
                <input type="text" name="name_en" x-model="nameEn" @input="
                    generateCode();
                    console.log('nameEn changed to:', nameEn);
                    console.log('generatedCode now:', generatedCode);
                "
                    class="w-full px-4 py-2 border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 dark:text-slate-100 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition @error('name_en') border-red-500 @enderror"
                    placeholder="Language material">
                @error('name_en')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Code -->
            <div>
                <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-2">
                    {{ __('Code') }} <span class="text-red-500">*</span>
                </label>
                <input type="text" name="code" maxlength="1" x-model="generatedCode" @change="console.log('Code input changed to:', generatedCode)"
                    class="w-full px-4 py-2 border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 dark:text-slate-100 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition @error('code') border-red-500 @enderror"
                    placeholder="Auto-generated">
                @error('code')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-xs text-gray-400" x-text="'Debug: generatedCode = ' + generatedCode"></p>
            </div>

            <!-- Name Vietnamese -->
            <div>
                <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-2">
                    {{ __('Tên (Tiếng Việt)') }} <span class="text-red-500">*</span>
                </label>
                <input type="text" name="name_vi" x-model="nameVi"
                    class="w-full px-4 py-2 border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 dark:text-slate-100 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition @error('name_vi') border-red-500 @enderror"
                    placeholder="Tài liệu văn bản">
                @error('name_vi')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description -->
            <div>
                <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-2">
                    {{ __('Mô tả') }}
                </label>
                <textarea name="description" rows="4"
                    class="w-full px-4 py-2 border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 dark:text-slate-100 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                    placeholder="Mô tả chi tiết...">{{ old('description', '') }}</textarea>
            </div>

            <!-- Order -->
            <div>
                <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-2">
                    {{ __('Thứ tự') }}
                </label>
                <input type="number" name="order" value="{{ old('order', 0) }}" min="0"
                    class="w-full px-4 py-2 border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 dark:text-slate-100 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
            </div>

            <!-- Is Active -->
            <div class="flex items-center space-x-3">
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="sr-only peer">
                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 dark:peer-focus:ring-indigo-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-indigo-600"></div>
                </label>
                <span class="text-sm font-semibold text-gray-700 dark:text-slate-300">{{ __('Kích hoạt') }}</span>
            </div>

            <!-- Buttons -->
            <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200 dark:border-slate-700">
                <a href="{{ route('admin.bibliographic-levels.index') }}"
                    class="px-6 py-2 bg-gray-200 dark:bg-slate-700 text-gray-700 dark:text-slate-300 font-semibold rounded-lg hover:bg-gray-300 dark:hover:bg-slate-600 transition">
                    {{ __('Hủy') }}
                </a>
                <button type="submit"
                    class="px-6 py-2 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 transition">
                    {{ __('Lưu') }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
