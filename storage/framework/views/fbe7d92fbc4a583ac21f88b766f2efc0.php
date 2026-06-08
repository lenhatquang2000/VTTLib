

<div class="space-y-6">
    
    <div class="space-y-3">
        
        <div class="bg-gradient-to-r from-vttu-red to-vttu-dark p-3 rounded-md shadow-sm">
            <h3 class="text-white font-black text-sm uppercase tracking-wide">
                Tiện ích thư viện
            </h3>
        </div>

        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
            <?php
                $images = [
                    'tienichthuvien_1.png',
                    'tienichthuvien_2.png',
                    'tienichthuvien_3.png',
                    'tienichthuvien_4.png',
                    'tienichthuvien_5.png',
                    'tienichthuvien_6.png',
                ];
            ?>
            
            <?php $__currentLoopData = $images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="relative group overflow-hidden rounded-md border border-border shadow-sm hover:shadow-md transition-all duration-200 hover:scale-105 aspect-square bg-card">
                    <img src="<?php echo e(asset('assets/info/' . $image)); ?>" 
                         alt="Tiện ích thư viện <?php echo e($index + 1); ?>" 
                         class="w-full h-full object-contain p-2">
                    <div class="absolute inset-0 bg-black/0 group-hover:bg-black/5 transition-all duration-200"></div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>

    
    <div class="space-y-3">
        
        <div class="bg-gradient-to-r from-vttu-red to-vttu-dark p-3 rounded-md shadow-sm">
            <h3 class="text-white font-black text-sm uppercase tracking-wide">
                Cơ sở vật chất
            </h3>
        </div>

        
        <div class="relative w-full aspect-video rounded-md overflow-hidden border border-border shadow-sm bg-card">
            <iframe 
                loading="lazy" 
                style="position: absolute; width: 100%; height: 100%; top: 0; left: 0; border: none; padding: 0; margin: 0;" 
                src="https://www.canva.com/design/DAF0Sc1gGIM/watch?embed" 
                allowfullscreen="allowfullscreen" 
                allow="fullscreen">
            </iframe>
        </div>
    </div>
</div>
<?php /**PATH E:\Workspace\VTTU\Laravel\VTTLib\resources\views/site/partials/library-benefits.blade.php ENDPATH**/ ?>