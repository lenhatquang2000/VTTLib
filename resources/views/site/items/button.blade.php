<!-- Button Block -->
<section class="py-8">
    <div class="container mx-auto px-4">
        <div class="text-center">
            @if($item->getDataAttribute('text') && $item->getDataAttribute('url'))
                @php
                    $style = $item->getDataAttribute('style', 'primary');
                    $classes = [
                        'primary' => 'bg-vttu-red text-white hover:bg-vttu-dark',
                        'secondary' => 'bg-vttu-dark text-white hover:bg-black',
                        'success' => 'bg-emerald-600 text-white hover:bg-emerald-700',
                        'danger' => 'bg-red-600 text-white hover:bg-red-700',
                        'outline' => 'border-2 border-vttu-red text-vttu-red hover:bg-vttu-red hover:text-white'
                    ];
                    $buttonClass = $classes[$style] ?? $classes['primary'];
                @endphp
                <a href="{{ $item->getDataAttribute('url') }}" 
                   class="px-8 py-3 rounded-lg font-semibold transition inline-block {{ $buttonClass }}">
                    {{ $item->getDataAttribute('text') }}
                </a>
            @endif
        </div>
    </div>
</section>
