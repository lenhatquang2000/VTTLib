@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center bg-white dark:bg-slate-900 p-6 rounded-xl shadow-sm border border-gray-100 dark:border-slate-800">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 dark:text-slate-100">{{ __('Bản ghi đã biên mục') }}</h2>
            <p class="text-sm text-gray-500 dark:text-slate-400 mt-1">{{ __('Xem và quản lý các bản ghi biên mục sách, tài liệu theo chuẩn MARC21') }}</p>
        </div>
        <a href="{{ route('admin.marc.book.form') }}"
            class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-lg text-sm font-semibold transition flex items-center shadow-lg shadow-indigo-100 dark:shadow-none">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            {{ __('Biên mục mới') }}
        </a>
    </div>

    <!-- Advanced Search Section -->
    <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-gray-100 dark:border-slate-800 overflow-hidden">
        <div class="p-6">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-slate-100 mb-4">{{ __('Tìm kiếm nâng cao') }}</h3>

            <form method="GET" class="space-y-4">
                @csrf

                <!-- Basic Search -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-2">{{ __('Tìm kiếm theo Nhan đề, Tác giả, ISBN, Nhà xuất bản, Chủ đề, Ghi chú') }}</label>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="{{ __('Nhập từ khóa tìm kiếm...') }}"
                            class="w-full px-4 py-2.5 border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 dark:text-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-2">{{ __('Sắp xếp theo') }}</label>
                        <div class="flex space-x-2">
                            <select name="sort_by"
                                class="flex-[2] min-w-[160px] px-3 py-2.5 border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 text-gray-900 dark:text-gray-100 rounded-lg text-sm font-bold focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors cursor-pointer">
                                <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>{{ __('Ngày tạo') }}</option>
                                <option value="title" {{ request('sort_by') == 'title' ? 'selected' : '' }}>{{ __('Nhan đề') }}</option>
                                <option value="author" {{ request('sort_by') == 'author' ? 'selected' : '' }}>{{ __('Tác giả') }}</option>
                                <option value="updated_at" {{ request('sort_by') == 'updated_at' ? 'selected' : '' }}>{{ __('Ngày cập nhật') }}</option>
                            </select>
                            <select name="sort_order"
                                class="flex-1 min-w-[120px] px-3 py-2.5 border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 text-gray-900 dark:text-gray-100 rounded-lg text-sm font-bold focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors cursor-pointer">
                                <option value="desc" {{ request('sort_order') == 'desc' ? 'selected' : '' }}>{{ __('Giảm dần') }}</option>
                                <option value="asc" {{ request('sort_order') == 'asc' ? 'selected' : '' }}>{{ __('Tăng dần') }}</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Filters Row 1 -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-2">{{ __('Khung biên mục') }}</label>
                        <select name="framework"
                            class="w-full px-4 py-2.5 border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 dark:text-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">{{ __('Tất cả Khung') }}</option>
                            @foreach($frameworks as $code => $name)
                            <option value="{{ $code }}" {{ request('framework') == $code ? 'selected' : '' }}>{{ $code }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-2">{{ __('Loại tài liệu') }}</label>
                        <select name="record_type"
                            class="w-full px-4 py-2.5 border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 dark:text-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">{{ __('Tất cả loại') }}</option>
                            @foreach($recordTypes as $type)
                            <option value="{{ $type }}" {{ request('record_type') == $type ? 'selected' : '' }}>{{ ucfirst($type) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-2">{{ __('Trạng thái') }}</label>
                        <select name="status"
                            class="w-full px-4 py-2.5 border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 dark:text-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">{{ __('Tất cả trạng thái') }}</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>{{ __('Đang chờ duyệt') }}</option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>{{ __('Đã duyệt') }}</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-2">{{ __('Thể loại chủ đề') }}</label>
                        <select name="subject_category"
                            class="w-full px-4 py-2.5 border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 dark:text-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">{{ __('Tất cả thể loại') }}</option>
                            @foreach($subjectCategories as $category)
                            <option value="{{ $category }}" {{ request('subject_category') == $category ? 'selected' : '' }}>{{ $category }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Filters Row 2 -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-2">{{ __('Từ ngày') }}</label>
                        <input type="date" name="date_from" value="{{ request('date_from') }}"
                            class="w-full px-4 py-2.5 border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 dark:text-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-2">{{ __('Đến ngày') }}</label>
                        <input type="date" name="date_to" value="{{ request('date_to') }}"
                            class="w-full px-4 py-2.5 border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 dark:text-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-2">{{ __('Trường MARC') }}</label>
                        <select name="marc_tag"
                            class="w-full px-4 py-2.5 border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 dark:text-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">{{ __('Chọn Trường') }}</option>
                            @foreach($commonMarcTags as $tag)
                            <option value="{{ $tag }}" {{ request('marc_tag') == $tag ? 'selected' : '' }}>{{ $tag }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-2">{{ __('Giá trị MARC') }}</label>
                        <input type="text" name="marc_value" value="{{ request('marc_value') }}"
                            placeholder="{{ __('Giá trị trường...') }}"
                            class="w-full px-4 py-2.5 border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 dark:text-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex space-x-3">
                    <button type="submit"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-lg text-sm font-semibold transition">
                        <i class="fas fa-search mr-2"></i>
                        {{ __('Tìm kiếm') }}
                    </button>
                    <a href="{{ route('admin.marc.book') }}"
                        class="px-6 py-2.5 bg-gray-100 dark:bg-slate-800 text-gray-600 dark:text-slate-400 hover:bg-gray-200 dark:hover:bg-slate-700 rounded-lg text-sm font-semibold transition">
                        {{ __('Xóa bộ lọc') }}
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Results Summary -->
    @if(request()->anyFilled(['search', 'framework', 'record_type', 'status', 'subject_category', 'date_from', 'date_to', 'marc_tag', 'marc_value']))
    <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-blue-800 dark:text-blue-200">
                    {{ __('Tìm thấy :count bản ghi khớp với điều kiện tìm kiếm', ['count' => $records->total()]) }}
                </p>
                @if(request()->filled('search'))
                <p class="text-xs text-blue-600 dark:text-blue-400 mt-1">
                    {{ __('Từ khóa tìm kiếm: ":term"', ['term' => request('search')]) }}
                </p>
                @endif
            </div>
            <a href="{{ route('admin.marc.book') }}"
                class="text-sm text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">
                {{ __('Xóa bộ lọc') }}
            </a>
        </div>
    </div>
    @endif

    <!-- Results Table -->
    <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-gray-100 dark:border-slate-800 overflow-hidden">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-50 dark:bg-slate-800/50 text-[10px] uppercase font-bold text-gray-500 dark:text-slate-400 tracking-wider">
                <tr>
                    <th class="px-6 py-4">ID</th>
                    <th class="px-6 py-4">{{ __('Leader / Kiểu') }}</th>
                    <th class="px-6 py-4">{{ __('Nội dung chính') }}</th>
                    <th class="px-6 py-4">{{ __('Số trường') }}</th>
                    <th class="px-6 py-4">{{ __('Trạng thái') }}</th>
                    <th class="px-6 py-4 text-right">{{ __('Thao tác') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-slate-800">
                @forelse($records as $record)
                @php
                $title = '';
                $author = '';
                foreach ($record->fields as $field) {
                if ($field->tag === '245') {
                foreach ($field->subfields as $sub) {
                if ($sub->code === 'a') $title = $sub->value;
                }
                }
                if ($field->tag === '100') {
                foreach ($field->subfields as $sub) {
                if ($sub->code === 'a') $author = $sub->value;
                }
                }
                }
                @endphp
                <tr class="hover:bg-gray-50 dark:hover:bg-slate-800/50 transition cursor-pointer"
                    data-edit-url="{{ route('admin.marc.book.form', $record->id) }}"
                    title="{{ __('Sửa') }}">
                    <td class="px-6 py-4 font-mono text-gray-400 dark:text-slate-500">#{{ $record->id }}</td>
                    <td class="px-6 py-4">
                        <span class="block font-mono text-[10px] text-gray-500 dark:text-slate-500">{{ $record->leader }}</span>
                        <span
                            class="inline-block px-2 py-0.5 bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 rounded text-[10px] font-bold uppercase mt-1">{{ $record->record_type }}</span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="font-bold text-gray-800 dark:text-slate-100">{{ $title ?: __('Chưa xác định nhan đề') }}</div>
                        <div class="text-xs text-gray-500 dark:text-slate-400 mt-0.5">{{ $author ?: __('Không rõ tác giả') }}</div>
                    </td>
                    <td class="px-6 py-4 text-gray-500 dark:text-slate-400 text-xs">
                        {{ __('Bao gồm :count trường', ['count' => $record->fields->count()]) }}
                    </td>
                    <td class="px-6 py-4">
                        @if($record->isApproved())
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 dark:bg-emerald-900/30 text-emerald-800 dark:text-emerald-400">
                            {{ __('Đã duyệt') }}
                        </span>
                        @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 dark:bg-amber-900/30 text-amber-800 dark:text-amber-400">
                            {{ __('Đang chờ duyệt') }}
                        </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end space-x-1">
                            <a href="{{ route('admin.marc.book.form', $record->id) }}?tab=0"
                                class="inline-flex items-center px-2 py-1 bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded text-xs font-medium hover:bg-blue-100 dark:hover:bg-blue-900/50 transition-colors"
                                title="{{ __('Thông tin Leader') }}">
                                <i class="fas fa-info-circle text-xs"></i>
                            </a>
                            <a href="{{ route('admin.marc.book.form', $record->id) }}?tab=1"
                                class="inline-flex items-center px-2 py-1 bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 rounded text-xs font-medium hover:bg-indigo-100 dark:hover:bg-indigo-900/50 transition-colors"
                                title="{{ __('Biên mục') }}">
                                <i class="fas fa-book text-xs"></i>
                            </a>
                            <a href="{{ route('admin.marc.book.form', $record->id) }}?tab=2"
                                class="inline-flex items-center px-2 py-1 bg-green-50 dark:bg-green-900/30 text-green-600 dark:text-green-400 rounded text-xs font-medium hover:bg-green-100 dark:hover:bg-green-900/50 transition-colors"
                                title="{{ __('Phân bổ') }}">
                                <i class="fas fa-share-alt text-xs"></i>
                            </a>
                            <a href="{{ route('admin.marc.book.form', $record->id) }}?tab=3"
                                class="inline-flex items-center px-2 py-1 bg-purple-50 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400 rounded text-xs font-medium hover:bg-purple-100 dark:hover:bg-purple-900/50 transition-colors"
                                title="{{ __('Xem trước') }}">
                                <i class="fas fa-eye text-xs"></i>
                            </a>
                            <a href="{{ route('admin.marc.export.download') }}?format=marc&record_id={{ $record->id }}"
                                class="inline-flex items-center px-2 py-1 bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 rounded text-xs font-medium hover:bg-emerald-100 dark:hover:bg-emerald-900/50 transition-colors"
                                title="{{ __('Xuất MARC') }}">
                                <i class="fas fa-file-export text-xs"></i>
                            </a>
                            <button type="button"
                                class="delete-record inline-flex items-center px-2 py-1 bg-red-50 dark:bg-red-900/30 text-red-600 dark:text-red-400 rounded text-xs font-medium hover:bg-red-100 dark:hover:bg-red-900/50 transition-colors"
                                data-id="{{ $record->id }}"
                                data-title="{{ $title }}"
                                title="{{ __('Xóa') }}">
                                <i class="fas fa-trash-alt text-xs"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                        {{ __('Không tìm thấy bản ghi nào') }}
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <div class="p-4 border-t border-gray-50 dark:border-slate-800">
            {{ $records->links() }}
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // MARC tag and value validation
        const marcTag = document.querySelector('select[name="marc_tag"]');
        const marcValue = document.querySelector('input[name="marc_value"]');

        function validateMarcFields() {
            if (marcTag && marcValue) {
                if (marcTag.value && !marcValue.value) {
                    marcValue.setCustomValidity("{{ __('Vui lòng nhập giá trị MARC khi trường đã được chọn') }}");
                } else if (!marcTag.value && marcValue.value) {
                    marcTag.setCustomValidity("{{ __('Vui lòng chọn trường MARC khi giá trị đã được nhập') }}");
                } else {
                    marcValue.setCustomValidity('');
                    marcTag.setCustomValidity('');
                }
            }
        }

        if (marcTag && marcValue) {
            marcTag.addEventListener('change', validateMarcFields);
            marcValue.addEventListener('input', validateMarcFields);
        }

        // Delete functionality
        document.querySelectorAll('.delete-record').forEach(button => {
            button.addEventListener('click', function(e) {
                e.stopPropagation();
                const id = this.dataset.id;
                const title = this.dataset.title;

                Swal.fire({
                    title: '{{ __("Bạn có chắc chắn muốn xóa?") }}',
                    text: `{{ __("Bạn chuẩn bị xóa bản ghi") }}: ${title}`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: '{{ __("Đúng, xóa nó!") }}',
                    cancelButtonText: '{{ __("Hủy bỏ") }}',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch(`{{ route('admin.marc.book.destroy', ['record' => ':id']) }}`.replace(':id', id), {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Accept': 'application/json',
                                    'Content-Type': 'application/json'
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    Swal.fire(
                                        '{{ __("Đã xóa!") }}',
                                        data.message,
                                        'success'
                                    ).then(() => {
                                        location.reload();
                                    });
                                } else {
                                    Swal.fire(
                                        '{{ __("Lỗi!") }}',
                                        data.message,
                                        'error'
                                    );
                                }
                            })
                            .catch(error => {
                                Swal.fire(
                                    '{{ __("Lỗi!") }}',
                                    '{{ __("Đã có lỗi xảy ra trong quá trình xóa") }}',
                                    'error'
                                );
                            });
                    }
                });
            });
        });
    });
    
    // Double-click to edit with tab=0
    document.querySelectorAll('tbody tr[data-edit-url]').forEach(row => {
        row.addEventListener('dblclick', function() {
            const editUrl = this.getAttribute('data-edit-url');
            window.location.href = editUrl + '?tab=0';
        });
    });
</script>
@endpush