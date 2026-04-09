@extends('layouts.admin')

@section('content')
<div class="space-y-8">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold">{{ $policy->name }}</h1>
            <p class="text-sm text-gray-400 mt-1">
                {{ __('Nhóm bạn đọc') }}: {{ $policy->patronGroup->name ?? 'N/A' }}
                @if($policy->notes)
                <br>{{ __('Ghi chú') }}: {{ $policy->notes }}
                @endif
            </p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.circulation.policies.edit', $policy) }}" class="btn-secondary">
                <i class="fas fa-edit mr-2"></i>{{ __('Sửa') }}
            </a>
            <a href="{{ route('admin.circulation.policies.index') }}" class="btn-secondary">
                <i class="fas fa-arrow-left mr-2"></i>{{ __('Quay lại') }}
            </a>
        </div>
    </div>

    <!-- Status Badge -->
    <div class="flex items-center gap-4">
        @if($policy->is_active)
            <span class="px-3 py-1 bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300 rounded text-sm font-medium">
                <i class="fas fa-check-circle mr-2"></i>{{ __('Kích hoạt') }}
            </span>
        @else
            <span class="px-3 py-1 bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-300 rounded text-sm font-medium">
                <i class="fas fa-pause-circle mr-2"></i>{{ __('Vô hiệu') }}
            </span>
        @endif
    </div>

    <!-- Policy Summary -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Loan Settings -->
        <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-gray-100 dark:border-slate-800 p-6">
            <h3 class="text-lg font-semibold mb-4 flex items-center">
                <i class="fas fa-book-reader mr-2 text-blue-500"></i>
                {{ __('Cài đặt mượn sách') }}
            </h3>
            
            <div class="space-y-3">
                <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-slate-700">
                    <span class="text-sm font-medium">{{ __('Số ngày mượn tối đa') }}</span>
                    <span class="text-sm font-bold text-blue-600">{{ $policySummary['loan']['max_days'] }} {{ __('ngày') }}</span>
                </div>
                
                <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-slate-700">
                    <span class="text-sm font-medium">{{ __('Số sách mượn tối đa') }}</span>
                    <span class="text-sm font-bold text-blue-600">{{ $policySummary['loan']['max_items'] }} {{ __('sách') }}</span>
                </div>
                
                <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-slate-700">
                    <span class="text-sm font-medium">{{ __('Số lần gia hạn tối đa') }}</span>
                    <span class="text-sm font-bold text-blue-600">{{ $policySummary['loan']['max_renewals'] }} {{ __('lần') }}</span>
                </div>
                
                <div class="flex justify-between items-center py-2">
                    <span class="text-sm font-medium">{{ __('Ngày gia hạn mỗi lần') }}</span>
                    <span class="text-sm font-bold text-blue-600">{{ $policySummary['loan']['renewal_days'] }} {{ __('ngày') }}</span>
                </div>
            </div>
        </div>

        <!-- Fine Settings -->
        <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-gray-100 dark:border-slate-800 p-6">
            <h3 class="text-lg font-semibold mb-4 flex items-center">
                <i class="fas fa-coins mr-2 text-yellow-500"></i>
                {{ __('Cài đặt phạt') }}
            </h3>
            
            <div class="space-y-3">
                <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-slate-700">
                    <span class="text-sm font-medium">{{ __('Phí phạt/ngày') }}</span>
                    <span class="text-sm font-bold text-yellow-600">{{ number_format($policySummary['fines']['per_day']) }} VND</span>
                </div>
                
                <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-slate-700">
                    <span class="text-sm font-medium">{{ __('Phạt tối đa') }}</span>
                    <span class="text-sm font-bold text-yellow-600">{{ number_format($policySummary['fines']['max_fine']) }} VND</span>
                </div>
                
                <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-slate-700">
                    <span class="text-sm font-medium">{{ __('Ngày ân hạn') }}</span>
                    <span class="text-sm font-bold text-yellow-600">{{ $policySummary['fines']['grace_period'] }} {{ __('ngày') }}</span>
                </div>
                
                <div class="flex justify-between items-center py-2">
                    <span class="text-sm font-medium">{{ __('Nợ tối đa được phép') }}</span>
                    <span class="text-sm font-bold text-yellow-600">{{ number_format($policySummary['fines']['max_outstanding']) }} VND</span>
                </div>
            </div>
        </div>

        <!-- Reading Room Settings -->
        <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-gray-100 dark:border-slate-800 p-6">
            <h3 class="text-lg font-semibold mb-4 flex items-center">
                <i class="fas fa-chair mr-2 text-purple-500"></i>
                {{ __('Cài đặt mượn đọc tại chỗ') }}
            </h3>
            
            @if($policySummary['reading_room']['allowed'])
                <div class="space-y-3">
                    <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-slate-700">
                        <span class="text-sm font-medium">{{ __('Trạng thái') }}</span>
                        <span class="px-2 py-1 bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300 rounded text-xs font-medium">
                            {{ __('Cho phép') }}
                        </span>
                    </div>
                    
                    <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-slate-700">
                        <span class="text-sm font-medium">{{ __('Số tài liệu tối đa') }}</span>
                        <span class="text-sm font-bold text-purple-600">{{ $policySummary['reading_room']['max_items'] }} {{ __('tài liệu') }}</span>
                    </div>
                    
                    <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-slate-700">
                        <span class="text-sm font-medium">{{ __('Thời gian mượn tối đa') }}</span>
                        <span class="text-sm font-bold text-purple-600">{{ $policySummary['reading_room']['max_hours'] }} {{ __('giờ') }}</span>
                    </div>
                    
                    <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-slate-700">
                        <span class="text-sm font-medium">{{ __('Giờ trả mặc định') }}</span>
                        <span class="text-sm font-bold text-purple-600">{{ $policySummary['reading_room']['due_time'] }}</span>
                    </div>
                    
                    <div class="flex justify-between items-center py-2">
                        <span class="text-sm font-medium">{{ __('Phí phạt/giờ') }}</span>
                        <span class="text-sm font-bold text-purple-600">{{ number_format($policySummary['reading_room']['fine_per_hour']) }} VND</span>
                    </div>
                </div>
            @else
                <div class="text-center py-8">
                    <i class="fas fa-times-circle text-4xl text-red-400 mb-3"></i>
                    <p class="text-sm text-gray-500">{{ __('Không cho phép sử dụng mượn đọc tại chỗ') }}</p>
                </div>
            @endif
        </div>

        <!-- Hold/Reserve Settings -->
        <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-gray-100 dark:border-slate-800 p-6">
            <h3 class="text-lg font-semibold mb-4 flex items-center">
                <i class="fas fa-hand-holding mr-2 text-green-500"></i>
                {{ __('Cài đặt giữ lại sách') }}
            </h3>
            
            @if($policySummary['holds']['allowed'])
                <div class="space-y-3">
                    <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-slate-700">
                        <span class="text-sm font-medium">{{ __('Trạng thái') }}</span>
                        <span class="px-2 py-1 bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300 rounded text-xs font-medium">
                            {{ __('Cho phép') }}
                        </span>
                    </div>
                    
                    <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-slate-700">
                        <span class="text-sm font-medium">{{ __('Số giữ lại tối đa') }}</span>
                        <span class="text-sm font-bold text-green-600">{{ $policySummary['holds']['max_holds'] }} {{ __('giữ lại') }}</span>
                    </div>
                    
                    <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-slate-700">
                        <span class="text-sm font-medium">{{ __('Ngày hết hạn') }}</span>
                        <span class="text-sm font-bold text-green-600">{{ $policySummary['holds']['expiry_days'] }} {{ __('ngày') }}</span>
                    </div>
                    
                    <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-slate-700">
                        <span class="text-sm font-medium">{{ __('Ngày thông báo') }}</span>
                        <span class="text-sm font-bold text-green-600">{{ $policySummary['holds']['notification_days'] }} {{ __('ngày') }}</span>
                    </div>
                    
                    <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-slate-700">
                        <span class="text-sm font-medium">{{ __('Phí hủy giữ lại') }}</span>
                        <span class="text-sm font-bold text-green-600">{{ number_format($policySummary['holds']['cancellation_fee']) }} VND</span>
                    </div>
                    
                    <div class="flex justify-between items-center py-2">
                        <span class="text-sm font-medium">{{ __('Gia hạn') }}</span>
                        <span class="text-sm font-bold text-green-600">
                            @if($policySummary['holds']['can_renew'])
                                {{ __('Cho phép') }} ({{ $policySummary['holds']['max_renewals'] }} {{ __('lần') }})
                            @else
                                {{ __('Không cho phép') }}
                            @endif
                        </span>
                    </div>
                </div>
            @else
                <div class="text-center py-8">
                    <i class="fas fa-times-circle text-4xl text-red-400 mb-3"></i>
                    <p class="text-sm text-gray-500">{{ __('Không cho phép đặt giữ lại sách') }}</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Actions -->
    <div class="flex justify-end gap-2">
        <form action="{{ route('admin.circulation.policies.toggle', $policy) }}" method="POST" class="inline">
            @csrf
            <button type="submit" class="btn-secondary">
                <i class="fas fa-{{ $policy->is_active ? 'pause' : 'play' }} mr-2"></i>
                {{ $policy->is_active ? __('Vô hiệu hóa') : __('Kích hoạt') }}
            </button>
        </form>
        
        <form action="{{ route('admin.circulation.policies.duplicate', $policy) }}" method="POST" class="inline">
            @csrf
            <button type="submit" class="btn-secondary">
                <i class="fas fa-copy mr-2"></i>{{ __('Sao chép') }}
            </button>
        </form>
        
        <a href="{{ route('admin.circulation.policies.edit', $policy) }}" class="btn-primary">
            <i class="fas fa-edit mr-2"></i>{{ __('Sửa chính sách') }}
        </a>
    </div>
</div>
@endsection
