@extends('layouts.admin')

@section('content')
<div class="space-y-8">
    @if(session('success'))
        <div class="bg-green-900/20 border border-green-500 text-green-400 p-4 text-xs font-mono rounded">
            [OK] {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-900/20 border border-red-500 text-red-400 p-4 text-xs font-mono rounded">
            [ERROR] {{ session('error') }}
        </div>
    @endif

    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold">{{ __('Document_Types') }}</h1>
            <p class="text-sm text-gray-400 mt-1">{{ __('Manage_document_types_for_library_materials') }}</p>
        </div>
        <button onclick="openModal('addDocTypeModal')" class="btn-primary">
            {{ __('Add_Document_Type') }}
        </button>
    </div>

    <!-- Document Types Table -->
    <div class="card-admin rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-800 text-xs uppercase">
                    <tr>
                        <th class="p-3 text-left w-12">#</th>
                        <th class="p-3 text-left">{{ __('Name') }}</th>
                        <th class="p-3 text-left">{{ __('Code') }}</th>
                        <th class="p-3 text-left">{{ __('MARC_Type') }}</th>
                        <th class="p-3 text-center">{{ __('Loan_Days') }}</th>
                        <th class="p-3 text-center">{{ __('Loanable') }}</th>
                        <th class="p-3 text-center">{{ __('Status') }}</th>
                        <th class="p-3 text-left">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700" id="sortableTable">
                    @forelse($documentTypes as $type)
                    <tr class="hover:bg-gray-800/50" data-id="{{ $type->id }}">
                        <td class="p-3 text-gray-500 cursor-move">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/>
                            </svg>
                        </td>
                        <td class="p-3">
                            <div class="flex items-center gap-2">
                                @if($type->icon)
                                <span class="text-gray-400">
                                    <i data-lucide="{{ $type->icon }}" class="w-4 h-4"></i>
                                </span>
                                @endif
                                <span class="font-medium">{{ $type->name }}</span>
                            </div>
                            @if($type->description)
                            <p class="text-xs text-gray-500 mt-1">{{ Str::limit($type->description, 50) }}</p>
                            @endif
                        </td>
                        <td class="p-3 font-mono text-xs">{{ $type->code }}</td>
                        <td class="p-3 font-mono text-xs text-gray-400">{{ $type->marc_type ?? '-' }}</td>
                        <td class="p-3 text-center font-mono">{{ $type->default_loan_days }}</td>
                        <td class="p-3 text-center">
                            @if($type->is_loanable)
                                <span class="text-green-400">✓</span>
                            @else
                                <span class="text-red-400">✗</span>
                            @endif
                        </td>
                        <td class="p-3 text-center">
                            @if($type->is_active)
                                <span class="bg-green-900/50 text-green-400 px-2 py-1 rounded text-xs">{{ __('Active') }}</span>
                            @else
                                <span class="bg-gray-900/50 text-gray-400 px-2 py-1 rounded text-xs">{{ __('Inactive') }}</span>
                            @endif
                        </td>
                        <td class="p-3">
                            <button onclick="editDocType({{ json_encode($type) }})" class="text-blue-400 hover:text-blue-300 text-xs mr-2">
                                {{ __('Edit') }}
                            </button>
                            <form action="{{ route('admin.document-types.destroy', $type) }}" method="POST" class="inline"
                                onsubmit="return confirm('{{ __('Delete_this_document_type?') }}')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-400 hover:text-red-300 text-xs">{{ __('Delete') }}</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="p-8 text-center text-gray-500">{{ __('No_document_types_found') }}</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- MARC Type Reference -->
    <div class="card-admin rounded-lg p-4">
        <h3 class="text-sm font-bold text-gray-300 mb-3">{{ __('MARC21_Type_Reference') }} (Leader/06)</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-2 text-xs">
            <div class="bg-gray-800/50 p-2 rounded"><code class="text-blue-400">a</code> - {{ __('Language_material') }}</div>
            <div class="bg-gray-800/50 p-2 rounded"><code class="text-blue-400">c</code> - {{ __('Notated_music') }}</div>
            <div class="bg-gray-800/50 p-2 rounded"><code class="text-blue-400">e</code> - {{ __('Cartographic') }}</div>
            <div class="bg-gray-800/50 p-2 rounded"><code class="text-blue-400">g</code> - {{ __('Projected_medium') }}</div>
            <div class="bg-gray-800/50 p-2 rounded"><code class="text-blue-400">i</code> - {{ __('Sound_recording') }}</div>
            <div class="bg-gray-800/50 p-2 rounded"><code class="text-blue-400">j</code> - {{ __('Musical_sound') }}</div>
            <div class="bg-gray-800/50 p-2 rounded"><code class="text-blue-400">k</code> - {{ __('2D_graphic') }}</div>
            <div class="bg-gray-800/50 p-2 rounded"><code class="text-blue-400">m</code> - {{ __('Computer_file') }}</div>
            <div class="bg-gray-800/50 p-2 rounded"><code class="text-blue-400">o</code> - {{ __('Kit') }}</div>
            <div class="bg-gray-800/50 p-2 rounded"><code class="text-blue-400">p</code> - {{ __('Mixed_materials') }}</div>
            <div class="bg-gray-800/50 p-2 rounded"><code class="text-blue-400">r</code> - {{ __('3D_artifact') }}</div>
            <div class="bg-gray-800/50 p-2 rounded"><code class="text-blue-400">s</code> - {{ __('Serial') }}</div>
        </div>
    </div>
</div>

<!-- Add Document Type Modal -->
<div id="addDocTypeModal" class="modal hidden">
    <div class="modal-backdrop" onclick="closeModal('addDocTypeModal')"></div>
    <div class="modal-content max-w-lg">
        <h3 class="text-lg font-bold mb-4">{{ __('Add_Document_Type') }}</h3>
        <form action="{{ route('admin.document-types.store') }}" method="POST">
            @csrf
            <div class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-1">{{ __('Name') }} *</label>
                        <input type="text" name="name" required class="input-field w-full" placeholder="{{ __('e.g._Book') }}">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">{{ __('Code') }} *</label>
                        <input type="text" name="code" required class="input-field w-full" placeholder="{{ __('e.g._BOOK') }}" maxlength="20">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-1">{{ __('MARC_Type') }}</label>
                        <input type="text" name="marc_type" class="input-field w-full" placeholder="a, s, g..." maxlength="10">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">{{ __('Icon') }}</label>
                        <input type="text" name="icon" class="input-field w-full" placeholder="book, newspaper...">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">{{ __('Description') }}</label>
                    <textarea name="description" class="input-field w-full" rows="2"></textarea>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-1">{{ __('Default_Loan_Days') }} *</label>
                        <input type="number" name="default_loan_days" value="14" required min="0" max="365" class="input-field w-full">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">{{ __('Order') }}</label>
                        <input type="number" name="order" value="0" min="0" class="input-field w-full">
                    </div>
                </div>
                <div class="flex gap-6">
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="is_loanable" value="1" checked class="rounded">
                        <span class="text-sm">{{ __('Loanable') }}</span>
                    </label>
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="is_active" value="1" checked class="rounded">
                        <span class="text-sm">{{ __('Active') }}</span>
                    </label>
                </div>
            </div>
            <div class="flex justify-end gap-2 mt-6">
                <button type="button" onclick="closeModal('addDocTypeModal')" class="btn-secondary">{{ __('Cancel') }}</button>
                <button type="submit" class="btn-primary">{{ __('Create') }}</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Document Type Modal -->
<div id="editDocTypeModal" class="modal hidden">
    <div class="modal-backdrop" onclick="closeModal('editDocTypeModal')"></div>
    <div class="modal-content max-w-lg">
        <h3 class="text-lg font-bold mb-4">{{ __('Edit_Document_Type') }}</h3>
        <form id="editDocTypeForm" method="POST">
            @csrf @method('PUT')
            <div class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-1">{{ __('Name') }} *</label>
                        <input type="text" name="name" id="editName" required class="input-field w-full">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">{{ __('Code') }} *</label>
                        <input type="text" name="code" id="editCode" required class="input-field w-full" maxlength="20">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-1">{{ __('MARC_Type') }}</label>
                        <input type="text" name="marc_type" id="editMarcType" class="input-field w-full" maxlength="10">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">{{ __('Icon') }}</label>
                        <input type="text" name="icon" id="editIcon" class="input-field w-full">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">{{ __('Description') }}</label>
                    <textarea name="description" id="editDescription" class="input-field w-full" rows="2"></textarea>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-1">{{ __('Default_Loan_Days') }} *</label>
                        <input type="number" name="default_loan_days" id="editLoanDays" required min="0" max="365" class="input-field w-full">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">{{ __('Order') }}</label>
                        <input type="number" name="order" id="editOrder" min="0" class="input-field w-full">
                    </div>
                </div>
                <div class="flex gap-6">
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="is_loanable" id="editLoanable" value="1" class="rounded">
                        <span class="text-sm">{{ __('Loanable') }}</span>
                    </label>
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="is_active" id="editActive" value="1" class="rounded">
                        <span class="text-sm">{{ __('Active') }}</span>
                    </label>
                </div>
            </div>
            <div class="flex justify-end gap-2 mt-6">
                <button type="button" onclick="closeModal('editDocTypeModal')" class="btn-secondary">{{ __('Cancel') }}</button>
                <button type="submit" class="btn-primary">{{ __('Update') }}</button>
            </div>
        </form>
    </div>
</div>

<style>
    .modal { position: fixed; inset: 0; z-index: 50; display: flex; align-items: center; justify-content: center; }
    .modal.hidden { display: none; }
    .modal-backdrop { position: absolute; inset: 0; background: rgba(0,0,0,0.8); }
    .modal-content { position: relative; background: #1f2937; border: 1px solid #374151; border-radius: 0.5rem; padding: 1.5rem; max-width: 28rem; width: 100%; max-height: 90vh; overflow-y: auto; }
    .modal-content.max-w-lg { max-width: 32rem; }
    .input-field { background: #111827; border: 1px solid #374151; border-radius: 0.375rem; padding: 0.5rem 0.75rem; color: #fff; font-size: 0.875rem; }
    .input-field:focus { outline: none; border-color: #3b82f6; }
    .btn-primary { background: #3b82f6; color: #fff; padding: 0.5rem 1rem; border-radius: 0.375rem; font-size: 0.875rem; font-weight: 500; }
    .btn-primary:hover { background: #2563eb; }
    .btn-secondary { background: #374151; color: #fff; padding: 0.5rem 1rem; border-radius: 0.375rem; font-size: 0.875rem; font-weight: 500; }
    .btn-secondary:hover { background: #4b5563; }
    .card-admin { background: #1f2937; border: 1px solid #374151; }
</style>

<script>
    function openModal(id) {
        document.getElementById(id).classList.remove('hidden');
    }
    
    function closeModal(id) {
        document.getElementById(id).classList.add('hidden');
    }

    function editDocType(type) {
        document.getElementById('editDocTypeForm').action = '/topsecret/document-types/' + type.id;
        document.getElementById('editName').value = type.name;
        document.getElementById('editCode').value = type.code;
        document.getElementById('editMarcType').value = type.marc_type || '';
        document.getElementById('editIcon').value = type.icon || '';
        document.getElementById('editDescription').value = type.description || '';
        document.getElementById('editLoanDays').value = type.default_loan_days;
        document.getElementById('editOrder').value = type.order;
        document.getElementById('editLoanable').checked = type.is_loanable;
        document.getElementById('editActive').checked = type.is_active;
        openModal('editDocTypeModal');
    }
</script>
@endsection
