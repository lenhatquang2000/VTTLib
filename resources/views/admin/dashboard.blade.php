@extends('layouts.admin')

@section('content')
<div class="space-y-4">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded bg-primary flex items-center justify-center text-primary-foreground shadow-sm">
                <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
            </div>
            <div>
                <h1 class="text-lg font-black text-foreground tracking-tight">{{ __('Dashboard') }}</h1>
                <p class="text-xs text-muted-foreground">{{ __('Tổng quan hoạt động thư viện') }} &middot; {{ now()->format('d/m/Y H:i') }}</p>
            </div>
        </div>
    </div>

    <!-- Row 1: Primary Stat Cards -->
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">
        <!-- Bạn đọc -->
        <div class="bg-card border border-border rounded-md p-3 hover:shadow-md transition-shadow group">
            <div class="flex items-center gap-2 mb-2">
                <div class="w-7 h-7 rounded bg-indigo-500/10 flex items-center justify-center text-indigo-500">
                    <i data-lucide="users" class="w-4 h-4"></i>
                </div>
                <span class="text-[10px] font-bold text-muted-foreground uppercase tracking-wider">{{ __('Bạn đọc') }}</span>
            </div>
            <p class="text-2xl font-black text-foreground">{{ number_format($totalPatrons) }}</p>
            <p class="text-[10px] text-muted-foreground mt-0.5">
                <span class="text-emerald-500">{{ $activePatrons }}</span> hoạt động
                @if($lockedPatrons > 0)
                    &middot; <span class="text-destructive">{{ $lockedPatrons }}</span> bị khóa
                @endif
            </p>
        </div>

        <!-- Biểu ghi -->
        <div class="bg-card border border-border rounded-md p-3 hover:shadow-md transition-shadow group">
            <div class="flex items-center gap-2 mb-2">
                <div class="w-7 h-7 rounded bg-emerald-500/10 flex items-center justify-center text-emerald-500">
                    <i data-lucide="book-open" class="w-4 h-4"></i>
                </div>
                <span class="text-[10px] font-bold text-muted-foreground uppercase tracking-wider">{{ __('Biểu ghi') }}</span>
            </div>
            <p class="text-2xl font-black text-foreground">{{ number_format($approvedRecords) }}</p>
            <p class="text-[10px] text-muted-foreground mt-0.5">/ {{ number_format($totalRecords) }} tổng cộng</p>
        </div>

        <!-- Bản sách -->
        <div class="bg-card border border-border rounded-md p-3 hover:shadow-md transition-shadow group">
            <div class="flex items-center gap-2 mb-2">
                <div class="w-7 h-7 rounded bg-cyan-500/10 flex items-center justify-center text-cyan-500">
                    <i data-lucide="library" class="w-4 h-4"></i>
                </div>
                <span class="text-[10px] font-bold text-muted-foreground uppercase tracking-wider">{{ __('Bản sách') }}</span>
            </div>
            <p class="text-2xl font-black text-foreground">{{ number_format($totalBookItems) }}</p>
            <p class="text-[10px] text-muted-foreground mt-0.5"><span class="text-emerald-500">{{ $availableItems }}</span> sẵn sàng</p>
        </div>

        <!-- Đang mượn -->
        <div class="bg-card border border-border rounded-md p-3 hover:shadow-md transition-shadow group">
            <div class="flex items-center gap-2 mb-2">
                <div class="w-7 h-7 rounded bg-amber-500/10 flex items-center justify-center text-amber-500">
                    <i data-lucide="book-up" class="w-4 h-4"></i>
                </div>
                <span class="text-[10px] font-bold text-muted-foreground uppercase tracking-wider">{{ __('Đang mượn') }}</span>
            </div>
            <p class="text-2xl font-black text-foreground">{{ number_format($activeLoans) }}</p>
            <p class="text-[10px] text-muted-foreground mt-0.5">/ {{ number_format($totalLoans) }} tổng lượt</p>
        </div>

        <!-- Quá hạn -->
        <div class="bg-card border border-border rounded-md p-3 hover:shadow-md transition-shadow group {{ $overdueLoans > 0 ? 'border-destructive/30 bg-destructive/5' : '' }}">
            <div class="flex items-center gap-2 mb-2">
                <div class="w-7 h-7 rounded bg-rose-500/10 flex items-center justify-center text-rose-500">
                    <i data-lucide="alert-triangle" class="w-4 h-4"></i>
                </div>
                <span class="text-[10px] font-bold text-muted-foreground uppercase tracking-wider">{{ __('Quá hạn') }}</span>
            </div>
            <p class="text-2xl font-black {{ $overdueLoans > 0 ? 'text-destructive' : 'text-foreground' }}">{{ number_format($overdueLoans) }}</p>
            <p class="text-[10px] text-muted-foreground mt-0.5">{{ number_format($returnedLoans) }} đã trả</p>
        </div>

        <!-- Đặt trước -->
        <div class="bg-card border border-border rounded-md p-3 hover:shadow-md transition-shadow group">
            <div class="flex items-center gap-2 mb-2">
                <div class="w-7 h-7 rounded bg-violet-500/10 flex items-center justify-center text-violet-500">
                    <i data-lucide="bookmark" class="w-4 h-4"></i>
                </div>
                <span class="text-[10px] font-bold text-muted-foreground uppercase tracking-wider">{{ __('Đặt trước') }}</span>
            </div>
            <p class="text-2xl font-black text-foreground">{{ number_format($pendingReservations) }}</p>
            <p class="text-[10px] text-muted-foreground mt-0.5">/ {{ number_format($totalReservations) }} tổng</p>
        </div>
    </div>

    <!-- Row 2: Secondary Stats -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
        <div class="bg-card border border-border rounded-md p-3 flex items-center gap-3">
            <div class="w-8 h-8 rounded bg-orange-500/10 flex items-center justify-center text-orange-500">
                <i data-lucide="banknote" class="w-4 h-4"></i>
            </div>
            <div>
                <p class="text-[10px] font-bold text-muted-foreground uppercase tracking-wider">{{ __('Phạt chưa thu') }}</p>
                <p class="text-sm font-black text-foreground">{{ number_format($totalFineAmount, 0, ',', '.') }}đ</p>
                <p class="text-[10px] text-muted-foreground">{{ $unpaidFines }} khoản</p>
            </div>
        </div>
        <div class="bg-card border border-border rounded-md p-3 flex items-center gap-3">
            <div class="w-8 h-8 rounded bg-sky-500/10 flex items-center justify-center text-sky-500">
                <i data-lucide="file-text" class="w-4 h-4"></i>
            </div>
            <div>
                <p class="text-[10px] font-bold text-muted-foreground uppercase tracking-wider">{{ __('Tài liệu số') }}</p>
                <p class="text-sm font-black text-foreground">{{ number_format($totalDigitalRes) }}</p>
                <p class="text-[10px] text-muted-foreground">đã xuất bản</p>
            </div>
        </div>
        <div class="bg-card border border-border rounded-md p-3 flex items-center gap-3">
            <div class="w-8 h-8 rounded bg-teal-500/10 flex items-center justify-center text-teal-500">
                <i data-lucide="globe" class="w-4 h-4"></i>
            </div>
            <div>
                <p class="text-[10px] font-bold text-muted-foreground uppercase tracking-wider">{{ __('OER') }}</p>
                <p class="text-sm font-black text-foreground">{{ number_format($totalOER) }}</p>
                <p class="text-[10px] text-muted-foreground">tài nguyên mở</p>
            </div>
        </div>
        <div class="bg-card border border-border rounded-md p-3 flex items-center gap-3">
            <div class="w-8 h-8 rounded bg-pink-500/10 flex items-center justify-center text-pink-500">
                <i data-lucide="newspaper" class="w-4 h-4"></i>
            </div>
            <div>
                <p class="text-[10px] font-bold text-muted-foreground uppercase tracking-wider">{{ __('Tin tức') }}</p>
                <p class="text-sm font-black text-foreground">{{ number_format($totalNews) }}</p>
                <p class="text-[10px] text-muted-foreground">bài viết</p>
            </div>
        </div>
    </div>

    <!-- Row 3: Charts -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-3">
        <!-- Loan Trend Chart -->
        <div class="lg:col-span-2 bg-card border border-border rounded-md p-3">
            <div class="flex items-center justify-between mb-3">
                <div class="flex items-center gap-2">
                    <i data-lucide="trending-up" class="w-4 h-4 text-muted-foreground"></i>
                    <h3 class="text-xs font-bold text-foreground uppercase tracking-wider">{{ __('Lượt mượn / trả theo tháng') }}</h3>
                </div>
                <div class="flex items-center gap-3 text-[10px]">
                    <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-indigo-500"></span> Mượn</span>
                    <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-emerald-500"></span> Trả</span>
                </div>
            </div>
            <div class="h-52">
                <canvas id="loanTrendChart"></canvas>
            </div>
        </div>

        <!-- Patron Group Pie -->
        <div class="bg-card border border-border rounded-md p-3">
            <div class="flex items-center gap-2 mb-3">
                <i data-lucide="pie-chart" class="w-4 h-4 text-muted-foreground"></i>
                <h3 class="text-xs font-bold text-foreground uppercase tracking-wider">{{ __('Phân bố bạn đọc') }}</h3>
            </div>
            <div class="h-52 flex items-center justify-center">
                <canvas id="patronGroupChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Row 4: Book Status + Top Borrowers -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-3">
        <!-- Book Status Distribution -->
        <div class="bg-card border border-border rounded-md p-3">
            <div class="flex items-center gap-2 mb-3">
                <i data-lucide="bar-chart-3" class="w-4 h-4 text-muted-foreground"></i>
                <h3 class="text-xs font-bold text-foreground uppercase tracking-wider">{{ __('Trạng thái bản sách') }}</h3>
            </div>
            <div class="h-52 flex items-center justify-center">
                <canvas id="bookStatusChart"></canvas>
            </div>
        </div>

        <!-- Top Borrowers -->
        <div class="lg:col-span-2 bg-card border border-border rounded-md overflow-hidden">
            <div class="p-3 border-b border-border flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <i data-lucide="trophy" class="w-4 h-4 text-amber-500"></i>
                    <h3 class="text-xs font-bold text-foreground uppercase tracking-wider">{{ __('Top sinh viên mượn nhiều nhất') }}</h3>
                </div>
                <span class="text-[10px] text-muted-foreground bg-muted px-2 py-0.5 rounded-sm">Top {{ $topBorrowers->count() }}</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-xs">
                    <thead>
                        <tr class="bg-muted/50 border-b border-border">
                            <th class="text-left px-3 py-2 font-bold text-muted-foreground uppercase tracking-wider">#</th>
                            <th class="text-left px-3 py-2 font-bold text-muted-foreground uppercase tracking-wider">{{ __('Bạn đọc') }}</th>
                            <th class="text-left px-3 py-2 font-bold text-muted-foreground uppercase tracking-wider">{{ __('Nhóm') }}</th>
                            <th class="text-left px-3 py-2 font-bold text-muted-foreground uppercase tracking-wider">{{ __('MSSV') }}</th>
                            <th class="text-right px-3 py-2 font-bold text-muted-foreground uppercase tracking-wider">{{ __('Lượt mượn') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border">
                        @forelse($topBorrowers as $i => $patron)
                        <tr class="hover:bg-muted/50 transition-colors">
                            <td class="px-3 py-2">
                                @if($i < 3)
                                    <span class="w-5 h-5 rounded-full inline-flex items-center justify-center text-[9px] font-black
                                        {{ $i === 0 ? 'bg-amber-500/20 text-amber-600' : ($i === 1 ? 'bg-slate-400/20 text-slate-500' : 'bg-orange-400/20 text-orange-500') }}">
                                        {{ $i + 1 }}
                                    </span>
                                @else
                                    <span class="text-muted-foreground">{{ $i + 1 }}</span>
                                @endif
                            </td>
                            <td class="px-3 py-2">
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded bg-primary/10 flex items-center justify-center text-primary text-[9px] font-black">
                                        {{ strtoupper(substr($patron->user->name ?? '?', 0, 1)) }}
                                    </div>
                                    <span class="font-bold text-foreground">{{ $patron->user->name ?? $patron->patron_code }}</span>
                                </div>
                            </td>
                            <td class="px-3 py-2 text-muted-foreground">{{ $patron->patronGroup->name ?? '—' }}</td>
                            <td class="px-3 py-2 text-muted-foreground font-mono">{{ $patron->mssv ?? '—' }}</td>
                            <td class="px-3 py-2 text-right">
                                <span class="inline-flex items-center px-1.5 py-0.5 rounded-sm bg-primary/10 text-primary font-bold text-[10px]">
                                    {{ $patron->loan_transactions_count }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-3 py-6 text-center text-muted-foreground">{{ __('Chưa có dữ liệu mượn sách') }}</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Row 5: Recent Loans + Overdue -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-3">
        <!-- Recent Loans -->
        <div class="bg-card border border-border rounded-md overflow-hidden">
            <div class="p-3 border-b border-border flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <i data-lucide="clock" class="w-4 h-4 text-muted-foreground"></i>
                    <h3 class="text-xs font-bold text-foreground uppercase tracking-wider">{{ __('Mượn gần đây') }}</h3>
                </div>
                <a href="{{ route('admin.circulation.loan-desk') }}" class="text-[10px] font-bold text-primary hover:underline uppercase tracking-wider">
                    {{ __('Xem tất cả') }}
                </a>
            </div>
            <div class="divide-y divide-border max-h-80 overflow-y-auto">
                @forelse($recentLoans as $loan)
                @php
                    $title = '—';
                    if ($loan->bookItem && $loan->bookItem->bibliographicRecord) {
                        $titleField = $loan->bookItem->bibliographicRecord->fields->where('tag', '245')->first();
                        if ($titleField) {
                            $titleSub = $titleField->subfields->where('code', 'a')->first();
                            $title = $titleSub ? $titleSub->value : '—';
                        }
                    }
                @endphp
                <div class="px-3 py-2 hover:bg-muted/50 transition-colors flex items-center gap-3">
                    <div class="w-7 h-7 rounded bg-amber-500/10 flex items-center justify-center text-amber-500 flex-shrink-0">
                        <i data-lucide="book-up" class="w-3.5 h-3.5"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-bold text-foreground truncate" title="{{ $title }}">{{ Str::limit($title, 40) }}</p>
                        <p class="text-[10px] text-muted-foreground">{{ $loan->patron->user->name ?? $loan->patron->patron_code ?? '—' }}</p>
                    </div>
                    <div class="text-right flex-shrink-0">
                        <p class="text-[10px] text-muted-foreground">{{ $loan->loan_date?->format('d/m/Y') }}</p>
                        <span class="inline-flex px-1.5 py-0.5 rounded-sm text-[9px] font-bold uppercase
                            {{ $loan->status === 'borrowed' ? 'bg-amber-500/10 text-amber-600' : 'bg-emerald-500/10 text-emerald-600' }}">
                            {{ $loan->status === 'borrowed' ? 'Đang mượn' : 'Đã trả' }}
                        </span>
                    </div>
                </div>
                @empty
                <div class="px-3 py-6 text-center text-muted-foreground text-xs">{{ __('Chưa có phiên mượn nào') }}</div>
                @endforelse
            </div>
        </div>

        <!-- Overdue List -->
        <div class="bg-card border border-border rounded-md overflow-hidden {{ $overdueLoans > 0 ? 'border-destructive/30' : '' }}">
            <div class="p-3 border-b border-border flex items-center justify-between {{ $overdueLoans > 0 ? 'bg-destructive/5' : '' }}">
                <div class="flex items-center gap-2">
                    <i data-lucide="alert-triangle" class="w-4 h-4 {{ $overdueLoans > 0 ? 'text-destructive' : 'text-muted-foreground' }}"></i>
                    <h3 class="text-xs font-bold {{ $overdueLoans > 0 ? 'text-destructive' : 'text-foreground' }} uppercase tracking-wider">
                        {{ __('Sách quá hạn') }}
                        @if($overdueLoans > 0)
                            <span class="ml-1 px-1.5 py-0.5 rounded-sm bg-destructive text-destructive-foreground text-[9px]">{{ $overdueLoans }}</span>
                        @endif
                    </h3>
                </div>
            </div>
            <div class="divide-y divide-border max-h-80 overflow-y-auto">
                @forelse($overdueList as $loan)
                @php
                    $title = '—';
                    if ($loan->bookItem && $loan->bookItem->bibliographicRecord) {
                        $titleField = $loan->bookItem->bibliographicRecord->fields->where('tag', '245')->first();
                        if ($titleField) {
                            $titleSub = $titleField->subfields->where('code', 'a')->first();
                            $title = $titleSub ? $titleSub->value : '—';
                        }
                    }
                    $overdueDays = $loan->getOverdueDays();
                @endphp
                <div class="px-3 py-2 hover:bg-muted/50 transition-colors flex items-center gap-3">
                    <div class="w-7 h-7 rounded bg-destructive/10 flex items-center justify-center text-destructive flex-shrink-0">
                        <i data-lucide="alert-circle" class="w-3.5 h-3.5"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-bold text-foreground truncate" title="{{ $title }}">{{ Str::limit($title, 35) }}</p>
                        <p class="text-[10px] text-muted-foreground">{{ $loan->patron->user->name ?? '—' }}</p>
                    </div>
                    <div class="text-right flex-shrink-0">
                        <p class="text-[10px] text-destructive font-bold">Quá {{ $overdueDays }} ngày</p>
                        <p class="text-[10px] text-muted-foreground">Hạn: {{ $loan->due_date?->format('d/m') }}</p>
                    </div>
                </div>
                @empty
                <div class="px-3 py-6 text-center text-xs">
                    <div class="w-10 h-10 rounded-full bg-emerald-500/10 flex items-center justify-center mx-auto mb-2">
                        <i data-lucide="check-circle" class="w-5 h-5 text-emerald-500"></i>
                    </div>
                    <p class="text-muted-foreground">{{ __('Không có sách quá hạn') }}</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Row 6: Pending Reservations + Latest Users -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-3">
        <!-- Pending Reservations -->
        <div class="bg-card border border-border rounded-md overflow-hidden">
            <div class="p-3 border-b border-border flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <i data-lucide="bookmark" class="w-4 h-4 text-violet-500"></i>
                    <h3 class="text-xs font-bold text-foreground uppercase tracking-wider">{{ __('Yêu cầu mượn đang chờ') }}</h3>
                </div>
                @if($pendingReservations > 0)
                    <span class="px-1.5 py-0.5 rounded-sm bg-violet-500/10 text-violet-600 text-[10px] font-bold">{{ $pendingReservations }}</span>
                @endif
            </div>
            <div class="divide-y divide-border max-h-64 overflow-y-auto">
                @forelse($pendingResList as $res)
                @php
                    $resTitle = '—';
                    if ($res->bibliographicRecord) {
                        $tf = $res->bibliographicRecord->fields->where('tag', '245')->first();
                        if ($tf) {
                            $ts = $tf->subfields->where('code', 'a')->first();
                            $resTitle = $ts ? $ts->value : '—';
                        }
                    }
                @endphp
                <div class="px-3 py-2 hover:bg-muted/50 transition-colors flex items-center gap-3">
                    <div class="w-7 h-7 rounded bg-violet-500/10 flex items-center justify-center text-violet-500 flex-shrink-0">
                        <i data-lucide="clock" class="w-3.5 h-3.5"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-bold text-foreground truncate">{{ Str::limit($resTitle, 35) }}</p>
                        <p class="text-[10px] text-muted-foreground">{{ $res->patron->user->name ?? '—' }}</p>
                    </div>
                    <div class="text-right flex-shrink-0">
                        <span class="inline-flex px-1.5 py-0.5 rounded-sm bg-amber-500/10 text-amber-600 text-[9px] font-bold uppercase">Chờ duyệt</span>
                        <p class="text-[10px] text-muted-foreground mt-0.5">{{ $res->reservation_date?->format('d/m/Y') }}</p>
                    </div>
                </div>
                @empty
                <div class="px-3 py-6 text-center text-xs text-muted-foreground">{{ __('Không có yêu cầu đang chờ') }}</div>
                @endforelse
            </div>
        </div>

        <!-- Latest Users -->
        <div class="bg-card border border-border rounded-md overflow-hidden">
            <div class="p-3 border-b border-border flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <i data-lucide="user-plus" class="w-4 h-4 text-muted-foreground"></i>
                    <h3 class="text-xs font-bold text-foreground uppercase tracking-wider">{{ __('Tài khoản mới') }}</h3>
                </div>
                <a href="{{ route('admin.users.index') }}" class="text-[10px] font-bold text-primary hover:underline uppercase tracking-wider">
                    {{ __('Xem tất cả') }}
                </a>
            </div>
            <div class="divide-y divide-border">
                @forelse($latestUsers as $user)
                <div class="px-3 py-2 hover:bg-muted/50 transition-colors flex items-center gap-3">
                    <div class="w-7 h-7 rounded bg-primary/10 flex items-center justify-center text-primary text-[10px] font-black flex-shrink-0">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-bold text-foreground truncate">{{ $user->name }}</p>
                        <p class="text-[10px] text-muted-foreground truncate">{{ $user->email ?? $user->username }}</p>
                    </div>
                    <div class="text-right flex-shrink-0">
                        <div class="flex flex-wrap justify-end gap-1">
                            @foreach($user->roles as $role)
                                <span class="px-1.5 py-0.5 rounded-sm bg-primary/10 text-primary text-[9px] font-bold">{{ $role->name }}</span>
                            @endforeach
                        </div>
                        <p class="text-[10px] text-muted-foreground mt-0.5">{{ $user->created_at?->format('d/m/Y') }}</p>
                    </div>
                </div>
                @empty
                <div class="px-3 py-6 text-center text-xs text-muted-foreground">{{ __('Chưa có tài khoản nào') }}</div>
                @endforelse
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const isDark = document.documentElement.classList.contains('dark');
    const gridColor = isDark ? 'rgba(255,255,255,0.06)' : 'rgba(0,0,0,0.06)';
    const textColor = isDark ? 'rgba(255,255,255,0.5)' : 'rgba(0,0,0,0.4)';

    Chart.defaults.font.family = "'Instrument Sans', sans-serif";
    Chart.defaults.font.size = 10;
    Chart.defaults.color = textColor;

    // ── Loan Trend Chart ──
    const loanData = @json($loanTrend);
    new Chart(document.getElementById('loanTrendChart'), {
        type: 'bar',
        data: {
            labels: loanData.map(d => d.label),
            datasets: [
                {
                    label: 'Mượn',
                    data: loanData.map(d => d.borrowed),
                    backgroundColor: isDark ? 'rgba(99,102,241,0.6)' : 'rgba(99,102,241,0.7)',
                    borderRadius: 3,
                    barPercentage: 0.6,
                    categoryPercentage: 0.7,
                },
                {
                    label: 'Trả',
                    data: loanData.map(d => d.returned),
                    backgroundColor: isDark ? 'rgba(16,185,129,0.6)' : 'rgba(16,185,129,0.7)',
                    borderRadius: 3,
                    barPercentage: 0.6,
                    categoryPercentage: 0.7,
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                x: { grid: { display: false }, ticks: { font: { size: 9 } } },
                y: { grid: { color: gridColor }, beginAtZero: true, ticks: { stepSize: 1, font: { size: 9 } } }
            }
        }
    });

    // ── Patron Group Doughnut ──
    const pgData = @json($patronGroups);
    const pgColors = ['#6366f1','#10b981','#f59e0b','#ef4444','#8b5cf6','#06b6d4','#ec4899','#f97316'];
    if (pgData.length > 0) {
        new Chart(document.getElementById('patronGroupChart'), {
            type: 'doughnut',
            data: {
                labels: pgData.map(d => d.name),
                datasets: [{
                    data: pgData.map(d => d.count),
                    backgroundColor: pgColors.slice(0, pgData.length),
                    borderWidth: 0,
                    hoverOffset: 6,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '65%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { padding: 10, boxWidth: 8, boxHeight: 8, font: { size: 9 } }
                    }
                }
            }
        });
    } else {
        document.getElementById('patronGroupChart').parentElement.innerHTML = '<p class="text-xs text-muted-foreground text-center">Chưa có dữ liệu</p>';
    }

    // ── Book Status Chart ──
    const bsData = @json($bookStatusDist);
    const statusLabels = {
        'available': 'Sẵn sàng', 'borrowed': 'Đang mượn', 'reserved': 'Đã đặt',
        'lost': 'Mất', 'damaged': 'Hư hỏng', 'processing': 'Đang xử lý',
        'in_transit': 'Đang vận chuyển', 'withdrawn': 'Thanh lý'
    };
    const statusColors = {
        'available': '#10b981', 'borrowed': '#f59e0b', 'reserved': '#8b5cf6',
        'lost': '#ef4444', 'damaged': '#f97316', 'processing': '#06b6d4',
        'in_transit': '#3b82f6', 'withdrawn': '#6b7280'
    };
    const bsKeys = Object.keys(bsData);
    if (bsKeys.length > 0) {
        new Chart(document.getElementById('bookStatusChart'), {
            type: 'doughnut',
            data: {
                labels: bsKeys.map(k => statusLabels[k] || k),
                datasets: [{
                    data: bsKeys.map(k => bsData[k]),
                    backgroundColor: bsKeys.map(k => statusColors[k] || '#6b7280'),
                    borderWidth: 0,
                    hoverOffset: 6,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '65%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { padding: 10, boxWidth: 8, boxHeight: 8, font: { size: 9 } }
                    }
                }
            }
        });
    } else {
        document.getElementById('bookStatusChart').parentElement.innerHTML = '<p class="text-xs text-muted-foreground text-center">Chưa có dữ liệu</p>';
    }
});
</script>
@endpush
@endsection