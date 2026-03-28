@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <!-- Search and Filters Section -->
    <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-gray-100 dark:border-slate-800 p-6">
        <h2 class="text-lg font-semibold text-gray-800 dark:text-slate-100 mb-4">{{ __('Search & Filters') }}</h2>
        
        <form method="GET" action="{{ route('admin.patrons.index') }}" class="space-y-4">
            <!-- Search Bar -->
            <div class="flex flex-col md:flex-row gap-4">
                <div class="flex-1">
                    <div class="relative">
                        <input type="text" 
                               name="search" 
                               value="{{ $search ?? '' }}" 
                               placeholder="{{ __('Search patrons...') }}" 
                               class="w-full pl-10 pr-4 py-2.5 border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 dark:text-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <svg class="absolute left-3 top-2.5 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                </div>
                
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-lg text-sm font-semibold transition">
                    {{ __('Search') }}
                </button>
                
                <a href="{{ route('admin.patrons.index') }}" class="bg-gray-100 hover:bg-gray-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-gray-600 dark:text-slate-400 px-6 py-2.5 rounded-lg text-sm font-semibold transition">
                    {{ __('Clear') }}
                </a>
            </div>

            <!-- Advanced Filters -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-1">{{ __('Status') }}</label>
                    <select name="status" class="w-full px-4 py-2.5 border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 dark:text-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="all" {{ ($status ?? 'all') == 'all' ? 'selected' : '' }}>{{ __('All Status') }}</option>
                        <option value="active" {{ ($status ?? '') == 'active' ? 'selected' : '' }}>{{ __('Active') }}</option>
                        <option value="locked" {{ ($status ?? '') == 'locked' ? 'selected' : '' }}>{{ __('Locked') }}</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-1">{{ __('Patron Group') }}</label>
                    <select name="patron_group" class="w-full px-4 py-2.5 border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 dark:text-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="all" {{ ($patronGroup ?? 'all') == 'all' ? 'selected' : '' }}>{{ __('All Groups') }}</option>
                        @if(isset($patronGroups))
                            @foreach($patronGroups as $group)
                                <option value="{{ $group->id }}" {{ ($patronGroup ?? '') == $group->id ? 'selected' : '' }}>{{ $group->name }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
            </div>
        </form>
    </div>

    <!-- Bulk Actions Section -->
    <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-gray-100 dark:border-slate-800 p-6" id="bulkActionsSection" style="display: none;">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <span class="text-sm font-medium text-gray-700 dark:text-slate-300">
                    <span id="selectedCount">0</span> {{ __('patrons selected') }}
                </span>
                <button type="button" onclick="clearSelection()" class="text-sm text-gray-500 hover:text-gray-700 dark:text-slate-400 dark:hover:text-slate-200">
                    {{ __('Clear selection') }}
                </button>
            </div>
            <div class="flex items-center space-x-3">
                <!-- Print Cards Button -->
                <form method="POST" action="{{ route('admin.patrons.cards.generate') }}" class="inline">
                    @csrf
                    <input type="hidden" name="layout" value="batch">
                    <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm font-medium transition-colors">
                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                        </svg>
                        {{ __('Print Cards') }}
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Results Display -->
    @php
        $query = \App\Models\PatronDetail::query();
        if(request('search')) {
            $query->where(function($q) {
                $q->where('display_name', 'like', '%'.request('search').'%')
                  ->orWhere('patron_code', 'like', '%'.request('search').'%')
                  ->orWhereHas('user', function($subQ) {
                      $subQ->where('email', 'like', '%'.request('search').'%');
                  });
            });
        }
        if(request('patron_group_id')) {
            $query->where('patron_group_id', request('patron_group_id'));
        }
        if(request('status')) {
            $query->where('card_status', request('status') == 'active' ? 'normal' : 'locked');
        }
        $patrons = $query->with(['user', 'patronGroup'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);
    @endphp

    @if(isset($patrons) && $patrons->count() > 0)
        <!-- List View -->
        <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-gray-100 dark:border-slate-800 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 dark:bg-slate-800">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-slate-400 uppercase tracking-wider">
                                <input type="checkbox" class="w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-slate-400 uppercase tracking-wider">{{ __('Patron') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-slate-400 uppercase tracking-wider">{{ __('Code') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-slate-400 uppercase tracking-wider">{{ __('Email') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-slate-400 uppercase tracking-wider">{{ __('Group') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-slate-400 uppercase tracking-wider">{{ __('Status') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-slate-400 uppercase tracking-wider">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-slate-700">
                        @forelse($patrons as $patron)
                            <tr class="hover:bg-gray-50 dark:hover:bg-slate-800 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <input type="checkbox" name="selected_patrons[]" value="{{ $patron->user_id }}" class="w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-slate-100 dark:bg-slate-800 rounded-full overflow-hidden mr-3">
                                            @if($patron->profile_image)
                                                <img src="{{ asset('storage/' . $patron->profile_image) }}" class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center">
                                                    <svg class="w-5 h-5 text-slate-400" fill="currentColor" viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900 dark:text-slate-100">{{ $patron->display_name }}</div>
                                            <div class="text-xs text-gray-500 dark:text-slate-400">{{ $patron->user->email ?? '' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 dark:text-slate-100 font-mono">{{ $patron->patron_code }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 dark:text-slate-100">{{ $patron->user->email ?? '' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 dark:text-slate-100">{{ $patron->patronGroup->name ?? '-' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $patron->card_status == 'normal' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }}">
                                        <span class="w-2 h-2 mr-1.5 rounded-full {{ $patron->card_status == 'normal' ? 'bg-green-400' : 'bg-red-400' }}"></span>
                                        {{ $patron->card_status == 'normal' ? __('Active') : __('Locked') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center space-x-2">
                                        <!-- Preview Card -->
                                        <a href="{{ route('admin.patrons.cards.preview', $patron->user_id) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300" title="{{ __('Preview Card') }}">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center">
                                    <p class="text-gray-500 dark:text-slate-400">{{ __('No patrons found.') }}</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        @if(isset($patrons))
            <div class="mt-6">
                {{ $patrons->links() }}
            </div>
        @endif
    @else
        <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-gray-100 dark:border-slate-800 p-12 text-center">
            <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <h3 class="text-lg font-medium text-gray-900 dark:text-slate-100 mb-2">{{ __('No patrons found') }}</h3>
            <p class="text-gray-500 dark:text-slate-400">{{ __('Try adjusting your search criteria or filters.') }}</p>
        </div>
    @endif
</div>

<!-- Include Bulk Edit Modal -->
@include('admin.patrons.bulk-edit')

<script>
// Bulk Actions JavaScript
let selectedPatrons = [];

function updateBulkActions() {
    const checkboxes = document.querySelectorAll('input[name="selected_patrons[]"]:checked');
    selectedPatrons = Array.from(checkboxes).map(cb => cb.value);
    
    console.log('updateBulkActions - selectedPatrons:', selectedPatrons);
    
    const bulkActionsSection = document.getElementById('bulkActionsSection');
    const selectedCount = document.getElementById('selectedCount');
    
    if (selectedPatrons.length > 0) {
        bulkActionsSection.style.display = 'block';
        selectedCount.textContent = selectedPatrons.length;
        
        // Find the print cards form and add patron IDs
        const printForm = document.querySelector('form[action*="cards/generate"]');
        if (printForm) {
            console.log('updateBulkActions - Found print form:', printForm);
            
            // Remove existing patron ID inputs
            const existingInputs = printForm.querySelectorAll('input[name="patron_ids[]"]');
            existingInputs.forEach(input => input.remove());
            
            // Add new patron ID inputs
            selectedPatrons.forEach(id => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'patron_ids[]';
                input.value = id;
                printForm.appendChild(input);
                console.log('updateBulkActions - Added input for patron ID:', id);
            });
            
            // Log all form data before submission
            printForm.addEventListener('submit', function(e) {
                const formData = new FormData(this);
                const data = {};
                for (let [key, value] of formData.entries()) {
                    data[key] = value;
                }
                console.log('Form submission data:', data);
            });
        } else {
            console.error('updateBulkActions - Print form not found!');
        }
    } else {
        bulkActionsSection.style.display = 'none';
    }
}

function clearSelection() {
    const checkboxes = document.querySelectorAll('input[name="selected_patrons[]"]');
    checkboxes.forEach(cb => cb.checked = false);
    updateBulkActions();
}

// Add event listeners to checkboxes
document.addEventListener('DOMContentLoaded', function() {
    const checkboxes = document.querySelectorAll('input[name="selected_patrons[]"]');
    checkboxes.forEach(cb => {
        cb.addEventListener('change', updateBulkActions);
    });
    
    // Select all functionality
    const selectAllCheckbox = document.querySelector('input[type="checkbox"]:not([name="selected_patrons[]"])');
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('input[name="selected_patrons[]"]');
            checkboxes.forEach(cb => cb.checked = this.checked);
            updateBulkActions();
        });
    }
});

function openBulkEditModal() {
    if (selectedPatrons.length === 0) {
        alert('Please select at least one patron to edit.');
        return;
    }
    
    // Set patron IDs
    document.getElementById('bulkEditPatronIds').value = selectedPatrons.join(',');
    document.getElementById('selectedPatronsCount').textContent = selectedPatrons.length;
    
    // Show modal
    document.getElementById('bulkEditModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeBulkEditModal() {
    document.getElementById('bulkEditModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}
</script>
@endsection
