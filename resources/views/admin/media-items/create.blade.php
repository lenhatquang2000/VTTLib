@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-slate-100">{{ __('Add Media Item') }}</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">{{ __('Adding to: ') }} <span class="font-semibold text-blue-600 dark:text-blue-400">{{ $category->name }}</span></p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.media-items.index', ['category_id' => $category->id]) }}" class="inline-flex items-center px-4 py-2 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-medium rounded-lg transition-colors duration-200">
                <i class="fas fa-arrow-left mr-2"></i>{{ __('Back to Items') }}
            </a>
        </div>
    </div>

    <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-slate-200 dark:border-slate-800 p-6">
        <form action="{{ route('admin.media-items.store') }}" method="POST">
            @csrf
            <input type="hidden" name="category_id" value="{{ $category->id }}">
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Left: Content Info -->
                <div class="space-y-4">
                    <h3 class="text-lg font-bold text-slate-900 dark:text-slate-100 border-b border-slate-200 dark:border-slate-800 pb-2 mb-4">{{ __('Item Details') }}</h3>
                    
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">{{ __('Title') }}</label>
                        <input type="text" name="title" 
                               class="w-full px-3 py-2 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-lg text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-400 dark:focus:border-blue-400 transition-colors duration-200" 
                               placeholder="{{ __('e.g: Welcome to VTTU Library') }}"
                               value="{{ old('title') }}">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">{{ __('Image URL') }} *</label>
                        <input type="text" name="image_url" required
                               class="w-full px-3 py-2 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-lg text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-400 dark:focus:border-blue-400 transition-colors duration-200" 
                               placeholder="https://example.com/image.jpg"
                               value="{{ old('image_url') }}">
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">{{ __('Enter full image URL or path') }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">{{ __('Description') }}</label>
                        <textarea name="description" rows="3"
                                  class="w-full px-3 py-2 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-lg text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-400 dark:focus:border-blue-400 transition-colors duration-200"
                                  placeholder="{{ __('Item description or caption') }}">{{ old('description') }}</textarea>
                    </div>
                </div>

                <!-- Right: Settings -->
                <div class="space-y-4">
                    <h3 class="text-lg font-bold text-slate-900 dark:text-slate-100 border-b border-slate-200 dark:border-slate-800 pb-2 mb-4">{{ __('Links & Display') }}</h3>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">{{ __('Link URL') }}</label>
                        <input type="text" name="link_url" 
                               class="w-full px-3 py-2 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-lg text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-400 dark:focus:border-blue-400 transition-colors duration-200" 
                               placeholder="https://example.com/page"
                               value="{{ old('link_url') }}">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">{{ __('Link Target') }}</label>
                            <select name="link_target" 
                                    class="w-full px-3 py-2 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-lg text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-400 dark:focus:border-blue-400 transition-colors duration-200">
                                <option value="_self" {{ old('link_target') == '_self' ? 'selected' : '' }}>Tab hiện tại (_self)</option>
                                <option value="_blank" {{ old('link_target') == '_blank' ? 'selected' : '' }}>Tab mới (_blank)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">{{ __('Display Order') }}</label>
                            <input type="number" name="sort_order" 
                                   class="w-full px-3 py-2 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-lg text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-400 dark:focus:border-blue-400 transition-colors duration-200" 
                                   value="{{ old('sort_order', 0) }}">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">{{ __('Start Date') }}</label>
                            <input type="date" name="start_date" 
                                   class="w-full px-3 py-2 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-lg text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-400 dark:focus:border-blue-400 transition-colors duration-200" 
                                   value="{{ old('start_date') }}">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">{{ __('End Date') }}</label>
                            <input type="date" name="end_date" 
                                   class="w-full px-3 py-2 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-lg text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-400 dark:focus:border-blue-400 transition-colors duration-200" 
                                   value="{{ old('end_date') }}">
                        </div>
                    </div>

                    <div class="flex items-center space-x-2 pt-2">
                        <input type="checkbox" name="is_active" id="is_active" 
                               class="w-4 h-4 text-blue-600 bg-white border-slate-300 rounded focus:ring-blue-500" 
                               {{ old('is_active', true) ? 'checked' : '' }}>
                        <label for="is_active" class="text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('Active') }}</label>
                    </div>
                </div>
            </div>

            <div class="mt-8 pt-6 border-t border-slate-200 dark:border-slate-800 flex justify-end space-x-3">
                <a href="{{ route('admin.media-items.index', ['category_id' => $category->id]) }}" class="px-4 py-2 text-slate-700 dark:text-slate-300 font-medium hover:text-slate-900 dark:hover:text-slate-100 transition-colors duration-200">
                    {{ __('Cancel') }}
                </a>
                <button type="submit" class="inline-flex items-center px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg shadow-lg shadow-blue-200 dark:shadow-none transition-all duration-200 hover:scale-[1.02]">
                    <i class="fas fa-save mr-2"></i>{{ __('Save Item') }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
