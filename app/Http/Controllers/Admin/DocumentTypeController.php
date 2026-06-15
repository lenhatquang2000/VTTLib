<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DocumentType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class DocumentTypeController extends Controller
{
    /**
     * Display a listing of document types.
     */
    public function index()
    {
        $documentTypes = DocumentType::ordered()->get();
        $bibliographicLevels = \App\Models\BibliographicLevel::active()->ordered()->get();
        
        return view('admin.document-types.index', compact('documentTypes', 'bibliographicLevels'));
    }

    /**
     * Store a newly created document type.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:20|unique:document_types,code',
            'marc_type' => 'nullable|string|max:10',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:50',
            'default_loan_days' => 'required|integer|min:0|max:365',
            'is_loanable' => 'boolean',
            'is_active' => 'boolean',
            'order' => 'integer|min:0',
        ]);

        $validated['is_loanable'] = $request->has('is_loanable');
        $validated['is_active'] = $request->has('is_active');

        DocumentType::create($validated);

        return redirect()->route('admin.document-types.index')
            ->with('success', __('Kiểu tài liệu đã được tạo thành công.'));
    }

    /**
     * Update the specified document type.
     */
    public function update(Request $request, DocumentType $documentType)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => ['required', 'string', 'max:20', Rule::unique('document_types', 'code')->ignore($documentType->id)],
            'marc_type' => 'nullable|string|max:10',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:50',
            'default_loan_days' => 'required|integer|min:0|max:365',
            'is_loanable' => 'boolean',
            'is_active' => 'boolean',
            'order' => 'integer|min:0',
        ]);

        $validated['is_loanable'] = $request->has('is_loanable');
        $validated['is_active'] = $request->has('is_active');

        $documentType->update($validated);

        return redirect()->route('admin.document-types.index')
            ->with('success', __('Kiểu tài liệu đã được cập nhật thành công.'));
    }

    /**
     * Remove the specified document type.
     */
    public function destroy(DocumentType $documentType)
    {
        // Check if document type has associated book items
        if ($documentType->bookItems()->count() > 0) {
            return redirect()->route('admin.document-types.index')
                ->with('error', __('Không thể xóa kiểu tài liệu đang có các bản sách liên kết.'));
        }

        $documentType->delete();

        return redirect()->route('admin.document-types.index')
            ->with('success', __('Kiểu tài liệu đã được xóa thành công.'));
    }

    /**
     * Update order of document types (AJAX)
     */
    public function updateOrder(Request $request)
    {
        $request->validate([
            'orders' => 'required|array',
            'orders.*.id' => 'required|exists:document_types,id',
            'orders.*.order' => 'required|integer|min:0',
        ]);

        DB::transaction(function () use ($request) {
            foreach ($request->orders as $item) {
                DocumentType::where('id', $item['id'])->update(['order' => $item['order']]);
            }
        });

        return response()->json(['success' => true]);
    }
}
