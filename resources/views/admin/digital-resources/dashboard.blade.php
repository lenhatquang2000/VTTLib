@extends('layouts.admin')

@section('content')
<div class="space-y-8 animate-fade-in pb-12">
    <!-- Hero Analytics Header -->
    <div class="relative overflow-hidden rounded-[2.5rem] bg-[#0f172a] p-10 shadow-2xl border border-slate-800">
        <div class="absolute top-0 right-0 w-1/3 h-full bg-blue-600/10 blur-[120px] rounded-full"></div>
        <div class="relative z-10 flex flex-col md:flex-row justify-between items-center gap-8">
            <div>
                <div class="inline-flex items-center px-3 py-1 rounded-full bg-blue-500/10 border border-blue-500/20 text-blue-400 text-[10px] font-black uppercase tracking-widest mb-4">
                    <i class="fas fa-chart-network mr-2"></i> Real-time Analytics
                </div>
                <h1 class="text-4xl font-black text-white tracking-tight mb-2">Thống kê <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-indigo-400">Tài liệu số</span></h1>
                <p class="text-slate-500 font-medium">Báo cáo chi tiết về kho tri thức và hiệu suất khai thác tài liệu.</p>
            </div>
            <div class="flex gap-4">
                <a href="{{ route('admin.digital-folders.index') }}" class="px-8 py-4 bg-blue-600 hover:bg-blue-500 text-white font-black rounded-2xl shadow-xl shadow-blue-500/20 transition-all flex items-center">
                    <i class="fas fa-folder-open mr-3"></i> Quản lý Thư mục
                </a>
            </div>
        </div>
    </div>

    <!-- Main Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="card-admin p-8 rounded-[2rem] bg-slate-900/40 border border-slate-800 relative overflow-hidden group hover:border-blue-500/30 transition-all">
            <div class="absolute -right-4 -bottom-4 text-blue-500/5 text-7xl group-hover:scale-110 transition-transform"><i class="fas fa-file-invoice"></i></div>
            <div class="text-[10px] font-black text-gray-500 uppercase tracking-[0.2em] mb-4">Tổng Tài liệu</div>
            <div class="text-4xl font-black text-white">{{ number_format($stats['total_resources']) }}</div>
            <div class="mt-4 flex items-center text-emerald-400 text-xs font-bold">
                <i class="fas fa-arrow-up mr-1"></i> +12% <span class="text-gray-600 ml-2 font-medium">so với tháng trước</span>
            </div>
        </div>

        <div class="card-admin p-8 rounded-[2rem] bg-slate-900/40 border border-slate-800 relative overflow-hidden group hover:border-emerald-500/30 transition-all">
            <div class="absolute -right-4 -bottom-4 text-emerald-500/5 text-7xl group-hover:scale-110 transition-transform"><i class="fas fa-download"></i></div>
            <div class="text-[10px] font-black text-gray-500 uppercase tracking-[0.2em] mb-4">Lượt Tải về</div>
            <div class="text-4xl font-black text-white">{{ number_format($stats['total_downloads']) }}</div>
            <div class="mt-4 flex items-center text-emerald-400 text-xs font-bold">
                <i class="fas fa-arrow-up mr-1"></i> +5.4% <span class="text-gray-600 ml-2 font-medium">đang tăng trưởng</span>
            </div>
        </div>

        <div class="card-admin p-8 rounded-[2rem] bg-slate-900/40 border border-slate-800 relative overflow-hidden group hover:border-orange-500/30 transition-all">
            <div class="absolute -right-4 -bottom-4 text-orange-500/5 text-7xl group-hover:scale-110 transition-transform"><i class="fas fa-eye"></i></div>
            <div class="text-[10px] font-black text-gray-500 uppercase tracking-[0.2em] mb-4">Lượt Xem Trực tuyến</div>
            <div class="text-4xl font-black text-white">{{ number_format($stats['total_views']) }}</div>
            <div class="mt-4 flex items-center text-orange-400 text-xs font-bold">
                <i class="fas fa-bolt mr-1"></i> Hot <span class="text-gray-600 ml-2 font-medium">Tương tác cao</span>
            </div>
        </div>

        <div class="card-admin p-8 rounded-[2rem] bg-slate-900/40 border border-slate-800 relative overflow-hidden group hover:border-purple-500/30 transition-all">
            <div class="absolute -right-4 -bottom-4 text-purple-500/5 text-7xl group-hover:scale-110 transition-transform"><i class="fas fa-database"></i></div>
            <div class="text-[10px] font-black text-gray-500 uppercase tracking-[0.2em] mb-4">Dung lượng sử dụng</div>
            <div class="text-4xl font-black text-white">{{ number_format($stats['storage_used'] / (1024*1024), 1) }} <span class="text-lg">MB</span></div>
            <div class="mt-4 flex items-center text-purple-400 text-xs font-bold">
                <i class="fas fa-cloud mr-1"></i> SSD <span class="text-gray-600 ml-2 font-medium">Storage Optimal</span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Recent Activities -->
        <div class="lg:col-span-2 card-admin p-8 rounded-[2.5rem] border border-slate-800 bg-slate-900/20">
            <h3 class="text-xl font-black text-white mb-8 flex items-center gap-3">
                <i class="fas fa-clock text-blue-500"></i> Tài liệu mới cập nhật
            </h3>
            <div class="space-y-4">
                @foreach($stats['recent_resources'] as $res)
                <div class="flex items-center justify-between p-5 bg-slate-800/30 rounded-2xl border border-slate-700/50 hover:bg-slate-800/50 transition-all group">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-slate-700 flex items-center justify-center text-blue-400 group-hover:scale-110 transition-transform">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <div>
                            <div class="font-bold text-white group-hover:text-blue-400 transition-colors line-clamp-1">{{ $res->title }}</div>
                            <div class="text-[10px] font-black text-gray-500 uppercase tracking-widest mt-1">{{ $res->folder->folder_name }} • {{ $res->created_at->diffForHumans() }}</div>
                        </div>
                    </div>
                    <a href="{{ route('admin.digital-resources.show', $res) }}" class="w-10 h-10 rounded-lg bg-slate-700 flex items-center justify-center text-gray-400 hover:text-white hover:bg-blue-600 transition-all">
                        <i class="fas fa-external-link-alt text-xs"></i>
                    </a>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Top Resources -->
        <div class="card-admin p-8 rounded-[2.5rem] border border-slate-800 bg-slate-900/20">
            <h3 class="text-xl font-black text-white mb-8 flex items-center gap-3">
                <i class="fas fa-fire text-orange-500"></i> Tài liệu tiêu biểu
            </h3>
            <div class="space-y-6">
                @foreach($stats['top_viewed'] as $index => $res)
                <div class="flex items-start gap-4">
                    <div class="text-2xl font-black text-slate-800 italic">{{ sprintf('%02d', $index + 1) }}</div>
                    <div class="flex-1">
                        <div class="font-bold text-gray-300 text-sm hover:text-blue-400 transition-colors cursor-pointer line-clamp-2 leading-snug">{{ $res->title }}</div>
                        <div class="flex items-center gap-4 mt-2">
                            <span class="text-[9px] font-black text-gray-600 uppercase tracking-widest"><i class="fas fa-eye mr-1"></i> {{ $res->view_count }} lượt xem</span>
                            <span class="text-[9px] font-black text-emerald-500/80 uppercase tracking-widest"><i class="fas fa-download mr-1"></i> {{ $res->download_count }} tải</span>
                        </div>
                    </div>
                </div>
                @if(!$loop->last) <hr class="border-slate-800/50"> @endif
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
