@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-slate-100">{{ __('Sửa thư mục tài liệu số') }}</h1>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.digital-categories.index') }}" class="inline-flex items-center px-4 py-2 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-medium rounded-lg transition-colors duration-200">
                <i class="fas fa-arrow-left mr-2"></i>{{ __('Quay lại') }}
            </a>
        </div>
    </div>

    <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-slate-200 dark:border-slate-800 p-6">
        <form action="{{ route('admin.digital-categories.update', $digitalCategory) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">{{ __('Tên thư mục') }} *</label>
                        <input type="text" name="name" required class="input-field" value="{{ old('name', $digitalCategory->name) }}">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">{{ __('Mã thư mục') }} *</label>
                        <input type="text" name="code" required class="input-field" value="{{ old('code', $digitalCategory->code) }}">
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
