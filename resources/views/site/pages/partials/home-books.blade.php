<div id="books-container" class="grid grid-cols-2 md:grid-cols-4 gap-4 animate-in fade-in duration-500">
    @foreach($newBooks as $book)
    @php
        $f245 = $book->fields->where('tag', '245')->first();
        $title = $f245 ? $f245->subfields->where('code', 'a')->first()?->value : 'Không có nhan đề';
    @endphp
    <div class="group cursor-pointer">
        <div class="aspect-[3/4] bg-slate-100 rounded-2xl overflow-hidden mb-4 relative shadow-sm group-hover:shadow-xl transition-all">
            @if($book->cover_image)
                <img src="{{ asset('storage/' . $book->cover_image) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
            @else
                <div class="w-full h-full flex items-center justify-center bg-vttu-red/5">
                    <i class="fas fa-book text-vttu-red/20 text-4xl"></i>
                </div>
            @endif
            <div class="absolute inset-0 bg-vttu-red/10 opacity-0 group-hover:opacity-100 transition-opacity"></div>
        </div>
        <h4 class="font-black text-vttu-dark text-sm line-clamp-2 group-hover:text-vttu-red transition-colors">{{ $title }}</h4>
    </div>
    @endforeach
    @if($newBooks->isEmpty())
        <div class="col-span-4 py-12 text-center text-slate-400 font-medium">
            {{ __('Hiện chưa có dữ liệu trong mục này.') }}
        </div>
    @endif
</div>
