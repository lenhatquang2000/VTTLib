<div class="space-y-6 animate-fade-in">
    <?php echo $__env->make('site.pages.partials.oer-header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <!-- Welcome Message Section -->
    <div class="bg-[#FFE4E6] p-6 rounded-sm border border-[#FFD1D5] text-[13px] text-slate-800 leading-relaxed space-y-4">
        <p class="font-bold"><?php echo e(__('Kính chào Quý Bạn đọc,')); ?></p>
        <p>
            <?php echo e(__('Thư viện Trường Đại học Võ Trường Toản xin chân thành cảm ơn sự quan tâm và hỗ trợ quý báu từ Quý bạn đọc trong việc phát triển Tài nguyên Giáo dục Mở (OER). Chúng tôi rất mong nhận được sự đóng góp tài liệu từ Quý bạn đọc để cùng nhau xây dựng một kho tàng tri thức mở rộng và phong phú, phục vụ cho nhu cầu học tập và nghiên cứu của cộng đồng.')); ?>

        </p>
        <p>
            <?php echo e(__('Nếu Quý bạn đọc có nhu cầu đóng góp tài liệu, xin vui lòng liên hệ với chúng tôi qua địa chỉ email: ')); ?>

            <a href="mailto:mailthuvien@vttu.edu.vn" class="text-blue-600 font-bold hover:underline">mailthuvien@vttu.edu.vn</a>
            <?php echo e(__(' hoặc nhập trực tiếp thông qua biểu mẫu bên dưới. Chúng tôi cam kết rằng mọi đóng góp của Quý bạn đọc sẽ được ghi nhận và trân trọng, đồng thời sẽ là nguồn động viên lớn lao cho sự phát triển toàn diện của thư viện.')); ?>

        </p>
        <p class="font-bold"><?php echo e(__('Cảm ơn sự quan tâm và hỗ trợ của quý bạn đọc!')); ?></p>
    </div>

    <!-- Success/Error Messages -->
    <?php if(session('success')): ?>
        <div class="bg-green-100 border border-green-200 text-green-700 px-4 py-3 rounded-sm text-sm">
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>
    <?php if(session('error')): ?>
        <div class="bg-red-100 border border-red-200 text-red-700 px-4 py-3 rounded-sm text-sm">
            <?php echo e(session('error')); ?>

        </div>
    <?php endif; ?>

    <!-- Contribution Form -->
    <div class="bg-white p-8 md:p-12">
        <form action="<?php echo e(route('site.oer.contribute.store')); ?>" method="POST" enctype="multipart/form-data" class="max-w-4xl mx-auto space-y-8">
            <?php echo csrf_field(); ?>
            
            <!-- Họ và tên -->
            <div class="space-y-2">
                <label class="block text-sm font-bold text-[#7B0000]"><?php echo e(__('Họ và tên:')); ?></label>
                <input type="text" name="full_name" required
                       class="w-full px-4 py-2.5 border border-slate-200 rounded-sm text-sm focus:border-[#7B0000] outline-none transition-all placeholder:text-slate-400"
                       placeholder="<?php echo e(__('Vui lòng cho biết tên đầy đủ')); ?>">
                <?php $__errorArgs = ['full_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <!-- Email/SĐT -->
            <div class="space-y-2">
                <label class="block text-sm font-bold text-[#7B0000]"><?php echo e(__('Email/Số điện thoại liên hệ:')); ?></label>
                <input type="text" name="contact_info" required
                       class="w-full px-4 py-2.5 border border-slate-200 rounded-sm text-sm focus:border-[#7B0000] outline-none transition-all placeholder:text-slate-400"
                       placeholder="<?php echo e(__('Địa chỉ mail hoặc số điện thoại của bạn')); ?>">
                <?php $__errorArgs = ['contact_info'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <!-- Giấy phép -->
            <div class="space-y-2">
                <label class="block text-sm font-bold text-[#7B0000]">
                    <?php echo e(__('Giấy phép: Tìm hiểu về giấy phép truy cập mở')); ?>

                    <a href="<?php echo e(route('site.oer.intro')); ?>#license" class="text-blue-600 hover:underline font-normal"><?php echo e(__('tại đây')); ?></a>
                </label>
                <select name="license" required
                        class="w-full px-4 py-2.5 border border-slate-200 rounded-sm text-sm focus:border-[#7B0000] outline-none transition-all appearance-none bg-no-repeat bg-[right_1rem_center] bg-[length:1em_1em]"
                        style="background-image: url('data:image/svg+xml;charset=utf-8,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' fill=\'none\' viewBox=\'0 0 20 20\'%3E%3Cpath stroke=\'%236b7280\' stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'1.5\' d=\'M6 8l4 4 4-4\'/%3E%3C/svg%3E');">
                    <option value=""><?php echo e(__('-Chọn giấy phép-')); ?></option>
                    <option value="CC BY">CC BY</option>
                    <option value="CC BY-SA">CC BY-SA</option>
                    <option value="CC BY-NC">CC BY-NC</option>
                    <option value="CC BY-NC-SA">CC BY-NC-SA</option>
                    <option value="CC BY-ND">CC BY-ND</option>
                    <option value="CC BY-NC-ND">CC BY-NC-ND</option>
                </select>
                <?php $__errorArgs = ['license'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <!-- Thông tin thêm -->
            <div class="space-y-2">
                <label class="block text-sm font-bold text-[#7B0000]"><?php echo e(__('Thông tin thêm:')); ?></label>
                <input type="text" name="additional_info"
                       class="w-full px-4 py-2.5 border border-slate-200 rounded-sm text-sm focus:border-[#7B0000] outline-none transition-all placeholder:text-slate-400"
                       placeholder="<?php echo e(__('Còn gì chúng tôi nên biết nữa không')); ?>">
                <?php $__errorArgs = ['additional_info'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <!-- Đính kèm file -->
            <div class="flex items-center gap-2 pt-2">
                <label class="text-sm font-bold text-[#7B0000] whitespace-nowrap"><?php echo e(__('Đính kèm file:')); ?></label>
                <input type="file" name="oer_file" required
                       class="text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-sm file:border-0 file:text-xs file:font-semibold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200 transition-all">
                <?php $__errorArgs = ['oer_file'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <!-- Submit Button -->
            <div class="pt-6">
                <button type="submit" 
                        class="w-full bg-[#7B0000] hover:bg-[#5A0000] text-white py-4 text-center font-black uppercase tracking-widest text-sm transition-colors shadow-md rounded-sm">
                    <?php echo e(__('GỬI TÀI LIỆU')); ?>

                </button>
            </div>
        </form>
    </div>
</div>
<?php /**PATH E:\Workspace\VTTU\Laravel\VTTLib\resources\views/site/pages/partials/oer-contribute-content.blade.php ENDPATH**/ ?>