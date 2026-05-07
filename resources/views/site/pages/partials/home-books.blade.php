<div id="books-container" class="grid gap-6" style="grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));">
    @forelse($newBooks as $book)
    @php
        // Helper để lấy nội dung từ MARC fields
        $title = $book->fields->where('tag', '245')->first()?->subfields->where('code', 'a')->first()?->value ?? 'Không có nhan đề';
        $author = $book->fields->where('tag', '100')->first()?->subfields->where('code', 'a')->first()?->value 
                ?? $book->fields->where('tag', '700')->first()?->subfields->where('code', 'a')->first()?->value 
                ?? 'Đang cập nhật tác giả';
    @endphp
    <div class="bg-white p-4 rounded-2xl border border-slate-100 hover:border-vttu-red/20 transition-all group flex flex-col shadow-sm hover:shadow-xl hover:-translate-y-1">
        <!-- Book Cover -->
        <a href="{{ route('opac.book.show', $book->id) }}" class="aspect-[3/4] bg-slate-100 rounded-xl mb-4 flex items-center justify-center border border-slate-50 group-hover:bg-vttu-red/5 transition-colors overflow-hidden relative">
            @if($book->cover_image)
                <img src="{{ asset('storage/' . $book->cover_image) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
            @else
                <i class="fas fa-book-open text-slate-300 group-hover:text-vttu-red text-4xl transition-colors"></i>
            @endif
            <div class="absolute top-2 right-2">
                <span class="px-2 py-0.5 bg-white/90 backdrop-blur text-vttu-dark rounded-lg text-[8px] font-black uppercase tracking-widest shadow-sm">{{ $book->record_type ?? 'Sách' }}</span>
            </div>
        </a>

        <!-- Book Info -->
        <div class="flex-grow flex flex-col">
            <a href="{{ route('opac.book.show', $book->id) }}">
                <h3 class="text-sm font-black text-vttu-dark group-hover:text-vttu-red transition-colors leading-tight line-clamp-2 min-h-[2.5rem]">
                    {{ $title }}
                </h3>
            </a>
            <p class="text-[11px] font-bold text-slate-500 mt-2 flex items-center gap-1.5 truncate">
                <i class="fas fa-user-edit text-[9px] text-vttu-red"></i>
                {{ $author }}
            </p>
            
            <div class="mt-auto pt-4 flex items-center justify-between border-t border-slate-50">
                @if($book->items->where('status', 'available')->count() > 0)
                    <span class="text-[9px] font-black text-emerald-600 bg-emerald-50 px-2 py-1 rounded-lg uppercase tracking-tighter">Sẵn sàng</span>
                @else
                    <span class="text-[9px] font-black text-rose-500 bg-rose-50 px-2 py-1 rounded-lg uppercase tracking-tighter">Hết sách</span>
                @endif
                <a href="{{ route('opac.book.show', $book->id) }}" class="w-8 h-8 rounded-full bg-slate-50 flex items-center justify-center text-vttu-red hover:bg-vttu-red hover:text-white transition-all shadow-sm">
                    <i class="fas fa-arrow-right text-[10px]"></i>
                </a>
            </div>
        </div>
    </div>
    @empty
    <div class="col-span-full py-12 text-center">
        <p class="text-slate-400 font-bold italic">Không có tài liệu nào trong chuyên mục này.</p>
    </div>
    @endforelse
</div>
