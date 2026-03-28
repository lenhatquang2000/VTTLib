@extends('layouts.admin')

@section('title', __('Import Preview - Map Columns'))

@section('content')
<div class="space-y-6 pb-12">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <a href="{{ route('admin.patrons.import.index') }}" class="text-xs font-bold text-slate-400 hover:text-indigo-600 flex items-center transition-colors mb-2 uppercase tracking-widest">
                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                {{ __('Back to Import') }}
            </a>
            <h1 class="text-3xl font-extrabold text-slate-900 dark:text-slate-100 tracking-tight">{{ __('Import Preview & Column Mapping') }}</h1>
            <p class="text-slate-500 dark:text-slate-400 text-sm font-medium mt-1">{{ __('Map Excel columns to database fields and review data before importing.') }}</p>
        </div>
    </div>

    <!-- Data Summary -->
    <div class="bg-white dark:bg-slate-900 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-800 p-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 bg-emerald-50 dark:bg-emerald-500/10 rounded-2xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">{{ __('Data Summary') }}</h3>
                    <p class="text-sm text-slate-600 dark:text-slate-400">{{ count($data) }} rows found in Excel file</p>
                </div>
            </div>
            <div class="text-right">
                <div class="text-2xl font-bold text-emerald-600 dark:text-emerald-400">{{ count($data) }}</div>
                <div class="text-xs text-slate-500 dark:text-slate-400 uppercase tracking-widest">{{ __('Total Rows') }}</div>
            </div>
        </div>
    </div>

    <!-- Column Mapping -->
    <div class="bg-white dark:bg-slate-900 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-800 overflow-hidden">
        <div class="px-8 py-5 border-b border-slate-50 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/50">
            <h2 class="text-xs font-black uppercase tracking-[0.2em] text-slate-800 dark:text-slate-200">{{ __('Map Excel Columns to Database Fields') }}</h2>
        </div>
        
        <form action="{{ route('admin.patrons.import.process') }}" method="POST" class="p-8">
            @csrf
            <input type="hidden" name="file_path" value="{{ $filePath }}">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                @foreach($columns as $column)
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">
                            {{ __('Excel Column') }}: <span class="text-indigo-600 dark:text-indigo-400">{{ $column }}</span>
                        </label>
                        <select name="column_mapping[{{ $column }}]" class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            <option value="">{{ __('-- Skip this column --') }}</option>
                            <option value="patron_code" 
                                @if(isset($autoMapping[$column]) && $autoMapping[$column] == 'patron_code') selected @endif
                                @if(str_contains(strtolower($column), 'mã') || str_contains(strtolower($column), 'ma') || str_contains(strtolower($column), 'code')) selected @endif>
                                {{ __('Patron Code') }} *
                            </option>
                            <option value="name" 
                                @if(isset($autoMapping[$column]) && $autoMapping[$column] == 'name') selected @endif
                                @if(str_contains(strtolower($column), 'họ') || str_contains(strtolower($column), 'ho') || str_contains(strtolower($column), 'tên') || str_contains(strtolower($column), 'ten')) selected @endif>
                                {{ __('Full Name') }} *
                            </option>
                            <option value="display_name" 
                                @if(isset($autoMapping[$column]) && $autoMapping[$column] == 'display_name') selected @endif
                                @if(str_contains(strtolower($column), 'hiển thị') || str_contains(strtolower($column), 'hien_thi')) selected @endif>
                                {{ __('Display Name') }}
                            </option>
                            <option value="email" 
                                @if(isset($autoMapping[$column]) && $autoMapping[$column] == 'email') selected @endif
                                @if(str_contains(strtolower($column), 'email')) selected @endif>
                                {{ __('Email') }} *
                            </option>
                            <option value="phone" 
                                @if(isset($autoMapping[$column]) && $autoMapping[$column] == 'phone') selected @endif
                                @if(str_contains(strtolower($column), 'điện thoại') || str_contains(strtolower($column), 'dien_thoai') || str_contains(strtolower($column), 'phone')) selected @endif>
                                {{ __('Phone') }}
                            </option>
                            <option value="mssv" 
                                @if(isset($autoMapping[$column]) && $autoMapping[$column] == 'mssv') selected @endif
                                @if(str_contains(strtolower($column), 'mssv') || str_contains(strtolower($column), 'student')) selected @endif>
                                {{ __('Student ID') }}
                            </option>
                            <option value="id_card" 
                                @if(isset($autoMapping[$column]) && $autoMapping[$column] == 'id_card') selected @endif
                                @if(str_contains(strtolower($column), 'cmnd') || str_contains(strtolower($column), 'cccd') || str_contains(strtolower($column), 'id')) selected @endif>
                                {{ __('ID Card') }}
                            </option>
                            <option value="school_name" 
                                @if(isset($autoMapping[$column]) && $autoMapping[$column] == 'school_name') selected @endif
                                @if(str_contains(strtolower($column), 'trường') || str_contains(strtolower($column), 'truong') || str_contains(strtolower($column), 'school')) selected @endif>
                                {{ __('School') }}
                            </option>
                            <option value="department" 
                                @if(isset($autoMapping[$column]) && $autoMapping[$column] == 'department') selected @endif
                                @if(str_contains(strtolower($column), 'bộ phận') || str_contains(strtolower($column), 'bo_phan') || str_contains(strtolower($column), 'lớp') || str_contains(strtolower($column), 'lop')) selected @endif>
                                {{ __('Department/Class') }}
                            </option>
                            <option value="batch" 
                                @if(isset($autoMapping[$column]) && $autoMapping[$column] == 'batch') selected @endif
                                @if(str_contains(strtolower($column), 'khóa') || str_contains(strtolower($column), 'khoa') || str_contains(strtolower($column), 'batch')) selected @endif>
                                {{ __('Batch/Course') }}
                            </option>
                            <option value="dob" 
                                @if(isset($autoMapping[$column]) && $autoMapping[$column] == 'dob') selected @endif
                                @if(str_contains(strtolower($column), 'sinh') || str_contains(strtolower($column), 'ngay sinh') || str_contains(strtolower($column), 'dob')) selected @endif>
                                {{ __('Date of Birth') }}
                            </option>
                            <option value="gender" 
                                @if(isset($autoMapping[$column]) && $autoMapping[$column] == 'gender') selected @endif
                                @if(str_contains(strtolower($column), 'giới tính') || str_contains(strtolower($column), 'gioi_tinh') || str_contains(strtolower($column), 'gender')) selected @endif>
                                {{ __('Gender') }}
                            </option>
                            <option value="address" 
                                @if(isset($autoMapping[$column]) && $autoMapping[$column] == 'address') selected @endif
                                @if(str_contains(strtolower($column), 'địa chỉ') || str_contains(strtolower($column), 'dia_chi') || str_contains(strtolower($column), 'address')) selected @endif>
                                {{ __('Address') }}
                            </option>
                            <option value="notes" 
                                @if(isset($autoMapping[$column]) && $autoMapping[$column] == 'notes') selected @endif
                                @if(str_contains(strtolower($column), 'ghi chú') || str_contains(strtolower($column), 'ghi_chu') || str_contains(strtolower($column), 'note')) selected @endif>
                                {{ __('Notes') }}
                            </option>
                        </select>
                    </div>
                @endforeach
            </div>

            <!-- Expiry Date Setting -->
            <div class="border-t border-slate-200 dark:border-slate-700 pt-6 mb-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">
                            {{ __('Default Expiry Date for All Patrons') }}
                        </label>
                        <input type="date" name="expiry_date" value="{{ \Carbon\Carbon::now()->addYear()->format('Y-m-d') }}" 
                               class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        <p class="text-xs text-slate-500 dark:text-slate-400">{{ __('This date will be set for all imported patrons') }}</p>
                    </div>
                </div>
            </div>

            <!-- Image Upload Section -->
            <div class="border-t border-slate-200 dark:border-slate-700 pt-6 mb-8">
                <h3 class="text-sm font-semibold text-slate-700 dark:text-slate-300 mb-4">{{ __('Optional: Upload Patron Images') }}</h3>
                <div class="bg-slate-50 dark:bg-slate-800 rounded-xl p-6">
                    <div class="space-y-4">
                        <div>
                            <label class="text-sm text-slate-600 dark:text-slate-400 block mb-2">
                                {{ __('Upload ZIP file containing patron images') }}
                            </label>
                            <input type="file" name="images_zip" accept=".zip" class="w-full bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-xl px-4 py-2.5 text-sm">
                        </div>
                        <p class="text-xs text-slate-500 dark:text-slate-400">
                            {{ __('Image filenames must match patron codes (e.g., PAT001.jpg for patron PAT001)') }}
                        </p>
                        <div id="image-upload-result" class="hidden"></div>
                    </div>
                </div>
            </div>

            <!-- Data Preview -->
            <div class="border-t border-slate-200 dark:border-slate-700 pt-6 mb-8">
                <h3 class="text-sm font-semibold text-slate-700 dark:text-slate-300 mb-4">{{ __('Data Preview (First 10 rows)') }}</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-slate-50 dark:bg-slate-800">
                            <tr>
                                @foreach(array_slice($columns, 0, 8) as $column)
                                    <th class="px-4 py-3 text-left text-xs font-medium text-slate-600 dark:text-slate-400 uppercase tracking-wider">
                                        {{ $column }}
                                    </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                            @foreach(array_slice($data, 0, 10) as $row)
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-800">
                                    @foreach(array_slice($columns, 0, 8) as $column)
                                        <td class="px-4 py-3 text-slate-900 dark:text-slate-100">
                                            {{ $row[$column] ?? '-' }}
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @if(count($data) > 10)
                        <div class="text-center py-3 text-xs text-slate-500 dark:text-slate-400">
                            {{ __('Showing first 10 of :count rows', ['count' => count($data)]) }}
                        </div>
                    @endif
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-between">
                <a href="{{ route('admin.patrons.import.index') }}" class="px-6 py-3 border border-slate-300 dark:border-slate-600 rounded-xl text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                    {{ __('Cancel') }}
                </a>
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-8 py-3 rounded-xl font-semibold transition-colors flex items-center space-x-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                    <span>{{ __('Import Patrons') }}</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Handle image upload
    const imageInput = document.querySelector('input[name="images_zip"]');
    const resultDiv = document.getElementById('image-upload-result');

    imageInput.addEventListener('change', function(e) {
        if (e.target.files.length > 0) {
            const formData = new FormData();
            formData.append('images_zip', e.target.files[0]);

            fetch('{{ route("admin.patrons.import.images") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    resultDiv.innerHTML = `
                        <div class="bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-500/20 rounded-lg p-3">
                            <div class="flex items-center space-x-2">
                                <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                <span class="text-sm text-emerald-800 dark:text-emerald-200">${data.count} images found</span>
                            </div>
                        </div>
                    `;
                    resultDiv.classList.remove('hidden');
                } else {
                    resultDiv.innerHTML = `
                        <div class="bg-rose-50 dark:bg-rose-500/10 border border-rose-200 dark:border-rose-500/20 rounded-lg p-3">
                            <div class="flex items-center space-x-2">
                                <svg class="w-4 h-4 text-rose-600 dark:text-rose-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>
                                <span class="text-sm text-rose-800 dark:text-rose-200">Error: ${data.error}</span>
                            </div>
                        </div>
                    `;
                    resultDiv.classList.remove('hidden');
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }
    });
</script>
@endsection
