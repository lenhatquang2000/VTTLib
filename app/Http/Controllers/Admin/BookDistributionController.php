<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BibliographicRecord;
use App\Models\BookItem;
use Illuminate\Http\Request;

class BookDistributionController extends Controller
{
    protected $barcodeService;

    public function __construct(\App\Services\BarcodeService $barcodeService)
    {
        $this->barcodeService = $barcodeService;
    }

    public function index(BibliographicRecord $record)
    {
        $record->load('items.branch', 'items.storageLocation');
        $nextBarcode = $this->barcodeService->previewNextBarcode('item');
        $branches = \App\Models\Branch::with('storageLocations')->where('is_active', true)->get();
        return view('admin.distributions.index', compact('record', 'nextBarcode', 'branches'));
    }

    public function checkBarcode(Request $request)
    {
        $barcode = $request->query('barcode');
        $exists = BookItem::where('barcode', $barcode)->exists();
        
        return response()->json([
            'exists' => $exists,
            'message' => $exists ? __('Barcode already exists') : __('Barcode is available')
        ]);
    }

    public function store(Request $request, BibliographicRecord $record)
    {
        $validated = $request->validate([
            'barcode' => 'nullable|string|unique:book_items,barcode',
            'accession_number' => 'required|unique:book_items,accession_number',
            'branch_id' => 'required|exists:branches,id',
            'storage_location_id' => 'required|exists:storage_locations,id',
            'storage_type' => 'nullable|string',
            'quantity' => 'required|integer|min:1',
            'location' => 'nullable|string',
            'temporary_location' => 'nullable|string',
            'status' => 'required|string',
            'order_code' => 'nullable|string',
            'waits_for_print' => 'boolean',
            'notes' => 'nullable|string',
            'volume_issue' => 'nullable|string',
            'day' => 'nullable|integer',
            'month_season' => 'nullable|string',
            'year' => 'nullable|integer',
            'shelf' => 'nullable|string',
            'shelf_position' => 'nullable|string',
        ]);

        // Auto-generate barcode if not provided
        if (empty($validated['barcode'])) {
            try {
                $validated['barcode'] = $this->barcodeService->getNextBarcode('item');
            } catch (\Exception $e) {
                return back()->with('error', $e->getMessage());
            }
        }

        // Generate barcode image (SVG is safer as it doesn't require GD extension)
        try {
            $generator = new \Picqer\Barcode\BarcodeGeneratorSVG();
            $barcodeData = $generator->getBarcode($validated['barcode'], $generator::TYPE_CODE_128);
            
            $path = public_path('barcode/' . $validated['barcode'] . '.svg');
            
            // Ensure directory exists
            if (!file_exists(public_path('barcode'))) {
                mkdir(public_path('barcode'), 0755, true);
            }
            
            file_put_contents($path, $barcodeData);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Barcode generation failed: ' . $e->getMessage());
        }

        $record->items()->create($validated);

        return back()->with('success', __('Book item distributed and barcode generated successfully.'));
    }
}
