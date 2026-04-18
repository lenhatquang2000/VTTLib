<!-- Hero Section -->
<section class="bg-gradient-to-r from-blue-600 to-blue-800 text-white py-20">
    <div class="container mx-auto px-4">
        <div class="text-center">
            @if($item->getDataAttribute('title'))
                <h1 class="text-4xl md:text-5xl font-bold mb-4">{{ $item->getDataAttribute('title') }}</h1>
            @endif
            @if($item->getDataAttribute('subtitle'))
                <p class="text-xl md:text-2xl mb-8">{{ $item->getDataAttribute('subtitle') }}</p>
            @endif
            @if($item->getDataAttribute('button_text') && $item->getDataAttribute('button_url'))
                <a href="{{ $item->getDataAttribute('button_url') }}" class="bg-white text-blue-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition inline-block">
                    {{ $item->getDataAttribute('button_text') }}
                </a>
            @endif
        </div>
    </div>
</section>
