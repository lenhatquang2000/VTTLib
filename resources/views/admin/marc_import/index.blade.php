@extends('layouts.admin')

@section('content')
<div class="space-y-6 w-full">
    <!-- Header -->
    <div class="flex justify-between items-center bg-white dark:bg-slate-900 p-6 rounded-xl shadow-sm border border-gray-100 dark:border-slate-800">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 dark:text-slate-100">{{ __('MARC Records Import') }}</h2>
            <p class="text-sm text-gray-500 dark:text-slate-400 mt-1">{{ __('Import Excel files to create or update MARC records') }}</p>
        </div>
        <a href="{{ route('admin.marc.book') }}"
            class="flex items-center px-4 py-2.5 text-sm font-semibold bg-gray-100 dark:bg-slate-800 text-gray-600 dark:text-slate-400 hover:bg-gray-200 dark:hover:bg-slate-700 rounded-lg transition">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            {{ __('Back to Cataloging') }}
        </a>
    </div>

    <!-- Import Form -->
    <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-gray-100 dark:border-slate-800 overflow-hidden">
        <div class="p-6">
            <form id="importForm" enctype="multipart/form-data">
                @csrf
                
                <!-- Framework Selection -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-2">
                            {{ __('Cataloging Framework') }} <span class="text-red-500">*</span>
                        </label>
                        <select name="framework_id" id="framework_id" required
                            class="w-full px-4 py-2.5 border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 dark:text-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">{{ __('Select Framework') }}</option>
                            @foreach($frameworks as $framework)
                                <option value="{{ $framework->id }}">{{ $framework->name }} ({{ $framework->code }})</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-2">
                            {{ __('Action Type') }} <span class="text-red-500">*</span>
                        </label>
                        <select name="action_type" id="action_type" required
                            class="w-full px-4 py-2.5 border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 dark:text-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="create">{{ __('Create New Records') }}</option>
                            <option value="update">{{ __('Update Existing Records') }}</option>
                        </select>
                    </div>
                </div>

                <!-- File Upload -->
                <div class="mb-6">
                    <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-2">
                        {{ __('Excel File') }} <span class="text-red-500">*</span>
                    </label>
                    <div class="border-2 border-dashed border-gray-300 dark:border-slate-600 rounded-lg p-6 text-center hover:border-indigo-500 transition-colors">
                        <input type="file" name="excel_file" id="excel_file" accept=".xlsx,.xls,.csv" required
                            class="hidden">
                        <label for="excel_file" class="cursor-pointer">
                            <div class="flex flex-col items-center">
                                <svg class="w-12 h-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                </svg>
                                <span class="text-sm text-gray-600 dark:text-slate-400">{{ __('Click to upload or drag and drop') }}</span>
                                <span class="text-xs text-gray-500 dark:text-slate-500 mt-1">{{ __('XLSX, XLS, CSV (Max 10MB)') }}</span>
                            </div>
                        </label>
                    </div>
                    <div id="fileInfo" class="hidden mt-2 text-sm text-gray-600 dark:text-slate-400"></div>
                </div>

                <!-- Template Download -->
                <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4 mb-6">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <h4 class="text-sm font-semibold text-blue-800 dark:text-blue-200">{{ __('Download Template') }}</h4>
                            <p class="text-xs text-blue-600 dark:text-blue-400 mt-1">{{ __('Download the Excel template to ensure proper data format') }}</p>
                            <button type="button" id="downloadTemplate" disabled
                                class="mt-2 px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium rounded transition disabled:opacity-50 disabled:cursor-not-allowed">
                                {{ __('Download Template') }}
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex space-x-3">
                    <button type="submit" id="uploadBtn" disabled
                        class="flex-1 bg-indigo-600 hover:bg-indigo-700 disabled:bg-gray-300 text-white px-6 py-3 rounded-lg text-sm font-semibold transition disabled:cursor-not-allowed">
                        <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                        </svg>
                        {{ __('Upload & Validate') }}
                    </button>
                    <button type="button" id="resetBtn"
                        class="px-6 py-3 bg-gray-100 dark:bg-slate-800 text-gray-600 dark:text-slate-400 hover:bg-gray-200 dark:hover:bg-slate-700 rounded-lg text-sm font-semibold transition">
                        {{ __('Reset') }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Validation Results -->
    <div id="validationResults" class="hidden bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-gray-100 dark:border-slate-800 overflow-hidden">
        <div class="p-6">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-slate-100 mb-4">{{ __('Validation Results') }}</h3>
            
            <!-- Summary -->
            <div class="grid grid-cols-3 gap-4 mb-6">
                <div class="bg-gray-50 dark:bg-slate-800 rounded-lg p-4 text-center">
                    <div class="text-2xl font-bold text-gray-800 dark:text-slate-100" id="totalRows">0</div>
                    <div class="text-xs text-gray-500 dark:text-slate-400">{{ __('Total Rows') }}</div>
                </div>
                <div class="bg-green-50 dark:bg-green-900/20 rounded-lg p-4 text-center">
                    <div class="text-2xl font-bold text-green-600 dark:text-green-400" id="validRows">0</div>
                    <div class="text-xs text-green-600 dark:text-green-400">{{ __('Valid Rows') }}</div>
                </div>
                <div class="bg-red-50 dark:bg-red-900/20 rounded-lg p-4 text-center">
                    <div class="text-2xl font-bold text-red-600 dark:text-red-400" id="invalidRows">0</div>
                    <div class="text-xs text-red-600 dark:text-red-400">{{ __('Invalid Rows') }}</div>
                </div>
            </div>

            <!-- Preview -->
            <div class="mb-6">
                <h4 class="text-sm font-semibold text-gray-700 dark:text-slate-300 mb-3">{{ __('Preview (First 5 valid records)') }}</h4>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 dark:bg-slate-800">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-slate-400">{{ __('Row') }}</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-slate-400">{{ __('Title') }}</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-slate-400">{{ __('Author') }}</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-slate-400">{{ __('ISBN') }}</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-slate-400">{{ __('Status') }}</th>
                            </tr>
                        </thead>
                        <tbody id="previewTable" class="divide-y divide-gray-200 dark:divide-slate-700">
                            <!-- Preview rows will be inserted here -->
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Errors -->
            <div id="errorsSection" class="hidden mb-6">
                <h4 class="text-sm font-semibold text-red-600 dark:text-red-400 mb-3">{{ __('Validation Errors') }}</h4>
                <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4 max-h-60 overflow-y-auto">
                    <div id="errorsList" class="space-y-2 text-sm">
                        <!-- Errors will be inserted here -->
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex space-x-3">
                <button type="button" id="processBtn" disabled
                    class="flex-1 bg-green-600 hover:bg-green-700 disabled:bg-gray-300 text-white px-6 py-3 rounded-lg text-sm font-semibold transition disabled:cursor-not-allowed">
                    <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    {{ __('Process Import') }}
                </button>
                <button type="button" id="cancelBtn"
                    class="px-6 py-3 bg-gray-100 dark:bg-slate-800 text-gray-600 dark:text-slate-400 hover:bg-gray-200 dark:hover:bg-slate-700 rounded-lg text-sm font-semibold transition">
                    {{ __('Cancel') }}
                </button>
            </div>
        </div>
    </div>

    <!-- Processing Results -->
    <div id="processingResults" class="hidden bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-gray-100 dark:border-slate-800 overflow-hidden">
        <div class="p-6">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-slate-100 mb-4">{{ __('Import Results') }}</h3>
            <div id="processingResultsContent">
                <!-- Results will be inserted here -->
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('importForm');
    const fileInput = document.getElementById('excel_file');
    const frameworkSelect = document.getElementById('framework_id');
    const actionTypeSelect = document.getElementById('action_type');
    const uploadBtn = document.getElementById('uploadBtn');
    const downloadTemplateBtn = document.getElementById('downloadTemplate');
    const resetBtn = document.getElementById('resetBtn');
    const validationResults = document.getElementById('validationResults');
    const processingResults = document.getElementById('processingResults');

    // Enable/disable buttons based on form state
    function updateButtonStates() {
        const hasFile = fileInput.files.length > 0;
        const hasFramework = frameworkSelect.value !== '';
        const isValid = hasFile && hasFramework;
        
        uploadBtn.disabled = !isValid;
        downloadTemplateBtn.disabled = !hasFramework;
    }

    // File selection
    fileInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const fileInfo = document.getElementById('fileInfo');
            fileInfo.textContent = `${file.name} (${(file.size / 1024 / 1024).toFixed(2)} MB)`;
            fileInfo.classList.remove('hidden');
        }
        updateButtonStates();
    });

    // Framework selection
    frameworkSelect.addEventListener('change', updateButtonStates);

    // Download template
    downloadTemplateBtn.addEventListener('click', function() {
        const frameworkId = frameworkSelect.value;
        if (frameworkId) {
            window.location.href = `/topsecret/marc-import/template?framework_id=${frameworkId}`;
        }
    });

    // Form submission
    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const formData = new FormData(form);
        
        uploadBtn.disabled = true;
        uploadBtn.innerHTML = `
            <svg class="animate-spin h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            {{ __('Validating...') }}
        `;
        
        try {
            const response = await fetch('/topsecret/marc-import/upload', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });
            
            const result = await response.json();
            
            if (result.success) {
                showValidationResults(result.data);
            } else {
                Swal.fire({
                    icon: 'error',
                    title: '{{ __('Error') }}',
                    text: result.message
                });
            }
        } catch (error) {
            Swal.fire({
                icon: 'error',
                title: '{{ __('Error') }}',
                text: error.message
            });
        } finally {
            uploadBtn.disabled = false;
            uploadBtn.innerHTML = `
                <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                </svg>
                {{ __('Upload & Validate') }}
            `;
        }
    });

    // Show validation results
    function showValidationResults(data) {
        // Update summary
        document.getElementById('totalRows').textContent = data.total_rows;
        document.getElementById('validRows').textContent = data.valid_rows;
        document.getElementById('invalidRows').textContent = data.invalid_rows;
        
        // Update preview table
        const previewTable = document.getElementById('previewTable');
        previewTable.innerHTML = '';
        
        data.preview.forEach(record => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td class="px-4 py-2 text-sm">${record.row_index}</td>
                <td class="px-4 py-2 text-sm">${record.title}</td>
                <td class="px-4 py-2 text-sm">${record.author}</td>
                <td class="px-4 py-2 text-sm">${record.isbn}</td>
                <td class="px-4 py-2">
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-400">
                        {{ __('Valid') }}
                    </span>
                </td>
            `;
            previewTable.appendChild(row);
        });
        
        // Show errors if any
        const errorsSection = document.getElementById('errorsSection');
        const errorsList = document.getElementById('errorsList');
        
        if (data.errors && data.errors.length > 0) {
            errorsSection.classList.remove('hidden');
            errorsList.innerHTML = '';
            
            data.errors.forEach(error => {
                const errorDiv = document.createElement('div');
                errorDiv.className = 'flex items-start space-x-2';
                errorDiv.innerHTML = `
                    <span class="text-red-600 dark:text-red-400 font-medium">{{ __('Row') }} ${error.row_index}:</span>
                    <span class="text-gray-600 dark:text-slate-400">${error.errors.join(', ')}</span>
                `;
                errorsList.appendChild(errorDiv);
            });
        } else {
            errorsSection.classList.add('hidden');
        }
        
        // Enable process button if there are valid rows
        document.getElementById('processBtn').disabled = data.valid_rows === 0;
        
        // Show results
        validationResults.classList.remove('hidden');
        validationResults.scrollIntoView({ behavior: 'smooth' });
    }

    // Process import
    document.getElementById('processBtn').addEventListener('click', async function() {
        this.disabled = true;
        this.innerHTML = `
            <svg class="animate-spin h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            {{ __('Processing...') }}
        `;
        
        try {
            const response = await fetch('/topsecret/marc-import/process', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    framework_id: frameworkSelect.value,
                    action_type: actionTypeSelect.value,
                    validated_data: [] // This should contain the validated data from previous step
                })
            });
            
            const result = await response.json();
            
            if (result.success) {
                showProcessingResults(result.data);
            } else {
                Swal.fire({
                    icon: 'error',
                    title: '{{ __('Error') }}',
                    text: result.message
                });
            }
        } catch (error) {
            Swal.fire({
                icon: 'error',
                title: '{{ __('Error') }}',
                text: error.message
            });
        } finally {
            this.disabled = false;
            this.innerHTML = `
                <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                {{ __('Process Import') }}
            `;
        }
    });

    // Reset form
    resetBtn.addEventListener('click', function() {
        form.reset();
        document.getElementById('fileInfo').classList.add('hidden');
        validationResults.classList.add('hidden');
        processingResults.classList.add('hidden');
        updateButtonStates();
    });

    // Cancel
    document.getElementById('cancelBtn').addEventListener('click', function() {
        validationResults.classList.add('hidden');
    });
});
</script>
@endpush
