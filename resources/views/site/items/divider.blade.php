<!-- Divider -->
<section class="py-8">
    <div class="container mx-auto px-4">
        @php
            $style = $item->getDataAttribute('style', 'solid');
            $styles = [
                'solid' => 'border-t border-gray-300',
                'dashed' => 'border-t-2 border-dashed border-gray-300',
                'dotted' => 'border-t-2 border-dotted border-gray-300',
                'double' => 'border-t-4 border-double border-gray-300'
            ];
            $dividerClass = $styles[$style] ?? $styles['solid'];
        @endphp
        <div class="{{ $dividerClass }}"></div>
    </div>
</section>
