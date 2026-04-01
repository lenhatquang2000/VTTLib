

<?php $__env->startSection('content'); ?>
<div class="space-y-8">
    <?php if(session('success')): ?>
        <div class="bg-green-900/20 border border-green-500 text-green-400 p-4 text-xs font-mono rounded">
            [OK] <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>
    <?php if(session('error')): ?>
        <div class="bg-red-900/20 border border-red-500 text-red-400 p-4 text-xs font-mono rounded">
            [ERROR] <?php echo e(session('error')); ?>

        </div>
    <?php endif; ?>

    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold"><?php echo e(__('Loan_Desk')); ?></h1>
            <p class="text-sm text-gray-400 mt-1"><?php echo e(__('Checkout_and_checkin_books')); ?></p>
        </div>
        <div class="flex gap-2">
            <a href="<?php echo e(route('admin.circulation.index')); ?>" class="btn-secondary">
                <?php echo e(__('Policies')); ?>

            </a>
            <a href="<?php echo e(route('admin.circulation.fines')); ?>" class="btn-secondary">
                <?php echo e(__('Fines')); ?>

            </a>
        </div>
    </div>

    <!-- Checkout/Checkin Forms -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Checkout Form -->
        <div class="card-admin rounded-lg p-6">
            <h3 class="text-lg font-bold mb-4 text-green-400"><?php echo e(__('Checkout')); ?> (<?php echo e(__('Loan')); ?>)</h3>
            <form action="<?php echo e(route('admin.circulation.checkout')); ?>" method="POST" class="space-y-4">
                <?php echo csrf_field(); ?>
                <div class="relative">
                    <label class="block text-sm font-medium mb-1"><?php echo e(__('Patron_Code')); ?> *</label>
                    <div class="relative">
                        <input type="text" id="patron_code" name="patron_code" required class="input-field w-full pr-10" 
                            placeholder="<?php echo e(__('Scan_or_enter_patron_code')); ?>" autofocus>
                        <button type="button" onclick="searchPatron()" 
                            class="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-blue-400 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </button>
                    </div>
                    <!-- Patron Search Results -->
                    <div id="patronSearchResult" class="mt-2 hidden">
                        <!-- Results will be displayed here -->
                    </div>
                </div>
                <div class="relative">
                    <label class="block text-sm font-medium mb-1"><?php echo e(__('Book_Barcode')); ?> *</label>
                    <div class="relative">
                        <input type="text" id="book_barcode" name="barcode" required class="input-field w-full pr-10" 
                            placeholder="<?php echo e(__('Scan_or_enter_barcode')); ?>">
                        <button type="button" onclick="searchBook()" 
                            class="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-blue-400 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </button>
                    </div>
                    <!-- Book Search Results -->
                    <div id="bookSearchResult" class="mt-2 hidden">
                        <!-- Results will be displayed here -->
                    </div>
                </div>
                <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white py-3 rounded font-bold">
                    <?php echo e(__('Checkout_Book')); ?>

                </button>
            </form>
        </div>

        <!-- Checkin Form -->
        <div class="card-admin rounded-lg p-6">
            <h3 class="text-lg font-bold mb-4 text-blue-400"><?php echo e(__('Checkin')); ?> (<?php echo e(__('Return')); ?>)</h3>
            <form action="<?php echo e(route('admin.circulation.checkin')); ?>" method="POST" class="space-y-4">
                <?php echo csrf_field(); ?>
                <div>
                    <label class="block text-sm font-medium mb-1"><?php echo e(__('Book_Barcode')); ?> *</label>
                    <input type="text" name="barcode" required class="input-field w-full" 
                        placeholder="<?php echo e(__('Scan_or_enter_barcode')); ?>">
                </div>
                <div class="pt-8">
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded font-bold">
                        <?php echo e(__('Return_Book')); ?>

                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Overdue Loans Alert -->
    <?php if($overdueLoans->count() > 0): ?>
    <div class="card-admin rounded-lg overflow-hidden border-l-4 border-red-500">
        <div class="p-4 bg-red-900/20">
            <h3 class="text-lg font-bold text-red-400"><?php echo e(__('Overdue_Loans')); ?> (<?php echo e($overdueLoans->count()); ?>)</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-800 text-xs uppercase">
                    <tr>
                        <th class="p-3 text-left"><?php echo e(__('Patron')); ?></th>
                        <th class="p-3 text-left"><?php echo e(__('Book')); ?></th>
                        <th class="p-3 text-left"><?php echo e(__('Due_Date')); ?></th>
                        <th class="p-3 text-left"><?php echo e(__('Overdue_Days')); ?></th>
                        <th class="p-3 text-left"><?php echo e(__('Actions')); ?></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700">
                    <?php $__currentLoopData = $overdueLoans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $loan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr class="hover:bg-gray-800/50">
                        <td class="p-3">
                            <div class="font-medium"><?php echo e($loan->patron->display_name ?? $loan->patron->user->name); ?></div>
                            <div class="text-xs text-gray-400"><?php echo e($loan->patron->patron_code); ?></div>
                        </td>
                        <td class="p-3">
                            <div class="font-medium"><?php echo e($loan->bookItem->bibliographicRecord->title ?? 'N/A'); ?></div>
                            <div class="text-xs text-gray-400 font-mono"><?php echo e($loan->bookItem->barcode); ?></div>
                        </td>
                        <td class="p-3 text-red-400"><?php echo e($loan->due_date->format('d/m/Y')); ?></td>
                        <td class="p-3">
                            <span class="bg-red-900/50 text-red-400 px-2 py-1 rounded text-xs font-bold">
                                <?php echo e($loan->getOverdueDays()); ?> <?php echo e(__('days')); ?>

                            </span>
                        </td>
                        <td class="p-3">
                            <form action="<?php echo e(route('admin.circulation.renew', $loan)); ?>" method="POST" class="inline">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="text-blue-400 hover:text-blue-300 text-xs" 
                                    <?php echo e(!$loan->canRenew() ? 'disabled' : ''); ?>>
                                    <?php echo e(__('Renew')); ?>

                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php endif; ?>

    <!-- Recent Transactions -->
    <div class="card-admin rounded-lg overflow-hidden">
        <div class="p-4 border-b border-gray-700">
            <h3 class="text-lg font-bold"><?php echo e(__('Recent_Transactions')); ?></h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-800 text-xs uppercase">
                    <tr>
                        <th class="p-3 text-left"><?php echo e(__('Date')); ?></th>
                        <th class="p-3 text-left"><?php echo e(__('Patron')); ?></th>
                        <th class="p-3 text-left"><?php echo e(__('Book')); ?></th>
                        <th class="p-3 text-left"><?php echo e(__('Due_Date')); ?></th>
                        <th class="p-3 text-left"><?php echo e(__('Status')); ?></th>
                        <th class="p-3 text-left"><?php echo e(__('Actions')); ?></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700">
                    <?php $__empty_1 = true; $__currentLoopData = $recentLoans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $loan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-gray-800/50">
                        <td class="p-3 text-xs text-gray-400"><?php echo e($loan->loan_date->format('d/m/Y H:i')); ?></td>
                        <td class="p-3">
                            <div class="font-medium"><?php echo e($loan->patron->display_name ?? $loan->patron->user->name); ?></div>
                            <div class="text-xs text-gray-400"><?php echo e($loan->patron->patron_code); ?></div>
                        </td>
                        <td class="p-3">
                            <div class="font-medium"><?php echo e($loan->bookItem->bibliographicRecord->title ?? 'N/A'); ?></div>
                            <div class="text-xs text-gray-400 font-mono"><?php echo e($loan->bookItem->barcode); ?></div>
                        </td>
                        <td class="p-3 <?php echo e($loan->isOverdue() ? 'text-red-400' : ''); ?>">
                            <?php echo e($loan->due_date->format('d/m/Y')); ?>

                        </td>
                        <td class="p-3">
                            <?php
                                $statusClass = match($loan->status) {
                                    'borrowed' => 'bg-yellow-900/50 text-yellow-400',
                                    'returned' => 'bg-green-900/50 text-green-400',
                                    'overdue' => 'bg-red-900/50 text-red-400',
                                    'lost' => 'bg-gray-900/50 text-gray-400',
                                    default => 'bg-gray-900/50 text-gray-400'
                                };
                            ?>
                            <span class="px-2 py-1 rounded text-xs font-bold <?php echo e($statusClass); ?>">
                                <?php echo e(__(ucfirst($loan->status))); ?>

                            </span>
                        </td>
                        <td class="p-3">
                            <?php if($loan->status === 'borrowed'): ?>
                                <?php if($loan->canRenew()): ?>
                                <form action="<?php echo e(route('admin.circulation.renew', $loan)); ?>" method="POST" class="inline">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="text-blue-400 hover:text-blue-300 text-xs mr-2">
                                        <?php echo e(__('Renew')); ?>

                                    </button>
                                </form>
                                <?php endif; ?>
                                <span class="text-xs text-gray-500">(<?php echo e($loan->renewal_count); ?>/<?php echo e($loan->policy->max_renewals ?? '?'); ?>)</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="6" class="p-8 text-center text-gray-500"><?php echo e(__('No_recent_transactions')); ?></td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
// Search timeout variables
let patronSearchTimeout;
let bookSearchTimeout;

// Patron search with 0.5s delay
document.getElementById('patron_code').addEventListener('input', function() {
    clearTimeout(patronSearchTimeout);
    const value = this.value.trim();
    
    console.log('Patron input changed:', value);
    
    if (value.length >= 2) {
        patronSearchTimeout = setTimeout(() => {
            console.log('Triggering patron search after delay');
            searchPatronByCode(value);
        }, 500);
    } else {
        console.log('Hiding patron search results (input too short)');
        document.getElementById('patronSearchResult').classList.add('hidden');
    }
});

// Book search with 0.5s delay
document.getElementById('book_barcode').addEventListener('input', function() {
    clearTimeout(bookSearchTimeout);
    const value = this.value.trim();
    
    console.log('Book input changed:', value);
    
    if (value.length >= 2) {
        bookSearchTimeout = setTimeout(() => {
            console.log('Triggering book search after delay');
            searchBookByBarcode(value);
        }, 500);
    } else {
        console.log('Hiding book search results (input too short)');
        document.getElementById('bookSearchResult').classList.add('hidden');
    }
});

// Manual search functions
function searchPatron() {
    const value = document.getElementById('patron_code').value.trim();
    console.log('Manual patron search triggered:', value);
    if (value.length >= 2) {
        searchPatronByCode(value);
    } else {
        console.warn('Manual patron search failed: input too short');
    }
}

function searchBook() {
    const value = document.getElementById('book_barcode').value.trim();
    console.log('Manual book search triggered:', value);
    if (value.length >= 2) {
        searchBookByBarcode(value);
    } else {
        console.warn('Manual book search failed: input too short');
    }
}

// AJAX search functions
function searchPatronByCode(code) {
    console.log('Searching patron:', code);
    const startTime = performance.now();
    
    const url = `<?php echo e(route('admin.circulation.search-patron')); ?>?code=${encodeURIComponent(code)}`;
    console.log('Patron search URL:', url);
    
    fetch(url)
        .then(response => {
            console.log('Patron search response status:', response.status);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            const endTime = performance.now();
            console.log('Patron search completed:', data, `in ${(endTime - startTime).toFixed(2)}ms`);
            displayPatronResult(data);
        })
        .catch(error => {
            const endTime = performance.now();
            console.error('Error searching patron:', error, `after ${(endTime - startTime).toFixed(2)}ms`);
            displayPatronError();
        });
}

function searchBookByBarcode(barcode) {
    console.log('Searching book:', barcode);
    const startTime = performance.now();
    
    const url = `<?php echo e(route('admin.circulation.search-book')); ?>?barcode=${encodeURIComponent(barcode)}`;
    console.log('Book search URL:', url);
    
    fetch(url)
        .then(response => {
            console.log('Book search response status:', response.status);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            const endTime = performance.now();
            console.log('Book search completed:', data, `in ${(endTime - startTime).toFixed(2)}ms`);
            displayBookResult(data);
        })
        .catch(error => {
            const endTime = performance.now();
            console.error('Error searching book:', error, `after ${(endTime - startTime).toFixed(2)}ms`);
            displayBookError();
        });
}

// Display functions
function displayPatronResult(patron) {
    const resultDiv = document.getElementById('patronSearchResult');
    
    if (patron.success) {
        const loans = patron.data.current_loans || 0;
        const maxLoans = patron.data.max_loans || 'N/A';
        const canBorrow = patron.data.can_borrow;
        
        resultDiv.innerHTML = `
            <div class="p-3 rounded-lg border ${canBorrow ? 'bg-green-50 dark:bg-green-900/20 border-green-200 dark:border-green-500' : 'bg-red-50 dark:bg-red-900/20 border-red-200 dark:border-red-500'}">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <h4 class="font-semibold text-sm ${canBorrow ? 'text-green-800 dark:text-green-300' : 'text-red-800 dark:text-red-300'}">
                            ${patron.data.display_name || patron.data.user?.name || 'N/A'}
                        </h4>
                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">
                            <span class="font-mono">${patron.data.patron_code}</span> • ${patron.data.patron_group?.name || 'N/A'}
                        </p>
                        <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">
                            <?php echo e(__('Current_Loans')); ?>: ${loans}/${maxLoans}
                        </p>
                        ${patron.data.outstanding_fine > 0 ? `
                            <p class="text-xs text-orange-600 dark:text-orange-400 mt-1">
                                <?php echo e(__('Outstanding_Fine')); ?>: ${patron.data.outstanding_fine.toLocaleString('vi-VN')}đ
                            </p>
                        ` : ''}
                    </div>
                    <div class="ml-2">
                        ${canBorrow ? 
                            '<span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-300"><?php echo e(__("Can_Borrow")); ?></span>' :
                            '<span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 dark:bg-red-900/50 text-red-800 dark:text-red-300"><?php echo e(__("Cannot_Borrow")); ?></span>'
                        }
                    </div>
                </div>
            </div>
        `;
    } else {
        resultDiv.innerHTML = `
            <div class="p-3 rounded-lg border bg-red-50 dark:bg-red-900/20 border-red-200 dark:border-red-500">
                <p class="text-sm text-red-800 dark:text-red-300">${patron.message}</p>
            </div>
        `;
    }
    
    resultDiv.classList.remove('hidden');
}

function displayBookResult(book) {
    const resultDiv = document.getElementById('bookSearchResult');
    
    if (book.success) {
        const isAvailable = book.data.status === 'available';
        
        resultDiv.innerHTML = `
            <div class="p-3 rounded-lg border ${isAvailable ? 'bg-green-50 dark:bg-green-900/20 border-green-200 dark:border-green-500' : 'bg-red-50 dark:bg-red-900/20 border-red-200 dark:border-red-500'}">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <h4 class="font-semibold text-sm ${isAvailable ? 'text-green-800 dark:text-green-300' : 'text-red-800 dark:text-red-300'}">
                            ${book.data.title || 'N/A'}
                        </h4>
                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">
                            <?php echo e(__('Barcode')); ?>: <span class="font-mono">${book.data.barcode}</span>
                        </p>
                        <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">
                            ${book.data.author || 'N/A'} • ${book.data.call_number || 'N/A'}
                        </p>
                        ${book.data.current_loan ? `
                            <p class="text-xs text-orange-600 dark:text-orange-400 mt-1">
                                <?php echo e(__('On_loan_to')); ?>: ${book.data.current_loan.patron_name}
                            </p>
                        ` : ''}
                    </div>
                    <div class="ml-2">
                        ${isAvailable ? 
                            '<span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-300"><?php echo e(__("Available")); ?></span>' :
                            '<span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 dark:bg-red-900/50 text-red-800 dark:text-red-300"><?php echo e(__("Not_Available")); ?></span>'
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
            <p class="text-sm text-red-800 dark:text-red-300"><?php echo e(__('Search_Error')); ?></p>
        </div>
    `;
    resultDiv.classList.remove('hidden');
}

function displayBookError() {
    const resultDiv = document.getElementById('bookSearchResult');
    resultDiv.innerHTML = `
        <div class="p-3 rounded-lg border bg-red-50 dark:bg-red-900/20 border-red-200 dark:border-red-500">
            <p class="text-sm text-red-800 dark:text-red-300"><?php echo e(__('Search_Error')); ?></p>
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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\Workspace\VTTU\Laravel\VTTLib\resources\views/admin/circulation/loan-desk.blade.php ENDPATH**/ ?>