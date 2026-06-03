<?php $__currentLoopData = $nodes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $node): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="tree-node relative" data-node-id="<?php echo e($node['id']); ?>">
        <div class="tree-node-content group/item hover:bg-muted/50 active:bg-muted/80 transition-all cursor-default">
            <!-- Toggle Button -->
            <div class="flex items-center w-5 mr-1 shrink-0">
                <?php if(!empty($node['children'])): ?>
                    <button onclick="toggleNode(<?php echo e($node['id']); ?>)" 
                            class="w-5 h-5 flex items-center justify-center rounded hover:bg-primary/20 text-muted-foreground transition-all" id="toggle-<?php echo e($node['id']); ?>">
                        <i data-lucide="chevron-down" class="w-3.5 h-3.5 transition-transform duration-200"></i>
                    </button>
                <?php endif; ?>
            </div>
            
            <!-- Node Icon -->
            <div class="w-8 h-8 shrink-0 bg-primary/10 rounded flex items-center justify-center text-primary mr-3 border border-primary/20 overflow-hidden">
                <?php
                    $iconValue = $node['icon'] ?? 'fas fa-file';
                    $isHtml = str_contains($iconValue, '<') && str_contains($iconValue, '>');
                ?>

                <?php if($isHtml): ?>
                    <div class="w-4 h-4 flex items-center justify-center [&>svg]:w-4 [&>svg]:h-4 [&>i]:text-[14px]">
                        <?php echo $iconValue; ?>

                    </div>
                <?php else: ?>
                    <i class="<?php echo e($iconValue); ?> text-[14px]"></i>
                <?php endif; ?>
            </div>
            
            <!-- Node Info -->
            <div class="flex-1 min-w-0">
                <div class="flex items-center flex-wrap gap-2">
                    <span class="text-xs font-bold text-foreground truncate"><?php echo e($node['display_name']); ?></span>
                    
                    <div class="flex items-center gap-1.5 shrink-0">
                        <!-- Status Badge -->
                        <span class="inline-flex items-center px-1.5 py-0.5 rounded-sm text-[9px] font-bold uppercase tracking-wider <?php echo e($node['is_active'] ? 'bg-emerald-500/10 text-emerald-600 border border-emerald-500/20' : 'bg-destructive/10 text-destructive border border-destructive/20'); ?>" 
                              id="status-<?php echo e($node['id']); ?>">
                            <?php echo e($node['is_active'] ? 'Hoạt động' : 'Ẩn'); ?>

                        </span>
                        
                        <!-- Type Badge -->
                        <span class="inline-flex items-center px-1.5 py-0.5 rounded-sm text-[9px] font-bold uppercase tracking-wider bg-muted text-muted-foreground border border-border">
                            <?php echo e($node['display_type']); ?>

                        </span>
                        
                        <!-- Access Type -->
                        <span class="inline-flex items-center px-1.5 py-0.5 rounded-sm text-[9px] font-bold uppercase tracking-wider bg-purple-500/10 text-purple-600 border border-purple-500/20">
                            <?php switch($node['access_type']):
                                case ('public'): ?> Công khai <?php break; ?>
                                <?php case ('auth'): ?> Yêu cầu ĐN <?php break; ?>
                                <?php case ('roles'): ?> Theo vai trò <?php break; ?>
                            <?php endswitch; ?>
                        </span>
                    </div>
                </div>
                
                <!-- Node Details -->
                <div class="flex items-center gap-3 mt-0.5">
                    <span class="text-[10px] text-muted-foreground font-mono bg-muted/30 px-1 rounded">#<?php echo e($node['node_code']); ?></span>
                    <?php if($node['route_name']): ?>
                        <div class="flex items-center gap-1 text-[9px] text-primary italic">
                            <i data-lucide="link" class="w-2.5 h-2.5"></i>
                            <span><?php echo e($node['route_name']); ?></span>
                        </div>
                    <?php endif; ?>
                    <?php if($node['url']): ?>
                        <div class="flex items-center gap-1 text-[9px] text-emerald-600 italic">
                            <i data-lucide="external-link" class="w-2.5 h-2.5"></i>
                            <span><?php echo e(Str::limit($node['url'], 30)); ?></span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Actions -->
            <div class="flex items-center gap-1 opacity-0 group-hover/item:opacity-100 transition-opacity ml-4 shrink-0">
                <?php if($node['has_content']): ?>
                    <a href="/page/<?php echo e($node['node_code']); ?>" 
                       target="_blank" 
                       class="w-7 h-7 flex items-center justify-center rounded bg-background hover:bg-emerald-500 hover:text-white text-muted-foreground border border-border transition-all active:scale-90"
                       title="Xem trang">
                        <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                    </a>
                <?php endif; ?>
                
                <button onclick="toggleStatus(<?php echo e($node['id']); ?>)" 
                        class="w-7 h-7 flex items-center justify-center rounded bg-background hover:bg-amber-500 hover:text-white text-muted-foreground border border-border transition-all active:scale-90"
                        title="Đổi trạng thái">
                    <i data-lucide="power" class="w-3.5 h-3.5"></i>
                </button>
                
                <a href="<?php echo e(route('admin.site-nodes.edit', $node['id'])); ?>" 
                   class="w-7 h-7 flex items-center justify-center rounded bg-background hover:bg-primary hover:text-white text-muted-foreground border border-border transition-all active:scale-90"
                   title="Chỉnh sửa">
                    <i data-lucide="edit-2" class="w-3.5 h-3.5"></i>
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
                                class="w-7 h-7 flex items-center justify-center rounded bg-background hover:bg-destructive hover:text-white text-muted-foreground border border-border transition-all active:scale-90"
                                title="Xóa">
                            <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                        </button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Children -->
        <?php if(!empty($node['children'])): ?>
            <div class="tree-children border-l border-border border-dashed ml-6 pl-4 mt-1 space-y-1" id="children-<?php echo e($node['id']); ?>">
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