<!-- CREATE BARCODE MODAL -->
<div id="createBarcodeModal" class="fixed inset-0 z-[100] hidden">
    <div class="absolute inset-0 bg-slate-950/60 backdrop-blur-sm" onclick="closeModal('createBarcodeModal')"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-md p-4">
        <div class="bg-card text-foreground rounded-md border border-border shadow-2xl overflow-hidden transform transition-all">
            <div class="px-4 py-3 bg-muted/30 border-b border-border">
                <h3 class="text-sm font-bold uppercase tracking-wider text-foreground">{{ __('Initialize Barcode Rule') }}</h3>
            </div>
            <form action="{{ route('admin.settings.barcode.store') }}" method="POST" class="p-4 space-y-3">
                @csrf
                <div>
                    <label class="block text-[10px] text-muted-foreground uppercase font-bold mb-1 tracking-widest">{{ __('Rule Label') }}</label>
                    <input type="text" name="name" required placeholder="{{ __('e.g. Sách tiếng Việt') }}"
                        class="w-full h-9 px-3 py-1.5 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[10px] text-muted-foreground uppercase font-bold mb-1 tracking-widest">{{ __('Prefix ID') }}</label>
                        <input type="text" name="prefix" placeholder="VTTU"
                            class="w-full h-9 px-3 py-1.5 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary font-mono uppercase transition-all">
                    </div>
                    <div>
                        <label class="block text-[10px] text-muted-foreground uppercase font-bold mb-1 tracking-widest">{{ __('Sequence Width') }}</label>
                        <input type="number" name="length" value="6" required min="1" max="20"
                            class="w-full h-9 px-3 py-1.5 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary font-mono transition-all">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[10px] text-muted-foreground uppercase font-bold mb-1 tracking-widest">{{ __('Start From') }}</label>
                        <input type="number" name="start_number" value="1" required min="0"
                            class="w-full h-9 px-3 py-1.5 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary font-mono transition-all">
                    </div>
                    <div>
                        <label class="block text-[10px] text-muted-foreground uppercase font-bold mb-1 tracking-widest">{{ __('Target Node') }}</label>
                        <select name="target_type" class="w-full h-9 px-3 py-1.5 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all">
                            <option value="item">{{ __('Book Item') }}</option>
                            <option value="patron">{{ __('Patron ID') }}</option>
                        </select>
                    </div>
                </div>
                <label class="flex items-center gap-2 cursor-pointer group mt-1">
                    <input type="checkbox" name="is_active" value="1" checked class="w-4 h-4 rounded-sm border-input bg-background text-primary focus:ring-primary">
                    <span class="text-xs text-muted-foreground font-semibold group-hover:text-primary transition-colors">{{ __('Set As Primary Rule') }}</span>
                </label>
                <div class="flex gap-2 pt-3 border-t border-border mt-4">
                    <button type="button" onclick="closeModal('createBarcodeModal')" class="btn-compact-secondary flex-1 h-9 justify-center">{{ __('Abort') }}</button>
                    <button type="submit" class="btn-compact-primary flex-1 h-9 justify-center">{{ __('Execute') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- EDIT BARCODE MODAL -->
<div id="editBarcodeModal" class="fixed inset-0 z-[100] hidden">
    <div class="absolute inset-0 bg-slate-950/60 backdrop-blur-sm" onclick="closeModal('editBarcodeModal')"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-md p-4">
        <div class="bg-card text-foreground rounded-md border border-border shadow-2xl overflow-hidden transform transition-all">
            <div class="px-4 py-3 bg-muted/30 border-b border-border">
                <h3 class="text-sm font-bold uppercase tracking-wider text-foreground">{{ __('Modify Barcode Rule') }}</h3>
            </div>
            <form id="editBarcodeForm" method="POST" class="p-4 space-y-3">
                @csrf @method('PUT')
                <div>
                    <label class="block text-[10px] text-muted-foreground uppercase font-bold mb-1 tracking-widest">{{ __('Rule Label') }}</label>
                    <input type="text" name="name" id="edit_name" required
                        class="w-full h-9 px-3 py-1.5 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[10px] text-muted-foreground uppercase font-bold mb-1 tracking-widest">{{ __('Prefix ID') }}</label>
                        <input type="text" name="prefix" id="edit_prefix"
                            class="w-full h-9 px-3 py-1.5 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary font-mono uppercase transition-all">
                    </div>
                    <div>
                        <label class="block text-[10px] text-muted-foreground uppercase font-bold mb-1 tracking-widest">{{ __('Sequence Width') }}</label>
                        <input type="number" name="length" id="edit_length" required min="1" max="20"
                            class="w-full h-9 px-3 py-1.5 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary font-mono transition-all">
                    </div>
                </div>
                <label class="flex items-center gap-2 cursor-pointer group mt-1">
                    <input type="checkbox" name="is_active" id="edit_is_active" value="1" class="w-4 h-4 rounded-sm border-input bg-background text-primary focus:ring-primary">
                    <span class="text-xs text-muted-foreground font-semibold group-hover:text-primary transition-colors">{{ __('Set As Primary Rule') }}</span>
                </label>
                <div class="p-2 bg-amber-500/5 rounded border border-amber-500/10 text-[10px] text-amber-600 dark:text-amber-400 italic leading-relaxed">
                    * {{ __('Changing the prefix only affects new barcodes. Existing ones remain unchanged.') }}
                </div>
                <div class="flex gap-2 pt-3 border-t border-border mt-4">
                    <button type="button" onclick="closeModal('editBarcodeModal')" class="btn-compact-secondary flex-1 h-9 justify-center">{{ __('Abort') }}</button>
                    <button type="submit" class="btn-compact-primary flex-1 h-9 justify-center">{{ __('Update') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- CREATE BRANCH MODAL -->
<div id="createBranchModal" class="fixed inset-0 z-[100] hidden">
    <div class="absolute inset-0 bg-slate-950/60 backdrop-blur-sm" onclick="closeModal('createBranchModal')"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-md p-4">
        <div class="bg-card text-foreground rounded-md border border-border shadow-2xl overflow-hidden transform transition-all">
            <div class="px-4 py-3 bg-muted/30 border-b border-border">
                <h3 class="text-sm font-bold uppercase tracking-wider text-foreground">{{ __('Initialize New Branch') }}</h3>
            </div>
            <form action="{{ route('admin.settings.branches.store') }}" method="POST" class="p-4 space-y-3">
                @csrf
                <div>
                    <label class="block text-[10px] text-muted-foreground uppercase font-bold mb-1 tracking-widest">{{ __('Branch Name') }}</label>
                    <input type="text" name="name" required class="w-full h-9 px-3 py-1.5 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all">
                </div>
                <div>
                    <label class="block text-[10px] text-muted-foreground uppercase font-bold mb-1 tracking-widest">{{ __('Branch Code') }}</label>
                    <input type="text" name="code" required class="w-full h-9 px-3 py-1.5 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary font-mono uppercase tracking-[0.1em] transition-all">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[10px] text-muted-foreground uppercase font-bold mb-1 tracking-widest">{{ __('Phone') }}</label>
                        <input type="text" name="phone" class="w-full h-9 px-3 py-1.5 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all">
                    </div>
                    <div>
                        <label class="block text-[10px] text-muted-foreground uppercase font-bold mb-1 tracking-widest">{{ __('Address') }}</label>
                        <input type="text" name="address" class="w-full h-9 px-3 py-1.5 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all">
                    </div>
                </div>
                <div class="flex gap-2 pt-3 border-t border-border mt-4">
                    <button type="button" onclick="closeModal('createBranchModal')" class="btn-compact-secondary flex-1 h-9 justify-center">{{ __('Abort') }}</button>
                    <button type="submit" class="btn-compact-primary flex-1 h-9 justify-center">{{ __('Execute') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- CREATE LOCATION MODAL -->
<div id="createLocationModal" class="fixed inset-0 z-[100] hidden">
    <div class="absolute inset-0 bg-slate-950/60 backdrop-blur-sm" onclick="closeModal('createLocationModal')"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-md p-4">
        <div class="bg-card text-foreground rounded-md border border-border shadow-2xl overflow-hidden transform transition-all">
            <div class="px-4 py-3 bg-muted/30 border-b border-border">
                <h3 class="text-sm font-bold uppercase tracking-wider text-foreground">{{ __('Initialize Storage Location') }}</h3>
            </div>
            <form action="{{ route('admin.settings.locations.store') }}" method="POST" class="p-4 space-y-3">
                @csrf
                <div>
                    <label class="block text-[10px] text-muted-foreground uppercase font-bold mb-1 tracking-widest">{{ __('Parent Branch') }}</label>
                    <select name="branch_id" required class="w-full h-9 px-3 py-1.5 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all">
                        @foreach($branches as $branch)
                            <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-[10px] text-muted-foreground uppercase font-bold mb-1 tracking-widest">{{ __('Location Name') }}</label>
                    <input type="text" name="name" required class="w-full h-9 px-3 py-1.5 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all">
                </div>
                <div>
                    <label class="block text-[10px] text-muted-foreground uppercase font-bold mb-1 tracking-widest">{{ __('Location Code') }}</label>
                    <input type="text" name="code" required class="w-full h-9 px-3 py-1.5 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary font-mono uppercase tracking-[0.1em] transition-all">
                </div>
                <div class="flex gap-2 pt-3 border-t border-border mt-4">
                    <button type="button" onclick="closeModal('createLocationModal')" class="btn-compact-secondary flex-1 h-9 justify-center">{{ __('Abort') }}</button>
                    <button type="submit" class="btn-compact-primary flex-1 h-9 justify-center">{{ __('Execute') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- CREATE SUPPLIER MODAL -->
<div id="createSupplierModal" class="fixed inset-0 z-[100] hidden">
    <div class="absolute inset-0 bg-slate-950/60 backdrop-blur-sm" onclick="closeModal('createSupplierModal')"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-md p-4">
        <div class="bg-card text-foreground rounded-md border border-border shadow-2xl overflow-hidden transform transition-all">
            <div class="px-4 py-3 bg-muted/30 border-b border-border">
                <h3 class="text-sm font-bold uppercase tracking-wider text-foreground">{{ __('Register New Supplier') }}</h3>
            </div>
            <form action="{{ route('admin.settings.suppliers.store') }}" method="POST" class="p-4 space-y-3">
                @csrf
                <div>
                    <label class="block text-[10px] text-muted-foreground uppercase font-bold mb-1 tracking-widest">{{ __('Supplier Name') }}</label>
                    <input type="text" name="name" required class="w-full h-9 px-3 py-1.5 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all">
                </div>
                <div>
                    <label class="block text-[10px] text-muted-foreground uppercase font-bold mb-1 tracking-widest">{{ __('Supplier Code') }}</label>
                    <input type="text" name="code" required class="w-full h-9 px-3 py-1.5 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary font-mono uppercase tracking-[0.1em] transition-all">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[10px] text-muted-foreground uppercase font-bold mb-1 tracking-widest">{{ __('Contact Person') }}</label>
                        <input type="text" name="contact_name" class="w-full h-9 px-3 py-1.5 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all">
                    </div>
                    <div>
                        <label class="block text-[10px] text-muted-foreground uppercase font-bold mb-1 tracking-widest">{{ __('Phone Number') }}</label>
                        <input type="text" name="phone" class="w-full h-9 px-3 py-1.5 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all">
                    </div>
                </div>
                <div>
                    <label class="block text-[10px] text-muted-foreground uppercase font-bold mb-1 tracking-widest">{{ __('Email') }}</label>
                    <input type="email" name="email" class="w-full h-9 px-3 py-1.5 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all">
                </div>
                <div class="flex gap-2 pt-3 border-t border-border mt-4">
                    <button type="button" onclick="closeModal('createSupplierModal')" class="btn-compact-secondary flex-1 h-9 justify-center">{{ __('Abort') }}</button>
                    <button type="submit" class="btn-compact-primary flex-1 h-9 justify-center">{{ __('Execute') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- EDIT SUPPLIER MODAL -->
<div id="editSupplierModal" class="fixed inset-0 z-[100] hidden">
    <div class="absolute inset-0 bg-slate-950/60 backdrop-blur-sm" onclick="closeModal('editSupplierModal')"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-md p-4">
        <div class="bg-card text-foreground rounded-md border border-border shadow-2xl overflow-hidden transform transition-all">
            <div class="px-4 py-3 bg-muted/30 border-b border-border">
                <h3 class="text-sm font-bold uppercase tracking-wider text-foreground">{{ __('Modify Supplier Info') }}</h3>
            </div>
            <form id="editSupplierForm" method="POST" class="p-4 space-y-3">
                @csrf @method('PUT')
                <div>
                    <label class="block text-[10px] text-muted-foreground uppercase font-bold mb-1 tracking-widest">{{ __('Supplier Name') }}</label>
                    <input type="text" name="name" id="edit_sup_name" required
                        class="w-full h-9 px-3 py-1.5 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all">
                </div>
                <div>
                    <label class="block text-[10px] text-muted-foreground uppercase font-bold mb-1 tracking-widest">{{ __('Supplier Code') }}</label>
                    <input type="text" name="code" id="edit_sup_code" required
                        class="w-full h-9 px-3 py-1.5 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary font-mono uppercase tracking-[0.1em] transition-all">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[10px] text-muted-foreground uppercase font-bold mb-1 tracking-widest">{{ __('Contact Person') }}</label>
                        <input type="text" name="contact_name" id="edit_sup_contact"
                            class="w-full h-9 px-3 py-1.5 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all">
                    </div>
                    <div>
                        <label class="block text-[10px] text-muted-foreground uppercase font-bold mb-1 tracking-widest">{{ __('Phone Number') }}</label>
                        <input type="text" name="phone" id="edit_sup_phone"
                            class="w-full h-9 px-3 py-1.5 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all">
                    </div>
                </div>
                <div>
                    <label class="block text-[10px] text-muted-foreground uppercase font-bold mb-1 tracking-widest">{{ __('Email') }}</label>
                    <input type="email" name="email" id="edit_sup_email"
                        class="w-full h-9 px-3 py-1.5 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all">
                </div>
                <label class="flex items-center gap-2 cursor-pointer group mt-1">
                    <input type="checkbox" name="is_active" id="edit_sup_is_active" value="1" class="w-4 h-4 rounded-sm border-input bg-background text-primary focus:ring-primary">
                    <span class="text-xs text-muted-foreground font-semibold group-hover:text-primary transition-colors">{{ __('Active Status') }}</span>
                </label>
                <div class="flex gap-2 pt-3 border-t border-border mt-4">
                    <button type="button" onclick="closeModal('editSupplierModal')" class="btn-compact-secondary flex-1 h-9 justify-center">{{ __('Abort') }}</button>
                    <button type="submit" class="btn-compact-primary flex-1 h-9 justify-center">{{ __('Update') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- EDIT BRANCH MODAL -->
<div id="editBranchModal" class="fixed inset-0 z-[100] hidden">
    <div class="absolute inset-0 bg-slate-950/60 backdrop-blur-sm" onclick="closeModal('editBranchModal')"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-md p-4">
        <div class="bg-card text-foreground rounded-md border border-border shadow-2xl overflow-hidden transform transition-all">
            <div class="px-4 py-3 bg-muted/30 border-b border-border">
                <h3 class="text-sm font-bold uppercase tracking-wider text-foreground">{{ __('Modify Branch Info') }}</h3>
            </div>
            <form id="editBranchForm" method="POST" class="p-4 space-y-3">
                @csrf @method('PUT')
                <div>
                    <label class="block text-[10px] text-muted-foreground uppercase font-bold mb-1 tracking-widest">{{ __('Branch Name') }}</label>
                    <input type="text" name="name" id="edit_branch_name" required class="w-full h-9 px-3 py-1.5 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all">
                </div>
                <div>
                    <label class="block text-[10px] text-muted-foreground uppercase font-bold mb-1 tracking-widest">{{ __('Branch Code') }}</label>
                    <input type="text" name="code" id="edit_branch_code" required class="w-full h-9 px-3 py-1.5 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary font-mono uppercase tracking-[0.1em] transition-all">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[10px] text-muted-foreground uppercase font-bold mb-1 tracking-widest">{{ __('Phone') }}</label>
                        <input type="text" name="phone" id="edit_branch_phone" class="w-full h-9 px-3 py-1.5 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all">
                    </div>
                    <div>
                        <label class="block text-[10px] text-muted-foreground uppercase font-bold mb-1 tracking-widest">{{ __('Address') }}</label>
                        <input type="text" name="address" id="edit_branch_address" class="w-full h-9 px-3 py-1.5 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all">
                    </div>
                </div>
                <label class="flex items-center gap-2 cursor-pointer group mt-1">
                    <input type="checkbox" name="is_active" id="edit_branch_is_active" value="1" class="w-4 h-4 rounded-sm border-input bg-background text-primary focus:ring-primary">
                    <span class="text-xs text-muted-foreground font-semibold group-hover:text-primary transition-colors">{{ __('Active Status') }}</span>
                </label>
                <div class="flex gap-2 pt-3 border-t border-border mt-4">
                    <button type="button" onclick="closeModal('editBranchModal')" class="btn-compact-secondary flex-1 h-9 justify-center">{{ __('Abort') }}</button>
                    <button type="submit" class="btn-compact-primary flex-1 h-9 justify-center">{{ __('Update') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- EDIT LOCATION MODAL -->
<div id="editLocationModal" class="fixed inset-0 z-[100] hidden">
    <div class="absolute inset-0 bg-slate-950/60 backdrop-blur-sm" onclick="closeModal('editLocationModal')"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-md p-4">
        <div class="bg-card text-foreground rounded-md border border-border shadow-2xl overflow-hidden transform transition-all">
            <div class="px-4 py-3 bg-muted/30 border-b border-border">
                <h3 class="text-sm font-bold uppercase tracking-wider text-foreground">{{ __('Modify Storage Location') }}</h3>
            </div>
            <form id="editLocationForm" method="POST" class="p-4 space-y-3">
                @csrf @method('PUT')
                <div>
                    <label class="block text-[10px] text-muted-foreground uppercase font-bold mb-1 tracking-widest">{{ __('Location Name') }}</label>
                    <input type="text" name="name" id="edit_location_name" required class="w-full h-9 px-3 py-1.5 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all">
                </div>
                <div>
                    <label class="block text-[10px] text-muted-foreground uppercase font-bold mb-1 tracking-widest">{{ __('Location Code') }}</label>
                    <input type="text" name="code" id="edit_location_code" required class="w-full h-9 px-3 py-1.5 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary font-mono uppercase tracking-[0.1em] transition-all">
                </div>
                <div>
                    <label class="block text-[10px] text-muted-foreground uppercase font-bold mb-1 tracking-widest">{{ __('Description') }}</label>
                    <textarea name="description" id="edit_location_description" rows="3" class="w-full px-3 py-1.5 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all"></textarea>
                </div>
                <label class="flex items-center gap-2 cursor-pointer group mt-1">
                    <input type="checkbox" name="is_active" id="edit_location_is_active" value="1" class="w-4 h-4 rounded-sm border-input bg-background text-primary focus:ring-primary">
                    <span class="text-xs text-muted-foreground font-semibold group-hover:text-primary transition-colors">{{ __('Active Status') }}</span>
                </label>
                <div class="flex gap-2 pt-3 border-t border-border mt-4">
                    <button type="button" onclick="closeModal('editLocationModal')" class="btn-compact-secondary flex-1 h-9 justify-center">{{ __('Abort') }}</button>
                    <button type="submit" class="btn-compact-primary flex-1 h-9 justify-center">{{ __('Update') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
