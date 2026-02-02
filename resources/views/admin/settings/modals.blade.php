<!-- CREATE BARCODE MODAL -->
<div id="createBarcodeModal" class="fixed inset-0 z-[100] hidden">
    <div class="absolute inset-0 bg-slate-900/80 backdrop-blur-sm" onclick="closeModal('createBarcodeModal')"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-md p-4">
        <div class="bg-white rounded-3xl shadow-2xl border border-slate-100 overflow-hidden transform transition-all">
            <div class="px-8 py-6 bg-slate-50 border-b border-slate-100">
                <h3 class="text-xl font-extrabold text-slate-900 tracking-tight">{{ __('Initialize Barcode Rule') }}</h3>
            </div>
            <form action="{{ route('admin.settings.barcode.store') }}" method="POST" class="p-8 space-y-5">
                @csrf
                <div>
                    <label class="block text-[10px] text-slate-400 uppercase font-bold mb-1 tracking-widest">{{ __('Rule Label') }}</label>
                    <input type="text" name="name" required placeholder="{{ __('e.g. Sách tiếng Việt') }}"
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] text-slate-400 uppercase font-bold mb-1 tracking-widest">{{ __('Prefix ID') }}</label>
                        <input type="text" name="prefix" placeholder="VTTU"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none font-mono uppercase transition-all">
                    </div>
                    <div>
                        <label class="block text-[10px] text-slate-400 uppercase font-bold mb-1 tracking-widest">{{ __('Sequence Width') }}</label>
                        <input type="number" name="length" value="6" required min="1" max="20"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none font-mono transition-all">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] text-slate-400 uppercase font-bold mb-1 tracking-widest">{{ __('Start From') }}</label>
                        <input type="number" name="start_number" value="1" required min="0"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none font-mono transition-all">
                    </div>
                    <div>
                        <label class="block text-[10px] text-slate-400 uppercase font-bold mb-1 tracking-widest">{{ __('Target Node') }}</label>
                        <select name="target_type" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none appearance-none transition-all">
                            <option value="item">{{ __('Book Item') }}</option>
                            <option value="patron">{{ __('Patron ID') }}</option>
                        </select>
                    </div>
                </div>
                <label class="flex items-center space-x-3 cursor-pointer group">
                    <input type="checkbox" name="is_active" value="1" checked class="w-5 h-5 rounded-lg border-slate-300 bg-slate-50 text-indigo-600 focus:ring-indigo-500/20 transition-all">
                    <span class="text-xs text-slate-600 font-bold group-hover:text-indigo-600 transition-colors">{{ __('Set As Primary Rule') }}</span>
                </label>
                <div class="flex space-x-4 pt-4 border-t border-slate-50">
                    <button type="button" onclick="closeModal('createBarcodeModal')" class="flex-1 bg-white border border-slate-200 text-slate-400 py-3 rounded-xl uppercase text-xs font-bold hover:bg-slate-50 transition-all">{{ __('Abort') }}</button>
                    <button type="submit" class="flex-1 bg-indigo-600 text-white py-3 rounded-xl uppercase text-xs font-bold shadow-lg shadow-indigo-100 hover:bg-indigo-500 transition-all">{{ __('Execute') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- EDIT BARCODE MODAL -->
<div id="editBarcodeModal" class="fixed inset-0 z-[100] hidden">
    <div class="absolute inset-0 bg-slate-900/80 backdrop-blur-sm" onclick="closeModal('editBarcodeModal')"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-md p-4">
        <div class="bg-white rounded-3xl shadow-2xl border border-slate-100 overflow-hidden transform transition-all">
            <div class="px-8 py-6 bg-slate-50 border-b border-slate-100">
                <h3 class="text-xl font-extrabold text-slate-900 tracking-tight">{{ __('Modify Barcode Rule') }}</h3>
            </div>
            <form id="editBarcodeForm" method="POST" class="p-8 space-y-5">
                @csrf @method('PUT')
                <div>
                    <label class="block text-[10px] text-slate-400 uppercase font-bold mb-1 tracking-widest">{{ __('Rule Label') }}</label>
                    <input type="text" name="name" id="edit_name" required
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] text-slate-400 uppercase font-bold mb-1 tracking-widest">{{ __('Prefix ID') }}</label>
                        <input type="text" name="prefix" id="edit_prefix"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none font-mono uppercase transition-all">
                    </div>
                    <div>
                        <label class="block text-[10px] text-slate-400 uppercase font-bold mb-1 tracking-widest">{{ __('Sequence Width') }}</label>
                        <input type="number" name="length" id="edit_length" required min="1" max="20"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none font-mono transition-all">
                    </div>
                </div>
                <label class="flex items-center space-x-3 cursor-pointer group">
                    <input type="checkbox" name="is_active" id="edit_is_active" value="1" class="w-5 h-5 rounded-lg border-slate-300 bg-slate-50 text-indigo-600 focus:ring-indigo-500/20 transition-all">
                    <span class="text-xs text-slate-600 font-bold group-hover:text-indigo-600 transition-colors">{{ __('Set As Primary Rule') }}</span>
                </label>
                <div class="m-0 p-3 bg-amber-50 rounded-xl border border-amber-100 text-[10px] text-amber-600 italic leading-relaxed">
                    * {{ __('Changing the prefix only affects new barcodes. Existing ones remain unchanged.') }}
                </div>
                <div class="flex space-x-4 pt-4 border-t border-slate-50">
                    <button type="button" onclick="closeModal('editBarcodeModal')" class="flex-1 bg-white border border-slate-200 text-slate-400 py-3 rounded-xl uppercase text-xs font-bold hover:bg-slate-50 transition-all">{{ __('Abort') }}</button>
                    <button type="submit" class="flex-1 bg-indigo-600 text-white py-3 rounded-xl uppercase text-xs font-bold shadow-lg shadow-indigo-100 hover:bg-indigo-500 transition-all">{{ __('Update') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- CREATE BRANCH MODAL -->
<div id="createBranchModal" class="fixed inset-0 z-[100] hidden">
    <div class="absolute inset-0 bg-slate-900/80 backdrop-blur-sm" onclick="closeModal('createBranchModal')"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-md p-4">
        <div class="bg-white rounded-3xl shadow-2xl border border-slate-100 overflow-hidden transform transition-all">
            <div class="px-8 py-6 bg-slate-50 border-b border-slate-100">
                <h3 class="text-xl font-extrabold text-slate-900 tracking-tight">{{ __('Initialize New Branch') }}</h3>
            </div>
            <form action="{{ route('admin.settings.branches.store') }}" method="POST" class="p-8 space-y-5">
                @csrf
                <div>
                    <label class="block text-[10px] text-slate-400 uppercase font-bold mb-1 tracking-widest">{{ __('Branch Name') }}</label>
                    <input type="text" name="name" required class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all">
                </div>
                <div>
                    <label class="block text-[10px] text-slate-400 uppercase font-bold mb-1 tracking-widest">{{ __('Branch Code') }}</label>
                    <input type="text" name="code" required class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none font-mono uppercase tracking-[0.1em] transition-all">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] text-slate-400 uppercase font-bold mb-1 tracking-widest">{{ __('Phone') }}</label>
                        <input type="text" name="phone" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all">
                    </div>
                    <div>
                        <label class="block text-[10px] text-slate-400 uppercase font-bold mb-1 tracking-widest">{{ __('Address') }}</label>
                        <input type="text" name="address" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all">
                    </div>
                </div>
                <div class="flex space-x-4 pt-4 border-t border-slate-50">
                    <button type="button" onclick="closeModal('createBranchModal')" class="flex-1 bg-white border border-slate-200 text-slate-400 py-3 rounded-xl uppercase text-xs font-bold hover:bg-slate-50 transition-all">{{ __('Abort') }}</button>
                    <button type="submit" class="flex-1 bg-indigo-600 text-white py-3 rounded-xl uppercase text-xs font-bold shadow-lg shadow-indigo-100 hover:bg-indigo-500 transition-all">{{ __('Execute') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- CREATE LOCATION MODAL -->
<div id="createLocationModal" class="fixed inset-0 z-[100] hidden">
    <div class="absolute inset-0 bg-slate-900/80 backdrop-blur-sm" onclick="closeModal('createLocationModal')"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-md p-4">
        <div class="bg-white rounded-3xl shadow-2xl border border-slate-100 overflow-hidden transform transition-all">
            <div class="px-8 py-6 bg-slate-50 border-b border-slate-100">
                <h3 class="text-xl font-extrabold text-slate-900 tracking-tight">{{ __('Initialize Storage Location') }}</h3>
            </div>
            <form action="{{ route('admin.settings.locations.store') }}" method="POST" class="p-8 space-y-5">
                @csrf
                <div>
                    <label class="block text-[10px] text-slate-400 uppercase font-bold mb-1 tracking-widest">{{ __('Parent Branch') }}</label>
                    <select name="branch_id" required class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none appearance-none transition-all">
                        @foreach($branches as $branch)
                            <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-[10px] text-slate-400 uppercase font-bold mb-1 tracking-widest">{{ __('Location Name') }}</label>
                    <input type="text" name="name" required class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all">
                </div>
                <div>
                    <label class="block text-[10px] text-slate-400 uppercase font-bold mb-1 tracking-widest">{{ __('Location Code') }}</label>
                    <input type="text" name="code" required class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none font-mono uppercase tracking-[0.1em] transition-all">
                </div>
                <div class="flex space-x-4 pt-4 border-t border-slate-50">
                    <button type="button" onclick="closeModal('createLocationModal')" class="flex-1 bg-white border border-slate-200 text-slate-400 py-3 rounded-xl uppercase text-xs font-bold hover:bg-slate-50 transition-all">{{ __('Abort') }}</button>
                    <button type="submit" class="flex-1 bg-indigo-600 text-white py-3 rounded-xl uppercase text-xs font-bold shadow-lg shadow-indigo-100 hover:bg-indigo-500 transition-all">{{ __('Execute') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
