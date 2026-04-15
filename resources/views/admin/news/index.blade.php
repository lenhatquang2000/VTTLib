@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold">{{ __('Quản lý Tin tức') }}</h1>
            <p class="text-sm text-gray-400 mt-1">{{ __('Quản lý tất cả bài viết tin tức') }}</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.news.create') }}" class="btn-primary">
                <i class="fas fa-plus mr-2"></i>{{ __('Tạo Tin tức') }}
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
        <div class="card-admin p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-400">Tổng số</p>
                    <p class="text-2xl font-bold text-blue-400">{{ $stats['total'] }}</p>
                </div>
                <i class="fas fa-newspaper text-3xl text-blue-400 opacity-20"></i>
            </div>
        </div>
        <div class="card-admin p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-400">Đã đăng</p>
                    <p class="text-2xl font-bold text-green-400">{{ $stats['published'] }}</p>
                </div>
                <i class="fas fa-check-circle text-3xl text-green-400 opacity-20"></i>
            </div>
        </div>
        <div class="card-admin p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-400">Bản nháp</p>
                    <p class="text-2xl font-bold text-gray-400">{{ $stats['draft'] }}</p>
                </div>
                <i class="fas fa-edit text-3xl text-gray-400 opacity-20"></i>
            </div>
        </div>
        <div class="card-admin p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-400">Chờ duyệt</p>
                    <p class="text-2xl font-bold text-yellow-400">{{ $stats['pending'] }}</p>
                </div>
                <i class="fas fa-clock text-3xl text-yellow-400 opacity-20"></i>
            </div>
        </div>
        <div class="card-admin p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-400">Nổi bật</p>
                    <p class="text-2xl font-bold text-purple-400">{{ $stats['featured'] }}</p>
                </div>
                <i class="fas fa-star text-3xl text-purple-400 opacity-20"></i>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card-admin p-4">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium mb-1">Tìm kiếm</label>
                <input type="text" name="search" value="{{ request('search') }}" 
                       class="input-field w-full" placeholder="Tiêu đề, nội dung...">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Trạng thái</label>
                <select name="status" class="input-field w-full">
                    <option value="">Tất cả</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Bản nháp</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chờ duyệt</option>
                    <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Đã đăng</option>
                    <option value="archived" {{ request('status') == 'archived' ? 'selected' : '' }}>Lưu trữ</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Chuyên mục</label>
                <select name="category_id" class="input-field w-full">
                    <option value="">Tất cả</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="btn-secondary">
                    <i class="fas fa-search mr-2"></i>Tìm kiếm
                </button>
                <a href="{{ route('admin.news.index') }}" class="btn-secondary">
                    <i class="fas fa-times mr-2"></i>Xóa
                </a>
            </div>
        </form>
    </div>

    <!-- News List -->
    <div class="card-admin p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold">Danh sách Tin tức</h3>
            <div class="flex items-center gap-2">
                <input type="checkbox" id="selectAll" onchange="toggleSelectAll()">
                <label for="selectAll" class="text-sm">Chọn tất cả</label>
                <select id="bulkAction" class="input-field text-sm" onchange="performBulkAction()">
                    <option value="">Hành động</option>
                    <option value="publish">Đăng</option>
                    <option value="archive">Lưu trữ</option>
                    <option value="delete">Xóa</option>
                    <option value="feature">Nổi bật</option>
                    <option value="unfeature">Bỏ nổi bật</option>
                </select>
            </div>
        </div>

        @if($news->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-gray-700">
                            <th class="text-left py-3 px-4">
                                <input type="checkbox" id="headerCheckbox" onchange="toggleSelectAll()">
                            </th>
                            <th class="text-left py-3 px-4">Tiêu đề</th>
                            <th class="text-left py-3 px-4">Chuyên mục</th>
                            <th class="text-left py-3 px-4">Tác giả</th>
                            <th class="text-left py-3 px-4">Trạng thái</th>
                            <th class="text-left py-3 px-4">Ngày đăng</th>
                            <th class="text-left py-3 px-4">Lượt xem</th>
                            <th class="text-center py-3 px-4">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($news as $item)
                            <tr class="border-b border-gray-800 hover:bg-gray-800/50 transition">
                                <td class="py-3 px-4">
                                    <input type="checkbox" class="news-checkbox" value="{{ $item->id }}">
                                </td>
                                <td class="py-3 px-4">
                                    <div class="flex items-center gap-2">
                                        @if($item->featured_image)
                                            <img src="{{ $item->featured_image }}" alt="" class="w-10 h-10 rounded object-cover">
                                        @endif
                                        <div>
                                            <div class="font-medium">{{ $item->title }}</div>
                                            @if($item->is_featured)
                                                <span class="text-xs text-yellow-400">
                                                    <i class="fas fa-star"></i> Nổi bật
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3 px-4">
                                    @if($item->category)
                                        <span class="text-sm">{{ $item->category->name }}</span>
                                    @else
                                        <span class="text-sm text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4">
                                    @if($item->author)
                                        <span class="text-sm">{{ $item->author->name }}</span>
                                    @else
                                        <span class="text-sm text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4">
                                    <span class="px-2 py-1 text-xs rounded bg-{{ $item->status_color }}-900/30 text-{{ $item->status_color }}-400">
                                        {{ $item->status_label }}
                                    </span>
                                </td>
                                <td class="py-3 px-4">
                                    <span class="text-sm">{{ $item->formatted_published_at }}</span>
                                </td>
                                <td class="py-3 px-4">
                                    <span class="text-sm">{{ $item->view_count }}</span>
                                </td>
                                <td class="py-3 px-4">
                                    <div class="flex justify-center gap-1">
                                        <a href="{{ route('admin.news.show', $item) }}" 
                                           class="text-blue-400 hover:text-blue-300" title="Xem">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.news.edit', $item) }}" 
                                           class="text-yellow-400 hover:text-yellow-300" title="Sửa">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button onclick="toggleStatus({{ $item->id }})" 
                                                class="text-green-400 hover:text-green-300" title="Đổi trạng thái">
                                            <i class="fas fa-power-off"></i>
                                        </button>
                                        <button onclick="toggleFeatured({{ $item->id }})" 
                                                class="text-purple-400 hover:text-purple-300" title="Nổi bật">
                                            <i class="fas fa-star"></i>
                                        </button>
                                        <form action="{{ route('admin.news.destroy', $item) }}" 
                                              method="POST" 
                                              onsubmit="return confirm('Bạn có chắc muốn xóa?')"
                                              class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="text-red-400 hover:text-red-300" title="Xóa">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $news->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <div class="bg-gray-100 w-24 h-24 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-newspaper text-gray-400 text-3xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-600 mb-2">Không có tin tức nào</h3>
                <p class="text-gray-500 mb-4">Chưa có bài viết tin tức nào được tạo.</p>
                <a href="{{ route('admin.news.create') }}" class="btn-primary">
                    <i class="fas fa-plus mr-2"></i>Tạo Tin tức đầu tiên
                </a>
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
let selectedNews = new Set();

function toggleSelectAll() {
    const checkboxes = document.querySelectorAll('.news-checkbox');
    const selectAll = document.getElementById('selectAll').checked;
    
    checkboxes.forEach(checkbox => {
        checkbox.checked = selectAll;
        const newsId = checkbox.value;
        
        if (selectAll) {
            selectedNews.add(newsId);
        } else {
            selectedNews.delete(newsId);
        }
    });
}

function toggleNewsSelection(newsId) {
    const checkbox = document.querySelector(`.news-checkbox[value="${newsId}"]`);
    
    if (checkbox.checked) {
        selectedNews.add(newsId);
    } else {
        selectedNews.delete(newsId);
    }
    
    // Update select all checkbox
    const allCheckboxes = document.querySelectorAll('.news-checkbox');
    const selectAll = document.getElementById('selectAll');
    selectAll.checked = selectedNews.size === allCheckboxes.length;
}

function performBulkAction() {
    const action = document.getElementById('bulkAction').value;
    
    if (!action || selectedNews.size === 0) {
        alert('Vui lòng chọn tin tức và hành động');
        return;
    }
    
    if (action === 'delete' && !confirm('Bạn có chắc muốn xóa các tin tức đã chọn?')) {
        return;
    }
    
    const newsIds = Array.from(selectedNews);
    
    fetch('/topsecret/news/bulk-action', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            action: action,
            news_ids: newsIds
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification(data.message, 'success');
            if (action === 'delete') {
                location.reload();
            }
        } else {
            showNotification(data.message, 'error');
        }
    })
    .catch(error => {
        showNotification('Lỗi khi thực hiện hành động', 'error');
    });
}

function toggleStatus(newsId) {
    fetch(`/topsecret/news/${newsId}/publish`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification(data.message, 'success');
            setTimeout(() => location.reload(), 1000);
        } else {
            showNotification(data.message, 'error');
        }
    })
    .catch(error => {
        showNotification('Lỗi khi cập nhật trạng thái!', 'error');
    });
}

function toggleFeatured(newsId) {
    fetch(`/topsecret/news/${newsId}/toggle-featured`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification(data.message, 'success');
            setTimeout(() => location.reload(), 1000);
        } else {
            showNotification(data.message, 'error');
        }
    })
    .catch(error => {
        showNotification('Lỗi khi cập nhật trạng thái nổi bật!', 'error');
    });
}

function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 ${
        type === 'success' ? 'bg-green-600 text-white' : 
        type === 'error' ? 'bg-red-600 text-white' : 
        'bg-blue-600 text-white'
    }`;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 3000);
}

// Event listeners
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.news-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            toggleNewsSelection(this.value);
        });
    });
});
</script>
@endpush
@endsection
