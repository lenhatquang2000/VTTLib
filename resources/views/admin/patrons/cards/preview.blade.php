@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-800 p-6 transition-colors">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-900 dark:text-slate-100">Xem Trước Thẻ Độc Giả</h1>
                <p class="text-slate-600 dark:text-slate-400 mt-1">{{ $patron->name }} - {{ $patron->patron_code }}</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.patrons.cards.index') }}" class="px-4 py-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">
                    Quay lại
                </a>
                <form method="POST" action="{{ route('admin.patrons.cards.generate') }}" class="inline">
                    @csrf
                    <input type="hidden" name="patron_ids[]" value="{{ $patron->id }}">
                    <input type="hidden" name="layout" value="single">
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        In thẻ
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Card Preview -->
    <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-800 p-8 transition-colors">
        <div class="flex justify-center">
            <div style="transform: scale(2); transform-origin: center;">
                <div style="width: 85.6mm; height: 54mm; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 8px; padding: 3mm; color: white; position: relative; overflow: hidden; box-sizing: border-box;">
                    <!-- Background pattern -->
                    <div style="position: absolute; top: -50%; right: -50%; width: 200%; height: 200%; background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%); transform: rotate(45deg);"></div>
                    
                    <!-- Header -->
                    <div style="text-align: center; margin-bottom: 2mm; position: relative; z-index: 1;">
                        <div style="width: 8mm; height: 8mm; background: white; border-radius: 50%; margin: 0 auto 1mm; display: flex; align-items: center; justify-content: center; font-weight: bold; color: #667eea; font-size: 4mm;">
                            TL
                        </div>
                        <div style="font-size: 3mm; font-weight: bold; text-transform: uppercase; letter-spacing: 0.5px; margin: 0;">
                            Thẻ Độc Giả
                        </div>
                    </div>
                    
                    <!-- Body -->
                    <div style="display: flex; gap: 3mm; position: relative; z-index: 1;">
                        <!-- Photo Section -->
                        <div style="width: 18mm; height: 24mm; background: white; border-radius: 4px; display: flex; align-items: center; justify-content: center; overflow: hidden;">
                            @if($patron->avatar)
                                <img src="{{ asset('storage/' . $patron->avatar) }}" alt="{{ $patron->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                            @else
                                <div style="width: 100%; height: 100%; background: #f0f0f0; display: flex; align-items: center; justify-content: center; color: #999; font-size: 8mm;">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                                    </svg>
                                </div>
                            @endif
                        </div>
                        
                        <!-- Info Section -->
                        <div style="flex: 1; font-size: 2.5mm; line-height: 1.3;">
                            <div style="margin-bottom: 1mm; display: flex; align-items: center;">
                                <span style="font-weight: bold; margin-right: 1mm; min-width: 15mm;">Họ tên:</span>
                                <span>{{ $patron->name }}</span>
                            </div>
                            <div style="margin-bottom: 1mm; display: flex; align-items: center;">
                                <span style="font-weight: bold; margin-right: 1mm; min-width: 15mm;">Mã số:</span>
                                <span>{{ $patron->patron_code }}</span>
                            </div>
                            <div style="margin-bottom: 1mm; display: flex; align-items: center;">
                                <span style="font-weight: bold; margin-right: 1mm; min-width: 15mm;">Ngày sinh:</span>
                                <span>{{ $patron->date_of_birth ? \Carbon\Carbon::parse($patron->date_of_birth)->format('d/m/Y') : 'N/A' }}</span>
                            </div>
                            <div style="margin-bottom: 1mm; display: flex; align-items: center;">
                                <span style="font-weight: bold; margin-right: 1mm; min-width: 15mm;">Giới tính:</span>
                                <span>{{ $patron->gender == 'male' ? 'Nam' : ($patron->gender == 'female' ? 'Nữ' : 'Khác') }}</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Barcode Section -->
                    <div style="margin-top: 2mm; text-align: center; position: relative; z-index: 1;">
                        <div style="height: 8mm; margin-bottom: 1mm;">
                            {!! $patron->barcode_svg !!}
                        </div>
                        <div style="font-size: 2mm; font-family: 'Courier New', monospace; letter-spacing: 0.5px;">
                            {{ $patron->patron_code }}
                        </div>
                    </div>
                    
                    <!-- Footer -->
                    <div style="margin-top: 2mm; text-align: center; font-size: 2mm; opacity: 0.9; position: relative; z-index: 1;">
                        <div>Thư viện - Hết hạn: <span style="font-weight: bold;">{{ $patron->expiry_date ? \Carbon\Carbon::parse($patron->expiry_date)->format('d/m/Y') : 'Không giới hạn' }}</span></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Patron Info -->
    <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-800 p-6 transition-colors">
        <h2 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-4">Thông tin độc giả</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Họ tên</label>
                <p class="mt-1 text-sm text-slate-900 dark:text-slate-100">{{ $patron->name }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Mã độc giả</label>
                <p class="mt-1 text-sm text-slate-900 dark:text-slate-100 font-mono">{{ $patron->patron_code }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Email</label>
                <p class="mt-1 text-sm text-slate-900 dark:text-slate-100">{{ $patron->email }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Nhóm độc giả</label>
                <p class="mt-1 text-sm text-slate-900 dark:text-slate-100">{{ $patron->patronGroup->name ?? 'Chưa phân loại' }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Ngày sinh</label>
                <p class="mt-1 text-sm text-slate-900 dark:text-slate-100">{{ $patron->date_of_birth ? \Carbon\Carbon::parse($patron->date_of_birth)->format('d/m/Y') : 'N/A' }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Giới tính</label>
                <p class="mt-1 text-sm text-slate-900 dark:text-slate-100">{{ $patron->gender == 'male' ? 'Nam' : ($patron->gender == 'female' ? 'Nữ' : 'Khác') }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Ngày đăng ký</label>
                <p class="mt-1 text-sm text-slate-900 dark:text-slate-100">{{ \Carbon\Carbon::parse($patron->registration_date)->format('d/m/Y') }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Ngày hết hạn</label>
                <p class="mt-1 text-sm text-slate-900 dark:text-slate-100">{{ $patron->expiry_date ? \Carbon\Carbon::parse($patron->expiry_date)->format('d/m/Y') : 'Không giới hạn' }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
