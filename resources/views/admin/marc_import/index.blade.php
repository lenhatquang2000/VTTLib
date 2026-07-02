@extends('layouts.admin')

@section('content')
<div class="w-full space-y-4 animate-in fade-in duration-500 pb-20">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
        <div>
            <h2 class="text-lg font-bold text-foreground tracking-tight">{{ __('MARC Records Import') }}</h2>
            <p class="text-xs text-muted-foreground mt-0.5">{{ __('Import từ file Excel hoặc file MARC (.mrc, .txt)') }}</p>
        </div>
        <a href="{{ route('admin.marc.book') }}" class="btn-compact-secondary">
            <i data-lucide="arrow-left" class="w-4 h-4 mr-1"></i>
            <span>{{ __('Back to Cataloging') }}</span>
        </a>
    </div>

    <!-- Tab Navigation -->
    <div class="bg-card text-foreground rounded-md border border-border shadow-sm overflow-hidden">
        <div class="flex border-b border-border">
            <button type="button" id="tabExcel" onclick="switchTab('excel')"
                class="flex-1 py-2 px-3 text-xs font-semibold border-b-2 border-primary text-primary bg-primary/5 transition duration-200 flex items-center justify-center gap-1.5">
                <i data-lucide="file-spreadsheet" class="w-4 h-4"></i>
                <span>{{ __('Import từ Excel') }}</span>
            </button>
            <button type="button" id="tabMarc" onclick="switchTab('marc')"
                class="flex-1 py-2 px-3 text-xs font-semibold border-b-2 border-transparent text-muted-foreground hover:text-foreground hover:bg-muted/50 transition duration-200 flex items-center justify-center gap-1.5">
                <i data-lucide="database" class="w-4 h-4"></i>
                <span>{{ __('Import từ file MARC (.mrc / .txt)') }}</span>
            </button>
        </div>
    </div>

    <!-- ============================================================ -->
    <!-- TAB 1: EXCEL IMPORT -->
    <!-- ============================================================ -->
    <div id="panelExcel" class="space-y-4">
        <!-- Import Form -->
        <div class="bg-card text-foreground rounded-md border border-border shadow-sm overflow-hidden">
            <div class="p-3">
                <form id="importForm" enctype="multipart/form-data">
                    @csrf

                    <!-- Framework Selection -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-3">
                        <div class="space-y-1">
                            <label class="block text-[10px] text-muted-foreground uppercase font-bold tracking-wider">
                                {{ __('Cataloging Framework') }} <span class="text-destructive">*</span>
                            </label>
                            <select name="framework_id" id="framework_id" required
                                class="w-full h-9 px-3 py-1.5 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all">
                                <option value="">{{ __('Select Framework') }}</option>
                                @foreach($frameworks as $framework)
                                <option value="{{ $framework->id }}">{{ $framework->name }} ({{ $framework->code }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="space-y-1">
                            <label class="block text-[10px] text-muted-foreground uppercase font-bold tracking-wider">
                                {{ __('Action Type') }} <span class="text-destructive">*</span>
                            </label>
                            <select name="action_type" id="action_type" required
                                class="w-full h-9 px-3 py-1.5 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all">
                                <option value="create">{{ __('Create New Records') }}</option>
                                <option value="update">{{ __('Update Existing Records') }}</option>
                            </select>
                        </div>
                    </div>

                    <!-- File Upload -->
                    <div class="mb-3">
                        <label class="block text-[10px] text-muted-foreground uppercase font-bold tracking-wider mb-1">
                            {{ __('Excel File') }} <span class="text-destructive">*</span>
                        </label>
                        <div id="dropZone" class="border-2 border-dashed border-border rounded-md p-6 text-center hover:border-primary transition-all duration-200 bg-muted/20">
                            <input type="file" name="excel_file" id="excel_file" accept=".xlsx,.xls,.csv" required class="hidden">
                            <label for="excel_file" class="cursor-pointer block">
                                <div id="uploadPlaceholder" class="flex flex-col items-center">
                                    <div class="w-12 h-12 bg-primary/10 text-primary border border-primary/20 rounded-full flex items-center justify-center mb-2">
                                        <i data-lucide="upload-cloud" class="w-6 h-6"></i>
                                    </div>
                                    <span class="text-xs font-semibold text-foreground">{{ __('Click to upload or drag and drop') }}</span>
                                    <span class="text-[10px] text-muted-foreground mt-0.5">{{ __('XLSX, XLS, CSV (Max 10MB)') }}</span>
                                </div>

                                <div id="fileSelectedState" class="hidden flex flex-col items-center">
                                    <div class="w-12 h-12 bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20 rounded-full flex items-center justify-center mb-2 animate-bounce">
                                        <i data-lucide="check-circle" class="w-6 h-6"></i>
                                    </div>
                                    <span id="selectedFileName" class="text-xs font-bold text-emerald-600 dark:text-emerald-400"></span>
                                    <span id="selectedFileSize" class="text-[10px] text-muted-foreground mt-0.5"></span>
                                    <button type="button" onclick="document.getElementById('resetBtn').click()" class="mt-2 text-[10px] text-destructive hover:underline">{{ __('Remove file') }}</button>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Template Download -->
                    <div class="bg-primary/5 border border-primary/15 rounded-sm p-3 mb-3 flex items-start gap-3">
                        <i data-lucide="info" class="w-4 h-4 text-primary shrink-0 mt-0.5"></i>
                        <div>
                            <h4 class="text-xs font-bold text-primary">{{ __('Download Template') }}</h4>
                            <p class="text-[10px] text-muted-foreground mt-0.5">{{ __('Download the Excel template to ensure proper data format') }}</p>
                            <button type="button" id="downloadTemplate" disabled class="mt-2 btn-compact-primary text-[10px] py-1 px-3">
                                <i data-lucide="download" class="w-3.5 h-3.5 mr-1"></i>
                                {{ __('Download Template') }}
                            </button>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex gap-2">
                        <button type="submit" id="uploadBtn" disabled class="flex-grow btn-compact-primary py-2.5 h-10 flex items-center justify-center gap-1.5">
                            <i data-lucide="upload-cloud" class="w-4 h-4"></i>
                            <span class="uppercase font-bold tracking-wider text-xs">{{ __('Upload & Validate') }}</span>
                        </button>
                        <button type="button" id="resetBtn" class="btn-compact-secondary py-2.5 h-10 px-6 flex items-center justify-center">
                            {{ __('Reset') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Validation Results -->
        <div id="validationResults" class="hidden bg-card text-foreground rounded-md border border-border shadow-sm overflow-hidden">
            <div class="p-3">
                <h3 class="text-xs font-bold text-foreground uppercase tracking-wider mb-3">{{ __('Validation Results') }}</h3>

                <!-- Summary -->
                <div class="grid grid-cols-3 gap-3 mb-3">
                    <div class="bg-muted/50 rounded-sm border border-border p-3 text-center">
                        <div class="text-lg font-bold text-foreground" id="totalRows">0</div>
                        <div class="text-[10px] text-muted-foreground uppercase font-bold tracking-wider mt-0.5">{{ __('Total Rows') }}</div>
                    </div>
                    <div class="bg-emerald-500/10 border border-emerald-500/20 rounded-sm p-3 text-center">
                        <div class="text-lg font-bold text-emerald-600 dark:text-emerald-400" id="validRows">0</div>
                        <div class="text-[10px] text-emerald-600 dark:text-emerald-400 uppercase font-bold tracking-wider mt-0.5">{{ __('Valid Rows') }}</div>
                    </div>
                    <div class="bg-destructive/10 border border-destructive/20 rounded-sm p-3 text-center">
                        <div class="text-lg font-bold text-destructive" id="invalidRows">0</div>
                        <div class="text-[10px] text-destructive uppercase font-bold tracking-wider mt-0.5">{{ __('Invalid Rows') }}</div>
                    </div>
                </div>

                <!-- Preview -->
                <div class="mb-3">
                    <h4 class="text-[10px] text-muted-foreground uppercase font-bold tracking-wider mb-2">{{ __('Preview (First 5 valid records)') }}</h4>
                    <div id="previewContainer" class="grid grid-cols-1 gap-3"></div>
                </div>

                <!-- Errors -->
                <div id="errorsSection" class="hidden mb-3">
                    <h4 class="text-[10px] text-destructive uppercase font-bold tracking-wider mb-1.5">{{ __('Validation Errors') }}</h4>
                    <div class="bg-destructive/5 border border-destructive/15 rounded-sm p-3 max-h-60 overflow-y-auto">
                        <div id="errorsList" class="space-y-1.5 text-xs"></div>
                    </div>

                    <!-- Suggested Action: Create Framework -->
                    <div id="createFrameworkSection" class="hidden mt-3 p-3 bg-primary/5 border border-primary/15 rounded-sm">
                        <div class="flex items-start gap-3">
                            <i data-lucide="plus-circle" class="w-4 h-4 text-primary shrink-0 mt-0.5"></i>
                            <div>
                                <h5 class="text-xs font-bold text-primary">{{ __('Dữ liệu không khớp với khung đã chọn?') }}</h5>
                                <p class="text-[10px] text-muted-foreground mt-0.5">
                                    {{ __('Có vẻ như file của bạn có cấu trúc cột khác với Khung biên mục hiện tại. Bạn có muốn hệ thống tự động tạo một Khung biên mục mới dựa trên các tiêu đề cột trong file này không?') }}
                                </p>
                                <button type="button" id="createFrameworkBtn" class="mt-2 btn-compact-primary py-1 px-3 text-[10px]">
                                    {{ __('Tạo Khung biên mục mới từ file này') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-2">
                    <button type="button" id="processBtn" disabled class="flex-grow btn-compact-primary py-2.5 h-10 flex items-center justify-center gap-1.5">
                        <i data-lucide="check" class="w-4 h-4"></i>
                        <span class="uppercase font-bold tracking-wider text-xs">{{ __('Process Import') }}</span>
                    </button>
                    <button type="button" id="cancelBtn" class="btn-compact-secondary py-2.5 h-10 px-6 flex items-center justify-center">
                        {{ __('Cancel') }}
                    </button>
                </div>
            </div>
        </div>

        <!-- Processing Results -->
        <div id="processingResults" class="hidden bg-card text-foreground rounded-md border border-border shadow-sm overflow-hidden">
            <div class="p-3">
                <h3 class="text-xs font-bold text-foreground uppercase tracking-wider mb-3">{{ __('Import Results') }}</h3>
                <div id="processingResultsContent"></div>
            </div>
        </div>
    </div><!-- /panelExcel -->

    <!-- ============================================================ -->
    <!-- TAB 2: MARC FILE IMPORT (.mrc / .txt) -->
    <!-- ============================================================ -->
    <div id="panelMarc" class="hidden space-y-4">
        <!-- MARC Upload Form -->
        <div class="bg-card text-foreground rounded-md border border-border shadow-sm overflow-hidden">
            <div class="p-3">
                <form id="marcImportForm" enctype="multipart/form-data">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-3">
                        <div class="space-y-1">
                            <label class="block text-[10px] text-muted-foreground uppercase font-bold tracking-wider">
                                {{ __('Loại thao tác') }} <span class="text-destructive">*</span>
                            </label>
                            <select name="action_type" id="marc_action_type" required
                                class="w-full h-9 px-3 py-1.5 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all">
                                <option value="create">{{ __('Tạo bản ghi mới') }}</option>
                                <option value="update">{{ __('Cập nhật bản ghi đã có') }}</option>
                            </select>
                        </div>
                        <div class="space-y-1">
                            <label class="block text-[10px] text-muted-foreground uppercase font-bold tracking-wider">
                                {{ __('Khung biên mục (tuỳ chọn)') }}
                            </label>
                            <select name="framework_id" id="marc_framework_id"
                                class="w-full h-9 px-3 py-1.5 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all">
                                <option value="">{{ __('-- Tự động trích xuất từ file --') }}</option>
                                @foreach($frameworks as $framework)
                                <option value="{{ $framework->id }}">{{ $framework->name }} ({{ $framework->code }})</option>
                                @endforeach
                            </select>
                            <p class="text-[9px] text-muted-foreground mt-0.5">{{ __('Để trống nếu muốn tạo khung mới từ file MARC') }}</p>
                        </div>
                    </div>

                    <!-- File Upload -->
                    <div class="mb-3">
                        <label class="block text-[10px] text-muted-foreground uppercase font-bold tracking-wider mb-1">
                            {{ __('File MARC') }} <span class="text-destructive">*</span>
                        </label>
                        <div id="marcDropZone" class="border-2 border-dashed border-border rounded-md p-6 text-center hover:border-primary transition-all duration-200 bg-muted/20">
                            <input type="file" name="marc_file" id="marc_file" accept=".mrc,.txt" required class="hidden">
                            <label for="marc_file" class="cursor-pointer block">
                                <div id="marcUploadPlaceholder" class="flex flex-col items-center">
                                    <div class="w-12 h-12 bg-primary/10 text-primary border border-primary/20 rounded-full flex items-center justify-center mb-2">
                                        <i data-lucide="upload-cloud" class="w-6 h-6"></i>
                                    </div>
                                    <span class="text-xs font-semibold text-foreground">{{ __('Click để chọn file hoặc kéo thả') }}</span>
                                    <span class="text-[10px] text-muted-foreground mt-0.5">{{ __('Hỗ trợ: .mrc (ISO 2709), .txt (MARC text) - Tối đa 10MB') }}</span>
                                </div>
                                <div id="marcFileSelectedState" class="hidden flex flex-col items-center">
                                    <div class="w-12 h-12 bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20 rounded-full flex items-center justify-center mb-2 animate-bounce">
                                        <i data-lucide="check-circle" class="w-6 h-6"></i>
                                    </div>
                                    <span id="marcSelectedFileName" class="text-xs font-bold text-emerald-600 dark:text-emerald-400"></span>
                                    <span id="marcSelectedFileSize" class="text-[10px] text-muted-foreground mt-0.5"></span>
                                    <button type="button" onclick="resetMarcFile()" class="mt-2 text-[10px] text-destructive hover:underline">{{ __('Xoá file') }}</button>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Info Box -->
                    <div class="bg-primary/5 border border-primary/15 rounded-sm p-3 mb-3 flex items-start gap-3">
                        <i data-lucide="info" class="w-4 h-4 text-primary shrink-0 mt-0.5"></i>
                        <div>
                            <h4 class="text-xs font-bold text-primary">{{ __('Hướng dẫn') }}</h4>
                            <ul class="text-[10px] text-muted-foreground mt-1 space-y-1 list-disc list-inside">
                                <li>{{ __('Hệ thống sẽ tự động phân tích cấu trúc MARC từ file') }}</li>
                                <li>{{ __('Sau khi upload, bạn có thể xem trước dữ liệu và khung biên mục được trích xuất') }}</li>
                                <li>{{ __('Bạn có thể lưu khung biên mục mới hoặc chọn khung đã có để import') }}</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="flex gap-2">
                        <button type="submit" id="marcUploadBtn" disabled class="flex-grow btn-compact-primary py-2.5 h-10 flex items-center justify-center gap-1.5">
                            <i data-lucide="upload-cloud" class="w-4 h-4"></i>
                            <span class="uppercase font-bold tracking-wider text-xs">{{ __('Upload & Phân tích') }}</span>
                        </button>
                        <button type="button" id="marcResetBtn" class="btn-compact-secondary py-2.5 h-10 px-6 flex items-center justify-center">
                            {{ __('Reset') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- MARC Validation Results -->
        <div id="marcValidationResults" class="hidden space-y-4">
            <!-- Summary -->
            <div class="bg-card text-foreground rounded-md border border-border shadow-sm overflow-hidden">
                <div class="p-3">
                    <h3 class="text-xs font-bold text-foreground uppercase tracking-wider mb-3">{{ __('Kết quả phân tích') }}</h3>
                    <div class="grid grid-cols-3 gap-3 mb-3">
                        <div class="bg-muted/50 rounded-sm border border-border p-3 text-center">
                            <div class="text-lg font-bold text-foreground" id="marcTotalRecords">0</div>
                            <div class="text-[10px] text-muted-foreground uppercase font-bold tracking-wider mt-0.5">{{ __('Tổng bản ghi') }}</div>
                        </div>
                        <div class="bg-emerald-500/10 border border-emerald-500/20 rounded-sm p-3 text-center">
                            <div class="text-lg font-bold text-emerald-600 dark:text-emerald-400" id="marcValidRecords">0</div>
                            <div class="text-[10px] text-emerald-600 dark:text-emerald-400 uppercase font-bold tracking-wider mt-0.5">{{ __('Hợp lệ') }}</div>
                        </div>
                        <div class="bg-destructive/10 border border-destructive/20 rounded-sm p-3 text-center">
                            <div class="text-lg font-bold text-destructive" id="marcInvalidRecords">0</div>
                            <div class="text-[10px] text-destructive uppercase font-bold tracking-wider mt-0.5">{{ __('Lỗi') }}</div>
                        </div>
                    </div>

                    <!-- Preview Records -->
                    <h4 class="text-[10px] text-muted-foreground uppercase font-bold tracking-wider mb-2">{{ __('Xem trước bản ghi (tối đa 5)') }}</h4>
                    <div id="marcPreviewContainer" class="space-y-3 mb-3"></div>

                    <!-- Errors -->
                    <div id="marcErrorsSection" class="hidden mb-3">
                        <h4 class="text-[10px] text-destructive uppercase font-bold tracking-wider mb-1.5">{{ __('Bản ghi lỗi') }}</h4>
                        <div class="bg-destructive/5 border border-destructive/15 rounded-sm p-3 max-h-40 overflow-y-auto">
                            <div id="marcErrorsList" class="space-y-1.5 text-xs"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Extracted Framework -->
            <div class="bg-card text-foreground rounded-md border border-border shadow-sm overflow-hidden">
                <div class="p-3">
                    <div class="flex justify-between items-center mb-3">
                        <div>
                            <h3 class="text-xs font-bold text-foreground uppercase tracking-wider">{{ __('Khung biên mục trích xuất') }}</h3>
                            <p class="text-[10px] text-muted-foreground mt-0.5">{{ __('Các trường MARC được phát hiện trong file. Bạn có muốn lưu khung này không?') }}</p>
                        </div>
                        <button type="button" id="saveFrameworkBtn" class="btn-compact-primary py-2 px-3 text-xs flex items-center gap-1">
                            <i data-lucide="save" class="w-3.5 h-3.5"></i>
                            <span>{{ __('Lưu khung biên mục này') }}</span>
                        </button>
                    </div>

                    <div id="marcFrameworkTable" class="overflow-x-auto rounded-sm border border-border">
                        <table class="w-full text-left border-collapse">
                            <thead class="bg-muted/50 border-b border-border text-muted-foreground uppercase font-bold text-[10px] tracking-wider">
                                <tr>
                                    <th class="py-2 px-3 w-20">{{ __('Tag') }}</th>
                                    <th class="py-2 px-3">{{ __('Tên trường') }}</th>
                                    <th class="py-2 px-3 w-40">{{ __('Trường con') }}</th>
                                </tr>
                            </thead>
                            <tbody id="marcFrameworkBody" class="divide-y divide-border text-xs">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Process Import -->
            <div class="bg-card text-foreground rounded-md border border-border shadow-sm overflow-hidden">
                <div class="p-3">
                    <h3 class="text-xs font-bold text-foreground uppercase tracking-wider mb-3">{{ __('Xác nhận Import') }}</h3>

                    <div id="marcFrameworkSelection" class="mb-3">
                        <label class="block text-[10px] text-muted-foreground uppercase font-bold tracking-wider mb-1">
                            {{ __('Chọn khung biên mục để import') }} <span class="text-destructive">*</span>
                        </label>
                        <select id="marcProcessFramework"
                            class="w-full h-9 px-3 py-1.5 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all">
                            <option value="">{{ __('-- Upload file để xem khung phù hợp --') }}</option>
                        </select>
                        <p class="text-[9px] text-muted-foreground mt-0.5">{{ __('Dropdown sẽ tự cập nhật sau khi upload file, hiển thị khung phù hợp với dấu ✅') }}</p>
                    </div>

                    <div class="flex space-x-3">
                        <button type="button" id="marcProcessBtn" disabled class="flex-grow btn-compact-primary py-2.5 h-10 flex items-center justify-center gap-1.5">
                            <i data-lucide="check" class="w-4 h-4"></i>
                            <span class="uppercase font-bold tracking-wider text-xs">{{ __('Tiến hành Import') }}</span>
                        </button>
                        <button type="button" id="marcCancelBtn" class="btn-compact-secondary py-2.5 h-10 px-6 flex items-center justify-center">
                            {{ __('Huỷ') }}
                        </button>
                    </div>
                </div>
            </div>

            <!-- MARC Processing Results -->
            <div id="marcProcessingResults" class="hidden bg-card text-foreground rounded-md border border-border shadow-sm overflow-hidden">
                <div class="p-3">
                    <h3 class="text-xs font-bold text-foreground uppercase tracking-wider mb-3">{{ __('Kết quả Import') }}</h3>
                    <div id="marcProcessingResultsContent"></div>
                </div>
            </div>
        </div>
    </div><!-- /panelMarc -->
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

        // SweetAlert standard configurations
        const swalConfig = {
            customClass: {
                popup: 'bg-card text-foreground border border-border rounded-md p-4 w-80',
                title: 'text-foreground font-bold text-sm border-b border-border pb-2',
                htmlContainer: 'text-muted-foreground text-xs mt-2',
                confirmButton: 'px-4 py-2 bg-primary text-primary-foreground hover:bg-primary/90 rounded-sm text-xs font-bold uppercase tracking-wider mx-1',
                cancelButton: 'px-4 py-2 bg-muted text-foreground hover:bg-muted/80 rounded-sm text-xs font-bold uppercase tracking-wider border border-border mx-1'
            },
            buttonsStyling: false
        };

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
                dropZone.classList.add('border-emerald-500/30', 'bg-emerald-500/5');
                dropZone.classList.remove('border-border', 'bg-muted/20');
            } else {
                uploadPlaceholder.classList.remove('hidden');
                fileSelectedState.classList.add('hidden');
                dropZone.classList.remove('border-emerald-500/30', 'bg-emerald-500/5');
                dropZone.classList.add('border-border', 'bg-muted/20');
            }
        }

        fileInput.addEventListener('change', updateButtonStates);
        frameworkSelect.addEventListener('change', updateButtonStates);

        downloadTemplateBtn.addEventListener('click', function() {
            const frameworkId = frameworkSelect.value;
            if (frameworkId) {
                window.location.href = `{{ route('admin.marc.import.template') }}?framework_id=${frameworkId}`;
            }
        });

        // Drag & drop Excel
        dropZone.addEventListener('dragover', e => { e.preventDefault(); dropZone.classList.add('border-primary', 'bg-primary/5'); });
        dropZone.addEventListener('dragleave', e => { e.preventDefault(); dropZone.classList.remove('border-primary', 'bg-primary/5'); });
        dropZone.addEventListener('drop', e => {
            e.preventDefault();
            dropZone.classList.remove('border-primary', 'bg-primary/5');
            if (e.dataTransfer.files.length > 0) {
                fileInput.files = e.dataTransfer.files;
                updateButtonStates();
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
                const response = await fetch('{{ route('admin.marc.import.upload') }}', {
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
                            ...swalConfig,
                            icon: 'success',
                            title: "{{ __('File Uploaded') }}",
                            text: "{{ __('File has been uploaded and validated successfully.') }}",
                            timer: 2000,
                            showConfirmButton: false
                        });
                    } else {
                        Swal.fire({
                            ...swalConfig,
                            icon: 'error',
                            title: "{{ __('Error') }}",
                            text: result.message
                        });
                    }
                } else {
                    const html = await response.text();
                    const errorWindow = window.open('', '_blank');
                    errorWindow.document.write(html);
                    errorWindow.document.close();
                    Swal.fire({
                        ...swalConfig,
                        icon: 'warning',
                        title: "{{ __('Debug Output') }}",
                        text: "{{ __('The server returned an HTML response. It has been opened in a new tab for debugging.') }}"
                    });
                }
            } catch (error) {
                Swal.fire({
                    ...swalConfig,
                    icon: 'error',
                    title: "{{ __('Error') }}",
                    text: error.message
                });
            } finally {
                uploadBtn.disabled = false;
                uploadBtn.innerHTML = `
                    <i data-lucide="upload-cloud" class="w-4 h-4"></i>
                    <span class="uppercase font-bold tracking-wider text-xs">${actionTypeSelect.value === 'update' ? '{{ __('Upload & Validate') }}' : '{{ __('Upload & Validate') }}'}</span>
                `;
                lucide.createIcons();
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
                card.className = "bg-muted/30 border border-border rounded-sm p-3 font-mono text-xs overflow-x-auto";
                card.innerHTML = `
                    <div class="flex justify-between items-center mb-1.5 pb-1.5 border-b border-border">
                        <span class="text-xs font-bold text-primary">#{{ __('Row') }} ${record.row_index}</span>
                        <span class="text-xs text-muted-foreground">${record.title}</span>
                    </div>
                    <pre class="text-foreground leading-relaxed whitespace-pre-wrap font-mono text-xs">${record.raw_marc}</pre>
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
                    errorDiv.className = 'flex items-start gap-1.5';
                    errorDiv.innerHTML = `<span class="text-destructive font-medium">{{ __('Row') }} ${error.row_index}:</span> <span class="text-muted-foreground">${error.errors.join(', ')}</span>`;
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
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-3">
                    <div class="p-3 bg-muted/50 rounded-sm border border-border text-center">
                        <div class="text-lg font-bold text-foreground">${total}</div>
                        <div class="text-[10px] text-muted-foreground uppercase font-bold tracking-wider mt-0.5">{{ __('Total Processed') }}</div>
                    </div>
                    <div class="p-3 bg-emerald-500/10 border border-emerald-500/20 rounded-sm text-center">
                        <div class="text-lg font-bold text-emerald-600 dark:text-emerald-400">${successCount}</div>
                        <div class="text-[10px] text-emerald-600 dark:text-emerald-400 uppercase font-bold tracking-wider mt-0.5">{{ __('Successful') }}</div>
                    </div>
                    <div class="p-3 bg-destructive/10 border border-destructive/20 rounded-sm text-center">
                        <div class="text-lg font-bold text-destructive">${failCount}</div>
                        <div class="text-[10px] text-destructive uppercase font-bold tracking-wider mt-0.5">{{ __('Failed') }}</div>
                    </div>
                </div>
                <div class="overflow-x-auto rounded-sm border border-border mb-3">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-muted/50 border-b border-border text-muted-foreground uppercase font-bold text-[10px] tracking-wider">
                            <tr>
                                <th class="py-2 px-3 w-16">{{ __('Row') }}</th>
                                <th class="py-2 px-3">{{ __('Title') }}</th>
                                <th class="py-2 px-3 w-28">{{ __('Status') }}</th>
                                <th class="py-2 px-3">{{ __('Details') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-border text-xs">
                            ${data.map(result => `
                                <tr class="table-row-hover">
                                    <td class="py-2 px-3">${result.row_index}</td>
                                    <td class="py-2 px-3 font-semibold">${result.title || '-'}</td>
                                    <td class="py-2 px-3">
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded-sm text-[9px] font-bold uppercase tracking-wider border ${result.success ? 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border-emerald-500/20' : 'bg-destructive/10 text-destructive border-destructive/20'}">
                                            ${result.success ? '{{ __("Success") }}' : '{{ __("Failed") }}'}
                                        </span>
                                    </td>
                                    <td class="py-2 px-3 text-xs ${result.success ? 'text-muted-foreground' : 'text-destructive'}">
                                        ${result.success ? 'ID: ' + result.record_id : result.error}
                                    </td>
                                </tr>
                            `).join('')}
                        </tbody>
                    </table>
                </div>
                <div class="flex justify-end">
                    <button type="button" onclick="location.reload()" class="btn-compact-primary py-2 px-6">
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
            const ts = Date.now().toString().slice(-6);
            const {
                value: formValues
            } = await Swal.fire({
                ...swalConfig,
                title: '{{ __("Tạo Khung biên mục mới") }}',
                html: '<div class="text-left space-y-3 pt-3">' +
                    '<div class="space-y-1">' +
                    '<label class="text-[10px] font-bold text-muted-foreground uppercase tracking-wider ml-1">Tên khung mới</label>' +
                    '<input id="swal-input1" class="w-full h-9 px-3 py-1.5 bg-background border border-input rounded-sm text-sm focus:ring-1 focus:ring-primary focus:border-primary outline-none text-foreground" value="{{ __("Khung sách giáo trình") }} ' + ts + '" placeholder="{{ __("Tên khung (VD: Tài liệu số)") }}">' +
                    '</div>' +
                    '<div class="space-y-1">' +
                    '<label class="text-[10px] font-bold text-muted-foreground uppercase tracking-wider ml-1">Mã viết tắt</label>' +
                    '<input id="swal-input2" class="w-full h-9 px-3 py-1.5 bg-background border border-input rounded-sm text-sm focus:ring-1 focus:ring-primary focus:border-primary outline-none text-foreground font-mono" value="GIAOTRINH_' + ts + '" placeholder="{{ __("Mã khung (VD: DIGI)") }}">' +
                    '</div>' +
                    '</div>',
                focusConfirm: false,
                showCancelButton: true,
                confirmButtonText: '{{ __("Tạo ngay") }}',
                cancelButtonText: '{{ __("Huỷ") }}',
                preConfirm: () => {
                    const name = document.getElementById('swal-input1').value.trim();
                    const code = document.getElementById('swal-input2').value.trim();
                    if (!name || !code) {
                        Swal.showValidationMessage('Vui lòng nhập đầy đủ tên và mã khung');
                        return false;
                    }
                    return [name, code];
                }
            });

            if (formValues && formValues[0] && formValues[1]) {
                const formData = new FormData(form);
                formData.append('framework_name', formValues[0]);
                formData.append('framework_code', formValues[1]);

                Swal.fire({
                    ...swalConfig,
                    title: '{{ __("Đang khởi tạo...") }}',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                try {
                    const response = await fetch('{{ route('admin.marc.import.create-framework') }}', {
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
                            ...swalConfig,
                            icon: 'success',
                            title: '{{ __("Thành công") }}',
                            text: result.message
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            ...swalConfig,
                            icon: 'error',
                            title: '{{ __("Lỗi") }}',
                            text: result.message
                        });
                    }
                } catch (error) {
                    Swal.fire({
                        ...swalConfig,
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
                const response = await fetch('{{ route('admin.marc.import.process') }}', {
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
                        ...swalConfig,
                        icon: 'error',
                        title: "{{ __('Error') }}",
                        text: result.message
                    });
                }
            } catch (error) {
                Swal.fire({
                    ...swalConfig,
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

    // ========================================================================
    // TAB SWITCHING
    // ========================================================================
    function switchTab(tab) {
        const tabExcel = document.getElementById('tabExcel');
        const tabMarc = document.getElementById('tabMarc');
        const panelExcel = document.getElementById('panelExcel');
        const panelMarc = document.getElementById('panelMarc');

        const activeClass = 'border-primary text-primary bg-primary/5';
        const inactiveClass = 'border-transparent text-muted-foreground hover:text-foreground hover:bg-muted/50';

        if (tab === 'excel') {
            panelExcel.classList.remove('hidden');
            panelMarc.classList.add('hidden');
            tabExcel.className = `flex-1 py-2 px-3 text-xs font-semibold border-b-2 ${activeClass} transition duration-200 flex items-center justify-center gap-1.5`;
            tabMarc.className = `flex-1 py-2 px-3 text-xs font-semibold border-b-2 ${inactiveClass} transition duration-200 flex items-center justify-center gap-1.5`;
        } else {
            panelExcel.classList.add('hidden');
            panelMarc.classList.remove('hidden');
            tabMarc.className = `flex-1 py-2 px-3 text-xs font-semibold border-b-2 ${activeClass} transition duration-200 flex items-center justify-center gap-1.5`;
            tabExcel.className = `flex-1 py-2 px-3 text-xs font-semibold border-b-2 ${inactiveClass} transition duration-200 flex items-center justify-center gap-1.5`;
        }
    }

    // ========================================================================
    // MARC FILE IMPORT TAB LOGIC
    // ========================================================================
    document.addEventListener('DOMContentLoaded', function() {
        const marcForm = document.getElementById('marcImportForm');
        const marcFileInput = document.getElementById('marc_file');
        const marcUploadBtn = document.getElementById('marcUploadBtn');
        const marcDropZone = document.getElementById('marcDropZone');
        const marcUploadPlaceholder = document.getElementById('marcUploadPlaceholder');
        const marcFileSelectedState = document.getElementById('marcFileSelectedState');
        const marcSelectedFileName = document.getElementById('marcSelectedFileName');
        const marcSelectedFileSize = document.getElementById('marcSelectedFileSize');

        let extractedFrameworkData = [];

        const swalConfig = {
            customClass: {
                popup: 'bg-card text-foreground border border-border rounded-md p-4 w-80',
                title: 'text-foreground font-bold text-sm border-b border-border pb-2',
                htmlContainer: 'text-muted-foreground text-xs mt-2',
                confirmButton: 'px-4 py-2 bg-primary text-primary-foreground hover:bg-primary/90 rounded-sm text-xs font-bold uppercase tracking-wider mx-1',
                cancelButton: 'px-4 py-2 bg-muted text-foreground hover:bg-muted/80 rounded-sm text-xs font-bold uppercase tracking-wider border border-border mx-1'
            },
            buttonsStyling: false
        };

        // File selection UI
        function updateMarcFileState() {
            const hasFile = marcFileInput.files.length > 0;
            marcUploadBtn.disabled = !hasFile;

            if (hasFile) {
                const file = marcFileInput.files[0];
                marcSelectedFileName.textContent = file.name;
                marcSelectedFileSize.textContent = (file.size / 1024 / 1024).toFixed(2) + ' MB';
                marcUploadPlaceholder.classList.add('hidden');
                marcFileSelectedState.classList.remove('hidden');
                marcDropZone.classList.add('border-emerald-500/30', 'bg-emerald-500/5');
                marcDropZone.classList.remove('border-border', 'bg-muted/20');
            } else {
                marcUploadPlaceholder.classList.remove('hidden');
                marcFileSelectedState.classList.add('hidden');
                marcDropZone.classList.remove('border-emerald-500/30', 'bg-emerald-500/5');
                marcDropZone.classList.add('border-border', 'bg-muted/20');
            }
        }

        marcFileInput.addEventListener('change', updateMarcFileState);

        // Drag & drop MARC
        marcDropZone.addEventListener('dragover', e => { e.preventDefault(); marcDropZone.classList.add('border-primary', 'bg-primary/5'); });
        marcDropZone.addEventListener('dragleave', e => { e.preventDefault(); marcDropZone.classList.remove('border-primary', 'bg-primary/5'); });
        marcDropZone.addEventListener('drop', e => {
            e.preventDefault();
            marcDropZone.classList.remove('border-primary', 'bg-primary/5');
            if (e.dataTransfer.files.length > 0) {
                marcFileInput.files = e.dataTransfer.files;
                updateMarcFileState();
            }
        });

        // Upload & parse
        marcForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            marcUploadBtn.disabled = true;
            marcUploadBtn.innerHTML = `
                <svg class="animate-spin h-4 w-4 mr-2 inline" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                {{ __('Đang phân tích...') }}
            `;

            try {
                const formData = new FormData(marcForm);
                const response = await fetch('{{ route("admin.marc.import.upload-marc") }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                const result = await response.json();
                if (result.success) {
                    showMarcResults(result.data);
                    Swal.fire({
                        ...swalConfig,
                        icon: 'success',
                        title: '{{ __("Phân tích thành công") }}',
                        text: result.message,
                        timer: 2000,
                        showConfirmButton: false
                    });
                } else {
                    Swal.fire({
                        ...swalConfig,
                        icon: 'error',
                        title: '{{ __("Lỗi") }}',
                        text: result.message
                    });
                }
            } catch (error) {
                Swal.fire({
                    ...swalConfig,
                    icon: 'error',
                    title: '{{ __("Lỗi kết nối") }}',
                    text: error.message
                });
            } finally {
                marcUploadBtn.disabled = false;
                marcUploadBtn.innerHTML = `
                    <i data-lucide="upload-cloud" class="w-4 h-4"></i>
                    <span>{{ __('Upload & Phân tích') }}</span>
                `;
                lucide.createIcons();
            }
        });

        function showMarcResults(data) {
            document.getElementById('marcTotalRecords').textContent = data.total_records;
            document.getElementById('marcValidRecords').textContent = data.valid_records;
            document.getElementById('marcInvalidRecords').textContent = data.invalid_records;

            // Preview records
            const previewContainer = document.getElementById('marcPreviewContainer');
            previewContainer.innerHTML = '';
            data.preview.forEach(rec => {
                const card = document.createElement('div');
                card.className = 'bg-muted/30 border border-border rounded-sm p-3';
                let fieldsHtml = '';
                if (rec.fields_summary) {
                    fieldsHtml = '<div class="mt-2 space-y-0.5 border-t border-border pt-2">' +
                        rec.fields_summary.map(f =>
                            `<div class="flex text-xs items-baseline"><span class="w-12 font-mono font-bold text-primary shrink-0">${f.tag}</span><span class="text-muted-foreground w-40 truncate shrink-0">${f.label}</span><span class="text-foreground flex-grow truncate font-mono text-[11px]">${f.value}</span></div>`
                        ).join('') +
                    '</div>';
                }
                card.innerHTML = `
                    <div class="flex justify-between items-center mb-1.5 pb-1.5 border-b border-border">
                        <span class="text-xs font-bold text-primary">#${rec.row_index}</span>
                        <div class="flex items-center space-x-3 text-[10px] text-muted-foreground font-mono">
                            <span><strong>ISBN:</strong> ${rec.isbn || 'N/A'}</span>
                            <span><strong>{{ __('Năm') }}:</strong> ${rec.year || 'N/A'}</span>
                        </div>
                    </div>
                    <div class="text-xs font-bold text-foreground leading-tight">${rec.title || 'N/A'}</div>
                    <div class="text-[10px] text-muted-foreground mt-0.5">${rec.author || 'N/A'} ${rec.publisher ? '— ' + rec.publisher : ''}</div>
                    ${fieldsHtml}
                `;
                previewContainer.appendChild(card);
            });

            // Errors
            const errorsSection = document.getElementById('marcErrorsSection');
            const errorsList = document.getElementById('marcErrorsList');
            if (data.errors && data.errors.length > 0) {
                errorsSection.classList.remove('hidden');
                errorsList.innerHTML = '';
                data.errors.forEach(err => {
                    const div = document.createElement('div');
                    div.className = 'text-destructive';
                    div.textContent = `{{ __('Bản ghi') }} #${err.row_index}: ${err.errors.join(', ')}`;
                    errorsList.appendChild(div);
                });
            } else {
                errorsSection.classList.add('hidden');
            }

            // Extracted framework table
            extractedFrameworkData = data.extracted_framework || [];
            const fwBody = document.getElementById('marcFrameworkBody');
            fwBody.innerHTML = '';
            extractedFrameworkData.forEach(tag => {
                const sfCodes = Object.values(tag.subfields).join(', ');
                const tr = document.createElement('tr');
                tr.className = 'table-row-hover';
                tr.innerHTML = `
                    <td class="py-2 px-3 font-mono font-bold text-primary">${tag.tag}</td>
                    <td class="py-2 px-3 text-foreground">${tag.label}</td>
                    <td class="py-2 px-3 font-mono text-muted-foreground">${sfCodes || '-'}</td>
                `;
                fwBody.appendChild(tr);
            });

            // Rebuild framework dropdown with matching info
            const marcProcessFramework = document.getElementById('marcProcessFramework');
            const marcProcessBtn = document.getElementById('marcProcessBtn');
            marcProcessFramework.innerHTML = '';

            // Option: create new from file
            const createOpt = document.createElement('option');
            createOpt.value = '__create_new__';
            createOpt.textContent = '➕ {{ __("Tạo khung mới từ file MARC") }}';
            createOpt.style.fontWeight = 'bold';
            marcProcessFramework.appendChild(createOpt);

            // Separator
            const sepOpt = document.createElement('option');
            sepOpt.disabled = true;
            sepOpt.textContent = '─────────────────────────';
            marcProcessFramework.appendChild(sepOpt);

            // Matching frameworks from server
            const matchingFws = data.matching_frameworks || [];
            let bestMatchId = null;

            matchingFws.forEach(fw => {
                const opt = document.createElement('option');
                opt.value = fw.id;
                let label = `${fw.name} (${fw.code})`;
                if (fw.is_compatible) {
                    label += ` [Phù hợp ${fw.match_ratio}% (${fw.matched_tags}/${fw.total_file_tags} tags)]`;
                    if (!bestMatchId) bestMatchId = fw.id;
                } else {
                    label += ` — ${fw.match_ratio}% (${fw.matched_tags}/${fw.total_file_tags} tags)`;
                }
                opt.textContent = label;
                marcProcessFramework.appendChild(opt);
            });

            marcProcessFramework.addEventListener('change', function() {
                marcProcessBtn.disabled = !this.value;
            });

            // Auto-select best match or create-new
            const preSelectedFw = document.getElementById('marc_framework_id').value;
            if (preSelectedFw) {
                marcProcessFramework.value = preSelectedFw;
            } else if (bestMatchId) {
                marcProcessFramework.value = bestMatchId;
            } else {
                marcProcessFramework.value = '__create_new__';
            }
            marcProcessBtn.disabled = false;

            document.getElementById('marcValidationResults').classList.remove('hidden');
            document.getElementById('marcValidationResults').scrollIntoView({ behavior: 'smooth' });
        }

        // Save framework button
        document.getElementById('saveFrameworkBtn').addEventListener('click', async function() {
            if (extractedFrameworkData.length === 0) {
                Swal.fire({
                    ...swalConfig,
                    icon: 'warning',
                    title: '{{ __("Không có dữ liệu") }}',
                    text: '{{ __("Chưa có khung biên mục để lưu. Hãy upload file trước.") }}'
                });
                return;
            }

            const ts = Date.now().toString().slice(-6);
            const { value: formValues } = await Swal.fire({
                ...swalConfig,
                title: '{{ __("Lưu Khung biên mục") }}',
                html: '<div class="text-left space-y-3 pt-3">' +
                    '<div class="space-y-1">' +
                    '<label class="text-[10px] font-bold text-muted-foreground uppercase tracking-wider ml-1">{{ __("Tên khung biên mục") }}</label>' +
                    '<input id="swal-fw-name" class="w-full h-9 px-3 py-1.5 bg-background border border-input rounded-sm text-sm focus:ring-1 focus:ring-primary focus:border-primary outline-none text-foreground" value="{{ __("Khung sách giáo trình") }} ' + ts + '" placeholder="{{ __("VD: Sách giáo trình y khoa") }}">' +
                    '</div>' +
                    '<div class="space-y-1">' +
                    '<label class="text-[10px] font-bold text-muted-foreground uppercase tracking-wider ml-1">{{ __("Mã khung (viết tắt, không dấu)") }}</label>' +
                    '<input id="swal-fw-code" class="w-full h-9 px-3 py-1.5 bg-background border border-input rounded-sm text-sm focus:ring-1 focus:ring-primary focus:border-primary outline-none text-foreground font-mono" value="GIAOTRINH_' + ts + '" placeholder="{{ __("VD: SGTYKHOA") }}" style="text-transform:uppercase">' +
                    '</div>' +
                    `<div class="text-[10px] text-muted-foreground font-bold uppercase tracking-wider mt-1">{{ __("Khung sẽ bao gồm") }} <span class="text-primary">${extractedFrameworkData.length}</span> {{ __("trường MARC") }}</div>` +
                    '</div>',
                focusConfirm: false,
                showCancelButton: true,
                confirmButtonText: '{{ __("Lưu khung") }}',
                cancelButtonText: '{{ __("Huỷ") }}',
                preConfirm: () => {
                    const name = document.getElementById('swal-fw-name').value.trim();
                    const code = document.getElementById('swal-fw-code').value.trim();
                    if (!name || !code) {
                        Swal.showValidationMessage('{{ __("Vui lòng nhập đầy đủ tên và mã khung") }}');
                        return false;
                    }
                    return { name, code };
                }
            });

            if (formValues) {
                Swal.fire({
                    ...swalConfig,
                    title: '{{ __("Đang lưu...") }}',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                try {
                    const tagsPayload = extractedFrameworkData.map(t => ({
                        tag: t.tag,
                        label: t.label,
                        subfields: Object.values(t.subfields)
                    }));

                    const response = await fetch('{{ route("admin.marc.import.save-framework-marc") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            framework_name: formValues.name,
                            framework_code: formValues.code,
                            tags: tagsPayload
                        })
                    });

                    const result = await response.json();
                    if (result.success) {
                        const select = document.getElementById('marcProcessFramework');
                        const opt = document.createElement('option');
                        opt.value = result.data.framework_id;
                        opt.textContent = `${result.data.framework_name} (${result.data.framework_code})`;
                        opt.selected = true;
                        select.appendChild(opt);
                        document.getElementById('marcProcessBtn').disabled = false;

                        Swal.fire({
                            ...swalConfig,
                            icon: 'success',
                            title: '{{ __("Khung biên mục đã lưu") }}',
                            text: `Khung ${result.data.framework_name} đã được tạo thành công và đã được chọn để import.`
                        });
                    } else {
                        Swal.fire({
                            ...swalConfig,
                            icon: 'error',
                            title: '{{ __("Lỗi") }}',
                            text: result.message
                        });
                    }
                } catch (error) {
                    Swal.fire({
                        ...swalConfig,
                        icon: 'error',
                        title: '{{ __("Lỗi kết nối") }}',
                        text: error.message
                    });
                }
            }
        });

        // Process MARC import
        document.getElementById('marcProcessBtn').addEventListener('click', async function() {
            let frameworkId = document.getElementById('marcProcessFramework').value;
            const actionType = document.getElementById('marc_action_type').value;

            if (!frameworkId) {
                Swal.fire({
                    ...swalConfig,
                    icon: 'warning',
                    title: '{{ __("Chưa chọn khung") }}',
                    text: '{{ __("Vui lòng chọn khung biên mục trước khi import.") }}'
                });
                return;
            }

            // If "create new from file" selected, prompt for name/code first
            if (frameworkId === '__create_new__') {
                if (extractedFrameworkData.length === 0) {
                    Swal.fire({
                        ...swalConfig,
                        icon: 'warning',
                        title: '{{ __("Không có dữ liệu") }}',
                        text: '{{ __("Chưa có khung biên mục để tạo. Hãy upload file trước.") }}'
                    });
                    return;
                }

                const ts = Date.now().toString().slice(-6);
                const { value: formValues } = await Swal.fire({
                    ...swalConfig,
                    title: '{{ __("Tạo khung biên mục từ file") }}',
                    html: '<div class="text-left space-y-3 pt-3">' +
                        '<div class="space-y-1">' +
                        '<label class="text-[10px] font-bold text-muted-foreground uppercase tracking-wider ml-1">{{ __("Tên khung biên mục") }}</label>' +
                        '<input id="swalFwName" class="w-full h-9 px-3 py-1.5 bg-background border border-input rounded-sm text-sm focus:ring-1 focus:ring-primary focus:border-primary outline-none text-foreground" value="{{ __("Khung sách giáo trình") }} ' + ts + '" placeholder="{{ __("Ví dụ: Khung sách giáo trình") }}">' +
                        '</div>' +
                        '<div class="space-y-1">' +
                        '<label class="text-[10px] font-bold text-muted-foreground uppercase tracking-wider ml-1">{{ __("Mã khung (viết hoa)") }}</label>' +
                        '<input id="swalFwCode" class="w-full h-9 px-3 py-1.5 bg-background border border-input rounded-sm text-sm focus:ring-1 focus:ring-primary focus:border-primary outline-none text-foreground font-mono" value="GIAOTRINH_' + ts + '" placeholder="{{ __("Ví dụ: GIAOTRINH") }}">' +
                        '</div>' +
                        '</div>',
                    focusConfirm: false,
                    showCancelButton: true,
                    confirmButtonText: '{{ __("Tạo & Import") }}',
                    cancelButtonText: '{{ __("Huỷ") }}',
                    preConfirm: () => {
                        const name = document.getElementById('swalFwName').value.trim();
                        const code = document.getElementById('swalFwCode').value.trim().toUpperCase();
                        if (!name || !code) {
                            Swal.showValidationMessage('{{ __("Vui lòng nhập đầy đủ tên và mã khung") }}');
                            return false;
                        }
                        if (code.length > 20) {
                            Swal.showValidationMessage('{{ __("Mã khung tối đa 20 ký tự") }}');
                            return false;
                        }
                        return { name, code };
                    }
                });

                if (!formValues) return;

                // Create framework first
                this.disabled = true;
                this.innerHTML = `
                    <svg class="animate-spin h-4 w-4 mr-2 inline" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    {{ __('Đang tạo khung...') }}
                `;

                try {
                    const tagsPayload = extractedFrameworkData.map(t => ({
                        tag: t.tag,
                        label: t.label,
                        subfields: Object.values(t.subfields)
                    }));

                    const fwResponse = await fetch('{{ route("admin.marc.import.save-framework-marc") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            framework_name: formValues.name,
                            framework_code: formValues.code,
                            tags: tagsPayload
                        })
                    });

                    const fwResult = await fwResponse.json();
                    if (!fwResult.success) {
                        Swal.fire({
                            ...swalConfig,
                            icon: 'error',
                            title: '{{ __("Lỗi tạo khung") }}',
                            text: fwResult.message
                        });
                        return;
                    }

                    frameworkId = fwResult.data.framework_id;
                    Swal.fire({
                        ...swalConfig,
                        icon: 'success',
                        title: '{{ __("Đã tạo khung") }}',
                        text: `${fwResult.data.framework_name} (${fwResult.data.framework_code})`,
                        timer: 1500,
                        showConfirmButton: false
                    });
                    await new Promise(r => setTimeout(r, 1600));
                } catch (error) {
                    Swal.fire({
                        ...swalConfig,
                        icon: 'error',
                        title: '{{ __("Lỗi kết nối") }}',
                        text: error.message
                    });
                    return;
                } finally {
                    this.disabled = false;
                    this.innerHTML = `
                        <i data-lucide="check" class="w-4 h-4"></i>
                        <span>{{ __('Tiến hành Import') }}</span>
                    `;
                    lucide.createIcons();
                }
            }

            // Confirm import
            const confirmResult = await Swal.fire({
                ...swalConfig,
                title: '{{ __("Xác nhận Import") }}',
                text: '{{ __("Bạn có chắc chắn muốn tiến hành import các bản ghi MARC đã phân tích?") }}',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: '{{ __("Tiến hành") }}',
                cancelButtonText: '{{ __("Huỷ") }}'
            });

            if (!confirmResult.isConfirmed) return;

            this.disabled = true;
            this.innerHTML = `
                <svg class="animate-spin h-4 w-4 mr-2 inline" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                {{ __('Đang import...') }}
            `;

            try {
                const response = await fetch('{{ route("admin.marc.import.process-marc") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        framework_id: frameworkId,
                        action_type: actionType
                    })
                });

                const result = await response.json();
                if (result.success) {
                    showMarcProcessingResults(result.data);
                    Swal.fire({
                        ...swalConfig,
                        icon: 'success',
                        title: '{{ __("Import thành công") }}',
                        text: result.message,
                        timer: 2000,
                        showConfirmButton: false
                    });
                } else {
                    Swal.fire({
                        ...swalConfig,
                        icon: 'error',
                        title: '{{ __("Lỗi") }}',
                        text: result.message
                    });
                }
            } catch (error) {
                Swal.fire({
                    ...swalConfig,
                    icon: 'error',
                    title: '{{ __("Lỗi kết nối") }}',
                    text: error.message
                });
            } finally {
                this.disabled = false;
                this.innerHTML = `
                    <i data-lucide="check" class="w-4 h-4"></i>
                    <span>{{ __('Tiến hành Import') }}</span>
                `;
                lucide.createIcons();
            }
        });

        function showMarcProcessingResults(data) {
            const container = document.getElementById('marcProcessingResultsContent');
            const total = data.length;
            const successCount = data.filter(r => r.success).length;
            const failCount = total - successCount;

            container.innerHTML = `
                <div class="grid grid-cols-3 gap-3 mb-3">
                    <div class="p-3 bg-muted/50 rounded-sm border border-border text-center">
                        <div class="text-lg font-bold text-foreground">${total}</div>
                        <div class="text-[10px] text-muted-foreground uppercase font-bold tracking-wider mt-0.5">{{ __('Tổng xử lý') }}</div>
                    </div>
                    <div class="p-3 bg-emerald-500/10 border border-emerald-500/20 rounded-sm text-center">
                        <div class="text-lg font-bold text-emerald-600 dark:text-emerald-400">${successCount}</div>
                        <div class="text-[10px] text-emerald-600 dark:text-emerald-400 uppercase font-bold tracking-wider mt-0.5">{{ __('Thành công') }}</div>
                    </div>
                    <div class="p-3 bg-destructive/10 border border-destructive/20 rounded-sm text-center">
                        <div class="text-lg font-bold text-destructive">${failCount}</div>
                        <div class="text-[10px] text-destructive uppercase font-bold tracking-wider mt-0.5">{{ __('Thất bại') }}</div>
                    </div>
                </div>
                <div class="overflow-x-auto rounded-sm border border-border mb-3">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-muted/50 border-b border-border text-muted-foreground uppercase font-bold text-[10px] tracking-wider">
                            <tr>
                                <th class="py-2 px-3 w-16">#</th>
                                <th class="py-2 px-3">{{ __('Nhan đề') }}</th>
                                <th class="py-2 px-3 w-28">{{ __('Trạng thái') }}</th>
                                <th class="py-2 px-3">{{ __('Chi tiết') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-border text-xs">
                            ${data.map(r => `
                                <tr class="table-row-hover">
                                    <td class="py-2 px-3">${r.row_index}</td>
                                    <td class="py-2 px-3 font-semibold">${r.title || '-'}</td>
                                    <td class="py-2 px-3">
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded-sm text-[9px] font-bold uppercase tracking-wider border ${r.success ? 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border-emerald-500/20' : 'bg-destructive/10 text-destructive border-destructive/20'}">
                                            ${r.success ? '{{ __("OK") }}' : '{{ __("Lỗi") }}'}
                                        </span>
                                    </td>
                                    <td class="py-2 px-3 text-xs ${r.success ? 'text-muted-foreground' : 'text-destructive'}">
                                        ${r.success ? 'ID: ' + r.record_id : r.error}
                                    </td>
                                </tr>
                            `).join('')}
                        </tbody>
                    </table>
                </div>
                <div class="flex justify-end">
                    <button type="button" onclick="location.reload()" class="btn-compact-primary py-2 px-6">
                        {{ __('Hoàn tất') }}
                    </button>
                </div>
            `;

            document.getElementById('marcProcessingResults').classList.remove('hidden');
            document.getElementById('marcProcessingResults').scrollIntoView({ behavior: 'smooth' });
        }

        // Cancel
        document.getElementById('marcCancelBtn').addEventListener('click', function() {
            document.getElementById('marcValidationResults').classList.add('hidden');
        });

        // Reset
        document.getElementById('marcResetBtn').addEventListener('click', function() {
            marcForm.reset();
            updateMarcFileState();
            document.getElementById('marcValidationResults').classList.add('hidden');
            document.getElementById('marcProcessingResults').classList.add('hidden');
        });
    });

    // Global helper
    function resetMarcFile() {
        document.getElementById('marc_file').value = '';
        document.getElementById('marcUploadPlaceholder').classList.remove('hidden');
        document.getElementById('marcFileSelectedState').classList.add('hidden');
        document.getElementById('marcDropZone').classList.remove('border-emerald-500/30', 'bg-emerald-500/5');
        document.getElementById('marcDropZone').classList.add('border-border', 'bg-muted/20');
        document.getElementById('marcUploadBtn').disabled = true;
    }
</script>
@endpush