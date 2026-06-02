<div id="news-container" class="space-y-6 animate-in fade-in duration-500">
    <?php if(($newsType ?? 'news') === 'video'): ?>
        <?php if(isset($tabNews) && count($tabNews) > 0): ?>
            <div class="space-y-3">
                <?php $__currentLoopData = $tabNews; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="flex gap-3 items-center group cursor-pointer">
                        <div class="w-32 h-20 flex-shrink-0 bg-slate-900 rounded-lg relative overflow-hidden shadow-sm">
                            <?php if($item->video_url): ?>
                                <iframe src="<?php echo e($item->video_url); ?>" class="w-full h-full" frameborder="0" allowfullscreen allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"></iframe>
                            <?php else: ?>
                                <div class="absolute inset-0 flex items-center justify-center z-10">
                                    <div class="w-8 h-8 bg-white/20 backdrop-blur-md rounded-full flex items-center justify-center text-white group-hover:scale-110 transition-transform border border-white/30">
                                        <i class="fas fa-play text-xs"></i>
                                    </div>
                                </div>
                                <?php if($item->featured_image): ?>
                                    <img src="<?php echo e($item->featured_image); ?>" class="w-full h-full object-cover opacity-60">
                                <?php else: ?>
                                    <img src="https://images.unsplash.com/photo-1516321318423-f06f85e504b3?auto=format&fit=crop&w=400&q=80" class="w-full h-full object-cover opacity-60">
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                        <div class="flex-1 min-w-0">
                            <a href="<?php echo e($item->url); ?>" class="text-xs font-bold text-vttu-dark leading-snug line-clamp-2 group-hover:text-vttu-red transition-colors"><?php echo e($item->title); ?></a>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php else: ?>
            <div class="aspect-video bg-slate-900 rounded-2xl relative overflow-hidden group cursor-pointer shadow-lg">
                <div class="absolute inset-0 flex items-center justify-center z-10">
                    <div class="w-12 h-12 bg-white/20 backdrop-blur-md rounded-full flex items-center justify-center text-white group-hover:scale-125 transition-transform border border-white/30">
                        <i class="fas fa-play text-xs"></i>
                    </div>
                </div>
                <img src="https://images.unsplash.com/photo-1516321318423-f06f85e504b3?auto=format&fit=crop&w=400&q=80" class="w-full h-full object-cover opacity-60">
            </div>
            <p class="text-sm font-bold text-vttu-dark mt-4 leading-snug">Chưa có video nào</p>
        <?php endif; ?>
    <?php else: ?>
        <?php if(isset($tabNews) && count($tabNews) > 0): ?>
            <?php 
                $tabNewsOrdered = collect($tabNews)->sortBy('sort_order');
            ?>
            <?php $__currentLoopData = $tabNewsOrdered; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="flex gap-6 items-center group">
                    <div class="w-24 h-24 bg-slate-100 rounded-2xl flex-shrink-0 overflow-hidden">
                        <img src="<?php echo e($item->featured_image ?? 'https://images.unsplash.com/photo-1497633762265-9d179a990aa6?auto=format&fit=crop&w=200&q=80'); ?>" 
                             class="w-full h-full object-cover group-hover:scale-110 transition-transform">
                    </div>
                    <div>
                        <a href="<?php echo e($item->url); ?>" class="font-black text-vttu-dark group-hover:text-vttu-red transition-colors line-clamp-1">
                            <?php echo e($item->title); ?>

                        </a>
                        <p class="text-xs text-slate-500 font-bold mt-1">
                            <?php echo e($item->published_at ? $item->published_at->format('d/m/Y') : $item->created_at->format('d/m/Y')); ?>

                        </p>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php else: ?>
            <?php for($i=1; $i<=3; $i++): ?>
                <div class="flex gap-6 items-center group">
                    <div class="w-24 h-24 bg-slate-100 rounded-2xl flex-shrink-0 overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1497633762265-9d179a990aa6?auto=format&fit=crop&w=200&q=80" class="w-full h-full object-cover group-hover:scale-110 transition-transform">
                    </div>
                    <div>
                        <h4 class="font-black text-vttu-dark group-hover:text-vttu-red transition-colors line-clamp-1">Tin tức học thuật và nghiên cứu số <?php echo e($i); ?></h4>
                        <p class="text-xs text-slate-500 font-bold mt-1">20/04/2026</p>
                    </div>
                </div>
            <?php endfor; ?>
        <?php endif; ?>
    <?php endif; ?>
</div>
<?php /**PATH E:\Workspace\VTTU\Laravel\VTTLib\resources\views/site/pages/partials/home-news.blade.php ENDPATH**/ ?>