@extends('layouts.admin')

@section('content')
<div class="space-y-4 animate-in fade-in duration-500" x-data="{
    nameEn: '',
    nameVi: '',
    generatedCode: '',
    takenCodes: @js($takenCodes ?? []),
    generateCode() {
        if (this.nameEn) {
            const words = this.nameEn.trim().split(/\s+/);
            let baseChar = words[0].charAt(0).toLowerCase();
            let code = baseChar;
            
            if (this.takenCodes && this.takenCodes.includes(code)) {
                // Thử ký tự thứ hai của từ đầu tiên nếu có
                if (words[0].length > 1 && !this.takenCodes.includes(words[0].charAt(1).toLowerCase())) {
                    code = words[0].charAt(1).toLowerCase();
                }
                // Thử chữ cái đầu của từ thứ hai nếu có
                else if (words.length > 1 && !this.takenCodes.includes(words[1].charAt(0).toLowerCase())) {
                    code = words[1].charAt(0).toLowerCase();
                }
                // Tìm ký tự trống từ a-z
                else {
                    const alphabet = 'abcdefghijklmnopqrstuvwxyz';
                    for (let char of alphabet) {
                        if (!this.takenCodes.includes(char)) {
                            code = char;
                            break;
                        }
                    }
                }
            }
            this.generatedCode = code;
        } else {
            this.generatedCode = '';
        }
    }
}" x-init="
    const oldNameEn = {{ old('name_en') ? json_encode(old('name_en')) : 'null' }};
    const oldCode = {{ old('code') ? json_encode(old('code')) : 'null' }};
    
    if (oldNameEn) {
        nameEn = oldNameEn;
        if (!oldCode) {
            generateCode();
        } else {
            generatedCode = oldCode;
        }
    }
">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <h1 class="text-xl font-bold text-foreground tracking-tight">{{ __('Thêm kiểu biểu ghi') }}</h1>
            <p class="text-xs text-muted-foreground">{{ __('Tạo một kiểu biểu ghi MARC mới') }}</p>
        </div>
        <a href="{{ route('admin.document-types.index', ['tab' => 'bibliographic-levels']) }}"
            class="btn-compact-secondary inline-flex items-center gap-1.5">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            {{ __('Quay lại') }}
        </a>
    </div>

    <!-- Form Container -->
    <div class="bg-card rounded-md shadow-sm border border-border p-4">
        <form method="POST" action="{{ route('admin.bibliographic-levels.store') }}" class="space-y-3">
            @csrf

            <!-- Name English -->
            <div class="space-y-1">
                <label class="text-[10px] font-bold text-muted-foreground uppercase tracking-wider block">
                    {{ __('Tên (Tiếng Anh)') }} <span class="text-destructive">*</span>
                </label>
                <input type="text" name="name_en" x-model="nameEn" @input="generateCode()" required
                    class="w-full h-9 px-3 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all @error('name_en') border-destructive @enderror"
                    placeholder="Language material">
                @error('name_en')
                <p class="mt-1 text-xs text-destructive">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <!-- Code -->
                <div class="space-y-1">
                    <label class="text-[10px] font-bold text-muted-foreground uppercase tracking-wider block">
                        {{ __('Code') }} <span class="text-destructive">*</span>
                    </label>
                    <input type="text" name="code" maxlength="1" x-model="generatedCode" required
                        class="w-full h-9 px-3 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all font-mono @error('code') border-destructive @enderror"
                        placeholder="Auto-generated">
                    @error('code')
                    <p class="mt-1 text-xs text-destructive">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Name Vietnamese -->
                <div class="space-y-1">
                    <label class="text-[10px] font-bold text-muted-foreground uppercase tracking-wider block">
                        {{ __('Tên (Tiếng Việt)') }} <span class="text-destructive">*</span>
                    </label>
                    <input type="text" name="name_vi" x-model="nameVi" required
                        class="w-full h-9 px-3 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all @error('name_vi') border-destructive @enderror"
                        placeholder="Tài liệu văn bản">
                    @error('name_vi')
                    <p class="mt-1 text-xs text-destructive">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Description -->
            <div class="space-y-1">
                <label class="text-[10px] font-bold text-muted-foreground uppercase tracking-wider block">
                    {{ __('Mô tả') }}
                </label>
                <textarea name="description" rows="3"
                    class="w-full px-3 py-1.5 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all"
                    placeholder="Mô tả chi tiết...">{{ old('description', '') }}</textarea>
            </div>

            <!-- Order -->
            <div class="space-y-1">
                <label class="text-[10px] font-bold text-muted-foreground uppercase tracking-wider block">
                    {{ __('Thứ tự') }}
                </label>
                <input type="number" name="order" value="{{ old('order', 0) }}" min="0"
                    class="w-full h-9 px-3 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all">
            </div>

            <!-- Is Active -->
            <div class="flex items-center space-x-2 px-1 py-1">
                <label class="flex items-center space-x-2 cursor-pointer group">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} 
                        class="rounded-sm border-input text-primary shadow-sm focus:ring-primary focus:ring-offset-background">
                    <span class="text-xs font-semibold text-muted-foreground group-hover:text-foreground transition-colors">{{ __('Kích hoạt') }}</span>
                </label>
            </div>

            <!-- Buttons -->
            <div class="flex justify-end gap-2 pt-4 border-t border-border">
                <a href="{{ route('admin.document-types.index', ['tab' => 'bibliographic-levels']) }}"
                    class="btn-compact-secondary">
                    {{ __('Hủy') }}
                </a>
                <button type="submit" class="btn-compact-primary">
                    {{ __('Lưu') }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
