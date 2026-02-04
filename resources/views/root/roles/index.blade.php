@extends('layouts.root')

@section('content')
    <div class="space-y-6 animate-in fade-in duration-500">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center p-6 bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-slate-200 dark:border-slate-700 gap-4">
            <div>
                <h2 class="text-xl font-bold text-slate-900 dark:text-white">{{ __('Role_Template_Management') }}</h2>
                <p class="text-sm text-slate-500 mt-1">{{ __('Define and manage system access levels.') }}</p>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('root.users.index') }}"
                    class="inline-flex items-center px-5 py-2.5 bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-200 text-sm font-bold rounded-xl hover:bg-slate-200 dark:hover:bg-slate-600 transition transform active:scale-95">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    {{ __('Manage_Subjects') }}
                </a>
                <a href="{{ route('root.roles.create') }}"
                    class="inline-flex items-center px-5 py-2.5 bg-indigo-600 text-white text-sm font-bold rounded-xl hover:bg-indigo-700 shadow-lg shadow-indigo-100 dark:shadow-none transition transform active:scale-95">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    {{ __('Initialize_New_Role') }}
                </a>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-[2rem] overflow-hidden border border-slate-200 dark:border-slate-700 shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead>
                        <tr class="bg-slate-50 dark:bg-slate-900/50 text-slate-500 dark:text-slate-400 border-b border-slate-200 dark:border-slate-700 uppercase text-[10px] font-black tracking-widest">
                            <th class="p-5">{{ __('ID') }}</th>
                            <th class="p-5">{{ __('Role_Identity') }}</th>
                            <th class="p-5">{{ __('Assigned_Subjects') }}</th>
                            <th class="p-5">{{ __('Default_Tabs') }}</th>
                            <th class="p-5 text-right">{{ __('Operations') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700/50">
                        @foreach($roles as $role)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition">
                                <td class="p-5 font-mono text-xs text-slate-400">#{{ str_pad($role->id, 3, '0', STR_PAD_LEFT) }}</td>
                                <td class="p-5">
                                    <div class="text-slate-900 dark:text-white font-bold">{{ $role->display_name }}</div>
                                    <div class="text-[10px] text-slate-500 font-bold tracking-wider uppercase mt-1">{{ $role->name }}</div>
                                </td>
                                <td class="p-5">
                                    <div class="flex items-center">
                                        <span class="inline-flex items-center px-3 py-1 bg-indigo-50 dark:bg-indigo-900/40 text-indigo-600 dark:text-indigo-400 text-xs font-black rounded-lg border border-indigo-100 dark:border-indigo-900/30">
                                            {{ $role->users_count }}
                                        </span>
                                        <span class="text-slate-400 text-[10px] uppercase font-black ml-2 tracking-widest">Subjects</span>
                                    </div>
                                </td>
                                <td class="p-5">
                                    <div class="flex flex-wrap gap-1.5 ">
                                        @foreach($role->sidebars->take(5) as $sidebar)
                                            <span class="px-2.5 py-1 bg-slate-50 dark:bg-slate-900 text-slate-600 dark:text-slate-400 text-[10px] font-bold rounded-full border border-slate-100 dark:border-slate-700">
                                                {{ __($sidebar->name) }}
                                            </span>
                                        @endforeach
                                        @if($role->sidebars->count() > 5)
                                            <span class="text-slate-400 text-[10px] font-black self-center ml-1">+{{ $role->sidebars->count() - 5 }} More</span>
                                        @endif
                                        @if($role->sidebars->count() == 0)
                                            <span class="text-slate-400 text-[10px] italic font-medium">No Default Tabs</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="p-5 text-right">
                                    <div class="flex justify-end items-center space-x-2">
                                        <a href="{{ route('root.roles.edit', $role->id) }}" 
                                            class="inline-flex items-center p-2.5 bg-slate-50 dark:bg-slate-700/50 text-slate-600 dark:text-slate-400 rounded-xl hover:bg-indigo-600 hover:text-white transition group shadow-sm"
                                            title="{{ __('Modify') }}">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </a>
                                        
                                        <form action="{{ route('root.roles.destroy', $role->id) }}" method="POST"
                                            onsubmit="return confirm('{{ __('Delete_Confirmation') }}')" class="inline">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                class="inline-flex items-center p-2.5 bg-rose-50 dark:bg-rose-900/20 text-rose-600 dark:text-rose-400 rounded-xl hover:bg-rose-600 hover:text-white transition shadow-sm"
                                                title="{{ __('Delete') }}">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
