@extends('layouts.admin')

@section('content')
<div class="space-y-6">
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
                    <div id="dropZone" class="border-2 border-dashed border-gray-300 dark:border-slate-600 rounded-lg p-8 text-center hover:border-indigo-500 transition-all duration-200 bg-gray-50/50 dark:bg-slate-800/50">
                        <input type="file" name="excel_file" id="excel_file" accept=".xlsx,.xls,.csv" required
                            class="hidden">
                        <label for="excel_file" class="cursor-pointer block">
                            <div id="uploadPlaceholder" class="flex flex-col items-center">
                                <div class="w-16 h-16 bg-indigo-50 dark:bg-indigo-900/30 rounded-full flex items-center justify-center mb-4 transition-transform group-hover:scale-110">
                                    <svg class="w-8 h-8 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                    </svg>
                                </div>
                                <span class="text-base font-medium text-gray-700 dark:text-slate-200">{{ __('Click to upload or drag and drop') }}</span>
                                <span class="text-sm text-gray-500 dark:text-slate-500 mt-1">{{ __('XLSX, XLS, CSV (Max 10MB)') }}</span>
                            </div>

                            <div id="fileSelectedState" class="hidden flex flex-col items-center">
                                <div class="w-16 h-16 bg-emerald-50 dark:bg-emerald-900/30 rounded-full flex items-center justify-center mb-4 animate-bounce">
                                    <svg class="w-8 h-8 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <span id="selectedFileName" class="text-base font-bold text-emerald-600 dark:text-emerald-400"></span>
                                <span id="selectedFileSize" class="text-sm text-gray-500 dark:text-slate-500 mt-1"></span>
                                <button type="button" onclick="document.getElementById('resetBtn').click()" class="mt-4 text-xs text-red-500 hover:text-red-700 underline">{{ __('Remove file') }}</button>
                            </div>
                        </label>
                    </div>
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
                        class="flex-1 bg-indigo-600 hover:bg-indigo-700 disabled:bg-gray-300 text-white px-6 py-3 rounded-lg text-sm font-semibold transition disabled:cursor-not-allowed shadow-lg shadow-indigo-200 dark:shadow-none">
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
                <div id="previewContainer" class="grid grid-cols-1 gap-4">
                    <!-- Raw MARC records will be injected here -->
                </div>
            </div>

            <!-- Errors -->
            <div id="errorsSection" class="hidden mb-6">
                <h4 class="text-sm font-semibold text-red-600 dark:text-red-400 mb-3">{{ __('Validation Errors') }}</h4>
                <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4 max-h-60 overflow-y-auto">
                    <div id="errorsList" class="space-y-2 text-sm">
                    </div>
                </div>

                <!-- Suggested Action: Create Framework -->
                <div id="createFrameworkSection" class="hidden mt-4 p-4 bg-indigo-50 dark:bg-indigo-900/20 border border-indigo-100 dark:border-indigo-800 rounded-lg">
                    <div class="flex items-start">
                        <div class="flex-shrink-0 pt-0.5">
                            <svg class="h-5 w-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h5 class="text-sm font-bold text-indigo-900 dark:text-indigo-100">{{ __('Dữ liệu không khớp với khung đã chọn?') }}</h5>
                            <p class="text-xs text-indigo-700 dark:text-indigo-300 mt-1">
                                {{ __('Có vẻ như file của bạn có cấu trúc cột khác với Khung biên mục hiện tại. Bạn có muốn hệ thống tự động tạo một Khung biên mục mới dựa trên các tiêu đề cột trong file này không?') }}
                            </p>
                            <button type="button" id="createFrameworkBtn" class="mt-3 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-lg transition shadow-sm">
                                {{ __('Tạo Khung biên mục mới từ file này') }}
                            </button>
                        </div>
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
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        let validImportData = [];
        const form = document.getElementById('importForm');
        const fileInput = document.getElementById('excel_file');
        const frameworkSelect = document.getElementById('framework_id');
        const actionTypeSelect = document.getElementById('action_type');
        const uploadBtn = document.getElementById('uploadBtn');
        const downloadTemplateBtn = document.getElementById('downloadTemplate');
        const resetBtn = document.getElementById('resetBtn');

        const dropZone = document.getElementById('dropZone');
        const uploadPlaceholder = document.getElementById('uploadPlaceholder');
        const fileSelectedState = document.getElementById('fileSelectedState');
        const selectedFileName = document.getElementById('selectedFileName');
        const selectedFileSize = document.getElementById('selectedFileSize');

        const validationResults = document.getElementById('validationResults');
        const processingResults = document.getElementById('processingResults');

        // Enable/disable buttons based on form state
        function updateButtonStates() {
            const hasFile = fileInput.files.length > 0;
            const hasFramework = frameworkSelect.value !== '';
            uploadBtn.disabled = !(hasFile && hasFramework);
            downloadTemplateBtn.disabled = !hasFramework;

            if (hasFile) {
                const file = fileInput.files[0];
                selectedFileName.textContent = file.name;
                selectedFileSize.textContent = (file.size / 1024 / 1024).toFixed(2) + ' MB';

                uploadPlaceholder.classList.add('hidden');
                fileSelectedState.classList.remove('hidden');
                dropZone.classList.add('border-emerald-500', 'bg-emerald-50/20');
                dropZone.classList.remove('border-gray-300', 'bg-gray-50/50');
            } else {
                uploadPlaceholder.classList.remove('hidden');
                fileSelectedState.classList.add('hidden');
                dropZone.classList.remove('border-emerald-500', 'bg-emerald-50/20');
                dropZone.classList.add('border-gray-300', 'bg-gray-50/50');
            }
        }

        fileInput.addEventListener('change', updateButtonStates);
        frameworkSelect.addEventListener('change', updateButtonStates);

        downloadTemplateBtn.addEventListener('click', function() {
            const frameworkId = frameworkSelect.value;
            if (frameworkId) {
                window.location.href = `/topsecret/marc-import/template?framework_id=${frameworkId}`;
            }
        });

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
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                const contentType = response.headers.get("content-type");
                if (contentType && contentType.indexOf("application/json") !== -1) {
                    const result = await response.json();
                    if (result.success) {
                        showValidationResults(result.data);
                        Swal.fire({
                            icon: 'success',
                            title: "{{ __('File Uploaded') }}",
                            text: "{{ __('File has been uploaded and validated successfully.') }}",
                            timer: 2000,
                            showConfirmButton: false
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: "{{ __('Error') }}",
                            text: result.message
                        });
                    }
                } else {
                    // It's not JSON (possibly an error page or dd() output)
                    const html = await response.text();
                    const errorWindow = window.open('', '_blank');
                    errorWindow.document.write(html);
                    errorWindow.document.close();
                    Swal.fire({
                        icon: 'warning',
                        title: "{{ __('Debug Output') }}",
                        text: "{{ __('The server returned an HTML response. It has been opened in a new tab for debugging.') }}"
                    });
                }
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: "{{ __('Error') }}",
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

        function showValidationResults(data) {
            document.getElementById('totalRows').textContent = data.total_rows;
            document.getElementById('validRows').textContent = data.valid_rows;
            document.getElementById('invalidRows').textContent = data.invalid_rows;

            const previewContainer = document.getElementById('previewContainer');
            previewContainer.innerHTML = '';

            data.preview.forEach(record => {
                const card = document.createElement('div');
                card.className = "bg-gray-50 dark:bg-slate-800/50 border border-gray-200 dark:border-slate-700 rounded-lg p-4 font-mono text-sm overflow-x-auto";
                card.innerHTML = `
                    <div class="flex justify-between items-center mb-2 pb-2 border-b border-gray-200 dark:border-slate-700">
                        <span class="text-xs font-bold text-indigo-600 dark:text-indigo-400">#{{ __('Row') }} ${record.row_index}</span>
                        <span class="text-xs text-gray-500">${record.title}</span>
                    </div>
                    <pre class="text-gray-800 dark:text-slate-200 leading-relaxed whitespace-pre-wrap">${record.raw_marc}</pre>
                `;
                previewContainer.appendChild(card);
            });
            const errorsSection = document.getElementById('errorsSection');
            const errorsList = document.getElementById('errorsList');
            if (data.errors && data.errors.length > 0) {
                errorsSection.classList.remove('hidden');
                document.getElementById('createFrameworkSection').classList.remove('hidden');
                errorsList.innerHTML = '';
                data.errors.forEach(error => {
                    const errorDiv = document.createElement('div');
                    errorDiv.className = 'flex items-start space-x-2';
                    errorDiv.innerHTML = `<span class="text-red-600 dark:text-red-400 font-medium">{{ __('Row') }} ${error.row_index}:</span> <span class="text-gray-600 dark:text-slate-400">${error.errors.join(', ')}</span>`;
                    errorsList.appendChild(errorDiv);
                });
            } else {
                errorsSection.classList.add('hidden');
                document.getElementById('createFrameworkSection').classList.add('hidden');
            }
            validImportData = data.valid_data || [];
            document.getElementById('processBtn').disabled = data.valid_rows === 0;
            validationResults.classList.remove('hidden');
            validationResults.scrollIntoView({
                behavior: 'smooth'
            });
        }

        function showProcessingResults(data) {
            const container = document.getElementById('processingResultsContent');
            const total = data.length;
            const successCount = data.filter(r => r.success).length;
            const failCount = total - successCount;

            container.innerHTML = `
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <div class="p-4 bg-gray-50 dark:bg-slate-800 rounded-lg text-center">
                        <div class="text-2xl font-bold text-gray-800 dark:text-slate-100">${total}</div>
                        <div class="text-xs text-gray-500 dark:text-slate-400">{{ __('Total Processed') }}</div>
                    </div>
                    <div class="p-4 bg-green-50 dark:bg-green-900/20 rounded-lg text-center">
                        <div class="text-2xl font-bold text-green-600 dark:text-green-400">${successCount}</div>
                        <div class="text-xs text-green-600 dark:text-green-400">{{ __('Successful') }}</div>
                    </div>
                    <div class="p-4 bg-red-50 dark:bg-red-900/20 rounded-lg text-center">
                        <div class="text-2xl font-bold text-red-600 dark:text-red-400">${failCount}</div>
                        <div class="text-xs text-red-600 dark:text-red-400">{{ __('Failed') }}</div>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 dark:bg-slate-800">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-slate-400">{{ __('Row') }}</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-slate-400">{{ __('Title') }}</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-slate-400">{{ __('Status') }}</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-slate-400">{{ __('Details') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-slate-700">
                            ${data.map(result => `
                                <tr>
                                    <td class="px-4 py-2">${result.row_index}</td>
                                    <td class="px-4 py-2">${result.title || '-'}</td>
                                    <td class="px-4 py-2">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium ${result.success ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}">
                                            ${result.success ? '{{ __("Success") }}' : '{{ __("Failed") }}'}
                                        </span>
                                    </td>
                                    <td class="px-4 py-2 text-xs ${result.success ? 'text-gray-500' : 'text-red-500'}">
                                        ${result.success ? 'ID: ' + result.record_id : result.error}
                                    </td>
                                </tr>
                            `).join('')}
                        </tbody>
                    </table>
                </div>
                <div class="mt-6 flex justify-end">
                    <button type="button" onclick="location.reload()" class="px-6 py-2 bg-indigo-600 text-white rounded-lg text-sm font-semibold hover:bg-indigo-700 transition">
                        {{ __('Done') }}
                    </button>
                </div>
            `;

            validationResults.classList.add('hidden');
            processingResults.classList.remove('hidden');
            processingResults.scrollIntoView({
                behavior: 'smooth'
            });
        }

        document.getElementById('createFrameworkBtn').addEventListener('click', async function() {
            const {
                value: formValues
            } = await Swal.fire({
                title: '{{ __("Tạo Khung biên mục mới") }}',
                html: '<input id="swal-input1" class="swal2-input" placeholder="{{ __("Tên khung (VD: Tài liệu số)") }}">' +
                    '<input id="swal-input2" class="swal2-input" placeholder="{{ __("Mã khung (VD: DIGI)") }}">',
                focusConfirm: false,
                showCancelButton: true,
                confirmButtonText: '{{ __("Tạo ngay") }}',
                preConfirm: () => {
                    return [
                        document.getElementById('swal-input1').value,
                        document.getElementById('swal-input2').value
                    ]
                }
            });

            if (formValues && formValues[0] && formValues[1]) {
                const formData = new FormData(form);
                formData.append('framework_name', formValues[0]);
                formData.append('framework_code', formValues[1]);

                Swal.fire({
                    title: '{{ __("Đang khởi tạo...") }}',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                try {
                    const response = await fetch('/topsecret/marc-import/create-framework', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    });

                    const result = await response.json();
                    if (result.success) {
                        Swal.fire({
                            icon: 'success',
                            title: '{{ __("Thành công") }}',
                            text: result.message
                        }).then(() => {
                            // Reload or suggest selecting the new framework
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: '{{ __("Lỗi") }}',
                            text: result.message
                        });
                    }
                } catch (error) {
                    Swal.fire({
                        icon: 'error',
                        title: '{{ __("Lỗi kết nối") }}',
                        text: error.message
                    });
                }
            }
        });

        document.getElementById('processBtn').addEventListener('click', async function() {
            this.disabled = true;
            this.innerHTML = `...`;
            try {
                const response = await fetch('/topsecret/marc-import/process', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        framework_id: frameworkSelect.value,
                        action_type: actionTypeSelect.value,
                        validated_data: validImportData
                    })
                });
                const result = await response.json();
                if (result.success) {
                    showProcessingResults(result.data);
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: "{{ __('Error') }}",
                        text: result.message
                    });
                }
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: "{{ __('Error') }}",
                    text: error.message
                });
            } finally {
                this.disabled = false;
                this.innerHTML = `{{ __('Process Import') }}`;
            }
        });

        resetBtn.addEventListener('click', function() {
            form.reset();
            updateButtonStates();
            validationResults.classList.add('hidden');
            processingResults.classList.add('hidden');
        });

        document.getElementById('cancelBtn').addEventListener('click', function() {
            validationResults.classList.add('hidden');
        });
    });
</script>
@endpush