@extends('layouts.admin')

@section('content')
    <div class="space-y-6 w-full">
        <div class="flex justify-between items-center bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">{{ __('Cataloged_Records') }}</h2>
                <p class="text-sm text-gray-500 mt-1">{{ __('Catalog_Instruction_Index') }}</p>
            </div>
            <a href="{{ route('admin.marc.book.create') }}"
                class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-lg text-sm font-semibold transition flex items-center shadow-lg shadow-indigo-200">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                {{ __('New_Cataloging') }}
            </a>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 text-[10px] uppercase font-bold text-gray-500 tracking-wider">
                    <tr>
                        <th class="px-6 py-4">ID</th>
                        <th class="px-6 py-4">{{ __('Leader_Type') }}</th>
                        <th class="px-6 py-4">{{ __('Main_Content') }}</th>
                        <th class="px-6 py-4">{{ __('Fields') }}</th>
                        <th class="px-6 py-4">{{ __('Status') }}</th>
                        <th class="px-6 py-4 text-right">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($records as $record)
                        @php
                            // Extract Title (245) and Author (100) for preview
                            $title = '';
                            $author = '';
                            foreach ($record->fields as $field) {
                                if ($field->tag === '245') {
                                    foreach ($field->subfields as $sub) {
                                        if ($sub->code === 'a')
                                            $title = $sub->value;
                                    }
                                }
                                if ($field->tag === '100') {
                                    foreach ($field->subfields as $sub) {
                                        if ($sub->code === 'a')
                                            $author = $sub->value;
                                    }
                                }
                            }
                        @endphp
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 font-mono text-gray-400">#{{ $record->id }}</td>
                            <td class="px-6 py-4">
                                <span class="block font-mono text-[10px] text-gray-500">{{ $record->leader }}</span>
                                <span
                                    class="inline-block px-2 py-0.5 bg-indigo-50 text-indigo-600 rounded text-[10px] font-bold uppercase mt-1">{{ $record->record_type }}</span>
                            </td>
                             <td class="px-6 py-4">
                                <div class="font-bold text-gray-800">{{ $title ?: __('No_Title_Defined') }}</div>
                                <div class="text-xs text-gray-500 mt-0.5">{{ $author ?: __('Unknown_Author') }}</div>
                            </td>
                             <td class="px-6 py-4 text-gray-500 text-xs">
                                {{ __('Tags_Included', ['count' => $record->fields->count()]) }}
                            </td>
                            <td class="px-6 py-4">
                                @if($record->isApproved())
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">
                                        {{ __('Approved') }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                                        {{ __('Pending') }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right space-x-2">
                                <a href="{{ route('admin.marc.book.show', $record->id) }}"
                                    class="text-indigo-600 hover:text-indigo-800 font-bold text-xs uppercase">{{ __('Review') }}</a>
                                <a href="{{ route('admin.marc.book.edit', $record->id) }}"
                                    class="text-amber-600 hover:text-amber-800 font-bold text-xs uppercase">{{ __('Edit') }}</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="text-gray-300 mb-4">
                                    <svg class="w-12 h-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                                        </path>
                                    </svg>
                                </div>
                                <h3 class="text-gray-500 font-bold uppercase tracking-widest text-sm">{{ __('No_Records_Found') }}</h3>
                                <p class="text-gray-400 text-xs mt-1">{{ __('Start_Cataloging') }}</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="p-4 border-t border-gray-50">
                {{ $records->links() }}
            </div>
        </div>
    </div>
@endsection