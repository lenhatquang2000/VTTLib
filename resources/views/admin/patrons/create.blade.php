@extends('layouts.admin')

@section('header_css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .select2-container--default .select2-selection--single {
        background-color: #f8fafc;
        border: 1px solid transparent;
        border-radius: 1rem;
        height: 48px;
        display: flex;
        align-items: center;
        transition: all 0.3s;
    }
    .dark .select2-container--default .select2-selection--single {
        background-color: #1e293b;
        border-color: #334155;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: #0f172a;
        font-weight: 700;
        font-size: 0.75rem;
        padding-left: 1.25rem;
    }
    .dark .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: #f1f5f9;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 46px;
        right: 10px;
    }
    .select2-dropdown {
        border-radius: 1rem;
        border: 1px solid #e2e8f0;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }
    .dark .select2-dropdown {
        background-color: #1e293b;
        border-color: #334155;
    }
    .select2-results__option {
        padding: 0.75rem 1.25rem;
        font-size: 0.75rem;
        font-weight: 600;
    }
</style>
@endsection

@section('content')
<div class="space-y-6 pb-12">
    <!-- Header Area -->
    <div class="flex items-center justify-between">
        <div>
            <a href="{{ route('admin.patrons.index') }}" class="text-xs font-bold text-slate-400 hover:text-indigo-600 flex items-center transition-colors mb-2 uppercase tracking-widest">
                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                {{ __('Back to List') }}
            </a>
            <h1 class="text-3xl font-extrabold text-slate-900 dark:text-slate-100 tracking-tight">{{ __('Register New Patron') }}</h1>
        </div>
        <div class="flex items-center space-x-4">
            <button type="button" onclick="autoFillRandom()" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-[10px] font-black uppercase tracking-widest rounded-xl shadow-lg shadow-indigo-500/20 transition-all flex items-center space-x-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                <span>{{ __('Auto Fill') }}</span>
            </button>
            <span class="px-4 py-2 bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 text-[10px] font-black uppercase tracking-widest rounded-xl border border-emerald-100 dark:border-emerald-500/20 shadow-sm">
                {{ __('Tình trạng thẻ') }}: {{ __('Bình thường') }}
            </span>
        </div>
    </div>

    @if ($errors->any())
        <div class="bg-rose-50 border border-rose-100 rounded-2xl p-4 flex items-start space-x-3 shadow-sm">
            <svg class="w-5 h-5 text-rose-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <div class="text-sm text-rose-600 font-medium">
                <ul class="list-disc list-inside">@foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach</ul>
            </div>
        </div>
    @endif

    <form action="{{ route('admin.patrons.store') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        @csrf
        
        <!-- Sidebar: Image & Status Toggles -->
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white dark:bg-slate-900 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-800 p-8 flex flex-col items-center">
                <div class="w-full aspect-square rounded-3xl bg-slate-50 dark:bg-slate-950/50 border-2 border-dashed border-slate-200 dark:border-slate-800 flex items-center justify-center overflow-hidden relative group cursor-pointer mb-6" onclick="document.getElementById('avatar-input').click()">
                    <img id="avatar-preview" src="#" class="hidden w-full h-full object-cover">
                    <div id="avatar-placeholder" class="text-slate-400 dark:text-slate-600 flex flex-col items-center">
                        <svg class="w-12 h-12 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        <span class="text-[10px] font-black uppercase tracking-widest">{{ __('Ảnh đại diện') }}</span>
                    </div>
                </div>
                <input type="file" name="profile_image" id="avatar-input" class="hidden" accept="image/*" onchange="previewAvatar(this)">
                <div class="flex space-x-2 w-full">
                    <button type="button" onclick="document.getElementById('avatar-input').click()" class="flex-1 bg-slate-50 dark:bg-slate-800 text-slate-500 dark:text-slate-400 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors">
                        <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        {{ __('Chọn ảnh') }}
                    </button>
                    <button type="button" onclick="removeAvatar()" class="flex-1 bg-rose-50 dark:bg-rose-500/10 text-rose-500 dark:text-rose-400 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-rose-100 dark:hover:bg-rose-500/20 transition-colors">
                        <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        {{ __('Xoá ảnh') }}
                    </button>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-900 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-800 p-6 space-y-4">
                <label class="flex items-center justify-between group cursor-pointer">
                    <span class="text-[10px] font-black uppercase tracking-widest text-slate-500 dark:text-slate-400">{{ __('Chỉ đăng ký đọc') }}</span>
                    <div class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="is_read_only" value="1" class="sr-only peer">
                        <div class="w-11 h-6 bg-slate-200 dark:bg-slate-800 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                    </div>
                </label>
                <label class="flex items-center justify-between group cursor-pointer">
                    <span class="text-[10px] font-black uppercase tracking-widest text-slate-500 dark:text-slate-400">{{ __('Thẻ chờ in') }}</span>
                    <div class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="is_waiting_for_print" value="1" class="sr-only peer" checked>
                        <div class="w-11 h-6 bg-slate-200 dark:bg-slate-800 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-orange-500"></div>
                    </div>
                </label>
                
                <!-- NEW: Reading Room Only -->
                <label class="flex items-center justify-between group cursor-pointer">
                    <span class="text-[10px] font-black uppercase tracking-widest text-slate-500 dark:text-slate-400">{{ __('Đọc tại chỗ') }}</span>
                    <div class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="is_reading_room_only" value="1" class="sr-only peer">
                        <div class="w-11 h-6 bg-slate-200 dark:bg-slate-800 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-600"></div>
                    </div>
                </label>
                
                <!-- NEW: Add to Print Queue -->
                <label class="flex items-center justify-between group cursor-pointer">
                    <span class="text-[10px] font-black uppercase tracking-widest text-slate-500 dark:text-slate-400">{{ __('Thêm vào danh sách chờ in') }}</span>
                    <div class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="add_to_print_queue" value="1" class="sr-only peer">
                        <div class="w-11 h-6 bg-slate-200 dark:bg-slate-800 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                    </div>
                </label>
            </div>

            <div class="bg-white dark:bg-slate-900 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-800 p-6 space-y-4" x-data="userSearch()">
                <div class="space-y-3">
                    <label class="text-[10px] font-black uppercase tracking-widest text-indigo-500 ml-1">{{ __('Liên kết tài khoản') }}</label>
                    
                    <!-- Search Input -->
                    <div class="relative group">
                        <input type="text" 
                            x-model="query" 
                            @input.debounce.1000ms="search"
                            placeholder="Nhập tên, email hoặc username..."
                            class="w-full bg-slate-50 dark:bg-slate-800 border-2 border-transparent rounded-2xl pl-5 pr-12 py-3.5 text-sm font-bold text-slate-900 dark:text-slate-100 focus:bg-white dark:focus:bg-slate-700 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all"
                            :class="status === 'found' ? 'border-emerald-500/30' : (status === 'not_found' ? 'border-rose-500/30' : '')">
                        
                        <button type="button" @click="search" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-indigo-500 transition-colors">
                            <template x-if="loading">
                                <svg class="animate-spin h-5 w-5" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.062 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            </template>
                            <template x-if="!loading">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            </template>
                        </button>
                    </div>

                    <!-- Hidden ID input for form submission -->
                    <input type="hidden" name="user_id" :value="selectedUser?.id">

                    <!-- Result Display -->
                    <div x-show="status === 'found'" x-cloak class="p-4 bg-emerald-50 dark:bg-emerald-500/10 rounded-2xl border border-emerald-100 dark:border-emerald-500/20 animate-in fade-in slide-in-from-top-2 duration-300">
                        <div class="flex items-center space-x-3">
                            <div class="p-2 bg-emerald-500 rounded-xl text-white">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-emerald-600 dark:text-emerald-400 uppercase tracking-widest leading-none mb-1">Tài khoản hợp lệ</p>
                                <p class="text-sm font-bold text-slate-900 dark:text-slate-100">
                                    <span x-text="selectedUser.name"></span> 
                                    (<span x-text="selectedUser.username" class="text-indigo-600 dark:text-indigo-400"></span>)
                                </p>
                                <p class="text-[11px] text-slate-500 dark:text-slate-400" x-text="selectedUser.email"></p>
                            </div>
                        </div>
                    </div>

                    <div x-show="status === 'not_found'" x-cloak class="p-4 bg-rose-50 dark:bg-rose-500/10 rounded-2xl border border-rose-100 dark:border-rose-500/20 animate-in fade-in slide-in-from-top-2 duration-300">
                        <div class="flex items-center space-x-3">
                            <div class="p-2 bg-rose-500 rounded-xl text-white">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-rose-600 dark:text-rose-400 uppercase tracking-widest leading-none mb-1">Không tìm thấy</p>
                                <p class="text-[11px] text-slate-500 dark:text-slate-400">Vui lòng kiểm tra lại tên hoặc email.</p>
                            </div>
                        </div>
                    </div>

                    <p class="text-[9px] text-slate-400 italic px-1" x-show="status === 'idle'">{{ __('Hệ thống tự động tìm sau 1s ngưng nhập hoặc nhấn icon tìm kiếm') }}</p>
                </div>
            </div>
        </div>

        <!-- Main Form Content -->
        <div class="lg:col-span-3 space-y-8">
            <!-- Part 1: Identity -->
            <div class="bg-white dark:bg-slate-900 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-800 overflow-hidden">
                <div class="px-8 py-5 border-b border-slate-50 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/50">
                    <h2 class="text-xs font-black uppercase tracking-[0.2em] text-slate-800 dark:text-slate-200">{{ __('1. Thông tin định danh') }}</h2>
                </div>
                <div class="p-8 grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-1">{{ __('Mã độc giả') }} <span class="text-rose-500">*</span></label>
                        <div class="relative">
                            <input type="text" name="patron_code" required value="{{ old('patron_code', $nextCode ?? date('ymdHis')) }}"
                                class="w-full bg-slate-50 dark:bg-slate-800 border-transparent rounded-2xl px-5 py-3.5 text-sm font-bold text-slate-900 dark:text-slate-100 focus:bg-white dark:focus:bg-slate-700 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all">
                            @if(isset($nextCode))
                                <span class="absolute right-4 top-1/2 -translate-y-1/2 px-2 py-0.5 bg-indigo-50 dark:bg-indigo-500/10 text-[8px] font-black text-indigo-600 dark:text-indigo-400 uppercase tracking-tighter rounded-md">{{ __('Quy tắc hệ thống') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-1">{{ __('MSSV') }}</label>
                        <input type="text" name="mssv" value="{{ old('mssv') }}" placeholder="Ex: 20210001"
                            class="w-full bg-slate-50 dark:bg-slate-800 border-transparent rounded-2xl px-5 py-3.5 text-sm font-bold text-slate-900 dark:text-slate-100 focus:bg-white dark:focus:bg-slate-700 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-1">{{ __('Số danh bạ') }}</label>
                        <input type="text" name="phone_contact" value="{{ old('phone_contact') }}" placeholder="5339"
                            class="w-full bg-slate-50 dark:bg-slate-800 border-transparent rounded-2xl px-5 py-3.5 text-sm font-bold text-slate-900 dark:text-slate-100 focus:bg-white dark:focus:bg-slate-700 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all">
                    </div>
                    <div class="md:col-span-1 space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-1">{{ __('Loại độc giả') }}</label>
                        <select name="patron_group_id" class="w-full bg-slate-50 dark:bg-slate-800 border-transparent rounded-2xl px-5 py-3.5 text-sm font-bold text-slate-900 dark:text-slate-100 focus:bg-white dark:focus:bg-slate-700 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all appearance-none">
                            @foreach($patronGroups as $group)
                                <option value="{{ $group->id }}">{{ $group->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="md:col-span-1 space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-1">{{ __('Tên đầy đủ') }} <span class="text-rose-500">*</span></label>
                        <input type="text" name="name" required value="{{ old('name') }}" placeholder="NGUYEN VAN A"
                            class="w-full bg-slate-50 dark:bg-slate-800 border-transparent rounded-2xl px-5 py-3.5 text-sm font-bold text-slate-900 dark:text-slate-100 focus:bg-white dark:focus:bg-slate-700 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all">
                    </div>
                    <div class="md:col-span-1 space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-1">{{ __('Tên hiển thị') }} <span class="text-rose-500">*</span></label>
                        <input type="text" name="display_name" required value="{{ old('display_name') }}" placeholder="Van A"
                            class="w-full bg-slate-50 dark:bg-slate-800 border-transparent rounded-2xl px-5 py-3.5 text-sm font-bold text-slate-900 dark:text-slate-100 focus:bg-white dark:focus:bg-slate-700 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all">
                    </div>
                </div>
            </div>

            <!-- Part 2: Personal & Organization -->
            <div class="bg-white dark:bg-slate-900 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-800 overflow-hidden">
                <div class="px-8 py-5 border-b border-slate-50 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/50">
                    <h2 class="text-xs font-black uppercase tracking-[0.2em] text-slate-800 dark:text-slate-200">{{ __('2. Cá nhân & Đơn vị') }}</h2>
                </div>
                <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-1">{{ __('Ngày sinh') }}</label>
                            <input type="date" name="dob" value="{{ old('dob') }}"
                                class="w-full bg-slate-50 dark:bg-slate-800 border-transparent rounded-2xl px-5 py-3 text-sm font-bold text-slate-900 dark:text-slate-100 focus:bg-white dark:focus:bg-slate-700 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-1 block">{{ __('Giới tính') }}</label>
                            <div class="flex items-center space-x-6 h-[46px]">
                                <label class="flex items-center space-x-2 cursor-pointer group">
                                    <input type="radio" name="gender" value="male" class="w-4 h-4 text-indigo-600 border-slate-300 dark:border-slate-700 focus:ring-indigo-500" checked>
                                    <span class="text-sm font-bold text-slate-600 dark:text-slate-400 group-hover:text-slate-900 dark:group-hover:text-slate-200 transition-colors">{{ __('Nam') }}</span>
                                </label>
                                <label class="flex items-center space-x-2 cursor-pointer group">
                                    <input type="radio" name="gender" value="female" class="w-4 h-4 text-indigo-600 border-slate-300 dark:border-slate-700 focus:ring-indigo-500">
                                    <span class="text-sm font-bold text-slate-600 dark:text-slate-400 group-hover:text-slate-900 dark:group-hover:text-slate-200 transition-colors">{{ __('Nữ') }}</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="space-y-2">
                                <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-1">{{ __('Tên trường') }}</label>
                                <input type="text" name="school_name" value="{{ old('school_name') }}"
                                    class="w-full bg-slate-50 dark:bg-slate-800 border-transparent rounded-2xl px-5 py-3.5 text-sm font-bold text-slate-900 dark:text-slate-100 focus:bg-white dark:focus:bg-slate-700 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all">
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-1">{{ __('Khóa') }}</label>
                                <input type="text" name="batch" value="{{ old('batch') }}"
                                    class="w-full bg-slate-50 dark:bg-slate-800 border-transparent rounded-2xl px-5 py-3.5 text-sm font-bold text-slate-900 dark:text-slate-100 focus:bg-white dark:focus:bg-slate-700 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all">
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="space-y-2">
                                <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-1">{{ __('Bộ phận') }}</label>
                                <input type="text" name="department" value="{{ old('department') }}"
                                    class="w-full bg-slate-50 dark:bg-slate-800 border-transparent rounded-2xl px-5 py-3.5 text-sm font-bold text-slate-900 dark:text-slate-100 focus:bg-white dark:focus:bg-slate-700 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all">
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-1">{{ __('Chức vụ/Lớp') }}</label>
                                <input type="text" name="position_class" value="{{ old('position_class') }}"
                                    class="w-full bg-slate-50 dark:bg-slate-800 border-transparent rounded-2xl px-5 py-3.5 text-sm font-bold text-slate-900 dark:text-slate-100 focus:bg-white dark:focus:bg-slate-700 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Part 3: Contact & Auth -->
            <div class="bg-white dark:bg-slate-900 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-800 overflow-hidden">
                <div class="px-8 py-5 border-b border-slate-50 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/50">
                    <h2 class="text-xs font-black uppercase tracking-[0.2em] text-slate-800 dark:text-slate-200">{{ __('3. Liên lạc & Tài khoản') }}</h2>
                </div>
                <div class="p-8 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-1">{{ __('Số điện thoại') }}</label>
                            <input type="text" name="phone" value="{{ old('phone') }}"
                                class="w-full bg-slate-50 dark:bg-slate-800 border-transparent rounded-2xl px-5 py-3.5 text-sm font-bold text-slate-900 dark:text-slate-100 focus:bg-white dark:focus:bg-slate-700 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-1">{{ __('Fax') }}</label>
                            <input type="text" name="fax" value="{{ old('fax') }}"
                                class="w-full bg-slate-50 dark:bg-slate-800 border-transparent rounded-2xl px-5 py-3.5 text-sm font-bold text-slate-900 dark:text-slate-100 focus:bg-white dark:focus:bg-slate-700 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-1">{{ __('Email') }}</label>
                            <input type="email" name="email" value="{{ old('email') }}"
                                class="w-full bg-slate-50 dark:bg-slate-800 border-transparent rounded-2xl px-5 py-3.5 text-sm font-bold text-slate-900 dark:text-slate-100 focus:bg-white dark:focus:bg-slate-700 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-1">{{ __('Mật khẩu OPAC') }}</label>
                            <input type="password" name="password" placeholder="••••••••"
                                class="w-full bg-slate-50 dark:bg-slate-800 border-transparent rounded-2xl px-5 py-3.5 text-sm font-bold text-slate-900 dark:text-slate-100 focus:bg-white dark:focus:bg-slate-700 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 outline-none transition-all">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-1">{{ __('Xác nhận mật khẩu') }}</label>
                            <input type="password" name="password_confirmation" placeholder="••••••••"
                                class="w-full bg-slate-50 dark:bg-slate-800 border-transparent rounded-2xl px-5 py-3.5 text-sm font-bold text-slate-900 dark:text-slate-100 focus:bg-white dark:focus:bg-slate-700 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 outline-none transition-all">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4 border-t border-slate-50 dark:border-slate-800">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-1">{{ __('Chi nhánh') }}</label>
                            <select name="branch" class="w-full bg-slate-50 dark:bg-slate-800 border-transparent rounded-2xl px-5 py-3.5 text-sm font-bold text-slate-900 dark:text-slate-100 focus:bg-white dark:focus:bg-slate-700 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all appearance-none">
                                <option value="all">{{ __('Tất cả chi nhánh') }}</option>
                                @foreach($branches as $b)
                                    <option value="{{ $b->id }}">{{ $b->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-1">{{ __('Phân loại') }}</label>
                            <select name="classification_type" class="w-full bg-slate-50 dark:bg-slate-800 border-transparent rounded-2xl px-5 py-3.5 text-sm font-bold text-slate-900 dark:text-slate-100 focus:bg-white dark:focus:bg-slate-700 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all appearance-none">
                                <option value="individual">{{ __('Cá nhân') }}</option>
                                <option value="group">{{ __('Tổ chức') }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="space-y-4 pt-4 border-t border-slate-50 dark:border-slate-800">
                        <div class="flex items-center justify-between">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-1">{{ __('Danh sách địa chỉ') }}</label>
                            <button type="button" onclick="addAddressField()" class="text-[10px] font-black uppercase tracking-widest text-indigo-600 hover:text-indigo-700">
                                + {{ __('Thêm địa chỉ') }}
                            </button>
                        </div>
                        <div id="address-list" class="space-y-3">
                            <div class="relative group">
                                <input type="text" name="addresses[]" placeholder="{{ __('Địa chỉ chính...') }}"
                                    class="w-full bg-slate-50 dark:bg-slate-800 border-transparent rounded-2xl px-5 py-3.5 text-sm font-bold text-slate-900 dark:text-slate-100 focus:bg-white dark:focus:bg-slate-700 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all">
                                <span class="absolute right-4 top-1/2 -translate-y-1/2 text-[8px] font-black text-emerald-500 uppercase tracking-tighter bg-emerald-50 dark:bg-emerald-500/10 px-2 py-1 rounded-md">{{ __('Mặc định') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Part 4: Financial & System Dates -->
            <div class="bg-white dark:bg-slate-900 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-800 overflow-hidden">
                <div class="px-8 py-5 border-b border-slate-50 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/50">
                    <h2 class="text-xs font-black uppercase tracking-[0.2em] text-slate-800 dark:text-slate-200">{{ __('4. Tài chính & Hệ thống') }}</h2>
                </div>
                <div class="p-8 space-y-8">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-1">{{ __('Lệ phí làm thẻ') }}</label>
                            <input type="number" name="card_fee" value="0"
                                class="w-full bg-slate-50 dark:bg-slate-800 border-transparent rounded-2xl px-5 py-3.5 text-sm font-bold text-slate-900 dark:text-slate-100 focus:bg-white dark:focus:bg-slate-700 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-1">{{ __('Tiền thế chân') }}</label>
                            <input type="number" name="deposit" value="0"
                                class="w-full bg-slate-50 dark:bg-slate-800 border-transparent rounded-2xl px-5 py-3.5 text-sm font-bold text-slate-900 dark:text-slate-100 focus:bg-white dark:focus:bg-slate-700 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-1">{{ __('Số dư tài khoản') }}</label>
                            <input type="number" name="balance" value="0"
                                class="w-full bg-slate-50 dark:bg-slate-800 border-transparent rounded-2xl px-5 py-3.5 text-sm font-bold text-slate-900 dark:text-slate-100 focus:bg-white dark:focus:bg-slate-700 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pt-6 border-t border-slate-50 dark:border-slate-800">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-1">{{ __('Ngày cập nhật') }}</label>
                            <input type="date" value="{{ date('Y-m-d') }}" disabled
                                class="w-full bg-slate-100 dark:bg-slate-800 border-transparent rounded-2xl px-5 py-3 text-sm font-bold text-slate-500 dark:text-slate-500 cursor-not-allowed">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-1">{{ __('Ngày đăng ký') }} <span class="text-rose-500">*</span></label>
                            <input type="date" name="registration_date" required value="{{ date('Y-m-d') }}"
                                class="w-full bg-slate-50 dark:bg-slate-800 border-transparent rounded-2xl px-5 py-3 text-sm font-bold text-slate-900 dark:text-slate-100 focus:bg-white dark:focus:bg-slate-700 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-1">{{ __('Ngày hết hạn') }} <span class="text-rose-500">*</span></label>
                            <input type="date" name="expiry_date" required value="{{ date('Y-m-d', strtotime('+1 year')) }}"
                                class="w-full bg-slate-50 dark:bg-slate-800 border-transparent rounded-2xl px-5 py-3 text-sm font-bold text-slate-900 dark:text-slate-100 focus:bg-white dark:focus:bg-slate-700 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all">
                        </div>
                    </div>

                    <div class="space-y-2 pt-6 border-t border-slate-50 dark:border-slate-800">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-1">{{ __('Ghi chú') }}</label>
                        <textarea name="notes" rows="3" placeholder="{{ __('Nhập ghi chú thêm về độc giả...') }}"
                            class="w-full bg-slate-50 dark:bg-slate-800 border-transparent rounded-2xl px-5 py-3.5 text-sm font-bold text-slate-900 dark:text-slate-100 focus:bg-white dark:focus:bg-slate-700 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all"></textarea>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-1">{{ __('Tập tin đính kèm') }}</label>
                        <div class="flex items-center justify-center w-full">
                            <label class="flex flex-col items-center justify-center w-full h-32 border-2 border-slate-200 dark:border-slate-800 border-dashed rounded-3xl cursor-pointer bg-slate-50 dark:bg-slate-800/50 hover:bg-slate-100 dark:hover:bg-slate-800 transition-all">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                    <svg class="w-8 h-8 mb-3 text-slate-400 dark:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                                    <p class="mb-2 text-xs text-slate-500 dark:text-slate-400 font-bold uppercase tracking-widest">{{ __('Chọn file đính kèm') }}</p>
                                </div>
                                <input name="attachments" type="file" class="hidden" />
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <input type="hidden" name="card_status" value="normal">

            <!-- Submit Button -->
            <button type="submit" class="group w-full relative overflow-hidden bg-slate-900 dark:bg-indigo-600 text-white rounded-3xl py-6 shadow-2xl transition-all hover:shadow-indigo-500/25 active:scale-[0.98]">
                <div class="absolute inset-0 bg-gradient-to-r from-indigo-600 to-violet-600 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                <div class="relative flex items-center justify-center space-x-3">
                    <span class="text-sm font-black uppercase tracking-[0.3em] ml-2">{{ __('Initialize Identity') }}</span>
                    <svg class="w-5 h-5 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </div>
            </button>
        </div>
    </form>
</div>

<script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<script>
    function userSearch() {
        return {
            query: '',
            loading: false,
            status: 'idle', // idle, found, not_found
            selectedUser: null,

            async search() {
                if (this.query.trim().length < 2) {
                    this.status = 'idle';
                    this.selectedUser = null;
                    return;
                }

                this.loading = true;
                try {
                    const response = await fetch(`{{ route('admin.patrons.search-users') }}?q=${encodeURIComponent(this.query)}`);
                    const data = await response.json();

                    if (data && data.length > 0) {
                        // Lấy kết quả đầu tiên (giả định khớp tốt nhất)
                        this.selectedUser = data[0];
                        this.status = 'found';
                        this.autoFill(this.selectedUser);
                    } else {
                        this.selectedUser = null;
                        this.status = 'not_found';
                    }
                } catch (error) {
                    console.error('Search error:', error);
                    this.status = 'not_found';
                } finally {
                    this.loading = false;
                }
            },

            autoFill(user) {
                if (user) {
                    document.getElementsByName('name')[0].value = user.name;
                    document.getElementsByName('display_name')[0].value = user.name.split(' ').pop();
                    document.getElementsByName('email')[0].value = user.email;
                    
                    // Hiện Toast thông báo
                    if (window.Toast) {
                        window.Toast.fire({ icon: 'success', title: 'Đã liên kết với: ' + user.name });
                    }
                }
            }
        }
    }

    function previewAvatar(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('avatar-preview').src = e.target.result;
                document.getElementById('avatar-preview').classList.remove('hidden');
                document.getElementById('avatar-placeholder').classList.add('hidden');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
    
    function removeAvatar() {
        document.getElementById('avatar-preview').src = '#';
        document.getElementById('avatar-preview').classList.add('hidden');
        document.getElementById('avatar-placeholder').classList.remove('hidden');
        document.getElementById('avatar-input').value = '';
    }

    function addAddressField() {
        const container = document.getElementById('address-list');
        const div = document.createElement('div');
        div.className = 'relative group flex items-center space-x-2 animate-in fade-in slide-in-from-top-2 duration-300';
        div.innerHTML = `
            <input type="text" name="addresses[]" placeholder="{{ __('Địa chỉ bổ sung...') }}"
                class="w-full bg-slate-50 border-transparent rounded-2xl px-5 py-3.5 text-sm font-bold text-slate-900 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all">
            <button type="button" onclick="this.parentElement.remove()" class="p-2 text-slate-300 hover:text-rose-500 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
            </button>
        `;
        container.appendChild(div);
    }

    function autoFillRandom() {
        const firstNames = ['Nguyễn', 'Trần', 'Lê', 'Phạm', 'Hoàng', 'Phan', 'Vũ', 'Đặng', 'Bùi', 'Đỗ'];
        const middleNames = ['Văn', 'Thị', 'Minh', 'Anh', 'Đức', 'Thanh', 'Hữu', 'Quốc', 'Ngọc', 'Kim'];
        const lastNames = ['An', 'Bình', 'Chi', 'Dũng', 'Em', 'Giang', 'Hương', 'Khánh', 'Linh', 'Minh', 'Nam', 'Oanh', 'Phúc', 'Quang', 'Sơn', 'Tâm', 'Uyên', 'Việt', 'Xuân', 'Yên'];
        
        const randomName = `${firstNames[Math.floor(Math.random() * firstNames.length)]} ${middleNames[Math.floor(Math.random() * middleNames.length)]} ${lastNames[Math.floor(Math.random() * lastNames.length)]}`;
        const randomSuffix = Math.floor(Math.random() * 10000); // Tăng độ dài suffix để tránh trùng email
        const email = randomName.toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g, "").replace(/\s+/g, '.') + randomSuffix + '@example.com';
        
        document.getElementsByName('name')[0].value = randomName;
        document.getElementsByName('display_name')[0].value = randomName.split(' ').pop();
        document.getElementsByName('email')[0].value = email;
        
        // Fix: Đảm bảo mật khẩu và xác nhận mật khẩu khớp nhau
        const randomPass = 'Password123@' + Math.floor(Math.random() * 100);
        const passFields = document.getElementsByName('password');
        const confirmFields = document.getElementsByName('password_confirmation');
        
        if (passFields.length > 0) passFields[0].value = randomPass;
        if (confirmFields.length > 0) confirmFields[0].value = randomPass;

        document.getElementsByName('mssv')[0].value = '2026' + Math.floor(Math.random() * 10000).toString().padStart(4, '0');
        document.getElementsByName('phone')[0].value = '09' + Math.floor(Math.random() * 100000000).toString().padStart(8, '0');
        document.getElementsByName('phone_contact')[0].value = Math.floor(Math.random() * 10000).toString().padStart(4, '0');
        
        // Random school and department
        const schools = ['Đại học Cần Thơ', 'Đại học Bách Khoa', 'Đại học Võ Trường Toản', 'Đại học Y Dược'];
        const depts = ['Công nghệ thông tin', 'Quản trị kinh doanh', 'Y đa khoa', 'Dược học'];
        
        document.getElementsByName('school_name')[0].value = schools[Math.floor(Math.random() * schools.length)];
        document.getElementsByName('department')[0].value = depts[Math.floor(Math.random() * depts.length)];
        document.getElementsByName('batch')[0].value = 'K' + (Math.floor(Math.random() * 5) + 20);
        
        if (window.Toast) {
            window.Toast.fire({ icon: 'info', title: 'Đã tự động điền dữ liệu mẫu' });
        }
    }
</script>
@endsection
