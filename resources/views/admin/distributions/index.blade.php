@extends('layouts.admin')

@section('content')
<div class="mx-auto pb-12">
    <!-- Header Section -->
    <div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div class="flex items-center space-x-4">
            <div class="w-12 h-12 rounded-xl bg-indigo-600 flex items-center justify-center shadow-lg shadow-indigo-200">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                </svg>
            </div>
            <div>
                <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">{{ __('Distribution') }}</h1>
                <p class="text-slate-500 font-medium flex items-center mt-1">
                    <span class="bg-slate-100 text-slate-700 font-mono text-xs px-2 py-0.5 rounded mr-2">DOC_ID #{{ $record->id }}</span>
                    {{ $record->fields->where('tag', '245')->first()?->subfields->where('code', 'a')->first()?->value ?? 'Untitled Record' }}
                </p>
            </div>
        </div>
        <a href="{{ route('admin.marc.book') }}" class="inline-flex items-center px-4 py-2 bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 rounded-xl transition font-semibold shadow-sm text-sm group">
            <svg class="w-4 h-4 mr-2 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            {{ __('Back to Books') }}
        </a>
    </div>

    @if(session('success'))
        <div class="mb-6 animate-in slide-in-from-top-4 fade-in duration-300">
            <div class="bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-r-xl shadow-sm flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-bold text-emerald-800">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="mb-6 animate-in slide-in-from-top-4 fade-in duration-300">
            <div class="bg-rose-50 border-l-4 border-rose-500 p-4 rounded-r-xl shadow-sm">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-rose-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-bold text-rose-800">{{ __('Validation Errors') }}</h3>
                        <div class="mt-1 text-xs text-rose-700 space-y-1">
                            @foreach ($errors->all() as $error)
                                <p>• {{ $error }}</p>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Main Grid Layout (4:8 Ratio using Tailwind) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        <!-- LEFT COLUMN: Input Form (4/12) -->
        <div class="lg:col-span-4">
            <div class="bg-slate-900 rounded-2xl shadow-2xl border border-slate-800 overflow-hidden sticky top-24">
                <div class="p-6 border-b border-slate-800 bg-slate-950 flex items-center justify-between">
                    <h2 class="text-white font-bold tracking-wide uppercase text-sm flex items-center whitespace-nowrap">
                        <span class="w-2 h-2 bg-indigo-500 rounded-full mr-3 animate-pulse"></span>
                        {{ __('Add New Item') }}
                    </h2>
                    <span class="text-[10px] font-mono text-slate-500 uppercase tracking-widest">{{ __('System_V2') }}</span>
                </div>
                
                <div class="p-0">
                    <form action="{{ route('admin.marc.book.distribution.store', $record) }}" method="POST" class="p-6 space-y-5"
                          x-data="{ 
                          barcode: '{{ old('barcode') }}', 
                          barcodeStatus: '', 
                          isError: false,
                          isChecking: false,
                          checkBarcode() {
                              if (this.barcode.length < 3) {
                                  this.barcodeStatus = '';
                                  this.isError = false;
                                  return;
                              }
                              this.isChecking = true;
                              fetch(`{{ route('admin.marc.book.distribution.check') }}?barcode=${this.barcode}`)
                                  .then(res => res.json())
                                  .then(data => {
                                      this.barcodeStatus = data.message;
                                      this.isError = data.exists;
                                  })
                                  .finally(() => {
                                      this.isChecking = false;
                                  });
                          }
                      }"
                      x-init="$watch('barcode', value => {
                          if (value.length >= 3) {
                              checkBarcode();
                          } else {
                              barcodeStatus = '';
                              isError = false;
                          }
                      })">
                        @csrf
                        
                        <div class="grid grid-cols-1 gap-5">
                            <div class="space-y-1">
                                <div class="flex justify-between items-end">
                                    <label class="block text-[10px] font-mono font-bold text-slate-400 uppercase tracking-widest">{{ __('Barcode') }} <span class="text-rose-500">*</span></label>
                                    <div x-show="isChecking" class="flex items-center space-x-1 mb-1">
                                        <div class="w-1 h-1 bg-indigo-500 rounded-full animate-bounce"></div>
                                        <div class="w-1 h-1 bg-indigo-500 rounded-full animate-bounce [animation-delay:0.2s]"></div>
                                        <div class="w-1 h-1 bg-indigo-500 rounded-full animate-bounce [animation-delay:0.4s]"></div>
                                    </div>
                                </div>
                                <input type="text" name="barcode" x-model.debounce.750ms="barcode" required
                                    class="w-full bg-slate-800/50 border-slate-700 text-indigo-400 font-mono text-sm rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all placeholder-slate-600"
                                    :class="isError ? 'border-rose-500/50 ring-1 ring-rose-500/20' : (barcodeStatus ? 'border-emerald-500/50 ring-1 ring-emerald-500/20' : 'border-slate-700')"
                                    placeholder="00000-00000">
                                <p class="text-[10px] font-mono mt-1" :class="isError ? 'text-rose-400' : 'text-emerald-400'" x-text="barcodeStatus" x-show="barcodeStatus"></p>
                            </div>

                            <div class="space-y-1">
                                <label class="block text-[10px] font-mono font-bold text-slate-400 uppercase tracking-widest">{{ __('Accession Number') }} <span class="text-rose-500">*</span></label>
                                <input type="text" name="accession_number" value="{{ old('accession_number') }}" required
                                    class="w-full bg-slate-800/50 border-slate-700 text-indigo-400 font-mono text-sm rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all placeholder-slate-600"
                                    placeholder="ACC-000000">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="space-y-1">
                                <label class="block text-[10px] font-mono font-bold text-slate-400 uppercase tracking-widest">{{ __('Storage Type') }}</label>
                                <select name="storage_type" class="w-full bg-slate-800/50 border-slate-700 text-slate-200 text-sm rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500/50 appearance-none">
                                    <option value="">-- {{ __('Select Type') }} --</option>
                                    <option value="Daily newspaper" {{ old('storage_type') == 'Daily newspaper' ? 'selected' : '' }}>{{ __('Daily newspaper') }}</option>
                                    <option value="Book" {{ old('storage_type') == 'Book' ? 'selected' : '' }}>{{ __('Book') }}</option>
                                    <option value="Magazine" {{ old('storage_type') == 'Magazine' ? 'selected' : '' }}>{{ __('Magazine') }}</option>
                                </select>
                            </div>
                            <div class="space-y-1">
                                <label class="block text-[10px] font-mono font-bold text-slate-400 uppercase tracking-widest">{{ __('Quantity') }}</label>
                                <input type="number" name="quantity" value="{{ old('quantity', 1) }}" min="1" class="w-full bg-slate-800/50 border-slate-700 text-slate-200 text-sm rounded-xl px-4 py-3">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="space-y-1">
                                <label class="block text-[10px] font-mono font-bold text-slate-400 uppercase tracking-widest">{{ __('Location') }}</label>
                                <input type="text" name="location" value="{{ old('location') }}" class="w-full bg-slate-800/50 border-slate-700 text-slate-200 text-sm rounded-xl px-4 py-3" placeholder="A-01">
                            </div>
                            <div class="space-y-1">
                                <label class="block text-[10px] font-mono font-bold text-slate-400 uppercase tracking-widest">{{ __('Temp Location') }}</label>
                                <input type="text" name="temporary_location" value="{{ old('temporary_location') }}" class="w-full bg-slate-800/50 border-slate-700 text-slate-200 text-sm rounded-xl px-4 py-3" placeholder="T-01">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="space-y-1">
                                <label class="block text-[10px] font-mono font-bold text-slate-400 uppercase tracking-widest">{{ __('Status') }}</label>
                                <select name="status" class="w-full bg-slate-800/50 border-slate-700 text-slate-200 text-sm rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500/50">
                                    <option value="available" {{ old('status') == 'available' ? 'selected' : '' }}>{{ __('Available') }}</option>
                                    <option value="borrowed" {{ old('status') == 'borrowed' ? 'selected' : '' }}>{{ __('Borrowed') }}</option>
                                    <option value="lost" {{ old('status') == 'lost' ? 'selected' : '' }}>{{ __('Lost') }}</option>
                                    <option value="damaged" {{ old('status') == 'damaged' ? 'selected' : '' }}>{{ __('Damaged') }}</option>
                                </select>
                            </div>
                            <div class="space-y-1">
                                <label class="block text-[10px] font-mono font-bold text-slate-400 uppercase tracking-widest">{{ __('Order Code') }}</label>
                                <input type="text" name="order_code" value="{{ old('order_code') }}" class="w-full bg-slate-800/50 border-slate-700 text-slate-200 text-sm rounded-xl px-4 py-3" placeholder="ORD-99">
                            </div>
                        </div>

                        <div class="bg-indigo-500/5 rounded-xl p-4 border border-indigo-500/10 space-y-4">
                            <div class="flex justify-between items-center">
                                <p class="text-[10px] font-mono font-bold text-indigo-400 uppercase tracking-widest">{{ __('Technical Details') }}</p>
                                <div class="flex items-center space-x-2">
                                    <input type="checkbox" name="waits_for_print" value="1" id="waits_for_print" {{ old('waits_for_print') ? 'checked' : '' }} 
                                           class="rounded border-slate-700 bg-slate-800 text-indigo-500 focus:ring-0 w-3 h-3 transition-colors">
                                    <label for="waits_for_print" class="text-[9px] font-bold text-slate-500 uppercase tracking-tighter">{{ __('Wait for print') }}</label>
                                </div>
                            </div>

                            <div class="grid grid-cols-3 gap-2">
                                <div class="space-y-1">
                                    <label class="text-[8px] font-mono text-slate-500 block uppercase ml-1">{{ __('Day') }}</label>
                                    <input type="number" name="day" placeholder="DD" value="{{ old('day') }}" class="w-full bg-transparent border-slate-700 text-slate-200 font-mono text-center text-sm rounded-lg py-1.5 focus:ring-1 focus:ring-indigo-500">
                                </div>
                                <div class="space-y-1">
                                    <label class="text-[8px] font-mono text-slate-500 block uppercase ml-1">{{ __('Month/Season') }}</label>
                                    <input type="text" name="month_season" placeholder="MM/S" value="{{ old('month_season') }}" class="w-full bg-transparent border-slate-700 text-slate-200 font-mono text-center text-sm rounded-lg py-1.5 focus:ring-1 focus:ring-indigo-500">
                                </div>
                                <div class="space-y-1">
                                    <label class="text-[8px] font-mono text-slate-500 block uppercase ml-1">{{ __('Year') }}</label>
                                    <input type="number" name="year" placeholder="YYYY" value="{{ old('year') }}" class="w-full bg-transparent border-slate-700 text-slate-200 font-mono text-center text-sm rounded-lg py-1.5 focus:ring-1 focus:ring-indigo-500">
                                </div>
                            </div>

                            <div class="space-y-1">
                                <label class="text-[8px] font-mono text-slate-500 block uppercase ml-1">{{ __('Volume/Issue') }}</label>
                                <input type="text" name="volume_issue" value="{{ old('volume_issue') }}" class="w-full bg-transparent border-slate-700 text-slate-200 text-sm rounded-lg px-4 py-2 focus:ring-1 focus:ring-indigo-500 border" placeholder="Vol 1, No 2">
                            </div>

                            <div class="grid grid-cols-2 gap-2">
                                <div class="space-y-1">
                                    <label class="text-[8px] font-mono text-slate-500 block uppercase ml-1">{{ __('Shelf') }}</label>
                                    <input type="text" name="shelf" value="{{ old('shelf') }}" class="w-full bg-transparent border-slate-700 text-slate-200 text-sm rounded-lg px-4 py-2 focus:ring-1 focus:ring-indigo-500 border" placeholder="S-42">
                                </div>
                                <div class="space-y-1">
                                    <label class="text-[8px] font-mono text-slate-500 block uppercase ml-1">{{ __('Position') }}</label>
                                    <input type="text" name="shelf_position" value="{{ old('shelf_position') }}" class="w-full bg-transparent border-slate-700 text-slate-200 text-sm rounded-lg px-4 py-2 focus:ring-1 focus:ring-indigo-500 border" placeholder="P-05">
                                </div>
                            </div>
                        </div>

                        <div class="space-y-1">
                            <label class="block text-[10px] font-mono font-bold text-slate-400 uppercase tracking-widest">{{ __('Notes') }}</label>
                            <textarea name="notes" rows="2" class="w-full bg-slate-800/50 border-slate-700 text-slate-200 text-sm rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500/50" placeholder="...">{{ old('notes') }}</textarea>
                        </div>

                        <div class="pt-4 border-t border-slate-800">
                            <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-500 text-white font-bold py-4 rounded-xl transition-all shadow-lg active:scale-95 flex items-center justify-center group tracking-widest uppercase text-sm">
                                <svg class="w-5 h-5 mr-2 group-hover:animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                {{ __('Save Item') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- RIGHT COLUMN: Distributed Items List (8/12) -->
        <div class="lg:col-span-8" x-data="{ 
            showModal: false, 
            selectedItem: null,
            translations: {
                idPrefix: '{{ __('ID #') }}',
                status: {
                    available: '{{ __('available') }}',
                    borrowed: '{{ __('borrowed') }}',
                    lost: '{{ __('lost') }}',
                    damaged: '{{ __('damaged') }}'
                },
                storage: {
                    'Daily newspaper': '{{ __('Daily newspaper') }}',
                    'Book': '{{ __('Book') }}',
                    'Magazine': '{{ __('Magazine') }}'
                },
                na: '{{ __('N/A') }}'
            }
        }">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden min-h-[600px]">
                <div class="p-6 border-b border-slate-50 flex justify-between items-center bg-slate-50/50">
                    <div class="flex items-center">
                        <h2 class="text-lg font-bold text-slate-800">{{ __('Distributed Items') }}</h2>
                        <span class="ml-3 bg-indigo-100 text-indigo-700 text-[10px] uppercase font-bold px-2 py-0.5 rounded-full">{{ $record->items->count() }} {{ __('UNIT(S)') }}</span>
                    </div>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-100">
                        <thead>
                            <tr class="bg-slate-50/30">
                                <th class="px-6 py-4 text-left text-[10px] font-mono font-bold text-slate-400 uppercase tracking-widest">{{ __('Identification') }}</th>
                                <th class="px-6 py-4 text-left text-[10px] font-mono font-bold text-slate-400 uppercase tracking-widest">{{ __('Storage Details') }}</th>
                                <th class="px-6 py-4 text-left text-[10px] font-mono font-bold text-slate-400 uppercase tracking-widest">{{ __('Status') }}</th>
                                <th class="px-6 py-4 text-left text-[10px] font-mono font-bold text-slate-400 uppercase tracking-widest">{{ __('Metadata') }}</th>
                                <th class="px-6 py-4"></th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-slate-50">
                            @forelse($record->items as $item)
                                <tr class="hover:bg-indigo-50/30 transition-colors group">
                                    <td class="px-6 py-5">
                                        <div class="flex flex-col">
                                            <span class="text-sm font-bold text-slate-900 font-mono tracking-tight">{{ $item->barcode }}</span>
                                            <span class="text-xs text-slate-400 font-mono uppercase mt-1">#{{ $item->accession_number }}</span>
                                            @if(file_exists(public_path('barcode/' . $item->barcode . '.svg')))
                                                <div class="mt-2 p-1 bg-white border border-slate-200 rounded lg:w-40">
                                                    <img src="{{ asset('barcode/' . $item->barcode . '.svg') }}" alt="Barcode" class="w-full h-auto">
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-5">
                                        <div class="flex flex-col">
                                            <span class="text-xs font-bold text-slate-700">{{ __($item->storage_type) }}</span>
                                            <div class="flex items-center space-x-2 mt-1">
                                                <span class="text-[10px] py-0.5 px-1.5 bg-slate-100 text-slate-500 rounded font-bold">{{ $item->location ?? 'N/A' }}</span>
                                                <span class="text-[10px] text-slate-300">/</span>
                                                <span class="text-[10px] py-0.5 px-1.5 bg-slate-100 text-slate-500 rounded font-bold">{{ $item->shelf ?? 'N/A' }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-5">
                                        @php
                                            $statusColors = [
                                                'available' => 'bg-emerald-100 text-emerald-700',
                                                'borrowed' => 'bg-blue-100 text-blue-700',
                                                'lost' => 'bg-rose-100 text-rose-700',
                                                'damaged' => 'bg-amber-100 text-orange-700',
                                            ];
                                            $colorClass = $statusColors[strtolower($item->status)] ?? 'bg-slate-100 text-slate-700';
                                        @endphp
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[10px] font-extrabold uppercase tracking-widest {{ $colorClass }}">
                                            <span class="w-1 h-1 rounded-full bg-current mr-2"></span>
                                            {{ __($item->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-5">
                                        <div class="text-[11px] text-slate-500 font-mono leading-relaxed">
                                            @if($item->day || $item->month_season || $item->year)
                                                <div class="text-slate-800 font-bold">
                                                    {{ str_pad($item->day, 2, '0', STR_PAD_LEFT) }}/{{ $item->month_season }}/{{ $item->year }}
                                                </div>
                                            @endif
                                            @if($item->volume_issue)
                                                <div class="mt-0.5 italic text-slate-400 capitalize">{{ $item->volume_issue }}</div>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-5 text-right">
                                        <button @click="selectedItem = {{ json_encode($item) }}; showModal = true" class="p-2 text-slate-300 hover:text-indigo-600 transition-colors bg-slate-50 hover:bg-indigo-50 rounded-lg">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path></svg>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-24 text-center">
                                        <div class="flex flex-col items-center">
                                            <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-4 border border-slate-100">
                                                <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0a2 2 0 01-2 2H6a2 2 0 01-2-2m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                            </div>
                                            <h3 class="text-slate-500 font-bold uppercase tracking-widest text-xs">{{ __('No items distributed yet.') }}</h3>
                                            <p class="text-slate-400 text-[10px] mt-1">{{ __('Start by adding the first copy using the left panel.') }}</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Detail Modal -->
            <div x-show="showModal" x-cloak class="fixed inset-0 z-[100] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                    <div x-show="showModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click="showModal = false" class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm transition-opacity" aria-hidden="true"></div>

                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                    <div x-show="showModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                         class="inline-block align-middle bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full border border-slate-100">
                        
                        <div class="relative">
                            <!-- Modal Header -->
                            <div class="px-8 py-6 bg-slate-50 border-b border-slate-100 flex justify-between items-center">
                                <div>
                                    <h3 class="text-xl font-extrabold text-slate-900 tracking-tight" id="modal-title">{{ __('Item Details') }}</h3>
                                    <p class="text-[10px] font-mono font-bold text-indigo-500 uppercase tracking-widest mt-1" x-text="translations.idPrefix + selectedItem?.barcode"></p>
                                </div>
                                <button @click="showModal = false" class="p-2 rounded-xl bg-white border border-slate-200 text-slate-400 hover:text-slate-900 hover:border-slate-300 transition-all shadow-sm">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>
                            </div>

                            <!-- Modal Content -->
                            <div class="px-8 py-8">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                    <!-- Left Side: Barcode & Basic -->
                                    <div class="space-y-6">
                                        <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100 flex flex-col items-center justify-center">
                                            <template x-if="selectedItem?.barcode">
                                                <div class="flex flex-col items-center">
                                                    <img :src="'/barcode/' + selectedItem.barcode + '.svg'" alt="Barcode" class="h-20 w-auto mb-3" onerror="this.style.display='none'">
                                                    <span class="font-mono text-lg font-bold text-slate-900 tracking-widest" x-text="selectedItem.barcode"></span>
                                                </div>
                                            </template>
                                        </div>

                                        <div class="space-y-4">
                                            <div>
                                                <label class="text-[9px] font-mono font-bold text-slate-400 uppercase tracking-[0.2em] block mb-1">{{ __('Accession Number') }}</label>
                                                <p class="text-sm font-bold text-slate-800 font-mono" x-text="'#' + selectedItem?.accession_number"></p>
                                            </div>
                                            <div class="grid grid-cols-2 gap-4">
                                                <div>
                                                    <label class="text-[9px] font-mono font-bold text-slate-400 uppercase tracking-[0.2em] block mb-1">{{ __('Status') }}</label>
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[9px] font-extrabold uppercase tracking-widest bg-indigo-100 text-indigo-700" 
                                                          x-text="translations.status[selectedItem?.status] || selectedItem?.status"></span>
                                                </div>
                                                <div>
                                                    <label class="text-[9px] font-mono font-bold text-slate-400 uppercase tracking-[0.2em] block mb-1">{{ __('Quantity') }}</label>
                                                    <p class="text-sm font-bold text-slate-800" x-text="selectedItem?.quantity"></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Right Side: Logistics & Tech -->
                                    <div class="space-y-6">
                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <label class="text-[9px] font-mono font-bold text-slate-400 uppercase tracking-[0.2em] block mb-1">{{ __('Storage Type') }}</label>
                                                <p class="text-sm font-semibold text-slate-700" x-text="translations.storage[selectedItem?.storage_type] || selectedItem?.storage_type || translations.na"></p>
                                            </div>
                                            <div>
                                                <label class="text-[9px] font-mono font-bold text-slate-400 uppercase tracking-[0.2em] block mb-1">{{ __('Order Code') }}</label>
                                                <p class="text-sm font-semibold text-slate-700" x-text="selectedItem?.order_code || translations.na"></p>
                                            </div>
                                        </div>

                                        <div class="p-4 bg-indigo-50/50 rounded-2xl border border-indigo-100 space-y-3">
                                            <div class="flex justify-between items-center pb-2 border-b border-indigo-100/50">
                                                <span class="text-[9px] font-bold text-indigo-500 uppercase">{{ __('Location Details') }}</span>
                                            </div>
                                            <div class="grid grid-cols-2 gap-4">
                                                <div>
                                                    <label class="text-[8px] font-bold text-slate-400 uppercase">{{ __('Position') }}</label>
                                                    <p class="text-xs font-bold text-slate-800" x-text="(selectedItem?.location || '-') + ' / ' + (selectedItem?.shelf || '-')"></p>
                                                </div>
                                                <div>
                                                    <label class="text-[8px] font-bold text-slate-400 uppercase">{{ __('Shelf Position') }}</label>
                                                    <p class="text-xs font-bold text-slate-800" x-text="selectedItem?.shelf_position || '-'"></p>
                                                </div>
                                            </div>
                                            <div>
                                                <label class="text-[8px] font-bold text-slate-400 uppercase">{{ __('Temp Location') }}</label>
                                                <p class="text-xs font-bold text-slate-800" x-text="selectedItem?.temporary_location || translations.na"></p>
                                            </div>
                                        </div>

                                        <div class="space-y-3">
                                            <label class="text-[9px] font-mono font-bold text-slate-400 uppercase tracking-[0.2em] block mb-1">{{ __('Metadata & Notes') }}</label>
                                            <div class="text-[11px] text-slate-600 bg-slate-50 p-3 rounded-xl border border-slate-100">
                                                <p x-show="selectedItem?.volume_issue" class="mb-2 italic border-b border-slate-200 pb-1" x-text="selectedItem?.volume_issue"></p>
                                                <p class="whitespace-pre-line" x-text="selectedItem?.notes || '{{ __('No notes.') }}'"></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal Footer -->
                            <div class="px-8 py-5 bg-slate-50 border-t border-slate-100 flex justify-end items-center space-x-3">
                                <button @click="showModal = false" class="px-6 py-2.5 bg-white border border-slate-200 text-slate-600 rounded-xl hover:bg-slate-50 transition-all font-bold text-xs uppercase tracking-widest shadow-sm">
                                    {{ __('Close') }}
                                </button>
                                <button class="px-6 py-2.5 bg-indigo-600 text-white rounded-xl hover:bg-indigo-500 transition-all font-bold text-xs uppercase tracking-widest shadow-lg shadow-indigo-100">
                                    {{ __('Edit Item') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div> <!-- KẾT THÚC GRID -->
</div>

<style>
    body {
        background-color: #f8fafc;
    }
    input::placeholder {
        font-family: 'JetBrains Mono', monospace;
        letter-spacing: -0.025em;
    }
    .font-mono {
        font-family: 'JetBrains Mono', monospace;
    }
</style>
@endsection
