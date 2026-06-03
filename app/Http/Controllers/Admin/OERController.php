<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OpenEducationalResource;
use App\Models\OERContribution;
use Illuminate\Http\Request;

class OERController extends Controller
{
    public function index()
    {
        $resources = OpenEducationalResource::latest()->paginate(20);
        return view('admin.oer.index', compact('resources'));
    }

    public function contributions()
    {
        $contributions = OERContribution::latest()->paginate(20);
        return view('admin.oer.contributions', compact('contributions'));
    }

    public function approveContribution($id)
    {
        $contribution = OERContribution::findOrFail($id);
        $contribution->update(['status' => 'approved']);
        
        // Logic to convert contribution to resource can be added here
        
        return back()->with('success', __('Đã phê duyệt đóng góp.'));
    }

    public function rejectContribution($id)
    {
        $contribution = OERContribution::findOrFail($id);
        $contribution->update(['status' => 'rejected']);
        
        return back()->with('success', __('Đã từ chối đóng góp.'));
    }
}
