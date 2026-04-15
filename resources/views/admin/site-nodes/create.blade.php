@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-slate-100">{{ __('Add New Node') }}</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">{{ __('Create new page or category for website') }}</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.site-nodes.index') }}" class="inline-flex items-center px-4 py-2 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-medium rounded-lg transition-colors duration-200">
                <i class="fas fa-arrow-left mr-2"></i>{{ __('Back') }}
            </a>
        </div>
    </div>

    <!-- Form -->
    <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-slate-200 dark:border-slate-800 p-6">
        <form action="{{ route('admin.site-nodes.store') }}" method="POST">
            @csrf
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Left Column -->
                <div class="space-y-4">
                    <!-- Basic Information -->
                    <div class="border-b border-slate-200 dark:border-slate-700 pb-4">
                        <h3 class="text-lg font-bold mb-4 text-blue-600 dark:text-blue-400">{{ __('Basic Information') }}</h3>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">{{ __('Node Code') }} *</label>
                                <input type="text" name="node_code" required
                                       class="w-full px-3 py-2 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-lg text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-400 dark:focus:border-blue-400 transition-colors duration-200" 
                                       placeholder="{{ __('e.g: about, contact') }}"
                                       value="{{ old('node_code') }}">
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">{{ __('Unique code, no accents, used for URL') }}</p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">{{ __('Node Name') }} *</label>
                                <input type="text" name="node_name" required
                                       class="w-full px-3 py-2 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-lg text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-400 dark:focus:border-blue-400 transition-colors duration-200" 
                                       placeholder="{{ __('Internal name') }}"
                                       value="{{ old('node_name') }}">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">{{ __('Display Name') }} *</label>
                                <input type="text" name="display_name" required
                                       class="w-full px-3 py-2 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-lg text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-400 dark:focus:border-blue-400 transition-colors duration-200" 
                                       placeholder="{{ __('Display name on menu') }}"
                                       value="{{ old('display_name') }}">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">{{ __('Description') }}</label>
                                <textarea name="description" rows="3"
                                          class="w-full px-3 py-2 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-lg text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-400 dark:focus:border-blue-400 transition-colors duration-200"
                                          placeholder="{{ __('Short description about the node') }}">{{ old('description') }}</textarea>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">{{ __('Parent Node') }}</label>
                                <select name="parent_id" class="w-full px-3 py-2 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-lg text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-400 dark:focus:border-blue-400 transition-colors duration-200">
                                    <option value="">-- {{ __('Root') }} --</option>
                                    @foreach($parents as $id => $name)
                                        <option value="{{ $id }}" {{ old('parent_id') == $id ? 'selected' : '' }}>
                                            {{ $name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">{{ __('Icon') }}</label>
                                <input type="text" name="icon" 
                                       class="w-full px-3 py-2 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-lg text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-400 dark:focus:border-blue-400 transition-colors duration-200" 
                                       placeholder="{{ __('Font Awesome class') }}"
                                       value="{{ old('icon') }}">
                                <p class="text-xs text-gray-400 mt-1">{{ __('Font Awesome class') }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Display Settings -->
                    <div class="border-b border-gray-700 pb-4">
                        <h3 class="text-lg font-bold mb-4 text-green-600 dark:text-green-400">{{ __('Display Settings') }}</h3>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium mb-1 text-slate-700 dark:text-slate-300">{{ __('Display Type') }}</label>
                                <select name="display_type" class="w-full px-3 py-2 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-lg text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
                                    @foreach($displayTypes as $value => $label)
                                        @php
                                            $translatedLabel = is_string($label) ? __($label) : (is_array($label) ? 'Mixed' : (string)$label);
                                        @endphp
                                        <option value="{{ $value }}" {{ old('display_type') == $value ? 'selected' : '' }}>
                                            {{ is_string($translatedLabel) ? $translatedLabel : 'Mixed' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium mb-1 text-slate-700 dark:text-slate-300">{{ __('Order') }}</label>
                                <input type="number" name="sort_order" 
                                       class="w-full px-3 py-2 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-lg text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200" 
                                       value="{{ old('sort_order', 0) }}">
                            </div>
                            
                            <div class="flex items-center">
                                <input type="checkbox" name="is_active" id="is_active" 
                                       class="mr-2" {{ old('is_active', true) ? 'checked' : '' }}>
                                <label for="is_active" class="text-sm text-slate-700 dark:text-slate-300">{{ __('Active') }}</label>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Right Column -->
                <div class="space-y-4">
                    <!-- Access Control -->
                    <div class="border-b border-gray-700 pb-4">
                        <h3 class="text-lg font-bold mb-4 text-yellow-400">{{ __('Access Control') }}</h3>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium mb-1 text-slate-700 dark:text-slate-300">{{ __('Access Type') }}</label>
                                <select name="access_type" id="access_type" 
                                        class="w-full px-3 py-2 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-lg text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"
                                        onchange="toggleAccessOptions()">
                                    @foreach($accessTypes as $value => $label)
                                        @php
                                            $translatedAccess = is_string($label) ? __($label) : (is_array($label) ? 'Mixed' : (string)$label);
                                        @endphp
                                        <option value="{{ $value }}" {{ old('access_type') == $value ? 'selected' : '' }}>
                                            {{ is_string($translatedAccess) ? $translatedAccess : 'Mixed' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="flex items-center">
                                <input type="checkbox" name="allow_guests" id="allow_guests" 
                                       class="mr-2" {{ old('allow_guests', true) ? 'checked' : '' }}>
                                <label for="allow_guests" class="text-sm text-slate-700 dark:text-slate-300">{{ __('Allow Guests') }}</label>
                            </div>
                        </div>
                    </div>

                    <!-- Link & Content -->
                    <div class="border-b border-slate-200 dark:border-slate-700 pb-4">
                        <h3 class="text-lg font-bold mb-4 text-purple-600 dark:text-purple-400">{{ __('Link & Content') }}</h3>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium mb-1 text-slate-700 dark:text-slate-300">{{ __('System Route') }}</label>
                                <select name="route_name" class="w-full px-3 py-2 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-lg text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
                                    <option value="">-- {{ __('Select route') }} --</option>
                                    @foreach($routes as $route)
                                        <option value="{{ $route }}" {{ old('route_name') == $route ? 'selected' : '' }}>{{ $route }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium mb-1 text-slate-700 dark:text-slate-300">{{ __('Custom URL') }}</label>
                                <input type="text" name="custom_url" 
                                       class="w-full px-3 py-2 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-lg text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200" 
                                       placeholder="https://example.com"
                                       value="{{ old('custom_url') }}">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium mb-1 text-slate-700 dark:text-slate-300">{{ __('Page Content') }}</label>
                                <textarea name="content" rows="5"
                                          class="w-full px-3 py-2 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-lg text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"
                                          placeholder="{{ __('Page content') }}">{{ old('content') }}</textarea>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">{{ __('Supports HTML. Leave empty if using route or URL.') }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- SEO Settings -->
                    <div>
                        <h3 class="text-lg font-bold mb-4 text-rose-600 dark:text-rose-400">{{ __('SEO and Meta Tags') }}</h3>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium mb-1 text-slate-700 dark:text-slate-300">{{ __('Meta Title') }}</label>
                                <input type="text" name="meta_title" 
                                       class="w-full px-3 py-2 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-lg text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200" 
                                       placeholder="{{ __('SEO title') }}"
                                       value="{{ old('meta_title') }}">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium mb-1 text-slate-700 dark:text-slate-300">{{ __('Meta Description') }}</label>
                                <textarea name="meta_description" rows="2"
                                          class="w-full px-3 py-2 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-lg text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"
                                          placeholder="{{ __('SEO description') }}">{{ old('meta_description') }}</textarea>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium mb-1 text-slate-700 dark:text-slate-300">{{ __('Meta Keywords') }}</label>
                                <input type="text" name="meta_keywords" 
                                       class="w-full px-3 py-2 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-lg text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200" 
                                       placeholder="{{ __('SEO keywords') }}"
                                       value="{{ old('meta_keywords') }}">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium mb-1 text-slate-700 dark:text-slate-300">{{ __('Additional Parameters (JSON)') }}</label>
                                <textarea name="settings" rows="2"
                                          class="w-full px-3 py-2 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-lg text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"
                                          placeholder='{"key": "value"}'>{{ old('settings') }}</textarea>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">{{ __('JSON format for custom settings') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="mt-8 pt-6 border-t border-slate-200 dark:border-slate-700 flex justify-end gap-3">
                <a href="{{ route('admin.site-nodes.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-medium rounded-lg transition-colors duration-200">
                    {{ __('Cancel') }}
                </a>
                <button type="submit" 
                        class="inline-flex items-center px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-lg shadow-lg shadow-indigo-200 dark:shadow-none transition-all duration-200 hover:scale-[1.02]">
                    <i class="fas fa-save mr-2"></i> {{ __('Create Node') }}
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function toggleAccessOptions() {
    const accessType = document.getElementById('access_type').value;
    const roleOptions = document.getElementById('role_options');
    
    if (accessType === 'roles') {
        roleOptions.classList.remove('hidden');
    } else {
        roleOptions.classList.add('hidden');
    }
}

function toggleContentOptions() {
    const routeName = document.getElementById('route_name').value;
    const url = document.getElementById('url').value;
    const contentOptions = document.getElementById('content_options');
    
    if (routeName || url) {
        contentOptions.style.opacity = '0.5';
        document.getElementById('content').disabled = true;
    } else {
        contentOptions.style.opacity = '1';
        document.getElementById('content').disabled = false;
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    toggleAccessOptions();
    toggleContentOptions();
});
</script>
@endpush
@endsection
