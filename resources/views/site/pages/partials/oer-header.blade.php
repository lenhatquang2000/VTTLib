<!-- Top Navigation Row -->
@php
    $currentView = request()->query('view', 'list');
    $isLanding = $currentView === 'landing';
    $isIntro = $currentView === 'intro';
    $isContribute = $currentView === 'contribute';
    $isList = $currentView === 'list' && !request()->routeIs('site.oer.*');
@endphp
<div class="flex flex-wrap items-center gap-x-6 gap-y-2 pb-3 border-b border-slate-200 text-xs font-black uppercase tracking-wider text-slate-700">
    <a href="{{ route('site.oer.landing') }}" class="flex items-center gap-1.5 {{ $isLanding ? 'text-vttu-red' : 'hover:text-vttu-red' }} transition-colors">
        <i class="fas fa-home {{ $isLanding ? 'text-vttu-red' : 'text-slate-400' }} text-[12px]"></i>
        <span>{{ __('Quay về OER') }}</span>
    </a>
    <a href="{{ route('site.oer.intro') }}" class="flex items-center gap-1.5 {{ $isIntro ? 'text-vttu-red' : 'hover:text-vttu-red' }} transition-colors">
        <i class="fas fa-info-circle {{ $isIntro ? 'text-vttu-red' : 'text-slate-400' }} text-[12px]"></i>
        <span>{{ __('Giới thiệu') }}</span>
    </a>
    <a href="{{ route('site.page', 'tai-nguyen-giao-duc-mo') }}" class="flex items-center gap-1.5 {{ $isList ? 'text-vttu-red' : 'hover:text-vttu-red' }} transition-colors">
        <i class="fas fa-database {{ $isList ? 'text-vttu-red' : 'text-slate-400' }} text-[12px]"></i>
        <span>{{ __('Kho tài liệu mở') }}</span>
    </a>
    <a href="{{ route('site.oer.contribute') }}" class="flex items-center gap-1.5 {{ $isContribute ? 'text-vttu-red' : 'hover:text-vttu-red' }} transition-colors">
        <i class="fas fa-share-alt {{ $isContribute ? 'text-vttu-red' : 'text-slate-400' }} text-[12px]"></i>
        <span>{{ __('Đóng góp tài liệu') }}</span>
    </a>
</div>
