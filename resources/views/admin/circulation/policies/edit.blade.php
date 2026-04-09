@extends('layouts.admin')

@php
    \Log::info('Edit view - Policy exists: ' . isset($policy));
    \Log::info('Edit view - Policy ID: ' . ($policy->id ?? 'null'));
    \Log::info('Edit view - Policy name: ' . ($policy->name ?? 'null'));
    \Log::info('Edit view - Patron groups count: ' . isset($patronGroups) ? $patronGroups->count() : 'null');
@endphp

@section('content')
<div class="space-y-8">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold">{{ __('Chỉnh sửa chính sách lưu thông') }}</h1>
            <p class="text-sm text-gray-400 mt-1">{{ $policy->name }} - {{ __('Cập nhật cài đặt chính sách') }}</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.circulation.policies.show', $policy) }}" class="btn-secondary">
                <i class="fas fa-eye mr-2"></i>{{ __('Xem') }}
            </a>
            <a href="{{ route('admin.circulation.policies.index') }}" class="btn-secondary">
                <i class="fas fa-arrow-left mr-2"></i>{{ __('Quay lại') }}
            </a>
        </div>
    </div>

    <!-- Form -->
    <form action="{{ route('admin.circulation.policies.update', $policy) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')
        
        <!-- Basic Information -->
        <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-gray-100 dark:border-slate-800 p-6">
            <h2 class="text-lg font-semibold mb-4">{{ __('Thông tin cơ bản') }}</h2>
            
            @if($errors->any())
            <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                <h3 class="text-red-800 font-medium mb-2">{{ __('Có lỗi xảy ra:') }}</h3>
                <ul class="text-red-700 text-sm space-y-1">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-2">{{ __('Tên chính sách') }} <span class="text-red-500">*</span></label>
                    <input type="text" name="name" required value="{{ $policy->name }}"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-800">
                </div>
                
                <div>
                    <label class="block text-sm font-medium mb-2">{{ __('Nhóm bạn đọc') }} <span class="text-red-500">*</span></label>
                    <select name="patron_group_id" required 
                            class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-800">
                        <option value="">{{ __('-- Chọn nhóm bạn đọc --') }}</option>
                        @foreach($patronGroups as $group)
                        <option value="{{ $group->id }}" {{ $policy->patron_group_id == $group->id ? 'selected' : '' }}>
                            {{ $group->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>
            
            <div class="mt-4">
                <label class="block text-sm font-medium mb-2">{{ __('Ghi chú') }}</label>
                <textarea name="notes" rows="2" 
                          class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-800">{{ $policy->notes }}</textarea>
            </div>
        </div>

        <!-- Loan Settings -->
        <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-gray-100 dark:border-slate-800 p-6">
            <h2 class="text-lg font-semibold mb-4">{{ __('Cài đặt mượn sách') }}</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-2">{{ __('Số ngày mượn tối đa') }} <span class="text-red-500">*</span></label>
                    <input type="number" name="max_loan_days" required min="1" max="365" value="{{ $policy->max_loan_days }}"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-800">
                </div>
                
                <div>
                    <label class="block text-sm font-medium mb-2">{{ __('Số sách mượn tối đa') }} <span class="text-red-500">*</span></label>
                    <input type="number" name="max_items" required min="1" max="50" value="{{ $policy->max_items }}"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-800">
                </div>
                
                <div>
                    <label class="block text-sm font-medium mb-2">{{ __('Số lần gia hạn tối đa') }} <span class="text-red-500">*</span></label>
                    <input type="number" name="max_renewals" required min="0" max="10" value="{{ $policy->max_renewals }}"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-800">
                </div>
                
                <div>
                    <label class="block text-sm font-medium mb-2">{{ __('Ngày gia hạn mỗi lần') }} <span class="text-red-500">*</span></label>
                    <input type="number" name="renewal_days" required min="1" max="90" value="{{ $policy->renewal_days }}"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-800">
                </div>
            </div>
        </div>

        <!-- Fine Settings -->
        <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-gray-100 dark:border-slate-800 p-6">
            <h2 class="text-lg font-semibold mb-4">{{ __('Cài đặt phạt') }}</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-2">{{ __('Phí phạt/ngày (VND)') }} <span class="text-red-500">*</span></label>
                    <input type="number" name="fine_per_day" required min="0" max="100000" step="100" value="{{ $policy->fine_per_day }}"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-800">
                </div>
                
                <div>
                    <label class="block text-sm font-medium mb-2">{{ __('Phạt tối đa (VND)') }} <span class="text-red-500">*</span></label>
                    <input type="number" name="max_fine" required min="0" max="1000000" step="100" value="{{ $policy->max_fine }}"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-800">
                </div>
                
                <div>
                    <label class="block text-sm font-medium mb-2">{{ __('Số ngày ân hạn') }} <span class="text-red-500">*</span></label>
                    <input type="number" name="grace_period_days" required min="0" max="30" value="{{ $policy->grace_period_days }}"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-800">
                </div>
                
                <div>
                    <label class="block text-sm font-medium mb-2">{{ __('Nợ tối đa được phép (VND)') }} <span class="text-red-500">*</span></label>
                    <input type="number" name="max_outstanding_fine" required min="0" max="1000000" step="100" value="{{ $policy->max_outstanding_fine }}"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-800">
                </div>
            </div>
        </div>

        <!-- Reservation Settings -->
        <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-gray-100 dark:border-slate-800 p-6">
            <h2 class="text-lg font-semibold mb-4">{{ __('Cài đặt đặt giữ lại') }}</h2>
            
            <div class="space-y-4">
                <div class="flex items-center">
                    <input type="checkbox" name="can_reserve" {{ $policy->can_reserve ? 'checked' : '' }}
                           class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                    <label class="ml-2 text-sm font-medium">{{ __('Cho phép đặt giữ lại sách') }}</label>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-2">{{ __('Số lượng đặt giữ lại tối đa') }} <span class="text-red-500">*</span></label>
                        <input type="number" name="max_reservations" required min="0" max="20" value="{{ $policy->max_reservations }}"
                               class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-800">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium mb-2">{{ __('Giữ lại trong (ngày)') }} <span class="text-red-500">*</span></label>
                        <input type="number" name="reservation_hold_days" required min="1" max="30" value="{{ $policy->reservation_hold_days }}"
                               class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-800">
                    </div>
                </div>
            </div>
        </div>

        <!-- Reading Room Policies -->
        <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-gray-100 dark:border-slate-800 p-6">
            <h2 class="text-lg font-semibold mb-4">{{ __('Cài đặt mượn đọc tại chỗ') }}</h2>
            
            <div class="mb-4">
                <label class="flex items-center">
                    <input type="checkbox" name="can_use_reading_room" {{ $policy->can_use_reading_room ? 'checked' : '' }}
                           class="mr-2 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    <span class="text-sm font-medium">{{ __('Cho phép sử dụng mượn đọc tại chỗ') }}</span>
                </label>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-2">{{ __('Số tài liệu tối đa') }} <span class="text-red-500">*</span></label>
                    <input type="number" name="max_reading_room_items" required min="0" max="20" value="{{ $policy->max_reading_room_items }}"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-800">
                </div>
                
                <div>
                    <label class="block text-sm font-medium mb-2">{{ __('Thời gian mượn (giờ)') }} <span class="text-red-500">*</span></label>
                    <input type="number" name="reading_room_hours" required min="1" max="24" value="{{ $policy->reading_room_hours }}"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-800">
                </div>
                
                <div>
                    <label class="block text-sm font-medium mb-2">{{ __('Giờ trả mặc định') }} <span class="text-red-500">*</span></label>
                    <input type="time" name="reading_room_due_time" value="{{ $policy->reading_room_due_time && $policy->reading_room_due_time !== '00:00:00' ? date('H:i', strtotime($policy->reading_room_due_time)) : '' }}"
                           class="w-full px-3 py-2 border {{ $errors->has('reading_room_due_time') ? 'border-red-500' : 'border-gray-300' }} dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-800">
                    @if($errors->has('reading_room_due_time'))
                    <p class="mt-1 text-sm text-red-600">{{ $errors->first('reading_room_due_time') }}</p>
                    @endif
                </div>
                
                <div>
                    <label class="block text-sm font-medium mb-2">{{ __('Phí phạt/giờ (VND)') }} <span class="text-red-500">*</span></label>
                    <input type="number" name="reading_room_fine_per_hour" required min="0" max="100000" step="100" value="{{ $policy->reading_room_fine_per_hour }}"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-800">
                </div>
            </div>
            
            <div class="mt-4">
                <label class="block text-sm font-medium mb-2">{{ __('Phạt mượn đọc tối đa (VND)') }} <span class="text-red-500">*</span></label>
                <input type="number" name="reading_room_max_fine" required min="0" max="1000000" step="100" value="{{ $policy->reading_room_max_fine }}"
                       class="w-full md:w-1/2 lg:w-1/4 px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-800">
            </div>
        </div>

        <!-- Hold/Reserve Settings -->
        <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-gray-100 dark:border-slate-800 p-6">
            <h2 class="text-lg font-semibold mb-4">{{ __('Cài đặt giữ lại sách') }}</h2>
            
            <div class="mb-4">
                <label class="flex items-center">
                    <input type="checkbox" name="can_place_hold" {{ $policy->can_place_hold ? 'checked' : '' }}
                           class="mr-2 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    <span class="text-sm font-medium">{{ __('Cho phép đặt giữ lại sách') }}</span>
                </label>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-2">{{ __('Số giữ lại tối đa') }} <span class="text-red-500">*</span></label>
                    <input type="number" name="max_holds" required min="0" max="20" value="{{ $policy->max_holds }}"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-800">
                </div>
                
                <div>
                    <label class="block text-sm font-medium mb-2">{{ __('Ngày hết hạn') }} <span class="text-red-500">*</span></label>
                    <input type="number" name="hold_expiry_days" required min="1" max="90" value="{{ $policy->hold_expiry_days }}"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-800">
                </div>
                
                <div>
                    <label class="block text-sm font-medium mb-2">{{ __('Ngày thông báo') }} <span class="text-red-500">*</span></label>
                    <input type="number" name="hold_notification_days" required min="0" max="30" value="{{ $policy->hold_notification_days }}"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-800">
                </div>
                
                <div>
                    <label class="block text-sm font-medium mb-2">{{ __('Phí hủy giữ lại (VND)') }} <span class="text-red-500">*</span></label>
                    <input type="number" name="hold_cancellation_fee" required min="0" max="100000" step="100" value="{{ $policy->hold_cancellation_fee }}"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-800">
                </div>
            </div>
            
            <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="flex items-center mb-2">
                        <input type="checkbox" name="allow_hold_renewal" {{ $policy->allow_hold_renewal ? 'checked' : '' }}
                               class="mr-2 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <span class="text-sm font-medium">{{ __('Cho phép gia hạn giữ lại') }}</span>
                    </label>
                </div>
                
                <div>
                    <label class="block text-sm font-medium mb-2">{{ __('Số lần gia hạn tối đa') }} <span class="text-red-500">*</span></label>
                    <input type="number" name="max_hold_renewals" required min="0" max="10" value="{{ $policy->max_hold_renewals }}"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-800">
                </div>
            </div>
        </div>

        <!-- Status -->
        <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-gray-100 dark:border-slate-800 p-6">
            <label class="flex items-center">
                <input type="checkbox" name="is_active" {{ $policy->is_active ? 'checked' : '' }}
                       class="mr-2 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                <span class="text-sm font-medium">{{ __('Kích hoạt chính sách') }}</span>
            </label>
        </div>

        <!-- Actions -->
        <div class="flex justify-end gap-2">
            <a href="{{ route('admin.circulation.policies.index') }}" class="btn-secondary">
                {{ __('Hủy') }}
            </a>
            <button type="submit" class="btn-primary">
                <i class="fas fa-save mr-2"></i>{{ __('Cập nhật chính sách') }}
            </button>
        </div>
    </form>
</div>
@endsection
