<!-- New Framework Modal -->
<div x-show="isNewFrameworkOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" @click="isNewFrameworkOpen = false"></div>
        <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden relative transform transition-all">
            <div class="p-8">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white uppercase tracking-tighter">{{ __('Tạo Framework mới') }}</h3>
                    <button @click="isNewFrameworkOpen = false" class="text-gray-400 hover:text-gray-600 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                <form action="{{ route('admin.marc.framework.store') }}" method="POST" class="space-y-5">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-gray-500 dark:text-slate-400 uppercase mb-2">{{ __('Mã duy nhất (ví dụ: MONO)') }}</label>
                        <input type="text" name="code" required class="w-full border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-800 rounded-xl p-4 text-sm focus:ring-2 focus:ring-indigo-500 dark:text-slate-100 transition uppercase shadow-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 dark:text-slate-400 uppercase mb-2">{{ __('Tên Framework') }}</label>
                        <input type="text" name="name" required class="w-full border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-800 rounded-xl p-4 text-sm focus:ring-2 focus:ring-indigo-500 dark:text-slate-100 transition shadow-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 dark:text-slate-400 uppercase mb-2">{{ __('Mô tả') }}</label>
                        <textarea name="description" class="w-full border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-800 rounded-xl p-4 text-sm focus:ring-2 focus:ring-indigo-500 dark:text-slate-100 transition shadow-sm" rows="3"></textarea>
                    </div>
                    <div class="pt-4 flex gap-3">
                        <button type="button" @click="isNewFrameworkOpen = false" class="flex-1 px-4 py-3 bg-gray-100 dark:bg-slate-800 hover:bg-gray-200 dark:hover:bg-slate-700 text-gray-600 dark:text-slate-400 font-bold rounded-xl transition uppercase text-xs">{{ __('Hủy') }}</button>
                        <button type="submit" class="flex-1 px-4 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl transition uppercase text-xs shadow-lg shadow-indigo-100 dark:shadow-none">{{ __('Tạo Framework') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Framework Modal -->
<div x-show="isEditFrameworkOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" @click="isEditFrameworkOpen = false"></div>
        <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden relative transform transition-all">
            <div class="p-8">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white uppercase tracking-tighter">{{ __('Chỉnh sửa Framework') }}</h3>
                    <button @click="isEditFrameworkOpen = false" class="text-gray-400 hover:text-gray-600 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                <form :action="`{{ route('admin.marc.framework.update', ['framework' => ':id']) }}`.replace(':id', framework.id)" method="POST" class="space-y-5">
                    @csrf @method('PUT')
                    <div>
                        <label class="block text-xs font-bold text-gray-500 dark:text-slate-400 uppercase mb-2">{{ __('Mã (không thể thay đổi)') }}</label>
                        <input type="text" :value="framework.code" disabled class="w-full border-gray-100 dark:border-slate-700 bg-gray-100 dark:bg-slate-800 text-gray-400 dark:text-slate-500 rounded-xl p-4 text-sm font-mono shadow-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 dark:text-slate-400 uppercase mb-2">{{ __('Tên Framework') }}</label>
                        <input type="text" name="name" x-model="framework.name" required class="w-full border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-800 rounded-xl p-4 text-sm focus:ring-2 focus:ring-indigo-500 dark:text-slate-100 transition shadow-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 dark:text-slate-400 uppercase mb-2">{{ __('Mô tả') }}</label>
                        <textarea name="description" x-model="framework.description" class="w-full border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-800 rounded-xl p-4 text-sm focus:ring-2 focus:ring-indigo-500 dark:text-slate-100 transition shadow-sm" rows="3"></textarea>
                    </div>
                    <div class="pt-4">
                        <button type="submit" class="w-full px-4 py-4 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl transition uppercase text-xs shadow-lg shadow-indigo-100 dark:shadow-none">{{ __('Lưu thay đổi') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- New Tag Modal -->
<div x-show="isNewTagOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" @click="isNewTagOpen = false"></div>
        <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden relative transform transition-all">
            <div class="p-8">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white uppercase tracking-tighter">{{ __('Đăng ký Tag MARC mới') }}</h3>
                    <button @click="isNewTagOpen = false" class="text-gray-400 hover:text-gray-600 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                <form action="{{ route('admin.marc.tag.store') }}" method="POST" class="space-y-5">
                    @csrf
                    <input type="hidden" name="framework_id" value="{{ $frameworkId }}">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 dark:text-slate-400 uppercase mb-2">{{ __('Mã Tag (3 chữ số)') }}</label>
                        <input type="text" name="tag" value="{{ old('tag') }}" placeholder="e.g. 245" maxlength="3" required class="w-full @error('tag') border-rose-500 ring-2 ring-rose-500/20 @else border-gray-200 dark:border-slate-700 @enderror bg-gray-50 dark:bg-slate-800 rounded-xl p-4 text-sm focus:ring-2 focus:ring-indigo-500 dark:text-slate-100 transition font-mono font-bold shadow-sm">
                        @error('tag')
                            <p class="mt-1.5 text-[10px] font-bold text-rose-500 uppercase tracking-widest">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 dark:text-slate-400 uppercase mb-2">{{ __('Nhãn hiển thị') }}</label>
                        <input type="text" name="label" placeholder="e.g. Title Statement" required class="w-full border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-800 rounded-xl p-4 text-sm focus:ring-2 focus:ring-indigo-500 dark:text-slate-100 transition">
                    </div>
                    <div class="flex items-center space-x-3 bg-indigo-50 dark:bg-indigo-900/20 p-4 rounded-xl">
                        <input type="checkbox" name="is_visible" value="1" id="tag_visible" checked class="w-4 h-4 rounded text-indigo-600 focus:ring-indigo-500">
                        <label for="tag_visible" class="text-sm font-semibold text-indigo-900 dark:text-indigo-300">{{ __('Hiển thị trong biểu mẫu biên mục') }}</label>
                    </div>
                    <div class="pt-4 flex gap-3">
                        <button type="submit" class="w-full px-4 py-4 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl transition uppercase text-xs shadow-lg shadow-indigo-100 dark:shadow-none">{{ __('Tạo Tag') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Tag Modal -->
<div x-show="isEditTagOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" @click="isEditTagOpen = false"></div>
        <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden relative transform transition-all">
            <div class="p-8">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white uppercase tracking-tighter">{{ __('Chỉnh sửa định nghĩa Tag') }}</h3>
                    <button @click="isEditTagOpen = false" class="text-gray-400 hover:text-gray-600 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                <form :action="`{{ route('admin.marc.tag.update', ['tag' => ':id']) }}`.replace(':id', tag.id)" method="POST" class="space-y-5">
                    @csrf @method('PUT')
                    <input type="hidden" name="framework_id" value="{{ $frameworkId }}">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 dark:text-slate-400 uppercase mb-2">{{ __('Mã Tag (không thể thay đổi)') }}</label>
                        <input type="text" :value="tag.tag" disabled class="w-full border-gray-100 dark:border-slate-700 bg-gray-100 dark:bg-slate-800 text-gray-400 dark:text-slate-500 rounded-xl p-4 text-sm font-mono font-bold shadow-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 dark:text-slate-400 uppercase mb-2">{{ __('Nhãn hiển thị') }}</label>
                        <input type="text" name="label" x-model="tag.label" required class="w-full border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-800 rounded-xl p-4 text-sm focus:ring-2 focus:ring-indigo-500 dark:text-slate-100 transition shadow-sm">
                    </div>
                    <div class="flex items-center space-x-3 bg-indigo-50 dark:bg-indigo-900/20 p-4 rounded-xl">
                        <input type="checkbox" name="is_visible" value="1" x-model="tag.is_visible" id="edit_tag_visible" class="w-4 h-4 rounded text-indigo-600 focus:ring-indigo-500">
                        <label for="edit_tag_visible" class="text-sm font-semibold text-indigo-900 dark:text-indigo-300">{{ __('Hiển thị trong biểu mẫu biên mục') }}</label>
                    </div>
                    <div class="pt-4">
                        <button type="submit" class="w-full px-4 py-4 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl transition uppercase text-xs shadow-lg shadow-indigo-100 dark:shadow-none">{{ __('Cập nhật định nghĩa') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- New Subfield Modal -->
<div x-show="isNewSubfieldOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" @click="isNewSubfieldOpen = false"></div>
        <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden relative transform transition-all">
            <div class="p-8">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white uppercase tracking-tighter">{{ __('Thêm trường con mới') }}</h3>
                        <p class="text-[10px] text-indigo-600 dark:text-indigo-400 font-bold mt-1 uppercase" x-text="`{{ __('Tag đích') }}: ${targetTag.tag}`"></p>
                    </div>
                    <button @click="isNewSubfieldOpen = false" class="text-gray-400 hover:text-gray-600 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                <form action="{{ route('admin.marc.subfield.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <input type="hidden" name="tag_id" :value="targetTag.id">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 dark:text-slate-400 uppercase mb-2">{{ __('Mã trường con (1 ký tự)') }}</label>
                        <input type="text" name="code" value="{{ old('code') }}" placeholder="e.g. a" maxlength="1" required class="w-full @error('code') border-rose-500 ring-2 ring-rose-500/20 @else border-gray-200 dark:border-slate-700 @enderror bg-gray-50 dark:bg-slate-800 rounded-xl p-4 text-sm focus:ring-2 focus:ring-indigo-500 dark:text-slate-100 transition font-mono font-bold shadow-sm">
                        @error('code')
                            <p class="mt-1.5 text-[10px] font-bold text-rose-500 uppercase tracking-widest">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 dark:text-slate-400 uppercase mb-2">{{ __('Nhãn') }}</label>
                        <input type="text" name="label" placeholder="e.g. Principal title" required class="w-full border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-800 rounded-xl p-4 text-sm focus:ring-2 focus:ring-indigo-500 dark:text-slate-100 transition shadow-sm">
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div class="flex items-center space-x-3 bg-gray-50 dark:bg-slate-800/50 p-4 rounded-xl border border-gray-100 dark:border-slate-800">
                            <input type="checkbox" name="is_visible" value="1" checked class="w-4 h-4 rounded text-indigo-600 focus:ring-indigo-500">
                            <label class="text-xs font-bold text-gray-600 dark:text-slate-300 uppercase">{{ __('Hiển thị') }}</label>
                        </div>
                        <div class="flex items-center space-x-3 bg-gray-50 dark:bg-slate-800/50 p-4 rounded-xl border border-gray-100 dark:border-slate-800">
                            <input type="checkbox" name="is_mandatory" value="1" class="w-4 h-4 rounded text-indigo-600 focus:ring-indigo-500">
                            <label class="text-xs font-bold text-gray-600 dark:text-slate-300 uppercase">{{ __('Bắt buộc') }}</label>
                        </div>
                        <div class="flex items-center space-x-3 bg-gray-50 dark:bg-slate-800/50 p-4 rounded-xl border border-gray-100 dark:border-slate-800">
                            <input type="checkbox" name="is_repeatable" value="1" class="w-4 h-4 rounded text-indigo-600 focus:ring-indigo-500">
                            <label class="text-xs font-bold text-gray-600 dark:text-slate-300 uppercase">{{ __('Lặp lại') }}</label>
                        </div>
                    </div>
                    <div class="pt-4">
                        <button type="submit" class="w-full px-4 py-4 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl transition uppercase text-xs shadow-lg shadow-indigo-100 dark:shadow-none">{{ __('Xác nhận định nghĩa') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Subfield Modal -->
<div x-show="isEditSubfieldOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" @click="isEditSubfieldOpen = false"></div>
        <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden relative transform transition-all">
            <div class="p-8">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white uppercase tracking-tighter">{{ __('Chỉnh sửa trường con') }}</h3>
                    <button @click="isEditSubfieldOpen = false" class="text-gray-400 hover:text-gray-600 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                <form :action="`{{ route('admin.marc.subfield.update', ['subfield' => ':id']) }}`.replace(':id', subfield.id)" method="POST" class="space-y-4">
                    @csrf @method('PUT')
                    <div>
                        <label class="block text-xs font-bold text-gray-500 dark:text-slate-400 uppercase mb-2">{{ __('Mã trường con (không thể thay đổi)') }}</label>
                        <div class="p-4 bg-gray-100 dark:bg-slate-800 rounded-xl font-mono font-bold text-gray-400 dark:text-slate-500 text-sm" x-text="`$${subfield.code}`"></div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 dark:text-slate-400 uppercase mb-2">{{ __('Nhãn') }}</label>
                        <input type="text" name="label" x-model="subfield.label" required class="w-full border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-800 rounded-xl p-4 text-sm focus:ring-2 focus:ring-indigo-500 dark:text-slate-100 transition shadow-sm">
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div class="flex items-center space-x-3 bg-white dark:bg-slate-900 p-4 rounded-xl border border-gray-100 dark:border-slate-800">
                            <input type="checkbox" name="is_visible" value="1" x-model="subfield.is_visible" class="w-4 h-4 rounded text-indigo-600 focus:ring-indigo-500">
                            <label class="text-xs font-bold text-gray-600 dark:text-slate-300 uppercase">{{ __('Hiển thị') }}</label>
                        </div>
                        <div class="flex items-center space-x-3 bg-white dark:bg-slate-900 p-4 rounded-xl border border-gray-100 dark:border-slate-800">
                            <input type="checkbox" name="is_mandatory" value="1" x-model="subfield.is_mandatory" class="w-4 h-4 rounded text-indigo-600 focus:ring-indigo-500">
                            <label class="text-xs font-bold text-gray-600 dark:text-slate-300 uppercase">{{ __('Bắt buộc') }}</label>
                        </div>
                        <div class="flex items-center space-x-3 bg-white dark:bg-slate-900 p-4 rounded-xl border border-gray-100 dark:border-slate-800">
                            <input type="checkbox" name="is_repeatable" value="1" x-model="subfield.is_repeatable" class="w-4 h-4 rounded text-indigo-600 focus:ring-indigo-500">
                            <label class="text-xs font-bold text-gray-600 dark:text-slate-300 uppercase">{{ __('Lặp lại') }}</label>
                        </div>
                    </div>
                    <div class="pt-4">
                        <button type="submit" class="w-full px-4 py-4 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl transition uppercase text-xs shadow-lg shadow-indigo-100 dark:shadow-none">{{ __('Lưu thay đổi') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
