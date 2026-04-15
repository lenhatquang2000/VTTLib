@extends('layouts.admin')

@section('content')
<div class="space-y-6" x-data="{
    selected: {},
    toggleAll(e) {
        const checked = e.target.checked;
        document.querySelectorAll('input[data-doc-checkbox="1"]').forEach(cb => {
            cb.checked = checked;
            this.selected[cb.value] = checked;
        });
    },
    updateOne(e) {
        this.selected[e.target.value] = e.target.checked;
    },
    get selectedIds() {
        return Object.keys(this.selected).filter(id => this.selected[id]);
    },
    get hasSelection() {
        return this.selectedIds.length > 0;
    },
    get singleSelection() {
        return this.selectedIds.length === 1;
    }
}">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-slate-100">{{ __('Tài liệu số') }}</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">{{ $category ? $category->name : __('Chưa có thư mục') }}</p>
        </div>
        <div class="flex gap-2 flex-wrap">
            <a href="{{ route('admin.digital-categories.index') }}" class="btn-secondary">
                <i class="fas fa-folder mr-2"></i>{{ __('Thư mục') }}
            </a>
            <a href="{{ route('admin.digital-documents.create', ['category_id' => $category?->id]) }}" class="btn-primary" @if(!$category) aria-disabled="true" @endif>
                <i class="fas fa-plus mr-2"></i>{{ __('Thêm tài liệu') }}
            </a>
        </div>
    </div>

    <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-slate-200 dark:border-slate-800 p-4">
        <form method="GET" action="{{ route('admin.digital-documents.index') }}" class="flex flex-col md:flex-row gap-3">
            <div class="flex-1">
                <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">{{ __('Chọn thư mục') }}</label>
                <select name="category_id" class="input-field" onchange="this.form.submit()">
                    @foreach($categories as $c)
                        <option value="{{ $c->id }}" {{ ($category && $category->id === $c->id) ? 'selected' : '' }}>{{ $c->name }} ({{ $c->code }})</option>
                    @endforeach
                </select>
            </div>
        </form>
    </div>

    <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-slate-200 dark:border-slate-800 overflow-hidden">
        <div class="p-4 flex gap-2 flex-wrap border-b border-slate-200 dark:border-slate-800">
            <button type="button" class="btn-secondary" :disabled="!singleSelection" @click="if(singleSelection){ window.location = '{{ route('admin.digital-documents.edit', ['digitalDocument' => '__ID__']) }}'.replace('__ID__', selectedIds[0]); }">
                <i class="fas fa-edit mr-2"></i>{{ __('Sửa') }}
            </button>
            <button type="button" class="btn-danger" :disabled="!hasSelection" @click="if(hasSelection){
                const ids = selectedIds;
                if(!confirm('Bạn có chắc chắn muốn xóa các tài liệu đã chọn?')) return;
                ids.forEach((id, idx) => {
                    const form = document.getElementById('delete-form-' + id);
                    if(form) form.submit();
                });
            }">
                <i class="fas fa-trash mr-2"></i>{{ __('Xóa') }}
            </button>
            <button type="button" class="btn-secondary" :disabled="!hasSelection" onclick="alert('Chức năng di chuyển sẽ được bổ sung sau');">
                <i class="fas fa-arrows-alt mr-2"></i>{{ __('Di chuyển') }}
            </button>
        </div>

        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 dark:bg-slate-800/50 border-b border-slate-200 dark:border-slate-800">
                    <th class="px-6 py-4 w-12">
                        <input type="checkbox" class="h-4 w-4" @change="toggleAll($event)">
                    </th>
                    <th class="px-6 py-4 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">{{ __('Tiêu đề') }}</th>
                    <th class="px-6 py-4 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">{{ __('Link') }}</th>
                    <th class="px-6 py-4 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">{{ __('Trạng thái') }}</th>
                    <th class="px-6 py-4 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">{{ __('Thao tác') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                @forelse($documents as $doc)
                <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/30 transition-colors duration-150">
                    <td class="px-6 py-4">
                        <input type="checkbox" class="h-4 w-4" value="{{ $doc->id }}" data-doc-checkbox="1" @change="updateOne($event)">
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm font-medium text-slate-900 dark:text-slate-100">{{ $doc->title }}</div>
                        @if($doc->description)
                            <div class="text-xs text-slate-500 dark:text-slate-400">{{ \Illuminate\Support\Str::limit($doc->description, 80) }}</div>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm">
                        @if($doc->file_url)
                            <a href="{{ $doc->file_url }}" target="_blank" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">{{ __('Mở') }}</a>
                        @else
                            <span class="text-slate-500 dark:text-slate-400">-</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $doc->is_active ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400' }}">
                            {{ $doc->is_active ? __('Đang hiển thị') : __('Ẩn') }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm font-medium space-x-2">
                        <a href="{{ route('admin.digital-documents.edit', $doc) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form id="delete-form-{{ $doc->id }}" action="{{ route('admin.digital-documents.destroy', $doc) }}" method="POST" class="inline-block">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300" onclick="return confirm('{{ __('Bạn có chắc chắn?') }}')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-10 text-center text-sm text-slate-500 dark:text-slate-400">{{ __('Chưa có tài liệu nào') }}</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div>
        {{ $documents->links() }}
    </div>
</div>
@endsection
