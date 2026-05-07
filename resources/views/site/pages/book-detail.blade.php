@extends('layouts.site')

@section('title', $fullTitle . ' - Chi tiết tài liệu - VTTLib')

@section('content')
<div class="bg-slate-50 min-h-screen pt-24 pb-12">
    <div class="max-w-9xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Breadcrumb -->
        <nav class="flex mb-8 text-sm font-medium" aria-label="Breadcrumb">
            <ol class="flex items-center space-x-2">
                <li><a href="{{ route('home') }}" class="text-slate-400 hover:text-vttu-red transition-colors">Trang chủ</a></li>
                <li><i class="fas fa-chevron-right text-[10px] text-slate-300"></i></li>
                <li><a href="{{ route('opac.search') }}" class="text-slate-400 hover:text-vttu-red transition-colors">Tra cứu OPAC</a></li>
                <li><i class="fas fa-chevron-right text-[10px] text-slate-300"></i></li>
                <li class="text-vttu-dark truncate max-w-[200px] md:max-w-md">{{ $fullTitle }}</li>
            </ol>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            
            <!-- LEFT: Book Cover & Quick Actions -->
            <div class="lg:col-span-4 space-y-6">
                <div class="bg-white rounded-[2.5rem] p-8 shadow-sm border border-slate-100 flex flex-col items-center sticky top-28">
                    <div class="w-full aspect-[3/4] bg-slate-50 rounded-3xl overflow-hidden shadow-2xl shadow-slate-200 mb-8 border border-slate-100 relative group">
                        @if($record->cover_image)
                            <img src="{{ asset('storage/' . $record->cover_image) }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex flex-col items-center justify-center text-slate-200">
                                <i class="fas fa-book-open text-8xl"></i>
                            </div>
                        @endif
                        <div class="absolute top-4 right-4">
                            <span class="px-4 py-1.5 bg-vttu-red text-white rounded-full text-[10px] font-black uppercase tracking-widest shadow-lg">{{ $record->record_type ?? 'Sách' }}</span>
                        </div>
                    </div>

                    <div class="w-full space-y-3">
                        @if($record->items->where('status', 'available')->count() > 0)
                            <button type="button" onclick="confirmReservation({{ $record->id }}, '{{ addslashes($fullTitle) }}')" class="w-full py-4 bg-vttu-red text-white rounded-2xl font-black uppercase text-xs tracking-[0.2em] hover:bg-vttu-dark transition-all shadow-xl shadow-vttu-red/20 flex items-center justify-center gap-3">
                                <i class="fas fa-shopping-basket"></i>
                                Đăng ký mượn ngay
                            </button>
                        @else
                            <button disabled class="w-full py-4 bg-slate-200 text-slate-400 rounded-2xl font-black uppercase text-xs tracking-[0.2em] cursor-not-allowed flex items-center justify-center gap-3">
                                <i class="fas fa-clock"></i>
                                Tài liệu tạm hết
                            </button>
                        @endif
                        
                        <button class="w-full py-4 bg-white text-vttu-dark border-2 border-slate-100 rounded-2xl font-black uppercase text-xs tracking-[0.2em] hover:border-vttu-red/20 hover:text-vttu-red transition-all flex items-center justify-center gap-3">
                            <i class="far fa-heart"></i>
                            Thêm vào yêu thích
                        </button>
                    </div>

                    <!-- Additional Metadata -->
                    <div class="w-full mt-8 pt-8 border-t border-slate-50 grid grid-cols-2 gap-4 text-center">
                        <div>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Lượt xem</p>
                            <p class="text-lg font-black text-vttu-dark">1,204</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Lượt mượn</p>
                            <p class="text-lg font-black text-vttu-dark">45</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- RIGHT: Book Information -->
            <div class="lg:col-span-8 space-y-8">
                
                <!-- Main Info Section -->
                <div class="bg-white rounded-[2.5rem] p-10 shadow-sm border border-slate-100">
                    <div class="mb-8 pb-8 border-b border-slate-50">
                        <h1 class="text-3xl md:text-4xl font-black text-vttu-dark tracking-tight leading-tight mb-4">
                            {{ $fullTitle }}
                        </h1>
                        <div class="flex flex-wrap items-center gap-6">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-full bg-vttu-red/10 flex items-center justify-center text-vttu-red">
                                    <i class="fas fa-user-edit text-[10px]"></i>
                                </div>
                                <div>
                                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">Tác giả</p>
                                    <p class="text-sm font-bold text-vttu-dark leading-none">{{ $author }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600">
                                    <i class="fas fa-building text-[10px]"></i>
                                </div>
                                <div>
                                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">Nhà xuất bản</p>
                                    <p class="text-sm font-bold text-vttu-dark leading-none">{{ $publisher }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-full bg-amber-100 flex items-center justify-center text-amber-600">
                                    <i class="fas fa-calendar-alt text-[10px]"></i>
                                </div>
                                <div>
                                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">Năm XB</p>
                                    <p class="text-sm font-bold text-vttu-dark leading-none">{{ $pubYear ?: 'Đang cập nhật' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Summary (Trường 520) -->
                    <div class="mb-10">
                        <h3 class="flex items-center gap-3 text-sm font-black text-vttu-dark uppercase tracking-[0.2em] mb-6">
                            <span class="w-8 h-1 bg-vttu-red rounded-full"></span>
                            Tóm tắt nội dung
                        </h3>
                        <div class="prose prose-slate max-w-none">
                            <p class="text-slate-600 leading-relaxed font-medium bg-slate-50 p-6 rounded-3xl border border-slate-100 italic">
                                "{{ $summary }}"
                            </p>
                        </div>
                    </div>

                    <!-- Detail Information -->
                    <div class="space-y-6">
                        <h3 class="flex items-center gap-3 text-sm font-black text-vttu-dark uppercase tracking-[0.2em] mb-6">
                            <span class="w-8 h-1 bg-vttu-red rounded-full"></span>
                            Thông tin chi tiết
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-4">
                            <div class="flex justify-between py-3 border-b border-slate-50 items-center">
                                <span class="text-xs font-black text-slate-400 uppercase tracking-widest">Mã vạch (Barcode)</span>
                                <span class="text-sm font-bold text-vttu-dark">{{ $record->items->first()?->barcode ?? 'N/A' }}</span>
                            </div>
                            <div class="flex justify-between py-3 border-b border-slate-50 items-center">
                                <span class="text-xs font-black text-slate-400 uppercase tracking-widest">Khổ sách</span>
                                <span class="text-sm font-bold text-vttu-dark">{{ $marcData['300']['c'] ?? 'Đang cập nhật' }}</span>
                            </div>
                            <div class="flex justify-between py-3 border-b border-slate-50 items-center">
                                <span class="text-xs font-black text-slate-400 uppercase tracking-widest">Số trang</span>
                                <span class="text-sm font-bold text-vttu-dark">{{ $marcData['300']['a'] ?? 'Đang cập nhật' }}</span>
                            </div>
                            <div class="flex justify-between py-3 border-b border-slate-50 items-center">
                                <span class="text-xs font-black text-slate-400 uppercase tracking-widest">ISBN</span>
                                <span class="text-sm font-bold text-vttu-dark">{{ $marcData['020']['a'] ?? 'N/A' }}</span>
                            </div>
                            <div class="flex justify-between py-3 border-b border-slate-50 items-center">
                                <span class="text-xs font-black text-slate-400 uppercase tracking-widest">Phân loại DDC</span>
                                <span class="text-sm font-bold text-vttu-dark">{{ $marcData['082']['a'] ?? 'N/A' }}</span>
                            </div>
                            <div class="flex justify-between py-3 border-b border-slate-50 items-center">
                                <span class="text-xs font-black text-slate-400 uppercase tracking-widest">Ngôn ngữ</span>
                                <span class="text-sm font-bold text-vttu-dark">Tiếng Việt</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Availability Section -->
                <div class="bg-white rounded-[2.5rem] p-10 shadow-sm border border-slate-100">
                    <h3 class="flex items-center gap-3 text-sm font-black text-vttu-dark uppercase tracking-[0.2em] mb-8">
                        <span class="w-8 h-1 bg-emerald-500 rounded-full"></span>
                        Trạng thái các ấn phẩm hiện có
                    </h3>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="text-left border-b border-slate-100">
                                    <th class="pb-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Số đăng ký</th>
                                    <th class="pb-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Kho tài liệu</th>
                                    <th class="pb-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Vị trí</th>
                                    <th class="pb-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Trạng thái</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @foreach($record->items as $item)
                                <tr>
                                    <td class="py-4 font-mono text-sm font-bold text-vttu-dark">{{ $item->accession_number }}</td>
                                    <td class="py-4 text-sm font-bold text-slate-600">{{ $item->storageLocation->name ?? 'N/A' }}</td>
                                    <td class="py-4 text-sm font-bold text-slate-600">{{ $item->shelf ?? 'Đang cập nhật' }}</td>
                                    <td class="py-4 text-center">
                                        @if($item->status == 'available')
                                            <span class="px-3 py-1 bg-emerald-50 text-emerald-600 text-[10px] font-black uppercase rounded-lg">Có thể mượn</span>
                                        @else
                                            <span class="px-3 py-1 bg-rose-50 text-rose-500 text-[10px] font-black uppercase rounded-lg">Đang bận</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
    @keyframes swal-book-float {
        0% { transform: translateY(100vh) translateX(0) rotate(0deg); opacity: 0; }
        20% { opacity: 1; }
        80% { opacity: 1; }
        100% { transform: translateY(-20vh) translateX(100px) rotate(360deg); opacity: 0; }
    }
    .swal2-container .floating-book {
        position: fixed;
        color: rgba(255, 255, 255, 0.3);
        font-size: 2rem;
        pointer-events: none;
        z-index: -1;
        animation: swal-book-float linear infinite;
    }
</style>
<script>
    function createFloatingBooks() {
        const container = document.querySelector('.swal2-container');
        const icons = ['fa-book', 'fa-book-open', 'fa-journal-whills', 'fa-book-bookmark', 'fa-library'];
        for (let i = 0; i < 15; i++) {
            const book = document.createElement('i');
            const icon = icons[Math.floor(Math.random() * icons.length)];
            book.className = `fas ${icon} floating-book`;
            book.style.left = `${Math.random() * 100}vw`;
            book.style.animationDuration = `${3 + Math.random() * 4}s`;
            book.style.animationDelay = `${Math.random() * 2}s`;
            book.style.fontSize = `${1 + Math.random() * 2}rem`;
            container.appendChild(book);
        }
    }

    function confirmReservation(id, title) {
        Swal.fire({
            title: 'Xác nhận mượn sách?',
            html: `Bạn muốn đăng ký mượn cuốn:<br><b class="text-vttu-red">${title}</b>`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#680102',
            cancelButtonColor: '#94a3b8',
            confirmButtonText: 'Đăng ký ngay',
            cancelButtonText: 'Hủy bỏ',
            borderRadius: '2rem',
            didOpen: () => {
                createFloatingBooks();
            },
            customClass: {
                popup: 'rounded-[2.5rem] border-4 border-vttu-red/10 shadow-2xl',
                confirmButton: 'rounded-2xl px-8 py-4 font-black uppercase text-xs tracking-[0.2em] shadow-lg shadow-vttu-red/20',
                cancelButton: 'rounded-2xl px-8 py-4 font-black uppercase text-xs tracking-[0.2em]'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Hiển thị loading
                Swal.fire({
                    title: 'Đang xử lý...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                // Gửi AJAX
                fetch(`/opac/book/${id}/reserve`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(async response => {
                    const isJson = response.headers.get('content-type')?.includes('application/json');
                    const data = isJson ? await response.json() : null;

                    if (!response.ok) {
                        throw new Error(data?.message || 'Có lỗi xảy ra từ máy chủ.');
                    }
                    return data;
                })
                .then(data => {
                    if (data && data.success) {
                        Swal.fire({
                            title: 'Thành công!',
                            text: data.message,
                            icon: 'success',
                            confirmButtonColor: '#680102',
                            borderRadius: '2rem'
                        }).then(() => {
                            window.location.reload();
                        });
                    } else {
                        throw new Error(data?.message || 'Đăng ký không thành công.');
                    }
                })
                .catch(error => {
                    console.error('Reservation error:', error);
                    Swal.fire({
                        title: 'Thất bại!',
                        text: error.message || 'Không thể kết nối tới hệ thống.',
                        icon: 'error',
                        confirmButtonColor: '#680102',
                        borderRadius: '2rem'
                    });
                });
            }
        });
    }
</script>
@endsection
@endsection
