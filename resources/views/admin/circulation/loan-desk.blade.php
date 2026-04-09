@extends('layouts.admin')

@section('content')
<div class="space-y-8">
    @if(session('success'))
        <div class="bg-green-900/20 border border-green-500 text-green-400 p-4 text-xs font-mono rounded">
            [OK] {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-900/20 border border-red-500 text-red-400 p-4 text-xs font-mono rounded">
            [ERROR] {{ session('error') }}
        </div>
    @endif

    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold">{{ __('Loan Desk') }}</h1>
            <p class="text-sm text-gray-400 mt-1">{{ __('Circulation Management - Loan, Return, Reading Room, Hold') }}</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.circulation.reports.index') }}" class="btn-secondary">
                <i class="fas fa-chart-bar mr-2"></i>{{ __('Reports') }}
            </a>
            <a href="{{ route('admin.circulation.tools') }}" class="btn-secondary">
                <i class="fas fa-tools mr-2"></i>{{ __('Tools') }}
            </a>
            <a href="{{ route('admin.circulation.policies.index') }}" class="btn-secondary">
                <i class="fas fa-cog mr-2"></i>{{ __('Policies') }}
            </a>
        </div>
    </div>

    <!-- Tabs Navigation -->
    <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-gray-100 dark:border-slate-800">
        <div class="flex border-b border-gray-200 dark:border-slate-700">
            <div class="flex space-x-1 mb-6">
                <button type="button" onclick="switchTab('checkout')" id="checkoutTab" 
                        class="px-6 py-3 text-sm font-medium rounded-t-lg transition-all duration-200 border-b-2 border-green-500 text-green-600 bg-green-50 dark:bg-green-900/20 dark:text-green-400">
                    <i class="fas fa-arrow-right mr-2"></i>{{ __('Mượn sách') }} ({{ __('Loan') }})
                </button>
                <button type="button" onclick="switchTab('checkin')" id="checkinTab"
                        class="px-6 -mb-px py-3 text-sm font-medium rounded-t-lg transition-all duration-200 border-b-2 border-transparent text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200">
                    <i class="fas fa-arrow-left mr-2"></i>{{ __('Trả sách') }} ({{ __('Return') }})
                </button>
                <button type="button" onclick="switchTab('reading-room')" id="readingRoomTab"
                        class="px-6 -mb-px py-3 text-sm font-medium rounded-t-lg transition-all duration-200 border-b-2 border-transparent text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200">
                    <i class="fas fa-book-reader mr-2"></i>{{ __('Mượn đọc') }}
                </button>
                <button type="button" onclick="switchTab('hold')" id="holdTab"
                        class="px-6 -mb-px py-3 text-sm font-medium rounded-t-lg transition-all duration-200 border-b-2 border-transparent text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200">
                    <i class="fas fa-bookmark mr-2"></i>{{ __('Giữ lại') }}
                </button>
            </div>
        </div>

        <!-- Tab Content -->
        <div class="p-6">
            <!-- Checkout Tab -->
            <div id="checkoutContent" class="space-y-6">
                <!-- Two Column Layout -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Left Column: Checkout Form -->
                    <div>
                        <h3 class="text-lg font-bold mb-4 text-green-400">{{ __('Checkout') }} ({{ __('Loan') }})</h3>
                        <form action="{{ route('admin.circulation.checkout') }}" method="POST" class="space-y-4">
                            @csrf
                            <div class="relative">
                                <label class="block text-sm font-medium mb-1">{{ __('Patron_Code') }} *</label>
                                <div class="relative">
                                    <input type="text" id="patron_code" name="patron_code" required class="input-field w-full pr-10" 
                                        placeholder="{{ __('Scan_or_enter_patron_code') }}" autofocus>
                                    <button type="button" onclick="searchPatronByCode(document.getElementById('patron_code').value)" 
                                        class="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-blue-400 transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            <div class="relative">
                                <label class="block text-sm font-medium mb-1">{{ __('Book_Barcode') }} *</label>
                                <div class="relative">
                                    <input type="text" id="book_barcode" name="barcode" required class="input-field w-full pr-10" 
                                        placeholder="{{ __('Scan_or_enter_barcode') }}">
                                    <button type="button" onclick="searchBook()" 
                                        class="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-blue-400 transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white py-3 rounded font-bold">
                                {{ __('Cho mượn sách') }}
                            </button>
                        </form>
                    </div>

                    <!-- Right Column: Patron & Book Information -->
                    <div class="space-y-4">
                        @include('admin.circulation.components.patron-info', ['id' => 'patronInfo'])
                        @include('admin.circulation.components.book-info', ['id' => 'bookSearchResult'])
                    </div>
                </div>
            </div>

            <!-- Checkin Tab -->
            <div id="checkinContent" class="space-y-6 hidden">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Left Column: Checkin Form -->
                    <div>
                        <h3 class="text-lg font-bold mb-4 text-blue-400">{{ __('Checkin') }} ({{ __('Return') }})</h3>
                        <form action="{{ route('admin.circulation.checkin') }}" method="POST" class="space-y-4">
                            @csrf
                            <div class="relative">
                                <label class="block text-sm font-medium mb-1">{{ __('Book_Barcode') }} *</label>
                                <div class="relative">
                                    <input type="text" id="checkin_book_barcode" name="barcode" required class="input-field w-full pr-10" 
                                        placeholder="{{ __('Scan_or_enter_barcode') }}" onchange="searchCheckinBook()">
                                    <button type="button" onclick="searchCheckinBook()" 
                                        class="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-blue-400 transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded font-bold">
                                {{ __('Nhận trả sách') }}
                            </button>
                        </form>
                    </div>
                    
                    <!-- Right Column: Book Information Display -->
                    <div>
                        <h3 class="text-lg font-bold mb-4 text-blue-400">{{ __('Book_Information') }}</h3>
                        <div id="checkinBookInfo" class="card-admin p-4 min-h-[200px]">
                            <div class="text-center text-gray-500 py-8">
                                <svg class="w-12 h-12 mx-auto mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                                <p class="text-sm">{{ __('Nhập mã vạch sách để hiển thị thông tin') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Reading Room Tab -->
            <div id="readingRoomContent" class="space-y-6 hidden">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Left Column: Reading Room Form -->
                    <div>
                        <h3 class="text-lg font-bold mb-4 text-purple-400">{{ __('Mượn đọc tại chỗ') }}</h3>
                        <form id="readingRoomForm" class="space-y-4">
                            @csrf
                            <div class="relative">
                                <label class="block text-sm font-medium mb-1">{{ __('Patron_Code') }} *</label>
                                <div class="relative">
                                    <input type="text" id="reading_patron_code" name="patron_code" required class="input-field w-full pr-10" 
                                        placeholder="{{ __('Scan_or_enter_patron_code') }}" onchange="loadReadingRoomTransactions()">
                                    <button type="button" onclick="searchPatronByCode(document.getElementById('reading_patron_code').value)" 
                                        class="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-blue-400 transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            <div class="relative">
                                <label class="block text-sm font-medium mb-1">{{ __('Book_Barcode') }} *</label>
                                <div class="relative">
                                    <input type="text" id="reading_book_barcode" name="barcode" required class="input-field w-full pr-10" 
                                        placeholder="{{ __('Scan_or_enter_barcode') }}">
                                    <button type="button" onclick="searchBookByBarcode(document.getElementById('reading_book_barcode').value)" 
                                        class="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-blue-400 transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1">{{ __('Ghi chú') }}</label>
                                <textarea id="reading_notes" name="notes" rows="2" class="input-field w-full" 
                                    placeholder="{{ __('Nhập ghi chú (không bắt buộc)') }}"></textarea>
                            </div>
                            <button type="button" onclick="processReadingRoomCheckout()" 
                                    class="w-full bg-purple-600 hover:bg-purple-700 text-white py-3 rounded font-bold">
                                <i class="fas fa-book-reader mr-2"></i>{{ __('Mượn đọc tại chỗ') }}
                            </button>
                        </form>
                    </div>

                    <!-- Right Column: Patron Info & Active Reading Room Transactions -->
                    <div>
                        @include('admin.circulation.components.patron-info', ['id' => 'readingPatronInfo'])
                        @include('admin.circulation.components.book-info', ['id' => 'readingBookInfo'])
                        
                        <h3 class="text-lg font-bold mb-4 text-purple-400">{{ __('Tài liệu đang mượn đọc') }}</h3>
                        <div id="readingRoomTransactions" class="card-admin p-4 min-h-[300px]">
                            <div class="text-center text-gray-500 py-8">
                                <svg class="w-12 h-12 mx-auto mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                                <p class="text-sm">{{ __('Nhập mã bạn đọc để xem tài liệu đang mượn đọc') }}</p>
                            </div>
                        </div>
                        
                        <!-- Return Selected Button -->
                        <div class="mt-4">
                            <button type="button" onclick="processReadingRoomCheckin()" 
                                    class="w-full bg-red-600 hover:bg-red-700 text-white py-3 rounded font-bold disabled:opacity-50 disabled:cursor-not-allowed" 
                                    id="returnReadingRoomBtn" disabled>
                                <i class="fas fa-undo mr-2"></i>{{ __('Trả các tài liệu đã chọn') }}
                            </button>
                        </div>
                    </div>
                </div>

                <!-- All Active Reading Room Transactions (Staff View) -->
                <div class="mt-6">
                    <h3 class="text-lg font-bold mb-4 text-purple-400">{{ __('Tất cả tài liệu đang mượn đọc') }}</h3>
                    <div id="allReadingRoomTransactions" class="card-admin p-4">
                        <div class="text-center text-gray-500 py-4">
                            <p class="text-sm">{{ __('Đang tải danh sách...') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Hold/Reserve Tab -->
            <div id="holdContent" class="space-y-6 hidden">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Left Column: Hold Form -->
                    <div>
                        <h3 class="text-lg font-bold mb-4 text-orange-400">{{ __('Giữ lại sách (Hold/Reserve)') }}</h3>
                        <form id="holdForm" class="space-y-4">
                            @csrf
                            <div class="relative">
                                <label class="block text-sm font-medium mb-1">{{ __('Patron_Code') }} *</label>
                                <div class="relative">
                                    <input type="text" id="hold_patron_code" name="patron_code" required class="input-field w-full pr-10" 
                                        placeholder="{{ __('Scan_or_enter_patron_code') }}" onchange="loadPatronReservations()">
                                    <button type="button" onclick="searchPatronByCode(document.getElementById('hold_patron_code').value)" 
                                        class="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-blue-400 transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            <div class="relative">
                                <label class="block text-sm font-medium mb-1">{{ __('Book_Barcode') }} *</label>
                                <div class="relative">
                                    <input type="text" id="hold_book_barcode" name="barcode" required class="input-field w-full pr-10" 
                                        placeholder="{{ __('Scan_or_enter_barcode') }}">
                                    <button type="button" onclick="searchBook()" 
                                        class="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-blue-400 transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1">{{ __('Ghi chú') }}</label>
                                <textarea id="hold_notes" name="notes" rows="2" class="input-field w-full" 
                                    placeholder="{{ __('Nhập ghi chú (không bắt buộc)') }}"></textarea>
                            </div>
                            <button type="button" onclick="processPlaceHold()" 
                                    class="w-full bg-orange-600 hover:bg-orange-700 text-white py-3 rounded font-bold">
                                <i class="fas fa-bookmark mr-2"></i>{{ __('Giữ lại sách') }}
                            </button>
                        </form>
                    </div>

                    <!-- Right Column: Patron Info & Active Reservations -->
                    <div>
                        @include('admin.circulation.components.patron-info', ['id' => 'holdPatronInfo'])
                        
                        <h3 class="text-lg font-bold mb-4 text-orange-400">{{ __('Reservations đang hoạt động') }}</h3>
                        <div id="patronReservations" class="card-admin p-4 min-h-[300px]">
                            <div class="text-center text-gray-500 py-8">
                                <svg class="w-12 h-12 mx-auto mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path>
                                </svg>
                                <p class="text-sm">{{ __("Nhập mã bạn đọc để xem reservations") }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- All Active Reservations (Staff View) -->
                <div class="mt-6">
                    <h3 class="text-lg font-bold mb-4 text-orange-400">{{ __('Tất cả Reservations đang hoạt động') }}</h3>
                    <div id="allReservations" class="card-admin p-4">
                        <div class="text-center text-gray-500 py-4">
                            <p class="text-sm">{{ __("Đang tải danh sách...") }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Overdue Loans Alert -->
    @if($overdueLoans->count() > 0)
    <div class="card-admin rounded-lg overflow-hidden border-l-4 border-red-500">
        <div class="p-4 bg-red-900/20">
            <h3 class="text-lg font-bold text-red-400">{{ __('Overdue_Loans') }} ({{ $overdueLoans->count() }})</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-800 text-xs uppercase">
                    <tr>
                        <th class="p-3 text-left">{{ __('Patron') }}</th>
                        <th class="p-3 text-left">{{ __('Book') }}</th>
                        <th class="p-3 text-left">{{ __('Due_Date') }}</th>
                        <th class="p-3 text-left">{{ __('Overdue_Days') }}</th>
                        <th class="p-3 text-left">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700">
                    @foreach($overdueLoans as $loan)
                    <tr class="hover:bg-gray-800/50">
                        <td class="p-3">
                            <div class="font-medium">{{ $loan->patron->display_name ?? $loan->patron->user->name }}</div>
                            <div class="text-xs text-gray-400">{{ $loan->patron->patron_code }}</div>
                        </td>
                        <td class="p-3">
                            <div class="font-medium">{{ $loan->bookItem->bibliographicRecord->title ?? 'N/A' }}</div>
                            <div class="text-xs text-gray-400 font-mono">{{ $loan->bookItem->barcode }}</div>
                        </td>
                        <td class="p-3 text-red-400">{{ $loan->due_date->format('d/m/Y') }}</td>
                        <td class="p-3">
                            <span class="bg-red-900/50 text-red-400 px-2 py-1 rounded text-xs font-bold">
                                {{ $loan->getOverdueDays() }} {{ __('days') }}
                            </span>
                        </td>
                        <td class="p-3">
                            <div class="flex space-x-2">
                                <form action="{{ route('admin.circulation.renew', $loan) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-blue-400 hover:text-blue-300 text-xs" 
                                        {{ !$loan->canRenew() ? 'disabled' : '' }}>
                                        {{ __('Renew') }}
                                    </button>
                                </form>
                                <button onclick="recallLoanTransaction('{{ $loan->bookItem->barcode }}', '{{ $loan->bookItem->bibliographicRecord->title }}')" 
                                        class="text-yellow-400 hover:text-yellow-300 text-xs" 
                                        title="{{ __("Triệu hồi") }}">
                                    <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                                    </svg>
                                </button>
                                <button onclick="declareLostLoanTransaction('{{ $loan->bookItem->barcode }}', '{{ $loan->bookItem->bibliographicRecord->title }}')" 
                                        class="text-red-400 hover:text-red-300 text-xs" 
                                        title="{{ __("Khai báo mất") }}">
                                    <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <!-- Recent Transactions -->
    <div class="card-admin rounded-lg overflow-hidden">
        <div class="p-4 border-b border-gray-700">
            <h3 class="text-lg font-bold">{{ __("Hành động gần đây") }}</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-800 text-xs uppercase">
                    <tr>
                        <th class="p-3 text-left">{{ __('Date') }}</th>
                        <th class="p-3 text-left">{{ __('Patron') }}</th>
                        <th class="p-3 text-left">{{ __('Book') }}</th>
                        <th class="p-3 text-left">{{ __('Due_Date') }}</th>
                        <th class="p-3 text-left">{{ __('Status') }}</th>
                        <th class="p-3 text-left">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700">
                    @forelse($recentLoans as $loan)
                    <tr class="hover:bg-gray-800/50">
                        <td class="p-3 text-xs text-gray-400">{{ $loan->loan_date->format('d/m/Y H:i') }}</td>
                        <td class="p-3">
                            <div class="font-medium">{{ $loan->patron->display_name ?? $loan->patron->user->name }}</div>
                            <div class="text-xs text-gray-400">{{ $loan->patron->patron_code }}</div>
                        </td>
                        <td class="p-3">
                            <div class="font-medium">{{ $loan->bookItem->bibliographicRecord->title ?? 'N/A' }}</div>
                            <div class="text-xs text-gray-400 font-mono">{{ $loan->bookItem->barcode }}</div>
                        </td>
                        <td class="p-3 {{ $loan->isOverdue() ? 'text-red-400' : '' }}">
                            {{ $loan->due_date->format('d/m/Y') }}
                        </td>
                        <td class="p-3">
                            @php
                                $statusClass = match($loan->status) {
                                    'borrowed' => 'bg-yellow-900/50 text-yellow-400',
                                    'returned' => 'bg-green-900/50 text-green-400',
                                    'overdue' => 'bg-red-900/50 text-red-400',
                                    'lost' => 'bg-gray-900/50 text-gray-400',
                                    default => 'bg-gray-900/50 text-gray-400'
                                };
                            @endphp
                            <span class="px-2 py-1 rounded text-xs font-bold {{ $statusClass }}">
                                {{ __(ucfirst($loan->status)) }}
                            </span>
                        </td>
                        <td class="p-3">
                            @if($loan->status === 'borrowed')
                                <div class="flex items-center space-x-2">
                                    @if($loan->canRenew())
                                    <form action="{{ route('admin.circulation.renew', $loan) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="text-blue-400 hover:text-blue-300 text-xs">
                                            {{ __('Renew') }}
                                        </button>
                                    </form>
                                    @endif
                                    <span class="text-xs text-gray-500">({{ $loan->renewal_count }}/{{ $loan->policy->max_renewals ?? '?' }})</span>
                                    <button onclick="recallLoanTransaction('{{ $loan->bookItem->barcode }}', '{{ $loan->bookItem->bibliographicRecord->title }}')" 
                                            class="text-yellow-400 hover:text-yellow-300 text-xs" 
                                            title="{{ __("Triệu hồi") }}">
                                        <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                                        </svg>
                                    </button>
                                    <button onclick="declareLostLoanTransaction('{{ $loan->bookItem->barcode }}', '{{ $loan->bookItem->bibliographicRecord->title }}')" 
                                            class="text-red-400 hover:text-red-300 text-xs" 
                                            title="{{ __("Khai báo mất") }}">
                                        <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                        </svg>
                                    </button>
                                </div>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="p-8 text-center text-gray-500">{{ __('No_recent_transactions') }}</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
// Lock history and loan data from controller
const lockHistoryData = @json($allLockHistory ?? []);
const loanTransactionsData = @json($allLoanTransactions ?? []);

// Tab switching function
function switchTab(tabName) {
    // Hide all tab contents
    document.getElementById('checkoutContent').classList.add('hidden');
    document.getElementById('checkinContent').classList.add('hidden');
    
    // Remove active state from all tabs
    document.getElementById('checkoutTab').classList.remove('bg-blue-600', 'text-white');
    document.getElementById('checkoutTab').classList.add('bg-gray-700', 'text-gray-300');
    document.getElementById('checkinTab').classList.remove('bg-blue-600', 'text-white');
    document.getElementById('checkinTab').classList.add('bg-gray-700', 'text-gray-300');
    
    // Show selected tab and set active state
    if (tabName === 'checkout') {
        document.getElementById('checkoutContent').classList.remove('hidden');
        document.getElementById('checkoutTab').classList.remove('bg-gray-700', 'text-gray-300');
        document.getElementById('checkoutTab').classList.add('bg-blue-600', 'text-white');
    } else if (tabName === 'checkin') {
        document.getElementById('checkinContent').classList.remove('hidden');
        document.getElementById('checkinTab').classList.remove('bg-gray-700', 'text-gray-300');
        document.getElementById('checkinTab').classList.add('bg-blue-600', 'text-white');
    }
}

// Search timeout variables
let patronSearchTimeout;
let bookSearchTimeout;

// Patron search with 0.5s delay
document.getElementById('patron_code').addEventListener('input', function() {
    clearTimeout(patronSearchTimeout);
    const value = this.value.trim();
    
    if (value.length >= 2) {
        patronSearchTimeout = setTimeout(() => {
            searchPatronByCode(value);
        }, 500);
    } else {
        // Clear patron info when input is empty
        const infoDiv = document.getElementById('patronInfo');
        if (infoDiv) {
            infoDiv.innerHTML = `
                <div class="text-center text-gray-500 text-sm py-8">
                    <svg class="w-12 h-12 mx-auto mb-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    <p>{{ __('Nhập mã bạn đọc để hiển thị thông tin') }}</p>
                </div>
            `;
        }
    }
});

// Book search with 0.5s delay
document.getElementById('book_barcode').addEventListener('input', function() {
    clearTimeout(bookSearchTimeout);
    const value = this.value.trim();
    
    if (value.length >= 2) {
        bookSearchTimeout = setTimeout(() => {
            searchBookByBarcode(value);
        }, 500);
    } else {
        // Clear book info when input is empty
        const resultDiv = document.getElementById('bookSearchResult');
        if (resultDiv) {
            resultDiv.innerHTML = `
                <div class="text-center text-gray-500 text-sm py-8">
                    <svg class="w-12 h-12 mx-auto mb-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                    <p>{{ __('Nhập mã vạch sách để hiển thị thông tin') }}</p>
                </div>
            `;
        }
    }
});

// Checkin book search with 0.5s delay
document.getElementById('checkin_book_barcode').addEventListener('input', function() {
    clearTimeout(bookSearchTimeout);
    const value = this.value.trim();
    
    if (value.length >= 2) {
        bookSearchTimeout = setTimeout(() => {
            searchCheckinBook();
        }, 500);
    } else {
        // Clear checkin book info when input is empty
        const infoDiv = document.getElementById('checkinBookInfo');
        if (infoDiv) {
            infoDiv.innerHTML = `
                <div class="text-center text-gray-500 text-sm py-8">
                    <svg class="w-12 h-12 mx-auto mb-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                    <p>{{ __('Nhập mã vạch sách để hiển thị thông tin') }}</p>
                </div>
            `;
        }
    }
});

// Manual search functions
function triggerPatronSearch() {
    const code = document.getElementById('patronCode').value.trim();
    if (code) {
        searchPatronByCode(code);
    }
}

function triggerBookSearch() {
    const barcode = document.getElementById('bookBarcode').value.trim();
    if (barcode) {
        searchBookByBarcode(barcode);
    }
}

function handlePatronInput(event) {
    const value = event.target.value.trim();
    if (value.length >= 3) {
        debouncedPatronSearch(value);
    }
}

function handleBookInput(event) {
    const value = event.target.value.trim();
    if (value.length >= 2) {
        searchBookByBarcode(value);
    }
}

// AJAX search functions
function searchPatronByCode(code) {
    const url = `{{ route('admin.circulation.search-patron') }}?code=${encodeURIComponent(code)}`;
    
    fetch(url)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            displayPatronResult(data);
        })
        .catch(error => {
            displayPatronError();
        });
}

function searchBookByBarcode(barcode) {
    const url = `{{ route('admin.circulation.search-book') }}?barcode=${encodeURIComponent(barcode)}`;
    
    fetch(url)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            displayBookResult(data);
        })
        .catch(error => {
            displayBookError();
        });
}

function searchCheckinBook() {
    const barcode = document.getElementById('checkin_book_barcode').value.trim();
    if (!barcode) {
        return;
    }
    
    const url = `{{ route('admin.circulation.search-book') }}?barcode=${encodeURIComponent(barcode)}`;
    
    fetch(url)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            displayCheckinBookResult(data);
        })
        .catch(error => {
            displayCheckinBookError();
        });
}

function displayCheckinBookResult(book) {
    const infoDiv = document.getElementById('checkinBookInfo');
    
    if (book.success) {
        // Debug: Log the actual status to understand the API response
        console.log('Book status:', book.data.status);
        console.log('Book data:', book.data);
        console.log('Current loan:', book.data.current_loan);
        console.log('Patron data:', book.data.patron);
        
        // Check multiple possible status values for "book is on loan"
        const isOnLoan = book.data.status === 'borrowed' || 
                        book.data.status === 'Đã cho mượn' || 
                        book.data.status === 'checked_out' ||
                        (book.data.current_loan && book.data.current_loan.patron_name);
        
        if (isOnLoan) {
            // Book is on loan - show full loan information
            infoDiv.innerHTML = `
                <div class="space-y-4">
                    <!-- Book Header with Cover -->
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0">
                            ${book.data.cover_image ? 
                                `<img src="${book.data.cover_image}" alt="${book.data.title || 'Book cover'}" class="w-20 h-28 object-cover rounded-lg shadow-lg">` :
                                `<div class="w-20 h-28 bg-gray-600 rounded-lg flex items-center justify-center">
                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                    </svg>
                                </div>`
                            }
                        </div>
                        <div class="flex-1">
                            <h4 class="font-semibold text-white text-lg">
                                ${book.data.title || 'N/A'}
                            </h4>
                            <p class="text-sm text-gray-400 mt-1">
                                {{ __("Barcode") }}: <span class="font-mono text-blue-400">${book.data.barcode}</span>
                            </p>
                            <div class="mt-2">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-orange-100 dark:bg-orange-900/50 text-orange-800 dark:text-orange-300">
                                    {{ __("On_Loan") }}
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Book Details -->
                    <div class="bg-gray-700/30 rounded-lg p-3 space-y-2">
                        <div class="flex justify-between">
                            <span class="text-gray-400 text-sm">{{ __("Author") }}:</span>
                            <span class="text-white text-sm">${book.data.author || '{{ __("N/A") }}'}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-400 text-sm">{{ __("Call Number") }}:</span>
                            <span class="text-white text-sm font-mono">${book.data.call_number || '{{ __("N/A") }}'}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-400 text-sm">{{ __("Status") }}:</span>
                            <span class="text-sm font-medium text-orange-400">
                                {{ __("On_Loan") }}
                            </span>
                        </div>
                    </div>
                    
                    <!-- Loan Information -->
                    <div class="bg-blue-900/20 border border-blue-700/50 rounded-lg p-3">
                        <h5 class="text-sm font-semibold text-blue-400 mb-2">{{ __("Current_Loan_Information") }}</h5>
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span class="text-gray-400 text-sm">{{ __("On_loan_to") }}:</span>
                                <span class="text-white text-sm font-medium">${book.data.current_loan?.patron_name || 'N/A'}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-400 text-sm">{{ __("Patron_Code") }}:</span>
                                <span class="text-white text-sm font-mono">${book.data.current_loan?.patron_code || 'N/A'}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-400 text-sm">{{ __("Due_Date") }}:</span>
                                <span class="text-sm font-medium ${book.data.current_loan?.is_overdue ? 'text-red-400' : 'text-yellow-400'}">
                                    ${book.data.current_loan?.due_date || 'N/A'}
                                </span>
                            </div>
                            ${book.data.current_loan?.is_overdue ? `
                                <div class="flex justify-between">
                                    <span class="text-gray-400 text-sm">{{ __("Overdue_Days") }}:</span>
                                    <span class="text-red-400 text-sm font-bold">${book.data.current_loan.overdue_days} {{ __("days") }}</span>
                                </div>
                            ` : ''}
                        </div>
                    </div>
                    
                    <!-- Action Status -->
                    <div class="text-center">
                        <div class="text-green-400 text-sm"><i class="fas fa-check-circle mr-1"></i> {{ __("Ready_for_checkin") }}</div>
                    </div>
                </div>
            `;
        } else {
            // Book is not on loan - show simplified message
            infoDiv.innerHTML = `
                <div class="text-center py-8">
                    <div class="flex justify-center mb-4">
                        <div class="flex-shrink-0">
                            ${book.data.cover_image ? 
                                `<img src="${book.data.cover_image}" alt="${book.data.title || 'Book cover'}" class="w-24 h-32 object-cover rounded-lg shadow-lg">` :
                                `<div class="w-24 h-32 bg-gray-600 rounded-lg flex items-center justify-center">
                                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>`
                            }
                        </div>
                    </div>
                    <h4 class="text-lg font-semibold text-white mb-2">
                        ${book.data.title || 'N/A'}
                    </h4>
                    <p class="text-sm text-gray-400 mb-1">
                        {{ __("Barcode") }}: <span class="font-mono text-blue-400">${book.data.barcode}</span>
                    </p>
                    <div class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-300 mb-4">
                        {{ __("Available") }}
                    </div>
                    <p class="text-green-400 text-sm">
                        <i class="fas fa-info-circle mr-1"></i> {{ __("This_book_is_already_available") }}
                    </p>
                </div>
            `;
        }
    } else {
        infoDiv.innerHTML = `
            <div class="text-center text-red-400 py-8">
                <svg class="w-12 h-12 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p class="text-sm">${book.message}</p>
            </div>
        `;
    }
}

function displayCheckinBookError() {
    const infoDiv = document.getElementById('checkinBookInfo');
    infoDiv.innerHTML = `
        <div class="text-center text-red-400 py-8">
            <svg class="w-12 h-12 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <p class="text-sm">{{ __("Search_Error") }}</p>
        </div>
    `;
}

// Display functions
function displayPatronResult(patron) {
    // Check which tab is active to determine which info div to update
    let infoDiv;
    if (document.getElementById('readingRoomContent').style.display !== 'none' && 
        !document.getElementById('readingRoomContent').classList.contains('hidden')) {
        // Reading Room tab is active
        infoDiv = document.getElementById('readingPatronInfo');
    } else if (document.getElementById('holdContent').style.display !== 'none' && 
               !document.getElementById('holdContent').classList.contains('hidden')) {
        // Hold tab is active
        infoDiv = document.getElementById('holdPatronInfo');
    } else {
        // Loan tab is active (default)
        infoDiv = document.getElementById('patronInfo');
    }
    
    if (!infoDiv) return; // Exit if no valid div found
    
    if (patron.success) {
        const loans = patron.data.current_loans || 0;
        const maxLoans = patron.data.max_loans || 'N/A';
        const canBorrow = patron.data.can_borrow;
        const outstandingFine = patron.data.outstanding_fine || 0;
        
        // Use lock history data from controller
        updateLockCount(patron.data.patron_code);
        updateActivityStats(patron.data.patron_code);
        
        let borrowingStatus = '';
        let statusColor = '';
        let statusIcon = '';
        
        if (!canBorrow) {
            if (loans >= maxLoans) {
                borrowingStatus = 'Đã đạt giới hạn mượn sách';
                statusColor = 'text-red-600 dark:text-red-400';
                statusIcon = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path></svg>';
            } else if (outstandingFine > 0) {
                borrowingStatus = `Còn nợ phí: ${outstandingFine.toLocaleString('vi-VN')}đ`;
                statusColor = 'text-red-600 dark:text-red-400';
                statusIcon = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path></svg>';
            } else {
                borrowingStatus = 'Không thể mượn';
                statusColor = 'text-red-600 dark:text-red-400';
                statusIcon = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>';
            }
        } else {
            borrowingStatus = 'Có thể mượn sách';
            statusColor = 'text-green-600 dark:text-green-400';
            statusIcon = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>';
        }
        
        // Update detailed patron info
        if (infoDiv) {
            infoDiv.innerHTML = `
                <div class="space-y-4">
                    <!-- Patron Header Section -->
                    <div class="flex items-start space-x-4 pb-4 border-b border-gray-600">
                        <!-- Avatar - Rectangle Vertical -->
                        <div class="flex-shrink-0">
                            <div class="w-20 h-24 rounded-lg overflow-hidden">
                                ${patron.data.profile_image ? 
                                    `<img src="${patron.data.profile_image}" alt="${patron.data.display_name || 'Patron'}" class="w-full h-full object-cover">` :
                                    `<div class="w-full h-full bg-gray-700 flex items-center justify-center">
                                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                    </div>`
                                }
                            </div>
                        </div>
                        
                        <!-- Patron Info -->
                        <div class="flex-1 min-w-0">
                            <h4 class="text-lg font-semibold text-white mb-1">
                                ${patron.data.display_name || patron.data.user?.name || 'N/A'}
                            </h4>
                            <p class="text-sm text-gray-400 font-mono mb-2">${patron.data.patron_code}</p>
                            <div class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium ${canBorrow ? 'bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-300' : 'bg-red-100 dark:bg-red-900/50 text-red-800 dark:text-red-300'} mb-3">
                                ${statusIcon}
                                <span class="ml-1">${borrowingStatus}</span>
                            </div>
                            <div class="grid grid-cols-2 gap-3 text-sm">
                                <div>
                                    <span class="text-gray-400">{{ __("Số sách đang mượn") }}:</span>
                                    <span class="text-white font-medium ml-1">${loans}</span>
                                </div>
                                <div>
                                    <span class="text-gray-400">{{ __("Phí chưa trả") }}:</span>
                                    <span class="text-white font-medium ml-1">${outstandingFine.toLocaleString('vi-VN')}đ</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Transaction Statistics -->
                    <div class="bg-gray-700/30 rounded-lg p-3 text-sm">
                        <h5 class="text-xs font-semibold text-gray-300 mb-2">{{ __("Lịch sử giao dịch") }}</h5>
                        <div class="grid grid-cols-3 gap-2 text-center">
                            <div>
                                <div class="text-blue-400 font-bold text-lg">${patron.data.transaction_stats?.total_checkouts || 0}</div>
                                <div class="text-gray-400 text-xs">{{ __("Đã mượn") }}</div>
                            </div>
                            <div>
                                <div class="text-green-400 font-bold text-lg">${patron.data.transaction_stats?.total_checkins || 0}</div>
                                <div class="text-gray-400 text-xs">{{ __("Đã trả") }}</div>
                            </div>
                            <div>
                                <div class="text-orange-400 font-bold text-lg">${patron.data.transaction_stats?.total_renewals || 0}</div>
                                <div class="text-gray-400 text-xs">{{ __("Gia hạn") }}</div>
                            </div>
                        </div>
                        <div class="mt-2 pt-2 border-t border-gray-600">
                            <div class="flex justify-between">
                                <span class="text-gray-400 text-xs">{{ __("Tổng giao dịch") }}:</span>
                                <span class="text-white font-medium text-xs">${patron.data.transaction_stats?.total_transactions || 0}</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Current Loans Table -->
                    ${loans > 0 ? `
                        <div class="bg-gray-700/30 rounded-lg p-3">
                            <h5 class="text-xs font-semibold text-gray-300 mb-2">{{ __("Tài liệu đang mượn") }} (${loans})</h5>
                            <div class="overflow-x-auto">
                                <table class="current-loans-table w-full">
                                    <thead>
                                        <tr class="border-b border-gray-600">
                                            <th class="text-left text-gray-400 pb-1">{{ __("Mã vạch") }}</th>
                                            <th class="text-left text-gray-400 pb-1">{{ __("Tên tài liệu") }}</th>
                                            <th class="text-left text-gray-400 pb-1">{{ __("Hết hạn") }}</th>
                                            <th class="text-right text-gray-400 pb-1">{{ __("Hành động") }}</th>
                                        </tr>
                                    </thead>
                                    <tbody id="currentLoansTableBody">
                                        <tr>
                                            <td colspan="4" class="text-center text-gray-400 py-2">
                                                <svg class="w-4 h-4 mx-auto mb-1 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                                </svg>
                                                {{ __("Đang tải...") }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    ` : ''}
                </div>
            `;
        }
        
        // Load current loans after DOM is rendered (only in Loan tab and if currentLoansTableBody exists)
        const isLoanTabActive = document.getElementById('readingRoomContent').classList.contains('hidden') || 
                               document.getElementById('readingRoomContent').style.display === 'none';
        
        if (loans > 0 && isLoanTabActive && document.getElementById('currentLoansTableBody')) {
            setTimeout(() => {
                loadCurrentLoans(patron.data.id);
            }, 100);
        }
        
        // Update stats AFTER DOM is rendered
        setTimeout(() => {
            updateLockCount(patron.data.patron_code);
            updateActivityStats(patron.data.patron_code);
        }, 10);
    } else {
        if (infoDiv) {
            infoDiv.innerHTML = `
                <div class="p-3 rounded-lg border bg-red-900/20 border-red-700/50">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-red-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="text-sm text-red-400">${patron.message || '{{ __("Không tìm thấy bạn đọc") }}'}</span>
                    </div>
                </div>
            `;
        }
    }
    
    if (infoDiv) {
        infoDiv.classList.remove('hidden');
    }
}

// Update lock count from controller data
function updateLockCount(patronCode) {
    const lockCountElement = document.getElementById('lockCount');
    
    if (lockCountElement) {
        let lockCount = 0;
        
        if (lockHistoryData && Array.isArray(lockHistoryData)) {
            const patronLocks = lockHistoryData.filter(item => {
                return item.patron?.patron_code === patronCode || 
                       item.patron?.user?.name === patronCode;
            });
            
            lockCount = patronLocks.length;
        }
        
        lockCountElement.textContent = lockCount;
    }
}

// Update activity stats
function updateActivityStats(patronCode) {
    const overdueBooksElement = document.getElementById('overdueBooks');
    const totalRenewalsElement = document.getElementById('totalRenewals');
    
    if (overdueBooksElement) {
        let overdueCount = 0;
        
        if (loanTransactionsData && Array.isArray(loanTransactionsData)) {
            const patronOverdueBooks = loanTransactionsData.filter(loan => {
                return (loan.patron?.patron_code === patronCode || 
                       loan.patron?.user?.name === patronCode) && 
                       loan.status === 'borrowed' && 
                       loan.due_date && new Date(loan.due_date) < new Date();
            });
            
            overdueCount = patronOverdueBooks.length;
        }
        
        overdueBooksElement.textContent = overdueCount;
    }
    
    if (totalRenewalsElement) {
        let totalRenewals = 0;
        
        if (loanTransactionsData && Array.isArray(loanTransactionsData)) {
            const patronLoans = loanTransactionsData.filter(loan => {
                return loan.patron?.patron_code === patronCode || 
                       loan.patron?.user?.name === patronCode;
            });
            
            totalRenewals = patronLoans.reduce((sum, loan) => {
                return sum + (loan.renewal_count || 0);
            }, 0);
        }
        
        totalRenewalsElement.textContent = totalRenewals;
    }
}

function displayBookResult(book) {
    // Check which tab is active to determine which info div to update
    let resultDiv;
    if (document.getElementById('readingRoomContent').style.display !== 'none' && 
        !document.getElementById('readingRoomContent').classList.contains('hidden')) {
        // Reading Room tab is active
        resultDiv = document.getElementById('readingBookInfo');
    } else if (document.getElementById('holdContent').style.display !== 'none' && 
               !document.getElementById('holdContent').classList.contains('hidden')) {
        // Hold tab is active
        resultDiv = document.getElementById('holdBookInfo');
    } else {
        // Loan tab is active (default)
        resultDiv = document.getElementById('bookSearchResult');
    }
    
    if (!resultDiv) return; // Exit if no valid div found
    
    if (book.success) {
        const isAvailable = book.data.status === 'available';
        
        resultDiv.innerHTML = `
            <div class="p-3 rounded-lg border ${isAvailable ? 'bg-green-50 dark:bg-green-900/20 border-green-200 dark:border-green-500' : 'bg-red-50 dark:bg-red-900/20 border-red-200 dark:border-red-500'}">
                <div class="flex items-start space-x-3">
                    <div class="flex-shrink-0">
                        ${book.data.cover_image ? 
                            `<img src="${book.data.cover_image}" alt="${book.data.title || 'Book cover'}" class="w-16 h-20 object-cover rounded-lg shadow-lg">` :
                            `<div class="w-16 h-20 bg-gray-600 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                            </div>`
                        }
                    </div>
                    <div class="flex-1">
                        <h4 class="font-semibold text-sm ${isAvailable ? 'text-green-800 dark:text-green-300' : 'text-red-800 dark:text-red-300'}">
                            ${book.data.title || 'N/A'}
                        </h4>
                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">
                            {{ __("Barcode") }}: <span class="font-mono">${book.data.barcode}</span>
                        </p>
                        <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">
                            ${book.data.author || '{{ __("N/A") }}'} • ${book.data.call_number || '{{ __("N/A") }}'}
                        </p>
                        ${book.data.current_loan ? `<p class="text-xs text-orange-600 dark:text-orange-400 mt-1">{{ __("On_loan_to") }}: ${book.data.current_loan.patron_name}</p>` : ''}
                    </div>
                    <div class="ml-2 flex-shrink-0">
                        ${isAvailable ? 
                            '<span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-300">{{ __("Available") }}</span>' :
                            '<span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 dark:bg-red-900/50 text-red-800 dark:text-red-300">{{ __("Not_Available") }}</span>'
                        }
                    </div>
                </div>
            </div>
        `;
    } else {
        resultDiv.innerHTML = `
            <div class="p-3 rounded-lg border bg-red-50 dark:bg-red-900/20 border-red-200 dark:border-red-500">
                <p class="text-sm text-red-800 dark:text-red-300">${book.message}</p>
            </div>
        `;
    }
    
    resultDiv.classList.remove('hidden');
}

function displayPatronError() {
    const resultDiv = document.getElementById('patronSearchResult');
    resultDiv.innerHTML = `
        <div class="p-3 rounded-lg border bg-red-50 dark:bg-red-900/20 border-red-200 dark:border-red-500">
            <p class="text-sm text-red-800 dark:text-red-300">{{ __("Search_Error") }}</p>
        </div>
    `;
    resultDiv.classList.remove('hidden');
}

function displayBookError() {
    const resultDiv = document.getElementById('bookSearchResult');
    resultDiv.innerHTML = `
        <div class="p-3 rounded-lg border bg-red-50 dark:bg-red-900/20 border-red-200 dark:border-red-500">
            <p class="text-sm text-red-800 dark:text-red-300">{{ __("Search_Error") }}</p>
        </div>
    `;
    resultDiv.classList.remove('hidden');
}
</script>

<style>
    .input-field { background: #111827; border: 1px solid #374151; border-radius: 0.375rem; padding: 0.5rem 0.75rem; color: #fff; font-size: 0.875rem; }
    .input-field:focus { outline: none; border-color: #3b82f6; }
    .btn-secondary { background: #374151; color: #fff; padding: 0.5rem 1rem; border-radius: 0.375rem; font-size: 0.875rem; font-weight: 500; }
    .btn-secondary:hover { background: #4b5563; }
    .card-admin { background: #1f2937; border: 1px solid #374151; }
    .patron-info-scroll { max-height: 600px; overflow-y: auto; }
    .current-loans-table { font-size: 0.75rem; }
    .action-btn-sm { padding: 0.25rem 0.5rem; font-size: 0.7rem; }
</style>

<!-- Recall Modal -->
<div id="recallModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
    <div class="bg-gray-800 rounded-lg p-6 w-full max-w-md mx-4">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-white flex items-center">
                <svg class="w-5 h-5 mr-2 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                </svg>
                {{ __("Triệu hồi tài liệu") }}
            </h3>
            <button onclick="closeRecallModal()" class="text-gray-400 hover:text-white">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1">{{ __("Mã vạch tài liệu") }} *</label>
                <input type="text" id="recallBookBarcode" class="input-field w-full" placeholder="{{ __("Nhập mã vạch tài liệu cần triệu hồi") }}">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1">{{ __("Lý do triệu hồi") }}</label>
                <textarea id="recallReason" class="input-field w-full" rows="3" placeholder="{{ __("Nhập lý do triệu hồi (không bắt buộc)") }}"></textarea>
            </div>
            
            <div class="flex justify-end space-x-3">
                <button onclick="closeRecallModal()" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg">
                    {{ __("Hủy") }}
                </button>
                <button onclick="processRecall()" class="px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white rounded-lg">
                    {{ __("Triệu hồi") }}
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Declare Lost Modal -->
<div id="declareLostModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
    <div class="bg-gray-800 rounded-lg p-6 w-full max-w-md mx-4">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-white flex items-center">
                <svg class="w-5 h-5 mr-2 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
                {{ __("Khai báo mất tài liệu") }}
            </h3>
            <button onclick="closeDeclareLostModal()" class="text-gray-400 hover:text-white">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1">{{ __("Chọn tài liệu") }} *</label>
                <div id="declareLostBooksList" class="max-h-40 overflow-y-auto space-y-2">
                    <!-- Books will be populated here -->
                </div>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1">{{ __("Ghi chú") }}</label>
                <textarea id="declareLostNotes" class="input-field w-full" rows="3" placeholder="{{ __("Nhập ghi chú (không bắt buộc)") }}"></textarea>
            </div>
            
            <div class="flex justify-end space-x-3">
                <button onclick="closeDeclareLostModal()" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg">
                    {{ __("Hủy") }}
                </button>
                <button onclick="processDeclareLost()" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg">
                    {{ __("Khai báo mất") }}
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Load current loans for patron
function loadCurrentLoans(patronId) {
    // This would need an API endpoint to get current loans
    // For now, we'll use the existing loanTransactionsData
    const tbody = document.getElementById('currentLoansTableBody');
    if (!tbody) return;
    
    // Debug: Log the data structure
    console.log('loadCurrentLoans - patronId:', patronId);
    console.log('loadCurrentLoans - loanTransactionsData:', loanTransactionsData);
    
    // Filter current loans for this patron from existing data
    const patronLoans = loanTransactionsData ? loanTransactionsData.filter(loan => 
        loan.patron_detail_id === patronId && loan.status === 'borrowed'
    ) : [];
    
    console.log('loadCurrentLoans - patronLoans:', patronLoans);
    
    if (patronLoans.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="4" class="text-center text-gray-400 py-2">
                    {{ __("Không có tài liệu nào đang mượn") }}
                </td>
            </tr>
        `;
        return;
    }
    
    // Generate table rows
    tbody.innerHTML = patronLoans.map(loan => {
        const dueDate = loan.due_date ? new Date(loan.due_date).toLocaleDateString('vi-VN') : 'N/A';
        const isOverdue = loan.due_date && new Date(loan.due_date) < new Date();
        
        // Debug: Log each loan structure
        console.log('Processing loan:', loan);
        console.log('book_item:', loan.book_item);
        console.log('bibliographic_record:', loan.book_item?.bibliographic_record);
        
        return `
            <tr class="border-b border-gray-700/50 hover:bg-gray-700/20">
                <td class="py-2 text-gray-300 font-mono text-xs">${loan.book_item?.barcode || 'N/A'}</td>
                <td class="py-2 text-gray-300 text-xs">
                    <div class="max-w-[200px] truncate" title="${loan.book_item?.bibliographic_record?.title || 'N/A'}">
                        ${loan.book_item?.bibliographic_record?.title || 'N/A'}
                    </div>
                </td>
                <td class="py-2 text-xs ${isOverdue ? 'text-red-400 font-medium' : 'text-gray-300'}">
                    ${dueDate}
                    ${isOverdue ? '<span class="ml-1 text-red-400">⚠️</span>' : ''}
                </td>
                <td class="py-2 text-right">
                    <div class="flex justify-end space-x-1">
                        <button onclick="recallSpecificBook('${loan.book_item?.barcode || ''}', '${loan.book_item?.bibliographic_record?.title || ''}')" 
                                class="p-1 text-yellow-400 hover:text-yellow-300 hover:bg-yellow-600/10 rounded transition-colors group"
                                title="{{ __("Triệu hồi") }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                            </svg>
                            <span class="absolute -top-8 right-0 bg-gray-800 text-xs text-white px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap z-10">
                                {{ __("Triệu hồi") }}
                            </span>
                        </button>
                        <button onclick="declareLostSpecificBook('${loan.book_item?.barcode || ''}', '${loan.book_item?.bibliographic_record?.title || ''}')" 
                                class="p-1 text-red-400 hover:text-red-300 hover:bg-red-600/10 rounded transition-colors group relative"
                                title="{{ __("Khai báo mất") }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                            <span class="absolute -top-8 right-0 bg-gray-800 text-xs text-white px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap z-10">
                                {{ __("Khai báo mất") }}
                            </span>
                        </button>
                    </div>
                </td>
            </tr>
        `;
    }).join('');
}

// Recall specific book
function recallSpecificBook(barcode, title) {
    if (!barcode) {
        Swal.fire({
            icon: 'error',
            title: '{{ __("Lỗi") }}',
            text: '{{ __("Không thể triệu hồi tài liệu này") }}',
            confirmButtonColor: '#3b82f6'
        });
        return;
    }
    
    // Pre-fill the recall modal
    document.getElementById('recallBookBarcode').value = barcode;
    document.getElementById('recallReason').value = `Triệu hồi tài liệu: ${title}`;
    showRecallModal();
}

// Declare specific book lost
function declareLostSpecificBook(barcode, title) {
    if (!barcode) {
        Swal.fire({
            icon: 'error',
            title: '{{ __("Lỗi") }}',
            text: '{{ __("Không thể khai báo mất tài liệu này") }}',
            confirmButtonColor: '#3b82f6'
        });
        return;
    }
    
    // Show confirmation
    Swal.fire({
        title: '{{ __("Xác nhận khai báo mất tài liệu") }}',
        html: `<strong>${title}</strong><br><small>${barcode}</small>`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#6b7280',
        confirmButtonText: '{{ __("Khai báo mất") }}',
        cancelButtonText: '{{ __("Hủy") }}'
    }).then((result) => {
        if (result.isConfirmed) {
            // Here you would make API call to process declare lost
            console.log('Declaring lost:', { barcode, title });
            
            // Show success message
            Swal.fire({
                icon: 'success',
                title: '{{ __("Thành công") }}',
                text: `{{ __("Khai báo mất tài liệu thành công") }}: ${title}`,
                confirmButtonColor: '#3b82f6'
            });
            
            // Refresh patron info
            const patronCode = document.getElementById('patron_code').value.trim();
            if (patronCode) {
                searchPatronByCode(patronCode);
            }
        }
    });
}

// Modal functions
function showRecallModal() {
    document.getElementById('recallModal').classList.remove('hidden');
}

function closeRecallModal() {
    document.getElementById('recallModal').classList.add('hidden');
    document.getElementById('recallBookBarcode').value = '';
    document.getElementById('recallReason').value = '';
}

function showDeclareLostModal() {
    document.getElementById('declareLostModal').classList.remove('hidden');
    loadPatronBooksForDeclareLost();
}

function closeDeclareLostModal() {
    document.getElementById('declareLostModal').classList.add('hidden');
    document.getElementById('declareLostNotes').value = '';
}

// Load patron books for declare lost
function loadPatronBooksForDeclareLost() {
    const patronCode = document.getElementById('patron_code').value.trim();
    if (!patronCode) return;
    
    fetch(`{{ route('admin.circulation.search-patron') }}?patron_code=${encodeURIComponent(patronCode)}`)
        .then(response => response.json())
        .then(data => {
            if (data.success && data.data.current_loans > 0) {
                // Load current loans for this patron
                loadCurrentLoansForDeclareLost(data.data.id);
            } else {
                document.getElementById('declareLostBooksList').innerHTML = `
                    <div class="text-gray-400 text-sm text-center py-4">
                        {{ __("Bạn đọc không có tài liệu đang mượn") }}
                    </div>
                `;
            }
        })
        .catch(error => {
            console.error('Error loading patron books:', error);
        });
}

function loadCurrentLoansForDeclareLost(patronId) {
    // This would need an API endpoint to get current loans
    // For now, show placeholder
    document.getElementById('declareLostBooksList').innerHTML = `
        <div class="space-y-2">
            <div class="flex items-center p-2 bg-gray-700 rounded">
                <input type="checkbox" id="book1" class="mr-3">
                <label for="book1" class="text-sm text-white flex-1">
                    Tối ưu hóa cơ sở dữ liệu - VTTU000000000001
                </label>
            </div>
            <div class="flex items-center p-2 bg-gray-700 rounded">
                <input type="checkbox" id="book2" class="mr-3">
                <label for="book2" class="text-sm text-white flex-1">
                    Lập trình nâng cao - VTTU000000000002
                </label>
            </div>
        </div>
    `;
}

// Process recall
function processRecall() {
    const barcode = document.getElementById('recallBookBarcode').value.trim();
    const reason = document.getElementById('recallReason').value.trim();
    
    if (!barcode) {
        Swal.fire({
            icon: 'warning',
            title: '{{ __("Thông báo") }}',
            text: '{{ __("Vui lòng nhập mã vạch tài liệu") }}',
            confirmButtonColor: '#3b82f6'
        });
        return;
    }
    
    // Show loading
    Swal.fire({
        title: '{{ __("Đang xử lý") }}',
        text: '{{ __("Đang triệu hồi tài liệu...") }}',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
    
    // Make API call
    fetch('{{ route("admin.circulation.recall") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            barcode: barcode,
            reason: reason
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            let message = data.message;
            
            // Add due date information to success message
            if (data.data.is_overdue) {
                message += `\n\nTài liệu đã quá hạn. Hạn trả không thay đổi: ${data.data.new_due_date}`;
            } else {
                message += `\n\nHạn trả đã cập nhật thành ngày triệu hồi: ${data.data.new_due_date}`;
            }
            
            Swal.fire({
                icon: 'success',
                title: '{{ __("Thành công") }}',
                text: message,
                confirmButtonColor: '#3b82f6'
            });
            
            // Call callback if exists (to add to recent actions)
            if (window.recallCallback) {
                window.recallCallback();
                window.recallCallback = null; // Clear callback
            }
            
            closeRecallModal();
            
            // Refresh page after a short delay
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        } else {
            Swal.fire({
                icon: 'error',
                title: '{{ __("Lỗi") }}',
                text: data.message,
                confirmButtonColor: '#3b82f6'
            });
        }
    })
    .catch(error => {
        console.error('Recall error:', error);
        Swal.fire({
            icon: 'error',
            title: '{{ __("Lỗi") }}',
            text: '{{ __("Có lỗi xảy ra khi triệu hồi tài liệu") }}',
            confirmButtonColor: '#3b82f6'
        });
    });
}

// Process declare lost
function processDeclareLost() {
    const checkboxes = document.querySelectorAll('#declareLostBooksList input[type="checkbox"]:checked');
    const notes = document.getElementById('declareLostNotes').value.trim();
    
    if (checkboxes.length === 0) {
        Swal.fire({
            icon: 'warning',
            title: '{{ __("Thông báo") }}',
            text: '{{ __("Vui lòng chọn ít nhất một tài liệu") }}',
            confirmButtonColor: '#3b82f6'
        });
        return;
    }
    
    // Here you would make API call to process declare lost
    console.log('Processing declare lost:', { selectedBooks: checkboxes.length, notes });
    
    // Show success message
    Swal.fire({
        icon: 'success',
        title: '{{ __("Thành công") }}',
        text: '{{ __("Khai báo mất tài liệu thành công") }}',
        confirmButtonColor: '#3b82f6'
    });
    
    closeDeclareLostModal();
}

// Loan Transaction Actions
function recallLoanTransaction(barcode, title) {
    if (!barcode) {
        Swal.fire({
            icon: 'error',
            title: '{{ __("Lỗi") }}',
            text: '{{ __("Không thể triệu hồi tài liệu này") }}',
            confirmButtonColor: '#3b82f6'
        });
        return;
    }
    
    // Pre-fill the recall modal
    document.getElementById('recallBookBarcode').value = barcode;
    document.getElementById('recallReason').value = `Triệu hồi tài liệu: ${title}`;
    
    // Store callback for after recall is processed
    window.recallCallback = () => {
        // Add action to recent activities
        addRecentAction({
            date: new Date().toISOString(),
            type: 'recall',
            patron: 'Thủ thư',
            book: title,
            barcode: barcode,
            status: 'recalled'
        });
    };
    
    showRecallModal();
}

function declareLostLoanTransaction(barcode, title) {
    if (!barcode) {
        Swal.fire({
            icon: 'error',
            title: '{{ __("Lỗi") }}',
            text: '{{ __("Không thể khai báo mất tài liệu này") }}',
            confirmButtonColor: '#3b82f6'
        });
        return;
    }
    
    // Show confirmation
    Swal.fire({
        title: '{{ __("Xác nhận khai báo mất tài liệu") }}',
        html: `<strong>${title}</strong><br><small>${barcode}</small>`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#6b7280',
        confirmButtonText: '{{ __("Khai báo mất") }}',
        cancelButtonText: '{{ __("Hủy") }}'
    }).then((result) => {
        if (result.isConfirmed) {
            // Show loading
            Swal.fire({
                title: '{{ __("Đang xử lý") }}',
                text: '{{ __("Đang khai báo mất tài liệu...") }}',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            // Make API call
            fetch('{{ route("admin.circulation.declare-lost") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    barcode: barcode,
                    notes: `Khai báo mất từ loan transaction: ${title}`
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Add action to recent activities
                    addRecentAction({
                        date: new Date().toISOString(),
                        type: 'declare_lost',
                        patron: 'Thủ thư',
                        book: title,
                        barcode: barcode,
                        status: 'lost'
                    });
                    
                    Swal.fire({
                        icon: 'success',
                        title: '{{ __("Thành công") }}',
                        text: data.message,
                        confirmButtonColor: '#3b82f6'
                    });
                    
                    // Refresh page after successful action
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: '{{ __("Lỗi") }}',
                        text: data.message,
                        confirmButtonColor: '#3b82f6'
                    });
                }
            })
            .catch(error => {
                console.error('Declare lost error:', error);
                Swal.fire({
                    icon: 'error',
                    title: '{{ __("Lỗi") }}',
                    text: '{{ __("Có lỗi xảy ra khi khai báo mất tài liệu") }}',
                    confirmButtonColor: '#3b82f6'
                });
            });
        }
    });
}

// Add recent action to the table
function addRecentAction(action) {
    const tbody = document.querySelector('table tbody');
    if (!tbody) return;
    
    // Create new row
    const newRow = document.createElement('tr');
    newRow.className = 'hover:bg-gray-800/50';
    
    const statusClass = action.type === 'declare_lost' ? 'bg-gray-900/50 text-gray-400' : 
                       action.type === 'recall' ? 'bg-yellow-900/50 text-yellow-400' : 
                       'bg-gray-900/50 text-gray-400';
    
    const statusText = action.type === 'declare_lost' ? '{{ __("Đã khai báo mất") }}' : 
                      action.type === 'recall' ? '{{ __("Đã triệu hồi") }}' : 
                      action.status;
    
    newRow.innerHTML = `
        <td class="p-3 text-xs text-gray-400">${new Date(action.date).toLocaleString('vi-VN')}</td>
        <td class="p-3">
            <div class="font-medium">${action.patron}</div>
            <div class="text-xs text-gray-400">Admin</div>
        </td>
        <td class="p-3">
            <div class="font-medium">${action.book}</div>
            <div class="text-xs text-gray-400 font-mono">${action.barcode}</div>
        </td>
        <td class="p-3">-</td>
        <td class="p-3">
            <span class="px-2 py-1 rounded text-xs font-bold ${statusClass}">
                ${statusText}
            </span>
        </td>
        <td class="p-3">
            <span class="text-xs text-gray-500">{{ __("Hoàn thành") }}</span>
        </td>
    `;
    
    // Add to top of table (after thead)
    const firstRow = tbody.querySelector('tr');
    if (firstRow) {
        tbody.insertBefore(newRow, firstRow);
    } else {
        tbody.appendChild(newRow);
    }
    
    // Remove empty state message if exists
    const emptyRow = tbody.querySelector('tr[colspan="6"]');
    if (emptyRow) {
        emptyRow.remove();
    }
}

// ==================== READING ROOM FUNCTIONS ====================

// Process reading room checkout
function processReadingRoomCheckout() {
    const patronCode = document.getElementById('reading_patron_code').value.trim();
    const bookBarcode = document.getElementById('reading_book_barcode').value.trim();
    const notes = document.getElementById('reading_notes').value.trim();
    
    if (!patronCode || !bookBarcode) {
        Swal.fire({
            icon: 'warning',
            title: '{{ __("Thông báo") }}',
            text: '{{ __("Vui lòng nhập mã bạn đọc và mã tài liệu") }}',
            confirmButtonColor: '#3b82f6'
        });
        return;
    }
    
    // Show loading
    Swal.fire({
        title: '{{ __("Đang xử lý") }}',
        text: '{{ __("Đang xử lý mượn đọc...") }}',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
    
    // Make API call
    fetch('{{ route("admin.circulation.reading-room.checkout") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            patron_code: patronCode,
            barcode: bookBarcode,
            notes: notes
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: '{{ __("Thành công") }}',
                html: `
                    ${data.message}<br><br>
                    <small>
                        <strong>{{ __("Bạn đọc") }}:</strong> ${data.data.patron_name}<br>
                        <strong>{{ __("Tài liệu") }}:</strong> ${data.data.book_title}<br>
                        <strong>{{ __("Hạn trả") }}:</strong> ${data.data.due_time}
                    </small>
                `,
                confirmButtonColor: '#3b82f6'
            });
            
            // Clear form
            document.getElementById('reading_book_barcode').value = '';
            document.getElementById('reading_notes').value = '';
            
            // Reload transactions
            loadReadingRoomTransactions();
            loadAllReadingRoomTransactions();
            
        } else {
            Swal.fire({
                icon: 'error',
                title: '{{ __("Lỗi") }}',
                text: data.message,
                confirmButtonColor: '#3b82f6'
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: '{{ __("Lỗi") }}',
            text: '{{ __("Có lỗi xảy ra. Vui lòng thử lại.") }}',
            confirmButtonColor: '#3b82f6'
        });
    });
}

// Load reading room transactions for patron
function loadReadingRoomTransactions() {
    const patronCode = document.getElementById('reading_patron_code').value.trim();
    
    if (!patronCode) {
        document.getElementById('readingRoomTransactions').innerHTML = `
            <div class="text-center text-gray-500 py-8">
                <svg class="w-12 h-12 mx-auto mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
                <p class="text-sm">{{ __("Nhập mã bạn đọc để xem tài liệu đang mượn đọc") }}</p>
            </div>
        `;
        document.getElementById('returnReadingRoomBtn').disabled = true;
        
        // Clear patron info
        const readingPatronInfo = document.getElementById('readingPatronInfo');
        if (readingPatronInfo) {
            readingPatronInfo.innerHTML = `
                <div class="text-center text-gray-500 text-sm py-8">
                    <svg class="w-12 h-12 mx-auto mb-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    <p>{{ __('Nhập mã bạn đọc để hiển thị thông tin') }}</p>
                </div>
            `;
        }
        return;
    }
    
    // First, get patron info
    searchPatronByCode(patronCode);
    
    // Then load transactions
    fetch(`{{ route("admin.circulation.reading-room.transactions") }}?patron_code=${patronCode}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                displayReadingRoomTransactions(data);
            } else {
                document.getElementById('readingRoomTransactions').innerHTML = `
                    <div class="text-center text-red-400 py-4">
                        <p class="text-sm">${data.message}</p>
                    </div>
                `;
                document.getElementById('returnReadingRoomBtn').disabled = true;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('readingRoomTransactions').innerHTML = `
                <div class="text-center text-red-400 py-4">
                    <p class="text-sm">{{ __("Có lỗi xảy ra khi tải danh sách") }}</p>
                </div>
            `;
            document.getElementById('returnReadingRoomBtn').disabled = true;
        });
}

// Display reading room transactions
function displayReadingRoomTransactions(data) {
    const container = document.getElementById('readingRoomTransactions');
    const returnBtn = document.getElementById('returnReadingRoomBtn');
    
    if (data.transactions.length === 0) {
        container.innerHTML = `
            <div class="text-center text-gray-500 py-8">
                <svg class="w-12 h-12 mx-auto mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <p class="text-sm">{{ __("Bạn đọc không có tài liệu nào đang mượn đọc") }}</p>
            </div>
        `;
        returnBtn.disabled = true;
        return;
    }
    
    let html = `
        <div class="space-y-2">
            <div class="text-sm text-gray-400 mb-3">
                {{ __("Tổng số") }}: ${data.total_count} {{ __("tài liệu") }}
            </div>
    `;
    
    data.transactions.forEach(transaction => {
        const statusClass = transaction.is_overdue ? 'text-red-400' : 'text-green-400';
        const statusText = transaction.is_overdue ? '{{ __("Quá hạn") }}' : '{{ __("Đang mượn") }}';
        
        html += `
            <div class="flex items-center space-x-3 p-3 bg-gray-800/50 rounded-lg hover:bg-gray-800/70 transition-colors">
                <input type="checkbox" class="reading-room-checkbox" value="${transaction.id}" 
                       class="w-4 h-4 text-purple-600 bg-gray-100 border-gray-300 rounded focus:ring-purple-500">
                <div class="flex-1">
                    <div class="font-medium text-sm">${transaction.book_title}</div>
                    <div class="text-xs text-gray-400">
                        ${transaction.author} • ${transaction.barcode}
                    </div>
                    <div class="text-xs mt-1">
                        <span class="${statusClass}">${statusText}</span> • 
                        {{ __("Mượn") }}: ${transaction.checkout_time} • 
                        {{ __("Hạn") }}: ${transaction.due_time} • 
                        {{ __("Thời gian") }}: ${transaction.duration}
                    </div>
                </div>
            </div>
        `;
    });
    
    html += `</div>`;
    container.innerHTML = html;
    returnBtn.disabled = false;
    
    // Add checkbox event listener
    document.querySelectorAll('.reading-room-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', updateReturnButton);
    });
}

// Update return button state
function updateReturnButton() {
    const checkboxes = document.querySelectorAll('.reading-room-checkbox:checked');
    const returnBtn = document.getElementById('returnReadingRoomBtn');
    returnBtn.disabled = checkboxes.length === 0;
}

// Process reading room checkin
function processReadingRoomCheckin() {
    const checkboxes = document.querySelectorAll('.reading-room-checkbox:checked');
    
    if (checkboxes.length === 0) {
        Swal.fire({
            icon: 'warning',
            title: '{{ __("Thông báo") }}',
            text: '{{ __("Vui lòng chọn ít nhất một tài liệu để trả") }}',
            confirmButtonColor: '#3b82f6'
        });
        return;
    }
    
    const transactionIds = Array.from(checkboxes).map(cb => parseInt(cb.value));
    
    // Show loading
    Swal.fire({
        title: '{{ __("Đang xử lý") }}',
        text: '{{ __("Đang trả tài liệu mượn đọc...") }}',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
    
    // Make API call
    fetch('{{ route("admin.circulation.reading-room.checkin") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            transaction_ids: transactionIds
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            let itemsHtml = '';
            data.data.checked_in_items.forEach(item => {
                itemsHtml += `<li>${item.book_title} (${item.barcode}) - {{ __("Thời gian") }}: ${item.duration}</li>`;
            });
            
            Swal.fire({
                icon: 'success',
                title: '{{ __("Thành công") }}',
                html: `
                    ${data.message}<br><br>
                    <small style="text-align: left; display: block;">
                        <strong>{{ __("Các tài liệu đã trả") }}:</strong><br>
                        <ul>${itemsHtml}</ul>
                    </small>
                `,
                confirmButtonColor: '#3b82f6'
            });
            
            // Reload transactions
            loadReadingRoomTransactions();
            loadAllReadingRoomTransactions();
            
        } else {
            Swal.fire({
                icon: 'error',
                title: '{{ __("Lỗi") }}',
                text: data.message,
                confirmButtonColor: '#3b82f6'
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: '{{ __("Lỗi") }}',
            text: '{{ __("Có lỗi xảy ra. Vui lòng thử lại.") }}',
            confirmButtonColor: '#3b82f6'
        });
    });
}

// Load all active reading room transactions
function loadAllReadingRoomTransactions() {
    fetch('{{ route("admin.circulation.reading-room.active") }}')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                displayAllReadingRoomTransactions(data.data);
            } else {
                document.getElementById('allReadingRoomTransactions').innerHTML = `
                    <div class="text-center text-red-400 py-4">
                        <p class="text-sm">${data.message}</p>
                    </div>
                `;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('allReadingRoomTransactions').innerHTML = `
                <div class="text-center text-red-400 py-4">
                    <p class="text-sm">{{ __("Có lỗi xảy ra khi tải danh sách") }}</p>
                </div>
            `;
        });
}

// Display all reading room transactions
function displayAllReadingRoomTransactions(data) {
    const container = document.getElementById('allReadingRoomTransactions');
    
    if (data.transactions.length === 0) {
        container.innerHTML = `
            <div class="text-center text-gray-500 py-4">
                <p class="text-sm">{{ __("Không có tài liệu nào đang mượn đọc") }}</p>
            </div>
        `;
        return;
    }
    
    let html = `
        <div class="mb-3 text-sm">
            <span class="text-gray-400">{{ __("Tổng số") }}: ${data.total_count}</span>
            ${data.overdue_count > 0 ? `<span class="text-red-400 ml-3">{{ __("Quá hạn") }}: ${data.overdue_count}</span>` : ''}
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-800 text-xs uppercase">
                    <tr>
                        <th class="p-2 text-left">{{ __("Bạn đọc") }}</th>
                        <th class="p-2 text-left">{{ __("Tài liệu") }}</th>
                        <th class="p-2 text-left">{{ __("Mã vạch") }}</th>
                        <th class="p-2 text-left">{{ __("Mượn") }}</th>
                        <th class="p-2 text-left">{{ __("Hạn") }}</th>
                        <th class="p-2 text-left">{{ __("Thời gian") }}</th>
                        <th class="p-2 text-left">{{ __("Trạng thái") }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700">
    `;
    
    data.transactions.forEach(transaction => {
        const statusClass = transaction.is_overdue ? 'text-red-400' : 'text-green-400';
        
        html += `
            <tr class="hover:bg-gray-800/50">
                <td class="p-2">
                    <div class="font-medium text-xs">${transaction.patron_name}</div>
                    <div class="text-xs text-gray-400">${transaction.patron_code}</div>
                </td>
                <td class="p-2">
                    <div class="text-xs max-w-[200px] truncate" title="${transaction.book_title}">
                        ${transaction.book_title}
                    </div>
                </td>
                <td class="p-2 text-xs font-mono">${transaction.barcode}</td>
                <td class="p-2 text-xs">${transaction.checkout_time}</td>
                <td class="p-2 text-xs ${transaction.is_overdue ? 'text-red-400' : ''}">${transaction.due_time}</td>
                <td class="p-2 text-xs">${transaction.duration}</td>
                <td class="p-2">
                    <span class="px-2 py-1 rounded text-xs font-bold ${statusClass}">
                        ${transaction.status_display}
                    </span>
                </td>
            </tr>
        `;
    });
    
    html += `
                </tbody>
            </table>
        </div>
    `;
    
    container.innerHTML = html;
}

// Update switchTab function to handle reading room tab
function switchTab(tabName) {
    // Hide all tabs
    document.getElementById('checkoutContent').classList.add('hidden');
    document.getElementById('checkinContent').classList.add('hidden');
    document.getElementById('readingRoomContent').classList.add('hidden');
    
    // Remove active styles from all tabs
    document.getElementById('checkoutTab').classList.remove('border-green-500', 'text-green-600', 'bg-green-50', 'dark:bg-green-900/20', 'dark:text-green-400');
    document.getElementById('checkinTab').classList.remove('border-blue-500', 'text-blue-600', 'bg-blue-50', 'dark:bg-blue-900/20', 'dark:text-blue-400');
    document.getElementById('readingRoomTab').classList.remove('border-purple-500', 'text-purple-600', 'bg-purple-50', 'dark:bg-purple-900/20', 'dark:text-purple-400');
    
    // Add inactive styles to all tabs
    document.getElementById('checkoutTab').classList.add('border-transparent', 'text-gray-600', 'dark:text-gray-400');
    document.getElementById('checkinTab').classList.add('border-transparent', 'text-gray-600', 'dark:text-gray-400');
    document.getElementById('readingRoomTab').classList.add('border-transparent', 'text-gray-600', 'dark:text-gray-400');
    
    // Show selected tab and add active styles
    if (tabName === 'checkout') {
        document.getElementById('checkoutContent').classList.remove('hidden');
        document.getElementById('checkoutTab').classList.remove('border-transparent', 'text-gray-600', 'dark:text-gray-400');
        document.getElementById('checkoutTab').classList.add('border-green-500', 'text-green-600', 'bg-green-50', 'dark:bg-green-900/20', 'dark:text-green-400');
    } else if (tabName === 'checkin') {
        document.getElementById('checkinContent').classList.remove('hidden');
        document.getElementById('checkinTab').classList.remove('border-transparent', 'text-gray-600', 'dark:text-gray-400');
        document.getElementById('checkinTab').classList.add('border-blue-500', 'text-blue-600', 'bg-blue-50', 'dark:bg-blue-900/20', 'dark:text-blue-400');
    } else if (tabName === 'reading-room') {
        document.getElementById('readingRoomContent').classList.remove('hidden');
        document.getElementById('readingRoomTab').classList.remove('border-transparent', 'text-gray-600', 'dark:text-gray-400');
        document.getElementById('readingRoomTab').classList.add('border-purple-500', 'text-purple-600', 'bg-purple-50', 'dark:bg-purple-900/20', 'dark:text-purple-400');
        
        // Load all reading room transactions when tab is opened
        loadAllReadingRoomTransactions();
    }
}

// ==================== HOLD/RESERVE FUNCTIONS ====================

// Process place hold
function processPlaceHold() {
    const patronCode = document.getElementById('hold_patron_code').value.trim();
    const bookBarcode = document.getElementById('hold_book_barcode').value.trim();
    const notes = document.getElementById('hold_notes').value.trim();
    
    if (!patronCode || !bookBarcode) {
        Swal.fire({
            icon: 'warning',
            title: '{{ __("Thông báo") }}',
            text: '{{ __("Vui lòng nhập mã bạn đọc và mã tài liệu") }}',
            confirmButtonColor: '#3b82f6'
        });
        return;
    }
    
    // Show loading
    Swal.fire({
        title: '{{ __("Đang xử lý") }}',
        text: '{{ __("Đang giữ lại sách...") }}',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
    
    // Make API call
    fetch('{{ route("admin.circulation.hold.place") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            patron_code: patronCode,
            barcode: bookBarcode,
            notes: notes
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: '{{ __("Thành công") }}',
                html: `
                    ${data.message}<br><br>
                    <small>
                        <strong>{{ __("Bạn đọc") }}:</strong> ${data.data.patron_name}<br>
                        <strong>{{ __("Tài liệu") }}:</strong> ${data.data.book_title}<br>
                        <strong>{{ __("Trạng thái") }}:</strong> ${data.data.status_display}<br>
                        <strong>{{ __("Hết hạn") }}:</strong> ${data.data.expiry_date}
                    </small>
                `,
                confirmButtonColor: '#3b82f6'
            });
            
            // Clear form
            document.getElementById('hold_book_barcode').value = '';
            document.getElementById('hold_notes').value = '';
            
            // Reload reservations
            loadPatronReservations();
            loadAllReservations();
            
        } else {
            Swal.fire({
                icon: 'error',
                title: '{{ __("Lỗi") }}',
                text: data.message,
                confirmButtonColor: '#3b82f6'
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: '{{ __("Lỗi") }}',
            text: '{{ __("Có lỗi xảy ra. Vui lòng thử lại.") }}',
            confirmButtonColor: '#3b82f6'
        });
    });
}

// Load patron reservations
function loadPatronReservations() {
    const patronCode = document.getElementById('hold_patron_code').value.trim();
    
    if (!patronCode) {
        document.getElementById('patronReservations').innerHTML = `
            <div class="text-center text-gray-500 py-8">
                <svg class="w-12 h-12 mx-auto mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path>
                </svg>
                <p class="text-sm">{{ __("Nhập mã bạn đọc để xem reservations") }}</p>
            </div>
        `;
        return;
    }
    
    fetch(`{{ route("admin.circulation.hold.patron") }}?patron_code=${patronCode}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                displayPatronReservations(data.data);
            } else {
                document.getElementById('patronReservations').innerHTML = `
                    <div class="text-center text-red-400 py-4">
                        <p class="text-sm">${data.message}</p>
                    </div>
                `;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('patronReservations').innerHTML = `
                <div class="text-center text-red-400 py-4">
                    <p class="text-sm">{{ __("Có lỗi xảy ra khi tải danh sách") }}</p>
                </div>
            `;
        });
}

// Display patron reservations
function displayPatronReservations(data) {
    const container = document.getElementById('patronReservations');
    
    if (data.reservations.length === 0) {
        container.innerHTML = `
            <div class="text-center text-gray-500 py-8">
                <svg class="w-12 h-12 mx-auto mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <p class="text-sm">{{ __("Bạn đọc không có reservation nào đang hoạt động") }}</p>
            </div>
        `;
        return;
    }
    
    let html = `
        <div class="space-y-2">
            <div class="text-sm text-gray-400 mb-3">
                {{ __("Tổng số") }}: ${data.total_count} {{ __("reservation") }}
            </div>
    `;
    
    data.reservations.forEach(reservation => {
        html += `
            <div class="p-3 bg-gray-800/50 rounded-lg hover:bg-gray-800/70 transition-colors">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <div class="font-medium text-sm">${reservation.book_title}</div>
                        <div class="text-xs text-gray-400">
                            ${reservation.author} • ${reservation.barcode}
                        </div>
                        <div class="text-xs mt-1 space-y-1">
                            <div class="${reservation.status_color}">${reservation.status_display}</div>
                            <div>{{ __("Đặt giữ") }}: ${reservation.reservation_date}</div>
                            <div>{{ __("Hết hạn") }}: ${reservation.expiry_date}</div>
                            ${reservation.position ? `<div>{{ __("Vị trí trong hàng chờ") }}: #${reservation.position}</div>` : ''}
                            <div>{{ __("Điểm nhận") }}: ${reservation.pickup_branch}</div>
                        </div>
                    </div>
                    <div class="flex space-x-2 ml-3">
                        ${reservation.status === 'ready' ? `
                            <button onclick="fulfillReservation(${reservation.id})" 
                                    class="text-green-400 hover:text-green-300 text-xs" 
                                    title="{{ __("Cho mượn") }}">
                                <i class="fas fa-hand-holding"></i>
                            </button>
                        ` : ''}
                        <button onclick="cancelReservation(${reservation.id})" 
                                class="text-red-400 hover:text-red-300 text-xs" 
                                title="{{ __("Hủy") }}">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                ${reservation.notes ? `<div class="text-xs text-gray-400 mt-2 italic">${reservation.notes}</div>` : ''}
            </div>
        `;
    });
    
    html += `</div>`;
    container.innerHTML = html;
}

// Cancel reservation
function cancelReservation(reservationId) {
    Swal.fire({
        title: '{{ __("Xác nhận hủy") }}',
        text: '{{ __("Bạn có chắc muốn hủy reservation này?") }}',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#6b7280',
        confirmButtonText: '{{ __("Hủy") }}',
        cancelButtonText: '{{ __("Không") }}'
    }).then((result) => {
        if (result.isConfirmed) {
            // Show loading
            Swal.fire({
                title: '{{ __("Đang xử lý") }}',
                text: '{{ __("Đang hủy reservation...") }}',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            fetch('{{ route("admin.circulation.hold.cancel") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    reservation_id: reservationId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: '{{ __("Thành công") }}',
                        text: data.message,
                        confirmButtonColor: '#3b82f6'
                    });
                    
                    // Reload reservations
                    loadPatronReservations();
                    loadAllReservations();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: '{{ __("Lỗi") }}',
                        text: data.message,
                        confirmButtonColor: '#3b82f6'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: '{{ __("Lỗi") }}',
                    text: '{{ __("Có lỗi xảy ra. Vui lòng thử lại.") }}',
                    confirmButtonColor: '#3b82f6'
                });
            });
        }
    });
}

// Fulfill reservation (convert to loan)
function fulfillReservation(reservationId) {
    Swal.fire({
        title: '{{ __("Xác nhận cho mượn") }}',
        text: '{{ __("Chuyển reservation thành mượn sách?") }}',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#10b981',
        cancelButtonColor: '#6b7280',
        confirmButtonText: '{{ __("Cho mượn") }}',
        cancelButtonText: '{{ __("Hủy") }}'
    }).then((result) => {
        if (result.isConfirmed) {
            // Show loading
            Swal.fire({
                title: '{{ __("Đang xử lý") }}',
                text: '{{ __("Đang cho mượn sách...") }}',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            fetch('{{ route("admin.circulation.hold.fulfill") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    reservation_id: reservationId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: '{{ __("Thành công") }}',
                        html: `
                            ${data.message}<br><br>
                            <small>
                                <strong>{{ __("Hạn trả") }}:</strong> ${data.data.due_date}<br>
                                <strong>{{ __("Ngày mượn") }}:</strong> ${data.data.loan_date}
                            </small>
                        `,
                        confirmButtonColor: '#3b82f6'
                    });
                    
                    // Reload reservations
                    loadPatronReservations();
                    loadAllReservations();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: '{{ __("Lỗi") }}',
                        text: data.message,
                        confirmButtonColor: '#3b82f6'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: '{{ __("Lỗi") }}',
                    text: '{{ __("Có lỗi xảy ra. Vui lòng thử lại.") }}',
                    confirmButtonColor: '#3b82f6'
                });
            });
        }
    });
}

// Load all active reservations
function loadAllReservations() {
    fetch('{{ route("admin.circulation.hold.all") }}')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                displayAllReservations(data.data);
            } else {
                document.getElementById('allReservations').innerHTML = `
                    <div class="text-center text-red-400 py-4">
                        <p class="text-sm">${data.message}</p>
                    </div>
                `;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('allReservations').innerHTML = `
                <div class="text-center text-red-400 py-4">
                    <p class="text-sm">{{ __("Có lỗi xảy ra khi tải danh sách") }}</p>
                </div>
            `;
        });
}

// Display all reservations
function displayAllReservations(data) {
    const container = document.getElementById('allReservations');
    
    if (data.reservations.length === 0) {
        container.innerHTML = `
            <div class="text-center text-gray-500 py-4">
                <p class="text-sm">{{ __("Không có reservation nào đang hoạt động") }}</p>
            </div>
        `;
        return;
    }
    
    let html = `
        <div class="mb-3 text-sm">
            <span class="text-gray-400">{{ __("Tổng số") }}: ${data.total_count}</span>
            <span class="text-green-400 ml-3">{{ __("Sẵn sàng") }}: ${data.ready_count}</span>
            <span class="text-yellow-400 ml-3">{{ __("Đang chờ") }}: ${data.pending_count}</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-800 text-xs uppercase">
                    <tr>
                        <th class="p-2 text-left">{{ __("Bạn đọc") }}</th>
                        <th class="p-2 text-left">{{ __("Tài liệu") }}</th>
                        <th class="p-2 text-left">{{ __("Mã vạch") }}</th>
                        <th class="p-2 text-left">{{ __("Đặt giữ") }}</th>
                        <th class="p-2 text-left">{{ __("Hết hạn") }}</th>
                        <th class="p-2 text-left">{{ __("Trạng thái") }}</th>
                        <th class="p-2 text-left">{{ __("Vị trí") }}</th>
                        <th class="p-2 text-left">{{ __("Hành động") }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700">
    `;
    
    data.reservations.forEach(reservation => {
        html += `
            <tr class="hover:bg-gray-800/50">
                <td class="p-2">
                    <div class="font-medium text-xs">${reservation.patron_name}</div>
                    <div class="text-xs text-gray-400">${reservation.patron_code}</div>
                </td>
                <td class="p-2">
                    <div class="text-xs max-w-[200px] truncate" title="${reservation.book_title}">
                        ${reservation.book_title}
                    </div>
                </td>
                <td class="p-2 text-xs font-mono">${reservation.barcode}</td>
                <td class="p-2 text-xs">${reservation.reservation_date}</td>
                <td class="p-2 text-xs ${reservation.is_expired ? 'text-red-400' : ''}">${reservation.expiry_date}</td>
                <td class="p-2">
                    <span class="px-2 py-1 rounded text-xs font-bold ${reservation.status_color}">
                        ${reservation.status_display}
                    </span>
                </td>
                <td class="p-2 text-xs">${reservation.position || '-'}</td>
                <td class="p-2">
                    <div class="flex space-x-2">
                        ${reservation.status === 'ready' ? `
                            <button onclick="fulfillReservation(${reservation.id})" 
                                    class="text-green-400 hover:text-green-300 text-xs" 
                                    title="{{ __("Cho mượn") }}">
                                <i class="fas fa-hand-holding"></i>
                            </button>
                        ` : ''}
                        <button onclick="cancelReservation(${reservation.id})" 
                                class="text-red-400 hover:text-red-300 text-xs" 
                                title="{{ __("Hủy") }}">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `;
    });
    
    html += `
                </tbody>
            </table>
        </div>
    `;
    
    container.innerHTML = html;
}

// Update switchTab function to handle hold tab
function switchTab(tabName) {
    // Hide all tabs
    document.getElementById('checkoutContent').classList.add('hidden');
    document.getElementById('checkinContent').classList.add('hidden');
    document.getElementById('readingRoomContent').classList.add('hidden');
    document.getElementById('holdContent').classList.add('hidden');
    
    // Remove active styles from all tabs
    document.getElementById('checkoutTab').classList.remove('border-green-500', 'text-green-600', 'bg-green-50', 'dark:bg-green-900/20', 'dark:text-green-400');
    document.getElementById('checkinTab').classList.remove('border-blue-500', 'text-blue-600', 'bg-blue-50', 'dark:bg-blue-900/20', 'dark:text-blue-400');
    document.getElementById('readingRoomTab').classList.remove('border-purple-500', 'text-purple-600', 'bg-purple-50', 'dark:bg-purple-900/20', 'dark:text-purple-400');
    document.getElementById('holdTab').classList.remove('border-orange-500', 'text-orange-600', 'bg-orange-50', 'dark:bg-orange-900/20', 'dark:text-orange-400');
    
    // Add inactive styles to all tabs
    document.getElementById('checkoutTab').classList.add('border-transparent', 'text-gray-600', 'dark:text-gray-400');
    document.getElementById('checkinTab').classList.add('border-transparent', 'text-gray-600', 'dark:text-gray-400');
    document.getElementById('readingRoomTab').classList.add('border-transparent', 'text-gray-600', 'dark:text-gray-400');
    document.getElementById('holdTab').classList.add('border-transparent', 'text-gray-600', 'dark:text-gray-400');
    
    // Show selected tab and add active styles
    if (tabName === 'checkout') {
        document.getElementById('checkoutContent').classList.remove('hidden');
        document.getElementById('checkoutTab').classList.remove('border-transparent', 'text-gray-600', 'dark:text-gray-400');
        document.getElementById('checkoutTab').classList.add('border-green-500', 'text-green-600', 'bg-green-50', 'dark:bg-green-900/20', 'dark:text-green-400');
    } else if (tabName === 'checkin') {
        document.getElementById('checkinContent').classList.remove('hidden');
        document.getElementById('checkinTab').classList.remove('border-transparent', 'text-gray-600', 'dark:text-gray-400');
        document.getElementById('checkinTab').classList.add('border-blue-500', 'text-blue-600', 'bg-blue-50', 'dark:bg-blue-900/20', 'dark:text-blue-400');
    } else if (tabName === 'reading-room') {
        document.getElementById('readingRoomContent').classList.remove('hidden');
        document.getElementById('readingRoomTab').classList.remove('border-transparent', 'text-gray-600', 'dark:text-gray-400');
        document.getElementById('readingRoomTab').classList.add('border-purple-500', 'text-purple-600', 'bg-purple-50', 'dark:bg-purple-900/20', 'dark:text-purple-400');
        
        // Load all reading room transactions when tab is opened
        loadAllReadingRoomTransactions();
    } else if (tabName === 'hold') {
        document.getElementById('holdContent').classList.remove('hidden');
        document.getElementById('holdTab').classList.remove('border-transparent', 'text-gray-600', 'dark:text-gray-400');
        document.getElementById('holdTab').classList.add('border-orange-500', 'text-orange-600', 'bg-orange-50', 'dark:bg-orange-900/20', 'dark:text-orange-400');
        
        // Load all reservations when tab is opened
        loadAllReservations();
    }
}
</script>

@endsection
