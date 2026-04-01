@extends('layouts.admin')

@section('content')
<div class="space-y-8">
<div class="circulation-page">
    {{-- Success/Error Messages --}}
    @if(session('success'))
        <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-500 text-green-800 dark:text-green-400 p-4 text-xs font-mono rounded mb-6">
            [OK] {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-500 text-red-800 dark:text-red-400 p-4 text-xs font-mono rounded mb-6">
            [ERROR] {{ session('error') }}
        </div>
    @endif

    {{-- Page Header --}}
    <div class="page-header">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h1 class="page-title">{{ __('Circulation_Policies') }}</h1>
                <p class="page-subtitle">{{ __('Manage_patron_groups_and_loan_policies') }}</p>
            </div>
            <div class="flex gap-2">
                <button onclick="openModal('addPatronGroupModal')" class="btn-primary">
                    {{ __('Add_Patron_Group') }}
                </button>
                <a href="{{ route('admin.circulation.loan-desk') }}" class="btn-secondary">
                    {{ __('Loan_Desk') }}
                </a>
            </div>
        </div>
    </div>

    <!-- Patron Groups & Policies -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        @forelse($patronGroups as $group)
        <div class="card-admin rounded-lg overflow-hidden">
            <!-- Group Header -->
            <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                <div>
                    <h3 class="font-bold text-lg">{{ $group->name }}</h3>
                    <span class="text-xs text-gray-500 dark:text-gray-400 font-mono">{{ $group->code }}</span>
                </div>
                <div class="flex gap-2">
                    <button onclick="editPatronGroup({{ json_encode($group) }})" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 text-sm">
                        {{ __('Edit') }}
                    </button>
                    <form action="{{ route('admin.circulation.patron-groups.destroy', $group) }}" method="POST" class="inline"
                        onsubmit="return confirm('{{ __('Delete_this_patron_group?') }}')">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 text-sm">{{ __('Delete') }}</button>
                    </form>
                </div>
            </div>

            <!-- Policies List -->
            <div class="p-4">
                @if($group->description)
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">{{ $group->description }}</p>
                @endif

                <div class="flex justify-between items-center mb-3">
                    <h4 class="text-sm font-bold text-gray-700 dark:text-gray-300">{{ __('Policies') }}</h4>
                    <button onclick="openAddPolicyModal({{ $group->id }}, '{{ $group->name }}')" 
                        class="text-xs text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">
                        + {{ __('Add_Policy') }}
                    </button>
                </div>

                @forelse($group->circulationPolicies as $policy)
                <div class="policy-card {{ $policy->is_active ? 'active' : '' }}">
                    <div class="flex justify-between items-start">
                        <div>
                            <span class="font-medium text-lg">{{ $policy->name }}</span>
                            @if($policy->is_active)
                                <span class="ml-2 text-xs bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-400 px-2 py-0.5 rounded">{{ __('Active') }}</span>
                            @endif
                        </div>
                        <div class="flex gap-2">
                            <button onclick="editPolicy({{ json_encode($policy) }})" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 text-sm transition-colors">
                                {{ __('Edit') }}
                            </button>
                            <form action="{{ route('admin.circulation.policies.destroy', $policy) }}" method="POST" class="inline"
                                onsubmit="return confirm('{{ __('Delete_this_policy?') }}')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 text-sm transition-colors">{{ __('Delete') }}</button>
                            </form>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mt-4 text-sm">
                        <div>
                            <span class="block text-gray-600 dark:text-gray-500">{{ __('Loan_Days') }}</span>
                            <span class="font-mono font-semibold">{{ $policy->max_loan_days }}</span>
                        </div>
                        <div>
                            <span class="block text-gray-600 dark:text-gray-500">{{ __('Max_Items') }}</span>
                            <span class="font-mono font-semibold">{{ $policy->max_items }}</span>
                        </div>
                        <div>
                            <span class="block text-gray-600 dark:text-gray-500">{{ __('Fine/Day') }}</span>
                            <span class="font-mono font-semibold">{{ number_format($policy->fine_per_day) }}đ</span>
                        </div>
                        <div>
                            <span class="block text-gray-600 dark:text-gray-500">{{ __('Renewals') }}</span>
                            <span class="font-mono font-semibold">{{ $policy->max_renewals }}</span>
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                    <p class="text-lg">{{ __('No_policies_defined') }}</p>
                    <button onclick="openAddPolicyModal({{ $group->id }}, '{{ $group->name }}')" 
                        class="mt-4 btn-primary">
                        {{ __('Add_Policy') }}
                    </button>
                </div>
                @endforelse
            </div>
        </div>
        @empty
        <div class="col-span-2 text-center py-12 text-gray-500 dark:text-gray-400">
            <p>{{ __('No_patron_groups_found') }}</p>
            <button onclick="openModal('addPatronGroupModal')" class="mt-4 btn-primary">
                {{ __('Create_First_Group') }}
            </button>
        </div>
        @endforelse
    </div>
</div>

<!-- Add Patron Group Modal -->
<div id="addPatronGroupModal" class="modal hidden">
    <div class="modal-backdrop" onclick="closeModal('addPatronGroupModal')"></div>
    <div class="modal-content">
        <h3 class="text-lg font-bold mb-4">{{ __('Add_Patron_Group') }}</h3>
        <form action="{{ route('admin.circulation.patron-groups.store') }}" method="POST">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-1">{{ __('Group_Name') }} *</label>
                    <input type="text" name="name" required class="input-field w-full" placeholder="{{ __('e.g._Students') }}">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">{{ __('Code') }} *</label>
                    <input type="text" name="code" required class="input-field w-full" placeholder="{{ __('e.g._SV') }}">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">{{ __('Description') }}</label>
                    <textarea name="description" class="input-field w-full" rows="2"></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">{{ __('Order') }}</label>
                    <input type="number" name="order" value="0" class="input-field w-full">
                </div>
            </div>
            <div class="flex justify-end gap-2 mt-6">
                <button type="button" onclick="closeModal('addPatronGroupModal')" class="btn-secondary">{{ __('Cancel') }}</button>
                <button type="submit" class="btn-primary">{{ __('Create') }}</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Patron Group Modal -->
<div id="editPatronGroupModal" class="modal hidden">
    <div class="modal-backdrop" onclick="closeModal('editPatronGroupModal')"></div>
    <div class="modal-content">
        <h3 class="text-lg font-bold mb-4">{{ __('Edit_Patron_Group') }}</h3>
        <form id="editPatronGroupForm" method="POST">
            @csrf @method('PUT')
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-1">{{ __('Group_Name') }} *</label>
                    <input type="text" name="name" id="editGroupName" required class="input-field w-full">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">{{ __('Code') }} *</label>
                    <input type="text" name="code" id="editGroupCode" required class="input-field w-full">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">{{ __('Description') }}</label>
                    <textarea name="description" id="editGroupDescription" class="input-field w-full" rows="2"></textarea>
                </div>
                <div class="flex items-center gap-4">
                    <div class="flex-1">
                        <label class="block text-sm font-medium mb-1">{{ __('Order') }}</label>
                        <input type="number" name="order" id="editGroupOrder" class="input-field w-full">
                    </div>
                    <div class="flex items-center gap-2 pt-6">
                        <input type="checkbox" name="is_active" id="editGroupActive" value="1" class="rounded">
                        <label for="editGroupActive" class="text-sm">{{ __('Active') }}</label>
                    </div>
                </div>
            </div>
            <div class="flex justify-end gap-2 mt-6">
                <button type="button" onclick="closeModal('editPatronGroupModal')" class="btn-secondary">{{ __('Cancel') }}</button>
                <button type="submit" class="btn-primary">{{ __('Update') }}</button>
            </div>
        </form>
    </div>
</div>

<!-- Add Policy Modal -->
<div id="addPolicyModal" class="modal hidden">
    <div class="modal-backdrop" onclick="closeModal('addPolicyModal')"></div>
    <div class="modal-content max-w-2xl">
        <h3 class="text-lg font-bold mb-4">{{ __('Add_Circulation_Policy') }}</h3>
        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">{{ __('For_group') }}: <span id="policyGroupName" class="font-bold"></span></p>
        <form action="{{ route('admin.circulation.policies.store') }}" method="POST">
            @csrf
            <input type="hidden" name="patron_group_id" id="policyGroupId">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium mb-1">{{ __('Policy_Name') }} *</label>
                    <input type="text" name="name" required class="input-field w-full" placeholder="{{ __('e.g._Standard_Loan') }}">
                </div>
                
                <!-- Loan Settings -->
                <div class="md:col-span-2 border-t border-gray-200 dark:border-gray-700 pt-4 mt-2">
                    <h4 class="text-sm font-bold text-gray-700 dark:text-gray-300 mb-3">{{ __('Loan_Settings') }}</h4>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">{{ __('Max_Loan_Days') }} *</label>
                    <input type="number" name="max_loan_days" value="14" required min="1" max="365" class="input-field w-full">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">{{ __('Max_Items') }} *</label>
                    <input type="number" name="max_items" value="5" required min="1" max="100" class="input-field w-full">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">{{ __('Max_Renewals') }} *</label>
                    <input type="number" name="max_renewals" value="2" required min="0" max="10" class="input-field w-full">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">{{ __('Renewal_Days') }} *</label>
                    <input type="number" name="renewal_days" value="7" required min="1" max="365" class="input-field w-full">
                </div>

                <!-- Fine Settings -->
                <div class="md:col-span-2 border-t border-gray-200 dark:border-gray-700 pt-4 mt-2">
                    <h4 class="text-sm font-bold text-gray-700 dark:text-gray-300 mb-3">{{ __('Fine_Settings') }}</h4>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">{{ __('Fine_Per_Day') }} (VND) *</label>
                    <input type="number" name="fine_per_day" value="1000" required min="0" class="input-field w-full">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">{{ __('Max_Fine') }} (VND) *</label>
                    <input type="number" name="max_fine" value="100000" required min="0" class="input-field w-full">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">{{ __('Grace_Period_Days') }} *</label>
                    <input type="number" name="grace_period_days" value="0" required min="0" max="30" class="input-field w-full">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">{{ __('Max_Outstanding_Fine') }} (VND) *</label>
                    <input type="number" name="max_outstanding_fine" value="50000" required min="0" class="input-field w-full">
                </div>

                <!-- Reservation Settings -->
                <div class="md:col-span-2 border-t border-gray-200 dark:border-gray-700 pt-4 mt-2">
                    <h4 class="text-sm font-bold text-gray-700 dark:text-gray-300 mb-3">{{ __('Reservation_Settings') }}</h4>
                </div>
                <div class="flex items-center gap-2">
                    <input type="checkbox" name="can_reserve" id="canReserve" value="1" checked class="rounded">
                    <label for="canReserve" class="text-sm">{{ __('Can_Reserve') }}</label>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">{{ __('Max_Reservations') }} *</label>
                    <input type="number" name="max_reservations" value="3" required min="0" max="20" class="input-field w-full">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">{{ __('Reservation_Hold_Days') }} *</label>
                    <input type="number" name="reservation_hold_days" value="3" required min="1" max="30" class="input-field w-full">
                </div>
                <div class="flex items-center gap-2">
                    <input type="checkbox" name="is_active" id="policyActive" value="1" checked class="rounded">
                    <label for="policyActive" class="text-sm">{{ __('Set_as_Active_Policy') }}</label>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium mb-1">{{ __('Notes') }}</label>
                    <textarea name="notes" class="input-field w-full" rows="2"></textarea>
                </div>
            </div>
            
            <div class="flex justify-end gap-2 mt-6">
                <button type="button" onclick="closeModal('addPolicyModal')" class="btn-secondary">{{ __('Cancel') }}</button>
                <button type="submit" class="btn-primary">{{ __('Create_Policy') }}</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Policy Modal -->
<div id="editPolicyModal" class="modal hidden">
    <div class="modal-backdrop" onclick="closeModal('editPolicyModal')"></div>
    <div class="modal-content max-w-2xl">
        <h3 class="text-lg font-bold mb-4">{{ __('Edit_Circulation_Policy') }}</h3>
        <form id="editPolicyForm" method="POST">
            @csrf @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium mb-1">{{ __('Policy_Name') }} *</label>
                    <input type="text" name="name" id="editPolicyName" required class="input-field w-full">
                </div>
                
                <!-- Loan Settings -->
                <div class="md:col-span-2 border-t border-gray-200 dark:border-gray-700 pt-4 mt-2">
                    <h4 class="text-sm font-bold text-gray-700 dark:text-gray-300 mb-3">{{ __('Loan_Settings') }}</h4>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">{{ __('Max_Loan_Days') }} *</label>
                    <input type="number" name="max_loan_days" id="editMaxLoanDays" required min="1" max="365" class="input-field w-full">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">{{ __('Max_Items') }} *</label>
                    <input type="number" name="max_items" id="editMaxItems" required min="1" max="100" class="input-field w-full">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">{{ __('Max_Renewals') }} *</label>
                    <input type="number" name="max_renewals" id="editMaxRenewals" required min="0" max="10" class="input-field w-full">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">{{ __('Renewal_Days') }} *</label>
                    <input type="number" name="renewal_days" id="editRenewalDays" required min="1" max="365" class="input-field w-full">
                </div>

                <!-- Fine Settings -->
                <div class="md:col-span-2 border-t border-gray-200 dark:border-gray-700 pt-4 mt-2">
                    <h4 class="text-sm font-bold text-gray-700 dark:text-gray-300 mb-3">{{ __('Fine_Settings') }}</h4>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">{{ __('Fine_Per_Day') }} (VND) *</label>
                    <input type="number" name="fine_per_day" id="editFinePerDay" required min="0" class="input-field w-full">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">{{ __('Max_Fine') }} (VND) *</label>
                    <input type="number" name="max_fine" id="editMaxFine" required min="0" class="input-field w-full">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">{{ __('Grace_Period_Days') }} *</label>
                    <input type="number" name="grace_period_days" id="editGracePeriod" required min="0" max="30" class="input-field w-full">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">{{ __('Max_Outstanding_Fine') }} (VND) *</label>
                    <input type="number" name="max_outstanding_fine" id="editMaxOutstandingFine" required min="0" class="input-field w-full">
                </div>

                <!-- Reservation Settings -->
                <div class="md:col-span-2 border-t border-gray-200 dark:border-gray-700 pt-4 mt-2">
                    <h4 class="text-sm font-bold text-gray-700 dark:text-gray-300 mb-3">{{ __('Reservation_Settings') }}</h4>
                </div>
                <div class="flex items-center gap-2">
                    <input type="checkbox" name="can_reserve" id="editCanReserve" value="1" class="rounded">
                    <label for="editCanReserve" class="text-sm">{{ __('Can_Reserve') }}</label>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">{{ __('Max_Reservations') }} *</label>
                    <input type="number" name="max_reservations" id="editMaxReservations" required min="0" max="20" class="input-field w-full">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">{{ __('Reservation_Hold_Days') }} *</label>
                    <input type="number" name="reservation_hold_days" id="editReservationHoldDays" required min="1" max="30" class="input-field w-full">
                </div>
                <div class="flex items-center gap-2">
                    <input type="checkbox" name="is_active" id="editPolicyActive" value="1" class="rounded">
                    <label for="editPolicyActive" class="text-sm">{{ __('Set_as_Active_Policy') }}</label>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium mb-1">{{ __('Notes') }}</label>
                    <textarea name="notes" id="editPolicyNotes" class="input-field w-full" rows="2"></textarea>
                </div>
            </div>
            
            <div class="flex justify-end gap-2 mt-6">
                <button type="button" onclick="closeModal('editPolicyModal')" class="btn-secondary">{{ __('Cancel') }}</button>
                <button type="submit" class="btn-primary">{{ __('Update_Policy') }}</button>
            </div>
        </form>
    </div>
</div>

@push('styles')
<style>
/* ════════════════════════════════════════════════
   CIRCULATION THEME - DARK & LIGHT MODE SUPPORT
════════════════════════════════════════════════ */

/* Root CSS Variables for Light Theme (Default) */
:root {
    --clr-bg: #f8fafc;
    --clr-bg-secondary: #ffffff;
    --clr-bg-tertiary: #f1f5f9;
    --clr-bg-card: #ffffff;
    --clr-text-primary: #1e293b;
    --clr-text-secondary: #475569;
    --clr-text-muted: #64748b;
    --clr-border: #e2e8f0;
    --clr-border-light: #cbd5e1;
    --clr-accent: #3b82f6;
    --clr-accent-hover: #2563eb;
    --clr-success: #10b981;
    --clr-warning: #f59e0b;
    --clr-error: #ef4444;
    --clr-gradient-start: #f8fafc;
    --clr-gradient-end: #f1f5f9;
    --shadow-primary: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    --shadow-secondary: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
}

/* Dark Theme Variables */
.dark {
    --clr-bg: #0a0a0a;
    --clr-bg-secondary: #111111;
    --clr-bg-tertiary: #1a1a1a;
    --clr-bg-card: #1f1f1f;
    --clr-text-primary: #ffffff;
    --clr-text-secondary: #e5e5e5;
    --clr-text-muted: #9ca3af;
    --clr-border: #2a2a2a;
    --clr-border-light: #3a3a3a;
    --clr-accent: #3b82f6;
    --clr-accent-hover: #2563eb;
    --clr-success: #10b981;
    --clr-warning: #f59e0b;
    --clr-error: #ef4444;
    --clr-gradient-start: #1a1a1a;
    --clr-gradient-end: #0a0a0a;
    --shadow-primary: 0 4px 6px -1px rgba(0, 0, 0, 0.5);
    --shadow-secondary: 0 10px 15px -3px rgba(0, 0, 0, 0.5);
}

/* Page Container */
.circulation-page {
    min-height: 100vh;
    background: linear-gradient(135deg, var(--clr-gradient-start) 0%, var(--clr-gradient-end) 100%);
    color: var(--clr-text-primary);
    padding: 2rem;
}

/* Page Header */
.page-header {
    margin-bottom: 2rem;
}

.page-title {
    font-size: 2.5rem;
    font-weight: 800;
    background: linear-gradient(135deg, var(--clr-accent) 0%, var(--clr-accent-hover) 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    margin-bottom: 0.5rem;
    letter-spacing: -0.025em;
}

.page-subtitle {
    color: var(--clr-text-muted);
    font-size: 1.125rem;
    font-weight: 400;
}

/* Cards */
.card-admin {
    background: var(--clr-bg-card);
    border: 1px solid var(--clr-border);
    box-shadow: var(--shadow-primary);
    transition: all 0.3s ease;
}

.card-admin:hover {
    box-shadow: var(--shadow-secondary);
    transform: translateY(-2px);
}

/* Buttons */
.btn-primary {
    background: linear-gradient(135deg, var(--clr-accent) 0%, var(--clr-accent-hover) 100%);
    color: #ffffff;
    padding: 0.75rem 1.5rem;
    border-radius: 0.5rem;
    font-size: 0.875rem;
    font-weight: 600;
    border: none;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: var(--shadow-primary);
}

.btn-primary:hover {
    transform: translateY(-1px);
    box-shadow: var(--shadow-secondary);
}

.btn-secondary {
    background: var(--clr-bg-tertiary);
    color: var(--clr-text-secondary);
    padding: 0.75rem 1.5rem;
    border-radius: 0.5rem;
    font-size: 0.875rem;
    font-weight: 600;
    border: 1px solid var(--clr-border);
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-secondary:hover {
    background: var(--clr-bg-secondary);
    border-color: var(--clr-border-light);
}

/* Form Elements */
.input-field {
    background: var(--clr-bg-secondary);
    border: 1px solid var(--clr-border);
    border-radius: 0.375rem;
    padding: 0.75rem;
    color: var(--clr-text-primary);
    font-size: 0.875rem;
    transition: all 0.3s ease;
}

.input-field:focus {
    outline: none;
    border-color: var(--clr-accent);
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

/* Policy Cards */
.policy-card {
    background: var(--clr-bg-secondary);
    border: 1px solid var(--clr-border);
    border-radius: 0.5rem;
    padding: 1rem;
    margin-bottom: 0.5rem;
    transition: all 0.3s ease;
}

.policy-card:hover {
    background: var(--clr-bg-tertiary);
    border-color: var(--clr-border-light);
}

.policy-card.active {
    border-left: 4px solid var(--clr-success);
}

/* Modal Styles */
.modal {
    position: fixed;
    inset: 0;
    z-index: 50;
    display: flex;
    align-items: center;
    justify-content: center;
}

.modal.hidden {
    display: none;
}

.modal-backdrop {
    position: absolute;
    inset: 0;
    background: rgba(0, 0, 0, 0.8);
    backdrop-filter: blur(4px);
}

.modal-content {
    position: relative;
    background: var(--clr-bg-card);
    border: 1px solid var(--clr-border);
    border-radius: 0.75rem;
    padding: 2rem;
    max-width: 28rem;
    width: 100%;
    max-height: 90vh;
    overflow-y: auto;
    box-shadow: var(--shadow-secondary);
}

.modal-content.max-w-2xl {
    max-width: 42rem;
}

/* Responsive Design */
@media (max-width: 768px) {
    .circulation-page {
        padding: 1rem;
    }

    .page-title {
        font-size: 2rem;
    }

    .modal-content {
        padding: 1.5rem;
        margin: 1rem;
    }
}

/* Animations */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.card-admin {
    animation: fadeIn 0.5s ease-out;
}

.btn-primary, .btn-secondary {
    animation: fadeIn 0.3s ease-out;
}
</style>
@endpush

<script>
    function openModal(id) {
        document.getElementById(id).classList.remove('hidden');
    }
    
    function closeModal(id) {
        document.getElementById(id).classList.add('hidden');
    }

    function openAddPolicyModal(groupId, groupName) {
        document.getElementById('policyGroupId').value = groupId;
        document.getElementById('policyGroupName').textContent = groupName;
        openModal('addPolicyModal');
    }

    function editPatronGroup(group) {
        document.getElementById('editPatronGroupForm').action = `{{ route('admin.circulation.patron-groups.update', ['patronGroup' => ':id']) }}`.replace(':id', group.id);
        document.getElementById('editGroupName').value = group.name;
        document.getElementById('editGroupCode').value = group.code;
        document.getElementById('editGroupDescription').value = group.description || '';
        document.getElementById('editGroupOrder').value = group.order || 0;
        document.getElementById('editGroupActive').checked = group.is_active;
        openModal('editPatronGroupModal');
    }

    function editPolicy(policy) {
        document.getElementById('editPolicyForm').action = `{{ route('admin.circulation.policies.update', ['policy' => ':id']) }}`.replace(':id', policy.id);
        document.getElementById('editPolicyName').value = policy.name;
        document.getElementById('editMaxLoanDays').value = policy.max_loan_days;
        document.getElementById('editMaxItems').value = policy.max_items;
        document.getElementById('editMaxRenewals').value = policy.max_renewals;
        document.getElementById('editRenewalDays').value = policy.renewal_days;
        document.getElementById('editFinePerDay').value = policy.fine_per_day;
        document.getElementById('editMaxFine').value = policy.max_fine;
        document.getElementById('editGracePeriod').value = policy.grace_period_days;
        document.getElementById('editMaxOutstandingFine').value = policy.max_outstanding_fine;
        document.getElementById('editCanReserve').checked = policy.can_reserve;
        document.getElementById('editMaxReservations').value = policy.max_reservations;
        document.getElementById('editReservationHoldDays').value = policy.reservation_hold_days;
        document.getElementById('editPolicyActive').checked = policy.is_active;
        document.getElementById('editPolicyNotes').value = policy.notes || '';
        openModal('editPolicyModal');
    }
</script>
@endsection
