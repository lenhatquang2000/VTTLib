<!-- Hero Section -->
<section class="bg-white border-b border-slate-100 py-12">
    <div class="container mx-auto px-4">
        <div class="text-center">
            @if($item->getDataAttribute('title'))
                <h1 class="text-4xl md:text-5xl font-black mb-4 text-vttu-dark">{{ $item->getDataAttribute('title') }}</h1>
            @endif
            @if($item->getDataAttribute('subtitle'))
                <p class="text-xl md:text-2xl mb-8 text-vttu-red/80">{{ $item->getDataAttribute('subtitle') }}</p>
            @endif
            @if($item->getDataAttribute('button_text') && $item->getDataAttribute('button_url'))
                <a href="{{ $item->getDataAttribute('button_url') }}" class="bg-vttu-red text-white px-8 py-3 rounded-xl font-black hover:bg-vttu-dark transition inline-block uppercase tracking-wider text-sm shadow-lg shadow-vttu-red/20">
                    {{ $item->getDataAttribute('button_text') }}
                </a>
            @endif
        </div>
    </div>
</section>
