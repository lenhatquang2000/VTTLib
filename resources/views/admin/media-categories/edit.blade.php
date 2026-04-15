@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-slate-100">{{ __('Edit Media Category') }}</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">{{ __('Update media group information') }}</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.media-categories.index') }}" class="inline-flex items-center px-4 py-2 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-medium rounded-lg transition-colors duration-200">
                <i class="fas fa-arrow-left mr-2"></i>{{ __('Back') }}
            </a>
        </div>
    </div>

    <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-slate-200 dark:border-slate-800 p-6">
        <form action="{{ route('admin.media-categories.update', $mediaCategory) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">{{ __('Category Name') }} *</label>
                        <input type="text" name="name" required
                               class="w-full px-3 py-2 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-lg text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-400 dark:focus:border-blue-400 transition-colors duration-200" 
                               value="{{ old('name', $mediaCategory->name) }}">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">{{ __('Category Code') }} *</label>
                        <input type="text" name="code" required
                               class="w-full px-3 py-2 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-lg text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-400 dark:focus:border-blue-400 transition-colors duration-200" 
                               value="{{ old('code', $mediaCategory->code) }}">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">{{ __('Type') }} *</label>
                        <select name="type" required
                                class="w-full px-3 py-2 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-lg text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-400 dark:focus:border-blue-400 transition-colors duration-200">
                            @foreach($types as $value => $label)
                                <option value="{{ $value }}" {{ old('type', $mediaCategory->type) == $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">{{ __('Position') }}</label>
                        <input type="text" name="position" 
                               class="w-full px-3 py-2 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-lg text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-400 dark:focus:border-blue-400 transition-colors duration-200" 
                               value="{{ old('position', $mediaCategory->position) }}">
                    </div>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">{{ __('Language') }} *</label>
                        <select name="language" required
                                class="w-full px-3 py-2 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-lg text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-400 dark:focus:border-blue-400 transition-colors duration-200">
                            <option value="vi" {{ old('language', $mediaCategory->language) == 'vi' ? 'selected' : '' }}>Tiếng Việt</option>
                            <option value="en" {{ old('language', $mediaCategory->language) == 'en' ? 'selected' : '' }}>English</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">{{ __('Description') }}</label>
                        <textarea name="description" rows="3"
                                  class="w-full px-3 py-2 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-lg text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-400 dark:focus:border-blue-400 transition-colors duration-200">{{ old('description', $mediaCategory->description) }}</textarea>
                    </div>

                    <div class="flex items-center space-x-2 pt-2">
                        <input type="checkbox" name="is_active" id="is_active" 
                               class="w-4 h-4 text-blue-600 bg-white border-slate-300 rounded focus:ring-blue-500" 
                               {{ old('is_active', $mediaCategory->is_active) ? 'checked' : '' }}>
                        <label for="is_active" class="text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('Active') }}</label>
                    </div>
                </div>
            </div>

            <div class="mt-8 pt-6 border-t border-slate-200 dark:border-slate-800 flex justify-end space-x-3">
                <button type="submit" class="inline-flex items-center px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg shadow-lg shadow-blue-200 dark:shadow-none transition-all duration-200 hover:scale-[1.02]">
                    <i class="fas fa-save mr-2"></i>{{ __('Update Category') }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
