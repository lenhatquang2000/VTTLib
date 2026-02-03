@extends('layouts.root')

@section('content')
    <div class="max-w-4xl mx-auto space-y-6 animate-in slide-in-from-bottom-4 duration-500">
        <div class="flex items-center justify-between pb-2">
            <div>
                <a href="{{ route('root.roles.index') }}" class="inline-flex items-center text-xs font-bold text-slate-500 hover:text-indigo-600 transition-colors uppercase tracking-widest mb-3 group">
                    <svg class="w-4 h-4 mr-1.5 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    {{ __('Back_to_Protocol') }}
                </a>
                <h1 class="text-3xl font-bold text-slate-900 dark:text-white tracking-tight">{{ __('Initialize_New_Security_Clearance') }}</h1>
            </div>
        </div>

        <form action="{{ route('root.roles.store') }}" method="POST" class="space-y-6">
            @csrf
            
            <div class="bg-white dark:bg-slate-800 rounded-[2.5rem] p-10 space-y-8 border border-slate-200 dark:border-slate-700 shadow-sm transition-all">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-2">
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest pl-1">{{ __('Role_System_Name') }} (Slug)</label>
                        <input type="text" name="name" placeholder="e.g. librarian" required
                            class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-2xl p-4 text-sm font-medium focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all">
                        @error('name') <p class="text-[10px] text-rose-500 font-bold mt-1 pl-1 uppercase tracking-wider">{{ $message }}</p> @enderror
                    </div>
                    <div class="space-y-2">
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest pl-1">{{ __('Role_Display_Name') }}</label>
                        <input type="text" name="display_name" placeholder="e.g. Librarian" required
                            class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-2xl p-4 text-sm font-medium focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all">
                        @error('display_name') <p class="text-[10px] text-rose-500 font-bold mt-1 pl-1 uppercase tracking-wider">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="space-y-5">
                    <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-700 pb-4">
                        <h3 class="text-sm font-black text-slate-900 dark:text-white uppercase tracking-widest">{{ __('Default_Sidebar_Access') }}</h3>
                        <span class="text-[10px] bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 px-3 py-1 rounded-full font-black uppercase tracking-widest border border-indigo-100 dark:border-indigo-900/30">Templates</span>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 max-h-[500px] overflow-y-auto pr-3 custom-scrollbar">
                        @foreach($sidebars as $sidebar)
                            <div class="space-y-4 col-span-1 md:col-span-2 group">
                                <label class="flex items-center p-5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-3xl cursor-pointer hover:border-indigo-500 dark:hover:border-indigo-400 hover:bg-white dark:hover:bg-slate-800 transition-all shadow-sm group">
                                    <input type="checkbox" name="sidebars[]" value="{{ $sidebar->id }}"
                                        class="w-6 h-6 rounded-lg border-slate-300 dark:border-slate-600 text-indigo-600 focus:ring-indigo-500 transition shadow-sm">
                                    <div class="ml-5">
                                        <span class="block text-sm font-black text-slate-900 dark:text-white uppercase tracking-wider">{{ __($sidebar->name) }}</span>
                                        <span class="block text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1.5">Primary Access Point</span>
                                    </div>
                                </label>
                                
                                @if($sidebar->children->isNotEmpty())
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pl-10">
                                        @foreach($sidebar->children as $child)
                                            <label class="flex items-center p-4 bg-white dark:bg-slate-800-ish border border-slate-100 dark:border-slate-700 rounded-2xl cursor-pointer hover:border-indigo-400 dark:hover:border-indigo-300 transition-all shadow-sm">
                                                <input type="checkbox" name="sidebars[]" value="{{ $child->id }}"
                                                    class="w-5 h-5 rounded-md border-slate-300 dark:border-slate-600 text-indigo-600 focus:ring-indigo-500 transition">
                                                <div class="ml-4">
                                                    <span class="block text-xs font-bold text-slate-700 dark:text-slate-200 uppercase tracking-tight">{{ __($child->name) }}</span>
                                                    <span class="block text-[9px] font-mono text-slate-400 mt-1 uppercase tracking-tight">{{ $child->route_name }}</span>
                                                </div>
                                            </label>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="pt-8 border-t border-slate-100 dark:border-slate-700 flex justify-end">
                    <button type="submit"
                        class="px-12 py-4.5 bg-indigo-600 text-white rounded-[1.25rem] font-black uppercase text-xs tracking-[0.2em] hover:bg-indigo-700 transition transform active:scale-95 shadow-2xl shadow-indigo-500/20 dark:shadow-none">
                        {{ __('Execute_Provision') }}
                    </button>
                </div>
            </div>
        </form>
    </div>

    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 20px; }
        [data-theme="dark"] .custom-scrollbar::-webkit-scrollbar-thumb { background: #334155; }
    </style>
@endsection
