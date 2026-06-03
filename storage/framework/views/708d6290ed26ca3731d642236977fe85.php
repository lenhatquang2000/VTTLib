<div class="space-y-6 animate-fade-in">
    <?php echo $__env->make('site.pages.partials.oer-header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <!-- Banner Section -->
    <div class="w-full bg-[#EAF9E7] p-4 rounded-t-sm shadow-sm relative overflow-hidden select-none border border-slate-100">
        <!-- Main Red Banner -->
        <div class="bg-[#7B0000] text-white py-6 md:py-8 px-4 md:px-12 flex items-center justify-between relative rounded-sm">
            <!-- Left Book Decoration -->
            <div class="hidden sm:block w-24 h-24 relative opacity-90">
                <svg viewBox="0 0 100 100" class="w-full h-full text-white fill-current">
                    <!-- Open Book -->
                    <path d="M50,85 C40,80 20,80 10,85 L10,25 C20,20 40,20 50,25 C60,20 80,20 90,25 L90,85 C80,80 60,80 50,85 Z" fill="none" stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M50,25 L50,85" stroke="currentColor" stroke-width="4" stroke-linecap="round"/>
                    <!-- Pages lines -->
                    <path d="M20,38 C28,35 38,35 45,38" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    <path d="M20,50 C28,47 38,47 45,50" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    <path d="M20,62 C28,59 38,59 45,62" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    <path d="M80,38 C72,35 62,35 55,38" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    <path d="M80,50 C72,47 62,47 55,50" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    <path d="M80,62 C72,59 62,59 55,62" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    <!-- Flowers/Leaves branches -->
                    <path d="M5,40 C2,30 8,15 15,20" stroke="currentColor" stroke-width="2" fill="none"/>
                    <circle cx="15" cy="20" r="3" fill="currentColor"/>
                    <circle cx="10" cy="12" r="2" fill="currentColor"/>
                    <path d="M95,40 C98,30 92,15 85,20" stroke="currentColor" stroke-width="2" fill="none"/>
                    <circle cx="85" cy="20" r="3" fill="currentColor"/>
                    <circle cx="90" cy="12" r="2" fill="currentColor"/>
                </svg>
            </div>

            <!-- Central Title Text -->
            <div class="flex-1 text-center">
                <h1 class="text-xl sm:text-2xl md:text-3xl lg:text-4xl font-black tracking-widest uppercase">
                    <?php echo e(__('Tài Nguyên Giáo Dục Mở')); ?>

                </h1>
            </div>

            <!-- Right Book Decoration -->
            <div class="hidden sm:block w-24 h-24 relative opacity-90">
                <svg viewBox="0 0 100 100" class="w-full h-full text-white fill-current">
                    <path d="M50,85 C40,80 20,80 10,85 L10,25 C20,20 40,20 50,25 C60,20 80,20 90,25 L90,85 C80,80 60,80 50,85 Z" fill="none" stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M50,25 L50,85" stroke="currentColor" stroke-width="4" stroke-linecap="round"/>
                    <path d="M20,38 C28,35 38,35 45,38" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    <path d="M20,50 C28,47 38,47 45,50" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    <path d="M20,62 C28,59 38,59 45,62" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    <path d="M80,38 C72,35 62,35 55,38" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    <path d="M80,50 C72,47 62,47 55,50" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    <path d="M80,62 C72,59 62,59 55,62" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    <path d="M5,40 C2,30 8,15 15,20" stroke="currentColor" stroke-width="2" fill="none"/>
                    <circle cx="15" cy="20" r="3" fill="currentColor"/>
                    <circle cx="10" cy="12" r="2" fill="currentColor"/>
                    <path d="M95,40 C98,30 92,15 85,20" stroke="currentColor" stroke-width="2" fill="none"/>
                    <circle cx="85" cy="20" r="3" fill="currentColor"/>
                    <circle cx="90" cy="12" r="2" fill="currentColor"/>
                </svg>
            </div>
        </div>

        <!-- Bottom Slider Controls Stripe -->
        <div class="w-full bg-[#8EAA90] h-6 mt-1 flex items-center justify-end px-3 gap-2 rounded-b-sm">
            <span class="text-white text-[10px] font-black cursor-pointer select-none">•••</span>
            <i class="fas fa-expand text-white text-[8px] cursor-pointer"></i>
        </div>
    </div>

    <!-- Two Main Columns Section -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4">
        <!-- Left Column: GIỚI THIỆU -->
        <div class="flex flex-col h-full bg-white rounded-sm overflow-hidden border border-slate-200 shadow-sm hover:shadow-md transition-shadow">
            <!-- Image Area -->
            <div class="aspect-video w-full overflow-hidden bg-slate-100 relative">
                <img src="https://images.unsplash.com/photo-1516321318423-f06f85e504b3?w=600&auto=format&fit=crop&q=80" 
                     class="w-full h-full object-cover" 
                     alt="Giới thiệu OER">
                <div class="absolute inset-0 bg-slate-900/5"></div>
            </div>
            <!-- Large Red Full Width Button -->
            <a href="<?php echo e(route('site.oer.intro')); ?>" class="w-full bg-[#7B0000] hover:bg-[#5A0000] text-white py-4 text-center font-black uppercase tracking-widest text-sm transition-colors cursor-pointer select-none shadow-sm">
                <?php echo e(__('Giới thiệu')); ?>

            </a>
        </div>

        <!-- Right Column: KHO TÀI LIỆU -->
        <div class="flex flex-col h-full bg-white rounded-sm overflow-hidden border border-slate-200 shadow-sm hover:shadow-md transition-shadow">
            <!-- Image Area -->
            <div class="aspect-video w-full overflow-hidden bg-slate-100 relative">
                <img src="https://images.unsplash.com/photo-1544197150-b99a580bb7a8?w=600&auto=format&fit=crop&q=80" 
                     class="w-full h-full object-cover" 
                     alt="Kho tài liệu mở OER">
                <div class="absolute inset-0 bg-slate-900/5"></div>
            </div>
            <!-- Large Red Full Width Button -->
            <a href="<?php echo e(route('site.page', 'tai-nguyen-giao-duc-mo')); ?>" class="w-full bg-[#7B0000] hover:bg-[#5A0000] text-white py-4 text-center font-black uppercase tracking-widest text-sm transition-colors cursor-pointer select-none shadow-sm">
                <?php echo e(__('Kho tài liệu')); ?>

            </a>
        </div>
    </div>
</div>
<?php /**PATH E:\Workspace\VTTU\Laravel\VTTLib\resources\views/site/pages/partials/oer-landing-content.blade.php ENDPATH**/ ?>