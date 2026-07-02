<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BookProposal;
use Illuminate\Http\Request;

class BookProposalController extends Controller
{
    /**
     * Display a listing of book proposals.
     */
    public function index(Request $request)
    {
        $query = BookProposal::with('user');

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by keyword search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('book_title', 'like', "%{$search}%")
                  ->orWhere('fullname', 'like', "%{$search}%")
                  ->orWhere('email_phone', 'like', "%{$search}%")
                  ->orWhere('author', 'like', "%{$search}%");
            });
        }

        $proposals = $query->latest()->paginate(15)->withQueryString();

        return view('admin.proposals.index', compact('proposals'));
    }

    /**
     * Update the status of the specified book proposal.
     */
    public function updateStatus(Request $request, $id)
    {
        $proposal = BookProposal::findOrFail($id);

        $request->validate([
            'status' => 'required|in:pending,approved,rejected,added',
        ]);

        $proposal->update([
            'status' => $request->status,
        ]);

        return back()->with('success', 'Cập nhật trạng thái đề xuất thành công.');
    }
}
