@extends('layouts.admin')

@section('content')
<div class="space-y-6 w-full pb-20">
    <div class="flex justify-between items-center bg-white p-6 rounded-xl shadow-sm border border-gray-100">
        <div class="flex items-center space-x-4">
            <a href="{{ route('admin.marc.book') }}" class="p-2 hover:bg-gray-100 rounded-full transition text-gray-400">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <div>
                <h2 class="text-2xl font-bold text-gray-800">{{ __('Review_Bibliographic_Record') }}</h2>
                <p class="text-sm text-gray-500 mt-1">{{ __('Review_Instruction') }}</p>
            </div>
        </div>
        
        <div class="flex items-center space-x-4">
            <div class="text-right mr-4">
                <span class="block text-[10px] text-gray-400 font-bold uppercase tracking-widest">{{ __('Current_Status') }}</span>
                @if($record->isApproved())
                    <span class="text-emerald-600 font-bold uppercase text-xs">{{ __('Approved') }}</span>
                @else
                    <span class="text-amber-500 font-bold uppercase text-xs">{{ __('Pending_Review') }}</span>
                @endif
            </div>

            <form action="{{ route('admin.marc.book.status', $record->id) }}" method="POST" class="flex items-center space-x-2">
                @csrf @method('PUT')
                <select name="status" class="bg-gray-50 border-gray-200 rounded-lg text-sm px-4 py-2 focus:ring-2 focus:ring-indigo-500">
                <select name="status" class="bg-gray-50 border-gray-200 rounded-lg text-sm px-4 py-2 focus:ring-2 focus:ring-indigo-500">
                    <option value="pending" {{ $record->status === 'pending' ? 'selected' : '' }}>{{ __('Pending') }}</option>
                    <option value="approved" {{ $record->status === 'approved' ? 'selected' : '' }}>{{ __('Approved') }}</option>
                </select>
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-bold transition shadow-lg shadow-indigo-100 whitespace-nowrap">
                    {{ __('Update_Status') }}
                </button>
            </form>
        </div>
    </div>

    <!-- MARC Data Display -->
    <div class="bg-white rounded-xl shadow-md border border-gray-100 overflow-hidden">
        <div class="p-4 bg-slate-900 text-white flex justify-between items-center">
            <div class="flex items-center space-x-3">
                <div class="w-8 h-8 rounded bg-indigo-500 flex items-center justify-center text-[10px] font-bold">MARC</div>
                <span class="text-sm font-bold tracking-tight">{{ __('FULL_RECORD_VIEW_MARC21') }}</span>
            </div>
            <span class="font-mono text-xs text-indigo-300">{{ $record->leader }}</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 text-[10px] uppercase font-bold text-gray-400">
                    <tr>
                        <th class="px-6 py-3 w-20">{{ __('Tag') }}</th>
                        <th class="px-6 py-3 w-16">{{ __('Ind') }}</th>
                        <th class="px-6 py-3">{{ __('Content_Data') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 font-mono">
                    @foreach($record->fields as $field)
                        <tr class="hover:bg-indigo-50/20 group transition">
                            <td class="px-6 py-4">
                                <span class="text-indigo-600 font-bold">{{ $field->tag }}</span>
                                <div class="text-[9px] text-gray-400 mt-1 uppercase font-sans">
                                    {{ $definitions->get($field->tag)->label ?? '' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 text-gray-400">
                                {{ $field->indicator1 ?: '#' }}{{ $field->indicator2 ?: '#' }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="space-y-1">
                                    @foreach($field->subfields as $sub)
                                        <div class="flex items-start space-x-2">
                                            <span class="text-emerald-600 font-bold">${{ $sub->code }}</span>
                                            <span class="text-gray-800 break-all">{{ $sub->value }}</span>
                                            
                                            @php
                                                $def = $definitions->get($field->tag);
                                                $subDef = $def ? $def->subfields->where('code', $sub->code)->first() : null;
                                            @endphp
                                            @if($subDef)
                                                <span class="text-[9px] text-gray-300 font-sans uppercase align-middle ml-2">[{{ $subDef->label }}]</span>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- History / Audit (Optional Mockup) -->
    <div class="bg-gray-50 p-6 rounded-xl border border-gray-200">
        <h4 class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-4">{{ __('Record_Metadata') }}</h4>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            <div>
                <span class="block text-[10px] text-gray-400 uppercase">{{ __('Created_At') }}</span>
                <span class="text-xs font-bold text-gray-700">{{ $record->created_at->format('Y-m-d H:i') }}</span>
            </div>
            <div>
                <span class="block text-[10px] text-gray-400 uppercase">{{ __('Last_Modified') }}</span>
                <span class="text-xs font-bold text-gray-700">{{ $record->updated_at->format('Y-m-d H:i') }}</span>
            </div>
            <div>
                <span class="block text-[10px] text-gray-400 uppercase">{{ __('Record_Type') }}</span>
                <span class="text-xs font-bold text-gray-700 uppercase">{{ $record->record_type }}</span>
            </div>
            <div>
                <span class="block text-[10px] text-gray-400 uppercase">{{ __('Control_Number') }}</span>
                <span class="text-xs font-bold text-indigo-600 font-mono">#{{ $record->id }}</span>
            </div>
        </div>
    </div>
</div>
@endsection
