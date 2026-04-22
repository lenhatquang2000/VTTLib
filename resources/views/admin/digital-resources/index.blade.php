@extends('layouts.admin')

@section('content')
<div class="space-y-6 animate-fade-in">
    <!-- Sophisticated Header -->
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6 bg-slate-900/50 p-6 rounded-[2rem] border border-slate-800 shadow-xl backdrop-blur-md">
        <div class="flex items-center gap-6">
            <div class="relative">
                <div class="absolute inset-0 bg-blue-500 blur-xl opacity-20"></div>
                <div class="relative w-20 h-20 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-3xl flex items-center justify-center text-white shadow-2xl shadow-blue-500/20">
                    <i class="fas fa-folder-open text-3xl"></i>
                </div>
            </div>
            <div>
                <div class="flex items-center gap-2 text-[10px] font-black text-gray-500 uppercase tracking-[0.2em] mb-1.5">
                    <a href="{{ route('admin.digital-folders.index') }}" class="hover:text-blue-400 transition-colors">Repository</a>
                    <i class="fas fa-chevron-right text-[8px] opacity-30"></i>
                    <span class="text-blue-400">{{ $folder->folder_name }}</span>
                </div>
                <h1 class="text-3xl font-black text-white tracking-tight">{{ __('Danh sách Tài liệu') }}</h1>
            </div>
        </div>
        
        <div class="flex flex-wrap gap-3 w-full lg:w-auto">
            <a href="{{ route('admin.digital-resources.create', ['folder_id' => $folder->id]) }}" 
               class="flex-1 lg:flex-none px-8 py-4 bg-blue-600 hover:bg-blue-500 text-white font-black rounded-2xl shadow-xl shadow-blue-500/25 transition-all hover:-translate-y-1 flex items-center justify-center group">
                <i class="fas fa-plus-circle mr-3 text-lg group-hover:rotate-90 transition-transform"></i>
                {{ __('Thêm Tài Liệu') }}
            </a>
            <a href="{{ route('admin.digital-folders.index') }}" 
               class="flex-1 lg:flex-none px-8 py-4 bg-slate-800 hover:bg-slate-700 text-white font-bold rounded-2xl transition-all flex items-center justify-center border border-slate-700">
                <i class="fas fa-arrow-left mr-3 opacity-60"></i>
                {{ __('Quay Lại') }}
            </a>
        </div>
    </div>

    <!-- Smart Table Container -->
    <div class="card-admin overflow-hidden border border-slate-800 shadow-2xl rounded-[2.5rem] bg-slate-900/40 backdrop-blur-md">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-800/50 border-b border-slate-700">
                        <th class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-gray-500">{{ __('Tài Liệu & Thông Tin Tệp') }}</th>
                        <th class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-gray-500">{{ __('Loại / Ngôn Ngữ') }}</th>
                        <th class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-gray-500">{{ __('Tác Giả') }}</th>
                        <th class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-gray-500">{{ __('Trạng Thái') }}</th>
                        <th class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-gray-500">{{ __('Khai Thác') }}</th>
                        <th class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-gray-500 text-right">{{ __('Thao Tác') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/50">
                    @forelse($resources as $res)
                    <tr class="hover:bg-blue-500/[0.03] transition-all group">
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-5">
                                <div class="relative w-16 h-16 shrink-0 bg-slate-800/80 rounded-2xl flex items-center justify-center text-blue-400 group-hover:scale-110 transition-transform border border-slate-700/50 shadow-inner">
                                    @php
                                        $icon = 'fa-file-alt';
                                        $color = 'text-blue-400';
                                        $format = strtolower($res->format);
                                        if($format == 'pdf') { $icon = 'fa-file-pdf'; $color = 'text-red-400'; }
                                        elseif(in_array($format, ['doc','docx'])) { $icon = 'fa-file-word'; $color = 'text-blue-500'; }
                                        elseif(in_array($format, ['mp4','mov','avi'])) { $icon = 'fa-file-video'; $color = 'text-purple-400'; }
                                        elseif(in_array($format, ['mp3','wav'])) { $icon = 'fa-file-audio'; $color = 'text-emerald-400'; }
                                        elseif(in_array($format, ['jpg','png','webp'])) { $icon = 'fa-file-image'; $color = 'text-orange-400'; }
                                    @endphp
                                    <i class="fas {{ $icon }} {{ $color }} text-2xl"></i>
                                </div>
                                <div>
                                    <div class="font-black text-white text-lg group-hover:text-blue-400 transition-colors line-clamp-1 mb-1">{{ $res->title }}</div>
                                    <div class="flex items-center gap-3">
                                        <div class="px-2 py-0.5 rounded bg-slate-800 text-[9px] font-black text-gray-500 border border-slate-700 uppercase">{{ $format }}</div>
                                        <span class="text-[10px] font-bold text-gray-600 truncate max-w-[150px]">{{ $res->file_name }}</span>
                                        <span class="w-1 h-1 bg-slate-800 rounded-full"></span>
                                        <span class="text-[10px] font-black text-blue-500/80 uppercase">{{ number_format($res->file_size / (1024*1024), 2) }} MB</span>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <div class="flex flex-col gap-1">
                                <span class="text-sm font-bold text-gray-300">{{ $res->resource_type }}</span>
                                <span class="text-[10px] font-black text-gray-600 uppercase tracking-widest">{{ $res->language }}</span>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <div class="flex flex-wrap gap-1.5 max-w-[200px]">
                                @php $authors = is_array($res->authors) ? $res->authors : [$res->authors]; @endphp
                                @foreach(array_slice($authors, 0, 2) as $author)
                                    <span class="px-2.5 py-1 bg-slate-800/50 text-gray-400 text-[10px] font-black rounded-lg border border-slate-700/50">{{ $author }}</span>
                                @endforeach
                                @if(count($authors) > 2)
                                    <span class="px-2.5 py-1 bg-blue-900/20 text-blue-400 text-[10px] font-black rounded-lg border border-blue-900/30">+{{ count($authors)-2 }}</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            @if($res->status === 'published')
                                <div class="inline-flex items-center px-4 py-1.5 bg-emerald-500/10 text-emerald-500 text-[10px] font-black uppercase tracking-widest rounded-full border border-emerald-500/20 shadow-[0_0_15px_rgba(16,185,129,0.1)]">
                                    <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full mr-2 animate-pulse"></span>
                                    {{ __('Ban hành') }}
                                </div>
                            @else
                                <div class="inline-flex items-center px-4 py-1.5 bg-amber-500/10 text-amber-500 text-[10px] font-black uppercase tracking-widest rounded-full border border-amber-500/20">
                                    <span class="w-1.5 h-1.5 bg-amber-500 rounded-full mr-2"></span>
                                    {{ __('Lưu nháp') }}
                                </div>
                            @endif
                        </td>
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-8">
                                <div class="text-center group/stat">
                                    <div class="text-xl font-black text-white leading-none group-hover/stat:text-blue-400 transition-colors">{{ $res->view_count }}</div>
                                    <div class="text-[9px] font-black text-gray-600 uppercase mt-1.5 tracking-tighter">{{ __('Xem') }}</div>
                                </div>
                                <div class="w-px h-8 bg-slate-800/50"></div>
                                <div class="text-center group/stat">
                                    <div class="text-xl font-black text-white leading-none group-hover/stat:text-emerald-400 transition-colors">{{ $res->download_count }}</div>
                                    <div class="text-[9px] font-black text-gray-600 uppercase mt-1.5 tracking-tighter">{{ __('Tải') }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-6 text-right">
                            <div class="flex justify-end gap-2.5 opacity-0 group-hover:opacity-100 transition-all duration-300 transform translate-x-4 group-hover:translate-x-0">
                                <a href="{{ Storage::url($res->file_path) }}" target="_blank" 
                                   class="w-12 h-12 rounded-2xl bg-blue-600/10 text-blue-400 hover:bg-blue-600 hover:text-white transition-all flex items-center justify-center shadow-xl border border-blue-500/20 hover:border-blue-400" title="Xem Trực Tuyến">
                                    <i class="fas fa-eye text-lg"></i>
                                </a>
                                <a href="#" class="w-12 h-12 rounded-2xl bg-slate-800 text-gray-400 hover:bg-slate-700 hover:text-white transition-all flex items-center justify-center border border-slate-700 shadow-xl" title="Chỉnh Sửa Metadata">
                                    <i class="fas fa-pen-nib text-lg"></i>
                                </a>
                                <form action="{{ route('admin.digital-resources.destroy', $res) }}" method="POST" onsubmit="return confirm('Xác nhận xóa vĩnh viễn tài liệu này?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="w-12 h-12 rounded-2xl bg-red-900/10 text-red-400 hover:bg-red-600 hover:text-white transition-all flex items-center justify-center border border-red-900/20 shadow-xl hover:border-red-500" title="Xóa Tài Liệu">
                                        <i class="fas fa-trash-alt text-lg"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-8 py-32 text-center">
                            <div class="flex flex-col items-center">
                                <div class="relative mb-8">
                                    <div class="absolute inset-0 bg-blue-500 blur-3xl opacity-10"></div>
                                    <div class="relative w-32 h-32 bg-slate-800/50 rounded-[3rem] flex items-center justify-center text-slate-700 border-2 border-dashed border-slate-700">
                                        <i class="fas fa-file-invoice text-5xl"></i>
                                    </div>
                                </div>
                                <h3 class="text-2xl font-black text-white mb-3">{{ __('Thư mục này còn trống') }}</h3>
                                <p class="text-gray-500 text-sm max-w-sm mx-auto mb-10 font-medium leading-relaxed">Bắt đầu xây dựng kho tri thức bằng cách thêm tài liệu số đầu tiên cho thư mục này.</p>
                                <a href="{{ route('admin.digital-resources.create', ['folder_id' => $folder->id]) }}" 
                                   class="px-10 py-5 bg-blue-600 hover:bg-blue-500 text-white font-black rounded-2xl shadow-2xl shadow-blue-500/30 transition-all hover:-translate-y-1 flex items-center">
                                    <i class="fas fa-plus-circle mr-3 text-xl"></i>
                                    {{ __('Thêm Tài Liệu Đầu Tiên') }}
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

