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

        return view('admin.circulation.loan-desk', compact('recentLoans', 'overdueLoans'));
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
}
