<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PatronGroup;
use App\Models\CirculationPolicy;
use App\Models\LoanTransaction;
use App\Models\Fine;
use App\Models\Reservation;
use App\Models\PatronDetail;
use App\Models\BookItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CirculationController extends Controller
{
    /**
     * Display circulation policies page
     */
    public function index()
    {
        $patronGroups = PatronGroup::with('circulationPolicies')->orderBy('order')->get();
        $policies = CirculationPolicy::with('patronGroup')->get();
        
        return view('admin.circulation.index', compact('patronGroups', 'policies'));
    }

    // ==================== PATRON GROUPS ====================

    public function storePatronGroup(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:20|unique:patron_groups',
            'description' => 'nullable|string|max:500',
            'order' => 'nullable|integer'
        ]);

        PatronGroup::create($validated);
        return back()->with('success', __('Patron group created successfully.'));
    }

    public function updatePatronGroup(Request $request, PatronGroup $patronGroup)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:20|unique:patron_groups,code,' . $patronGroup->id,
            'description' => 'nullable|string|max:500',
            'is_active' => 'boolean',
            'order' => 'nullable|integer'
        ]);

        $patronGroup->update($validated);
        return back()->with('success', __('Patron group updated successfully.'));
    }

    public function deletePatronGroup(PatronGroup $patronGroup)
    {
        // Check if has patrons
        if ($patronGroup->patrons()->exists()) {
            return back()->with('error', __('Cannot delete patron group that has associated patrons.'));
        }

        $patronGroup->delete();
        return back()->with('success', __('Patron group deleted successfully.'));
    }

    // ==================== CIRCULATION POLICIES ====================

    public function storePolicy(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'patron_group_id' => 'required|exists:patron_groups,id',
            'max_loan_days' => 'required|integer|min:1|max:365',
            'max_items' => 'required|integer|min:1|max:100',
            'max_renewals' => 'required|integer|min:0|max:10',
            'renewal_days' => 'required|integer|min:1|max:365',
            'fine_per_day' => 'required|numeric|min:0',
            'max_fine' => 'required|numeric|min:0',
            'grace_period_days' => 'required|integer|min:0|max:30',
            'can_reserve' => 'boolean',
            'max_reservations' => 'required|integer|min:0|max:20',
            'reservation_hold_days' => 'required|integer|min:1|max:30',
            'max_outstanding_fine' => 'required|numeric|min:0',
            'is_active' => 'boolean',
            'notes' => 'nullable|string|max:1000'
        ]);

        // If setting as active, deactivate others for same patron group
        if ($validated['is_active'] ?? false) {
            CirculationPolicy::where('patron_group_id', $validated['patron_group_id'])
                ->update(['is_active' => false]);
        }

        CirculationPolicy::create($validated);
        return back()->with('success', __('Circulation policy created successfully.'));
    }

    public function updatePolicy(Request $request, CirculationPolicy $policy)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'max_loan_days' => 'required|integer|min:1|max:365',
            'max_items' => 'required|integer|min:1|max:100',
            'max_renewals' => 'required|integer|min:0|max:10',
            'renewal_days' => 'required|integer|min:1|max:365',
            'fine_per_day' => 'required|numeric|min:0',
            'max_fine' => 'required|numeric|min:0',
            'grace_period_days' => 'required|integer|min:0|max:30',
            'can_reserve' => 'boolean',
            'max_reservations' => 'required|integer|min:0|max:20',
            'reservation_hold_days' => 'required|integer|min:1|max:30',
            'max_outstanding_fine' => 'required|numeric|min:0',
            'is_active' => 'boolean',
            'notes' => 'nullable|string|max:1000'
        ]);

        // If setting as active, deactivate others for same patron group
        if ($validated['is_active'] ?? false) {
            CirculationPolicy::where('patron_group_id', $policy->patron_group_id)
                ->where('id', '!=', $policy->id)
                ->update(['is_active' => false]);
        }

        $policy->update($validated);
        return back()->with('success', __('Circulation policy updated successfully.'));
    }

    public function deletePolicy(CirculationPolicy $policy)
    {
        // Check if has loan transactions
        if ($policy->loanTransactions()->exists()) {
            return back()->with('error', __('Cannot delete policy that has associated loan transactions.'));
        }

        $policy->delete();
        return back()->with('success', __('Circulation policy deleted successfully.'));
    }

    // ==================== LOAN OPERATIONS ====================

    /**
     * Loan desk page
     */
    public function loanDesk()
    {
        $recentLoans = LoanTransaction::with(['patron.user', 'bookItem.bibliographicRecord'])
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();

        $overdueLoans = LoanTransaction::with(['patron.user', 'bookItem.bibliographicRecord'])
            ->overdue()
            ->orderBy('due_date')
            ->get();
            
        // Get all lock history with patron relationships
        $allLockHistory = \App\Models\PatronLockHistory::with(['patron.user', 'lockedBy', 'unlockedBy'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Get all loan transactions for stats calculation
        $allLoanTransactions = LoanTransaction::with(['patron', 'bookItem.bibliographicRecord'])
            ->get();
        
        return view('admin.circulation.loan-desk', compact('recentLoans', 'overdueLoans', 'allLockHistory', 'allLoanTransactions'));
    }

    /**
     * Process checkout (loan)
     */
    public function checkout(Request $request)
    {
        $validated = $request->validate([
            'patron_code' => 'required|string',
            'barcode' => 'required|string',
            'loan_branch_id' => 'nullable|exists:branches,id'
        ]);

        try {
            DB::beginTransaction();

            // Find patron
            $patron = PatronDetail::where('patron_code', $validated['patron_code'])->firstOrFail();
            
            // Find book item
            $bookItem = BookItem::where('barcode', $validated['barcode'])->firstOrFail();

            // Check if patron can borrow
            if (!$patron->canBorrow()) {
                throw new \Exception(__('Patron cannot borrow. Check loan limits or outstanding fines.'));
            }

            // Check if book is available
            if ($bookItem->status !== 'available') {
                throw new \Exception(__('Book item is not available for loan.'));
            }

            // Get policy
            $policy = $patron->patronGroup?->activePolicy;
            if (!$policy) {
                throw new \Exception(__('No active circulation policy for this patron group.'));
            }

            // Create loan transaction
            $loan = LoanTransaction::create([
                'patron_detail_id' => $patron->id,
                'book_item_id' => $bookItem->id,
                'circulation_policy_id' => $policy->id,
                'loan_date' => Carbon::now(),
                'due_date' => Carbon::now()->addDays($policy->max_loan_days),
                'status' => 'borrowed',
                'loaned_by' => auth()->id(),
                'loan_branch_id' => $validated['loan_branch_id'] ?? null
            ]);

            // Update book item status
            $bookItem->update(['status' => 'on_loan']);

            DB::commit();

            return back()->with('success', __('Book checked out successfully. Due date: :date', [
                'date' => $loan->due_date->format('d/m/Y')
            ]));

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Process checkin (return)
     */
    public function checkin(Request $request)
    {
        $validated = $request->validate([
            'barcode' => 'required|string',
            'return_branch_id' => 'nullable|exists:branches,id'
        ]);

        try {
            DB::beginTransaction();

            // Find book item
            $bookItem = BookItem::where('barcode', $validated['barcode'])->firstOrFail();

            // Find active loan
            $loan = LoanTransaction::where('book_item_id', $bookItem->id)
                ->where('status', 'borrowed')
                ->firstOrFail();

            // Calculate fine if overdue
            $fine = null;
            if ($loan->isOverdue() && $loan->policy) {
                $overdueDays = $loan->getOverdueDays();
                $fineAmount = $loan->policy->calculateFine($overdueDays);

                if ($fineAmount > 0) {
                    $fine = Fine::create([
                        'patron_detail_id' => $loan->patron_detail_id,
                        'loan_transaction_id' => $loan->id,
                        'fine_type' => 'overdue',
                        'amount' => $fineAmount,
                        'status' => 'pending',
                        'description' => __('Overdue fine for :days days', ['days' => $overdueDays])
                    ]);
                }
            }

            // Update loan
            $loan->update([
                'return_date' => Carbon::now(),
                'status' => 'returned',
                'returned_to' => auth()->id(),
                'return_branch_id' => $validated['return_branch_id'] ?? null
            ]);

            // Update book item status
            $bookItem->update(['status' => 'available']);

            DB::commit();

            $message = __('Book returned successfully.');
            if ($fine) {
                $message .= ' ' . __('Fine applied: :amount VND', ['amount' => number_format($fine->amount)]);
            }

            return back()->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Renew loan
     */
    public function renew(Request $request, LoanTransaction $loan)
    {
        try {
            if (!$loan->canRenew()) {
                throw new \Exception(__('This loan cannot be renewed.'));
            }

            $loan->update([
                'due_date' => Carbon::parse($loan->due_date)->addDays($loan->policy->renewal_days),
                'renewal_count' => $loan->renewal_count + 1,
                'last_renewal_date' => Carbon::now()
            ]);

            return back()->with('success', __('Loan renewed successfully. New due date: :date', [
                'date' => $loan->due_date->format('d/m/Y')
            ]));

        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    // ==================== FINES ====================

    /**
     * Fines management page
     */
    public function fines()
    {
        $unpaidFines = Fine::with(['patron.user', 'loanTransaction.bookItem'])
            ->unpaid()
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.circulation.fines', compact('unpaidFines'));
    }

    /**
     * Pay fine
     */
    public function payFine(Request $request, Fine $fine)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0|max:' . $fine->balance,
            'payment_method' => 'required|in:cash,transfer,card'
        ]);

        $fine->paid_amount += $validated['amount'];
        
        if ($fine->balance <= 0) {
            $fine->status = 'paid';
            $fine->paid_date = Carbon::now();
        } else {
            $fine->status = 'partial';
        }

        $fine->collected_by = auth()->id();
        $fine->payment_method = $validated['payment_method'];
        $fine->save();

        return back()->with('success', __('Payment recorded successfully.'));
    }

    /**
     * Waive fine
     */
    public function waiveFine(Request $request, Fine $fine)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0|max:' . $fine->balance,
            'notes' => 'nullable|string|max:500'
        ]);

        $fine->waived_amount += $validated['amount'];
        
        if ($fine->balance <= 0) {
            $fine->status = 'waived';
        }

        $fine->notes = $validated['notes'];
        $fine->save();

        return back()->with('success', __('Fine waived successfully.'));
    }

    /**
     * Book distribution page - View all book items
     */
    public function distribution(Request $request)
    {
        $query = BookItem::with(['bibliographicRecord', 'branch', 'currentLoan.patron.user']);
        
        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Filter by branch
        if ($request->filled('branch_id')) {
            $query->where('branch_id', $request->branch_id);
        }
        
        // Search by barcode or title
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('barcode', 'like', "%{$search}%")
                  ->orWhereHas('bibliographicRecord', function($subQ) use ($search) {
                      $subQ->where('title', 'like', "%{$search}%");
                  });
            });
        }
        
        // Get statistics
        $stats = [
            'total' => BookItem::count(),
            'available' => BookItem::where('status', 'available')->count(),
            'on_loan' => BookItem::where('status', 'on_loan')->count(),
            'lost' => BookItem::where('status', 'lost')->count(),
            'damaged' => BookItem::where('status', 'damaged')->count(),
            'in_processing' => BookItem::where('status', 'in_processing')->count(),
        ];
        
        // Get branches for filter
        $branches = \App\Models\Branch::pluck('name', 'id');
        
        // Paginate results
        $bookItems = $query->orderBy('created_at', 'desc')->paginate(50);
        
        return view('admin.circulation.distribution', compact('bookItems', 'stats', 'branches'));
    }

    /**
     * Get book item history (AJAX)
     */
    public function getBookItemHistory(Request $request)
    {
        $validated = $request->validate([
            'barcode' => 'required|string'
        ]);

        try {
            // Find book item
            $bookItem = BookItem::where('barcode', $validated['barcode'])->firstOrFail();
            
            // Get all loan transactions for this book item with relationships
            $transactions = LoanTransaction::with(['patron.user', 'patron.patronGroup'])
                ->where('book_item_id', $bookItem->id)
                ->orderBy('created_at', 'desc')
                ->get();

            // Format transaction data
            $history = $transactions->map(function($transaction) {
                $statusInfo = $this->getTransactionStatusInfo($transaction);
                
                return [
                    'id' => $transaction->id,
                    'date' => $transaction->loan_date->format('d/m/Y H:i'),
                    'due_date' => $transaction->due_date->format('d/m/Y'),
                    'return_date' => $transaction->return_date?->format('d/m/Y H:i'),
                    'status' => $transaction->status,
                    'status_display' => $statusInfo['display'],
                    'status_color' => $statusInfo['color'],
                    'notes' => $transaction->notes,
                    'patron' => [
                        'name' => $transaction->patron->display_name ?? $transaction->patron->user->name,
                        'code' => $transaction->patron->patron_code,
                        'group' => $transaction->patron->patronGroup?->name ?? 'N/A'
                    ],
                    'loaned_by' => $transaction->loanedBy?->name ?? 'System',
                    'renewal_count' => $transaction->renewal_count ?? 0
                ];
            });

            // Get book info
            $bookInfo = [
                'barcode' => $bookItem->barcode,
                'title' => $bookItem->bibliographicRecord?->title ?? 'N/A',
                'author' => $bookItem->bibliographicRecord?->author ?? 'N/A',
                'current_status' => $bookItem->status,
                'location' => $bookItem->location ?? 'N/A',
                'branch' => $bookItem->branch?->name ?? 'N/A'
            ];

            return response()->json([
                'success' => true,
                'data' => [
                    'book_info' => $bookInfo,
                    'history' => $history,
                    'total_transactions' => $history->count()
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi lấy lịch sử: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get transaction status display info
     */
    private function getTransactionStatusInfo($transaction)
    {
        $statusMap = [
            'borrowed' => [
                'display' => __('Đã mượn'),
                'color' => 'blue'
            ],
            'returned' => [
                'display' => __('Đã trả'),
                'color' => 'green'
            ],
            'renewed' => [
                'display' => __('Đã gia hạn'),
                'color' => 'yellow'
            ],
            'recalled' => [
                'display' => __('Đã triệu hồi'),
                'color' => 'yellow'
            ],
            'lost' => [
                'display' => __('Đã khai báo mất'),
                'color' => 'red'
            ],
            'overdue' => [
                'display' => __('Quá hạn'),
                'color' => 'red'
            ]
        ];

        return $statusMap[$transaction->status] ?? [
            'display' => ucfirst($transaction->status),
            'color' => 'gray'
        ];
    }

    // ==================== SEARCH METHODS ====================

    /**
     * Search patron by code (AJAX)
     */
    public function searchPatron(Request $request)
    {
        $code = $request->get('code');
        $startTime = microtime(true);
        
        // Log search attempt
        \Log::info('Patron search initiated', [
            'code' => $code,
            'user_id' => auth()->id(),
            'ip' => $request->ip(),
            'timestamp' => now()->toISOString()
        ]);
        
        if (!$code || strlen($code) < 2) {
            \Log::warning('Invalid patron code search attempt', [
                'code' => $code,
                'length' => strlen($code ?? ''),
                'user_id' => auth()->id()
            ]);
            
            return response()->json(['success' => false, 'message' => __('Invalid patron code')]);
        }

        try {
            $patron = PatronDetail::with(['user', 'patronGroup.activePolicy'])
                ->where('patron_code', 'like', $code . '%')
                ->first();

            if (!$patron) {
                \Log::info('Patron not found', [
                    'search_code' => $code,
                    'user_id' => auth()->id(),
                    'execution_time' => round((microtime(true) - $startTime) * 1000, 2) . 'ms'
                ]);
                
                return response()->json(['success' => false, 'message' => __('Patron not found')]);
            }

            // Get current loans count
            $currentLoans = LoanTransaction::where('patron_detail_id', $patron->id)
                ->where('status', 'borrowed')
                ->count();

            // Calculate total outstanding fines
            $outstandingFine = Fine::whereHas('loanTransaction', function($query) use ($patron) {
                $query->where('patron_detail_id', $patron->id);
            })
            ->where('status', 'unpaid')
            ->selectRaw('SUM(amount - paid_amount - waived_amount) as outstanding')
            ->value('outstanding') ?? 0;

            // Check if patron can borrow
            $maxLoans = $patron->patronGroup?->activePolicy?->max_items ?? 5;
            $canBorrow = $patron->canBorrow() && $currentLoans < $maxLoans;

            // Get transaction statistics from loan_transactions
            $totalCheckouts = LoanTransaction::where('patron_detail_id', $patron->id)->count();
            $totalCheckins = LoanTransaction::where('patron_detail_id', $patron->id)
                ->whereNotNull('return_date')->count();
            $totalRenewals = LoanTransaction::where('patron_detail_id', $patron->id)
                ->sum('renewal_count');

            $result = [
                'success' => true,
                'data' => [
                    'id' => $patron->id,
                    'patron_code' => $patron->patron_code,
                    'display_name' => $patron->display_name,
                    'profile_image' => $patron->profile_image,
                    'user' => [
                        'name' => $patron->user?->name
                    ],
                    'patron_group' => [
                        'name' => $patron->patronGroup?->name
                    ],
                    'current_loans' => $currentLoans,
                    'max_loans' => $maxLoans,
                    'outstanding_fine' => $outstandingFine,
                    'can_borrow' => $canBorrow,
                    'status' => $patron->status,
                    'transaction_stats' => [
                        'total_checkouts' => $totalCheckouts,
                        'total_checkins' => $totalCheckins,
                        'total_renewals' => $totalRenewals,
                        'total_transactions' => $totalCheckouts + $totalCheckins + $totalRenewals
                    ]
                ]
            ];

            \Log::info('Patron search completed successfully', [
                'patron_id' => $patron->id,
                'patron_code' => $patron->patron_code,
                'can_borrow' => $canBorrow,
                'current_loans' => $currentLoans,
                'outstanding_fine' => $outstandingFine,
                'user_id' => auth()->id(),
                'execution_time' => round((microtime(true) - $startTime) * 1000, 2) . 'ms'
            ]);

            return response()->json($result);

        } catch (\Exception $e) {
            \Log::error('Patron search error', [
                'search_code' => $code,
                'error_message' => $e->getMessage(),
                'error_trace' => $e->getTraceAsString(),
                'user_id' => auth()->id(),
                'execution_time' => round((microtime(true) - $startTime) * 1000, 2) . 'ms'
            ]);
            
            return response()->json(['success' => false, 'message' => __('Search error: ') . $e->getMessage()]);
        }
    }

    /**
     * Search book by barcode (AJAX)
     */
    public function searchBook(Request $request)
    {
        $barcode = $request->get('barcode');
        $startTime = microtime(true);
        
        // Log search attempt
        \Log::info('Book search initiated', [
            'barcode' => $barcode,
            'user_id' => auth()->id(),
            'ip' => $request->ip(),
            'timestamp' => now()->toISOString()
        ]);
        
        if (!$barcode || strlen($barcode) < 2) {
            \Log::warning('Invalid barcode search attempt', [
                'barcode' => $barcode,
                'length' => strlen($barcode ?? ''),
                'user_id' => auth()->id()
            ]);
            
            return response()->json(['success' => false, 'message' => __('Invalid barcode')]);
        }

        try {
            $bookItem = BookItem::with(['bibliographicRecord', 'currentLoan.patron.user'])
                ->where('barcode', 'like', $barcode . '%')
                ->first();

            if (!$bookItem) {
                \Log::info('Book not found', [
                    'search_barcode' => $barcode,
                    'user_id' => auth()->id(),
                    'execution_time' => round((microtime(true) - $startTime) * 1000, 2) . 'ms'
                ]);
                
                return response()->json(['success' => false, 'message' => __('Book not found')]);
            }

            // Debug bibliographic record data
            \Log::info('Book item found', [
                'book_item_id' => $bookItem->id,
                'barcode' => $bookItem->barcode,
                'bibliographic_record_id' => $bookItem->bibliographic_record_id,
                'bibliographic_record_exists' => $bookItem->bibliographicRecord ? true : false,
                'bibliographic_record_title' => $bookItem->bibliographicRecord?->title,
                'status' => $bookItem->status
            ]);

            // Get current loan information
            $currentLoan = null;
            if ($bookItem->status === 'on_loan' && $bookItem->currentLoan) {
                $currentLoan = [
                    'patron_name' => $bookItem->currentLoan->patron->display_name ?? $bookItem->currentLoan->patron->user->name,
                    'patron_code' => $bookItem->currentLoan->patron->patron_code,
                    'due_date' => $bookItem->currentLoan->due_date->format('d/m/Y'),
                    'is_overdue' => $bookItem->currentLoan->due_date->isPast(),
                    'overdue_days' => $bookItem->currentLoan->due_date->isPast() ? $bookItem->currentLoan->due_date->diffInDays(now()) : 0
                ];
            }

            $result = [
                'success' => true,
                'data' => [
                    'id' => $bookItem->id,
                    'barcode' => $bookItem->barcode,
                    'title' => $bookItem->bibliographicRecord?->title ?? 'N/A',
                    'author' => $bookItem->bibliographicRecord?->author ?? 'N/A',
                    'call_number' => $bookItem->bibliographicRecord?->call_number ?? 'N/A',
                    'cover_image' => $bookItem->bibliographicRecord?->cover_image,
                    'status' => $bookItem->status,
                    'current_loan' => $currentLoan
                ]
            ];

            \Log::info('Book search completed successfully', [
                'book_item_id' => $bookItem->id,
                'barcode' => $bookItem->barcode,
                'title' => $bookItem->bibliographicRecord?->title,
                'status' => $bookItem->status,
                'has_current_loan' => !is_null($currentLoan),
                'user_id' => auth()->id(),
                'execution_time' => round((microtime(true) - $startTime) * 1000, 2) . 'ms'
            ]);

            return response()->json($result);

        } catch (\Exception $e) {
            \Log::error('Book search error', [
                'search_barcode' => $barcode,
                'error_message' => $e->getMessage(),
                'error_trace' => $e->getTraceAsString(),
                'user_id' => auth()->id(),
                'execution_time' => round((microtime(true) - $startTime) * 1000, 2) . 'ms'
            ]);
            
            return response()->json(['success' => false, 'message' => __('Search error: ') . $e->getMessage()]);
        }
    }

    /**
     * Process recall - Create new record only
     */
    public function recall(Request $request)
    {
        $validated = $request->validate([
            'barcode' => 'required|string',
            'reason' => 'nullable|string'
        ]);
        try {
            DB::beginTransaction();
            $bookItem = BookItem::where('barcode', $validated['barcode'])->firstOrFail();
            $activeLoan = LoanTransaction::where('book_item_id', $bookItem->id)
                ->where('status', 'borrowed')
                ->firstOrFail();
            
            // Check if book is overdue and set appropriate due date
            $now = Carbon::now();
            $originalDueDate = $activeLoan->due_date;
            
            if ($originalDueDate->greaterThan($now)) {
                // Book is NOT overdue → set due date to recall date
                $newDueDate = $now;
            } else {
                // Book IS overdue → keep original due date
                $newDueDate = $originalDueDate;
            }
            
            $recallTransaction = LoanTransaction::create([
                'patron_detail_id' => $activeLoan->patron_detail_id,
                'book_item_id' => $bookItem->id,
                'circulation_policy_id' => $activeLoan->circulation_policy_id,
                'loan_date' => $now,
                'due_date' => $newDueDate,
                'status' => 'recalled',
                'loaned_by' => auth()->id(),
                'loan_branch_id' => $activeLoan->loan_branch_id,
                'notes' => 'Triệu hồi: ' . ($validated['reason'] ?? 'Không có lý do')
            ]);
            
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Ghi nhận hành động triệu hồi thành công',
                'data' => [
                    'loan_id' => $recallTransaction->id,
                    'book_title' => $bookItem->bibliographicRecord->title ?? 'N/A',
                    'patron_name' => $activeLoan->patron->display_name ?? $activeLoan->patron->user->name ?? 'N/A',
                    'original_due_date' => $originalDueDate->format('d/m/Y'),
                    'new_due_date' => $newDueDate->format('d/m/Y'),
                    'is_overdue' => !$originalDueDate->greaterThan($now)
                ]
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi ghi nhận triệu hồi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Process declare lost - Create new record only
     */
    public function declareLost(Request $request)
    {
        $validated = $request->validate([
            'barcode' => 'required|string',
            'notes' => 'nullable|string'
        ]);

        try {
            DB::beginTransaction();

            // Find book item
            $bookItem = BookItem::where('barcode', $validated['barcode'])->firstOrFail();
            
            // Find active loan transaction
            $activeLoan = LoanTransaction::where('book_item_id', $bookItem->id)
                ->where('status', 'borrowed')
                ->firstOrFail();

            // Create NEW loan transaction record for declare lost action
            $lostTransaction = LoanTransaction::create([
                'patron_detail_id' => $activeLoan->patron_detail_id,
                'book_item_id' => $bookItem->id,
                'circulation_policy_id' => $activeLoan->circulation_policy_id,
                'loan_date' => Carbon::now(), // Current time as action date
                'due_date' => $activeLoan->due_date, // Keep original due date
                'status' => 'lost', // New status for this record
                'loaned_by' => auth()->id(),
                'loan_branch_id' => $activeLoan->loan_branch_id,
                'notes' => 'Khai báo mất: ' . ($validated['notes'] ?? 'Không có ghi chú')
            ]);

            // DO NOT update the original loan transaction
            // DO NOT update book item status

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Ghi nhận hành động khai báo mất thành công',
                'data' => [
                    'loan_id' => $lostTransaction->id,
                    'book_title' => $bookItem->bibliographicRecord->title ?? 'N/A',
                    'patron_name' => $activeLoan->patron->display_name ?? $activeLoan->patron->user->name ?? 'N/A'
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi ghi nhận khai báo mất: ' . $e->getMessage()
            ], 500);
        }
    }
}
