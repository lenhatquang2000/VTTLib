<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SystemSetting;
use App\Models\BarcodeConfig;
use App\Models\Branch;
use App\Models\StorageLocation;
use Illuminate\Http\Request;

class SystemSettingsController extends Controller
{
    public function index()
    {
        $settings = SystemSetting::all()->groupBy('group');
        $barcodeConfigs = BarcodeConfig::all();
        $branches = Branch::with('storageLocations')->get();
        
        return view('admin.settings.index', compact('settings', 'barcodeConfigs', 'branches'));
    }

    public function updateLibraryInfo(Request $request)
    {
        $data = $request->validate([
            'library_name_vi' => 'required|string|max:255',
            'library_name_en' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:500',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
        ]);

        foreach ($data as $key => $value) {
            SystemSetting::set($key, $value, 'library');
        }

        return back()->with('success', __('Library information updated successfully.'));
    }

    public function storeBarcodeConfig(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'prefix' => 'nullable|string|max:20',
            'length' => 'required|integer|min:1|max:20',
            'start_number' => 'required|integer|min:0',
            'target_type' => 'required|string|in:item,patron',
            'is_active' => 'boolean'
        ]);

        if ($validated['is_active'] ?? false) {
            // Deactivate others of same type
            BarcodeConfig::where('target_type', $validated['target_type'])->update(['is_active' => false]);
        }

        BarcodeConfig::create($validated);

        return back()->with('success', __('Barcode configuration created successfully.'));
    }

    public function updateBarcodeConfig(Request $request, BarcodeConfig $config)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'prefix' => 'nullable|string|max:20',
            'length' => 'required|integer|min:1|max:20',
            'is_active' => 'boolean'
        ]);

        if ($validated['is_active'] ?? false) {
            BarcodeConfig::where('target_type', $config->target_type)
                ->where('id', '!=', $config->id)
                ->update(['is_active' => false]);
        }

        $config->update($validated);

        return back()->with('success', __('Barcode configuration updated successfully.'));
    }

    public function deleteBarcodeConfig(BarcodeConfig $config)
    {
        if ($config->current_number > 0) {
            return back()->with('error', __('Cannot delete configuration that has already generated barcodes.'));
        }

        $config->delete();
        return back()->with('success', __('Barcode configuration deleted successfully.'));
    }

    // Branch Management
    public function storeBranch(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:20|unique:branches',
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20'
        ]);

        Branch::create($validated);
        return back()->with('success', __('Branch created successfully.'));
    }

    public function updateBranch(Request $request, Branch $branch)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:20|unique:branches,code,' . $branch->id,
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'is_active' => 'boolean'
        ]);

        $branch->update($validated);
        return back()->with('success', __('Branch updated successfully.'));
    }

    public function deleteBranch(Branch $branch)
    {
        if ($branch->bookItems()->exists()) {
            return back()->with('error', __('Cannot delete branch that has associated book items.'));
        }
        $branch->delete();
        return back()->with('success', __('Branch deleted successfully.'));
    }

    // Storage Location (Stackroom) Management
    public function storeLocation(Request $request)
    {
        $validated = $request->validate([
            'branch_id' => 'required|exists:branches,id',
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:20|unique:storage_locations',
            'description' => 'nullable|string|max:500'
        ]);

        StorageLocation::create($validated);
        return back()->with('success', __('Storage location created successfully.'));
    }

    public function updateLocation(Request $request, StorageLocation $location)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:20|unique:storage_locations,code,' . $location->id,
            'description' => 'nullable|string|max:500',
            'is_active' => 'boolean'
        ]);

        $location->update($validated);
        return back()->with('success', __('Storage location updated successfully.'));
    }

    public function deleteLocation(StorageLocation $location)
    {
        if ($location->bookItems()->exists()) {
            return back()->with('error', __('Cannot delete location that has associated book items.'));
        }
        $location->delete();
        return back()->with('success', __('Storage location deleted successfully.'));
    }
}
