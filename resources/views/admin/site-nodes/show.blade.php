@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold">{{ __('Chi tiết Node') }}</h1>
            <p class="text-sm text-gray-400 mt-1">{{ __('Thông tin chi tiết: ') }}{{ $siteNode->display_name }}</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.site-nodes.edit', $siteNode) }}" class="btn-primary">
                <i class="fas fa-edit mr-2"></i>{{ __('Chỉnh sửa') }}
            </a>
            <a href="{{ route('admin.site-nodes.index', ['language' => $siteNode->language]) }}" class="btn-secondary">
                <i class="fas fa-arrow-left mr-2"></i>{{ __('Quay lại') }}
            </a>
        </div>
    </div>

    <!-- Node Details -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Information -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Basic Info -->
            <div class="card-admin p-6">
                <h3 class="text-lg font-bold mb-4 text-blue-400">Thông tin cơ bản</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm text-gray-400">Mã Node</label>
                        <p class="font-medium">{{ $siteNode->node_code }}</p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-400">Tên Node</label>
                        <p class="font-medium">{{ $siteNode->node_name }}</p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-400">Tên hiển thị</label>
                        <p class="font-medium">{{ $siteNode->display_name }}</p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-400">Ngôn ngữ</label>
                        <p class="font-medium">{{ $siteNode->language }}</p>
                    </div>
                    <div class="md:col-span-2">
                        <label class="text-sm text-gray-400">Mô tả</label>
                        <p class="font-medium">{{ $siteNode->description ?: 'Không có' }}</p>
                    </div>
                </div>
            </div>

            <!-- Content -->
            @if($siteNode->content)
            <div class="card-admin p-6">
                <h3 class="text-lg font-bold mb-4 text-green-400">Nội dung trang</h3>
                <div class="bg-gray-900/50 p-4 rounded-lg">
                    <div class="prose prose-invert max-w-none">
                        {!! $siteNode->content !!}
                    </div>
                </div>
            </div>
            @endif

            <!-- SEO Information -->
            <div class="card-admin p-6">
                <h3 class="text-lg font-bold mb-4 text-red-400">SEO</h3>
                <div class="space-y-3">
                    <div>
                        <label class="text-sm text-gray-400">Meta Title</label>
                        <p class="font-medium">{{ $siteNode->meta_title ?: 'Không có' }}</p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-400">Meta Description</label>
                        <p class="font-medium">{{ $siteNode->meta_description ?: 'Không có' }}</p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-400">Meta Keywords</label>
                        <p class="font-medium">{{ $siteNode->meta_keywords ?: 'Không có' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar Information -->
        <div class="space-y-6">
            <!-- Status -->
            <div class="card-admin p-6">
                <h3 class="text-lg font-bold mb-4 text-yellow-400">Trạng thái</h3>
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-400">Kích hoạt</span>
                        <span class="px-2 py-1 text-xs rounded {{ $siteNode->is_active ? 'bg-green-900/30 text-green-400' : 'bg-red-900/30 text-red-400' }}">
                            {{ $siteNode->is_active ? 'Hoạt động' : 'Ẩn' }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-400">Kiểu hiển thị</span>
                        <span class="px-2 py-1 text-xs rounded bg-gray-700 text-gray-300">
                            {{ $siteNode->display_type }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-400">Target</span>
                        <span class="px-2 py-1 text-xs rounded bg-gray-700 text-gray-300">
                            {{ $siteNode->target }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-400">Thứ tự</span>
                        <span class="font-medium">{{ $siteNode->sort_order }}</span>
                    </div>
                </div>
            </div>

            <!-- Access Control -->
            <div class="card-admin p-6">
                <h3 class="text-lg font-bold mb-4 text-purple-400">Quyền truy cập</h3>
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-400">Loại truy cập</span>
                        <span class="px-2 py-1 text-xs rounded bg-purple-900/30 text-purple-400">
                            @switch($siteNode->access_type)
                                @case('public')
                                    Công khai
                                    @break
                                @case('auth')
                                    Yêu cầu đăng nhập
                                    @break
                                @case('roles')
                                    Theo vai trò
                                    @break
                            @endswitch
                        </span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-400">Cho phép khách</span>
                        <span class="px-2 py-1 text-xs rounded {{ $siteNode->allow_guest ? 'bg-green-900/30 text-green-400' : 'bg-red-900/30 text-red-400' }}">
                            {{ $siteNode->allow_guest ? 'Có' : 'Không' }}
                        </span>
                    </div>
                    @if($siteNode->allowed_roles)
                    <div>
                        <label class="text-sm text-gray-400">Vai trò được phép</label>
                        <div class="mt-1 space-y-1">
                            @foreach($siteNode->allowed_roles as $role)
                                <span class="px-2 py-1 text-xs rounded bg-blue-900/30 text-blue-400">
                                    {{ $role }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Link Information -->
            <div class="card-admin p-6">
                <h3 class="text-lg font-bold mb-4 text-cyan-400">Link</h3>
                <div class="space-y-3">
                    @if($siteNode->route_name)
                    <div>
                        <label class="text-sm text-gray-400">Route</label>
                        <p class="font-medium text-blue-400">{{ $siteNode->route_name }}</p>
                    </div>
                    @endif
                    @if($siteNode->url)
                    <div>
                        <label class="text-sm text-gray-400">URL</label>
                        <p class="font-medium text-blue-400 break-all">{{ $siteNode->url }}</p>
                    </div>
                    @endif
                    <div>
                        <label class="text-sm text-gray-400">URL đầy đủ</label>
                        <p class="font-medium text-blue-400 break-all">{{ $siteNode->getUrl() }}</p>
                    </div>
                </div>
            </div>

            <!-- Hierarchy -->
            <div class="card-admin p-6">
                <h3 class="text-lg font-bold mb-4 text-orange-400">Cấu trúc</h3>
                <div class="space-y-3">
                    @if($siteNode->parent)
                    <div>
                        <label class="text-sm text-gray-400">Node cha</label>
                        <a href="{{ route('admin.site-nodes.show', $siteNode->parent) }}" 
                           class="font-medium text-blue-400 hover:text-blue-300">
                            {{ $siteNode->parent->display_name }}
                        </a>
                    </div>
                    @else
                    <div>
                        <label class="text-sm text-gray-400">Node cha</label>
                        <p class="font-medium">Gốc (Root)</p>
                    </div>
                    @endif
                    <div>
                        <label class="text-sm text-gray-400">Số node con</label>
                        <p class="font-medium">{{ $siteNode->children->count() }}</p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-400">Độ sâu</label>
                        <p class="font-medium">{{ $siteNode->getDepth() }}</p>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="card-admin p-6">
                <h3 class="text-lg font-bold mb-4 text-gray-400">Hành động</h3>
                <div class="space-y-2">
                    @if($siteNode->hasContent())
                    <a href="{{ $siteNode->getUrl() }}" 
                       target="_blank" 
                       class="w-full btn-secondary text-center">
                        <i class="fas fa-eye mr-2"></i>Xem trang
                    </a>
                    @endif
                    <button onclick="duplicateNode({{ $siteNode->id }})" 
                            class="w-full btn-secondary text-center">
                        <i class="fas fa-copy mr-2"></i>Sao chép
                    </button>
                    <button onclick="toggleStatus({{ $siteNode->id }})" 
                            class="w-full btn-secondary text-center">
                        <i class="fas fa-power-off mr-2"></i>Đổi trạng thái
                    </button>
                    @if($siteNode->children->count() === 0)
                    <form action="{{ route('admin.site-nodes.destroy', $siteNode) }}" 
                          method="POST" 
                          onsubmit="return confirm('Bạn có chắc muốn xóa node này?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full btn-danger text-center">
                            <i class="fas fa-trash mr-2"></i>Xóa
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Children Nodes -->
    @if($siteNode->children->count() > 0)
    <div class="card-admin p-6">
        <h3 class="text-lg font-bold mb-4">Node con ({{ $siteNode->children->count() }})</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($siteNode->children as $child)
            <div class="border border-gray-700 rounded-lg p-4 hover:border-blue-500 transition">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <h4 class="font-medium mb-1">{{ $child->display_name }}</h4>
                        <p class="text-sm text-gray-400 mb-2">{{ $child->node_code }}</p>
                        <div class="flex gap-2">
                            <span class="px-2 py-1 text-xs rounded {{ $child->is_active ? 'bg-green-900/30 text-green-400' : 'bg-red-900/30 text-red-400' }}">
                                {{ $child->is_active ? 'Hoạt động' : 'Ẩn' }}
                            </span>
                            <span class="px-2 py-1 text-xs rounded bg-gray-700 text-gray-300">
                                {{ $child->display_type }}
                            </span>
                        </div>
                    </div>
                    <div class="flex gap-1">
                        <a href="{{ route('admin.site-nodes.show', $child) }}" 
                           class="text-blue-400 hover:text-blue-300" title="Xem chi tiết">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('admin.site-nodes.edit', $child) }}" 
                           class="text-yellow-400 hover:text-yellow-300" title="Chỉnh sửa">
                            <i class="fas fa-edit"></i>
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>

@push('scripts')
<script>
function toggleStatus(nodeId) {
    fetch(`/topsecret/site-nodes/${nodeId}/toggle-status`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
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

function duplicateNode(nodeId) {
    if (confirm('Bạn có chắc muốn sao chép node này?')) {
        fetch(`/topsecret/site-nodes/${nodeId}/duplicate`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.redirect) {
                window.location.href = data.redirect;
            } else {
                showNotification('Sao chép thành công!', 'success');
                setTimeout(() => location.reload(), 1000);
            }
        })
        .catch(error => {
            showNotification('Lỗi khi sao chép node!', 'error');
        });
    }
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
</script>
@endpush
@endsection
