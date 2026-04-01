<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\BarcodeService;
use Illuminate\Http\Request;

class BarcodeController extends Controller
{
    protected $barcodeService;

    public function __construct(BarcodeService $barcodeService)
    {
        $this->barcodeService = $barcodeService;
    }

    /**
     * Display barcode as SVG
     */
    public function show($code)
    {
        try {
            $svg = $this->barcodeService->renderSvg($code);
            
            return response($svg)
                ->header('Content-Type', 'image/svg+xml')
                ->header('Cache-Control', 'public, max-age=31536000') // Cache for 1 year
                ->header('Expires', gmdate('D, d M Y H:i:s \G\M\T', time() + 31536000));
                
        } catch (\Exception $e) {
            // Return error SVG
            $errorSvg = '<svg width="200" height="40" xmlns="http://www.w3.org/2000/svg"><rect width="100%" height="100%" fill="#f8f9fa"/><text x="50%" y="50%" text-anchor="middle" dy=".3em" font-family="Arial" font-size="12" fill="#6c757d">Barcode Error</text></svg>';
            
            return response($errorSvg)
                ->header('Content-Type', 'image/svg+xml');
        }
    }
}
