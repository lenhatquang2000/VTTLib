@extends('layouts.admin')

@section('content')
<div class="report-detail-page bg-white dark:bg-slate-900 rounded-lg shadow-xl overflow-hidden">
    <div class="p-6 border-b border-slate-200 dark:border-slate-800 flex justify-between items-center bg-slate-50 dark:bg-slate-950/50">
        <div>
            <h1 class="text-2xl font-black text-slate-800 dark:text-white uppercase tracking-tight">
                {{ __('Currently Borrowed Items') }}
            </h1>
            <p class="text-sm text-slate-500 mt-1">
                {{ __('Displaying all items currently out on loan.') }}
            </p>
        </div>
        <a href="{{ route('admin.circulation.reports.index') }}" class="px-4 py-2 bg-slate-200 dark:bg-slate-800 text-slate-700 dark:text-slate-300 rounded-lg font-bold text-xs uppercase tracking-widest hover:bg-slate-300 dark:hover:bg-slate-700 transition-all">
            <i class="fa-solid fa-arrow-left mr-2"></i>{{ __('Back') }}
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-100 dark:bg-slate-800/50 text-slate-600 dark:text-slate-400 text-[10px] font-black uppercase tracking-widest border-b border-slate-200 dark:border-slate-700">
                    <th class="px-6 py-4">{{ __('Barcode') }}</th>
                    <th class="px-6 py-4">{{ __('Title') }}</th>
                    <th class="px-6 py-4">{{ __('Patron') }}</th>
                    <th class="px-6 py-4">{{ __('Loan Date') }}</th>
                    <th class="px-6 py-4">{{ __('Due Date') }}</th>
                    <th class="px-6 py-4">{{ __('Status') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                @forelse($loans as $loan)
                <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/30 transition-colors">
                    <td class="px-6 py-4 font-mono text-xs font-bold text-indigo-600 dark:text-indigo-400">
                        {{ $loan->bookItem->barcode }}
                    </td>
                    <td class="px-6 py-4 text-sm font-semibold text-slate-800 dark:text-slate-200">
                        {{ $loan->bookItem->bibliographicRecord->title ?? __('No_Title_Defined') }}
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                            <div class="w-8 h-8 rounded-full bg-slate-200 dark:bg-slate-700 flex items-center justify-center text-xs font-bold mr-3 shadow-inner">
                                {{ substr($loan->patron->user->name ?? 'P', 0, 1) }}
                            </div>
                            <div>
                                <p class="text-sm font-bold text-slate-800 dark:text-slate-200 leading-none">
                                    {{ $loan->patron->user->name ?? 'Unknown' }}
                                </p>
                                <p class="text-[10px] text-slate-500 mt-1 uppercase font-black">
                                    {{ $loan->patron->patron_code }}
                                </p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-xs font-medium text-slate-600 dark:text-slate-400">
                        {{ $loan->loan_date ? \Carbon\Carbon::parse($loan->loan_date)->format('d/m/Y H:i') : '-' }}
                    </td>
                    <td class="px-6 py-4 text-xs font-medium {{ $loan->due_date < now() ? 'text-rose-500 font-bold' : 'text-slate-600 dark:text-slate-400' }}">
                        {{ $loan->due_date ? \Carbon\Carbon::parse($loan->due_date)->format('d/m/Y') : '-' }}
                        @if($loan->due_date < now())
                            <span class="ml-1 text-[8px] bg-rose-100 text-rose-600 px-1 py-0.5 rounded uppercase">{{ __('Overdue') }}</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-[9px] font-black uppercase rounded-full {{ $loan->status === 'borrowed' ? 'bg-amber-100 text-amber-600 dark:bg-amber-900/20 dark:text-amber-400' : 'bg-green-100 text-green-600 dark:bg-green-900/20 dark:text-green-400' }}">
                            {{ __($loan->status) }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-slate-400 italic">
                        {{ __('No_Records_Found') }}
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($loans->hasPages())
    <div class="px-6 py-4 bg-slate-50 dark:bg-slate-950/50 border-t border-slate-200 dark:border-slate-800">
        {{ $loans->links() }}
    </div>
    @endif
</div>
@endsection
