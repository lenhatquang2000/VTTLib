<!-- Text Block -->
<section class="py-16">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto">
            @if($item->getDataAttribute('title'))
                <h2 class="text-3xl font-bold text-center mb-8">{{ $item->getDataAttribute('title') }}</h2>
            @endif
            @if($item->getDataAttribute('content'))
                <div class="prose prose-lg max-w-none">
                    {!! $item->getDataAttribute('content') !!}
                </div>
            @endif
        </div>
    </div>
</section>
