@extends('layouts.root')

@section('content')
<div class="space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-700">
    <!-- Header -->
    <div class="relative overflow-hidden bg-white dark:bg-slate-800 rounded-[3rem] p-10 shadow-xl shadow-slate-200/50 dark:shadow-none border border-slate-100 dark:border-slate-700">
        <div class="relative z-10 flex flex-col md:flex-row justify-between items-center gap-6">
            <div class="text-center md:text-left">
                <h1 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight uppercase">{{ __('QUẢN LÝ QUYỀN HẠN NHANH') }}</h1>
                <p class="text-slate-500 dark:text-slate-400 mt-2 font-medium tracking-wide">{{ __('Tìm kiếm và gán vai trò trực tiếp cho danh tính hệ thống.') }}</p>
            </div>
            <a href="{{ route('root.users.index') }}" class="inline-flex items-center px-6 py-3 bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 rounded-2xl font-bold hover:bg-slate-200 transition-all transform active:scale-95 group">
                <svg class="w-5 h-5 mr-2 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                {{ __('Quay lại danh sách') }}
            </a>
        </div>
        
        <!-- Decoration -->
        <div class="absolute -top-24 -right-24 w-64 h-64 bg-indigo-500/5 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-24 -left-24 w-64 h-64 bg-emerald-500/5 rounded-full blur-3xl"></div>
    </div>

    <!-- Smart Search & Filters -->
    <div class="bg-white dark:bg-slate-800 p-6 rounded-[2.5rem] shadow-lg border border-slate-100 dark:border-slate-700">
        <form action="{{ route('root.users.assign') }}" method="GET" class="flex flex-col md:flex-row gap-4">
            <div class="relative flex-1 group">
                <span class="absolute inset-y-0 left-0 pl-5 flex items-center text-slate-400 group-focus-within:text-indigo-500 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </span>
                <input type="text" name="search" value="{{ $search }}" 
                    placeholder="{{ __('Nhập tên, email hoặc username để tìm kiếm...') }}" 
                    class="w-full bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 pl-14 pr-6 py-4 rounded-3xl text-sm font-medium focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all">
                @if($search)
                    <a href="{{ route('root.users.assign') }}" class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-300 hover:text-rose-500 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </a>
                @endif
            </div>
            
            <div class="flex gap-2">
                <button type="submit" class="px-10 py-4 bg-indigo-600 text-white rounded-3xl font-black uppercase text-xs tracking-widest hover:bg-indigo-700 shadow-xl shadow-indigo-100 dark:shadow-none transition transform active:scale-95">
                    {{ __('Quét danh tính') }}
                </button>
            </div>
        </form>
    </div>

    <!-- Users Grid/Table -->
    <div class="bg-white dark:bg-slate-800 rounded-[3rem] overflow-hidden border border-slate-100 dark:border-slate-700 shadow-xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-separate border-spacing-0">
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-900/50 text-slate-400 dark:text-slate-500">
                        <th class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.2em] border-b border-slate-100 dark:border-slate-700">{{ __('Danh tính') }}</th>
                        <th class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.2em] border-b border-slate-100 dark:border-slate-700">{{ __('Thông tin liên lạc') }}</th>
                        <th class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.2em] border-b border-slate-100 dark:border-slate-700">{{ __('Vai trò hiện tại') }}</th>
                        <th class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.2em] border-b border-slate-100 dark:border-slate-700">{{ __('Gán vai trò mới') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 dark:divide-slate-700/50">
                    @forelse($users as $user)
                    <tr class="group hover:bg-slate-50/50 dark:hover:bg-slate-900/30 transition-all duration-300">
                        <td class="px-8 py-6">
                            <div class="flex items-center">
                                <div class="w-12 h-12 bg-indigo-50 dark:bg-indigo-900/20 rounded-2xl flex items-center justify-center text-indigo-600 dark:text-indigo-400 font-black text-xl border border-indigo-100 dark:border-indigo-900/30">
                                    {{ substr($user->name, 0, 1) }}
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-bold text-slate-900 dark:text-white">{{ $user->name }}</div>
                                    <div class="text-[10px] font-black text-indigo-500 uppercase tracking-widest mt-0.5">{{ $user->username ?? 'Unknown' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <div class="text-sm text-slate-600 dark:text-slate-300 font-medium">{{ $user->email }}</div>
                            <div class="text-[10px] text-slate-400 font-bold mt-1">ID: #{{ str_pad($user->id, 5, '0', STR_PAD_LEFT) }}</div>
                        </td>
                        <td class="px-8 py-6">
                            <div class="flex flex-wrap gap-2">
                                @forelse($user->roles as $role)
                                    <div class="flex items-center bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl pl-3 pr-1 py-1 shadow-sm">
                                        <span class="text-[10px] font-black uppercase tracking-wider text-slate-600 dark:text-slate-400">{{ $role->display_name }}</span>
                                        <form action="{{ route('root.users.roles.remove', $user->roles()->where('role_id', $role->id)->first()->pivot->id) }}" method="POST" class="ml-1">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="p-1 hover:text-rose-500 transition-colors" onclick="return confirm('Thu hồi quyền này?')">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                            </button>
                                        </form>
                                    </div>
                                @empty
                                    <span class="text-xs text-slate-300 italic font-medium">{{ __('Chưa có vai trò') }}</span>
                                @endforelse
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <form action="{{ route('root.users.roles.store') }}" method="POST" class="flex gap-2 items-center">
                                @csrf
                                <input type="hidden" name="user_id" value="{{ $user->id }}">
                                <select name="role_id" required 
                                    onchange="const form = this.closest('form'); const sBtn = form.querySelector('.submit-btn'); const cBtn = form.querySelector('.cancel-btn'); const hasVal = !!this.value; sBtn.classList.toggle('hidden', !hasVal); cBtn.classList.toggle('hidden', !hasVal);"
                                    class="flex-1 bg-slate-50 dark:bg-slate-900/80 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-2 text-xs font-bold focus:ring-2 focus:ring-indigo-500 outline-none transition-all appearance-none cursor-pointer min-w-[120px]">
                                    <option value="" disabled selected>{{ __('Chọn Role') }}</option>
                                    @foreach($roles as $role)
                                        @if(!$user->roles->contains($role->id))
                                            <option value="{{ $role->id }}">{{ $role->display_name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                                <div class="flex gap-1.5">
                                    <button type="submit" class="submit-btn hidden p-2 bg-emerald-500 text-white rounded-xl hover:bg-emerald-600 transition transform active:scale-95 shadow-lg shadow-emerald-100 dark:shadow-none duration-200 animate-in zoom-in">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    </button>
                                    <button type="button" class="cancel-btn hidden p-2 bg-slate-100 dark:bg-slate-700 text-slate-400 hover:text-rose-500 rounded-xl hover:bg-slate-200 transition transform active:scale-95 duration-200 animate-in zoom-in"
                                        onclick="const sl = this.closest('form').querySelector('select'); sl.value = ''; sl.dispatchEvent(new Event('change'));">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    </button>
                                </div>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-8 py-20 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-20 h-20 bg-slate-50 dark:bg-slate-900 rounded-full flex items-center justify-center text-slate-200 dark:text-slate-800 mb-4">
                                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                </div>
                                <h3 class="text-lg font-bold text-slate-400 uppercase tracking-widest">{{ __('Không tìm thấy danh tính nào') }}</h3>
                                <p class="text-slate-400 dark:text-slate-600 text-sm mt-1">{{ __('Vui lòng thử từ khóa khác hoặc xóa bộ lọc.') }}</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
        <div class="px-8 py-6 bg-slate-50 dark:bg-slate-900/30 border-t border-slate-100 dark:border-slate-700 flex justify-between items-center">
            <div class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">
                {{ __('Hiển thị') }} {{ $users->firstItem() }}-{{ $users->lastItem() }} / {{ $users->total() }} {{ __('Danh tính') }}
            </div>
            <div class="custom-pagination">
                {{ $users->links() }}
            </div>
        </div>
        @endif
    </div>
</div>

<style>
    .custom-pagination nav { display: flex; gap: 0.35rem; }
    .custom-pagination span, .custom-pagination a { 
        padding: 0.65rem 0.95rem; border: none; border-radius: 1rem; color: #64748b; font-size: 0.8rem; font-weight: 800; background: white; transition: all 0.3s ease;
        box-shadow: 0 1px 2px rgba(0,0,0,0.05);
    }
    .dark .custom-pagination span, .dark .custom-pagination a { background: #1e293b; color: #94a3b8; box-shadow: none; }
    .custom-pagination a:hover { background: #f1f5f9; color: #0f172a; transform: translateY(-2px); }
    .dark .custom-pagination a:hover { background: #334155; color: #fff; }
    .custom-pagination .active span { background: #4f46e5; color: #fff; box-shadow: 0 10px 15px -3px rgba(79, 70, 229, 0.4); }
</style>
@endsection
