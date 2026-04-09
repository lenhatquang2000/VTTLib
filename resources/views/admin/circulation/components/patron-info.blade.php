<!-- Patron Information Component -->
<div class="bg-gray-800 dark:bg-slate-800 rounded-lg p-4">
    <h4 class="text-sm font-bold text-gray-300 mb-3 flex items-center">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
        </svg>
        {{ __('Thông tin Bạn đọc') }}
    </h4>
    <div id="{{ $id ?? 'patronInfo' }}" class="patron-info-scroll space-y-2">
        <div class="text-center text-gray-500 text-sm py-8">
            <svg class="w-12 h-12 mx-auto mb-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
            </svg>
            <p>{{ __('Nhập mã bạn đọc để hiển thị thông tin') }}</p>
        </div>
    </div>
</div>
