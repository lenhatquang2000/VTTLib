@foreach ($nodes as $node)
    <div class="tree-node" data-node-id="{{ $node['id'] }}">
        <div class="tree-node-content">
            <!-- Toggle Button -->
            @if(!empty($node['children']))
                <button onclick="toggleNode({{ $node['id'] }})" 
                        class="toggle-btn mr-2" id="toggle-{{ $node['id'] }}">
                    <i class="fas fa-chevron-down text-xs"></i>
                </button>
            @else
                <div class="w-5 mr-2"></div>
            @endif
            
            <!-- Node Icon -->
            <div class="node-icon bg-blue-900/30 text-blue-400">
                <i class="{{ $node['icon'] ?? 'fas fa-file' }}"></i>
            </div>
            
            <!-- Node Info -->
            <div class="flex-1">
                <div class="flex items-center gap-3">
                    <span class="font-medium">{{ $node['display_name'] }}</span>
                    
                    <!-- Status Badge -->
                    <span class="status-badge {{ $node['is_active'] ? 'status-active' : 'status-inactive' }}" 
                          id="status-{{ $node['id'] }}">
                        {{ $node['is_active'] ? 'Hoạt động' : 'Ẩn' }}
                    </span>
                    
                    <!-- Type Badge -->
                    <span class="px-2 py-1 text-xs rounded bg-gray-700 text-gray-300">
                        {{ $node['display_type'] }}
                    </span>
                    
                    <!-- Access Type -->
                    <span class="px-2 py-1 text-xs rounded bg-purple-900/30 text-purple-400">
                        @switch($node['access_type'])
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
                
                <!-- Node Details -->
                <div class="text-sm text-gray-400 mt-1">
                    <span class="mr-4">Code: {{ $node['node_code'] }}</span>
                    @if($node['route_name'])
                        <span class="mr-4">Route: {{ $node['route_name'] }}</span>
                    @endif
                    @if($node['url'])
                        <span class="mr-4">URL: {{ $node['url'] }}</span>
                    @endif
                    @if($node['has_content'])
                        <span class="mr-4">Có nội dung</span>
                    @endif
                </div>
            </div>
            
            <!-- Actions -->
            <div class="node-actions">
                @if($node['has_content'])
                    <a href="/page/{{ $node['node_code'] }}" 
                       target="_blank" 
                       class="action-btn text-green-400 hover:text-green-300"
                       title="Xem trang">
                        <i class="fas fa-eye"></i>
                    </a>
                @endif
                
                <button onclick="toggleStatus({{ $node['id'] }})" 
                        class="action-btn text-yellow-400 hover:text-yellow-300"
                        title="Đổi trạng thái">
                    <i class="fas fa-power-off"></i>
                </button>
                
                <a href="{{ route('admin.site-nodes.edit', $node['id']) }}" 
                   class="action-btn text-blue-400 hover:text-blue-300"
                   title="Chỉnh sửa">
                    <i class="fas fa-edit"></i>
                </a>
                
                @if(empty($node['children']))
                    <form id="delete-form-{{ $node['id'] }}" 
                          action="{{ route('admin.site-nodes.destroy', $node['id']) }}" 
                          method="POST" 
                          class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="button" 
                                onclick="confirmDelete({{ $node['id'] }})"
                                class="action-btn text-red-400 hover:text-red-300"
                                title="Xóa">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                @endif
            </div>
        </div>
        
        <!-- Children -->
        @if(!empty($node['children']))
            <div class="tree-children" id="children-{{ $node['id'] }}">
                @include('admin.site-nodes.tree', ['nodes' => $node['children'], 'level' => $level + 1])
            </div>
        @endif
    </div>
@endforeach

@if($level === 0)
    <script>
    function confirmDelete(nodeId) {
        Swal.fire({
            title: 'Xác nhận xóa?',
            text: "Bạn có chắc chắn muốn xóa node này? Hành động này không thể hoàn tác!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Vâng, xóa nó!',
            cancelButtonText: 'Hủy',
            background: document.documentElement.classList.contains('dark') ? '#0f172a' : '#ffffff',
            color: document.documentElement.classList.contains('dark') ? '#f1f5f9' : '#1e293b',
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(`delete-form-${nodeId}`).submit();
            }
        });
    }
    </script>
@endif
