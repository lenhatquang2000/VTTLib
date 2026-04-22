<div id="content_options" <?php echo e(old('route_name', $siteNode->route_name) || old('url', $siteNode->url) ? 'style="opacity: 0.5;"' : ''); ?>>
    
    <!-- Template Selection -->
    <div class="mb-6 p-4 bg-gray-800 border border-gray-700 rounded-lg">
        <label class="block text-sm font-medium mb-2 text-blue-400"><?php echo e(__('Theme (Template)')); ?></label>
        <div class="flex gap-2">
            <select name="masterpage" id="masterpage_select" class="input-field flex-1 bg-gray-900 border-gray-600">
                <option value="">-- <?php echo e(__('Default (by node code)')); ?> --</option>
                <?php
                    $templates = \App\Models\SiteTemplate::where('is_active', true)->orderBy('sort_order')->get();
                ?>
                <?php $__currentLoopData = $templates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tpl): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($tpl->template_code); ?>" <?php echo e(old('masterpage', $siteNode->masterpage) == $tpl->template_code ? 'selected' : ''); ?>>
                        <?php echo e($tpl->template_name); ?>

                    </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            
            <a href="<?php echo e($siteNode->getUrl()); ?>" id="preview_template_btn" target="_blank" 
               class="btn-secondary flex items-center gap-2 whitespace-nowrap bg-blue-600/20 hover:bg-blue-600/30 text-blue-400 border-blue-600/50">
                <i class="fas fa-eye"></i>
                <?php echo e(__('Preview')); ?>

            </a>
        </div>
        <p class="text-xs text-gray-400 mt-2"><?php echo e(__('Choose a separate blade file to render this UI (in site/pages/ folder)')); ?></p>
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

    <label class="block text-sm font-medium mb-1"><?php echo e(__('Content Configuration')); ?></label>

    <?php if($siteNode->display_type === 'page'): ?>
        <div class="border border-gray-600 rounded-lg overflow-hidden bg-gradient-to-r from-blue-900 to-purple-900">
            <div class="p-8 text-center">
                <i class="fas fa-layer-group text-6xl text-blue-400 mb-4"></i>
                <h3 class="text-xl font-bold text-white mb-2"><?php echo e(__('Page Builder')); ?></h3>
                <p class="text-gray-300 mb-6"><?php echo e(__('Drag and drop blocks to build your page')); ?></p>

                <a href="<?php echo e(route('admin.site-nodes.page-builder', $siteNode)); ?>"
                   class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-external-link-alt mr-2"></i>
                    <?php echo e(__('Open Page Builder')); ?>

                </a>

                <p class="text-xs text-gray-400 mt-4">
                    <?php echo e(__('Or click "Page Builder" button above to go to the content builder page')); ?>

                </p>
            </div>
        </div>
    <?php else: ?>
        <div class="border border-gray-700 rounded-lg p-4 bg-gray-900 text-sm text-gray-300">
            <?php echo e(__('Legacy HTML content has been disabled. Please use System Route or Custom URL.')); ?>

        </div>
    <?php endif; ?>

    <input type="hidden" name="items_data" id="items_data" value="">
</div>
<?php /**PATH E:\Workspace\VTTU\Laravel\VTTLib\resources\views/admin/site-nodes/partials/content-options.blade.php ENDPATH**/ ?>