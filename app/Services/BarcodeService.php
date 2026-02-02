<?php

namespace App\Services;

use App\Models\BarcodeConfig;
use Picqer\Barcode\BarcodeGeneratorSVG;

class BarcodeService
{
    protected $generator;

    public function __construct()
    {
        $this->generator = new BarcodeGeneratorSVG();
    }

    /**
     * Get the next available code for a target type (item or patron)
     */
    public function getNextCode(string $targetType): ?string
    {
        $config = BarcodeConfig::where('target_type', $targetType)
            ->where('is_active', true)
            ->first();

        if (!$config) {
            return null;
        }

        return $config->generateNext();
    }

    /**
     * Increment the counter for an active barcode rule
     */
    public function incrementCounter(string $targetType, string $code): void
    {
        $config = BarcodeConfig::where('target_type', $targetType)
            ->where('is_active', true)
            ->first();

        if ($config) {
            // Check if the code matches the expected pattern
            // This is simple: just increment current_number
            $config->increment('current_number');
        }
    }

    /**
     * Render a barcode code as SVG
     */
    public function renderSvg(string $code, string $type = 'TYPE_CODE_128'): string
    {
        try {
            $barcodeType = constant("\Picqer\Barcode\BarcodeGenerator::" . $type);
            return $this->generator->getBarcode($code, $barcodeType, 2, 40);
        } catch (\Exception $e) {
            return '<!-- Error generating barcode -->';
        }
    }

    /**
     * Save barcode as SVG file to storage
     */
    public function saveAsFile(string $code, string $path, string $type = 'TYPE_CODE_128'): bool
    {
        try {
            $svgContent = $this->renderSvg($code, $type);
            
            // Ensure directory exists
            $fullPath = storage_path('app/public/' . $path);
            $directory = dirname($fullPath);
            if (!file_exists($directory)) {
                mkdir($directory, 0755, true);
            }

            file_put_contents($fullPath, $svgContent);
            return true;
        } catch (\Exception $e) {
            \Log::error("Failed to save barcode file: " . $e->getMessage());
            return false;
        }
    }
}
