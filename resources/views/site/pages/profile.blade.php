@extends('layouts.site')

@section('title', 'Hồ sơ cá nhân - VTTLib')

@section('content')
<div class="bg-slate-50 min-h-screen pt-24 pb-12">
    <div class="max-w-9xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- Sidebar: User Info -->
            <div class="lg:col-span-4">
                <div class="bg-white rounded-[2.5rem] p-8 shadow-sm border border-slate-100 sticky top-28">
                    <div class="flex flex-col items-center text-center">
                        <div class="w-32 h-32 rounded-full bg-vttu-red/5 flex items-center justify-center border-4 border-white shadow-xl mb-6 overflow-hidden">
                            @if($user->profile_photo_path)
                                <img src="{{ asset('storage/' . $user->profile_photo_path) }}" class="w-full h-full object-cover">
                            @else
                                <i class="fas fa-user text-vttu-red text-5xl"></i>
                            @endif
                        </div>
                        <h2 class="text-xl font-black text-vttu-dark uppercase tracking-tight">{{ $user->name }}</h2>
                        <p class="text-slate-400 font-bold text-xs mt-1 uppercase tracking-widest">{{ $patron->patronGroup->name ?? 'Độc giả' }}</p>
                        
                        <div class="w-full grid grid-cols-2 gap-4 mt-8 pt-8 border-t border-slate-50">
                            <div class="p-4 bg-slate-50 rounded-2xl">
                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Mã độc giả</p>
                                <p class="text-sm font-bold text-vttu-dark">{{ $patron->patron_code ?? 'N/A' }}</p>
                            </div>
                            <div class="p-4 bg-slate-50 rounded-2xl">
                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Trạng thái thẻ</p>
                                <span class="px-2 py-0.5 bg-emerald-50 text-emerald-600 text-[10px] font-black uppercase rounded-lg">Hoạt động</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content: Requests & Loans -->
            <div class="lg:col-span-8 space-y-8">
                <!-- Loan Requests -->
                <div class="bg-white rounded-[2.5rem] p-8 shadow-sm border border-slate-100">
                    <h3 class="flex items-center gap-3 text-sm font-black text-vttu-dark uppercase tracking-[0.2em] mb-8">
                        <span class="w-8 h-1 bg-vttu-red rounded-full"></span>
                        Yêu cầu mượn sách đang chờ
                    </h3>
                    
                    <div class="space-y-4">
                        @forelse($reservations as $res)
                        @php
                            $resTitle = $res->bibliographicRecord->fields->where('tag', '245')->first()?->subfields->where('code', 'a')->first()?->value ?? 'Không có nhan đề';
                        @endphp
                        <div class="flex items-center justify-between p-6 bg-slate-50 rounded-3xl border border-slate-100">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-16 bg-white rounded-lg flex items-center justify-center text-slate-200 border border-slate-100 shadow-sm overflow-hidden">
                                    @if($res->bibliographicRecord->cover_image)
                                        <img src="{{ asset('storage/' . $res->bibliographicRecord->cover_image) }}" class="w-full h-full object-cover">
                                    @else
                                        <i class="fas fa-book text-xl"></i>
                                    @endif
                                </div>
                                <div>
                                    <h4 class="text-sm font-black text-vttu-dark line-clamp-1">{{ $resTitle }}</h4>
                                    <p class="text-[10px] text-slate-400 font-bold mt-1 uppercase tracking-widest">Ngày đăng ký: {{ $res->reservation_date->format('d/m/Y') }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                @if($res->status == 'pending')
                                    <span class="px-3 py-1 bg-amber-50 text-amber-600 text-[10px] font-black uppercase rounded-full border border-amber-100">Đang chờ duyệt</span>
                                @elseif($res->status == 'ready')
                                    <span class="px-3 py-1 bg-emerald-50 text-emerald-600 text-[10px] font-black uppercase rounded-full border border-emerald-100">Sẵn sàng nhận sách</span>
                                @elseif($res->status == 'rejected')
                                    <span class="px-3 py-1 bg-rose-50 text-rose-500 text-[10px] font-black uppercase rounded-full border border-rose-100">Đã từ chối</span>
                                @endif
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-12">
                            <i class="fas fa-inbox text-slate-200 text-4xl mb-4"></i>
                            <p class="text-slate-400 font-bold">Bạn không có yêu cầu mượn nào.</p>
                        </div>
                        @endforelse
                    </div>
                </div>

                <!-- Active Loans -->
                <div class="bg-white rounded-[2.5rem] p-8 shadow-sm border border-slate-100">
                    <h3 class="flex items-center gap-3 text-sm font-black text-vttu-dark uppercase tracking-[0.2em] mb-8">
                        <span class="w-8 h-1 bg-emerald-500 rounded-full"></span>
                        Sách đang mượn
                    </h3>
                    
                    <div class="space-y-4">
                        @forelse($activeLoans as $loan)
                        @php
                            $loanTitle = $loan->bookItem->bibliographicRecord->fields->where('tag', '245')->first()?->subfields->where('code', 'a')->first()?->value ?? 'Không có nhan đề';
                        @endphp
                        <div class="flex items-center justify-between p-6 bg-slate-50 rounded-3xl border border-slate-100">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-16 bg-white rounded-lg flex items-center justify-center text-slate-200 border border-slate-100 shadow-sm overflow-hidden">
                                    @if($loan->bookItem->bibliographicRecord->cover_image)
                                        <img src="{{ asset('storage/' . $loan->bookItem->bibliographicRecord->cover_image) }}" class="w-full h-full object-cover">
                                    @else
                                        <i class="fas fa-book text-xl"></i>
                                    @endif
                                </div>
                                <div>
                                    <h4 class="text-sm font-black text-vttu-dark line-clamp-1">{{ $loanTitle }}</h4>
                                    <div class="flex gap-4 mt-1">
                                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Hạn trả: <span class="text-rose-500">{{ $loan->due_date->format('d/m/Y') }}</span></p>
                                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Mã vạch: {{ $loan->bookItem->barcode }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="px-3 py-1 bg-blue-50 text-blue-600 text-[10px] font-black uppercase rounded-full border border-blue-100">Đang mượn</span>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-12">
                            <i class="fas fa-book-reader text-slate-200 text-4xl mb-4"></i>
                            <p class="text-slate-400 font-bold">Bạn hiện không mượn tài liệu nào.</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
