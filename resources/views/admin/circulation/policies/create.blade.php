@extends('layouts.admin')

@section('content')
<div class="space-y-8">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold">{{ __('Thêm chính sách lưu thông') }}</h1>
            <p class="text-sm text-gray-400 mt-1">{{ __('Tạo chính sách mới cho nhóm bạn đọc') }}</p>
        </div>
        <a href="{{ route('admin.circulation.policies.index') }}" class="btn-secondary">
            <i class="fas fa-arrow-left mr-2"></i>{{ __('Quay lại') }}
        </a>
    </div>

    <!-- Form -->
    <form action="{{ route('admin.circulation.policies.store') }}" method="POST" class="space-y-6">
        @csrf
        @method('POST')
        
        <!-- Basic Information -->
        <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-gray-100 dark:border-slate-800 p-6">
            <h2 class="text-lg font-semibold mb-4">{{ __('Thông tin cơ bản') }}</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-2">{{ __('Tên chính sách') }} <span class="text-red-500">*</span></label>
                    <input type="text" name="name" required 
                           class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-800"
                           placeholder="{{ __('Ví dụ: Chính sách Sinh viên') }}">
                </div>
                
                <div>
                    <label class="block text-sm font-medium mb-2">{{ __('Nhóm bạn đọc') }} <span class="text-red-500">*</span></label>
                    <select name="patron_group_id" required 
                            class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-800">
                        <option value="">{{ __('-- Chọn nhóm bạn đọc --') }}</option>
                        @foreach($patronGroups as $group)
                        <option value="{{ $group->id }}">{{ $group->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            
            <div class="mt-4">
                <label class="block text-sm font-medium mb-2">{{ __('Ghi chú') }}</label>
                <textarea name="notes" rows="2" 
                          class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-800"
                          placeholder="{{ __('Ghi chú về chính sách...') }}"></textarea>
            </div>
        </div>

        <!-- Loan Settings -->
        <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-gray-100 dark:border-slate-800 p-6">
            <h2 class="text-lg font-semibold mb-4">{{ __('Cài đặt mượn sách') }}</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-2">{{ __('Số ngày mượn tối đa') }} <span class="text-red-500">*</span></label>
                    <input type="number" name="max_loan_days" required min="1" max="365" value="14"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-800">
                </div>
                
                <div>
                    <label class="block text-sm font-medium mb-2">{{ __('Số sách mượn tối đa') }} <span class="text-red-500">*</span></label>
                    <input type="number" name="max_items" required min="1" max="50" value="5"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-800">
                </div>
                
                <div>
                    <label class="block text-sm font-medium mb-2">{{ __('Số lần gia hạn tối đa') }} <span class="text-red-500">*</span></label>
                    <input type="number" name="max_renewals" required min="0" max="10" value="2"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-800">
                </div>
                
                <div>
                    <label class="block text-sm font-medium mb-2">{{ __('Ngày gia hạn mỗi lần') }} <span class="text-red-500">*</span></label>
                    <input type="number" name="renewal_days" required min="1" max="90" value="7"
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
                    <input type="number" name="fine_per_day" required min="0" max="100000" step="100" value="1000"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-800">
                </div>
                
                <div>
                    <label class="block text-sm font-medium mb-2">{{ __('Phạt tối đa (VND)') }} <span class="text-red-500">*</span></label>
                    <input type="number" name="max_fine" required min="0" max="1000000" step="100" value="100000"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-800">
                </div>
                
                <div>
                    <label class="block text-sm font-medium mb-2">{{ __('Số ngày ân hạn') }} <span class="text-red-500">*</span></label>
                    <input type="number" name="grace_period_days" required min="0" max="30" value="0"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-800">
                </div>
                
                <div>
                    <label class="block text-sm font-medium mb-2">{{ __('Nợ tối đa được phép (VND)') }} <span class="text-red-500">*</span></label>
                    <input type="number" name="max_outstanding_fine" required min="0" max="1000000" step="100" value="50000"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-800">
                </div>
            </div>
        </div>

        <!-- Reading Room Settings -->
        <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-gray-100 dark:border-slate-800 p-6">
            <h2 class="text-lg font-semibold mb-4">{{ __('Cài đặt mượn đọc tại chỗ') }}</h2>
            
            <div class="mb-4">
                <label class="flex items-center">
                    <input type="checkbox" name="can_use_reading_room" checked
                           class="mr-2 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    <span class="text-sm font-medium">{{ __('Cho phép sử dụng mượn đọc tại chỗ') }}</span>
                </label>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-2">{{ __('Số tài liệu tối đa') }} <span class="text-red-500">*</span></label>
                    <input type="number" name="max_reading_room_items" required min="0" max="20" value="5"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-800">
                </div>
                
                <div>
                    <label class="block text-sm font-medium mb-2">{{ __('Thời gian mượn (giờ)') }} <span class="text-red-500">*</span></label>
                    <input type="number" name="reading_room_hours" required min="1" max="24" value="4"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-800">
                </div>
                
                <div>
                    <label class="block text-sm font-medium mb-2">{{ __('Giờ trả mặc định') }} <span class="text-red-500">*</span></label>
                    <input type="time" name="reading_room_due_time" required value="17:00"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-800">
                </div>
                
                <div>
                    <label class="block text-sm font-medium mb-2">{{ __('Phí phạt/giờ (VND)') }} <span class="text-red-500">*</span></label>
                    <input type="number" name="reading_room_fine_per_hour" required min="0" max="100000" step="100" value="5000"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-800">
                </div>
            </div>
            
            <div class="mt-4">
                <label class="block text-sm font-medium mb-2">{{ __('Phạt mượn đọc tối đa (VND)') }} <span class="text-red-500">*</span></label>
                <input type="number" name="reading_room_max_fine" required min="0" max="1000000" step="100" value="50000"
                       class="w-full md:w-1/2 lg:w-1/4 px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-800">
            </div>
        </div>

        <!-- Hold/Reserve Settings -->
        <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-gray-100 dark:border-slate-800 p-6">
            <h2 class="text-lg font-semibold mb-4">{{ __('Cài đặt giữ lại sách') }}</h2>
            
            <div class="mb-4">
                <label class="flex items-center">
                    <input type="checkbox" name="can_place_hold" checked
                           class="mr-2 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    <span class="text-sm font-medium">{{ __('Cho phép đặt giữ lại sách') }}</span>
                </label>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-2">{{ __('Số giữ lại tối đa') }} <span class="text-red-500">*</span></label>
                    <input type="number" name="max_holds" required min="0" max="20" value="3"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-800">
                </div>
                
                <div>
                    <label class="block text-sm font-medium mb-2">{{ __('Ngày hết hạn') }} <span class="text-red-500">*</span></label>
                    <input type="number" name="hold_expiry_days" required min="1" max="90" value="7"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-800">
                </div>
                
                <div>
                    <label class="block text-sm font-medium mb-2">{{ __('Ngày thông báo') }} <span class="text-red-500">*</span></label>
                    <input type="number" name="hold_notification_days" required min="0" max="30" value="2"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-800">
                </div>
                
                <div>
                    <label class="block text-sm font-medium mb-2">{{ __('Phí hủy giữ lại (VND)') }} <span class="text-red-500">*</span></label>
                    <input type="number" name="hold_cancellation_fee" required min="0" max="100000" step="100" value="0"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-800">
                </div>
            </div>
            
            <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="flex items-center mb-2">
                        <input type="checkbox" name="allow_hold_renewal"
                               class="mr-2 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <span class="text-sm font-medium">{{ __('Cho phép gia hạn giữ lại') }}</span>
                    </label>
                </div>
                
                <div>
                    <label class="block text-sm font-medium mb-2">{{ __('Số lần gia hạn tối đa') }} <span class="text-red-500">*</span></label>
                    <input type="number" name="max_hold_renewals" required min="0" max="10" value="0"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-800">
                </div>
            </div>
        </div>

        <!-- Status -->
        <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-gray-100 dark:border-slate-800 p-6">
            <label class="flex items-center">
                <input type="checkbox" name="is_active" checked
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
                <i class="fas fa-save mr-2"></i>{{ __('Lưu chính sách') }}
            </button>
        </div>
    </form>
</div>
@endsection
