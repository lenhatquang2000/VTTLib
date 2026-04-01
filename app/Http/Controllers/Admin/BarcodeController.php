<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\BarcodeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
            Log::info('Barcode request: ' . $code);
            
            // Check if file exists in public storage
            $publicPath = public_path('storage/items/barcodes/' . $code . '.svg');
            Log::info('Checking file: ' . $publicPath);
            
            if (file_exists($publicPath)) {
                Log::info('File exists, reading file');
                $svg = file_get_contents($publicPath);
                
                return response($svg)
                    ->header('Content-Type', 'image/svg+xml')
                    ->header('Cache-Control', 'public, max-age=31536000')
                    ->header('Expires', gmdate('D, d M Y H:i:s \G\M\T', time() + 31536000));
            }
            
            Log::info('File not found, generating on-the-fly');
            // Generate barcode on-the-fly if file doesn't exist
            $svg = $this->barcodeService->renderSvg($code);
            
            return response($svg)
                ->header('Content-Type', 'image/svg+xml')
                ->header('Cache-Control', 'public, max-age=31536000')
                ->header('Expires', gmdate('D, d M Y H:i:s \G\M\T', time() + 31536000));
                
        } catch (\Exception $e) {
            Log::error('Barcode error: ' . $e->getMessage());
            // Return error SVG
            $errorSvg = '<svg width="200" height="40" xmlns="http://www.w3.org/2000/svg"><rect width="100%" height="100%" fill="#f8f9fa"/><text x="50%" y="50%" text-anchor="middle" dy=".3em" font-family="Arial" font-size="12" fill="#6c757d">Barcode Error</text></svg>';
            
            return response($errorSvg)
                ->header('Content-Type', 'image/svg+xml');
        }
    }
}
