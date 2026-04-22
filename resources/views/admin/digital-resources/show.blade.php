@extends('layouts.admin')

@section('content')
<div class="max-w-[1600px] mx-auto space-y-6 animate-fade-in pb-10">
    <!-- Sophisticated Header -->
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6 bg-slate-900/50 p-6 rounded-[2rem] border border-slate-800 shadow-xl backdrop-blur-md">
        <div class="flex items-center gap-6">
            <div class="relative">
                <div class="absolute inset-0 bg-blue-500 blur-xl opacity-20"></div>
                <div class="relative w-16 h-16 bg-slate-800 rounded-2xl flex items-center justify-center text-blue-400 border border-slate-700 shadow-2xl">
                    <i class="fas fa-file-alt text-2xl"></i>
                </div>
            </div>
            <div>
                <div class="flex items-center gap-2 text-[9px] font-black text-gray-500 uppercase tracking-[0.2em] mb-1">
                    <a href="{{ route('admin.digital-resources.index', ['folder_id' => $resource->folder_id]) }}" class="hover:text-blue-400 transition-colors">{{ $resource->folder->folder_name }}</a>
                    <i class="fas fa-chevron-right text-[7px] opacity-30"></i>
                    <span class="text-blue-400">{{ __('Đang Xem') }}</span>
                </div>
                <h1 class="text-2xl font-black text-white tracking-tight line-clamp-1 max-w-2xl">{{ $resource->title }}</h1>
            </div>
        </div>
        
        <div class="flex gap-3 w-full lg:w-auto">
            <a href="{{ route('admin.digital-resources.download', $resource) }}" 
               class="flex-1 lg:flex-none px-6 py-3 bg-emerald-600 hover:bg-emerald-500 text-white font-black rounded-xl shadow-lg transition-all flex items-center justify-center">
                <i class="fas fa-download mr-2 text-sm"></i>
                {{ __('Tải Tệp') }}
            </a>
            <a href="{{ route('admin.digital-resources.index', ['folder_id' => $resource->folder_id]) }}" 
               class="flex-1 lg:flex-none px-6 py-3 bg-slate-800 hover:bg-slate-700 text-white font-bold rounded-xl transition-all flex items-center justify-center border border-slate-700">
                <i class="fas fa-arrow-left mr-2 opacity-60"></i>
                {{ __('Quay Lại') }}
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <!-- Resource Viewer (Main Content) -->
        <div class="lg:col-span-3 space-y-6">
            <div class="card-admin p-2 rounded-[2.5rem] bg-slate-950 border border-slate-800 shadow-2xl overflow-hidden aspect-[4/3] lg:aspect-video relative group">
                @php $format = strtolower($resource->format); @endphp
                
                @if($format == 'pdf')
                    <iframe src="{{ Storage::url($resource->file_path) }}#toolbar=0&navpanes=0&scrollbar=0" class="w-full h-full rounded-[2.2rem]" frameborder="0"></iframe>
                @elseif(in_array($format, ['mp4','mov','avi','webm']))
                    <video class="w-full h-full rounded-[2.2rem] outline-none" controls controlsList="nodownload">
                        <source src="{{ Storage::url($resource->file_path) }}" type="video/{{ $format == 'mov' ? 'mp4' : $format }}">
                        Trình duyệt của bạn không hỗ trợ phát video.
                    </video>
                @elseif(in_array($format, ['jpg','jpeg','png','webp']))
                    <div class="w-full h-full flex items-center justify-center bg-slate-900/50">
                        <img src="{{ Storage::url($resource->file_path) }}" class="max-w-full max-h-full rounded-2xl shadow-2xl object-contain">
                    </div>
                @else
                    <div class="w-full h-full flex flex-col items-center justify-center space-y-4">
                        <div class="w-24 h-24 bg-slate-800 rounded-3xl flex items-center justify-center text-slate-600 border-2 border-dashed border-slate-700">
                            <i class="fas fa-file-download text-4xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-400">Định dạng {{ strtoupper($format) }} không hỗ trợ xem trực tiếp</h3>
                        <p class="text-gray-600 text-sm">Vui lòng tải tệp về máy để xem nội dung.</p>
                        <a href="{{ route('admin.digital-resources.download', $resource) }}" class="px-8 py-3 bg-blue-600 text-white font-black rounded-xl">Tải Tệp Ngay</a>
                    </div>
                @endif
            </div>

            <!-- Resource Description Card -->
            <div class="card-admin p-8 rounded-[2.5rem]">
                <h3 class="text-lg font-black text-white mb-4 uppercase tracking-widest">{{ __('Mô tả tài liệu') }}</h3>
                <div class="prose prose-invert max-w-none text-gray-400 font-medium leading-relaxed italic">
                    {{ $resource->description ?: 'Không có mô tả cho tài liệu này.' }}
                </div>
            </div>
        </div>

        <!-- Sidebar: Metadata Information -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Stats Info -->
            <div class="grid grid-cols-2 gap-4">
                <div class="card-admin p-5 rounded-3xl bg-blue-600/10 border-blue-500/20 text-center">
                    <div class="text-2xl font-black text-blue-400 leading-none">{{ $resource->view_count }}</div>
                    <div class="text-[9px] font-black text-gray-500 uppercase mt-2 tracking-widest">Lượt Xem</div>
                </div>
                <div class="card-admin p-5 rounded-3xl bg-emerald-600/10 border-emerald-500/20 text-center">
                    <div class="text-2xl font-black text-emerald-400 leading-none">{{ $resource->download_count }}</div>
                    <div class="text-[9px] font-black text-gray-500 uppercase mt-2 tracking-widest">Lượt Tải</div>
                </div>
            </div>

            <!-- Detail Metadata -->
            <div class="card-admin p-6 rounded-[2rem] space-y-6">
                <div>
                    <h4 class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-3">Tác giả</h4>
                    <div class="flex flex-wrap gap-2">
                        @php $authors = is_array($resource->authors) ? $resource->authors : [$resource->authors]; @endphp
                        @foreach($authors as $author)
                            <span class="px-3 py-1 bg-slate-800 text-gray-300 text-xs font-bold rounded-lg border border-slate-700">{{ $author }}</span>
                        @endforeach
                    </div>
                </div>

                <div>
                    <h4 class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-3">Thông tin tệp</h4>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center text-xs">
                            <span class="text-gray-500 font-bold">Định dạng</span>
                            <span class="text-white font-black uppercase text-blue-400">{{ $resource->format }}</span>
                        </div>
                        <div class="flex justify-between items-center text-xs">
                            <span class="text-gray-500 font-bold">Dung lượng</span>
                            <span class="text-white font-black">{{ number_format($resource->file_size / (1024*1024), 2) }} MB</span>
                        </div>
                        <div class="flex justify-between items-center text-xs">
                            <span class="text-gray-500 font-bold">Ngôn ngữ</span>
                            <span class="text-white font-black">{{ $resource->language }}</span>
                        </div>
                    </div>
                </div>

                <div>
                    <h4 class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-3">Xuất bản</h4>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center text-xs">
                            <span class="text-gray-500 font-bold">Năm XB</span>
                            <span class="text-white font-black">{{ $resource->publish_year ?: '--' }}</span>
                        </div>
                        <div class="flex justify-between items-center text-xs">
                            <span class="text-gray-500 font-bold">Nhà XB</span>
                            <span class="text-white font-black text-right line-clamp-1">{{ $resource->publisher ?: '--' }}</span>
                        </div>
                    </div>
                </div>

                @if($resource->cataloging_link)
                <div class="pt-4 border-t border-slate-800">
                    <a href="#" class="flex items-center justify-between group/link">
                        <span class="text-[10px] font-black text-gray-500 uppercase tracking-widest">Xem Biên Mục MARC21</span>
                        <i class="fas fa-external-link-alt text-blue-500 group-hover/link:translate-x-1 transition-transform"></i>
                    </a>
                </div>
                @endif
            </div>

            <!-- Copyright Info -->
            <div class="card-admin p-6 rounded-[2rem] bg-indigo-900/10 border-indigo-900/30">
                <div class="flex items-center gap-3 mb-3">
                    <i class="fas fa-shield-alt text-indigo-400"></i>
                    <h4 class="text-[10px] font-black text-indigo-300 uppercase tracking-widest">Bản quyền & Sử dụng</h4>
                </div>
                <p class="text-xs text-gray-500 leading-relaxed font-medium">
                    {{ $resource->copyright ?: 'Bản quyền thuộc về Trường Đại học Võ Trường Toản.' }}
                </p>
            </div>
        </div>
    </div>
</div>

<style>
    .animate-fade-in { animation: fadeIn 0.5s ease-out; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
</style>
@endsection
