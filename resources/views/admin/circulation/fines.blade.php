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
            <h1 class="text-2xl font-bold">{{ __('Fines_Management') }}</h1>
            <p class="text-sm text-gray-400 mt-1">{{ __('Manage_patron_fines_and_payments') }}</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.circulation.index') }}" class="btn-secondary">
                {{ __('Policies') }}
            </a>
            <a href="{{ route('admin.circulation.loan-desk') }}" class="btn-secondary">
                {{ __('Loan_Desk') }}
            </a>
        </div>
    </div>

    <!-- Unpaid Fines Table -->
    <div class="card-admin rounded-lg overflow-hidden">
        <div class="p-4 border-b border-gray-700">
            <h3 class="text-lg font-bold">{{ __('Unpaid_Fines') }}</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-800 text-xs uppercase">
                    <tr>
                        <th class="p-3 text-left">{{ __('Date') }}</th>
                        <th class="p-3 text-left">{{ __('Patron') }}</th>
                        <th class="p-3 text-left">{{ __('Type') }}</th>
                        <th class="p-3 text-left">{{ __('Description') }}</th>
                        <th class="p-3 text-right">{{ __('Amount') }}</th>
                        <th class="p-3 text-right">{{ __('Paid') }}</th>
                        <th class="p-3 text-right">{{ __('Balance') }}</th>
                        <th class="p-3 text-left">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700">
                    @forelse($unpaidFines as $fine)
                    <tr class="hover:bg-gray-800/50">
                        <td class="p-3 text-xs text-gray-400">{{ $fine->created_at->format('d/m/Y') }}</td>
                        <td class="p-3">
                            <div class="font-medium">{{ $fine->patron->display_name ?? $fine->patron->user->name }}</div>
                            <div class="text-xs text-gray-400">{{ $fine->patron->patron_code }}</div>
                        </td>
                        <td class="p-3">
                            @php
                                $typeClass = match($fine->fine_type) {
                                    'overdue' => 'bg-yellow-900/50 text-yellow-400',
                                    'lost' => 'bg-red-900/50 text-red-400',
                                    'damaged' => 'bg-orange-900/50 text-orange-400',
                                    default => 'bg-gray-900/50 text-gray-400'
                                };
                            @endphp
                            <span class="px-2 py-1 rounded text-xs font-bold {{ $typeClass }}">
                                {{ __(ucfirst($fine->fine_type)) }}
                            </span>
                        </td>
                        <td class="p-3 text-xs text-gray-400">{{ $fine->description }}</td>
                        <td class="p-3 text-right font-mono">{{ number_format($fine->amount) }}đ</td>
                        <td class="p-3 text-right font-mono text-green-400">{{ number_format($fine->paid_amount) }}đ</td>
                        <td class="p-3 text-right font-mono text-red-400 font-bold">{{ number_format($fine->balance) }}đ</td>
                        <td class="p-3">
                            <button onclick="openPayModal({{ $fine->id }}, {{ $fine->balance }})" 
                                class="text-green-400 hover:text-green-300 text-xs mr-2">
                                {{ __('Pay') }}
                            </button>
                            <button onclick="openWaiveModal({{ $fine->id }}, {{ $fine->balance }})" 
                                class="text-yellow-400 hover:text-yellow-300 text-xs">
                                {{ __('Waive') }}
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="p-8 text-center text-gray-500">{{ __('No_unpaid_fines') }}</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($unpaidFines->hasPages())
        <div class="p-4 border-t border-gray-700">
            {{ $unpaidFines->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Pay Fine Modal -->
<div id="payFineModal" class="modal hidden">
    <div class="modal-backdrop" onclick="closeModal('payFineModal')"></div>
    <div class="modal-content">
        <h3 class="text-lg font-bold mb-4">{{ __('Record_Payment') }}</h3>
        <form id="payFineForm" method="POST">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-1">{{ __('Amount') }} (VND) *</label>
                    <input type="number" name="amount" id="payAmount" required min="1" class="input-field w-full">
                    <p class="text-xs text-gray-400 mt-1">{{ __('Max') }}: <span id="payMaxAmount">0</span>đ</p>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">{{ __('Payment_Method') }} *</label>
                    <select name="payment_method" required class="input-field w-full">
                        <option value="cash">{{ __('Cash') }}</option>
                        <option value="transfer">{{ __('Bank_Transfer') }}</option>
                        <option value="card">{{ __('Card') }}</option>
                    </select>
                </div>
            </div>
            <div class="flex justify-end gap-2 mt-6">
                <button type="button" onclick="closeModal('payFineModal')" class="btn-secondary">{{ __('Cancel') }}</button>
                <button type="submit" class="btn-primary bg-green-600 hover:bg-green-700">{{ __('Record_Payment') }}</button>
            </div>
        </form>
    </div>
</div>

<!-- Waive Fine Modal -->
<div id="waiveFineModal" class="modal hidden">
    <div class="modal-backdrop" onclick="closeModal('waiveFineModal')"></div>
    <div class="modal-content">
        <h3 class="text-lg font-bold mb-4">{{ __('Waive_Fine') }}</h3>
        <form id="waiveFineForm" method="POST">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-1">{{ __('Amount_to_Waive') }} (VND) *</label>
                    <input type="number" name="amount" id="waiveAmount" required min="1" class="input-field w-full">
                    <p class="text-xs text-gray-400 mt-1">{{ __('Max') }}: <span id="waiveMaxAmount">0</span>đ</p>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">{{ __('Reason') }}</label>
                    <textarea name="notes" class="input-field w-full" rows="2" placeholder="{{ __('Reason_for_waiving') }}"></textarea>
                </div>
            </div>
            <div class="flex justify-end gap-2 mt-6">
                <button type="button" onclick="closeModal('waiveFineModal')" class="btn-secondary">{{ __('Cancel') }}</button>
                <button type="submit" class="btn-primary bg-yellow-600 hover:bg-yellow-700">{{ __('Waive_Fine') }}</button>
            </div>
        </form>
    </div>
</div>

<style>
    .modal { position: fixed; inset: 0; z-index: 50; display: flex; align-items: center; justify-content: center; }
    .modal.hidden { display: none; }
    .modal-backdrop { position: absolute; inset: 0; background: rgba(0,0,0,0.8); }
    .modal-content { position: relative; background: #1f2937; border: 1px solid #374151; border-radius: 0.5rem; padding: 1.5rem; max-width: 28rem; width: 100%; }
    .input-field { background: #111827; border: 1px solid #374151; border-radius: 0.375rem; padding: 0.5rem 0.75rem; color: #fff; font-size: 0.875rem; }
    .input-field:focus { outline: none; border-color: #3b82f6; }
    .btn-primary { background: #3b82f6; color: #fff; padding: 0.5rem 1rem; border-radius: 0.375rem; font-size: 0.875rem; font-weight: 500; }
    .btn-secondary { background: #374151; color: #fff; padding: 0.5rem 1rem; border-radius: 0.375rem; font-size: 0.875rem; font-weight: 500; }
    .btn-secondary:hover { background: #4b5563; }
    .card-admin { background: #1f2937; border: 1px solid #374151; }
</style>

<script>
    function openModal(id) {
        document.getElementById(id).classList.remove('hidden');
    }
    
    function closeModal(id) {
        document.getElementById(id).classList.add('hidden');
    }

    function openPayModal(fineId, balance) {
        document.getElementById('payFineForm').action = '/topsecret/circulation/fines/' + fineId + '/pay';
        document.getElementById('payAmount').max = balance;
        document.getElementById('payAmount').value = balance;
        document.getElementById('payMaxAmount').textContent = balance.toLocaleString();
        openModal('payFineModal');
    }

    function openWaiveModal(fineId, balance) {
        document.getElementById('waiveFineForm').action = '/topsecret/circulation/fines/' + fineId + '/waive';
        document.getElementById('waiveAmount').max = balance;
        document.getElementById('waiveAmount').value = balance;
        document.getElementById('waiveMaxAmount').textContent = balance.toLocaleString();
        openModal('waiveFineModal');
    }
</script>
@endsection
