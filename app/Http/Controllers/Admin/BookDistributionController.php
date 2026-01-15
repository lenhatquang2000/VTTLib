<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BibliographicRecord;
use App\Models\BookItem;
use Illuminate\Http\Request;

class BookDistributionController extends Controller
{
    public function index(BibliographicRecord $record)
    {
        $record->load('items');
        return view('admin.distributions.index', compact('record'));
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
            'barcode' => 'required|unique:book_items,barcode',
            'accession_number' => 'required|unique:book_items,accession_number',
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

        // Generate barcode image (SVG is safer as it doesn't require GD extension)
        try {
            $generator = new \Picqer\Barcode\BarcodeGeneratorSVG();
            $barcodeData = $generator->getBarcode($validated['barcode'], $generator::TYPE_CODE_128);
            
            $path = public_path('barcode/' . $validated['barcode'] . '.svg');
            file_put_contents($path, $barcodeData);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Barcode generation failed: ' . $e->getMessage());
        }

        $record->items()->create($validated);

        return back()->with('success', __('Book item distributed and barcode generated successfully.'));
    }
}
