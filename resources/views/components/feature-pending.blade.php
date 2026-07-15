@props([
    'title' => __('Chức năng chưa cập nhật'),
    'desc' => __('Tính năng này hiện đang trong quá trình phát triển và sẽ sớm được cập nhật trong phiên bản tiếp theo.'),
    'icon' => 'construction',
    'class' => ''
])

<div class="flex flex-col items-center justify-center py-16 px-4 bg-card border border-border rounded-md text-center {{ $class }}">
    <!-- Premium Animated Icon Box -->
    <div class="relative w-14 h-14 rounded-full bg-primary/5 flex items-center justify-center text-primary mb-4 border border-primary/10 shadow-inner">
        <!-- Ping effect -->
        <span class="absolute inline-flex h-full w-full rounded-full bg-primary/5 animate-ping opacity-75"></span>
        <i data-lucide="{{ $icon }}" class="w-6 h-6 text-primary relative z-10"></i>
    </div>
    
    <!-- Text Information -->
    <h3 class="text-sm font-bold text-foreground mb-1.5 uppercase tracking-wide">{{ $title }}</h3>
    <p class="text-xs text-muted-foreground max-w-sm leading-relaxed mb-6">{{ $desc }}</p>
    
    <!-- Micro-loader to look dynamic and premium -->
    <div class="flex items-center gap-1.5 justify-center">
        <span class="w-1.5 h-1.5 rounded-full bg-primary animate-bounce" style="animation-delay: 0ms"></span>
        <span class="w-1.5 h-1.5 rounded-full bg-primary animate-bounce" style="animation-delay: 150ms"></span>
        <span class="w-1.5 h-1.5 rounded-full bg-primary animate-bounce" style="animation-delay: 300ms"></span>
    </div>
</div>
