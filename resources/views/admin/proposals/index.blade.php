@extends('layouts.admin')

@section('content')
<div class="space-y-4 animate-in fade-in duration-500">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded bg-primary flex items-center justify-center text-primary-foreground shadow-sm">
                <i data-lucide="lightbulb" class="w-5 h-5"></i>
            </div>
            <div>
                <h1 class="text-xl font-bold text-foreground tracking-tight">{{ __('Đề xuất mua sách') }}</h1>
                <p class="text-sm text-muted-foreground">{{ __('Danh sách đề xuất, đề nghị bổ sung sách từ sinh viên và độc giả.') }}</p>
            </div>
        </div>
    </div>

    <!-- Alert Success -->
    @if(session('success'))
        <div class="p-3 bg-emerald-500/10 border border-emerald-500/30 text-emerald-500 text-sm rounded-md flex items-center gap-2">
            <i data-lucide="check-circle" class="w-4 h-4"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <div class="bg-card rounded-md shadow-sm border border-border overflow-hidden">
        <!-- Filter & Search Bar -->
        <div class="p-3 bg-muted/30 border-b border-border">
            <form action="{{ route('admin.book-proposals.index') }}" method="GET" class="flex flex-col sm:flex-row gap-2">
                <!-- Search Input -->
                <div class="relative flex-1 sm:max-w-xs">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none">
                        <i data-lucide="search" class="w-4 h-4 text-muted-foreground"></i>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}" 
                        placeholder="Tìm nhan đề, tác giả, độc giả..." 
                        class="block w-full pl-9 pr-3 py-1.5 h-9 text-sm border border-input rounded bg-background text-foreground placeholder-muted-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all">
                </div>
                
                <!-- Status Filter -->
                <select name="status" class="h-9 w-full sm:w-48 px-3 py-1.5 text-sm border border-input rounded bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all">
                    <option value="">{{ __('Tất cả trạng thái') }}</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Đang chờ xử lý</option>
                    <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Đã đồng ý</option>
                    <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Đã từ chối</option>
                    <option value="added" {{ request('status') === 'added' ? 'selected' : '' }}>Đã thêm tài liệu</option>
                </select>

                <!-- Action Buttons -->
                <div class="flex gap-2">
                    <button type="submit" class="inline-flex items-center justify-center gap-2 px-4 h-9 rounded text-xs font-medium transition-all duration-200 active:scale-95 bg-primary text-primary-foreground hover:bg-primary/90 shadow-sm active:bg-primary/80">
                        {{ __('Tìm kiếm') }}
                    </button>

                    @if(request('search') || request('status'))
                        <a href="{{ route('admin.book-proposals.index') }}" 
                            class="inline-flex items-center justify-center gap-2 px-3 h-9 rounded text-xs font-medium transition-all duration-200 active:scale-95 bg-muted text-muted-foreground hover:bg-muted/80 border border-border hover:text-foreground active:bg-muted/60">
                            <i data-lucide="x" class="w-4 h-4"></i>
                            {{ __('Xóa lọc') }}
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Table View -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-muted/50 text-xs font-semibold uppercase tracking-wider text-muted-foreground border-b border-border">
                    <tr>
                        <th class="py-3 px-4">{{ __('Độc giả đề xuất') }}</th>
                        <th class="py-3 px-4">{{ __('Thông tin sách đề xuất') }}</th>
                        <th class="py-3 px-4">{{ __('Lý do') }}</th>
                        <th class="py-3 px-4">{{ __('Ngày gửi') }}</th>
                        <th class="py-3 px-4">{{ __('Trạng thái') }}</th>
                        <th class="py-3 px-4 text-right w-52">{{ __('Cập nhật trạng thái') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border text-sm">
                    @forelse($proposals as $proposal)
                        <tr class="hover:bg-muted/30 transition-colors">
                            <!-- Patron Info -->
                            <td class="py-3 px-4">
                                <div class="font-semibold text-foreground">{{ $proposal->fullname }}</div>
                                <div class="text-xs text-muted-foreground flex items-center gap-1 mt-0.5">
                                    <i data-lucide="mail" class="w-3.5 h-3.5"></i>
                                    <span>{{ $proposal->email_phone }}</span>
                                </div>
                                @if($proposal->user)
                                    <div class="text-[10px] text-primary/80 mt-1 inline-flex items-center gap-0.5 px-1.5 py-0.5 rounded bg-primary/10">
                                        <i data-lucide="user" class="w-3 h-3"></i>
                                        <span>Tài khoản: {{ $proposal->user->name }}</span>
                                    </div>
                                @endif
                            </td>

                            <!-- Book Info -->
                            <td class="py-3 px-4">
                                <div class="font-bold text-primary">{{ $proposal->book_title }}</div>
                                <div class="text-xs text-muted-foreground space-y-0.5 mt-1">
                                    @if($proposal->author)
                                        <div><span class="text-muted-foreground/70">{{ __('Tác giả:') }}</span> {{ $proposal->author }}</div>
                                    @endif
                                    @if($proposal->publisher_year)
                                        <div><span class="text-muted-foreground/70">{{ __('NXB & Năm:') }}</span> {{ $proposal->publisher_year }}</div>
                                    @endif
                                    <div><span class="text-muted-foreground/70">{{ __('Số lượng:') }}</span> <strong class="text-foreground">{{ $proposal->quantity }}</strong></div>
                                </div>
                            </td>

                            <!-- Reason -->
                            <td class="py-3 px-4 max-w-xs">
                                <p class="text-xs text-foreground/80 line-clamp-3" title="{{ $proposal->reason }}">
                                    {{ $proposal->reason ?: '—' }}
                                </p>
                            </td>

                            <!-- Created Date -->
                            <td class="py-3 px-4 whitespace-nowrap text-xs text-muted-foreground">
                                <div class="flex items-center gap-1">
                                    <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                                    <span>{{ $proposal->created_at ? $proposal->created_at->format('d/m/Y H:i') : '—' }}</span>
                                </div>
                            </td>

                            <!-- Current Status Badge -->
                            <td class="py-3 px-4 whitespace-nowrap">
                                @if($proposal->status === 'pending')
                                    <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-amber-500/10 text-amber-500 border border-amber-500/20">
                                        {{ __('Đang chờ xử lý') }}
                                    </span>
                                @elseif($proposal->status === 'approved')
                                    <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-blue-500/10 text-blue-500 border border-blue-500/20">
                                        {{ __('Đã đồng ý') }}
                                    </span>
                                @elseif($proposal->status === 'rejected')
                                    <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-rose-500/10 text-rose-500 border border-rose-500/20">
                                        {{ __('Đã từ chối') }}
                                    </span>
                                @elseif($proposal->status === 'added')
                                    <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-emerald-500/10 text-emerald-500 border border-emerald-500/20">
                                        {{ __('Đã thêm tài liệu') }}
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-muted text-muted-foreground">
                                        {{ $proposal->status }}
                                    </span>
                                @endif
                            </td>

                            <!-- Action Form -->
                            <td class="py-3 px-4 text-right">
                                <form action="{{ route('admin.book-proposals.update-status', $proposal->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('PATCH')
                                    <select name="status" onchange="this.form.submit()" 
                                        class="h-8 text-xs border border-input rounded bg-background text-foreground pr-8 focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all cursor-pointer">
                                        <option value="pending" {{ $proposal->status === 'pending' ? 'selected' : '' }}>Đang chờ xử lý</option>
                                        <option value="approved" {{ $proposal->status === 'approved' ? 'selected' : '' }}>Đã đồng ý</option>
                                        <option value="rejected" {{ $proposal->status === 'rejected' ? 'selected' : '' }}>Đã từ chối</option>
                                        <option value="added" {{ $proposal->status === 'added' ? 'selected' : '' }}>Đã thêm tài liệu</option>
                                    </select>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-muted-foreground">
                                <div class="flex flex-col items-center justify-center gap-2">
                                    <i data-lucide="info" class="w-8 h-8 text-muted-foreground/60"></i>
                                    <span>{{ __('Không tìm thấy đề xuất mua sách nào.') }}</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination Section -->
        @if($proposals->hasPages())
            <div class="p-3 bg-muted/20 border-t border-border">
                {{ $proposals->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
