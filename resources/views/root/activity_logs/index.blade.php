@extends('layouts.root')

@section('content')
<div class="space-y-8 animate-in fade-in duration-700">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight leading-tight">{{ __('Activity Logs') }}</h1>
            <p class="text-lg font-medium text-slate-500 dark:text-slate-400 mt-1">{{ __('Monitor system actions and user operations.') }}</p>
        </div>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-[3rem] shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
        <!-- Filter Bar -->
        <div class="p-8 border-b border-slate-100 dark:border-slate-700/50">
            <form action="{{ route('root.activity-logs.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                <div class="relative group">
                    <select name="user_id" onchange="this.form.submit()" 
                        class="w-full bg-slate-50 dark:bg-slate-900 border-transparent dark:border-slate-700 px-6 py-4 rounded-[1.25rem] text-sm font-bold text-slate-700 dark:text-slate-200 outline-none transition appearance-none cursor-pointer pr-10">
                        <option value="">{{ __('All Users') }}</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="relative group">
                    <input type="text" name="action" value="{{ request('action') }}" placeholder="{{ __('Action (e.g. create)') }}" 
                        class="w-full bg-slate-50 dark:bg-slate-900 border-transparent dark:border-slate-700 px-6 py-4 rounded-[1.25rem] text-sm font-bold text-slate-700 dark:text-slate-200 outline-none transition duration-300">
                </div>

                <div class="relative group">
                    <select name="method" onchange="this.form.submit()" 
                        class="w-full bg-slate-50 dark:bg-slate-900 border-transparent dark:border-slate-700 px-6 py-4 rounded-[1.25rem] text-sm font-bold text-slate-700 dark:text-slate-200 outline-none transition appearance-none cursor-pointer pr-10">
                        <option value="">{{ __('All Methods') }}</option>
                        <option value="POST" {{ request('method') == 'POST' ? 'selected' : '' }}>POST</option>
                        <option value="PUT" {{ request('method') == 'PUT' ? 'selected' : '' }}>PUT</option>
                        <option value="PATCH" {{ request('method') == 'PATCH' ? 'selected' : '' }}>PATCH</option>
                        <option value="DELETE" {{ request('method') == 'DELETE' ? 'selected' : '' }}>DELETE</option>
                        <option value="GET" {{ request('method') == 'GET' ? 'selected' : '' }}>GET</option>
                    </select>
                </div>

                <div class="relative group">
                    <input type="date" name="date_from" value="{{ request('date_from') }}" 
                        class="w-full bg-slate-50 dark:bg-slate-900 border-transparent dark:border-slate-700 px-6 py-4 rounded-[1.25rem] text-sm font-bold text-slate-700 dark:text-slate-200 outline-none transition">
                </div>

                <div class="flex gap-2">
                    <button type="submit" class="flex-1 px-6 py-4 bg-indigo-600 text-white text-[10px] font-black uppercase tracking-[0.2em] rounded-[1.25rem] transition transform active:scale-95 shadow-lg shadow-indigo-500/20">
                        {{ __('Filter') }}
                    </button>
                    @if(request()->anyFilled(['user_id', 'action', 'method', 'date_from', 'date_to']))
                        <a href="{{ route('root.activity-logs.index') }}" class="px-4 py-4 bg-rose-50 dark:bg-rose-900/20 text-rose-600 dark:text-rose-400 rounded-[1.25rem] border border-rose-100 dark:border-rose-900/30">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Table View -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-100 dark:divide-slate-700/50">
                <thead class="bg-slate-50/50 dark:bg-slate-900/50">
                    <tr>
                        <th scope="col" class="px-10 py-6 text-left text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em]">{{ __('User') }}</th>
                        <th scope="col" class="px-6 py-6 text-left text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em]">{{ __('Action') }}</th>
                        <th scope="col" class="px-6 py-6 text-left text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em]">{{ __('Method / Status') }}</th>
                        <th scope="col" class="px-6 py-6 text-left text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em]">{{ __('URL') }}</th>
                        <th scope="col" class="px-6 py-6 text-left text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em]">{{ __('Time') }}</th>
                        <th scope="col" class="px-10 py-6 text-right text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em]">{{ __('Details') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 dark:divide-slate-700/50">
                    @forelse($logs as $log)
                    <tr class="group hover:bg-slate-50/50 dark:hover:bg-slate-900/30 transition-all duration-200">
                        <td class="px-10 py-7 whitespace-nowrap">
                            <div class="flex items-center">
                                <span class="w-10 h-10 rounded-full bg-indigo-100 dark:bg-indigo-900/40 flex items-center justify-center text-indigo-600 dark:text-indigo-400 font-extrabold text-xs mr-4">
                                    {{ substr($log->user?->name ?? '?', 0, 1) }}
                                </span>
                                <div>
                                    <div class="text-sm font-black text-slate-900 dark:text-white">{{ $log->user?->name ?? 'Guest' }}</div>
                                    <div class="text-[10px] text-slate-500 dark:text-slate-400 font-bold">{{ $log->ip_address }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-7">
                            <span class="inline-flex items-center px-3 py-1 bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 text-[10px] font-black uppercase tracking-widest rounded-lg">
                                {{ $log->action }}
                            </span>
                        </td>
                        <td class="px-6 py-7">
                            <div class="flex items-center space-x-2">
                                <span class="px-2 py-1 text-[9px] font-black uppercase rounded {{ 
                                    $log->method == 'DELETE' ? 'bg-rose-50 text-rose-600 dark:bg-rose-900/30 dark:text-rose-400' : 
                                    ($log->method == 'POST' ? 'bg-emerald-50 text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-400' : 'bg-blue-50 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400') 
                                }}">
                                    {{ $log->method }}
                                </span>
                                @if($log->status_code)
                                    <span class="text-[10px] font-bold {{ $log->status_code >= 400 ? 'text-rose-500' : 'text-emerald-500' }}">
                                        {{ $log->status_code }}
                                    </span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-7">
                            <div class="text-[10px] text-slate-500 dark:text-slate-400 font-mono truncate max-w-[200px]" title="{{ $log->url }}">
                                {{ $log->url }}
                            </div>
                        </td>
                        <td class="px-6 py-7 whitespace-nowrap text-[10px] font-bold text-slate-500 dark:text-slate-400">
                            {{ $log->created_at->diffForHumans() }}
                        </td>
                        <td class="px-10 py-7 text-right">
                            <button onclick="openLogModal({{ $log->id }})" class="p-3 bg-slate-50 dark:bg-slate-900 text-slate-400 dark:text-slate-500 hover:text-indigo-600 dark:hover:text-indigo-400 rounded-xl transition-all border border-transparent hover:border-indigo-100 dark:hover:border-indigo-900/30">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-10 py-20 text-center">
                            <div class="flex flex-col items-center">
                                <svg class="w-16 h-16 text-slate-100 dark:text-slate-700 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                <p class="text-slate-400 dark:text-slate-500 text-sm font-bold">{{ __('No activities found in the signal stream.') }}</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($logs->hasPages())
        <div class="px-10 py-8 bg-slate-50/50 dark:bg-slate-900/50 border-t border-slate-100 dark:border-slate-700/50">
            {{ $logs->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Log Details Modal -->
<div id="logModal" class="hidden fixed inset-0 z-[100] overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity bg-slate-950/80 backdrop-blur-sm" onclick="closeLogModal()"></div>
        <div class="inline-block w-full max-w-4xl my-8 overflow-hidden text-left align-middle transition-all transform bg-white dark:bg-slate-900 rounded-[2.5rem] shadow-2xl">
            <div class="p-10">
                <div class="flex items-center justify-between mb-8">
                    <h3 class="text-2xl font-black text-slate-900 dark:text-white uppercase tracking-tight">{{ __('Activity Payload Analysis') }}</h3>
                    <button onclick="closeLogModal()" class="text-slate-400 hover:text-slate-600 transition-colors">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                
                <div id="logDetailContent" class="space-y-8">
                    <!-- Dynamic content will flow here -->
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function openLogModal(logId) {
        const modal = document.getElementById('logModal');
        const content = document.getElementById('logDetailContent');
        modal.classList.remove('hidden');
        content.innerHTML = '<div class="flex justify-center py-20"><div class="animate-spin rounded-full h-12 w-12 border-4 border-indigo-500 border-t-transparent"></div></div>';

        fetch(`/root/activity-logs/${logId}`)
            .then(response => response.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const detailHtml = doc.getElementById('log-detail-snippet').innerHTML;
                content.innerHTML = detailHtml;
            });
    }

    function closeLogModal() {
        document.getElementById('logModal').classList.add('hidden');
    }
</script>
@endsection
