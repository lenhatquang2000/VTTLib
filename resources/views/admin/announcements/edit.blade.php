@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold">{{ __('Chỉnh sửa Thông báo') }}</h1>
            <p class="text-sm text-gray-400 mt-1">{{ __('Chỉnh sửa: ') }}{{ $announcement->title }}</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.announcements.index') }}" 
               class="inline-flex items-center px-5 py-2.5 rounded-xl bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 font-bold text-sm transition-all duration-300 border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700/50 hover:shadow-sm active:scale-95 group">
                <i class="fas fa-chevron-left mr-2 group-hover:-translate-x-1 transition-transform"></i>
                {{ __('Quay lại') }}
            </a>
        </div>
    </div>

    <!-- Form -->
    <div class="card-admin p-6 bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-800">
        <form action="{{ route('admin.announcements.update', $announcement) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-6">
                    <div>
                        <h3 class="text-lg font-bold mb-4 text-blue-400">Thông tin chi tiết</h3>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium mb-1">Tiêu đề *</label>
                                <input type="text" name="title" required
                                       class="input-field w-full px-4 py-2 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl" 
                                       placeholder="Nhập tiêu đề thông báo"
                                       value="{{ old('title', $announcement->title) }}">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium mb-1">Slug (URL)</label>
                                <input type="text" name="slug" 
                                       class="input-field w-full px-4 py-2 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl" 
                                       placeholder="Tự động tạo từ tiêu đề"
                                       value="{{ old('slug', $announcement->slug) }}">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium mb-1">Tóm tắt ngắn</label>
                                <textarea name="summary" rows="3"
                                          class="input-field w-full px-4 py-2 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl"
                                          placeholder="Tóm tắt ngắn gọn">{{ old('summary', $announcement->summary) }}</textarea>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium mb-1">Nội dung thông báo *</label>
                                <textarea name="content" id="content" rows="15"
                                          class="input-field w-full px-4 py-2 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl"
                                          placeholder="Nhập nội dung thông báo đầy đủ" required>{{ old('content', $announcement->content) }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Sidebar -->
                <div class="space-y-6">
                    <div>
                        <h3 class="text-lg font-bold mb-4 text-green-400">Cài đặt hiển thị</h3>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium mb-1">Trạng thái *</label>
                                <select name="status" class="input-field w-full px-4 py-2 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl">
                                    <option value="draft" {{ old('status', $announcement->status) == 'draft' ? 'selected' : '' }}>Bản nháp</option>
                                    <option value="pending" {{ old('status', $announcement->status) == 'pending' ? 'selected' : '' }}>Chờ duyệt</option>
                                    <option value="published" {{ old('status', $announcement->status) == 'published' ? 'selected' : '' }}>Đã đăng</option>
                                    <option value="archived" {{ old('status', $announcement->status) == 'archived' ? 'selected' : '' }}>Lưu trữ</option>
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium mb-1">Ngày đăng</label>
                                <input type="datetime-local" name="published_at" 
                                       class="input-field w-full px-4 py-2 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl"
                                       value="{{ old('published_at', $announcement->published_at ? $announcement->published_at->format('Y-m-d\TH:i') : '') }}">
                            </div>

                            <div class="flex items-center">
                                <input type="checkbox" name="is_featured" id="is_featured" 
                                       class="mr-2" {{ old('is_featured', $announcement->is_featured) ? 'checked' : '' }}>
                                <label for="is_featured" class="text-sm">Ưu tiên hiển thị (Nổi bật)</label>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-lg font-bold mb-4 text-purple-400">Ảnh đại diện</h3>
                        <div class="space-y-4">
                            @if($announcement->featured_image)
                            <div class="mb-4">
                                <p class="text-xs text-slate-500 mb-2">Ảnh hiện tại:</p>
                                <img src="{{ $announcement->featured_image }}" class="w-full h-32 object-cover rounded-xl shadow-sm">
                            </div>
                            @endif
                            <div class="relative group">
                                <input type="file" name="featured_image_file" accept="image/*"
                                       class="hidden" id="featured_image_file"
                                       onchange="previewImage(this)">
                                <label for="featured_image_file" 
                                       class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-slate-300 dark:border-slate-700 rounded-2xl cursor-pointer hover:bg-slate-50 dark:hover:bg-slate-800 transition-all">
                                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                        <i class="fas fa-cloud-upload-alt text-2xl text-slate-400 mb-2"></i>
                                        <p class="text-xs text-slate-500">Thay đổi ảnh đại diện</p>
                                    </div>
                                </label>
                            </div>
                            <div id="image-preview-container" class="hidden mt-4">
                                <img id="image-preview" src="#" class="w-full h-40 object-cover rounded-xl shadow-md">
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-lg font-bold mb-4 text-yellow-400">Ngôn ngữ</h3>
                        <div class="space-y-4">
                            <select name="language" class="input-field w-full px-4 py-2 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl">
                                <option value="vi" {{ old('language', $announcement->language) == 'vi' ? 'selected' : '' }}>Tiếng Việt</option>
                                <option value="en" {{ old('language', $announcement->language) == 'en' ? 'selected' : '' }}>English</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="flex justify-end gap-3 mt-8 pt-8 border-t border-slate-200 dark:border-slate-800">
                <button type="submit" 
                        class="inline-flex items-center px-8 py-3 rounded-lg bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-700 hover:to-violet-700 text-white font-black text-sm transition-all duration-300 shadow-lg active:scale-95 group">
                    <i class="fas fa-check-circle mr-2 group-hover:scale-110 transition-transform"></i>
                    Cập nhật thông báo
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function previewImage(input) {
    const preview = document.getElementById('image-preview');
    const container = document.getElementById('image-preview-container');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            container.classList.remove('hidden');
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush
@endsection
