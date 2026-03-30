<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PrintQueue;
use Illuminate\Http\Request;

class PrintQueueController extends Controller
{
    /**
     * Display print queue
     */
    public function index(Request $request)
    {
        $query = PrintQueue::with(['patron', 'addedBy'])
            ->orderBy('priority', 'desc')
            ->orderBy('created_at', 'asc');

        // Filter by status
        if ($request->status) {
            $query->where('status', $request->status);
        }

        $printQueue = $query->paginate(20);

        return view('admin.patrons.print-queue.index', compact('printQueue'));
    }

    /**
     * Mark item as printed
     */
    public function markPrinted($id)
    {
        $queueItem = PrintQueue::findOrFail($id);
        
        if ($queueItem->status !== PrintQueue::STATUS_PENDING) {
            return back()->with('error', 'Item is not in pending status.');
        }

        $queueItem->markAsPrinted(auth()->id());

        return back()->with('success', 'Đã đánh dấu là đã in thành công.');
    }

    /**
     * Remove item from print queue
     */
    public function destroy($id)
    {
        $queueItem = PrintQueue::findOrFail($id);
        
        $queueItem->cancel();

        return back()->with('success', 'Đã xóa khỏi danh sách chờ in thành công.');
    }
}
