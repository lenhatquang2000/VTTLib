@extends('layouts.admin')

@section('content')
<div class="report-detail-page bg-white dark:bg-slate-900 rounded-lg shadow-xl overflow-hidden">
    <div class="p-6 border-b border-indigo-200 dark:border-indigo-900/30 flex justify-between items-center bg-indigo-50 dark:bg-indigo-950/20">
        <div>
            <h1 class="text-2xl font-black text-indigo-800 dark:text-indigo-400 uppercase tracking-tight flex items-center">
                <i class="fa-solid fa-box-archive mr-3 opacity-70"></i>
                {{ __('Never Borrowed Items') }}
            </h1>
            <p class="text-sm text-indigo-600/70 dark:text-indigo-400/50 mt-1">
                {{ __('Identifying cataloged documents that have zero circulation history.') }}
            </p>
        </div>
        <a href="{{ route('admin.circulation.reports.index') }}" class="px-4 py-2 bg-slate-200 dark:bg-slate-800 text-slate-700 dark:text-slate-300 rounded-lg font-bold text-xs uppercase tracking-widest hover:bg-slate-300 dark:hover:bg-slate-700 transition-all shadow-sm">
            <i class="fa-solid fa-arrow-left mr-2"></i>{{ __('Back') }}
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-indigo-50/50 dark:bg-slate-800/50 text-indigo-600 dark:text-indigo-400/70 text-[10px] font-black uppercase tracking-widest border-b border-indigo-100 dark:border-slate-700">
                    <th class="px-6 py-4">{{ __('Barcode') }}</th>
                    <th class="px-6 py-4">{{ __('Title') }}</th>
                    <th class="px-6 py-4">{{ __('Location') }}</th>
                    <th class="px-6 py-4">{{ __('Status') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-indigo-100 dark:divide-slate-800">
                @forelse($items as $item)
                <tr class="hover:bg-indigo-50/30 dark:hover:bg-indigo-900/5 transition-colors">
                    <td class="px-6 py-4 font-mono text-xs font-bold text-indigo-600 dark:text-indigo-400">
                        {{ $item->barcode }}
                    </td>
                    <td class="px-6 py-4 text-sm font-semibold text-slate-800 dark:text-slate-200">
                        {{ $item->bibliographicRecord->title ?? __('No_Title_Defined') }}
                    </td>
                    <td class="px-6 py-4 text-xs font-medium text-slate-600 dark:text-slate-400">
                        {{ $item->storageLocation->name ?? 'N/A' }}
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-[9px] font-black uppercase rounded-full bg-slate-100 text-slate-600 dark:bg-slate-900/20 dark:text-slate-400">
                            {{ __($item->status) }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-12 text-center text-indigo-400 italic font-medium">
                        {{ __('No_Records_Found') }}
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($items->hasPages())
    <div class="px-6 py-4 bg-indigo-50/50 dark:bg-slate-950/50 border-t border-indigo-100 dark:border-slate-800">
        {{ $items->links() }}
    </div>
    @endif
</div>
@endsection
