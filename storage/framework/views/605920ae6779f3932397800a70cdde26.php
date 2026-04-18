<div id="content_options" <?php echo e(old('route_name', $siteNode->route_name) || old('url', $siteNode->url) ? 'style="opacity: 0.5;"' : ''); ?>>
    
    <!-- Template Selection -->
    <div class="mb-6 p-4 bg-gray-800 border border-gray-700 rounded-lg">
        <label class="block text-sm font-medium mb-2 text-blue-400">Giao diện (Template)</label>
        <div class="flex gap-2">
            <select name="masterpage" id="masterpage_select" class="input-field flex-1 bg-gray-900 border-gray-600">
                <option value="">-- Mặc định (theo mã node) --</option>
                <?php
                    $templates = [
                        'home' => 'Trang chủ hiện đại (Waves)',
                        'about' => 'Trang giới thiệu (About)',
                        'contact' => 'Trang liên hệ (Contact)',
                        'services' => 'Trang dịch vụ (Services)',
                        'full-width' => 'Trang toàn màn hình (Full width)',
                    ];
                ?>
                <?php $__currentLoopData = $templates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($val); ?>" <?php echo e(old('masterpage', $siteNode->masterpage) == $val ? 'selected' : ''); ?>>
                        <?php echo e($label); ?>

                    </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            
            <a href="<?php echo e($siteNode->getUrl()); ?>" id="preview_template_btn" target="_blank" 
               class="btn-secondary flex items-center gap-2 whitespace-nowrap bg-blue-600/20 hover:bg-blue-600/30 text-blue-400 border-blue-600/50">
                <i class="fas fa-eye"></i>
                Xem trước
            </a>
        </div>
        <p class="text-xs text-gray-400 mt-2">Chọn file blade riêng để render giao diện này (trong thư mục site/pages/)</p>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const select = document.getElementById('masterpage_select');
            const btn = document.getElementById('preview_template_btn');
            const baseUrl = "<?php echo e($siteNode->getUrl()); ?>";

            function updatePreviewLink() {
                const val = select.value;
                if (val) {
                    const separator = baseUrl.includes('?') ? '&' : '?';
                    btn.href = baseUrl + separator + 'preview_template=' + val;
                    btn.classList.remove('opacity-50', 'pointer-events-none');
                } else {
                    btn.href = baseUrl;
                }
            }

            select.addEventListener('change', updatePreviewLink);
            updatePreviewLink(); // Initial call
        });
    </script>

    <label class="block text-sm font-medium mb-1">Cấu hình nội dung</label>

    <?php if($siteNode->display_type === 'page'): ?>
        <div class="border border-gray-600 rounded-lg overflow-hidden bg-gradient-to-r from-blue-900 to-purple-900">
            <div class="p-8 text-center">
                <i class="fas fa-layer-group text-6xl text-blue-400 mb-4"></i>
                <h3 class="text-xl font-bold text-white mb-2">Page Builder</h3>
                <p class="text-gray-300 mb-6">Kéo thả các block để xây dựng trang</p>

                <a href="<?php echo e(route('admin.site-nodes.page-builder', $siteNode)); ?>"
                   class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-external-link-alt mr-2"></i>
                    Mở Page Builder
                </a>

                <p class="text-xs text-gray-400 mt-4">
                    Hoặc click nút "Page Builder" ở trên để chuyển đến trang xây dựng nội dung
                </p>
            </div>
        </div>
    <?php else: ?>
        <div class="border border-gray-700 rounded-lg p-4 bg-gray-900 text-sm text-gray-300">
            Nội dung HTML cũ đã bị tắt. Vui lòng dùng Route hệ thống hoặc URL tùy chỉnh.
        </div>
    <?php endif; ?>

    <input type="hidden" name="items_data" id="items_data" value="">
</div>
<?php /**PATH E:\Workspace\VTTU\Laravel\VTTLib\resources\views/admin/site-nodes/partials/content-options.blade.php ENDPATH**/ ?>