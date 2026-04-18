<!-- Button Block -->
<section class="py-8">
    <div class="container mx-auto px-4">
        <div class="text-center">
            @if($item->getDataAttribute('text') && $item->getDataAttribute('url'))
                @php
                    $style = $item->getDataAttribute('style', 'primary');
                    $classes = [
                        'primary' => 'bg-blue-600 text-white hover:bg-blue-700',
                        'secondary' => 'bg-gray-600 text-white hover:bg-gray-700',
                        'success' => 'bg-green-600 text-white hover:bg-green-700',
                        'danger' => 'bg-red-600 text-white hover:bg-red-700',
                        'outline' => 'border-2 border-blue-600 text-blue-600 hover:bg-blue-600 hover:text-white'
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
