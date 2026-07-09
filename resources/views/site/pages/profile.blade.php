@extends('layouts.site')

@section('title', 'Hồ sơ cá nhân - VTTLib')

@section('content')
<div class="bg-slate-50 min-h-screen pt-24 pb-12" x-data="{ activeTab: '{{ request()->query('tab', $errors->has('current_password') || $errors->has('new_password') ? 'password' : (session('success') ? 'password' : 'info')) }}' }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- Sidebar: User Info -->
            <div class="lg:col-span-4">
                <div class="bg-white rounded-md p-6 shadow-sm border border-slate-100 sticky top-28">
                    <div class="flex flex-col items-center text-center">
                        <div class="w-24 h-24 rounded-full bg-vttu-red/5 flex items-center justify-center border-4 border-white shadow-md mb-4 overflow-hidden">
                            @if($user->profile_photo_path)
                                <img src="{{ asset('storage/' . $user->profile_photo_path) }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full bg-vttu-red flex items-center justify-center">
                                    <span class="text-2xl font-black text-white">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                </div>
                            @endif
                        </div>
                        <h2 class="text-lg font-bold text-vttu-dark uppercase tracking-tight">{{ $user->name }}</h2>
                        <p class="text-slate-400 font-bold text-[10px] mt-1 uppercase tracking-widest">{{ $user->roles->pluck('display_name')->implode(', ') ?: 'Độc giả' }}</p>
                        
                        <div class="w-full grid grid-cols-2 gap-3 mt-6 pt-6 border-t border-slate-50">
                            <div class="p-3 bg-slate-50 rounded-sm">
                                <p class="text-[8px] font-bold text-slate-400 uppercase tracking-widest mb-1">Mã độc giả</p>
                                <p class="text-xs font-bold text-vttu-dark">{{ $patron?->patron_code ?? 'N/A' }}</p>
                            </div>
                            <div class="p-3 bg-slate-50 rounded-sm">
                                <p class="text-[8px] font-bold text-slate-400 uppercase tracking-widest mb-1">Trạng thái thẻ</p>
                                <p class="text-xs font-bold text-vttu-dark">
                                    @if($patron)
                                        <span class="px-2 py-0.5 bg-emerald-50 text-emerald-600 text-[9px] font-bold uppercase rounded-sm">Hoạt động</span>
                                    @else
                                        <span class="px-2 py-0.5 bg-slate-100 text-slate-500 text-[9px] font-bold uppercase rounded-sm">N/A</span>
                                    @endif
                                </p>
                            </div>
                        </div>

                        <!-- Menu Tabs -->
                        <div class="w-full mt-6 space-y-2">
                            <button @click="activeTab = 'info'; window.history.replaceState(null, '', '?tab=info')" :class="activeTab === 'info' ? 'bg-vttu-red text-white shadow-sm' : 'text-slate-600 hover:bg-slate-50'" class="w-full flex items-center space-x-3 px-4 py-3 rounded-sm transition-all text-xs font-bold uppercase tracking-widest">
                                <i class="fas fa-user-circle"></i>
                                <span>Thông tin cá nhân</span>
                            </button>
                            <button @click="activeTab = 'history'; window.history.replaceState(null, '', '?tab=history')" :class="activeTab === 'history' ? 'bg-vttu-red text-white shadow-sm' : 'text-slate-600 hover:bg-slate-50'" class="w-full flex items-center space-x-3 px-4 py-3 rounded-sm transition-all text-xs font-bold uppercase tracking-widest">
                                <i class="fas fa-history"></i>
                                <span>Lịch sử mượn sách</span>
                            </button>
                            <button @click="activeTab = 'password'; window.history.replaceState(null, '', '?tab=password')" :class="activeTab === 'password' ? 'bg-vttu-red text-white shadow-sm' : 'text-slate-600 hover:bg-slate-50'" class="w-full flex items-center space-x-3 px-4 py-3 rounded-sm transition-all text-xs font-bold uppercase tracking-widest">
                                <i class="fas fa-key"></i>
                                <span>Đổi mật khẩu</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="lg:col-span-8">
                @if (session('success'))
                    <div class="mb-4 bg-emerald-50 border border-emerald-200 text-emerald-600 rounded-md p-3 text-xs font-bold flex items-center gap-2">
                        <i class="fas fa-check-circle"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif
                @if ($errors->any())
                    <div class="mb-4 bg-rose-50 border border-rose-200 text-rose-600 rounded-md p-3 text-xs font-bold space-y-1">
                        @foreach ($errors->all() as $error)
                            <div class="flex items-center gap-2">
                                <i class="fas fa-exclamation-circle"></i>
                                <span>{{ $error }}</span>
                            </div>
                        @endforeach
                    </div>
                @endif
                <!-- Tab: Information -->
                <div x-show="activeTab === 'info'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3 mb-6">
                        <div class="bg-white p-4 rounded-md shadow-sm border border-slate-100">
                            <div class="w-8 h-8 bg-blue-50 text-blue-500 rounded-sm flex items-center justify-center mb-3 text-sm">
                                <i class="fas fa-book"></i>
                            </div>
                            <p class="text-[8px] font-bold text-slate-400 uppercase tracking-widest">Đã mượn</p>
                            <h4 class="text-xl font-bold text-vttu-dark">{{ $stats['total_borrowed'] }}</h4>
                        </div>
                        <div class="bg-white p-4 rounded-md shadow-sm border border-slate-100">
                            <div class="w-8 h-8 bg-emerald-50 text-emerald-500 rounded-sm flex items-center justify-center mb-3 text-sm">
                                <i class="fas fa-book-reader"></i>
                            </div>
                            <p class="text-[8px] font-bold text-slate-400 uppercase tracking-widest">Đang mượn</p>
                            <h4 class="text-xl font-bold text-vttu-dark">{{ $stats['active_loans'] }}</h4>
                        </div>
                        <div class="bg-white p-4 rounded-md shadow-sm border border-slate-100">
                            <div class="w-8 h-8 bg-amber-50 text-amber-500 rounded-sm flex items-center justify-center mb-3 text-sm">
                                <i class="fas fa-exclamation-circle"></i>
                            </div>
                            <p class="text-[8px] font-bold text-slate-400 uppercase tracking-widest">Quá hạn</p>
                            <h4 class="text-xl font-bold text-vttu-dark">{{ $stats['overdue_loans'] }}</h4>
                        </div>
                        <div class="bg-white p-4 rounded-md shadow-sm border border-slate-100">
                            <div class="w-8 h-8 bg-rose-50 text-rose-500 rounded-sm flex items-center justify-center mb-3 text-sm">
                                <i class="fas fa-wallet"></i>
                            </div>
                            <p class="text-[8px] font-bold text-slate-400 uppercase tracking-widest">Tiền nợ</p>
                            <h4 class="text-xl font-bold text-vttu-dark">{{ number_format($stats['total_fines']) }}đ</h4>
                        </div>
                    </div>

                    <div class="bg-white rounded-md p-6 shadow-sm border border-slate-100">
                        <h3 class="flex items-center gap-2 text-xs font-bold text-vttu-dark uppercase tracking-[0.2em] mb-6">
                            <span class="w-6 h-1 bg-vttu-red rounded-full"></span>
                            Thông tin chi tiết
                        </h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-4">
                                <div>
                                    <label class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block mb-1.5">Họ và tên</label>
                                    <p class="text-xs font-bold text-vttu-dark bg-slate-50 px-4 py-2.5 rounded-sm border border-slate-100">{{ $user->name }}</p>
                                </div>
                                <div>
                                    <label class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block mb-1.5">Email</label>
                                    <p class="text-xs font-bold text-vttu-dark bg-slate-50 px-4 py-2.5 rounded-sm border border-slate-100">{{ $user->email }}</p>
                                </div>
                                <div>
                                    <label class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block mb-1.5">Số điện thoại</label>
                                    <p class="text-xs font-bold text-vttu-dark bg-slate-50 px-4 py-2.5 rounded-sm border border-slate-100">{{ $patron?->phone ?? 'Chưa cập nhật' }}</p>
                                </div>
                            </div>
                            <div class="space-y-4">
                                <div>
                                    <label class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block mb-1.5">Địa chỉ</label>
                                    <p class="text-xs font-bold text-vttu-dark bg-slate-50 px-4 py-2.5 rounded-sm border border-slate-100">{{ $patron?->address ?? 'Chưa cập nhật' }}</p>
                                </div>
                                <div>
                                    <label class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block mb-1.5">Ngày sinh</label>
                                    <p class="text-xs font-bold text-vttu-dark bg-slate-50 px-4 py-2.5 rounded-sm border border-slate-100">{{ $patron?->dob ? \Carbon\Carbon::parse($patron->dob)->format('d/m/Y') : 'Chưa cập nhật' }}</p>
                                </div>
                                <div>
                                    <label class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block mb-1.5">Đơn vị/Lớp</label>
                                    <p class="text-xs font-bold text-vttu-dark bg-slate-50 px-4 py-2.5 rounded-sm border border-slate-100">{{ $patron?->department ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tab: Loan History -->
                <div x-show="activeTab === 'history'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-6">
                    <!-- Section: Reservations -->
                    <div class="bg-white rounded-md p-6 shadow-sm border border-slate-100">
                        <h3 class="flex items-center gap-2 text-xs font-bold text-vttu-dark uppercase tracking-[0.2em] mb-6">
                            <span class="w-6 h-1 bg-amber-400 rounded-full"></span>
                            Yêu cầu mượn & Đăng ký
                        </h3>
                        
                        <div class="space-y-3">
                            @forelse($reservations as $res)
                            @php
                                $resTitle = $res->bibliographicRecord->fields->where('tag', '245')->first()?->subfields->where('code', 'a')->first()?->value ?? 'Không có nhan đề';
                            @endphp
                            <div class="p-4 bg-slate-50 rounded-sm border border-slate-100">
                                <div class="flex flex-col md:flex-row md:items-center justify-between gap-3">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-14 bg-white rounded-sm flex items-center justify-center text-slate-200 border border-slate-100 shadow-sm overflow-hidden flex-shrink-0">
                                            @if($res->bibliographicRecord->cover_image)
                                                <img src="{{ asset('storage/' . $res->bibliographicRecord->cover_image) }}" class="w-full h-full object-contain mix-blend-multiply">
                                            @else
                                                <i class="fas fa-book text-lg"></i>
                                            @endif
                                        </div>
                                        <div>
                                            <h4 class="text-xs font-bold text-vttu-dark line-clamp-1">{{ $resTitle }}</h4>
                                            <p class="text-[9px] text-slate-400 font-bold mt-1 uppercase tracking-widest">Đăng ký: {{ $res->reservation_date->format('d/m/Y') }}</p>
                                        </div>
                                    </div>
                                    <div class="flex flex-col items-end gap-1">
                                        @if($res->status == 'pending')
                                            <span class="px-2 py-0.5 bg-amber-50 text-amber-600 text-[9px] font-bold uppercase rounded-sm border border-amber-100">Đang chờ duyệt</span>
                                        @elseif($res->status == 'ready')
                                            <div class="text-right">
                                                <span class="px-2 py-0.5 bg-emerald-50 text-emerald-600 text-[9px] font-bold uppercase rounded-sm border border-emerald-100">Sẵn sàng nhận sách</span>
                                                @if($res->expiry_date)
                                                <p class="text-[8px] text-emerald-600 font-bold mt-0.5 uppercase tracking-widest">
                                                    Còn {{ now()->diffInDays($res->expiry_date, false) }} ngày
                                                </p>
                                                @endif
                                            </div>
                                        @elseif($res->status == 'cancelled' || $res->status == 'rejected')
                                            <span class="px-2 py-0.5 bg-rose-50 text-rose-500 text-[9px] font-bold uppercase rounded-sm border border-rose-100">Đã hủy / Từ chối</span>
                                        @elseif($res->status == 'completed')
                                            <span class="px-2 py-0.5 bg-blue-50 text-blue-500 text-[9px] font-bold uppercase rounded-sm border border-blue-100">Đã hoàn tất</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div class="text-center py-8">
                                <p class="text-slate-400 font-bold text-xs">Không có yêu cầu nào.</p>
                            </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Section: Current Loans -->
                    <div class="bg-white rounded-md p-6 shadow-sm border border-slate-100">
                        <h3 class="flex items-center gap-2 text-xs font-bold text-vttu-dark uppercase tracking-[0.2em] mb-6">
                            <span class="w-6 h-1 bg-emerald-500 rounded-full"></span>
                            Sách đang mượn
                        </h3>
                        
                        <div class="space-y-3">
                            @forelse($activeLoans as $loan)
                            @php
                                $loanTitle = $loan->bookItem->bibliographicRecord->fields->where('tag', '245')->first()?->subfields->where('code', 'a')->first()?->value ?? 'Không có nhan đề';
                                $isOverdue = $loan->isOverdue();
                            @endphp
                            <div class="p-4 {{ $isOverdue ? 'bg-rose-50 border-rose-100' : 'bg-slate-50 border-slate-100' }} rounded-sm border">
                                <div class="flex flex-col md:flex-row md:items-center justify-between gap-3">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-14 bg-white rounded-sm flex items-center justify-center text-slate-200 border border-slate-100 shadow-sm overflow-hidden flex-shrink-0">
                                            @if($loan->bookItem->bibliographicRecord->cover_image)
                                                <img src="{{ asset('storage/' . $loan->bookItem->bibliographicRecord->cover_image) }}" class="w-full h-full object-contain mix-blend-multiply">
                                            @else
                                                <i class="fas fa-book text-lg"></i>
                                            @endif
                                        </div>
                                        <div>
                                            <h4 class="text-xs font-bold text-vttu-dark line-clamp-1">{{ $loanTitle }}</h4>
                                            <div class="flex flex-wrap gap-x-3 gap-y-0.5 mt-1">
                                                <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest">Ngày mượn: {{ $loan->loan_date->format('d/m/Y') }}</p>
                                                <p class="text-[9px] font-bold uppercase tracking-widest {{ $isOverdue ? 'text-rose-500' : 'text-slate-400' }}">
                                                    Hạn trả: {{ $loan->due_date->format('d/m/Y') }} 
                                                    @if($isOverdue) ({{ $loan->getOverdueDays() }} ngày) @endif
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <span class="px-2 py-0.5 {{ $isOverdue ? 'bg-rose-100 text-rose-600 border-rose-200' : 'bg-blue-50 text-blue-600 border-blue-100' }} text-[9px] font-bold uppercase rounded-sm border">
                                            {{ $isOverdue ? 'Quá hạn' : 'Đang mượn' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div class="text-center py-8">
                                <p class="text-slate-400 font-bold text-xs">Hiện không mượn cuốn sách nào.</p>
                            </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Section: Returned Loans -->
                    <div class="bg-white rounded-md p-6 shadow-sm border border-slate-100">
                        <h3 class="flex items-center gap-2 text-xs font-bold text-vttu-dark uppercase tracking-[0.2em] mb-6">
                            <span class="w-6 h-1 bg-slate-300 rounded-full"></span>
                            Lịch sử đã trả
                        </h3>
                        
                        <div class="space-y-3">
                            @forelse($returnedLoans as $loan)
                            @php
                                $loanTitle = $loan->bookItem->bibliographicRecord->fields->where('tag', '245')->first()?->subfields->where('code', 'a')->first()?->value ?? 'Không có nhan đề';
                            @endphp
                            <div class="p-4 bg-slate-50/50 rounded-sm border border-slate-100 opacity-75 hover:opacity-100 transition-opacity">
                                <div class="flex flex-col md:flex-row md:items-center justify-between gap-3">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-14 bg-white rounded-sm flex items-center justify-center text-slate-200 border border-slate-100 shadow-sm overflow-hidden flex-shrink-0">
                                            @if($loan->bookItem->bibliographicRecord->cover_image)
                                                <img src="{{ asset('storage/' . $loan->bookItem->bibliographicRecord->cover_image) }}" class="w-full h-full object-contain mix-blend-multiply">
                                            @else
                                                <i class="fas fa-book text-lg"></i>
                                            @endif
                                        </div>
                                        <div>
                                            <h4 class="text-xs font-bold text-vttu-dark line-clamp-1">{{ $loanTitle }}</h4>
                                            <div class="flex flex-wrap gap-x-3 gap-y-0.5 mt-1">
                                                <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest">Đã trả: {{ $loan->return_date ? $loan->return_date->format('d/m/Y') : 'N/A' }}</p>
                                                <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest">Mã vạch: {{ $loan->bookItem->barcode }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <span class="px-2 py-0.5 bg-slate-100 text-slate-500 text-[9px] font-bold uppercase rounded-sm border border-slate-200">Đã trả</span>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div class="text-center py-8">
                                <p class="text-slate-400 font-bold text-xs">Chưa có lịch sử trả sách.</p>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Tab: Change Password -->
                <div x-show="activeTab === 'password'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                    <div class="bg-white rounded-md p-6 shadow-sm border border-slate-100">
                        <h3 class="flex items-center gap-2 text-xs font-bold text-vttu-dark uppercase tracking-[0.2em] mb-6">
                            <span class="w-6 h-1 bg-vttu-red rounded-full"></span>
                            Đổi mật khẩu tài khoản
                        </h3>
                        
                        <form action="{{ route('profile.change-password') }}" method="POST" class="space-y-4 max-w-md">
                            @csrf
                            <div>
                                <label for="current_password" class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block mb-1.5">Mật khẩu hiện tại</label>
                                <input type="password" name="current_password" id="current_password" required
                                       class="w-full text-xs font-bold text-vttu-dark bg-slate-50 px-4 py-2.5 rounded-sm border border-slate-100 focus:outline-none focus:border-vttu-red transition-colors">
                            </div>
                            
                            <div>
                                <label for="new_password" class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block mb-1.5">Mật khẩu mới</label>
                                <input type="password" name="new_password" id="new_password" required
                                       class="w-full text-xs font-bold text-vttu-dark bg-slate-50 px-4 py-2.5 rounded-sm border border-slate-100 focus:outline-none focus:border-vttu-red transition-colors">
                            </div>
                            
                            <div>
                                <label for="new_password_confirmation" class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block mb-1.5">Xác nhận mật khẩu mới</label>
                                <input type="password" name="new_password_confirmation" id="new_password_confirmation" required
                                       class="w-full text-xs font-bold text-vttu-dark bg-slate-50 px-4 py-2.5 rounded-sm border border-slate-100 focus:outline-none focus:border-vttu-red transition-colors">
                            </div>
                            
                            <div class="pt-2">
                                <button type="submit"
                                        class="inline-flex items-center justify-center px-4 py-2.5 bg-vttu-red text-white hover:bg-vttu-dark text-xs font-bold rounded shadow-sm transition-all gap-1.5 active:scale-[0.98]">
                                    <i class="fas fa-save"></i>
                                    <span>Cập nhật mật khẩu</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

