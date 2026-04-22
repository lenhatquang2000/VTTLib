@extends('layouts.admin')

@section('content')
<div class="space-y-8 animate-fade-in">
    <!-- Hero Header Section -->
    <div class="relative overflow-hidden rounded-[2rem] bg-gradient-to-br from-indigo-900 via-blue-900 to-slate-900 p-8 md:p-12 shadow-2xl">
        <div class="absolute top-0 right-0 -mt-20 -mr-20 w-80 h-80 bg-blue-500/20 rounded-full blur-[100px]"></div>
        <div class="absolute bottom-0 left-0 -mb-20 -ml-20 w-80 h-80 bg-indigo-500/20 rounded-full blur-[100px]"></div>
        
        <div class="relative z-10 flex flex-col lg:flex-row justify-between items-center gap-8">
            <div class="text-center lg:text-left">
                <div class="inline-flex items-center px-4 py-1.5 rounded-full bg-blue-500/10 border border-blue-500/20 text-blue-400 text-xs font-bold uppercase tracking-widest mb-4">
                    <span class="flex h-2 w-2 rounded-full bg-blue-500 mr-2 animate-pulse"></span>
                    {{ __('Digital Repository System') }}
                </div>
                <h1 class="text-4xl md:text-5xl font-black text-white mb-4 tracking-tight leading-tight">
                    {{ __('Quản Lý') }} <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-indigo-400">{{ __('Tài Liệu Số') }}</span>
                </h1>
                <p class="text-blue-100/60 text-lg max-w-xl mx-auto lg:mx-0">{{ __('Tổ chức tri thức, lưu trữ tài liệu học thuật và phân loại khoa học theo chuẩn thư viện quốc tế.') }}</p>
            </div>
            
            <div class="flex flex-col sm:flex-row gap-4 w-full sm:w-auto">
                <button onclick="openCreateModal()" class="px-8 py-4 bg-blue-600 hover:bg-blue-500 text-white font-bold rounded-2xl shadow-xl shadow-blue-500/25 transition-all hover:-translate-y-1 flex items-center justify-center group">
                    <i class="fas fa-folder-plus mr-3 text-xl group-hover:scale-110 transition-transform"></i>
                    {{ __('Thêm Thư Mục') }}
                </button>
                <a href="{{ route('admin.digital-folders.export') }}" class="px-8 py-4 bg-white/5 hover:bg-white/10 text-white font-bold rounded-2xl backdrop-blur-md border border-white/10 transition-all flex items-center justify-center group">
                    <i class="fas fa-file-export mr-3 opacity-60 group-hover:rotate-12 transition-transform"></i>
                    {{ __('Xuất Dữ Liệu') }}
                </a>
            </div>
        </div>
    </div>

    <!-- Smart Statistics Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        @php
            $stats = [
                ['label' => 'Tổng Thư Mục', 'value' => $folders->count(), 'icon' => 'fa-folders', 'color' => 'blue'],
                ['label' => 'Tổng Tài Liệu', 'value' => $folders->sum('resources_count'), 'icon' => 'fa-file-medical', 'color' => 'emerald'],
                ['label' => 'Lượt Xem File', 'value' => '1.2K', 'icon' => 'fa-eye', 'color' => 'orange'],
                ['label' => 'Dung Lượng', 'value' => '4.2 GB', 'icon' => 'fa-database', 'color' => 'purple'],
            ];
        @endphp
        @foreach($stats as $stat)
        <div class="card-admin p-6 flex items-center gap-5 border-b-4 border-{{ $stat['color'] }}-500 group hover:bg-slate-800/80 transition-all">
            <div class="w-14 h-14 rounded-2xl bg-{{ $stat['color'] }}-500/10 flex items-center justify-center text-{{ $stat['color'] }}-500 group-hover:scale-110 transition-transform">
                <i class="fas {{ $stat['icon'] }} text-2xl"></i>
            </div>
            <div>
                <div class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-1">{{ __($stat['label']) }}</div>
                <div class="text-3xl font-black text-white">{{ $stat['value'] }}</div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Advanced Folder Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @foreach($folders as $folder)
        <div class="relative group h-72 rounded-[2.5rem] overflow-hidden bg-slate-900/40 border border-slate-800 hover:border-blue-500/50 shadow-2xl transition-all duration-500 hover:-translate-y-2">
            <!-- Glassmorphism Background Decoration -->
            <div class="absolute -top-12 -right-12 w-40 h-40 bg-blue-600/5 rounded-full blur-3xl group-hover:bg-blue-600/10 transition-colors"></div>
            
            <div class="relative z-10 p-10 h-full flex flex-col justify-between">
                <div>
                    <div class="flex justify-between items-start">
                        <div class="relative">
                            <div class="absolute inset-0 bg-blue-500 blur-lg opacity-20 group-hover:opacity-40 transition-opacity"></div>
                            <div class="relative w-16 h-16 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl flex items-center justify-center text-white shadow-xl">
                                <i class="fas fa-folder-open text-3xl"></i>
                            </div>
                        </div>
                        <div class="flex space-x-1 opacity-0 group-hover:opacity-100 transition-all duration-300">
                            <button onclick="openEditModal({{ $folder }})" class="w-10 h-10 rounded-xl bg-slate-800 text-gray-400 hover:text-white hover:bg-slate-700 transition-all flex items-center justify-center">
                                <i class="fas fa-pencil-alt text-sm"></i>
                            </button>
                            <form action="{{ route('admin.digital-folders.destroy', $folder) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa thư mục này?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="w-10 h-10 rounded-xl bg-slate-800 text-gray-400 hover:text-red-400 hover:bg-red-900/20 transition-all flex items-center justify-center">
                                    <i class="fas fa-trash-alt text-sm"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                    
                    <div class="mt-8">
                        <h3 class="text-2xl font-black text-white mb-2 leading-tight line-clamp-1 group-hover:text-blue-400 transition-colors">
                            {{ $folder->folder_name }}
                        </h3>
                        <div class="flex items-center space-x-3">
                            <span class="px-3 py-1 bg-blue-500/10 text-blue-400 text-[10px] font-black uppercase tracking-[0.2em] rounded-lg border border-blue-500/20">
                                {{ $folder->folder_code }}
                            </span>
                            <div class="flex items-center text-gray-500 text-[10px] font-bold uppercase tracking-widest">
                                <i class="fas fa-globe-asia mr-1.5"></i>
                                {{ $folder->language == 'vi' ? 'Tiếng Việt' : 'English' }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex items-end justify-between pt-6 border-t border-slate-800/50">
                    <div>
                        <div class="flex items-baseline gap-1">
                            <span class="text-4xl font-black text-white group-hover:scale-110 transition-transform inline-block">{{ $folder->resources_count }}</span>
                            <span class="text-[10px] font-black text-gray-500 uppercase tracking-widest">{{ __('File') }}</span>
                        </div>
                    </div>
                    <a href="{{ route('admin.digital-resources.index', ['folder_id' => $folder->id]) }}" 
                       class="h-14 px-8 bg-slate-800 hover:bg-blue-600 text-white rounded-2xl font-black text-sm transition-all duration-300 flex items-center group/btn shadow-xl border border-slate-700 hover:border-blue-400">
                        {{ __('Vào Thư Mục') }}
                        <i class="fas fa-arrow-right ml-3 text-xs group-hover/btn:translate-x-2 transition-transform"></i>
                    </a>
                </div>
            </div>
        </div>
        @endforeach

        <!-- Modern Add New Card -->
        <button onclick="openCreateModal()" class="relative h-72 rounded-[2.5rem] border-4 border-dashed border-slate-800 hover:border-blue-500/50 hover:bg-blue-500/5 transition-all duration-500 flex flex-col items-center justify-center group overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-blue-600/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
            <div class="relative z-10 w-20 h-20 rounded-3xl bg-slate-800 group-hover:bg-blue-600 flex items-center justify-center text-slate-600 group-hover:text-white transition-all duration-500 mb-6 shadow-2xl group-hover:rotate-90">
                <i class="fas fa-plus text-3xl"></i>
            </div>
            <span class="relative z-10 text-slate-500 group-hover:text-blue-400 font-black uppercase tracking-[0.3em] text-xs transition-colors">{{ __('Thêm Thư Mục Mới') }}</span>
        </button>
    </div>
</div>

<!-- Refined Modal Styling -->
<div id="folderModal" class="fixed inset-0 z-50 hidden bg-slate-950/90 backdrop-blur-xl flex items-center justify-center p-4">
    <div class="bg-slate-900 border border-slate-800 rounded-[2.5rem] w-full max-w-lg shadow-[0_0_100px_rgba(37,99,235,0.1)] overflow-hidden animate-modal-in">
        <div class="p-10 border-b border-slate-800 flex justify-between items-center bg-gradient-to-r from-blue-600/10 to-transparent">
            <div>
                <h2 id="modalTitle" class="text-3xl font-black text-white tracking-tight">Tạo Thư Mục</h2>
                <p class="text-gray-500 text-sm mt-1 uppercase tracking-widest font-bold">Cấu hình phân loại mới</p>
            </div>
            <button onclick="closeModal()" class="w-12 h-12 rounded-2xl bg-slate-800 text-gray-400 hover:text-white hover:bg-red-500/20 transition-all">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        <form id="folderForm" action="{{ route('admin.digital-folders.store') }}" method="POST" class="p-10 space-y-6">
            @csrf
            <div id="methodField"></div>
            
            <div class="space-y-4">
                <div class="group">
                    <label class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-2 px-1">{{ __('Mã Thư Mục (Code)') }} *</label>
                    <div class="relative">
                        <i class="fas fa-fingerprint absolute left-5 top-1/2 -translate-y-1/2 text-gray-500 group-focus-within:text-blue-400 transition-colors"></i>
                        <input type="text" name="folder_code" id="f_code" required 
                               class="input-field w-full pl-14 py-4 rounded-2xl bg-slate-800/50 border-slate-700 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all font-bold" 
                               placeholder="VD: YH-SK">
                    </div>
                </div>
                
                <div class="group">
                    <label class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-2 px-1">{{ __('Tên Thư Mục (Name)') }} *</label>
                    <div class="relative">
                        <i class="fas fa-tag absolute left-5 top-1/2 -translate-y-1/2 text-gray-500 group-focus-within:text-blue-400 transition-colors"></i>
                        <input type="text" name="folder_name" id="f_name" required 
                               class="input-field w-full pl-14 py-4 rounded-2xl bg-slate-800/50 border-slate-700 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all font-bold" 
                               placeholder="VD: Y học & Sức khỏe">
                    </div>
                </div>
                
                <div class="group">
                    <label class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-2 px-1">{{ __('Mô Tả (Description)') }}</label>
                    <div class="relative">
                        <i class="fas fa-align-left absolute left-5 top-5 text-gray-500 group-focus-within:text-blue-400 transition-colors"></i>
                        <textarea name="description" id="f_desc" rows="3" 
                                  class="input-field w-full pl-14 py-4 rounded-2xl bg-slate-800/50 border-slate-700 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all font-bold" 
                                  placeholder="Mô tả mục đích của thư mục này..."></textarea>
                    </div>
                </div>
                
                <div class="group">
                    <label class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-2 px-1">{{ __('Ngôn Ngữ (Language)') }}</label>
                    <div class="relative">
                        <i class="fas fa-globe-asia absolute left-5 top-1/2 -translate-y-1/2 text-gray-500 group-focus-within:text-blue-400 transition-colors"></i>
                        <select name="language" class="input-field w-full pl-14 py-4 rounded-2xl bg-slate-800/50 border-slate-700 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all font-bold appearance-none">
                            <option value="vi">Tiếng Việt</option>
                            <option value="en">English</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="pt-6 flex gap-4">
                <button type="button" onclick="closeModal()" 
                        class="flex-1 px-8 py-4 bg-slate-800 text-gray-400 font-bold rounded-2xl hover:bg-slate-700 hover:text-white transition-all uppercase tracking-widest text-xs">
                    {{ __('Hủy Bỏ') }}
                </button>
                <button type="submit" 
                        class="flex-1 px-8 py-4 bg-blue-600 text-white font-black rounded-2xl hover:bg-blue-500 shadow-xl shadow-blue-500/20 transition-all uppercase tracking-widest text-xs">
                    {{ __('Lưu Thông Tin') }}
                </button>
            </div>
        </form>
    </div>
</div>

<style>
    @keyframes modal-in {
        from { transform: scale(0.95); opacity: 0; }
        to { transform: scale(1); opacity: 1; }
    }
    .animate-modal-in {
        animation: modal-in 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
    }
    .input-field {
        background: #1e293b;
        color: white;
    }
    .input-field::placeholder {
        color: #64748b;
        font-weight: 500;
    }
</style>

<script>
    const modal = document.getElementById('folderModal');
    const form = document.getElementById('folderForm');
    const title = document.getElementById('modalTitle');
    const methodField = document.getElementById('methodField');

    function openCreateModal() {
        form.action = "{{ route('admin.digital-folders.store') }}";
        methodField.innerHTML = '';
        title.innerText = "Tạo Thư Mục";
        form.reset();
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function openEditModal(folder) {
        form.action = `/topsecret/digital-folders/${folder.id}`;
        methodField.innerHTML = '@method("PUT")';
        title.innerText = "Sửa Thư Mục";
        document.getElementById('f_code').value = folder.folder_code;
        document.getElementById('f_name').value = folder.folder_name;
        document.getElementById('f_desc').value = folder.description || '';
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeModal() {
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }
</script>
@endsection

