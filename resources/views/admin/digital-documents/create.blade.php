@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-slate-100">{{ __('Thêm tài liệu số') }}</h1>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.digital-documents.index', ['category_id' => $categoryId]) }}" class="btn-secondary">
                <i class="fas fa-arrow-left mr-2"></i>{{ __('Quay lại') }}
            </a>
        </div>
    </div>

    <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-slate-200 dark:border-slate-800 p-6">
        <form action="{{ route('admin.digital-documents.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">{{ __('Thư mục') }} *</label>
                        <select name="folder_id" class="input-field" required>
                            @foreach($categories as $c)
                                <option value="{{ $c->id }}" {{ (int)old('folder_id', $categoryId) === $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">{{ __('Tiêu đề') }} *</label>
                        <input type="text" name="title" required class="input-field" value="{{ old('title') }}">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">{{ __('Mô tả') }}</label>
                        <textarea name="description" rows="4" class="input-field">{{ old('description') }}</textarea>
                    </div>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">{{ __('Link file') }}</label>
                        <input type="text" name="file_url" class="input-field" value="{{ old('file_url') }}">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">{{ __('Thứ tự') }}</label>
                        <input type="number" name="sort_order" min="0" class="input-field" value="{{ old('sort_order', 0) }}">
                    </div>

                    <div class="flex items-center gap-2 pt-2">
                        <input type="checkbox" name="is_active" id="is_active" class="h-4 w-4" {{ old('is_active', true) ? 'checked' : '' }}>
                        <label for="is_active" class="text-sm text-slate-700 dark:text-slate-300">{{ __('Đang hiển thị') }}</label>
                    </div>
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-2">
                <button type="submit" class="btn-primary">
                    <i class="fas fa-save mr-2"></i>{{ __('Lưu') }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
