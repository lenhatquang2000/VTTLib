<div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
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
    <div class="lg:col-span-4 bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-gray-100 dark:border-slate-800 overflow-hidden sticky top-6">
        <div class="p-5 border-b border-gray-100 dark:border-slate-800 bg-gray-50/50 dark:bg-slate-800/50">
            <h3 class="text-sm font-bold text-gray-800 dark:text-slate-100 uppercase tracking-wider flex items-center">
                <span class="w-2 h-2 bg-indigo-500 rounded-full mr-2" :class="editingIndex !== null ? 'animate-pulse' : ''"></span>
                <span x-text="editingIndex !== null ? '{{ __('Chỉnh sửa bản sách') }}' : '{{ __('Thêm bản sách mới') }}'"></span>
            </h3>
        </div>
        <div class="p-6 space-y-4">
            <div class="grid grid-cols-1 gap-4">
                <div class="space-y-1">
                    <label class="block text-[10px] font-bold text-gray-500 dark:text-slate-400 uppercase tracking-widest">{{ __('Mã vạch') }}</label>
                    <input type="text" x-model="newItem.barcode"
                        class="w-full px-4 py-2.5 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-lg text-sm font-mono focus:ring-2 focus:ring-indigo-500 transition-all"
                        placeholder="{{ $nextBarcode ?? 'AUTO' }}">
                </div>
                <div class="space-y-1">
                    <label class="block text-[10px] font-bold text-gray-500 dark:text-slate-400 uppercase tracking-widest">{{ __('Số đăng ký cá biệt') }}</label>
                    <input type="text" x-model="newItem.accession_number"
                        class="w-full px-4 py-2.5 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-lg text-sm font-mono focus:ring-2 focus:ring-indigo-500 transition-all"
                        placeholder="ACC-XXXXXX">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div class="space-y-1">
                    <label class="block text-[10px] font-bold text-gray-500 dark:text-slate-400 uppercase tracking-widest">{{ __('Chi nhánh') }}</label>
                    <select x-model="newItem.branch_id"
                        class="w-full px-4 py-2.5 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 appearance-none">
                        <option value="">-- {{ __('Chọn') }} --</option>
                        <template x-for="branch in branches" :key="branch.id">
                            <option :value="branch.id" x-text="branch.name"></option>
                        </template>
                    </select>
                </div>
                <div class="space-y-1">
                    <label class="block text-[10px] font-bold text-gray-500 dark:text-slate-400 uppercase tracking-widest">{{ __('Kho lưu trữ') }}</label>
                    <select x-model="newItem.storage_location_id" :disabled="!newItem.branch_id"
                        class="w-full px-4 py-2.5 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 appearance-none disabled:opacity-50">
                        <option value="">-- {{ __('Chọn') }} --</option>
                        <template x-for="loc in activeLocations" :key="loc.id">
                            <option :value="loc.id" x-text="loc.name"></option>
                        </template>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div class="space-y-1">
                    <label class="block text-[10px] font-bold text-gray-500 dark:text-slate-400 uppercase tracking-widest">{{ __('Thể loại lưu trữ') }}</label>
                    <select x-model="newItem.storage_type"
                        class="w-full px-4 py-2.5 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 appearance-none">
                        <option value="Book">{{ __('Sách') }}</option>
                        <option value="Daily newspaper">{{ __('Báo hàng ngày') }}</option>
                        <option value="Magazine">{{ __('Tạp chí') }}</option>
                    </select>
                </div>
                <div class="space-y-1">
                    <label class="block text-[10px] font-bold text-gray-500 dark:text-slate-400 uppercase tracking-widest">{{ __('Trạng thái') }}</label>
                    <select x-model="newItem.status"
                        class="w-full px-4 py-3 border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 dark:text-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 appearance-none cursor-pointer">
                        <option value="available">{{ __('Sẵn có') }}</option>
                        <option value="borrowed">{{ __('Đang mượn') }}</option>
                        <option value="lost">{{ __('Đã mất') }}</option>
                        <option value="damaged">{{ __('Hư hỏng') }}</option>
                    </select>
                </div>
            </div>

            <div class="space-y-1">
                <label class="block text-[10px] font-bold text-gray-500 dark:text-slate-400 uppercase tracking-widest">{{ __('Ghi chú') }}</label>
                <textarea x-model="newItem.notes" rows="2" class="w-full px-4 py-2.5 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-lg text-sm" placeholder="{{ __('Ghi chú...') }}"></textarea>
            </div>

            <div class="space-y-1" x-show="editingIndex === null">
                <label class="block text-[10px] font-bold text-gray-500 dark:text-slate-400 uppercase tracking-widest">{{ __('Số lượng phân bổ') }}</label>
                <input type="number" x-model="batchQuantity" min="1"
                    class="w-full px-4 py-2.5 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-lg text-sm font-mono focus:ring-2 focus:ring-indigo-500 transition-all">
                <p class="text-[9px] text-gray-400 mt-1 italic">{{ __('Tự động tạo các bản sách dựa trên số lượng này') }}</p>
            </div>

            <div class="flex gap-2 pt-2">
                <button type="button" @click="addItem()" class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-4 rounded-xl transition-all shadow-sm flex items-center justify-center space-x-2">
                    <svg x-show="editingIndex === null" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    <svg x-show="editingIndex !== null" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span class="uppercase tracking-widest" x-text="editingIndex !== null ? '{{ __('Cập nhật') }}' : '+ {{ __('Thêm mục') }}'"></span>
                </button>
                <button type="button" x-show="editingIndex !== null" @click="resetNewItem()" class="px-4 py-3 bg-gray-100 dark:bg-slate-800 text-gray-600 dark:text-slate-400 rounded-xl hover:bg-gray-200 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- RIGHT: List Table -->
    <div class="lg:col-span-8 bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-gray-100 dark:border-slate-800 overflow-hidden">
        <div class="p-5 border-b border-gray-100 dark:border-slate-800 bg-gray-50/50 dark:bg-slate-800/50 flex justify-between items-center">
            <h3 class="text-sm font-bold text-gray-800 dark:text-slate-100 uppercase tracking-wider">{{ __('Các mục trong hàng đợi') }}</h3>
            <span class="bg-indigo-100 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-400 text-[10px] font-bold px-2.5 py-1 rounded-full" x-text="items.length + ' {{ __('Mục') }}'"></span>
        </div>
        <div class="overflow-x-auto min-h-[400px]">
            <table class="w-full text-left">
                <thead class="bg-gray-50 dark:bg-slate-800/50 text-[10px] font-bold text-gray-400 dark:text-slate-500 uppercase tracking-widest border-b border-gray-100 dark:border-slate-800">
                    <tr>
                        <th class="px-6 py-4">{{ __('Thông tin nhận diện') }}</th>
                        <th class="px-6 py-4">{{ __('Lưu trữ') }}</th>
                        <th class="px-6 py-4">{{ __('Trạng thái') }}</th>
                        <th class="px-6 py-4">{{ __('Ảnh mã vạch') }}</th>
                        <th class="px-6 py-4"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 dark:divide-slate-800">
                    <template x-for="(item, index) in items" :key="index">
                        <tr class="hover:bg-gray-50 dark:hover:bg-slate-800/50 transition-colors group">
                            <td class="px-6 py-4">
                                <div class="flex flex-col space-y-1">
                                    <div class="flex items-center space-x-2">
                                        <span class="text-[9px] font-black text-indigo-500 uppercase tracking-tighter">BARCODE:</span>
                                        <span class="text-sm font-bold text-gray-800 dark:text-slate-200 font-mono" x-text="item.barcode || 'AUTO'"></span>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <span class="text-[9px] font-black text-emerald-500 uppercase tracking-tighter">ACC:</span>
                                        <span class="text-[10px] text-gray-400 dark:text-slate-500 font-mono" x-text="item.accession_number"></span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-col">
                                    <span class="text-sm font-bold text-gray-700 dark:text-slate-300" x-text="item.storage_type === 'Book' ? '{{ __('Sách') }}' : (item.storage_type === 'Daily newspaper' ? '{{ __('Báo hàng ngày') }}' : '{{ __('Tạp chí') }}')"></span>
                                    <div class="flex items-center space-x-1 mt-1">
                                        <span class="text-[10px] font-medium text-gray-400" 
                                            x-text="branches.find(b => b.id == item.branch_id)?.name || '-'"></span>
                                        <span class="text-[10px] bg-indigo-50 dark:bg-indigo-900/40 text-indigo-600 dark:text-indigo-400 px-1.5 py-0.5 rounded font-bold" 
                                            x-text="branches.find(b => b.id == item.branch_id)?.storage_locations.find(l => l.id == item.storage_location_id)?.name || '-'"></span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2 py-1 rounded-lg text-[9px] font-black uppercase tracking-wider border"
                                    :class="{
                                        'bg-emerald-50 text-emerald-600 border-emerald-100 dark:bg-emerald-900/20 dark:border-emerald-900/30': item.status === 'available',
                                        'bg-blue-50 text-blue-600 border-blue-100 dark:bg-blue-900/20 dark:border-blue-900/30': item.status === 'borrowed',
                                        'bg-rose-50 text-rose-600 border-rose-100 dark:bg-rose-900/20 dark:border-rose-900/30': item.status === 'lost'
                                    }">
                                    <span class="w-1 h-1 rounded-full bg-current mr-2 animate-pulse"></span>
                                    <span x-text="item.status === 'available' ? '{{ __('Sẵn có') }}' : (item.status === 'borrowed' ? '{{ __('Đang mượn') }}' : (item.status === 'lost' ? '{{ __('Đã mất') }}' : '{{ __('Hư hỏng') }}'))"></span>
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div x-show="item.barcode" class="w-[140px] h-[45px] bg-white p-1 rounded shadow-sm border border-slate-100 flex items-center justify-center overflow-hidden group-hover:scale-110 transition-transform duration-500">
                                    <img :src="'/storage/items/barcodes/' + item.barcode + '.svg'" 
                                         class="h-full w-full object-contain"
                                         x-on:error="$event.target.style.display='none'">
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end space-x-2">
                                    <button type="button" @click="editItem(index)" class="p-2 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 rounded-lg transition-all">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </button>
                                    <button type="button" @click="removeItem(index)" class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-all">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
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

