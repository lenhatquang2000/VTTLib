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
            <h1 class="text-2xl font-bold">{{ __('Loan_Desk') }}</h1>
            <p class="text-sm text-gray-400 mt-1">{{ __('Checkout_and_checkin_books') }}</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.circulation.index') }}" class="btn-secondary">
                {{ __('Policies') }}
            </a>
            <a href="{{ route('admin.circulation.fines') }}" class="btn-secondary">
                {{ __('Fines') }}
            </a>
        </div>
    </div>

    <!-- Tabs Navigation -->
    <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-gray-100 dark:border-slate-800">
        <div class="flex border-b border-gray-200 dark:border-slate-700">
            <button type="button" onclick="switchTab('checkout')" id="checkoutTab" 
                class="flex-1 py-4 px-6 text-sm font-semibold transition-all duration-200 focus:outline-none flex items-center justify-center space-x-2 bg-green-50 dark:bg-green-900/20 text-green-600 dark:text-green-400 border-b-2 border-green-600 dark:border-green-400">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                <span>{{ __('Mượn sách') }} ({{ __('Mượn') }})</span>
            </button>
            <button type="button" onclick="switchTab('checkin')" id="checkinTab"
                class="flex-1 py-4 px-6 text-sm font-semibold transition-all duration-200 focus:outline-none flex items-center justify-center space-x-2 text-gray-500 dark:text-slate-400 hover:text-gray-700 dark:hover:text-slate-300 hover:bg-gray-50 dark:hover:bg-slate-800">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 15l-6-6m0 0l-6 6m6-6v12"></path>
                </svg>
                <span>{{ __('Trả sách') }} ({{ __('Trả') }})</span>
            </button>
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
                                    <button type="button" onclick="searchPatron()" 
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
                        <!-- Patron Information -->
                        <div class="bg-gray-800 dark:bg-slate-800 rounded-lg p-4">
                            <h4 class="text-sm font-bold text-gray-300 mb-3 flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                {{ __('Thông tin Bạn đọc') }}
                            </h4>
                            <div id="patronInfo" class="space-y-2">
                                <div class="text-center text-gray-500 text-sm py-8">
                                    <svg class="w-12 h-12 mx-auto mb-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    <p>{{ __('Nhập mã bạn đọc để hiển thị thông tin') }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Book Information -->
                        <div class="bg-gray-800 dark:bg-slate-800 rounded-lg p-4">
                            <h4 class="text-sm font-bold text-gray-300 mb-3 flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                                {{ __('Thông tin Sách') }}
                            </h4>
                            <div id="bookSearchResult" class="space-y-2">
                                <div class="text-center text-gray-500 text-sm py-8">
                                    <svg class="w-12 h-12 mx-auto mb-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                    </svg>
                                    <p>{{ __('Nhập mã vạch sách để hiển thị thông tin') }}</p>
                                </div>
                            </div>
                        </div>
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
                            <form action="{{ route('admin.circulation.renew', $loan) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="text-blue-400 hover:text-blue-300 text-xs" 
                                    {{ !$loan->canRenew() ? 'disabled' : '' }}>
                                    {{ __('Renew') }}
                                </button>
                            </form>
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
            <h3 class="text-lg font-bold">{{ __('Recent_Transactions') }}</h3>
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
                                @if($loan->canRenew())
                                <form action="{{ route('admin.circulation.renew', $loan) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-blue-400 hover:text-blue-300 text-xs mr-2">
                                        {{ __('Renew') }}
                                    </button>
                                </form>
                                @endif
                                <span class="text-xs text-gray-500">({{ $loan->renewal_count }}/{{ $loan->policy->max_renewals ?? '?' }})</span>
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
    const infoDiv = document.getElementById('patronInfo');
    
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
                <div class="text-center py-6">
                    <div class="flex justify-center mb-4">
                        <div class="w-24 h-24 rounded-full overflow-hidden">
                            ${patron.data.profile_image ? 
                                `<img src="${patron.data.profile_image}" alt="${patron.data.display_name || 'Patron'}" class="w-full h-full object-cover">` :
                                `<div class="w-full h-full bg-gray-700 flex items-center justify-center">
                                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>`
                            }
                        </div>
                    </div>
                    <h4 class="text-lg font-semibold text-white mb-1">
                        ${patron.data.display_name || patron.data.user?.name || 'N/A'}
                    </h4>
                    <p class="text-sm text-gray-400 font-mono mb-2">${patron.data.patron_code}</p>
                    <div class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium ${canBorrow ? 'bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-300' : 'bg-red-100 dark:bg-red-900/50 text-red-800 dark:text-red-300'} mb-4">
                        ${statusIcon}
                        <span class="ml-1">${borrowingStatus}</span>
                    </div>
                    <div class="grid grid-cols-2 gap-4 text-sm mb-4">
                        <div>
                            <span class="text-gray-400">{{ __("Số sách đang mượn") }}:</span>
                            <span class="text-white font-medium ml-1">${loans}</span>
                        </div>
                        <div>
                            <span class="text-gray-400">{{ __("Phí chưa trả") }}:</span>
                            <span class="text-white font-medium ml-1">${outstandingFine.toLocaleString('vi-VN')}đ</span>
                        </div>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="grid grid-cols-2 gap-3 mb-4">
                        <button onclick="showRecallModal()" class="flex items-center justify-center px-3 py-2 bg-yellow-600 hover:bg-yellow-700 text-white text-sm font-medium rounded-lg transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                            </svg>
                            {{ __("Triệu hồi") }}
                        </button>
                        <button onclick="showDeclareLostModal()" class="flex items-center justify-center px-3 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                            {{ __("Khai báo mất") }}
                        </button>
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
                </div>
            `;
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
    const resultDiv = document.getElementById('bookSearchResult');
    
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
</style>
@endsection
