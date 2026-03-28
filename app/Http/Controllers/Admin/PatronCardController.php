<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\PatronDetail;
use App\Services\BarcodeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class PatronCardController extends Controller
{
    protected $barcodeService;

    public function __construct(BarcodeService $barcodeService)
    {
        $this->barcodeService = $barcodeService;
    }

    /**
     * Show print card interface
     */
    public function index(Request $request)
    {
        $selectedPatrons = [];
        
        if ($request->has('patron_ids')) {
            $selectedPatrons = PatronDetail::whereIn('user_id', $request->patron_ids)
                ->with(['user', 'patronGroup', 'addresses'])
                ->get();
        }

        return view('admin.patrons.cards.index', compact('selectedPatrons'));
    }

    /**
     * Generate PDF for patron cards
     */
    public function generateCards(Request $request)
    {
        // Debug: Log received data
        \Log::info('PatronCardController::generateCards - Received data:', [
            'patron_ids' => $request->patron_ids,
            'layout' => $request->layout,
            'all_request_data' => $request->all()
        ]);

        $request->validate([
            'patron_ids' => 'required|array',
            'patron_ids.*' => 'exists:users,id',
            'layout' => 'required|in:single,batch'
        ]);

        // Debug: Log after validation
        \Log::info('PatronCardController::generateCards - After validation, patron_ids:', $request->patron_ids);

        $patrons = PatronDetail::whereIn('user_id', $request->patron_ids)
            ->with(['user', 'patronGroup', 'addresses'])
            ->get();

        // Debug: Log query results
        \Log::info('PatronCardController::generateCards - Found patrons count:', ['count' => $patrons->count()]);
        \Log::info('PatronCardController::generateCards - PatronDetail query result:', $patrons->pluck('user_id')->toArray());

        if ($patrons->isEmpty()) {
            // Check if users exist but don't have PatronDetail
            $usersWithoutPatronDetail = \App\Models\User::whereIn('id', $request->patron_ids)
                ->whereDoesntHave('patronDetail')
                ->pluck('id')
                ->toArray();
            
            \Log::warning('PatronCardController::generateCards - Users without PatronDetail:', $usersWithoutPatronDetail);
            
            return back()->with('error', 'Không có độc giả nào được chọn. Các user ID: ' . implode(', ', $request->patron_ids) . ' không có PatronDetail.');
        }

        // Generate barcode for each patron
        foreach ($patrons as $patron) {
            $patron->barcode_svg = $this->barcodeService->renderSvg($patron->patron_code);
        }

        $pdf = PDF::loadView('admin.patrons.cards.pdf', [
            'patrons' => $patrons,
            'layout' => $request->layout
        ]);

        $filename = 'the-doc-gia-' . now()->format('Y-m-d-H-i-s') . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Preview single card
     */
    public function previewCard($userId)
    {
        $patron = PatronDetail::where('user_id', $userId)
            ->with(['user', 'patronGroup', 'addresses'])
            ->firstOrFail();
            
        $patron->barcode_svg = $this->barcodeService->renderSvg($patron->patron_code);

        return view('admin.patrons.cards.preview', compact('patron'));
    }
}
