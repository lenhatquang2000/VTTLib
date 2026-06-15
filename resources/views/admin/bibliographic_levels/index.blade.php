@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center bg-white dark:bg-slate-900 p-6 rounded-xl shadow-sm border border-gray-100 dark:border-slate-800">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 dark:text-slate-100">{{ __('Kiểu biểu ghi MARC') }}</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ __('Quản lý các kiểu biểu ghi trong MARC 21') }}</p>
        </div>
        <a href="{{ route('admin.bibliographic-levels.create') }}"
            class="inline-flex items-center px-4 py-2.5 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 transition">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            {{ __('Thêm mới') }}
        </a>
    </div>

    <!-- Table -->
    <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-gray-100 dark:border-slate-800 overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50 dark:bg-slate-800 border-b border-gray-200 dark:border-slate-700">
                <tr>
                    <th class="px-6 py-4 text-left text-sm font-bold text-gray-700 dark:text-slate-300">{{ __('Code') }}</th>
                    <th class="px-6 py-4 text-left text-sm font-bold text-gray-700 dark:text-slate-300">{{ __('Tên (Anh)') }}</th>
                    <th class="px-6 py-4 text-left text-sm font-bold text-gray-700 dark:text-slate-300">{{ __('Tên (Việt)') }}</th>
                    <th class="px-6 py-4 text-left text-sm font-bold text-gray-700 dark:text-slate-300">{{ __('Thứ tự') }}</th>
                    <th class="px-6 py-4 text-center text-sm font-bold text-gray-700 dark:text-slate-300">{{ __('Trạng thái') }}</th>
                    <th class="px-6 py-4 text-center text-sm font-bold text-gray-700 dark:text-slate-300">{{ __('Hành động') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-slate-700">
                @forelse($levels as $level)
                <tr class="hover:bg-gray-50 dark:hover:bg-slate-800 transition">
                    <td class="px-6 py-4 text-sm font-semibold text-gray-900 dark:text-slate-100">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-bold bg-indigo-100 dark:bg-indigo-900/30 text-indigo-800 dark:text-indigo-400">
                            {{ $level->code }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600 dark:text-slate-300">{{ $level->name_en }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600 dark:text-slate-300">{{ $level->name_vi }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600 dark:text-slate-300">{{ $level->order }}</td>
                    <td class="px-6 py-4 text-center">
                        @if($level->is_active)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-semibold bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-400">
                            {{ __('Kích hoạt') }}
                        </span>
                        @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-semibold bg-gray-100 dark:bg-gray-900/30 text-gray-800 dark:text-gray-400">
                            {{ __('Vô hiệu') }}
                        </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-center space-x-3 flex justify-center">
                        <a href="{{ route('admin.bibliographic-levels.edit', $level) }}"
                            class="text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300 font-semibold">
                            {{ __('Sửa') }}
                        </a>
                        <form method="POST" action="{{ route('admin.bibliographic-levels.destroy', $level) }}" class="inline"
                            onsubmit="return confirm('{{ __('Bạn chắc chắn muốn xóa?') }}')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 font-semibold">
                                {{ __('Xóa') }}
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-8 text-center text-gray-500 dark:text-slate-400">
                        {{ __('Không có dữ liệu') }}
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($levels->hasPages())
    <div class="flex justify-center">
        {{ $levels->links() }}
    </div>
    @endif
</div>
@endsection
