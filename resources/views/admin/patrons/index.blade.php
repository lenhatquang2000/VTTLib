@extends('layouts.admin')

@section('content')
<div class="space-y-6 pb-12">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">{{ __('Patron Management') }}</h1>
            <p class="text-slate-500 text-sm font-medium mt-1">{{ __('Manage and audit library member identities.') }}</p>
        </div>
        <div class="flex items-center space-x-3 text-[10px] font-black uppercase tracking-widest">
            <span class="text-slate-400 mr-2">{{ __('Lọc theo trạng thái') }}:</span>
            <button class="text-indigo-600 border-b-2 border-indigo-600 pb-1">{{ __('Tất cả') }}</button>
            <button class="text-slate-400 hover:text-slate-600 pb-1">{{ __('Đang hoạt động') }}</button>
            <button class="text-slate-400 hover:text-slate-600 pb-1">{{ __('Đã khóa') }}</button>
            <a href="{{ route('admin.patrons.create') }}" class="ml-4 bg-indigo-600 text-white px-6 py-2.5 rounded-xl shadow-md transition-all hover:bg-indigo-500">
                {{ __('Add New Patron') }}
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-100 rounded-xl p-4 flex items-center space-x-3 shadow-sm">
            <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            <span class="text-sm text-emerald-600 font-bold">{{ session('success') }}</span>
        </div>
    @endif

    <!-- Card Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8">
        @forelse($patrons as $patron)
            <div class="group relative bg-white border border-slate-200 rounded-xl p-5 shadow-sm hover:shadow-md transition-all duration-200 w-full max-w-[420px] mx-auto min-h-[240px] overflow-hidden">
                <!-- Logo Watermark Background -->
                <div class="absolute inset-0 flex items-center justify-center opacity-[0.03] pointer-events-none">
                    <img src="{{ asset('assets/imgs/logo-vttu.png') }}" class="w-1/2">
                </div>
                
                <!-- Top Row: Label & Checkbox -->
                <div class="flex justify-between items-start mb-4">
                    <span class="text-[12px] font-black text-indigo-700 tracking-tight uppercase">{{ __('Library Card') }}</span>
                    <label class="cursor-pointer">
                        <input type="checkbox" name="selected_patrons[]" value="{{ $patron->id }}" class="w-4 h-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                    </label>
                </div>

                <!-- Middle Content -->
                <div class="flex space-x-5">
                    <!-- Left: Profile Photo -->
                    <div class="w-[110px] h-[140px] flex-shrink-0 bg-slate-100 border border-slate-200 overflow-hidden">
                        @if($patron->profile_image)
                            <img src="{{ asset('storage/' . $patron->profile_image) }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center">
                                <svg class="w-12 h-12 text-slate-300" fill="currentColor" viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                            </div>
                        @endif
                    </div>

                    <!-- Right: Info Details -->
                    <div class="flex-1 flex flex-col pt-1">
                        <h2 class="text-[16px] font-black text-indigo-700 uppercase leading-none mb-4 truncate">{{ $patron->display_name }}</h2>
                        
                        <div class="space-y-3 mb-4">
                            <div class="text-[12px] font-bold text-indigo-600">
                                {{ date('d/m/Y', strtotime($patron->registration_date)) }} - {{ date('d/m/Y', strtotime($patron->expiry_date)) }}
                            </div>
                            <!-- Barcode Area -->
                            <div class="relative">
                                <div class="h-[45px] w-full bg-white flex items-center justify-start overflow-hidden">
                                    {!! $barcodeService->renderSvg($patron->patron_code) !!}
                                </div>
                                <div class="text-[10px] font-black font-mono text-indigo-700 text-left tracking-[0.2em] mt-1">
                                    {{ $patron->patron_code }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card Overlay for Quick Actions (Hidden but functional) -->
                <div class="absolute bottom-3 right-5 flex items-center space-x-2 opacity-0 group-hover:opacity-100 transition-opacity">
                    <form action="{{ route('admin.patrons.toggle-status', $patron->id) }}" method="POST" class="inline">
                        @csrf @method('PATCH')
                        <button type="submit" class="p-1.5 bg-slate-50 border border-slate-200 rounded-lg text-slate-400 hover:text-indigo-600 shadow-sm" title="{{ __('Lock/Unlock') }}">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 00-2 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        </button>
                    </form>
                    <button onclick="openRenewModal({{ json_encode(['id' => $patron->id, 'name' => $patron->display_name, 'expiry' => $patron->expiry_date]) }})" 
                        class="p-1.5 bg-slate-50 border border-slate-200 rounded-lg text-slate-400 hover:text-indigo-600 shadow-sm">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </button>
                    <form action="{{ route('admin.patrons.destroy', $patron->id) }}" method="POST" class="inline">
                        @csrf @method('DELETE')
                        <button type="submit" class="p-1.5 bg-slate-50 border border-slate-200 rounded-lg text-slate-400 hover:text-rose-500 shadow-sm">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                    </form>
                </div>

                <!-- Status Dot -->
                <div class="absolute bottom-3 left-5 flex items-center space-x-1.5">
                    <div class="w-2 h-2 rounded-full @if($patron->card_status == 'normal') bg-emerald-500 @else bg-rose-500 @endif shadow-[0_0_8px_rgba(16,185,129,0.5)]"></div>
                    <span class="text-[8px] font-black uppercase tracking-widest text-slate-400">{{ $patron->card_status == 'normal' ? __('Active') : __('Locked') }}</span>
                    @if($patron->is_waiting_for_print)
                        <span class="w-1.5 h-1.5 rounded-full bg-orange-400 ml-2"></span>
                        <span class="text-[8px] font-black uppercase tracking-widest text-orange-400">{{ __('Queue') }}</span>
                    @endif
                </div>
            </div>
        @empty
            <div class="col-span-full py-20 bg-white border border-dashed border-slate-200 rounded-xl flex flex-col items-center">
                <svg class="w-12 h-12 text-slate-200 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857M9 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zm-3 4a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                <p class="text-slate-400 font-bold uppercase tracking-widest text-xs">{{ __('No patrons found') }}</p>
            </div>
        @endforelse
    </div>
    
    @if($patrons->hasPages())
        <div class="mt-12 flex justify-center">
            {{ $patrons->links() }}
        </div>
    @endif
</div>

<!-- Renew Modal (unchanged style but clean) -->
<div id="renewModal" class="fixed inset-0 z-[100] hidden">
    <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" onclick="closeRenewModal()"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-md p-4">
        <div class="bg-white rounded-2xl shadow-2xl border border-slate-100 overflow-hidden transform transition-all">
            <div class="px-8 py-5 bg-slate-50 border-b border-slate-100">
                <h3 class="text-lg font-black text-slate-800 tracking-tight uppercase">{{ __('Gia hạn thẻ') }}</h3>
                <p class="text-indigo-600 text-[10px] font-bold mt-1 uppercase" id="renewPatronName"></p>
            </div>
            <form id="renewForm" method="POST" class="p-8 space-y-5">
                @csrf @method('PATCH')
                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">{{ __('Ngày hết hạn mới') }}</label>
                    <input type="date" name="expiry_date" id="renew_expiry_date" required 
                        class="w-full bg-slate-50 border-slate-200 rounded-xl px-5 py-3 text-sm font-bold text-slate-900 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all">
                </div>
                <div class="flex space-x-3 pt-4 border-t border-slate-50">
                    <button type="button" onclick="closeRenewModal()" class="flex-1 bg-white border border-slate-200 text-slate-400 py-3 rounded-xl uppercase text-[10px] font-black hover:bg-slate-50 transition-all">{{ __('Hủy') }}</button>
                    <button type="submit" class="flex-1 bg-indigo-600 text-white py-3 rounded-xl uppercase text-[10px] font-black shadow-md hover:bg-indigo-500 transition-all">{{ __('Cập nhật') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openRenewModal(patron) {
        document.getElementById('renewForm').action = `/topsecret/patrons/${patron.id}/renew`;
        document.getElementById('renewPatronName').textContent = patron.name;
        document.getElementById('renew_expiry_date').value = patron.expiry;
        document.getElementById('renewModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
    function closeRenewModal() {
        document.getElementById('renewModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }
</script>
@endsection
