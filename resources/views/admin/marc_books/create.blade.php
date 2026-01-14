@extends('layouts.admin')

@section('content')
    <div class="space-y-6 w-full pb-20">
        <div class="flex justify-between items-center bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">{{ __('MARC21_Cataloging_Form') }}</h2>
                <p class="text-sm text-gray-500 mt-1">{{ __('Cataloging_Instruction') }}</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.marc.book') }}"
                    class="px-5 py-2.5 rounded-lg text-sm font-semibold bg-gray-100 text-gray-600 hover:bg-gray-200 transition">{{ __('Cancel') }}</a>
                <button form="catalogForm" type="submit"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-lg text-sm font-semibold transition shadow-lg shadow-indigo-200">
                    {{ __('Save_Record') }}
                </button>
            </div>
        </div>

        <form id="catalogForm" action="{{ route('admin.marc.book.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Record Leader (Usually fixed or auto-generated, but showing for transparency) -->
            <!-- Record Leader (Collapsible) -->
            <div class="bg-slate-900 rounded-xl shadow-xl border border-slate-800 overflow-hidden" x-data="{ expanded: false }">
                <div class="p-4 flex justify-between items-center cursor-pointer border-b border-slate-800" @click="expanded = !expanded">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 rounded-lg bg-indigo-500 flex items-center justify-center text-white font-bold text-xs uppercase">LR</div>
                        <h3 class="text-white font-bold tracking-tight uppercase text-sm">{{ __('Record_Leader') }}</h3>
                    </div>
                    <svg class="w-4 h-4 text-slate-500 transition-transform duration-200" :class="expanded ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </div>
                
                <div class="p-6 space-y-4" x-show="expanded" x-cloak x-collapse>
                    <input type="text" name="leader" value="00000nam a2200000 i 4500" readonly
                        class="w-full bg-slate-950 border-slate-800 text-indigo-400 font-mono text-sm rounded-lg p-3 ring-1 ring-slate-800">
                    <p class="text-[10px] text-slate-500 italic">{{ __('Leader_Instruction') }}</p>
                </div>
            </div>

            <!-- Dynamic Fields from Framework Definitions -->
            @foreach($definitions as $tag)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden"
                    x-data="{ expanded: {{ $tag->tag == '245' ? 'true' : 'false' }} }">
                    <div class="p-4 bg-gray-50/50 flex justify-between items-center cursor-pointer border-b border-gray-50"
                        @click="expanded = !expanded">
                        <div class="flex items-center space-x-4">
                            <span class="bg-gray-800 text-white px-3 py-1 rounded font-mono font-bold">{{ $tag->tag }}</span>
                            <h4 class="font-bold text-gray-700 uppercase text-xs">{{ $tag->label }}</h4>
                        </div>
                        <div class="flex items-center space-x-4">
                            <div class="flex space-x-1">
                                <input type="text" name="fields[{{ $tag->tag }}][ind1]" placeholder="#" maxlength="1"
                                    class="w-8 h-8 p-0 text-center border-gray-200 rounded text-xs font-mono uppercase bg-white focus:ring-1 focus:ring-indigo-500"
                                    @click.stop>
                                <input type="text" name="fields[{{ $tag->tag }}][ind2]" placeholder="#" maxlength="1"
                                    class="w-8 h-8 p-0 text-center border-gray-200 rounded text-xs font-mono uppercase bg-white focus:ring-1 focus:ring-indigo-500"
                                    @click.stop>
                            </div>
                            <svg class="w-4 h-4 text-gray-400 transition-transform duration-200"
                                :class="expanded ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </div>

                    <div class="p-6" x-show="expanded" x-cloak x-collapse>
                        <div x-data="{ 
                            rows: [{ code: '', value: '' }],
                            definitions: {{ $tag->subfields->map(fn($s) => ['code' => $s->code, 'label' => $s->label])->toJson() }}
                        }">
                            <div class="space-y-3">
                                <template x-for="(row, index) in rows" :key="index">
                                    <div class="flex flex-col md:flex-row gap-3 items-start bg-gray-50/30 p-3 rounded-lg border border-gray-100 group">
                                        <!-- Subfield Selector -->
                                        <div class="w-full md:w-1/3">
                                            <select :name="'fields[' + '{{ $tag->tag }}' + '][subfields][' + index + '][code]'" 
                                                    x-model="row.code"
                                                    class="w-full border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 font-mono">
                                                <option value="">{{ __('Select_Subfield') }}</option>
                                                <template x-for="def in definitions" :key="def.code">
                                                    <option :value="def.code" x-text="'$' + def.code + ' ' + def.label"></option>
                                                </template>
                                            </select>
                                        </div>

                                        <!-- Value Input -->
                                        <div class="w-full md:w-2/3 flex gap-2">
                                            <input type="text" 
                                                   :name="'fields[' + '{{ $tag->tag }}' + '][subfields][' + index + '][value]'" 
                                                   x-model="row.value"
                                                   placeholder="{{ __('Enter_Value') }}"
                                                   class="flex-1 border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
                                            
                                            <button type="button" @click="rows.splice(index, 1)" class="text-gray-300 hover:text-rose-500 p-2 transition opacity-0 group-hover:opacity-100">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </div>
                                    </div>
                                </template>
                            </div>

                            <button type="button" @click="rows.push({ code: '', value: '' })" 
                                    class="mt-4 inline-flex items-center text-xs font-bold text-indigo-600 hover:text-indigo-800 uppercase tracking-wider">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                {{ __('Add_Subfield') }}
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach

            <div class="bg-gray-50 p-6 rounded-xl border-2 border-dashed border-gray-200 text-center">
                <button type="submit"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white px-10 py-4 rounded-xl text-lg font-bold transition shadow-xl shadow-indigo-100 uppercase tracking-widest">
                    {{ __('Finalize_Save_Record') }}
                </button>
                <p class="text-xs text-gray-400 mt-4">{{ __('Save_Note') }}</p>
            </div>
        </form>
    </div>

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
@endsection