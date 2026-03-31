@extends('layouts.admin')

@section('content')
<div class="report-detail-page bg-white dark:bg-slate-900 rounded-lg shadow-xl overflow-hidden">
    <div class="p-6 border-b border-indigo-200 dark:border-indigo-900/30 flex justify-between items-center bg-indigo-50 dark:bg-indigo-950/20">
        <div>
            <h1 class="text-2xl font-black text-indigo-800 dark:text-indigo-400 uppercase tracking-tight flex items-center">
                <i class="fa-solid fa-ranking-star mr-3 opacity-70"></i>
                {{ __('Most Borrowing Patrons') }}
            </h1>
            <p class="text-sm text-indigo-600/70 dark:text-indigo-400/50 mt-1">
                {{ __('Top 20 most active patrons based on their loan history.') }}
            </p>
        </div>
        <a href="{{ route('admin.circulation.reports.index') }}" class="px-4 py-2 bg-slate-200 dark:bg-slate-800 text-slate-700 dark:text-slate-300 rounded-lg font-bold text-xs uppercase tracking-widest hover:bg-slate-300 dark:hover:bg-slate-700 transition-all shadow-sm">
            <i class="fa-solid fa-arrow-left mr-2"></i>{{ __('Back') }}
        </a>
    </div>

    <div class="overflow-x-auto p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @forelse($topPatrons as $index => $patron)
            <div class="card-admin p-4 rounded-xl relative overflow-hidden group">
                <div class="absolute -top-4 -right-4 w-16 h-16 bg-indigo-500/10 group-hover:bg-indigo-500/20 rounded-full flex items-center justify-center transition-all">
                    <span class="text-2xl font-black text-indigo-500/50 group-hover:text-indigo-500/80">#{{ $index + 1 }}</span>
                </div>
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 rounded-2xl bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center font-black text-indigo-600 text-lg shadow-sm">
                        {{ substr($patron->user->name ?? 'P', 0, 1) }}
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-bold text-slate-800 dark:text-slate-200 leading-none">
                            {{ $patron->user->name ?? 'Unknown' }}
                        </h3>
                        <p class="text-[10px] text-slate-500 mt-1 uppercase font-black tracking-widest">
                            {{ $patron->patron_code }}
                        </p>
                    </div>
                </div>
                <div class="flex justify-between items-end border-t border-slate-100 dark:border-slate-800 pt-3">
                    <div class="text-xs">
                        <span class="block text-slate-400 uppercase font-black text-[9px] tracking-widest">{{ __('Total Loans') }}</span>
                        <span class="text-lg font-black text-indigo-600 dark:text-indigo-400">{{ $patron->loan_transactions_count }}</span>
                    </div>
                    <div class="text-[9px] font-black uppercase text-indigo-500/50 bg-indigo-50 dark:bg-indigo-500/10 px-2 py-1 rounded">
                        {{ $patron->patronGroup->name ?? 'General' }}
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-4 py-20 text-center text-slate-400 italic">
                {{ __('No_Records_Found') }}
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
