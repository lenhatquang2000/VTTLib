<?php $__env->startSection('content'); ?>
<div class="space-y-4 animate-fade-in">
    <!-- Header & Actions -->
    <div class="bg-card p-4 rounded-xl border border-border shadow-sm space-y-4 lg:space-y-0 lg:flex lg:items-center lg:justify-between">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-primary/10 rounded-xl flex items-center justify-center text-primary shadow-sm border border-primary/10">
                <i data-lucide="sitemap" class="w-6 h-6"></i>
            </div>
            <div>
                <div class="flex items-center gap-2 text-[10px] font-black text-muted-foreground uppercase tracking-[0.2em] mb-0.5">
                    <span><?php echo e(__('Site')); ?></span>
                    <i data-lucide="chevron-right" class="w-3 h-3 opacity-50"></i>
                    <span><?php echo e(__('Management')); ?></span>
                </div>
                <h1 class="text-xl font-black text-foreground tracking-tight"><?php echo e(__('Quản lý Cấu trúc Website')); ?></h1>
            </div>
        </div>
        
        <div class="flex flex-wrap items-center gap-2">
            <button onclick="expandAll()" class="inline-flex items-center px-4 py-2 bg-muted hover:bg-muted/80 text-muted-foreground text-xs font-bold rounded-lg transition-all border border-border shadow-sm">
                <i data-lucide="expand" class="w-4 h-4 mr-2"></i> <?php echo e(__('Mở hết')); ?>

            </button>
            <button onclick="collapseAll()" class="inline-flex items-center px-4 py-2 bg-muted hover:bg-muted/80 text-muted-foreground text-xs font-bold rounded-lg transition-all border border-border shadow-sm">
                <i data-lucide="shrink" class="w-4 h-4 mr-2"></i> <?php echo e(__('Thu hết')); ?>

            </button>
            <div class="w-px h-6 bg-border mx-1"></div>
            <a href="<?php echo e(route('admin.site-nodes.create')); ?>" class="inline-flex items-center px-5 py-2 bg-primary hover:bg-primary/90 text-primary-foreground text-xs font-bold rounded-lg transition-all shadow-md shadow-primary/20 border border-primary/10">
                <i data-lucide="plus" class="w-4 h-4 mr-2"></i><?php echo e(__('Thêm Node')); ?>

            </a>
        </div>
    </div>

    <!-- Info & Statistics -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-4">
        <!-- Language Info (4/12) -->
        <div class="lg:col-span-4 bg-card border border-border rounded-md p-3 shadow-sm flex flex-col justify-center">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded bg-muted flex items-center justify-center text-muted-foreground">
                    <i data-lucide="languages" class="w-4 h-4"></i>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-muted-foreground uppercase tracking-wider"><?php echo e(__('Ngôn ngữ hiện tại')); ?></p>
                    <p class="text-xs font-bold text-foreground"><?php echo e(strtoupper($language)); ?> - <?php echo e($language === 'vi' ? 'Tiếng Việt' : 'English'); ?></p>
                </div>
            </div>
            <p class="mt-2 text-[9px] text-muted-foreground italic leading-tight">* <?php echo e(__('Sử dụng bộ chuyển đổi ngôn ngữ ở thanh trên để thay đổi.')); ?></p>
        </div>

        <!-- Statistics (8/12) -->
        <div class="lg:col-span-8 grid grid-cols-2 md:grid-cols-4 gap-3">
            <div class="bg-card border border-border rounded-md p-3 shadow-sm flex flex-col justify-between">
                <div class="flex items-center justify-between mb-1">
                    <p class="text-[9px] font-bold text-muted-foreground uppercase tracking-widest">Tổng số</p>
                    <i data-lucide="files" class="w-3 h-3 text-blue-500 opacity-50"></i>
                </div>
                <p class="text-xl font-black text-foreground"><?php echo e($stats['total'] ?? 0); ?></p>
            </div>
            <div class="bg-card border border-border rounded-md p-3 shadow-sm flex flex-col justify-between">
                <div class="flex items-center justify-between mb-1">
                    <p class="text-[9px] font-bold text-muted-foreground uppercase tracking-widest">Hoạt động</p>
                    <i data-lucide="check-circle-2" class="w-3 h-3 text-emerald-500 opacity-50"></i>
                </div>
                <p class="text-xl font-black text-foreground"><?php echo e($stats['published'] ?? 0); ?></p>
            </div>
            <div class="bg-card border border-border rounded-md p-3 shadow-sm flex flex-col justify-between">
                <div class="flex items-center justify-between mb-1">
                    <p class="text-[9px] font-bold text-muted-foreground uppercase tracking-widest">Bản nháp</p>
                    <i data-lucide="edit-3" class="w-3 h-3 text-amber-500 opacity-50"></i>
                </div>
                <p class="text-xl font-black text-foreground"><?php echo e($stats['draft'] ?? 0); ?></p>
            </div>
            <div class="bg-card border border-border rounded-md p-3 shadow-sm flex flex-col justify-between">
                <div class="flex items-center justify-between mb-1">
                    <p class="text-[9px] font-bold text-muted-foreground uppercase tracking-widest">Ngôn ngữ</p>
                    <i data-lucide="globe" class="w-3 h-3 text-purple-500 opacity-50"></i>
                </div>
                <p class="text-xl font-black text-foreground uppercase"><?php echo e($language); ?></p>
            </div>
        </div>
    </div>

    <!-- Layout Settings (Collapse) -->
    <div x-data="{ open: false }" class="bg-card border border-border rounded-md overflow-hidden shadow-sm transition-all duration-200">
        <button @click="open = !open" type="button" class="w-full flex items-center justify-between p-3 hover:bg-muted/50 transition-colors">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded bg-primary/10 flex items-center justify-center text-primary">
                    <i data-lucide="palette" class="w-4 h-4"></i>
                </div>
                <div class="text-left">
                    <h3 class="text-xs font-bold text-foreground uppercase tracking-tight"><?php echo e(__('Cấu hình giao diện (Logo & Banner)')); ?></h3>
                </div>
            </div>
            <i data-lucide="chevron-down" class="w-4 h-4 text-muted-foreground transition-transform duration-300" :class="open && 'rotate-180'"></i>
        </button>

        <div x-show="open" x-collapse x-cloak class="border-t border-border">
            <form action="<?php echo e(route('admin.site-nodes.layout-settings')); ?>" method="POST" enctype="multipart/form-data" class="p-4 space-y-4">
                <?php echo csrf_field(); ?>
                <div class="flex items-center gap-4 p-3 bg-muted/30 rounded-md border border-border border-dashed">
                    <div class="flex items-center gap-3">
                        <?php $currentLogo = \App\Models\SystemSetting::get('site_logo'); ?>
                        <?php if($currentLogo): ?>
                            <img src="<?php echo e(asset('storage/' . $currentLogo)); ?>" alt="Logo" class="h-8 w-8 object-contain rounded border border-border bg-background">
                        <?php else: ?>
                            <div class="h-8 w-8 rounded bg-primary/10 flex items-center justify-center">
                                <i data-lucide="library" class="w-4 h-4 text-primary"></i>
                            </div>
                        <?php endif; ?>
                        <span class="font-bold text-foreground text-sm uppercase tracking-tight">
                            <?php echo e(\App\Models\SystemSetting::get('site_name', 'Thư viện số')); ?>

                        </span>
                    </div>
                    <span class="ml-auto text-[9px] font-bold text-muted-foreground uppercase tracking-widest">Preview hiện tại</span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-1.5">
                        <label class="text-[10px] text-muted-foreground uppercase font-bold tracking-wider"><?php echo e(__('Tên Banner hiển thị')); ?></label>
                        <input type="text" name="site_name"
                               value="<?php echo e(\App\Models\SystemSetting::get('site_name', 'Thư viện số')); ?>"
                               placeholder="VD: Thư viện số VTTU"
                               class="w-full bg-background border border-border rounded p-2 text-xs text-foreground placeholder:text-muted-foreground focus:ring-1 focus:ring-primary outline-none transition-all">
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-[10px] text-muted-foreground uppercase font-bold tracking-wider"><?php echo e(__('Tải lên Logo')); ?></label>
                        <div x-data="{ fileName: '', previewUrl: '<?php echo e($currentLogo ? asset('storage/' . $currentLogo) : ''); ?>', removeLogo: false }" class="space-y-2">
                            <div class="flex items-center gap-2">
                                <label class="flex-1 flex items-center gap-2 px-3 py-2 bg-background border border-border border-dashed rounded cursor-pointer hover:bg-muted/50 transition-colors">
                                    <i data-lucide="cloud-upload" class="w-4 h-4 text-muted-foreground"></i>
                                    <span class="text-[11px] text-muted-foreground truncate" x-text="fileName || 'Chọn file logo...'"></span>
                                    <input type="file" name="site_logo" accept="image/*" class="hidden"
                                           @change="fileName = $event.target.files[0]?.name; previewUrl = URL.createObjectURL($event.target.files[0]); removeLogo = false">
                                </label>
                                <?php if($currentLogo): ?>
                                    <button type="button" @click="removeLogo = !removeLogo; previewUrl = removeLogo ? '' : '<?php echo e(asset('storage/' . $currentLogo)); ?>'"
                                            class="w-8 h-8 flex items-center justify-center rounded transition-all"
                                            :class="removeLogo ? 'bg-destructive/10 text-destructive' : 'bg-muted text-muted-foreground hover:bg-destructive/10 hover:text-destructive'">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                    <input type="hidden" name="remove_logo" :value="removeLogo ? 1 : 0">
                                <?php endif; ?>
                            </div>
                            <template x-if="previewUrl">
                                <div class="flex items-center gap-2 p-2 bg-muted/50 rounded-md border border-border">
                                    <img :src="previewUrl" class="h-6 w-6 object-contain rounded">
                                    <span class="text-[10px] text-muted-foreground italic">Xem trước logo mới</span>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end pt-2 border-t border-border">
                    <button type="submit" class="btn-compact-primary">
                        <i data-lucide="save" class="w-4 h-4 mr-2"></i><?php echo e(__('Lưu cấu hình')); ?>

                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Site Structure Tree -->
    <div class="bg-card border border-border rounded-xl shadow-sm p-5 transition-colors duration-200">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-1.5 h-5 bg-primary rounded-full"></div>
            <h3 class="text-sm font-black text-foreground uppercase tracking-wider"><?php echo e(__('Cấu trúc Site (Dạng cây)')); ?></h3>
        </div>
        
        <div class="relative">
            <?php if(count($tree) > 0): ?>
            <div id="site-tree" class="space-y-1">
                <?php echo $__env->make('admin.site-nodes.tree', ['nodes' => $tree, 'level' => 0], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            </div>
            <?php else: ?>
            <div class="flex flex-col items-center py-16">
                <div class="w-16 h-16 bg-muted rounded-full flex items-center justify-center text-muted-foreground mb-4 border border-border border-dashed">
                    <i data-lucide="sitemap" class="w-8 h-8 opacity-20"></i>
                </div>
                <h3 class="text-sm font-bold text-foreground mb-1"><?php echo e(__('Chưa có Node nào')); ?></h3>
                <p class="text-xs text-muted-foreground mb-4"><?php echo e(__('Hãy bắt đầu bằng cách tạo trang đầu tiên của bạn.')); ?></p>
                <a href="<?php echo e(route('admin.site-nodes.create')); ?>" class="btn-compact-primary">
                    <i data-lucide="plus" class="w-4 h-4 mr-2"></i><?php echo e(__('Tạo Node đầu tiên')); ?>

                </a>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php $__env->startPush('styles'); ?>
<style>
    .tree-node-content {
        display: flex;
        align-items: center;
        padding: 6px 12px;
        border-radius: 4px;
    }
    
    .tree-children {
        display: block;
    }
    
    .hidden {
        display: none;
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="https://unpkg.com/lucide@latest"></script>
<script>
function toggleNode(nodeId) {
    const children = document.getElementById(`children-${nodeId}`);
    const toggle = document.getElementById(`toggle-${nodeId}`);
    
    if (children) {
        children.classList.toggle('hidden');
        const icon = toggle.querySelector('svg, i');
        if (children.classList.contains('hidden')) {
            icon.style.transform = 'rotate(-90deg)';
        } else {
            icon.style.transform = 'rotate(0deg)';
        }
    }
}

function expandAll() {
    const allChildren = document.querySelectorAll('.tree-children');
    const allToggles = document.querySelectorAll('.w-5.h-5 i');
    
    allChildren.forEach(child => child.classList.remove('hidden'));
    allToggles.forEach(icon => {
        icon.style.transform = 'rotate(0deg)';
    });
}

function collapseAll() {
    const allChildren = document.querySelectorAll('.tree-children');
    const allToggles = document.querySelectorAll('.w-5.h-5 i');
    
    allChildren.forEach(child => child.classList.add('hidden'));
    allToggles.forEach(icon => {
        icon.style.transform = 'rotate(-90deg)';
    });
}

// Re-initialize Lucide icons after content updates
document.addEventListener('DOMContentLoaded', function() {
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
});

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
            const badge = document.getElementById(`status-${nodeId}`);
            badge.className = `status-badge ${data.is_active ? 'status-active' : 'status-inactive'}`;
            badge.textContent = data.is_active ? 'Hoạt động' : 'Ẩn';
            
            // Show success message
            showNotification(data.message, 'success');
        } else {
            showNotification(data.message, 'error');
        }
    })
    .catch(error => {
        showNotification('Lỗi khi cập nhật trạng thái!', 'error');
    });
}

function showNotification(message, type = 'info') {
    // Simple notification implementation
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

// Drag and drop for reordering
document.addEventListener('DOMContentLoaded', function() {
    // Initialize drag and drop functionality here
    console.log('Site management initialized');
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\Workspace\VTTU\Laravel\VTTLib\resources\views/admin/site-nodes/index.blade.php ENDPATH**/ ?>