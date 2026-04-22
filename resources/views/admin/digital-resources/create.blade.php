@extends('layouts.admin')

@section('content')
<div class="max-w-[1400px] mx-auto space-y-8 animate-fade-in pb-20">
    <!-- Sophisticated Header -->
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6 bg-slate-900/50 p-8 rounded-[2.5rem] border border-slate-800 shadow-xl backdrop-blur-md">
        <div class="flex items-center gap-6">
            <div class="relative">
                <div class="absolute inset-0 bg-blue-500 blur-2xl opacity-20 animate-pulse"></div>
                <div class="relative w-20 h-20 bg-gradient-to-br from-blue-600 to-indigo-700 rounded-3xl flex items-center justify-center text-white shadow-2xl">
                    <i class="fas fa-file-signature text-3xl"></i>
                </div>
            </div>
            <div>
                <div class="flex items-center gap-2 text-[10px] font-black text-gray-500 uppercase tracking-[0.2em] mb-2">
                    <span>Metadata Entry</span>
                    <i class="fas fa-chevron-right text-[8px] opacity-30"></i>
                    <span class="text-blue-400">{{ $folder->folder_name }}</span>
                </div>
                <h1 class="text-4xl font-black text-white tracking-tight">{{ __('Biên mục Tài liệu') }}</h1>
            </div>
        </div>
        
        <div class="flex gap-3">
            <a href="{{ route('admin.digital-resources.index', ['folder_id' => $folder->id]) }}" 
               class="px-8 py-4 bg-slate-800 hover:bg-slate-700 text-white font-bold rounded-2xl transition-all flex items-center border border-slate-700">
                <i class="fas fa-times-circle mr-3 opacity-60"></i>
                {{ __('Hủy Bỏ') }}
            </a>
        </div>
    </div>

    <form action="{{ route('admin.digital-resources.store') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        @csrf
        <input type="hidden" name="folder_id" value="{{ $folder->id }}">

        <!-- Main Metadata Column -->
        <div class="lg:col-span-8 space-y-8">
            <!-- 1. Primary Identification -->
            <div class="card-admin p-10 rounded-[2.5rem] border-l-8 border-blue-600 relative overflow-hidden">
                <div class="absolute top-0 right-0 p-10 opacity-[0.03] pointer-events-none">
                    <i class="fas fa-id-card text-9xl"></i>
                </div>
                
                <h2 class="text-2xl font-black text-white mb-8 flex items-center gap-3">
                    <span class="w-10 h-10 rounded-xl bg-blue-600/20 text-blue-500 flex items-center justify-center text-sm">01</span>
                    {{ __('Nhận diện & Tác giả') }}
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="md:col-span-2 group">
                        <label class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-3 px-1">{{ __('Tiêu đề tài liệu') }} *</label>
                        <input type="text" name="title" required 
                               class="w-full px-6 py-5 rounded-2xl bg-slate-800/50 border-slate-700 text-white font-bold focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all text-lg" 
                               placeholder="Nhập tiêu đề đầy đủ của tài liệu...">
                    </div>

                    <div class="space-y-6">
                        <div class="group">
                            <label class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-3 px-1">{{ __('Tác giả chính (+)') }}</label>
                            <div class="relative">
                                <input type="text" id="author_input" 
                                       class="w-full px-6 py-4 rounded-2xl bg-slate-800/50 border-slate-700 text-white font-bold focus:border-blue-500 transition-all pr-16" 
                                       placeholder="Gõ tên và nhấn Enter">
                                <button type="button" onclick="addTag('author')" class="absolute right-3 top-1/2 -translate-y-1/2 w-10 h-10 bg-blue-600 hover:bg-blue-500 text-white rounded-xl shadow-lg transition-all">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                            <div id="authors_tags" class="flex flex-wrap gap-2 mt-4"></div>
                        </div>

                        <div class="group">
                            <label class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-3 px-1">{{ __('Tác giả phụ (+)') }}</label>
                            <div class="relative">
                                <input type="text" id="s_author_input" 
                                       class="w-full px-6 py-4 rounded-2xl bg-slate-800/50 border-slate-700 text-white font-bold focus:border-blue-500 transition-all pr-16" 
                                       placeholder="Cộng sự, biên dịch...">
                                <button type="button" onclick="addTag('s_author')" class="absolute right-3 top-1/2 -translate-y-1/2 w-10 h-10 bg-slate-700 hover:bg-slate-600 text-white rounded-xl transition-all">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                            <div id="s_authors_tags" class="flex flex-wrap gap-2 mt-4"></div>
                        </div>
                    </div>

                    <div class="space-y-6">
                        <div class="group">
                            <label class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-3 px-1">{{ __('Chủ đề / Đề mục (+)') }}</label>
                            <div class="relative">
                                <input type="text" id="subject_input" 
                                       class="w-full px-6 py-4 rounded-2xl bg-slate-800/50 border-slate-700 text-white font-bold focus:border-blue-500 transition-all pr-16" 
                                       placeholder="VD: Kinh tế số, AI...">
                                <button type="button" onclick="addTag('subject')" class="absolute right-3 top-1/2 -translate-y-1/2 w-10 h-10 bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl shadow-lg transition-all">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                            <div id="subjects_tags" class="flex flex-wrap gap-2 mt-4"></div>
                        </div>

                        <div class="group">
                            <label class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-3 px-1">{{ __('Định danh (ISBN/DOI)') }}</label>
                            <input type="text" name="identifier" 
                                   class="w-full px-6 py-4 rounded-2xl bg-slate-800/50 border-slate-700 text-white font-bold focus:border-blue-500 transition-all" 
                                   placeholder="Mã số định danh duy nhất">
                        </div>
                    </div>
                </div>
            </div>

            <!-- 2. Content & Publication -->
            <div class="card-admin p-10 rounded-[2.5rem] border-l-8 border-emerald-600 relative overflow-hidden">
                <div class="absolute top-0 right-0 p-10 opacity-[0.03] pointer-events-none">
                    <i class="fas fa-book-reader text-9xl"></i>
                </div>

                <h2 class="text-2xl font-black text-white mb-8 flex items-center gap-3">
                    <span class="w-10 h-10 rounded-xl bg-emerald-600/20 text-emerald-500 flex items-center justify-center text-sm">02</span>
                    {{ __('Nội dung & Xuất bản') }}
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="group">
                        <label class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-3 px-1">{{ __('Nhà xuất bản') }}</label>
                        <input type="text" name="publisher" 
                               class="w-full px-6 py-4 rounded-2xl bg-slate-800/50 border-slate-700 text-white font-bold focus:border-emerald-500 transition-all" 
                               placeholder="VD: NXB Giáo dục">
                    </div>
                    <div class="group">
                        <label class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-3 px-1">{{ __('Năm / Ngày phát hành') }}</label>
                        <input type="text" name="publish_year" 
                               class="w-full px-6 py-4 rounded-2xl bg-slate-800/50 border-slate-700 text-white font-bold focus:border-emerald-500 transition-all" 
                               placeholder="2023 hoặc 25/12/2023">
                    </div>
                    <div class="md:col-span-2 group">
                        <label class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-3 px-1">{{ __('Mô tả vắn tắt') }}</label>
                        <textarea name="description" rows="4" 
                                  class="w-full px-6 py-5 rounded-2xl bg-slate-800/50 border-slate-700 text-white font-medium focus:border-emerald-500 transition-all" 
                                  placeholder="Tóm tắt nội dung chính của tài liệu..."></textarea>
                    </div>
                    <div class="group">
                        <label class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-3 px-1">{{ __('Nguồn gốc') }}</label>
                        <input type="text" name="source" 
                               class="w-full px-6 py-4 rounded-2xl bg-slate-800/50 border-slate-700 text-white font-bold focus:border-emerald-500 transition-all">
                    </div>
                    <div class="group">
                        <label class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-3 px-1">{{ __('Phạm vi (Coverage)') }}</label>
                        <input type="text" name="coverage" 
                               class="w-full px-6 py-4 rounded-2xl bg-slate-800/50 border-slate-700 text-white font-bold focus:border-emerald-500 transition-all">
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side: File & Action Column -->
        <div class="lg:col-span-4 space-y-8">
            <!-- File Upload Card -->
            <div class="card-admin p-8 rounded-[2.5rem] bg-gradient-to-br from-slate-900 to-indigo-950 border-blue-500/30 shadow-2xl">
                <h3 class="text-lg font-black text-white mb-6 uppercase tracking-widest flex items-center">
                    <i class="fas fa-cloud-upload-alt mr-3 text-blue-500"></i>
                    {{ __('Tệp Tài Liệu Số') }}
                </h3>
                
                <div class="space-y-6">
                    <div class="group">
                        <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-3">{{ __('Loại Tài Liệu') }} *</label>
                        <div class="relative">
                            <select name="resource_type" required 
                                    class="w-full px-5 py-4 rounded-2xl bg-slate-800 border-slate-700 text-white font-bold focus:border-blue-500 transition-all appearance-none">
                                <option value="">-- Chọn --</option>
                                <option value="E-book">E-book</option>
                                <option value="Thesis">Khóa luận / Luận văn</option>
                                <option value="Video">Video bài giảng</option>
                                <option value="Scientific Report">Báo cáo khoa học</option>
                            </select>
                            <i class="fas fa-chevron-down absolute right-5 top-1/2 -translate-y-1/2 text-gray-600 pointer-events-none"></i>
                        </div>
                    </div>

                    <div class="group">
                        <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-3">{{ __('Ngôn Ngữ') }} *</label>
                        <input type="text" name="language" required value="Tiếng Việt" 
                               class="w-full px-5 py-4 rounded-2xl bg-slate-800 border-slate-700 text-white font-bold focus:border-blue-500 transition-all">
                    </div>

                    <div class="relative">
                        <label class="block text-[10px] font-black text-blue-400 uppercase tracking-widest mb-3">{{ __('Tải lên file nguồn') }} *</label>
                        <div class="relative group/file">
                            <input type="file" name="file" id="file-upload" required class="hidden" onchange="updateFileName(this)">
                            <label for="file-upload" class="flex flex-col items-center justify-center w-full h-48 border-2 border-dashed border-slate-700 group-hover/file:border-blue-500/50 bg-slate-800/50 rounded-3xl cursor-pointer transition-all">
                                <div id="upload-placeholder" class="text-center">
                                    <div class="w-16 h-16 bg-slate-700 rounded-2xl flex items-center justify-center text-slate-500 mb-3 mx-auto group-hover/file:scale-110 transition-transform">
                                        <i class="fas fa-file-upload text-2xl"></i>
                                    </div>
                                    <p class="text-xs font-bold text-gray-400 px-4 leading-tight">PDF, MP4, DOCX (Max 50MB)</p>
                                </div>
                                <div id="file-info" class="hidden text-center px-4">
                                    <div class="w-16 h-16 bg-blue-600 rounded-2xl flex items-center justify-center text-white mb-3 mx-auto">
                                        <i class="fas fa-check-circle text-2xl"></i>
                                    </div>
                                    <p id="file-name-display" class="text-sm font-black text-blue-400 truncate max-w-[200px]"></p>
                                    <p class="text-[10px] text-gray-500 mt-1 uppercase tracking-widest">Đã chọn thành công</p>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Legal & Linking -->
            <div class="card-admin p-8 rounded-[2.5rem] shadow-2xl">
                <h3 class="text-lg font-black text-white mb-6 uppercase tracking-widest">Pháp lý & Liên kết</h3>
                <div class="space-y-6">
                    <div class="group">
                        <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-3">{{ __('Bản quyền') }}</label>
                        <div class="relative">
                            <select name="copyright" class="w-full px-5 py-4 rounded-2xl bg-slate-800 border-slate-700 text-white font-bold focus:border-blue-500 transition-all appearance-none">
                                <option value="VTTU Copyright">Bản quyền VTTU</option>
                                <option value="Creative Commons">Creative Commons</option>
                                <option value="Public Domain">Công cộng</option>
                            </select>
                            <i class="fas fa-chevron-down absolute right-5 top-1/2 -translate-y-1/2 text-gray-600 pointer-events-none"></i>
                        </div>
                    </div>
                    <div class="group">
                        <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-3">{{ __('Liên kết biên mục MARC21') }}</label>
                        <div class="relative">
                            <input type="text" name="cataloging_link" 
                                   class="w-full px-5 py-4 rounded-2xl bg-slate-800 border-slate-700 text-white font-bold focus:border-blue-500 transition-all pl-12" 
                                   placeholder="VD: #12345">
                            <i class="fas fa-link absolute left-5 top-1/2 -translate-y-1/2 text-gray-600"></i>
                        </div>
                        <p class="text-[10px] text-gray-600 mt-3 italic px-1 leading-tight">Kết nối với bản ghi vật lý trong hệ thống sách giấy</p>
                    </div>
                </div>
            </div>

            <!-- Publish Actions -->
            <div class="card-admin p-8 rounded-[2.5rem] bg-slate-900/80 sticky top-6 shadow-2xl border-t border-slate-800">
                <div class="space-y-4">
                    <button type="submit" name="draft" 
                            class="w-full py-5 bg-slate-800 hover:bg-slate-700 text-gray-300 font-black rounded-2xl transition-all flex items-center justify-center uppercase tracking-widest text-xs border border-slate-700">
                        <i class="fas fa-archive mr-3 opacity-50"></i>
                        {{ __('Lưu Nháp (Cập nhật)') }}
                    </button>
                    <button type="submit" name="publish" 
                            class="w-full py-5 bg-gradient-to-r from-blue-600 to-indigo-700 hover:from-blue-500 hover:to-indigo-600 text-white font-black rounded-2xl transition-all transform hover:-translate-y-1 shadow-2xl shadow-blue-500/40 flex items-center justify-center uppercase tracking-widest text-xs group">
                        <i class="fas fa-paper-plane mr-3 group-hover:translate-x-1 group-hover:-translate-y-1 transition-transform"></i>
                        {{ __('Ban hành tài liệu') }}
                    </button>
                    <div class="flex items-center justify-center gap-2 text-[10px] text-gray-600 font-black uppercase tracking-tighter mt-4">
                        <i class="fas fa-shield-check text-blue-500/50"></i>
                        <span>Thông tin đã được mã hóa an toàn</span>
                    </div>
                </div>
            </div>
        </div>

        <div id="hidden_tags_container"></div>
    </form>
</div>

<style>
    .input-field { background: #1e293b; color: white; }
    .tag-item { animation: tag-in 0.3s cubic-bezier(0.34, 1.56, 0.64, 1); }
    @keyframes tag-in { from { transform: scale(0.8); opacity: 0; } to { transform: scale(1); opacity: 1; } }
    .animate-fade-in { animation: fadeIn 0.5s ease-out; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
</style>

<script>
    const tags = { author: [], s_author: [], subject: [] };

    function addTag(type) {
        const input = document.getElementById(type === 'author' ? 'author_input' : (type === 's_author' ? 's_author_input' : 'subject_input'));
        const value = input.value.trim();
        if (value && !tags[type].includes(value)) {
            tags[type].push(value);
            renderTags(type);
            input.value = '';
        }
    }

    function removeTag(type, value) {
        tags[type] = tags[type].filter(t => t !== value);
        renderTags(type);
    }

    function renderTags(type) {
        const container = document.getElementById(type === 'author' ? 'authors_tags' : (type === 's_author' ? 's_authors_tags' : 'subjects_tags'));
        const colors = { author: 'blue', s_author: 'slate', subject: 'indigo' };
        const color = colors[type];
        
        container.innerHTML = tags[type].map(t => `
            <span class="tag-item inline-flex items-center px-4 py-2.5 rounded-xl bg-${color}-500/10 text-${color}-400 text-[10px] font-black border border-${color}-500/20 shadow-sm">
                ${t}
                <button type="button" onclick="removeTag('${type}', '${t}')" class="ml-3 hover:text-white transition-colors"><i class="fas fa-times-circle"></i></button>
            </span>
        `).join('');
        updateHiddenInputs();
    }

    function updateHiddenInputs() {
        const container = document.getElementById('hidden_tags_container');
        container.innerHTML = '';
        Object.keys(tags).forEach(type => {
            const fieldName = type === 'author' ? 'authors' : (type === 's_author' ? 'secondary_authors' : 'subjects');
            tags[type].forEach(value => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = `${fieldName}[]`;
                input.value = value;
                container.appendChild(input);
            });
        });
    }

    function updateFileName(input) {
        if (input.files && input.files[0]) {
            document.getElementById('upload-placeholder').classList.add('hidden');
            document.getElementById('file-info').classList.remove('hidden');
            document.getElementById('file-name-display').innerText = input.files[0].name;
        }
    }

    ['author_input', 's_author_input', 'subject_input'].forEach(id => {
        document.getElementById(id).addEventListener('keypress', function(e) {
            if (e.key === 'Enter') { e.preventDefault(); addTag(id.replace('_input', '')); }
        });
    });
</script>
@endsection
