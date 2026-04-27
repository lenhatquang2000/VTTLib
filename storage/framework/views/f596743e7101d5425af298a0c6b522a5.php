<?php $__currentLoopData = $nodes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $node): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="tree-node" data-node-id="<?php echo e($node['id']); ?>">
        <div class="tree-node-content">
            <!-- Toggle Button -->
            <?php if(!empty($node['children'])): ?>
                <button onclick="toggleNode(<?php echo e($node['id']); ?>)" 
                        class="toggle-btn mr-2" id="toggle-<?php echo e($node['id']); ?>">
                    <i class="fas fa-chevron-down text-xs"></i>
                </button>
            <?php else: ?>
                <div class="w-5 mr-2"></div>
            <?php endif; ?>
            
            <!-- Node Icon -->
            <div class="node-icon bg-blue-900/30 text-blue-400">
                <i class="<?php echo e($node['icon'] ?? 'fas fa-file'); ?>"></i>
            </div>
            
            <!-- Node Info -->
            <div class="flex-1">
                <div class="flex items-center gap-3">
                    <span class="font-medium"><?php echo e($node['display_name']); ?></span>
                    
                    <!-- Status Badge -->
                    <span class="status-badge <?php echo e($node['is_active'] ? 'status-active' : 'status-inactive'); ?>" 
                          id="status-<?php echo e($node['id']); ?>">
                        <?php echo e($node['is_active'] ? 'Hoạt động' : 'Ẩn'); ?>

                    </span>
                    
                    <!-- Type Badge -->
                    <span class="px-2 py-1 text-xs rounded bg-gray-700 text-gray-300">
                        <?php echo e($node['display_type']); ?>

                    </span>
                    
                    <!-- Access Type -->
                    <span class="px-2 py-1 text-xs rounded bg-purple-900/30 text-purple-400">
                        <?php switch($node['access_type']):
                            case ('public'): ?>
                                Công khai
                                <?php break; ?>
                            <?php case ('auth'): ?>
                                Yêu cầu đăng nhập
                                <?php break; ?>
                            <?php case ('roles'): ?>
                                Theo vai trò
                                <?php break; ?>
                        <?php endswitch; ?>
                    </span>
                </div>
                
                <!-- Node Details -->
                <div class="text-sm text-gray-400 mt-1">
                    <span class="mr-4">Code: <?php echo e($node['node_code']); ?></span>
                    <?php if($node['route_name']): ?>
                        <span class="mr-4">Route: <?php echo e($node['route_name']); ?></span>
                    <?php endif; ?>
                    <?php if($node['url']): ?>
                        <span class="mr-4">URL: <?php echo e($node['url']); ?></span>
                    <?php endif; ?>
                    <?php if($node['has_content']): ?>
                        <span class="mr-4">Có nội dung</span>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Actions -->
            <div class="node-actions">
                <?php if($node['has_content']): ?>
                    <a href="/page/<?php echo e($node['node_code']); ?>" 
                       target="_blank" 
                       class="action-btn text-green-400 hover:text-green-300"
                       title="Xem trang">
                        <i class="fas fa-eye"></i>
                    </a>
                <?php endif; ?>
                
                <button onclick="toggleStatus(<?php echo e($node['id']); ?>)" 
                        class="action-btn text-yellow-400 hover:text-yellow-300"
                        title="Đổi trạng thái">
                    <i class="fas fa-power-off"></i>
                </button>
                
                <a href="<?php echo e(route('admin.site-nodes.edit', $node['id'])); ?>" 
                   class="action-btn text-blue-400 hover:text-blue-300"
                   title="Chỉnh sửa">
                    <i class="fas fa-edit"></i>
                </a>
                
                <?php if(empty($node['children'])): ?>
                    <form id="delete-form-<?php echo e($node['id']); ?>" 
                          action="<?php echo e(route('admin.site-nodes.destroy', $node['id'])); ?>" 
                          method="POST" 
                          class="inline">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('DELETE'); ?>
                        <button type="button" 
                                onclick="confirmDelete(<?php echo e($node['id']); ?>)"
                                class="action-btn text-red-400 hover:text-red-300"
                                title="Xóa">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Children -->
        <?php if(!empty($node['children'])): ?>
            <div class="tree-children" id="children-<?php echo e($node['id']); ?>">
                <?php echo $__env->make('admin.site-nodes.tree', ['nodes' => $node['children'], 'level' => $level + 1], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            </div>
        <?php endif; ?>
    </div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

<?php if($level === 0): ?>
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
<?php endif; ?>
<?php /**PATH E:\Workspace\VTTU\Laravel\VTTLib\resources\views/admin/site-nodes/tree.blade.php ENDPATH**/ ?>