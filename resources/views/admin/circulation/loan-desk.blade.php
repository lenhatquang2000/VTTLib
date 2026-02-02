@extends('layouts.admin')

@section('content')
<div class="space-y-8">
    @if(session('success'))
        <div class="bg-green-900/20 border border-green-500 text-green-400 p-4 text-xs font-mono rounded">
            [OK] {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-900/20 border border-red-500 text-red-400 p-4 text-xs font-mono rounded">
            [ERROR] {{ session('error') }}
        </div>
    @endif

    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold">{{ __('Loan_Desk') }}</h1>
            <p class="text-sm text-gray-400 mt-1">{{ __('Checkout_and_checkin_books') }}</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.circulation.index') }}" class="btn-secondary">
                {{ __('Policies') }}
            </a>
            <a href="{{ route('admin.circulation.fines') }}" class="btn-secondary">
                {{ __('Fines') }}
            </a>
        </div>
    </div>

    <!-- Checkout/Checkin Forms -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Checkout Form -->
        <div class="card-admin rounded-lg p-6">
            <h3 class="text-lg font-bold mb-4 text-green-400">{{ __('Checkout') }} ({{ __('Loan') }})</h3>
            <form action="{{ route('admin.circulation.checkout') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium mb-1">{{ __('Patron_Code') }} *</label>
                    <input type="text" name="patron_code" required class="input-field w-full" 
                        placeholder="{{ __('Scan_or_enter_patron_code') }}" autofocus>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">{{ __('Book_Barcode') }} *</label>
                    <input type="text" name="barcode" required class="input-field w-full" 
                        placeholder="{{ __('Scan_or_enter_barcode') }}">
                </div>
                <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white py-3 rounded font-bold">
                    {{ __('Checkout_Book') }}
                </button>
            </form>
        </div>

        <!-- Checkin Form -->
        <div class="card-admin rounded-lg p-6">
            <h3 class="text-lg font-bold mb-4 text-blue-400">{{ __('Checkin') }} ({{ __('Return') }})</h3>
            <form action="{{ route('admin.circulation.checkin') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium mb-1">{{ __('Book_Barcode') }} *</label>
                    <input type="text" name="barcode" required class="input-field w-full" 
                        placeholder="{{ __('Scan_or_enter_barcode') }}">
                </div>
                <div class="pt-8">
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded font-bold">
                        {{ __('Return_Book') }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Overdue Loans Alert -->
    @if($overdueLoans->count() > 0)
    <div class="card-admin rounded-lg overflow-hidden border-l-4 border-red-500">
        <div class="p-4 bg-red-900/20">
            <h3 class="text-lg font-bold text-red-400">{{ __('Overdue_Loans') }} ({{ $overdueLoans->count() }})</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-800 text-xs uppercase">
                    <tr>
                        <th class="p-3 text-left">{{ __('Patron') }}</th>
                        <th class="p-3 text-left">{{ __('Book') }}</th>
                        <th class="p-3 text-left">{{ __('Due_Date') }}</th>
                        <th class="p-3 text-left">{{ __('Overdue_Days') }}</th>
                        <th class="p-3 text-left">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700">
                    @foreach($overdueLoans as $loan)
                    <tr class="hover:bg-gray-800/50">
                        <td class="p-3">
                            <div class="font-medium">{{ $loan->patron->display_name ?? $loan->patron->user->name }}</div>
                            <div class="text-xs text-gray-400">{{ $loan->patron->patron_code }}</div>
                        </td>
                        <td class="p-3">
                            <div class="font-medium">{{ $loan->bookItem->bibliographicRecord->title ?? 'N/A' }}</div>
                            <div class="text-xs text-gray-400 font-mono">{{ $loan->bookItem->barcode }}</div>
                        </td>
                        <td class="p-3 text-red-400">{{ $loan->due_date->format('d/m/Y') }}</td>
                        <td class="p-3">
                            <span class="bg-red-900/50 text-red-400 px-2 py-1 rounded text-xs font-bold">
                                {{ $loan->getOverdueDays() }} {{ __('days') }}
                            </span>
                        </td>
                        <td class="p-3">
                            <form action="{{ route('admin.circulation.renew', $loan) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="text-blue-400 hover:text-blue-300 text-xs" 
                                    {{ !$loan->canRenew() ? 'disabled' : '' }}>
                                    {{ __('Renew') }}
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <!-- Recent Transactions -->
    <div class="card-admin rounded-lg overflow-hidden">
        <div class="p-4 border-b border-gray-700">
            <h3 class="text-lg font-bold">{{ __('Recent_Transactions') }}</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-800 text-xs uppercase">
                    <tr>
                        <th class="p-3 text-left">{{ __('Date') }}</th>
                        <th class="p-3 text-left">{{ __('Patron') }}</th>
                        <th class="p-3 text-left">{{ __('Book') }}</th>
                        <th class="p-3 text-left">{{ __('Due_Date') }}</th>
                        <th class="p-3 text-left">{{ __('Status') }}</th>
                        <th class="p-3 text-left">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700">
                    @forelse($recentLoans as $loan)
                    <tr class="hover:bg-gray-800/50">
                        <td class="p-3 text-xs text-gray-400">{{ $loan->loan_date->format('d/m/Y H:i') }}</td>
                        <td class="p-3">
                            <div class="font-medium">{{ $loan->patron->display_name ?? $loan->patron->user->name }}</div>
                            <div class="text-xs text-gray-400">{{ $loan->patron->patron_code }}</div>
                        </td>
                        <td class="p-3">
                            <div class="font-medium">{{ $loan->bookItem->bibliographicRecord->title ?? 'N/A' }}</div>
                            <div class="text-xs text-gray-400 font-mono">{{ $loan->bookItem->barcode }}</div>
                        </td>
                        <td class="p-3 {{ $loan->isOverdue() ? 'text-red-400' : '' }}">
                            {{ $loan->due_date->format('d/m/Y') }}
                        </td>
                        <td class="p-3">
                            @php
                                $statusClass = match($loan->status) {
                                    'borrowed' => 'bg-yellow-900/50 text-yellow-400',
                                    'returned' => 'bg-green-900/50 text-green-400',
                                    'overdue' => 'bg-red-900/50 text-red-400',
                                    'lost' => 'bg-gray-900/50 text-gray-400',
                                    default => 'bg-gray-900/50 text-gray-400'
                                };
                            @endphp
                            <span class="px-2 py-1 rounded text-xs font-bold {{ $statusClass }}">
                                {{ __(ucfirst($loan->status)) }}
                            </span>
                        </td>
                        <td class="p-3">
                            @if($loan->status === 'borrowed')
                                @if($loan->canRenew())
                                <form action="{{ route('admin.circulation.renew', $loan) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-blue-400 hover:text-blue-300 text-xs mr-2">
                                        {{ __('Renew') }}
                                    </button>
                                </form>
                                @endif
                                <span class="text-xs text-gray-500">({{ $loan->renewal_count }}/{{ $loan->policy->max_renewals ?? '?' }})</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="p-8 text-center text-gray-500">{{ __('No_recent_transactions') }}</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    .input-field { background: #111827; border: 1px solid #374151; border-radius: 0.375rem; padding: 0.5rem 0.75rem; color: #fff; font-size: 0.875rem; }
    .input-field:focus { outline: none; border-color: #3b82f6; }
    .btn-secondary { background: #374151; color: #fff; padding: 0.5rem 1rem; border-radius: 0.375rem; font-size: 0.875rem; font-weight: 500; }
    .btn-secondary:hover { background: #4b5563; }
    .card-admin { background: #1f2937; border: 1px solid #374151; }
</style>
@endsection
