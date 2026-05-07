<div id="news-container" class="space-y-6 animate-in fade-in duration-500">
    <?php if(($newsType ?? 'news') === 'video'): ?>
        <div class="aspect-video bg-slate-900 rounded-2xl relative overflow-hidden group cursor-pointer shadow-lg">
            <div class="absolute inset-0 flex items-center justify-center z-10">
                <div class="w-12 h-12 bg-white/20 backdrop-blur-md rounded-full flex items-center justify-center text-white group-hover:scale-125 transition-transform border border-white/30">
                    <i class="fas fa-play text-xs"></i>
                </div>
            </div>
            <img src="https://images.unsplash.com/photo-1516321318423-f06f85e504b3?auto=format&fit=crop&w=400&q=80" class="w-full h-full object-cover opacity-60">
        </div>
        <p class="text-sm font-bold text-vttu-dark mt-4 leading-snug">Hướng dẫn đăng ký và sử dụng tài khoản thư viện số VTTU</p>
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
</div>
<?php /**PATH E:\Workspace\VTTU\Laravel\VTTLib\resources\views/site/pages/partials/home-news.blade.php ENDPATH**/ ?>