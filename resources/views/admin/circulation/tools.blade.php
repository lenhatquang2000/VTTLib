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
            <h1 class="text-2xl font-bold">{{ __('Công cụ Lưu thông') }}</h1>
            <p class="text-sm text-gray-400 mt-1">{{ __('Công cụ quản lý và tra cứu lưu thông') }}</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.circulation.loan-desk') }}" class="btn-secondary">
                <i class="fas fa-arrow-left mr-2"></i>{{ __('Quay lại Loan Desk') }}
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <!-- Sidebar -->
        <div class="lg:col-span-1">
            <div class="card-admin p-4">
                <h3 class="text-lg font-bold mb-4 text-blue-400">{{ __('Công cụ') }}</h3>
                <nav class="space-y-2">
                    <button onclick="showTool('book-history')" 
                            class="tool-btn w-full text-left px-4 py-3 rounded-lg transition-colors hover:bg-blue-900/20 flex items-center"
                            data-tool="book-history">
                        <i class="fas fa-history mr-3 text-blue-400"></i>
                        <div>
                            <div class="font-medium">{{ __('Lịch sử tài liệu') }}</div>
                            <div class="text-xs text-gray-400">{{ __('Xem lịch sử mượn/trả tài liệu') }}</div>
                        </div>
                    </button>
                    
                    <button onclick="showTool('patron-history')" 
                            class="tool-btn w-full text-left px-4 py-3 rounded-lg transition-colors hover:bg-blue-900/20 flex items-center"
                            data-tool="patron-history">
                        <i class="fas fa-user-history mr-3 text-green-400"></i>
                        <div>
                            <div class="font-medium">{{ __('Lịch sử bạn đọc') }}</div>
                            <div class="text-xs text-gray-400">{{ __('Xem lịch sử mượn của bạn đọc') }}</div>
                        </div>
                    </button>
                    
                    <button onclick="showTool('book-search')" 
                            class="tool-btn w-full text-left px-4 py-3 rounded-lg transition-colors hover:bg-blue-900/20 flex items-center"
                            data-tool="book-search">
                        <i class="fas fa-search mr-3 text-purple-400"></i>
                        <div>
                            <div class="font-medium">{{ __('Tìm kiếm tài liệu') }}</div>
                            <div class="text-xs text-gray-400">{{ __('Tìm kiếm tài liệu nâng cao') }}</div>
                        </div>
                    </button>
                    
                    <button onclick="showTool('system-logs')" 
                            class="tool-btn w-full text-left px-4 py-3 rounded-lg transition-colors hover:bg-blue-900/20 flex items-center"
                            data-tool="system-logs">
                        <i class="fas fa-file-alt mr-3 text-yellow-400"></i>
                        <div>
                            <div class="font-medium">{{ __('Nhật ký hệ thống') }}</div>
                            <div class="text-xs text-gray-400">{{ __('Xem log hoạt động lưu thông') }}</div>
                        </div>
                    </button>
                    
                    <button onclick="showTool('settings')" 
                            class="tool-btn w-full text-left px-4 py-3 rounded-lg transition-colors hover:bg-blue-900/20 flex items-center"
                            data-tool="settings">
                        <i class="fas fa-cog mr-3 text-gray-400"></i>
                        <div>
                            <div class="font-medium">{{ __('Cấu hình dịch vụ') }}</div>
                            <div class="text-xs text-gray-400">{{ __('Cài đặt lưu thông') }}</div>
                        </div>
                    </button>
                </nav>
            </div>
        </div>

        <!-- Main Content -->
        <div class="lg:col-span-3">
            <!-- Book History Tool -->
            <div id="book-history-tool" class="tool-content">
                <div class="card-admin">
                    <div class="p-6 border-b border-gray-700">
                        <h3 class="text-xl font-bold text-blue-400">{{ __('Lịch sử lưu thông tài liệu') }}</h3>
                        <p class="text-sm text-gray-400 mt-1">{{ __('Tra cứu lịch sử mượn/trả của một tài liệu cụ thể') }}</p>
                    </div>
                    <div class="p-6">
                        <form id="bookHistoryForm" class="space-y-4">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium mb-1">{{ __('Mã vạch tài liệu') }} *</label>
                                    <div class="relative">
                                        <input type="text" id="history_barcode" name="barcode" required 
                                               class="input-field w-full pr-10" 
                                               placeholder="{{ __('Nhập hoặc quét mã vạch') }}">
                                        <button type="button" onclick="searchBook()" 
                                                class="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-blue-400 transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-1">{{ __('Loại lịch sử') }}</label>
                                    <select id="history_type" name="history_type" class="input-field w-full">
                                        <option value="all">{{ __('Tất cả') }}</option>
                                        <option value="loan">{{ __('Chỉ mượn') }}</option>
                                        <option value="reservation">{{ __('Chỉ reservation') }}</option>
                                        <option value="reading_room">{{ __('Chỉ mượn đọc') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="flex gap-3">
                                <button type="button" onclick="loadBookHistory()" 
                                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded font-medium">
                                    <i class="fas fa-search mr-2"></i>{{ __('Tra cứu') }}
                                </button>
                                <button type="button" onclick="clearBookHistory()" 
                                        class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded font-medium">
                                    <i class="fas fa-times mr-2"></i>{{ __('Xóa') }}
                                </button>
                            </div>
                        </form>
                        
                        <!-- Book Info Display -->
                        <div id="bookInfoDisplay" class="mt-6 hidden">
                            <div class="bg-gray-800/50 rounded-lg p-4">
                                <h4 class="font-medium text-blue-400 mb-3">{{ __('Thông tin tài liệu') }}</h4>
                                <div id="bookInfoContent"></div>
                            </div>
                        </div>
                        
                        <!-- History Results -->
                        <div id="bookHistoryResults" class="mt-6">
                            <div class="text-center text-gray-500 py-8">
                                <svg class="w-12 h-12 mx-auto mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <p class="text-sm">{{ __('Nhập mã vạch tài liệu để tra cứu lịch sử') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Patron History Tool -->
            <div id="patron-history-tool" class="tool-content hidden">
                <div class="card-admin">
                    <div class="p-6 border-b border-gray-700">
                        <h3 class="text-xl font-bold text-green-400">{{ __('Lịch sử bạn đọc') }}</h3>
                        <p class="text-sm text-gray-400 mt-1">{{ __('Tra cứu lịch sử mượn sách của bạn đọc') }}</p>
                    </div>
                    <div class="p-6">
                        <form id="patronHistoryForm" class="space-y-4">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium mb-1">{{ __('Mã bạn đọc') }} *</label>
                                    <div class="relative">
                                        <input type="text" id="patron_history_code" name="patron_code" required 
                                               class="input-field w-full pr-10" 
                                               placeholder="{{ __('Nhập hoặc quét mã bạn đọc') }}">
                                        <button type="button" onclick="searchPatron()" 
                                                class="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-blue-400 transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-1">{{ __('Khoảng thời gian') }}</label>
                                    <select id="patron_history_period" name="period" class="input-field w-full">
                                        <option value="all">{{ __('Tất cả thời gian') }}</option>
                                        <option value="30">{{ __('30 ngày gần nhất') }}</option>
                                        <option value="90">{{ __('3 tháng gần nhất') }}</option>
                                        <option value="365">{{ __('1 năm gần nhất') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="flex gap-3">
                                <button type="button" onclick="loadPatronHistory()" 
                                        class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded font-medium">
                                    <i class="fas fa-search mr-2"></i>{{ __('Tra cứu') }}
                                </button>
                                <button type="button" onclick="clearPatronHistory()" 
                                        class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded font-medium">
                                    <i class="fas fa-times mr-2"></i>{{ __('Xóa') }}
                                </button>
                            </div>
                        </form>
                        
                        <!-- Patron Info Display -->
                        <div id="patronInfoDisplay" class="mt-6 hidden">
                            <div class="bg-gray-800/50 rounded-lg p-4">
                                <h4 class="font-medium text-green-400 mb-3">{{ __('Thông tin bạn đọc') }}</h4>
                                <div id="patronInfoContent"></div>
                            </div>
                        </div>
                        
                        <!-- History Results -->
                        <div id="patronHistoryResults" class="mt-6">
                            <div class="text-center text-gray-500 py-8">
                                <svg class="w-12 h-12 mx-auto mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                <p class="text-sm">{{ __('Nhập mã bạn đọc để tra cứu lịch sử') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Book Search Tool -->
            <div id="book-search-tool" class="tool-content hidden">
                <div class="card-admin">
                    <div class="p-6 border-b border-gray-700">
                        <h3 class="text-xl font-bold text-purple-400">{{ __('Tìm kiếm tài liệu nâng cao') }}</h3>
                        <p class="text-sm text-gray-400 mt-1">{{ __('Tìm kiếm tài liệu với nhiều tiêu chí') }}</p>
                    </div>
                    <div class="p-6">
                        <form id="bookSearchForm" class="space-y-4">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium mb-1">{{ __('Tên tài liệu') }}</label>
                                    <input type="text" id="search_title" name="title" 
                                           class="input-field w-full" 
                                           placeholder="{{ __('Nhập tên tài liệu') }}">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-1">{{ __('Tác giả') }}</label>
                                    <input type="text" id="search_author" name="author" 
                                           class="input-field w-full" 
                                           placeholder="{{ __('Nhập tác giả') }}">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-1">{{ __('Mã vạch') }}</label>
                                    <input type="text" id="search_barcode" name="barcode" 
                                           class="input-field w-full" 
                                           placeholder="{{ __('Nhập mã vạch') }}">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-1">{{ __('Trạng thái') }}</label>
                                    <select id="search_status" name="status" class="input-field w-full">
                                        <option value="">{{ __('Tất cả') }}</option>
                                        <option value="available">{{ __('Sẵn có') }}</option>
                                        <option value="on_loan">{{ __('Đang mượn') }}</option>
                                        <option value="reserved">{{ __('Để dành') }}</option>
                                        <option value="in_reading_room">{{ __('Mượn đọc') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="flex gap-3">
                                <button type="button" onclick="searchBooksAdvanced()" 
                                        class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-2 rounded font-medium">
                                    <i class="fas fa-search mr-2"></i>{{ __('Tìm kiếm') }}
                                </button>
                                <button type="button" onclick="clearBookSearch()" 
                                        class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded font-medium">
                                    <i class="fas fa-times mr-2"></i>{{ __('Xóa') }}
                                </button>
                            </div>
                        </form>
                        
                        <!-- Search Results -->
                        <div id="bookSearchResults" class="mt-6">
                            <div class="text-center text-gray-500 py-8">
                                <svg class="w-12 h-12 mx-auto mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                                <p class="text-sm">{{ __('Nhập tiêu chí tìm kiếm') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- System Logs Tool -->
            <div id="system-logs-tool" class="tool-content hidden">
                <div class="card-admin">
                    <div class="p-6 border-b border-gray-700">
                        <h3 class="text-xl font-bold text-yellow-400">{{ __('Nhật ký hệ thống') }}</h3>
                        <p class="text-sm text-gray-400 mt-1">{{ __('Xem log hoạt động lưu thông') }}</p>
                    </div>
                    <div class="p-6">
                        <div class="bg-yellow-900/20 border border-yellow-500 text-yellow-400 p-4 rounded-lg">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="font-medium">{{ __("Tính năng đang phát triển") }}</span>
                            </div>
                            <p class="text-sm mt-2">{{ __("Chức năng nhật ký hệ thống sẽ được triển khai trong phiên bản tiếp theo.") }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Settings Tool -->
            <div id="settings-tool" class="tool-content hidden">
                <div class="card-admin">
                    <div class="p-6 border-b border-gray-700">
                        <h3 class="text-xl font-bold text-gray-400">{{ __('Cấu hình dịch vụ') }}</h3>
                        <p class="text-sm text-gray-400 mt-1">{{ __('Cài đặt các thông số lưu thông') }}</p>
                    </div>
                    <div class="p-6">
                        <div class="bg-gray-900/20 border border-gray-500 text-gray-400 p-4 rounded-lg">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="font-medium">{{ __("Tính năng đang phát triển") }}</span>
                            </div>
                            <p class="text-sm mt-2">{{ __("Chức năng cấu hình sẽ được triển khai trong phiên bản tiếp theo.") }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Tool switching
function showTool(toolName) {
    // Hide all tool contents
    document.querySelectorAll('.tool-content').forEach(content => {
        content.classList.add('hidden');
    });
    
    // Remove active styles from all buttons
    document.querySelectorAll('.tool-btn').forEach(btn => {
        btn.classList.remove('bg-blue-900/30', 'border-l-4', 'border-blue-500');
    });
    
    // Show selected tool
    document.getElementById(toolName + '-tool').classList.remove('hidden');
    
    // Add active style to selected button
    const activeBtn = document.querySelector(`[data-tool="${toolName}"]`);
    activeBtn.classList.add('bg-blue-900/30', 'border-l-4', 'border-blue-500');
}

// Book History Functions
function loadBookHistory() {
    const barcode = document.getElementById('history_barcode').value.trim();
    const historyType = document.getElementById('history_type').value;
    
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
    const resultsDiv = document.getElementById('bookHistoryResults');
    resultsDiv.innerHTML = `
        <div class="text-center py-8">
            <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-500"></div>
            <p class="text-sm text-gray-400 mt-2">{{ __("Đang tải lịch sử...") }}</p>
        </div>
    `;
    
    fetch(`{{ route('admin.circulation.book-history') }}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            barcode: barcode,
            history_type: historyType
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            displayBookHistory(data);
        } else {
            resultsDiv.innerHTML = `
                <div class="text-center text-red-400 py-8">
                    <svg class="w-12 h-12 mx-auto mb-3 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-sm">${data.message}</p>
                </div>
            `;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        resultsDiv.innerHTML = `
            <div class="text-center text-red-400 py-8">
                <p class="text-sm">{{ __("Có lỗi xảy ra. Vui lòng thử lại.") }}</p>
            </div>
        `;
    });
}

function displayBookHistory(data) {
    // Show book info
    const bookInfoDiv = document.getElementById('bookInfoDisplay');
    const bookInfoContent = document.getElementById('bookInfoContent');
    
    bookInfoContent.innerHTML = `
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
            <div>
                <span class="text-gray-400">{{ __("Tên tài liệu") }}:</span>
                <span class="ml-2 font-medium">${data.book_info.title}</span>
            </div>
            <div>
                <span class="text-gray-400">{{ __("Tác giả") }}:</span>
                <span class="ml-2">${data.book_info.author}</span>
            </div>
            <div>
                <span class="text-gray-400">{{ __("Mã vạch") }}:</span>
                <span class="ml-2 font-mono">${data.book_info.barcode}</span>
            </div>
            <div>
                <span class="text-gray-400">{{ __("Trạng thái") }}:</span>
                <span class="ml-2">${data.book_info.status}</span>
            </div>
        </div>
    `;
    bookInfoDiv.classList.remove('hidden');
    
    // Display history
    const resultsDiv = document.getElementById('bookHistoryResults');
    
    if (data.history.length === 0) {
        resultsDiv.innerHTML = `
            <div class="text-center text-gray-500 py-8">
                <svg class="w-12 h-12 mx-auto mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <p class="text-sm">{{ __("Không có lịch sử nào") }}</p>
            </div>
        `;
        return;
    }
    
    let html = `
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-800 text-xs uppercase">
                    <tr>
                        <th class="p-3 text-left">{{ __("Ngày") }}</th>
                        <th class="p-3 text-left">{{ __("Bạn đọc") }}</th>
                        <th class="p-3 text-left">{{ __("Loại") }}</th>
                        <th class="p-3 text-left">{{ __("Chi tiết") }}</th>
                        <th class="p-3 text-left">{{ __("Thủ thư") }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700">
    `;
    
    data.history.forEach(item => {
        const typeColor = item.type === 'loan' ? 'text-green-400' : 
                         item.type === 'return' ? 'text-blue-400' : 
                         item.type === 'reservation' ? 'text-yellow-400' : 'text-purple-400';
        
        html += `
            <tr class="hover:bg-gray-800/50">
                <td class="p-3">${item.date}</td>
                <td class="p-3">
                    <div class="font-medium">${item.patron_name}</div>
                    <div class="text-xs text-gray-400">${item.patron_code}</div>
                </td>
                <td class="p-3">
                    <span class="px-2 py-1 rounded text-xs font-bold ${typeColor}">
                        ${item.type_display}
                    </span>
                </td>
                <td class="p-3 text-xs">${item.details}</td>
                <td class="p-3 text-xs text-gray-400">${item.staff_name || '-'}</td>
            </tr>
        `;
    });
    
    html += `
                </tbody>
            </table>
        </div>
    `;
    
    resultsDiv.innerHTML = html;
}

function clearBookHistory() {
    document.getElementById('bookHistoryForm').reset();
    document.getElementById('bookInfoDisplay').classList.add('hidden');
    document.getElementById('bookHistoryResults').innerHTML = `
        <div class="text-center text-gray-500 py-8">
            <svg class="w-12 h-12 mx-auto mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <p class="text-sm">{{ __("Nhập mã vạch tài liệu để tra cứu lịch sử") }}</p>
        </div>
    `;
}

// Patron History Functions
function loadPatronHistory() {
    const patronCode = document.getElementById('patron_history_code').value.trim();
    const period = document.getElementById('patron_history_period').value;
    
    if (!patronCode) {
        Swal.fire({
            icon: 'warning',
            title: '{{ __("Thông báo") }}',
            text: '{{ __("Vui lòng nhập mã bạn đọc") }}',
            confirmButtonColor: '#3b82f6'
        });
        return;
    }
    
    // Show loading
    const resultsDiv = document.getElementById('patronHistoryResults');
    resultsDiv.innerHTML = `
        <div class="text-center py-8">
            <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-green-500"></div>
            <p class="text-sm text-gray-400 mt-2">{{ __("Đang tải lịch sử...") }}</p>
        </div>
    `;
    
    fetch(`{{ route('admin.circulation.patron-history') }}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            patron_code: patronCode,
            period: period
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            displayPatronHistory(data);
        } else {
            resultsDiv.innerHTML = `
                <div class="text-center text-red-400 py-8">
                    <svg class="w-12 h-12 mx-auto mb-3 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-sm">${data.message}</p>
                </div>
            `;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        resultsDiv.innerHTML = `
            <div class="text-center text-red-400 py-8">
                <p class="text-sm">{{ __("Có lỗi xảy ra. Vui lòng thử lại.") }}</p>
            </div>
        `;
    });
}

function displayPatronHistory(data) {
    // Show patron info
    const patronInfoDiv = document.getElementById('patronInfoDisplay');
    const patronInfoContent = document.getElementById('patronInfoContent');
    
    patronInfoContent.innerHTML = `
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
            <div>
                <span class="text-gray-400">{{ __("Họ tên") }}:</span>
                <span class="ml-2 font-medium">${data.patron_info.name}</span>
            </div>
            <div>
                <span class="text-gray-400">{{ __("Mã bạn đọc") }}:</span>
                <span class="ml-2 font-mono">${data.patron_info.code}</span>
            </div>
            <div>
                <span class="text-gray-400">{{ __("Nhóm bạn đọc") }}:</span>
                <span class="ml-2">${data.patron_info.group}</span>
            </div>
            <div>
                <span class="text-gray-400">{{ __("Email") }}:</span>
                <span class="ml-2">${data.patron_info.email || 'N/A'}</span>
            </div>
            <div>
                <span class="text-gray-400">{{ __("Điện thoại") }}:</span>
                <span class="ml-2">${data.patron_info.phone}</span>
            </div>
            <div>
                <span class="text-gray-400">{{ __("Ngày đăng ký") }}:</span>
                <span class="ml-2">${data.patron_info.registration_date}</span>
            </div>
        </div>
        
        <!-- Statistics -->
        <div class="mt-4 pt-4 border-t border-gray-700">
            <h5 class="font-medium mb-3 text-green-400">{{ __("Thống kê sử dụng") }}</h5>
            <div class="grid grid-cols-2 md:grid-cols-3 gap-3 text-sm">
                <div class="bg-gray-800/50 rounded p-2">
                    <div class="text-gray-400">{{ __("Tổng mượn") }}</div>
                    <div class="font-bold text-blue-400">${data.stats.total_loans}</div>
                </div>
                <div class="bg-gray-800/50 rounded p-2">
                    <div class="text-gray-400">{{ __("Đang mượn") }}</div>
                    <div class="font-bold text-green-400">${data.stats.active_loans}</div>
                </div>
                <div class="bg-gray-800/50 rounded p-2">
                    <div class="text-gray-400">{{ __("Tổng reservation") }}</div>
                    <div class="font-bold text-yellow-400">${data.stats.total_reservations}</div>
                </div>
                <div class="bg-gray-800/50 rounded p-2">
                    <div class="text-gray-400">{{ __("Reservation đang chờ") }}</div>
                    <div class="font-bold text-orange-400">${data.stats.active_reservations}</div>
                </div>
                <div class="bg-gray-800/50 rounded p-2">
                    <div class="text-gray-400">{{ __("Mượn đọc") }}</div>
                    <div class="font-bold text-purple-400">${data.stats.total_reading_room}</div>
                </div>
                <div class="bg-gray-800/50 rounded p-2">
                    <div class="text-gray-400">{{ __("Đang mượn đọc") }}</div>
                    <div class="font-bold text-pink-400">${data.stats.active_reading_room}</div>
                </div>
            </div>
        </div>
    `;
    patronInfoDiv.classList.remove('hidden');
    
    // Display history
    const resultsDiv = document.getElementById('patronHistoryResults');
    
    if (data.history.length === 0) {
        resultsDiv.innerHTML = `
            <div class="text-center text-gray-500 py-8">
                <svg class="w-12 h-12 mx-auto mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <p class="text-sm">{{ __("Không có lịch sử nào trong khoảng thời gian đã chọn") }}</p>
            </div>
        `;
        return;
    }
    
    let html = `
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-800 text-xs uppercase">
                    <tr>
                        <th class="p-3 text-left">{{ __("Ngày") }}</th>
                        <th class="p-3 text-left">{{ __("Tài liệu") }}</th>
                        <th class="p-3 text-left">{{ __("Mã vạch") }}</th>
                        <th class="p-3 text-left">{{ __("Loại") }}</th>
                        <th class="p-3 text-left">{{ __("Chi tiết") }}</th>
                        <th class="p-3 text-left">{{ __("Thủ thư") }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700">
    `;
    
    data.history.forEach(item => {
        const typeColor = item.type === 'loan' ? 'text-green-400' : 
                         item.type === 'return' ? 'text-blue-400' : 
                         item.type === 'reservation' ? 'text-yellow-400' : 
                         item.type === 'reading_room' ? 'text-purple-400' : 'text-pink-400';
        
        html += `
            <tr class="hover:bg-gray-800/50">
                <td class="p-3">${item.date}</td>
                <td class="p-3">
                    <div class="font-medium text-xs max-w-[200px] truncate" title="${item.book_title}">
                        ${item.book_title}
                    </div>
                    <div class="text-xs text-gray-400">${item.book_author}</div>
                </td>
                <td class="p-3 text-xs font-mono">${item.barcode}</td>
                <td class="p-3">
                    <span class="px-2 py-1 rounded text-xs font-bold ${typeColor}">
                        ${item.type_display}
                    </span>
                </td>
                <td class="p-3 text-xs">${item.details}</td>
                <td class="p-3 text-xs text-gray-400">${item.staff_name || '-'}</td>
            </tr>
        `;
    });
    
    html += `
                </tbody>
            </table>
        </div>
    `;
    
    resultsDiv.innerHTML = html;
}

function clearPatronHistory() {
    document.getElementById('patronHistoryForm').reset();
    document.getElementById('patronInfoDisplay').classList.add('hidden');
    document.getElementById('patronHistoryResults').innerHTML = `
        <div class="text-center text-gray-500 py-8">
            <svg class="w-12 h-12 mx-auto mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
            </svg>
            <p class="text-sm">{{ __("Nhập mã bạn đọc để tra cứu lịch sử") }}</p>
        </div>
    `;
}

// Advanced Book Search Functions
let currentPage = 1;
let searchCriteria = {};

function searchBooksAdvanced(page = 1) {
    const title = document.getElementById('search_title').value.trim();
    const author = document.getElementById('search_author').value.trim();
    const barcode = document.getElementById('search_barcode').value.trim();
    const status = document.getElementById('search_status').value;
    
    // Store search criteria
    searchCriteria = {
        title: title,
        author: author,
        barcode: barcode,
        status: status,
        page: page
    };
    
    if (!title && !author && !barcode && !status) {
        Swal.fire({
            icon: 'warning',
            title: '{{ __("Thông báo") }}',
            text: '{{ __("Vui lòng nhập ít nhất một tiêu chí tìm kiếm") }}',
            confirmButtonColor: '#3b82f6'
        });
        return;
    }
    
    // Show loading
    const resultsDiv = document.getElementById('bookSearchResults');
    resultsDiv.innerHTML = `
        <div class="text-center py-8">
            <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-purple-500"></div>
            <p class="text-sm text-gray-400 mt-2">{{ __("Đang tìm kiếm...") }}</p>
        </div>
    `;
    
    fetch(`{{ route('admin.circulation.advanced-search') }}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify(searchCriteria)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            currentPage = page;
            displayAdvancedSearchResults(data);
        } else {
            resultsDiv.innerHTML = `
                <div class="text-center text-red-400 py-8">
                    <svg class="w-12 h-12 mx-auto mb-3 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-sm">${data.message}</p>
                </div>
            `;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        resultsDiv.innerHTML = `
            <div class="text-center text-red-400 py-8">
                <p class="text-sm">{{ __("Có lỗi xảy ra. Vui lòng thử lại.") }}</p>
            </div>
        `;
    });
}

function displayAdvancedSearchResults(data) {
    const resultsDiv = document.getElementById('bookSearchResults');
    
    if (data.data.books.length === 0) {
        resultsDiv.innerHTML = `
            <div class="text-center text-gray-500 py-8">
                <svg class="w-12 h-12 mx-auto mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p class="text-sm">{{ __("Không tìm thấy tài liệu nào phù hợp") }}</p>
            </div>
        `;
        return;
    }
    
    // Build search summary
    const criteriaText = Object.entries(data.data.search_criteria)
        .map(([key, value]) => {
            const labels = {
                title: '{{ __("Tên tài liệu") }}',
                author: '{{ __("Tác giả") }}',
                barcode: '{{ __("Mã vạch") }}',
                status: '{{ __("Trạng thái") }}'
            };
            return `${labels[key] || key}: "${value}"`;
        })
        .join(', ');
    
    let html = `
        <!-- Search Summary -->
        <div class="bg-purple-900/20 border border-purple-500 text-purple-400 p-4 rounded-lg mb-4">
            <div class="flex justify-between items-center">
                <div>
                    <span class="font-medium">{{ __("Kết quả tìm kiếm") }}:</span>
                    <span class="ml-2">${criteriaText}</span>
                </div>
                <div class="text-sm">
                    <span class="font-medium">{{ __("Tổng") }}:</span>
                    <span class="ml-1">${data.data.stats.total_results} {{ __("kết quả") }}</span>
                </div>
            </div>
        </div>
        
        <!-- Results Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-800 text-xs uppercase">
                    <tr>
                        <th class="p-3 text-left">{{ __("Mã vạch") }}</th>
                        <th class="p-3 text-left">{{ __("Tài liệu") }}</th>
                        <th class="p-3 text-left">{{ __("Tác giả") }}</th>
                        <th class="p-3 text-left">{{ __("NXB") }}</th>
                        <th class="p-3 text-left">{{ __("Trạng thái") }}</th>
                        <th class="p-3 text-left">{{ __("Chi nhánh") }}</th>
                        <th class="p-3 text-left">{{ __("Đang mượn") }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700">
    `;
    
    data.data.books.forEach(book => {
        const statusColor = `text-${book.status_color}-400`;
        const loanInfo = book.current_loan ? `
            <div class="text-xs">
                <div class="font-medium">${book.current_loan.patron_name}</div>
                <div class="text-gray-400">${book.current_loan.patron_code}</div>
                <div class="${book.current_loan.is_overdue ? 'text-red-400' : 'text-gray-400'}">
                    ${book.current_loan.due_date}
                </div>
            </div>
        ` : '<span class="text-gray-400">-</span>';
        
        html += `
            <tr class="hover:bg-gray-800/50">
                <td class="p-3 font-mono text-xs">${book.barcode}</td>
                <td class="p-3">
                    <div class="font-medium text-xs max-w-[200px] truncate" title="${book.title}">
                        ${book.title}
                    </div>
                    <div class="text-xs text-gray-400">${book.publication_year}</div>
                </td>
                <td class="p-3 text-xs">${book.author}</td>
                <td class="p-3 text-xs">${book.publisher}</td>
                <td class="p-3">
                    <span class="px-2 py-1 rounded text-xs font-bold ${statusColor}">
                        ${book.status_display}
                    </span>
                </td>
                <td class="p-3 text-xs">${book.branch}</td>
                <td class="p-3 text-xs">${loanInfo}</td>
            </tr>
        `;
    });
    
    html += `
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        ${data.data.stats.total_pages > 1 ? `
        <div class="flex justify-between items-center mt-4">
            <div class="text-sm text-gray-400">
                {{ __("Hiển thị") }} ${(data.data.stats.current_page - 1) * data.data.stats.per_page + 1} - 
                ${Math.min(data.data.stats.current_page * data.data.stats.per_page, data.data.stats.total_results)} 
                {{ __("của") }} ${data.data.stats.total_results} {{ __("kết quả") }}
            </div>
            <div class="flex gap-2">
                ${data.data.stats.has_prev ? `
                <button onclick="searchBooksAdvanced(${data.data.stats.current_page - 1})" 
                        class="px-3 py-1 bg-gray-600 hover:bg-gray-700 text-white rounded text-sm">
                    {{ __("Trước") }}
                </button>
                ` : ''}
                
                <span class="px-3 py-1 bg-purple-600 text-white rounded text-sm">
                    ${data.data.stats.current_page} / ${data.data.stats.total_pages}
                </span>
                
                ${data.data.stats.has_next ? `
                <button onclick="searchBooksAdvanced(${data.data.stats.current_page + 1})" 
                        class="px-3 py-1 bg-gray-600 hover:bg-gray-700 text-white rounded text-sm">
                    {{ __("Sau") }}
                </button>
                ` : ''}
            </div>
        </div>
        ` : ''}
    `;
    
    resultsDiv.innerHTML = html;
}

function clearBookSearch() {
    document.getElementById('bookSearchForm').reset();
    document.getElementById('bookSearchResults').innerHTML = `
        <div class="text-center text-gray-500 py-8">
            <svg class="w-12 h-12 mx-auto mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
            </svg>
            <p class="text-sm">{{ __("Nhập tiêu chí tìm kiếm") }}</p>
        </div>
    `;
    currentPage = 1;
    searchCriteria = {};
}

// Initialize with first tool active
document.addEventListener('DOMContentLoaded', function() {
    showTool('book-history');
});
</script>
@endsection
