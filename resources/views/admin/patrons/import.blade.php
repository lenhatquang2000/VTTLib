@extends('layouts.admin')

@section('title', __('Import Patrons from Excel'))

@section('content')
<div class="space-y-6 pb-12">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <a href="{{ route('admin.patrons.index') }}" class="text-xs font-bold text-slate-400 hover:text-indigo-600 flex items-center transition-colors mb-2 uppercase tracking-widest">
                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                {{ __('Back to Patron Management') }}
            </a>
            <h1 class="text-3xl font-extrabold text-slate-900 dark:text-slate-100 tracking-tight">{{ __('Import Patrons from Excel') }}</h1>
            <p class="text-slate-500 dark:text-slate-400 text-sm font-medium mt-1">{{ __('Bulk import multiple patrons at once using Excel file.') }}</p>
        </div>
    </div>

    @if(session('error'))
        <div class="bg-rose-50 border border-rose-100 rounded-2xl p-4 flex items-start space-x-3 shadow-sm">
            <svg class="w-5 h-5 text-rose-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <div class="text-sm text-rose-600 font-medium">{{ session('error') }}</div>
        </div>
    @endif

    @if(session('failures'))
        <div class="bg-amber-50 border border-amber-100 rounded-2xl p-4 shadow-sm">
            <h3 class="text-sm font-semibold text-amber-800 mb-2">{{ __('Import Validation Errors') }}</h3>
            <div class="space-y-1">
                @foreach(session('failures') as $failure)
                    <div class="text-xs text-amber-700 font-mono bg-amber-100/50 p-2 rounded">
                        @if(is_object($failure))
                            <strong>Row {{ $failure->row() }}:</strong> {{ implode(', ', $failure->errors()) }}
                        @else
                            <strong>Row {{ $failure['row'] }}:</strong> {{ implode(', ', (array)$failure['errors']) }}
                        @endif
                    </div>
                @endforeach
            </div>
            @if(session('error_details'))
                <div class="mt-3 pt-3 border-t border-amber-200">
                    <h4 class="text-xs font-semibold text-amber-800 mb-1">{{ __('Detailed Error Log:') }}</h4>
                    <div class="text-xs text-amber-600 font-mono bg-amber-50 p-2 rounded max-h-32 overflow-y-auto">
                        @foreach(session('error_details') as $detail)
                            <div>{{ $detail }}</div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    @endif

    <!-- Step 1: Download Template -->
    <div class="bg-white dark:bg-slate-900 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-800 overflow-hidden">
        <div class="px-8 py-5 border-b border-slate-50 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/50">
            <div class="flex items-center space-x-3">
                <div class="w-8 h-8 bg-indigo-100 dark:bg-indigo-500/20 rounded-full flex items-center justify-center">
                    <span class="text-sm font-black text-indigo-600 dark:text-indigo-400">1</span>
                </div>
                <h2 class="text-xs font-black uppercase tracking-[0.2em] text-slate-800 dark:text-slate-200">{{ __('Download Template') }}</h2>
            </div>
        </div>
        <div class="p-8">
            <div class="flex items-start space-x-4">
                <div class="w-12 h-12 bg-emerald-50 dark:bg-emerald-500/10 rounded-2xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v1a3 3 0 003 3h0a3 3 0 003-3v-1m-6 0h6M9 11V9a3 3 0 016 0v2m-6 0h6m-6 0l3 3m3-3l-3 3"></path></svg>
                </div>
                <div class="flex-1">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-2">{{ __('Download Excel Template') }}</h3>
                    <p class="text-sm text-slate-600 dark:text-slate-400 mb-4">{{ __('Get the pre-formatted Excel template with all required columns and sample data.') }}</p>
                    <a href="{{ route('admin.patrons.import.template') }}" class="inline-flex items-center space-x-2 bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-2.5 rounded-xl transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        <span class="text-sm font-semibold">{{ __('Download Template') }}</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Step 2: Upload Excel -->
    <div class="bg-white dark:bg-slate-900 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-800 overflow-hidden">
        <div class="px-8 py-5 border-b border-slate-50 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/50">
            <div class="flex items-center space-x-3">
                <div class="w-8 h-8 bg-indigo-100 dark:bg-indigo-500/20 rounded-full flex items-center justify-center">
                    <span class="text-sm font-black text-indigo-600 dark:text-indigo-400">2</span>
                </div>
                <h2 class="text-xs font-black uppercase tracking-[0.2em] text-slate-800 dark:text-slate-200">{{ __('Upload Excel File') }}</h2>
            </div>
        </div>
        <div class="p-8">
            <form action="{{ route('admin.patrons.import.upload') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                
                <div class="border-2 border-dashed border-slate-200 dark:border-slate-700 rounded-3xl p-8 text-center hover:border-indigo-300 dark:hover:border-indigo-600 transition-colors">
                    <div class="w-16 h-16 bg-indigo-50 dark:bg-indigo-500/10 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                    </div>
                    <div class="mb-4">
                        <label for="excel_file" class="cursor-pointer">
                            <span class="text-lg font-semibold text-slate-900 dark:text-slate-100">{{ __('Choose Excel file') }}</span>
                            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">{{ __('or drag and drop your Excel file here') }}</p>
                        </label>
                        <input id="excel_file" name="excel_file" type="file" accept=".xlsx,.xls" class="hidden" required>
                    </div>
                    <div class="text-xs text-slate-400 dark:text-slate-500">
                        <p>{{ __('Supported formats: .xlsx, .xls (Max 10MB)') }}</p>
                    </div>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-8 py-3 rounded-xl font-semibold transition-colors flex items-center space-x-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                        <span>{{ __('Upload & Preview') }}</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Instructions -->
    <div class="bg-blue-50 dark:bg-blue-500/10 border border-blue-100 dark:border-blue-500/20 rounded-3xl p-6">
        <div class="flex items-start space-x-3">
            <div class="w-6 h-6 bg-blue-100 dark:bg-blue-500/20 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                <svg class="w-3 h-3 text-blue-600 dark:text-blue-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
            </div>
            <div class="flex-1">
                <h3 class="text-sm font-semibold text-blue-900 dark:text-blue-100 mb-2">{{ __('Import Instructions') }}</h3>
                <ol class="text-sm text-blue-800 dark:text-blue-200 space-y-1 list-decimal list-inside">
                    <li>{{ __('Download the Excel template and fill in patron information') }}</li>
                    <li>{{ __('Required fields: Patron Code, Name, Email') }}</li>
                    <li>{{ __('Optional fields: Phone, MSSV, ID Card, School, Department, etc.') }}</li>
                    <li>{{ __('Upload the filled Excel file to preview data') }}</li>
                    <li>{{ __('Map Excel columns to database fields') }}</li>
                    <li>{{ __('Set expiry date for all imported patrons') }}</li>
                    <li>{{ __('Optionally upload ZIP file containing patron images') }}</li>
                    <li>{{ __('Review and confirm import') }}</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<script>
    // File upload handling
    const dropZone = document.querySelector('.border-dashed');
    const fileInput = document.getElementById('excel_file');

    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, preventDefaults, false);
    });

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    ['dragenter', 'dragover'].forEach(eventName => {
        dropZone.addEventListener(eventName, highlight, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, unhighlight, false);
    });

    function highlight(e) {
        dropZone.classList.add('border-indigo-300', 'dark:border-indigo-600', 'bg-indigo-50', 'dark:bg-indigo-500/5');
    }

    function unhighlight(e) {
        dropZone.classList.remove('border-indigo-300', 'dark:border-indigo-600', 'bg-indigo-50', 'dark:bg-indigo-500/5');
    }

    dropZone.addEventListener('drop', handleDrop, false);

    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;
        
        if (files.length > 0) {
            fileInput.files = files;
            updateFileName(files[0].name);
        }
    }

    fileInput.addEventListener('change', function(e) {
        if (e.target.files.length > 0) {
            updateFileName(e.target.files[0].name);
        }
    });

    function updateFileName(fileName) {
        const label = dropZone.querySelector('span.text-lg');
        label.textContent = fileName;
    }
</script>
@endsection
