<!-- Image Block -->
<section class="py-16">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto">
            @if($item->getDataAttribute('title'))
                <h2 class="text-3xl font-bold text-center mb-8">{{ $item->getDataAttribute('title') }}</h2>
            @endif
            <div class="text-center">
                @if($item->getDataAttribute('url'))
                    <img src="{{ $item->getDataAttribute('url') }}" 
                         alt="{{ $item->getDataAttribute('alt', 'Image') }}" 
                         class="rounded-lg shadow-lg mx-auto max-w-full h-auto">
                @endif
                @if($item->getDataAttribute('caption'))
                    <p class="text-gray-600 mt-4 text-sm">{{ $item->getDataAttribute('caption') }}</p>
                @endif
            </div>
        </div>
    </div>
</section>
