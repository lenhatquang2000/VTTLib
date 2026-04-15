@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-slate-100">{{ $category->name }} - {{ __('Items') }}</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">{{ __('Manage items for this category') }}</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.media-categories.index') }}" class="inline-flex items-center px-4 py-2 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-medium rounded-lg transition-colors duration-200">
                <i class="fas fa-arrow-left mr-2"></i>{{ __('Back to Categories') }}
            </a>
            <a href="{{ route('admin.media-items.create', ['category_id' => $category->id]) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200">
                <i class="fas fa-plus mr-2"></i>{{ __('Add Item') }}
            </a>
        </div>
    </div>

    <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-slate-200 dark:border-slate-800 overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 dark:bg-slate-800/50 border-b border-slate-200 dark:border-slate-800">
                    <th class="px-6 py-4 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider w-32">{{ __('Preview') }}</th>
                    <th class="px-6 py-4 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">{{ __('Title') }}</th>
                    <th class="px-6 py-4 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">{{ __('Link') }}</th>
                    <th class="px-6 py-4 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">{{ __('Order') }}</th>
                    <th class="px-6 py-4 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">{{ __('Status') }}</th>
                    <th class="px-6 py-4 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                @forelse($items as $item)
                <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/30 transition-colors duration-150">
                    <td class="px-6 py-4">
                        <img src="{{ $item->image_url }}" alt="{{ $item->title }}" class="w-24 h-16 object-cover rounded shadow-sm border border-slate-200 dark:border-slate-700">
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm font-medium text-slate-900 dark:text-slate-100">{{ $item->title ?: __('No Title') }}</div>
                        <div class="text-xs text-slate-500 dark:text-slate-400 truncate max-w-xs">{{ $item->description }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-xs text-slate-600 dark:text-slate-400 truncate max-w-xs">{{ $item->link_url ?: __('No Link') }}</div>
                    </td>
                    <td class="px-6 py-4 text-sm text-slate-600 dark:text-slate-400">{{ $item->sort_order }}</td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $item->is_active ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400' }}">
                            {{ $item->is_active ? __('Active') : __('Inactive') }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm font-medium space-x-2">
                        <a href="{{ route('admin.media-items.edit', $item) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('admin.media-items.destroy', $item) }}" method="POST" class="inline-block">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300" onclick="return confirm('{{ __('Are you sure?') }}')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-10 text-center text-slate-500 dark:text-slate-400">
                        {{ __('No items found') }}
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        @if($items->hasPages())
        <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-800">
            {{ $items->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
