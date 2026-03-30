<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PatronDetail;
use App\Models\PatronTransaction;
use Illuminate\Http\Request;

class PatronTransactionController extends Controller
{
    /**
     * Display patron transactions
     */
    public function index($patronId)
    {
        $patron = PatronDetail::findOrFail($patronId);
        
        $transactions = $patron->transactions()
            ->with('createdBy')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.patrons.transactions.index', compact('patron', 'transactions'));
    }

    /**
     * Store new transaction
     */
    public function store(Request $request, $patronId)
    {
        $request->validate([
            'type' => 'required|in:deposit,withdraw,fee,fine,penalty',
            'amount' => 'required|numeric|min:0.01',
            'description' => 'required|string|max:255',
            'notes' => 'nullable|string|max:500',
            'payment_method' => 'required_if:type,deposit|in:cash,transfer,card'
        ]);

        $patron = PatronDetail::findOrFail($patronId);

        // Check balance for debit transactions
        if (in_array($request->type, ['withdraw', 'fee', 'fine', 'penalty'])) {
            if ($patron->balance < $request->amount) {
                return back()->with('error', 'Độc giả không đủ số dư để thực hiện giao dịch này.');
            }
        }

        // Create transaction
        $transaction = $patron->addTransaction(
            $request->type,
            $request->amount,
            $request->description,
            $request->notes,
            $request->payment_method,
            auth()->id()
        );

        return back()->with('success', 'Giao dịch đã được thực hiện thành công.');
    }
}
