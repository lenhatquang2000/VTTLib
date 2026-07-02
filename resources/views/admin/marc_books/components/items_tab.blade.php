<div class="grid grid-cols-1 lg:grid-cols-12 gap-3 items-start">
    <!-- Hidden inputs for form submission -->
    <div class="hidden">
        <template x-for="(item, index) in items" :key="index">
            <div>
                <input type="hidden" :name="'items['+index+'][id]'" :value="item.id">
                <input type="hidden" :name="'items['+index+'][branch_id]'" :value="item.branch_id">
                <input type="hidden" :name="'items['+index+'][storage_location_id]'" :value="item.storage_location_id">
                <input type="hidden" :name="'items['+index+'][barcode]'" :value="item.barcode">
                <input type="hidden" :name="'items['+index+'][accession_number]'" :value="item.accession_number">
                <input type="hidden" :name="'items['+index+'][storage_type]'" :value="item.storage_type">
                <input type="hidden" :name="'items['+index+'][status]'" :value="item.status">
                <input type="hidden" :name="'items['+index+'][notes]'" :value="item.notes">
            </div>
        </template>
    </div>

    <!-- LEFT: Add/Edit Form -->
    <div class="lg:col-span-4 bg-card text-foreground rounded-md border border-border shadow-sm overflow-hidden sticky top-4">
        <div class="p-3 border-b border-border bg-muted/30">
            <h3 class="text-xs font-bold text-foreground uppercase tracking-wider flex items-center gap-1.5">
                <span class="w-1.5 h-1.5 bg-primary rounded-full" :class="editingIndex !== null ? 'animate-pulse' : ''"></span>
                <span x-text="editingIndex !== null ? '{{ __('Chỉnh sửa bản sách') }}' : '{{ __('Thêm bản sách mới') }}'"></span>
            </h3>
        </div>
        <div class="p-3 space-y-3">
            <div class="grid grid-cols-1 gap-3">
                <div class="space-y-1">
                    <label class="block text-[10px] font-bold text-muted-foreground uppercase tracking-wider">{{ __('Mã vạch') }}</label>
                    <input type="text" x-model="newItem.barcode"
                        class="w-full h-9 px-3 py-1.5 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary font-mono transition-all"
                        placeholder="{{ $nextBarcode ?? 'AUTO' }}">
                </div>
                <div class="space-y-1">
                    <label class="block text-[10px] font-bold text-muted-foreground uppercase tracking-wider">{{ __('Số đăng ký cá biệt') }}</label>
                    <input type="text" x-model="newItem.accession_number"
                        class="w-full h-9 px-3 py-1.5 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary font-mono transition-all"
                        placeholder="ACC-XXXXXX">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div class="space-y-1">
                    <label class="block text-[10px] font-bold text-muted-foreground uppercase tracking-wider">{{ __('Chi nhánh') }}</label>
                    <select x-model="newItem.branch_id"
                        class="w-full h-9 px-3 py-1.5 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all">
                        <option value="">-- {{ __('Chọn') }} --</option>
                        <template x-for="branch in branches" :key="branch.id">
                            <option :value="branch.id" x-text="branch.name"></option>
                        </template>
                    </select>
                </div>
                <div class="space-y-1">
                    <label class="block text-[10px] font-bold text-muted-foreground uppercase tracking-wider">{{ __('Kho lưu trữ') }}</label>
                    <select x-model="newItem.storage_location_id" :disabled="!newItem.branch_id"
                        class="w-full h-9 px-3 py-1.5 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all disabled:opacity-50">
                        <option value="">-- {{ __('Chọn') }} --</option>
                        <template x-for="loc in activeLocations" :key="loc.id">
                            <option :value="loc.id" x-text="loc.name"></option>
                        </template>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div class="space-y-1">
                    <label class="block text-[10px] font-bold text-muted-foreground uppercase tracking-wider">{{ __('Thể loại lưu trữ') }}</label>
                    <select x-model="newItem.storage_type"
                        class="w-full h-9 px-3 py-1.5 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all">
                        <option value="Book">{{ __('Sách') }}</option>
                        <option value="Daily newspaper">{{ __('Báo hàng ngày') }}</option>
                        <option value="Magazine">{{ __('Tạp chí') }}</option>
                    </select>
                </div>
                <div class="space-y-1">
                    <label class="block text-[10px] font-bold text-muted-foreground uppercase tracking-wider">{{ __('Trạng thái') }}</label>
                    <select x-model="newItem.status"
                        class="w-full h-9 px-3 py-1.5 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all">
                        <option value="available">{{ __('Sẵn có') }}</option>
                        <option value="on_loan">{{ __('Đang mượn') }}</option>
                        <option value="in_reading_room">{{ __('Mượn đọc tại chỗ') }}</option>
                        <option value="reserved">{{ __('Đặt giữ') }}</option>
                        <option value="lost">{{ __('Đã mất') }}</option>
                        <option value="damaged">{{ __('Hư hỏng') }}</option>
                        <option value="maintenance">{{ __('Bảo trì') }}</option>
                    </select>
                </div>
            </div>

            <div class="space-y-1">
                <label class="block text-[10px] font-bold text-muted-foreground uppercase tracking-wider">{{ __('Ghi chú') }}</label>
                <textarea x-model="newItem.notes" rows="2" class="w-full px-3 py-1.5 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all" placeholder="{{ __('Ghi chú...') }}"></textarea>
            </div>

            <div class="space-y-1" x-show="editingIndex === null">
                <label class="block text-[10px] font-bold text-muted-foreground uppercase tracking-wider">{{ __('Số lượng phân bổ') }}</label>
                <input type="number" x-model="batchQuantity" min="1"
                    class="w-full h-9 px-3 py-1.5 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary font-mono transition-all">
                <p class="text-[9px] text-muted-foreground mt-1 italic">{{ __('Tự động tạo các bản sách dựa trên số lượng này') }}</p>
            </div>

            <div class="flex gap-2 pt-2">
                <button type="button" @click="addItem()" class="flex-grow btn-compact-primary py-2.5 h-10 flex items-center justify-center gap-1.5">
                    <i x-show="editingIndex === null" data-lucide="plus" class="w-4 h-4"></i>
                    <i x-show="editingIndex !== null" data-lucide="check" class="w-4 h-4"></i>
                    <span class="uppercase font-bold tracking-wider text-xs" x-text="editingIndex !== null ? '{{ __('Cập nhật') }}' : '{{ __('Thêm mục') }}'"></span>
                </button>
                <button type="button" x-show="editingIndex !== null" @click="resetNewItem()" class="btn-compact-secondary px-3 h-10 flex items-center justify-center">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- RIGHT: List Table -->
    <div class="lg:col-span-8 bg-card text-foreground rounded-md border border-border shadow-sm overflow-hidden">
        <div class="p-3 border-b border-border bg-muted/30 flex justify-between items-center">
            <h3 class="text-xs font-bold text-foreground uppercase tracking-wider">{{ __('Các mục trong hàng đợi') }}</h3>
            <span class="bg-primary/10 text-primary text-[10px] font-bold px-2 py-0.5 rounded-sm border border-primary/20" x-text="items.length + ' {{ __('Mục') }}'"></span>
        </div>
        <div class="overflow-x-auto min-h-[400px]">
            <table class="w-full text-left border-collapse">
                <thead class="bg-muted/50 border-b border-border text-muted-foreground uppercase font-bold text-[10px] tracking-wider">
                    <tr>
                        <th class="py-2 px-3">{{ __('Thông tin nhận diện') }}</th>
                        <th class="py-2 px-3">{{ __('Lưu trữ') }}</th>
                        <th class="py-2 px-3">{{ __('Trạng thái') }}</th>
                        <th class="py-2 px-3">{{ __('Ảnh mã vạch') }}</th>
                        <th class="py-2 px-3 w-20 text-right"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    <template x-for="(item, index) in items" :key="index">
                        <tr class="table-row-hover group">
                            <td class="py-2 px-3">
                                <div class="flex flex-col space-y-0.5">
                                    <div class="flex items-center gap-1.5">
                                        <span class="text-[9px] font-bold text-primary uppercase tracking-tighter">BARCODE:</span>
                                        <span class="text-xs font-bold text-foreground font-mono" x-text="item.barcode || 'AUTO'"></span>
                                    </div>
                                    <div class="flex items-center gap-1.5">
                                        <span class="text-[9px] font-bold text-emerald-600 dark:text-emerald-400 uppercase tracking-tighter">ACC:</span>
                                        <span class="text-[10px] text-muted-foreground font-mono" x-text="item.accession_number"></span>
                                    </div>
                                </div>
                            </td>
                            <td class="py-2 px-3">
                                <div class="flex flex-col">
                                    <span class="text-xs font-bold text-foreground" x-text="item.storage_type === 'Book' ? '{{ __('Sách') }}' : (item.storage_type === 'Daily newspaper' ? '{{ __('Báo hàng ngày') }}' : '{{ __('Tạp chí') }}')"></span>
                                    <div class="flex items-center gap-1 mt-0.5">
                                        <span class="text-[10px] font-medium text-muted-foreground" 
                                            x-text="branches.find(b => b.id == item.branch_id)?.name || '-'"></span>
                                        <span class="text-[10px] bg-primary/10 text-primary px-1 rounded-sm font-bold border border-primary/20" 
                                            x-text="branches.find(b => b.id == item.branch_id)?.storage_locations.find(l => l.id == item.storage_location_id)?.name || '-'"></span>
                                    </div>
                                </div>
                            </td>
                            <td class="py-2 px-3">
                                <span class="inline-flex items-center px-1.5 py-0.5 rounded-sm text-[9px] font-bold uppercase tracking-wider border"
                                    :class="{
                                        'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border-emerald-500/20': item.status === 'available',
                                        'bg-blue-500/10 text-blue-600 dark:text-blue-400 border-blue-500/20': item.status === 'borrowed' || item.status === 'on_loan',
                                        'bg-purple-500/10 text-purple-600 dark:text-purple-400 border-purple-500/20': item.status === 'in_reading_room',
                                        'bg-orange-500/10 text-orange-600 dark:text-orange-400 border-orange-500/20': item.status === 'reserved',
                                        'bg-destructive/10 text-destructive border-destructive/20': item.status === 'lost' || item.status === 'damaged',
                                        'bg-amber-500/10 text-amber-600 dark:text-amber-400 border-amber-500/20': item.status === 'maintenance'
                                    }">
                                    <span class="w-1 h-1 rounded-full bg-current mr-1.5 animate-pulse"></span>
                                    <span x-text="item.status === 'available' ? '{{ __('Sẵn có') }}' : ((item.status === 'borrowed' || item.status === 'on_loan') ? '{{ __('Đang mượn') }}' : (item.status === 'in_reading_room' ? '{{ __('Mượn đọc tại chỗ') }}' : (item.status === 'reserved' ? '{{ __('Đặt giữ') }}' : (item.status === 'lost' ? '{{ __('Đã mất') }}' : (item.status === 'maintenance' ? '{{ __('Bảo trì') }}' : '{{ __('Hư hỏng') }}')))))"></span>
                                </span>
                            </td>
                            <td class="py-2 px-3">
                                <div x-show="item.barcode" class="w-[120px] h-[35px] bg-white p-0.5 rounded shadow-sm border border-border flex items-center justify-center overflow-hidden group-hover:scale-105 transition-transform duration-500">
                                    <img :src="'/storage/items/barcodes/' + item.barcode + '.svg'" 
                                         class="h-full w-full object-contain"
                                         x-on:error="$event.target.style.display='none'">
                                </div>
                            </td>
                            <td class="py-2 px-3 text-right">
                                <div class="flex items-center justify-end gap-1">
                                    <button type="button" @click="editItem(index)" class="btn-icon-compact text-amber-500" title="{{ __('Edit') }}">
                                        <i data-lucide="edit-2" class="w-3.5 h-3.5"></i>
                                    </button>
                                    <button type="button" @click="removeItem(index)" class="btn-icon-danger" title="{{ __('Delete') }}">
                                        <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>
</div>
