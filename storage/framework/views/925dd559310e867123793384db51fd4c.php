

<?php $__env->startSection('content'); ?>
<div class="space-y-3 animate-fade-in">
    <!-- Header -->
    <div class="bg-card p-4 border border-border rounded">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2">
                <i data-lucide="sitemap" class="w-4 h-4 text-muted-foreground"></i>
                <h1 class="text-sm font-bold text-foreground uppercase tracking-wide"><?php echo e(__('Quản lý Website')); ?></h1>
            </div>
            <a href="<?php echo e(route('admin.site-nodes.create')); ?>" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-primary hover:bg-primary/90 text-primary-foreground text-xs font-bold rounded transition-all">
                <i data-lucide="plus" class="w-4 h-4"></i><?php echo e(__('Thêm')); ?>

            </a>
        </div>
    </div>

    <!-- Tabs -->
    <div x-data="{ activeTab: 'layout' }" class="bg-card border border-border rounded overflow-hidden">
        <!-- Tab Navigation -->
        <div class="flex border-b border-border bg-muted/30">
            <button @click="activeTab = 'layout'" 
                    class="flex-1 flex items-center justify-center gap-1.5 px-4 py-2 text-xs font-bold uppercase text-muted-foreground transition-all"
                    :class="activeTab === 'layout' ? 'bg-card text-foreground border-b-2 border-primary' : 'hover:bg-muted'">
                <i data-lucide="palette" class="w-4 h-4"></i>
                <span class="hidden sm:inline"><?php echo e(__('Cấu hình giao diện')); ?></span>
            </button>
            <button @click="activeTab = 'structure'" 
                    class="flex-1 flex items-center justify-center gap-1.5 px-4 py-2 text-xs font-bold uppercase text-muted-foreground transition-all"
                    :class="activeTab === 'structure' ? 'bg-card text-foreground border-b-2 border-primary' : 'hover:bg-muted'">
                <i data-lucide="sitemap" class="w-4 h-4"></i>
                <span class="hidden sm:inline"><?php echo e(__('Cấu trúc')); ?></span>
            </button>
        </div>

        <!-- Tab Content -->
        <div class="p-4">
            <!-- Layout Tab -->
            <div x-show="activeTab === 'layout'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="space-y-3">
                <!-- Logo & Name Section -->
                <div class="border-b border-border pb-3">
                    <h3 class="text-xs font-bold text-foreground uppercase tracking-wide mb-3 flex items-center gap-2">
                        <i data-lucide="settings" class="w-4 h-4 text-muted-foreground"></i>
                        <?php echo e(__('Logo & Tên')); ?>

                    </h3>
                    
                    <form action="<?php echo e(route('admin.site-nodes.layout-settings')); ?>" method="POST" enctype="multipart/form-data" class="space-y-3">
                        <?php echo csrf_field(); ?>
                        
                        <!-- Preview -->
                        <div class="p-2.5 bg-muted/20 border border-border rounded">
                            <p class="text-[9px] text-muted-foreground font-bold uppercase tracking-wide mb-2">Xem trước</p>
                            <div class="flex items-center gap-2 p-2.5 bg-card border border-border rounded">
                                <?php $currentLogo = \App\Models\SystemSetting::get('site_logo'); ?>
                                <?php if($currentLogo): ?>
                                    <img src="<?php echo e(asset('storage/' . $currentLogo)); ?>" alt="Logo" class="h-10 w-10 object-contain rounded border border-border bg-background">
                                <?php else: ?>
                                    <div class="h-10 w-10 rounded bg-muted flex items-center justify-center flex-shrink-0">
                                        <i data-lucide="library" class="w-5 h-5 text-muted-foreground"></i>
                                    </div>
                                <?php endif; ?>
                                <div class="flex-1 min-w-0">
                                    <h5 class="text-xs font-bold text-foreground truncate"><?php echo e(\App\Models\SystemSetting::get('site_name', 'Thư viện số')); ?></h5>
                                    <p class="text-[9px] text-muted-foreground">Header</p>
                                </div>
                            </div>
                        </div>

                        <!-- Inputs -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                            <div class="space-y-1.5">
                                <label class="text-[9px] font-bold text-muted-foreground uppercase tracking-wide"><?php echo e(__('Tên')); ?></label>
                                <input type="text" name="site_name"
                                       value="<?php echo e(\App\Models\SystemSetting::get('site_name', 'Thư viện số')); ?>"
                                       class="w-full h-9 bg-background border border-border rounded-sm px-2.5 text-xs text-foreground placeholder:text-muted-foreground focus:ring-1 focus:ring-primary focus:border-primary outline-none transition-all">
                            </div>

                            <div class="space-y-1.5" x-data="{ fileName: '', previewUrl: '<?php echo e($currentLogo ? asset('storage/' . $currentLogo) : ''); ?>', removeLogo: false }">
                                <label class="text-[9px] font-bold text-muted-foreground uppercase tracking-wide"><?php echo e(__('Logo')); ?></label>
                                <div class="flex items-center gap-1.5">
                                    <label class="flex-1 flex items-center gap-1.5 px-2.5 h-9 bg-background border border-border border-dashed rounded-sm cursor-pointer hover:bg-muted/50 transition-all group">
                                        <i data-lucide="cloud-upload" class="w-4 h-4 text-muted-foreground group-hover:text-foreground transition-colors flex-shrink-0"></i>
                                        <span class="text-xs text-muted-foreground group-hover:text-foreground truncate" x-text="fileName || 'Chọn...'"></span>
                                        <input type="file" name="site_logo" accept="image/*" class="hidden"
                                               @change="fileName = $event.target.files[0]?.name; previewUrl = URL.createObjectURL($event.target.files[0]); removeLogo = false">
                                    </label>
                                    <?php if($currentLogo): ?>
                                        <button type="button" @click="removeLogo = !removeLogo; previewUrl = removeLogo ? '' : '<?php echo e(asset('storage/' . $currentLogo)); ?>'"
                                                class="w-9 h-9 flex items-center justify-center rounded-sm transition-all border border-border"
                                                :class="removeLogo ? 'bg-red-500/10 text-red-600' : 'bg-background hover:bg-muted'>
                                            <i data-lucide="trash-2" class="w-3 h-3"></i>
                                        </button>
                                        <input type="hidden" name="remove_logo" :value="removeLogo ? 1 : 0">
                                    <?php endif; ?>
                                </div>
                                <template x-if="previewUrl">
                                    <div class="flex items-center gap-1.5 p-1.5 bg-muted/30 rounded border border-border text-[9px] text-muted-foreground">
                                        <img :src="previewUrl" class="h-6 w-6 object-contain rounded border border-border bg-background">
                                        <span>Xem trước</span>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <!-- Submit -->
                        <div class="flex justify-end pt-2 border-t border-border">
                            <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-primary hover:bg-primary/90 text-primary-foreground text-xs font-bold rounded-sm transition-all active:scale-95">
                                <i data-lucide="save" class="w-4 h-4"></i><?php echo e(__('Lưu')); ?>

                            </button>
                        </div>
                    </form>
                </div>

                <!-- Network Logos Section -->
                <div>
                    <div class="flex items-center justify-between mb-2.5">
                        <h3 class="text-xs font-bold text-foreground uppercase tracking-wide flex items-center gap-2">
                            <i data-lucide="network" class="w-4 h-4 text-muted-foreground"></i>
                            <?php echo e(__('Nhãn hiệu liên kết')); ?>

                        </h3>
                        <button type="button" 
                                @click="document.getElementById('networkLogoForm').style.display = document.getElementById('networkLogoForm').style.display === 'none' ? 'block' : 'none'"
                                class="inline-flex items-center gap-1.5 px-2.5 py-1.5 bg-primary hover:bg-primary/90 text-primary-foreground text-xs font-bold rounded-sm transition-all">
                            <i data-lucide="plus" class="w-3 h-3"></i><?php echo e(__('Thêm')); ?>

                        </button>
                    </div>

                    <!-- Add Form -->
                    <div id="networkLogoForm" style="display: none;" class="mb-2.5 p-2.5 bg-muted/20 border border-border border-dashed rounded space-y-2.5" x-data="{ fileName: '', previewUrl: '' }">
                        <form action="<?php echo e(route('admin.site-nodes.add-network-logo')); ?>" method="POST" enctype="multipart/form-data" class="space-y-2.5">
                            <?php echo csrf_field(); ?>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                                <div class="space-y-1.5">
                                    <label class="text-[9px] font-bold text-muted-foreground uppercase tracking-wide"><?php echo e(__('Tên')); ?> *</label>
                                    <input type="text" name="name" required placeholder="Tên thư viện" class="w-full h-9 bg-background border border-border rounded-sm px-2.5 text-xs text-foreground placeholder:text-muted-foreground focus:ring-1 focus:ring-primary outline-none transition-all">
                                </div>
                                <div class="space-y-1.5">
                                    <label class="text-[9px] font-bold text-muted-foreground uppercase tracking-wide"><?php echo e(__('URL')); ?> *</label>
                                    <input type="url" name="url" required placeholder="https://..." class="w-full h-9 bg-background border border-border rounded-sm px-2.5 text-xs text-foreground placeholder:text-muted-foreground focus:ring-1 focus:ring-primary outline-none transition-all">
                                </div>
                                <div class="space-y-1.5 sm:col-span-2">
                                    <label class="text-[9px] font-bold text-muted-foreground uppercase tracking-wide"><?php echo e(__('Logo')); ?> *</label>
                                    <label class="flex items-center gap-1.5 px-2.5 h-9 bg-background border border-border border-dashed rounded-sm cursor-pointer hover:bg-muted/50 transition-all group">
                                        <i data-lucide="cloud-upload" class="w-4 h-4 text-muted-foreground group-hover:text-foreground transition-colors flex-shrink-0"></i>
                                        <span class="text-xs text-muted-foreground group-hover:text-foreground truncate" x-text="fileName || 'Chọn...'"></span>
                                        <input type="file" name="logo_path" accept="image/*" required class="hidden" @change="fileName = $event.target.files[0]?.name; previewUrl = URL.createObjectURL($event.target.files[0])">
                                    </label>
                                    <template x-if="previewUrl">
                                        <div class="flex items-center gap-1.5 p-1.5 bg-muted/30 rounded border border-border text-[9px] text-muted-foreground">
                                            <img :src="previewUrl" class="h-6 w-6 object-contain rounded border border-border bg-background">
                                            <span>Xem trước</span>
                                        </div>
                                    </template>
                                </div>
                            </div>
                            <div class="flex justify-end gap-1.5 pt-2 border-t border-border">
                                <button type="button" @click="document.getElementById('networkLogoForm').style.display = 'none'" class="px-3 py-1.5 bg-muted hover:bg-muted/80 text-muted-foreground text-xs font-bold rounded-sm transition-all"><?php echo e(__('Hủy')); ?></button>
                                <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-primary hover:bg-primary/90 text-primary-foreground text-xs font-bold rounded-sm transition-all active:scale-95">
                                    <i data-lucide="plus" class="w-3 h-3"></i><?php echo e(__('Thêm')); ?>

                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- List -->
                    <?php $networkLogos = \App\Models\LibraryNetworkLogo::orderBy('sort_order')->get(); ?>
                    <?php if($networkLogos->count() > 0): ?>
                        <div class="space-y-0 border border-border rounded overflow-hidden">
                            <?php $__currentLoopData = $networkLogos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $logo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="flex items-center gap-2 p-2.5 bg-card border-b border-border last:border-b-0 hover:bg-muted/50 transition-all group">
                                    <?php if($logo->logo_path && file_exists(storage_path('app/public/' . $logo->logo_path))): ?>
                                        <img src="<?php echo e(asset('storage/' . $logo->logo_path)); ?>" alt="<?php echo e($logo->name); ?>" class="h-8 w-8 object-contain rounded-sm border border-border bg-background flex-shrink-0">
                                    <?php else: ?>
                                        <div class="h-8 w-8 rounded-sm bg-muted flex items-center justify-center flex-shrink-0"><i data-lucide="image" class="w-3 h-3 text-muted-foreground"></i></div>
                                    <?php endif; ?>
                                    <div class="flex-1 min-w-0">
                                        <h5 class="text-xs font-bold text-foreground truncate"><?php echo e($logo->name); ?></h5>
                                        <a href="<?php echo e($logo->url); ?>" target="_blank" class="text-[9px] text-blue-600 hover:underline truncate block"><?php echo e($logo->url); ?></a>
                                    </div>
                                    <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <button type="button" @click="editLogo(<?php echo e($logo->id); ?>, '<?php echo e($logo->name); ?>', '<?php echo e($logo->url); ?>', '<?php echo e(asset('storage/' . $logo->logo_path)); ?>')" class="p-1.5 rounded-sm bg-blue-500/10 hover:bg-blue-500 text-blue-600 hover:text-white transition-all border border-blue-500/20"><i data-lucide="edit-2" class="w-3 h-3"></i></button>
                                        <form action="<?php echo e(route('admin.site-nodes.delete-network-logo', $logo->id)); ?>" method="POST" class="inline" onsubmit="return confirm('<?php echo e(__('Xác nhận?')); ?>')">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="p-1.5 rounded-sm bg-red-500/10 hover:bg-red-500 text-red-600 hover:text-white transition-all border border-red-500/20"><i data-lucide="trash-2" class="w-3 h-3"></i></button>
                                        </form>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php else: ?>
                        <div class="flex flex-col items-center justify-center p-4 bg-muted/20 border border-border border-dashed rounded text-center">
                            <i data-lucide="image" class="w-5 h-5 text-muted-foreground mb-1 opacity-50"></i>
                            <p class="text-xs text-muted-foreground"><?php echo e(__('Chưa có')); ?></p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Structure Tab -->
            <div x-show="activeTab === 'structure'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                <!-- Stats -->
                <div class="grid grid-cols-3 gap-2 mb-3">
                    <div class="bg-muted/50 p-3 border border-border rounded">
                        <p class="text-[10px] text-muted-foreground font-bold uppercase tracking-wide mb-0.5">Tổng số</p>
                        <p class="text-lg font-black text-foreground"><?php echo e($stats['total'] ?? 0); ?></p>
                    </div>
                    <div class="bg-muted/50 p-3 border border-border rounded">
                        <p class="text-[10px] text-muted-foreground font-bold uppercase tracking-wide mb-0.5">Hoạt động</p>
                        <p class="text-lg font-black text-foreground"><?php echo e($stats['published'] ?? 0); ?></p>
                    </div>
                    <div class="bg-muted/50 p-3 border border-border rounded">
                        <p class="text-[10px] text-muted-foreground font-bold uppercase tracking-wide mb-0.5">Bản nháp</p>
                        <p class="text-lg font-black text-foreground"><?php echo e($stats['draft'] ?? 0); ?></p>
                    </div>
                </div>

                <!-- Tree Section Header -->
                <div class="flex items-center justify-between mb-2.5 pb-2.5 border-b border-border">
                    <h3 class="text-xs font-bold text-foreground uppercase tracking-wide flex items-center gap-2">
                        <i data-lucide="sitemap" class="w-4 h-4 text-muted-foreground"></i>
                        <?php echo e(__('Cấu trúc Website')); ?>

                    </h3>
                    <div class="flex items-center gap-1.5">
                        <button onclick="expandAll()" class="p-1.5 hover:bg-muted rounded-sm text-muted-foreground hover:text-foreground transition-all border border-border" title="Mở hết">
                            <i data-lucide="expand" class="w-3 h-3"></i>
                        </button>
                        <button onclick="collapseAll()" class="p-1.5 hover:bg-muted rounded-sm text-muted-foreground hover:text-foreground transition-all border border-border" title="Thu hết">
                            <i data-lucide="shrink" class="w-3 h-3"></i>
                        </button>
                        <a href="<?php echo e(route('admin.site-nodes.create')); ?>" class="inline-flex items-center gap-1 px-2.5 py-1.5 bg-primary hover:bg-primary/90 text-primary-foreground text-xs font-bold rounded-sm transition-all">
                            <i data-lucide="plus" class="w-3 h-3"></i><?php echo e(__('Thêm')); ?>

                        </a>
                    </div>
                </div>

                <?php if(count($tree) > 0): ?>
                    <div id="site-tree" class="space-y-0.5">
                        <?php echo $__env->make('admin.site-nodes.tree', ['nodes' => $tree, 'level' => 0], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    </div>
                <?php else: ?>
                    <div class="flex flex-col items-center justify-center py-8 text-center">
                        <i data-lucide="sitemap" class="w-6 h-6 text-muted-foreground mb-2 opacity-50"></i>
                        <p class="text-xs text-muted-foreground mb-2"><?php echo e(__('Chưa có')); ?></p>
                        <a href="<?php echo e(route('admin.site-nodes.create')); ?>" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-primary hover:bg-primary/90 text-primary-foreground text-xs font-bold rounded-sm">
                            <i data-lucide="plus" class="w-4 h-4"></i><?php echo e(__('Tạo')); ?>

                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div id="editLogoModal" style="display: none;" class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4 backdrop-blur-sm" @click.self="document.getElementById('editLogoModal').style.display = 'none'">
    <div class="bg-card rounded border border-border shadow-2xl max-w-sm w-full" x-data="{ editFileName: '', editPreviewUrl: '' }">
        <div class="flex items-center justify-between p-3 border-b border-border bg-muted/30">
            <h3 class="text-xs font-bold text-foreground uppercase tracking-wide"><?php echo e(__('Chỉnh sửa')); ?></h3>
            <button type="button" @click="document.getElementById('editLogoModal').style.display = 'none'" class="p-1 hover:bg-muted rounded transition-all">
                <i data-lucide="x" class="w-4 h-4 text-muted-foreground"></i>
            </button>
        </div>
        <form id="editLogoForm" method="POST" enctype="multipart/form-data" class="p-3 space-y-2.5">
            <?php echo csrf_field(); ?>
            <?php echo method_field('POST'); ?>
            <input type="hidden" id="logoId" name="logo_id">
            <div class="space-y-1.5">
                <label class="text-[9px] font-bold text-muted-foreground uppercase tracking-wide"><?php echo e(__('Tên')); ?> *</label>
                <input type="text" id="editName" name="name" required class="w-full h-9 bg-background border border-border rounded-sm px-2.5 text-xs text-foreground focus:ring-1 focus:ring-primary outline-none transition-all">
            </div>
            <div class="space-y-1.5">
                <label class="text-[9px] font-bold text-muted-foreground uppercase tracking-wide"><?php echo e(__('URL')); ?> *</label>
                <input type="url" id="editUrl" name="url" required class="w-full h-9 bg-background border border-border rounded-sm px-2.5 text-xs text-foreground focus:ring-1 focus:ring-primary outline-none transition-all">
            </div>
            <div class="space-y-1.5">
                <label class="text-[9px] font-bold text-muted-foreground uppercase tracking-wide"><?php echo e(__('Logo')); ?></label>
                <label class="flex items-center gap-1.5 px-2.5 h-9 bg-background border border-border border-dashed rounded-sm cursor-pointer hover:bg-muted/50 transition-all group">
                    <i data-lucide="cloud-upload" class="w-4 h-4 text-muted-foreground group-hover:text-foreground transition-colors flex-shrink-0"></i>
                    <span class="text-xs text-muted-foreground group-hover:text-foreground truncate" x-text="editFileName || 'Chọn...'"></span>
                    <input type="file" name="logo_path" accept="image/*" class="hidden" @change="editFileName = $event.target.files[0]?.name; editPreviewUrl = URL.createObjectURL($event.target.files[0])">
                </label>
            </div>
            <div id="currentLogoPreview" class="flex items-center gap-1.5 p-1.5 bg-muted/30 rounded border border-border text-[9px] text-muted-foreground">
                <img id="currentLogoImg" src="" alt="Logo" class="h-6 w-6 object-contain rounded border border-border bg-background">
                <span>Hiện tại</span>
            </div>
            <div id="newLogoPreview" style="display: none;" class="flex items-center gap-1.5 p-1.5 bg-muted/30 rounded border border-border text-[9px] text-muted-foreground" x-data>
                <img :src="editPreviewUrl" alt="New" class="h-6 w-6 object-contain rounded border border-border bg-background">
                <span>Mới</span>
            </div>
            <div class="flex justify-end gap-1.5 pt-2 border-t border-border">
                <button type="button" @click="document.getElementById('editLogoModal').style.display = 'none'" class="px-3 py-1.5 bg-muted hover:bg-muted/80 text-muted-foreground text-xs font-bold rounded-sm transition-all"><?php echo e(__('Hủy')); ?></button>
                <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-primary hover:bg-primary/90 text-primary-foreground text-xs font-bold rounded-sm transition-all active:scale-95">
                    <i data-lucide="save" class="w-3 h-3"></i><?php echo e(__('Lưu')); ?>

                </button>
            </div>
        </form>
    </div>
</div>

<?php $__env->startPush('styles'); ?>
<style>
    .tree-children { display: block; }
    .hidden { display: none; }
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
        const icon = toggle.querySelector('i');
        icon.style.transform = children.classList.contains('hidden') ? 'rotate(-90deg)' : 'rotate(0deg)';
    }
}
function expandAll() {
    document.querySelectorAll('.tree-children').forEach(c => c.classList.remove('hidden'));
    document.querySelectorAll('[id^="toggle-"] i').forEach(i => i.style.transform = 'rotate(0deg)');
}
function collapseAll() {
    document.querySelectorAll('.tree-children').forEach(c => c.classList.add('hidden'));
    document.querySelectorAll('[id^="toggle-"] i').forEach(i => i.style.transform = 'rotate(-90deg)');
}
function editLogo(logoId, logoName, logoUrl, logoPath) {
    document.getElementById('logoId').value = logoId;
    document.getElementById('editName').value = logoName;
    document.getElementById('editUrl').value = logoUrl;
    document.getElementById('currentLogoImg').src = logoPath;
    document.getElementById('editLogoForm').action = `/topsecret/site-nodes/network-logo/${logoId}`;
    document.querySelector('#editLogoForm input[name="logo_path"]').value = '';
    document.getElementById('newLogoPreview').style.display = 'none';
    document.getElementById('editLogoModal').style.display = 'flex';
}
document.addEventListener('DOMContentLoaded', function() {
    if (typeof lucide !== 'undefined') lucide.createIcons();
    const fileInput = document.querySelector('#editLogoForm input[name="logo_path"]');
    if (fileInput) {
        fileInput.addEventListener('change', function() {
            const preview = document.getElementById('newLogoPreview');
            const img = preview.querySelector('img');
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = e => { img.src = e.target.result; preview.style.display = 'flex'; };
                reader.readAsDataURL(this.files[0]);
            } else {
                preview.style.display = 'none';
            }
        });
    }
});
function toggleStatus(nodeId) {
    fetch(`/topsecret/site-nodes/${nodeId}/toggle-status`, {
        method: 'POST',
        headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')}
    })
    .then(r => r.json())
    .then(d => {
        if (d.success) {
            const badge = document.getElementById(`status-${nodeId}`);
            badge.className = `inline-flex items-center px-2 py-1 rounded-sm text-[9px] font-bold text-white ${d.is_active ? 'bg-green-600' : 'bg-slate-400'}`;
            badge.textContent = d.is_active ? 'Hoạt động' : 'Ẩn';
        }
    });
}
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\Workspace\VTTU\Laravel\VTTLib\resources\views/admin/site-nodes/index.blade.php ENDPATH**/ ?>