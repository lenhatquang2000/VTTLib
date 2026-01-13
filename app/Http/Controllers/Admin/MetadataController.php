<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Metadata;
use App\Models\MetadataValue;
use Illuminate\Http\Request;

class MetadataController extends Controller
{
    public function index()
    {
        $metadata = Metadata::with('values')->get();
        return view('admin.metadata.index', compact('metadata'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'metadata_code' => 'required|unique:metadata,metadata_code|max:50',
            'metadata_name' => 'required|max:255',
            'description' => 'nullable',
            'allow_multiple' => 'boolean'
        ]);

        Metadata::create($validated);

        return redirect()->back()->with('success', 'Đã thêm Metadata mới.');
    }

    public function storeValue(Request $request)
    {
        $validated = $request->validate([
            'metadata_id' => 'required|exists:metadata,id',
            'value_code' => 'required|max:50',
            'value_name' => 'required|max:255',
            'description' => 'nullable'
        ]);

        MetadataValue::create($validated);

        return redirect()->back()->with('success', 'Đã thêm giá trị mới.');
    }

    public function destroy(Metadata $metadata)
    {
        $metadata->delete();
        return redirect()->back()->with('success', 'Đã xóa Metadata.');
    }

    public function destroyValue(MetadataValue $value)
    {
        $value->delete();
        return redirect()->back()->with('success', 'Đã xóa giá trị.');
    }
}
