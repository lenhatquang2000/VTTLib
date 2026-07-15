@extends('layouts.admin')

@section('title', __('Patron Reports & Export'))

@section('content')
<div class="w-full space-y-3 pb-4">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
        <div>
            <h1 class="text-lg font-bold text-foreground tracking-tight">{{ __('Báo cáo & Xuất dữ liệu Độc giả') }}</h1>
            <p class="text-xs text-muted-foreground mt-0.5">{{ __('Chọn loại báo cáo từ cây phân hệ bên trái và cấu hình bộ lọc tương ứng bên phải.') }}</p>
        </div>
        <div class="flex items-center gap-2 w-full sm:w-auto">
            <a href="{{ route('admin.patrons.index') }}" class="btn-compact-secondary">
                <i data-lucide="arrow-left" class="w-4 h-4 mr-1"></i>
                <span>{{ __('Quay lại') }}</span>
            </a>
        </div>
    </div>

    <!-- Main Two-Column Layout -->
    <div class="flex flex-col lg:flex-row gap-3 w-full">
        
        <!-- Left Column: Report Tree Menu -->
        <div id="left-tree-column" class="w-full lg:w-80 xl:w-96 bg-card text-foreground rounded-md border border-border shadow-sm overflow-hidden flex flex-col shrink-0">
            <!-- Header bar with Collapse Button -->
            <div class="p-3 border-b border-border bg-muted/30 flex items-center justify-between font-bold shadow-sm">
                <div class="flex items-center gap-2">
                    <i data-lucide="users" class="w-4 h-4 text-muted-foreground"></i>
                    <span class="text-xs font-bold uppercase tracking-wider text-foreground">{{ __('BÁO CÁO PHÂN HỆ QUẢN LÝ ĐỘC GIẢ') }}</span>
                </div>
                <button type="button" id="toggle-tree-btn" class="btn-icon-compact" title="{{ __('Thu gọn danh mục') }}">
                    <i data-lucide="chevron-left" class="w-4 h-4 text-muted-foreground"></i>
                </button>
            </div>
            
            <!-- Tree view area -->
            <div class="p-3 overflow-y-auto max-h-[600px] custom-scrollbar bg-card">
                <div class="tree-container">
                    
                    <!-- Group 1: Báo cáo phân hệ quản lý độc giả -->
                    @php
                    $isGroup1Active = in_array($reportType, ['patron_list', 'print_cards', 'renew_list', 'renew_by_period']);
                    @endphp
                    <div class="tree-group">
                        <div class="tree-folder flex items-center py-1.5 px-1 hover:bg-muted/50 rounded cursor-pointer select-none" data-group="group-1">
                            <span class="toggle-icon-container mr-1 text-muted-foreground flex items-center justify-center">
                                <i data-lucide="square-minus" class="w-4 h-4 toggle-open {{ $isGroup1Active ? '' : 'hidden' }}"></i>
                                <i data-lucide="square-plus" class="w-4 h-4 toggle-closed {{ $isGroup1Active ? 'hidden' : '' }}"></i>
                            </span>
                            <span class="folder-icon-container mr-1.5 text-muted-foreground flex items-center justify-center">
                                <i data-lucide="folder-open" class="w-4 h-4 folder-open-icon {{ $isGroup1Active ? '' : 'hidden' }}"></i>
                                <i data-lucide="folder" class="w-4 h-4 folder-closed-icon {{ $isGroup1Active ? 'hidden' : '' }}"></i>
                            </span>
                            <span class="font-bold text-foreground text-xs">{{ __('Báo cáo phân hệ quản lý độc giả') }}</span>
                        </div>
                        
                        <!-- Group 1 children (with tree lines) -->
                        <div class="tree-node-parent {{ $isGroup1Active ? '' : 'hidden' }}" id="group-1">
                            <a href="{{ route('admin.patrons.reports.index', ['report_type' => 'patron_list']) }}" 
                               class="tree-node-child tree-leaf flex items-center py-1 px-2 my-0.5 rounded border border-transparent hover:bg-muted {{ $reportType === 'patron_list' ? 'active-leaf bg-primary text-primary-foreground font-semibold' : 'text-muted-foreground' }}">
                                <span class="relative flex items-center mr-1.5">
                                    <span class="w-1.5 h-1.5 bg-primary {{ $reportType === 'patron_list' ? 'bg-primary-foreground' : 'invisible' }} rounded-full mr-2"></span>
                                    <i data-lucide="file-text" class="w-4 h-4 leaf-icon {{ $reportType === 'patron_list' ? 'text-primary-foreground' : 'text-muted-foreground' }}"></i>
                                </span>
                                <span class="text-xs">{{ __('Danh sách độc giả trong thư viện') }}</span>
                            </a>
                            
                            <a href="{{ route('admin.patrons.reports.index', ['report_type' => 'print_cards']) }}" 
                               class="tree-node-child tree-leaf flex items-center py-1 px-2 my-0.5 rounded border border-transparent hover:bg-muted {{ $reportType === 'print_cards' ? 'active-leaf bg-primary text-primary-foreground font-semibold' : 'text-muted-foreground' }}">
                                <span class="relative flex items-center mr-1.5">
                                    <span class="w-1.5 h-1.5 bg-primary {{ $reportType === 'print_cards' ? 'bg-primary-foreground' : 'invisible' }} rounded-full mr-2"></span>
                                    <i data-lucide="file-text" class="w-4 h-4 leaf-icon {{ $reportType === 'print_cards' ? 'text-primary-foreground' : 'text-muted-foreground' }}"></i>
                                </span>
                                <span class="text-xs">{{ __('In thẻ độc giả') }}</span>
                            </a>
                            
                            <a href="{{ route('admin.patrons.reports.index', ['report_type' => 'renew_list']) }}" 
                               class="tree-node-child tree-leaf flex items-center py-1 px-2 my-0.5 rounded border border-transparent hover:bg-muted {{ $reportType === 'renew_list' ? 'active-leaf bg-primary text-primary-foreground font-semibold' : 'text-muted-foreground' }}">
                                <span class="relative flex items-center mr-1.5">
                                    <span class="w-1.5 h-1.5 bg-primary {{ $reportType === 'renew_list' ? 'bg-primary-foreground' : 'invisible' }} rounded-full mr-2"></span>
                                    <i data-lucide="file-text" class="w-4 h-4 leaf-icon {{ $reportType === 'renew_list' ? 'text-primary-foreground' : 'text-muted-foreground' }}"></i>
                                </span>
                                <span class="text-xs">{{ __('Danh sách độc giả gia hạn thẻ') }}</span>
                            </a>
                            
                            <a href="{{ route('admin.patrons.reports.index', ['report_type' => 'renew_by_period']) }}" 
                               class="tree-node-child tree-leaf flex items-center py-1 px-2 my-0.5 rounded border border-transparent hover:bg-muted {{ $reportType === 'renew_by_period' ? 'active-leaf bg-primary text-primary-foreground font-semibold' : 'text-muted-foreground' }}">
                                <span class="relative flex items-center mr-1.5">
                                    <span class="w-1.5 h-1.5 bg-primary {{ $reportType === 'renew_by_period' ? 'bg-primary-foreground' : 'invisible' }} rounded-full mr-2"></span>
                                    <i data-lucide="file-text" class="w-4 h-4 leaf-icon {{ $reportType === 'renew_by_period' ? 'text-primary-foreground' : 'text-muted-foreground' }}"></i>
                                </span>
                                <span class="text-xs text-left leading-tight">{{ __('Danh sách độc giả gia hạn thẻ theo một khoảng thời gian') }}</span>
                            </a>
                        </div>
                    </div>
                    
                    <!-- Group 2: In ấn -->
                    @php
                    $isGroup2Active = in_array($reportType, ['viewer_patron_list', 'viewer_print_cards']);
                    @endphp
                    <div class="tree-group mt-2">
                        <div class="tree-folder flex items-center py-1.5 px-1 hover:bg-muted/50 rounded cursor-pointer select-none" data-group="group-2">
                            <span class="toggle-icon-container mr-1 text-muted-foreground flex items-center justify-center">
                                <i data-lucide="square-minus" class="w-4 h-4 toggle-open {{ $isGroup2Active ? '' : 'hidden' }}"></i>
                                <i data-lucide="square-plus" class="w-4 h-4 toggle-closed {{ $isGroup2Active ? 'hidden' : '' }}"></i>
                            </span>
                            <span class="folder-icon-container mr-1.5 text-muted-foreground flex items-center justify-center">
                                <i data-lucide="folder-open" class="w-4 h-4 folder-open-icon {{ $isGroup2Active ? '' : 'hidden' }}"></i>
                                <i data-lucide="folder" class="w-4 h-4 folder-closed-icon {{ $isGroup2Active ? 'hidden' : '' }}"></i>
                            </span>
                            <span class="font-bold text-foreground text-xs">{{ __('In ấn') }}</span>
                        </div>
                        
                        <!-- Group 2 children (with tree lines) -->
                        <div class="tree-node-parent {{ $isGroup2Active ? '' : 'hidden' }}" id="group-2">
                            <a href="{{ route('admin.patrons.reports.index', ['report_type' => 'viewer_patron_list']) }}" 
                               class="tree-node-child tree-leaf flex items-center py-1 px-2 my-0.5 rounded border border-transparent hover:bg-muted {{ $reportType === 'viewer_patron_list' ? 'active-leaf bg-primary text-primary-foreground font-semibold' : 'text-muted-foreground' }}">
                                <span class="relative flex items-center mr-1.5">
                                    <span class="w-1.5 h-1.5 bg-primary {{ $reportType === 'viewer_patron_list' ? 'bg-primary-foreground' : 'invisible' }} rounded-full mr-2"></span>
                                    <i data-lucide="file-text" class="w-4 h-4 leaf-icon {{ $reportType === 'viewer_patron_list' ? 'text-primary-foreground' : 'text-muted-foreground' }}"></i>
                                </span>
                                <span class="text-xs">{{ __('[ReportViewer] Danh sách độc giả') }}</span>
                            </a>
                            
                            <a href="{{ route('admin.patrons.reports.index', ['report_type' => 'viewer_print_cards']) }}" 
                               class="tree-node-child tree-leaf flex items-center py-1 px-2 my-0.5 rounded border border-transparent hover:bg-muted {{ $reportType === 'viewer_print_cards' ? 'active-leaf bg-primary text-primary-foreground font-semibold' : 'text-muted-foreground' }}">
                                <span class="relative flex items-center mr-1.5">
                                    <span class="w-1.5 h-1.5 bg-primary {{ $reportType === 'viewer_print_cards' ? 'bg-primary-foreground' : 'invisible' }} rounded-full mr-2"></span>
                                    <i data-lucide="file-text" class="w-4 h-4 leaf-icon {{ $reportType === 'viewer_print_cards' ? 'text-primary-foreground' : 'text-muted-foreground' }}"></i>
                                </span>
                                <span class="text-xs">{{ __('[ReportViewer] In thẻ độc giả') }}</span>
                            </a>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
        
        <!-- Right Column: Filters and Actions Panel -->
        <div class="flex-1 min-w-0 bg-card text-foreground border border-border rounded-md shadow-sm flex flex-col min-h-[450px] relative">
            <!-- Panel Header with Expand Button -->
            <div class="p-3 border-b border-border bg-muted/30 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2 w-full">
                <div class="flex items-start gap-2 flex-1 min-w-0">
                    <!-- Expand Button, hidden by default -->
                    <button type="button" id="expand-tree-btn" class="btn-icon-compact hidden shrink-0" title="{{ __('Mở rộng danh mục') }}">
                        <i data-lucide="chevron-right" class="w-4 h-4 text-muted-foreground"></i>
                    </button>
                    <div class="flex-1 min-w-0">
                        <span class="inline-flex items-center px-1.5 py-0.5 rounded-sm text-[10px] font-bold bg-primary/10 text-primary uppercase tracking-widest mb-1" id="current_report_label">
                            {{ $activeReport['title'] }}
                        </span>
                        <h2 class="text-sm font-bold text-foreground tracking-tight" id="right_panel_title">
                            {{ $activeReport['title'] }}
                        </h2>
                        <p class="text-xs text-muted-foreground mt-0.5 leading-relaxed" id="right_panel_desc">
                            {{ $activeReport['desc'] }}
                        </p>
                    </div>
                </div>
            </div>
            
            <!-- Filter Form -->
            <form action="{{ route('admin.patrons.reports.generate') }}" method="POST" target="_blank" id="reportForm" class="p-3 flex-1 flex flex-col justify-between space-y-3">
                @csrf
                <input type="hidden" name="report_type" id="selected_report_type" value="{{ $reportType }}">
                
                <!-- Filter Search & Collapse Header -->
                <div class="space-y-2 relative">
                    <!-- Simple Search Bar -->
                    <div class="flex items-center gap-2">
                        <div class="relative flex-1">
                            <input type="text" name="search" id="search_input" placeholder="{{ __('Tìm nhanh độc giả bằng từ khóa (Mã số, Họ tên, Email, Số điện thoại)...') }}" class="w-full h-9 pl-3 pr-10 text-xs border border-input rounded-sm bg-background text-foreground placeholder:text-muted-foreground/60 focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all">
                            <button type="submit" class="absolute right-1 top-1 h-7 w-7 flex items-center justify-center rounded-sm bg-primary text-primary-foreground hover:bg-primary/90 transition-colors">
                                <i data-lucide="search" class="w-4 h-4"></i>
                            </button>
                        </div>
                        <button type="button" id="toggle-advanced-btn" class="btn-compact-secondary">
                            <i data-lucide="sliders" class="w-4 h-4 mr-1"></i>
                            <span>{{ __('Tìm nâng cao') }}</span>
                            <i data-lucide="chevron-down" class="w-4 h-4 ml-1 transition-transform duration-200 transform" id="advanced-chevron"></i>
                        </button>
                    </div>

                    <!-- Collapsible Advanced Search Panel (Floating Overlay) -->
                    <div id="advanced-search-panel" class="hidden absolute left-0 right-0 top-full mt-1 z-30 border border-border rounded-md bg-card p-3 space-y-3 shadow-lg transition-all duration-300">
                        <!-- Tab Selector Buttons -->
                        <div class="flex flex-wrap gap-1 border-b border-border pb-1">
                            <button type="button" class="tab-btn px-3 py-1 text-[10px] font-bold rounded-sm border border-transparent bg-muted/60 text-muted-foreground hover:bg-muted active-tab" data-tab="tab-info">Thông tin</button>
                            <button type="button" class="tab-btn px-3 py-1 text-[10px] font-bold rounded-sm border border-transparent bg-muted/60 text-muted-foreground hover:bg-muted" data-tab="tab-dist">Trạng thái & Nhóm</button>
                            <button type="button" class="tab-btn px-3 py-1 text-[10px] font-bold rounded-sm border border-transparent bg-muted/60 text-muted-foreground hover:bg-muted" data-tab="tab-limit">Giới hạn</button>
                            <button type="button" class="tab-btn px-3 py-1 text-[10px] font-bold rounded-sm border border-transparent bg-muted/60 text-muted-foreground hover:bg-muted" data-tab="tab-location">Chi nhánh / Đơn vị</button>
                        </div>

                        <!-- Tab Content Sections -->
                        <div class="tab-contents min-h-[240px]">
                            
                            <!-- Tab 1: Thông tin (Logic Builder) -->
                            <div id="tab-info" class="tab-content space-y-1.5">
                                @for($i = 0; $i < 4; $i++)
                                <div class="flex items-center gap-2">
                                    @if($i == 0)
                                    <span class="text-[10px] font-bold text-muted-foreground uppercase tracking-widest w-16 text-center">Bắt đầu</span>
                                    <input type="hidden" name="info_ops[]" value="AND">
                                    @else
                                    <select name="info_ops[]" class="h-8 px-2 text-xs border border-input rounded-sm bg-background text-foreground w-16">
                                        <option value="AND">AND</option>
                                        <option value="OR">OR</option>
                                        <option value="NOT">NOT</option>
                                    </select>
                                    @endif
                                    <select name="info_fields[]" class="h-8 px-2 text-xs border border-input rounded-sm bg-background text-foreground w-28 md:w-36 shrink-0">
                                        <option value="patron_code" {{ $i==0 ? 'selected' : '' }}>{{ __('Mã bạn đọc / Số thẻ') }}</option>
                                        <option value="name" {{ $i==1 ? 'selected' : '' }}>{{ __('Họ tên') }}</option>
                                        <option value="email">{{ __('Email') }}</option>
                                        <option value="phone">{{ __('Số điện thoại') }}</option>
                                        <option value="mssv">{{ __('Mã số sinh viên (MSSV)') }}</option>
                                        <option value="id_card">{{ __('Số CMND/CCCD') }}</option>
                                        <option value="department">{{ __('Lớp / Đơn vị') }}</option>
                                        <option value="any">{{ __('Bất kỳ') }}</option>
                                    </select>
                                    <input type="text" name="info_vals[]" placeholder="{{ __('Nhập từ khóa tìm kiếm...') }}" class="flex-1 h-8 px-2 text-xs border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all">
                                </div>
                                @endfor
                            </div>

                            <!-- Tab 2: Trạng thái & Nhóm -->
                            <div id="tab-dist" class="tab-content hidden grid grid-cols-1 md:grid-cols-2 gap-3">
                                <!-- Trạng thái hoạt động -->
                                <div class="space-y-1">
                                    <span class="text-[9px] font-bold text-muted-foreground uppercase tracking-widest block border-b border-border pb-1">Trạng thái tài khoản</span>
                                    <div class="space-y-1">
                                        <label class="flex items-center text-xs text-foreground cursor-pointer select-none">
                                            <input type="checkbox" name="statuses[]" value="normal" class="w-3.5 h-3.5 text-primary bg-background border-border rounded-sm focus:ring-primary">
                                            <span class="ml-1.5 text-[11px]">Đang hoạt động (Normal)</span>
                                        </label>
                                        <label class="flex items-center text-xs text-foreground cursor-pointer select-none">
                                            <input type="checkbox" name="statuses[]" value="locked" class="w-3.5 h-3.5 text-primary bg-background border-border rounded-sm focus:ring-primary">
                                            <span class="ml-1.5 text-[11px]">Bị khóa (Locked)</span>
                                        </label>
                                    </div>
                                </div>

                                <!-- Nhóm độc giả (Scrolling list) -->
                                <div class="space-y-1">
                                    <span class="text-[9px] font-bold text-muted-foreground uppercase tracking-widest block border-b border-border pb-1">Nhóm bạn đọc</span>
                                    <div class="max-h-56 overflow-y-auto custom-scrollbar space-y-1 pr-1">
                                        @foreach($patronGroups as $g)
                                        <label class="flex items-center text-xs text-foreground cursor-pointer select-none">
                                            <input type="checkbox" name="patron_group_ids[]" value="{{ $g->id }}" class="w-3.5 h-3.5 text-primary bg-background border-border rounded-sm focus:ring-primary">
                                            <span class="ml-1.5 text-[11px]">{{ $g->name }}</span>
                                        </label>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <!-- Tab 3: Giới hạn & Ngày tháng -->
                            <div id="tab-limit" class="tab-content hidden grid grid-cols-1 md:grid-cols-2 gap-3">
                                <!-- Khoảng ngày đăng ký & Ngày hết hạn -->
                                <div class="space-y-2">
                                    <div class="space-y-1">
                                        <label class="text-[10px] font-bold text-muted-foreground uppercase tracking-widest block">Khoảng ngày đăng ký</label>
                                        <div class="flex items-center gap-1">
                                            <input type="date" name="date_from" id="date_from" class="flex-1 h-8 px-2 text-xs border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all">
                                            <span class="text-[10px] text-muted-foreground">đến</span>
                                            <input type="date" name="date_to" id="date_to" class="flex-1 h-8 px-2 text-xs border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all">
                                        </div>
                                    </div>
                                    <div class="space-y-1">
                                        <label class="text-[10px] font-bold text-muted-foreground uppercase tracking-widest block">Khoảng ngày hết hạn thẻ</label>
                                        <div class="flex items-center gap-1">
                                            <input type="date" name="expiry_from" class="flex-1 h-8 px-2 text-xs border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all">
                                            <span class="text-[10px] text-muted-foreground">đến</span>
                                            <input type="date" name="expiry_to" class="flex-1 h-8 px-2 text-xs border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all">
                                        </div>
                                    </div>
                                </div>

                                <!-- Giới hạn số lượng kết quả -->
                                <div class="space-y-2">
                                    <div class="space-y-1">
                                        <label class="text-[10px] font-bold text-muted-foreground uppercase tracking-widest block">Giới hạn số kết quả</label>
                                        <input type="number" name="result_limit" placeholder="Số lượng bản ghi..." class="w-full h-8 px-2 text-xs border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all">
                                    </div>
                                </div>
                            </div>

                            <!-- Tab 4: Chi nhánh / Đơn vị -->
                            <div id="tab-location" class="tab-content hidden grid grid-cols-1 md:grid-cols-2 gap-3">
                                <div class="space-y-1 col-span-2">
                                    <label for="branch_id" class="text-[10px] font-bold text-muted-foreground uppercase tracking-widest block">Đơn vị / Chi nhánh</label>
                                    <select name="branch_id" id="branch_id" class="select2-smart w-full">
                                        <option value="">-- Tất cả Đơn vị/Chi nhánh --</option>
                                        @foreach($branches as $b)
                                        <option value="{{ $b->id }}">{{ $b->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                        </div>

                        <!-- Close Button inside Floating Panel -->
                        <div class="flex justify-end pt-1.5 border-t border-border mt-2">
                            <button type="button" class="close-advanced-btn flex items-center gap-1 text-[11px] font-bold text-muted-foreground hover:text-foreground transition-all">
                                <i data-lucide="x" class="w-3.5 h-3.5"></i>
                                <span>{{ __('Đóng') }}</span>
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Format Toggle (Bottom bar) -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-t border-border pt-3">
                    <!-- Format Toggle -->
                    <div class="space-y-1">
                        <span class="text-[10px] font-bold text-muted-foreground uppercase tracking-widest block">{{ __('Định dạng file') }}</span>
                        <div class="inline-flex p-0.5 bg-muted rounded-sm border border-border shadow-inner">
                            <label class="relative group cursor-pointer">
                                <input type="radio" name="format" value="excel" checked class="sr-only peer">
                                <div class="px-3 py-1 text-xs font-bold rounded-sm transition-all peer-checked:bg-primary peer-checked:text-primary-foreground text-muted-foreground hover:text-foreground">
                                    <i class="fas fa-file-excel mr-1 text-xs"></i>Excel
                                </div>
                            </label>
                            <label class="relative group cursor-pointer ml-1">
                                <input type="radio" name="format" value="csv" class="sr-only peer">
                                <div class="px-3 py-1 text-xs font-bold rounded-sm transition-all peer-checked:bg-primary peer-checked:text-primary-foreground text-muted-foreground hover:text-foreground">
                                    <i class="fas fa-file-csv mr-1 text-xs"></i>CSV
                                </div>
                            </label>
                        </div>
                    </div>
                </div>
                
                <!-- Action Buttons -->
                <div class="flex items-center justify-between gap-2 border-t border-border pt-3 mt-3">
                    <button type="button" onclick="resetForm()" class="btn-compact-muted">
                        <i data-lucide="rotate-ccw" class="w-4 h-4 mr-1"></i>
                        {{ __('Reset bộ lọc') }}
                    </button>
                    <button type="submit" class="btn-compact-primary">
                        <i data-lucide="download" class="w-4 h-4 mr-1 text-primary-foreground"></i>
                        {{ __('XUẤT BÁO CÁO') }}
                    </button>
                </div>
            </form>
        </div>
        
    </div>
</div>

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    /* Customize Select2 to comply with Rule.txt constraints */
    .select2-container--default .select2-selection--single {
        height: 36px !important; /* h-9 */
        padding: 5px 10px !important;
        border-radius: 2px !important; /* rounded-sm */
        border: 1px solid hsl(var(--border)) !important;
        background-color: hsl(var(--background)) !important;
        font-size: 0.875rem !important; /* text-sm */
        font-weight: 500 !important;
        color: hsl(var(--foreground)) !important;
        outline: none !important;
        transition: all 0.2s !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: hsl(var(--foreground)) !important;
        padding-left: 0px !important;
        line-height: 24px !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 34px !important;
        right: 6px !important;
    }
    .select2-dropdown {
        border-radius: 4px !important; /* rounded-md */
        border: 1px solid hsl(var(--border)) !important;
        background-color: hsl(var(--card)) !important;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1) !important;
        overflow: hidden !important;
    }
    .select2-results__option {
        padding: 6px 12px !important;
        font-size: 0.75rem !important;
        font-weight: 500 !important;
        color: hsl(var(--foreground)) !important;
    }
    .select2-container--default .select2-results__option--highlighted[aria-selected] {
        background-color: hsl(var(--primary)) !important;
        color: hsl(var(--primary-foreground)) !important;
    }
    
    /* Custom Tree View Styles matching standard file directories */
    .tree-node-parent {
        position: relative;
        padding-left: 12px;
    }
    .tree-node-parent::before {
        content: "";
        position: absolute;
        left: 8px;
        top: 0px;
        bottom: 12px;
        border-left: 1.5px dashed hsl(var(--border));
    }
    .tree-node-child {
        position: relative;
        padding-left: 16px;
    }
    .tree-node-child::before {
        content: "";
        position: absolute;
        left: -4px;
        top: 14px;
        width: 14px;
        border-top: 1.5px dashed hsl(var(--border));
    }
    
    /* Custom Scrollbar for tree */
    .custom-scrollbar::-webkit-scrollbar {
        width: 4px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: transparent;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: hsl(var(--border));
        border-radius: 2px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: hsl(var(--muted-foreground));
    }

    /* Active Tab style constraint rule */
    .active-tab {
        background-color: hsl(var(--card)) !important;
        border-color: hsl(var(--border)) !important;
        color: hsl(var(--foreground)) !important;
        border-bottom-color: transparent !important;
    }

    /* Tree leaf hover rules preventing hover effect on active leaf but keeping it for inactive ones */
    .tree-leaf.active-leaf:hover {
        background-color: hsl(var(--primary)) !important;
        color: hsl(var(--primary-foreground)) !important;
    }
</style>
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        // Initialize Select2
        $('.select2-smart').select2({
            minimumResultsForSearch: 10,
            width: '100%'
        });

        // Folder toggle handler (collapse/expand)
        $('.tree-folder').click(function() {
            const groupId = $(this).data('group');
            const $group = $('#' + groupId);
            
            $group.slideToggle(150);
            
            // Toggle open/closed icons
            $(this).find('.toggle-open, .toggle-closed').toggleClass('hidden');
            $(this).find('.folder-open-icon, .folder-closed-icon').toggleClass('hidden');
        });

        // Collapse left column
        $('#toggle-tree-btn').click(function() {
            $('#left-tree-column').hide(150, function() {
                $('#expand-tree-btn').removeClass('hidden');
            });
        });

        // Expand left column
        $('#expand-tree-btn').click(function() {
            $('#expand-tree-btn').addClass('hidden');
            $('#left-tree-column').show(150);
        });

        // Toggle Advanced Search collapse
        $('#toggle-advanced-btn').click(function() {
            const $panel = $('#advanced-search-panel');
            const $chevron = $('#advanced-chevron');
            
            $panel.slideToggle(200);
            $chevron.toggleClass('rotate-180');
        });

        // Close Advanced Search
        $('.close-advanced-btn').click(function() {
            $('#advanced-search-panel').slideUp(200);
            $('#advanced-chevron').removeClass('rotate-180');
        });

        // Tab selection handler
        $('.tab-btn').click(function() {
            $('.tab-btn').removeClass('active-tab');
            $(this).addClass('active-tab');
            
            const activeTabId = $(this).data('tab');
            $('.tab-content').addClass('hidden');
            $('#' + activeTabId).removeClass('hidden');
        });
    });

    // Reset Form function
    function resetForm() {
        // Clear text inputs
        $('#search_input').val('');
        $('input[name="info_vals[]"]').val('');
        $('input[type="date"]').val('');
        $('input[type="text"]').val('');
        $('input[type="number"]').val('');
        
        // Uncheck checkboxes
        $('input[type="checkbox"]').prop('checked', false);
        
        // Reset select dropdowns
        $('select[name="info_fields[]"]').each(function(index) {
            if (index == 0) $(this).val('patron_code');
            else if (index == 1) $(this).val('name');
            else $(this).val('patron_code');
        });
        $('select[name="info_ops[]"]').val('AND');
        
        // Reset Select2 dropdowns
        $('#branch_id').val('').trigger('change');
        
        // Reset Format radio buttons
        $('input[name="format"][value="excel"]').prop('checked', true);
    }
</script>
@endpush
@endsection
