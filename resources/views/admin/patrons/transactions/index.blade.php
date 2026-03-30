@extends('layouts.admin')

@section('title', __('Giao dịch tài chính'))

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 dark:text-slate-100 tracking-tight">{{ __('Giao dịch tài chính') }}</h1>
            <p class="text-slate-500 dark:text-slate-400 text-sm font-medium mt-1">{{ __('Quản lý giao dịch tài chính của độc giả') }}: {{ $patron->display_name }}</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.patrons.index') }}" class="bg-gray-100 hover:bg-gray-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-gray-600 dark:text-slate-400 px-6 py-2.5 rounded-xl font-medium transition">
                {{ __('Quay lại') }}
            </a>
            <button type="button" onclick="openTransactionModal({{ json_encode(['id' => $patron->id, 'name' => $patron->display_name, 'balance' => $patron->balance]) }})" class="bg-indigo-600 text-white px-6 py-2.5 rounded-xl font-medium shadow-md hover:bg-indigo-500">
                {{ __('Thêm giao dịch') }}
            </button>
        </div>
    </div>

    <!-- Patron Info Card -->
    <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-gray-100 dark:border-slate-800 p-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="w-16 h-16 bg-slate-100 dark:bg-slate-800 rounded-full overflow-hidden">
                    @if($patron->profile_image)
                        <img src="{{ asset('storage/' . $patron->profile_image) }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center">
                            <svg class="w-8 h-8 text-slate-400" fill="currentColor" viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                        </div>
                    @endif
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-slate-100">{{ $patron->display_name }}</h3>
                    <p class="text-sm text-gray-500 dark:text-slate-400">{{ $patron->patron_code }}</p>
                    <p class="text-sm text-gray-500 dark:text-slate-400">{{ $patron->user->email }}</p>
                </div>
            </div>
            <div class="text-right">
                <p class="text-sm text-gray-500 dark:text-slate-400">{{ __('Số dư hiện tại') }}</p>
                <p class="text-2xl font-bold {{ $patron->balance >= 0 ? 'text-green-600' : 'text-red-600' }}">
                    {{ number_format($patron->balance, 0, ',', '.') }} VNĐ
                </p>
            </div>
        </div>
    </div>

    <!-- Transactions List -->
    <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-gray-100 dark:border-slate-800 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-slate-800">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-slate-400 uppercase tracking-wider">{{ __('Ngày') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-slate-400 uppercase tracking-wider">{{ __('Loại') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-slate-400 uppercase tracking-wider">{{ __('Số tiền') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-slate-400 uppercase tracking-wider">{{ __('Số dư trước') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-slate-400 uppercase tracking-wider">{{ __('Số dư sau') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-slate-400 uppercase tracking-wider">{{ __('Mô tả') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-slate-400 uppercase tracking-wider">{{ __('Người thực hiện') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-slate-700">
                    @forelse($transactions as $transaction)
                        <tr class="hover:bg-gray-50 dark:hover:bg-slate-800">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-slate-100">
                                {{ $transaction->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $transaction->isCredit() ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }}">
                                    {{ $transaction->type_label }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium {{ $transaction->isCredit() ? 'text-green-600' : 'text-red-600' }}">
                                {{ $transaction->isCredit() ? '+' : '-' }} {{ number_format($transaction->amount, 0, ',', '.') }} VNĐ
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-slate-100">
                                {{ number_format($transaction->balance_before, 0, ',', '.') }} VNĐ
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-slate-100">
                                {{ number_format($transaction->balance_after, 0, ',', '.') }} VNĐ
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-slate-100">
                                <div>
                                    <p class="font-medium">{{ $transaction->description }}</p>
                                    @if($transaction->notes)
                                        <p class="text-xs text-gray-500 dark:text-slate-400">{{ $transaction->notes }}</p>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-slate-100">
                                {{ $transaction->createdBy->name ?? 'System' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <p class="text-gray-500 dark:text-slate-400">{{ __('Chưa có giao dịch nào') }}</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if($transactions->hasPages())
        <div class="mt-6">
            {{ $transactions->links() }}
        </div>
    @endif
</div>

<!-- Include Transaction Modal -->
@include('admin.patrons.modals')
@endsection
