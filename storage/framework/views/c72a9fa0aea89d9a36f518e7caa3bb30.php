<nav class="flex mb-5" aria-label="Breadcrumb">
    <ol class="inline-flex items-center space-x-1 md:space-x-3">
        <?php $__currentLoopData = $segments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $segment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <li class="inline-flex items-center">
                <?php if(!$loop->first): ?>
                    <div class="flex items-center">
                        <svg class="w-3 h-3 text-slate-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                        </svg>
                    </div>
                <?php endif; ?>

                <?php if($segment['is_last']): ?>
                    <span class="ml-1 text-xs font-black text-indigo-600 dark:text-indigo-400 uppercase tracking-widest md:ml-2">
                        <?php echo e(__($segment['name'])); ?>

                    </span>
                <?php else: ?>
                    <a href="<?php echo e($segment['url']); ?>" class="inline-flex items-center text-xs font-bold text-slate-500 hover:text-indigo-600 dark:text-slate-400 dark:hover:text-indigo-300 uppercase tracking-widest transition-colors">
                        <?php if($loop->first): ?>
                            <svg class="w-3 h-3 mr-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                <path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z"/>
                            </svg>
                        <?php endif; ?>
                        <?php echo e(__($segment['name'])); ?>

                    </a>
                <?php endif; ?>
            </li>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </ol>
</nav><?php /**PATH E:\Workspace\VTTU\Laravel\VTTLib\resources\views/components/breadcrumb.blade.php ENDPATH**/ ?>