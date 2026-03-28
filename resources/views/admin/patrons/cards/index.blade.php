@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-800 p-6 transition-colors">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-900 dark:text-slate-100">In Thẻ Độc Giả</h1>
                <p class="text-slate-600 dark:text-slate-400 mt-1">In thẻ độc giả đơn lẻ hoặc hàng loạt</p>
            </div>
            <div class="flex space-x-3">
                @if(request()->has('patron_ids'))
                    <a href="{{ route('admin.patrons.cards.index') }}" class="px-4 py-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">
                        Chọn lại
                    </a>
                @endif
            </div>
        </div>
    </div>

    @if(request()->has('patron_ids'))
        <!-- Selected Patrons -->
        <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-800 p-6 transition-colors">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-slate-900 dark:text-slate-100">
                    Độc giả đã chọn ({{ $selectedPatrons->count() }})
                </h2>
                <form method="POST" action="{{ route('admin.patrons.cards.generate') }}">
                    @csrf
                    @foreach(request('patron_ids') as $id)
                        <input type="hidden" name="patron_ids[]" value="{{ $id }}">
                    @endforeach
                    <input type="hidden" name="layout" value="batch">
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                        </svg>
                        In tất cả
                    </button>
                </form>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($selectedPatrons as $patron)
                    <div class="border border-slate-200 dark:border-slate-700 rounded-lg p-4 hover:shadow-md transition-shadow">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <h3 class="font-semibold text-slate-900 dark:text-slate-100">{{ $patron->name }}</h3>
                                <p class="text-sm text-slate-600 dark:text-slate-400">Mã: {{ $patron->patron_code }}</p>
                                <p class="text-sm text-slate-600 dark:text-slate-400">{{ $patron->patronGroup->name ?? 'Chưa phân loại' }}</p>
                            </div>
                            <div class="flex space-x-2">
                                <a href="{{ route('admin.patrons.cards.preview', $patron->id) }}" 
                                   class="text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300"
                                   target="_blank">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </a>
                                <form method="POST" action="{{ route('admin.patrons.cards.generate') }}" class="inline">
                                    @csrf
                                    <input type="hidden" name="patron_ids[]" value="{{ $patron->id }}">
                                    <input type="hidden" name="layout" value="single">
                                    <button type="submit" class="text-green-600 hover:text-green-800 dark:text-green-400 dark:hover:text-green-300">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @else
        <!-- Patron Selection -->
        <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-800 p-6 transition-colors">
            <h2 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-4">Chọn độc giả cần in thẻ</h2>
            
            <form method="GET" action="{{ route('admin.patrons.cards.index') }}" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Tìm kiếm</label>
                        <input type="text" name="search" value="{{ request('search') }}" 
                               placeholder="Tên, mã độc giả, email..."
                               class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Nhóm độc giả</label>
                        <select name="patron_group_id" class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-indigo-500">
                            <option value="">Tất cả</option>
                            @foreach(App\Models\PatronGroup::where('is_active', true)->get() as $group)
                                <option value="{{ $group->id }}" {{ request('patron_group_id') == $group->id ? 'selected' : '' }}>
                                    {{ $group->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Trạng thái</label>
                        <select name="status" class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-indigo-500">
                            <option value="">Tất cả</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Hoạt động</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Không hoạt động</option>
                        </select>
                    </div>
                </div>
                
                <div class="flex justify-end">
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                        Tìm kiếm
                    </button>
                </div>
            </form>
        </div>

        <!-- Patron List -->
        <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-800 overflow-hidden transition-colors">
            <form method="POST" action="{{ route('admin.patrons.cards.index') }}">
                @csrf
                <div class="p-6 border-b border-slate-200 dark:border-slate-700">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Danh sách độc giả</h3>
                        <div class="flex items-center space-x-4">
                            <label class="flex items-center">
                                <input type="checkbox" id="selectAll" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                                <span class="ml-2 text-sm text-slate-600 dark:text-slate-400">Chọn tất cả</span>
                            </label>
                            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors disabled:opacity-50" id="submitSelected" disabled>
                                In thẻ đã chọn
                            </button>
                        </div>
                    </div>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-slate-50 dark:bg-slate-800">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                                    <input type="checkbox" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Thông tin</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Mã độc giả</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Nhóm</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Ngày hết hạn</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                            @php
                                $query = App\Models\User::query();
                                if(request('search')) {
                                    $query->where(function($q) {
                                        $q->where('name', 'like', '%'.request('search').'%')
                                          ->orWhere('patron_code', 'like', '%'.request('search').'%')
                                          ->orWhere('email', 'like', '%'.request('search').'%');
                                    });
                                }
                                if(request('patron_group_id')) {
                                    $query->where('patron_group_id', request('patron_group_id'));
                                }
                                if(request('status')) {
                                    $query->where('is_active', request('status') == 'active');
                                }
                                $patrons = $query->with('patronGroup')->paginate(20);
                            @endphp
                            
                            @foreach($patrons as $patron)
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-800">
                                    <td class="px-6 py-4">
                                        <input type="checkbox" name="patron_ids[]" value="{{ $patron->id }}" 
                                               class="patron-checkbox rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                                    </td>
                                    <td class="px-6 py-4">
                                        <div>
                                            <div class="text-sm font-medium text-slate-900 dark:text-slate-100">{{ $patron->name }}</div>
                                            <div class="text-sm text-slate-500 dark:text-slate-400">{{ $patron->email }}</div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm font-mono text-slate-900 dark:text-slate-100">
                                        {{ $patron->patron_code }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-slate-900 dark:text-slate-100">
                                        {{ $patron->patronGroup->name ?? 'Chưa phân loại' }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-slate-900 dark:text-slate-100">
                                        {{ $patron->expiry_date ? \Carbon\Carbon::parse($patron->expiry_date)->format('d/m/Y') : 'Không giới hạn' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="p-6 border-t border-slate-200 dark:border-slate-700">
                    {{ $patrons->links() }}
                </div>
            </form>
        </div>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.patron-checkbox');
    const submitBtn = document.getElementById('submitSelected');
    
    if (selectAll) {
        selectAll.addEventListener('change', function() {
            checkboxes.forEach(cb => cb.checked = this.checked);
            updateSubmitButton();
        });
    }
    
    checkboxes.forEach(cb => {
        cb.addEventListener('change', updateSubmitButton);
    });
    
    function updateSubmitButton() {
        const checkedCount = document.querySelectorAll('.patron-checkbox:checked').length;
        submitBtn.disabled = checkedCount === 0;
        submitBtn.textContent = checkedCount > 0 ? `In thẻ đã chọn (${checkedCount})` : 'In thẻ đã chọn';
    }
});
</script>
@endsection
