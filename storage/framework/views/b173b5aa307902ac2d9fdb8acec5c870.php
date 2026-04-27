

<?php $__env->startSection('content'); ?>
<div class="space-y-6" x-data="marcManager()">
    <?php if(session('success')): ?>
        <div class="bg-emerald-50 dark:bg-emerald-900/20 border-l-4 border-emerald-400 p-4 shadow-sm animate-fade-in-down">
            <div class="flex items-center">
                <div class="flex-shrink-0 text-emerald-400">
                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-emerald-800 dark:text-emerald-200"><?php echo e(session('success')); ?></p>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <div class="bg-rose-50 dark:bg-rose-900/20 border-l-4 border-rose-400 p-4 shadow-sm animate-fade-in-down">
            <div class="flex items-center">
                <div class="flex-shrink-0 text-rose-400">
                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-rose-800 dark:text-rose-200"><?php echo e(session('error')); ?></p>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Header & Framework Switcher -->
    <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-gray-100 dark:border-slate-800">
        <div class="p-6 border-b border-gray-100 dark:border-slate-800 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
            <div class="flex items-center space-x-4">
                <div class="p-3 bg-indigo-50 dark:bg-indigo-900/30 rounded-xl">
                    <svg class="w-8 h-8 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-800 dark:text-slate-100 tracking-tight"><?php echo e(__('MARC21_Framework_Manager')); ?></h2>
                    <div class="flex items-center mt-1 text-sm text-gray-500 dark:text-slate-400">
                        <span class="hover:text-indigo-600 cursor-help" title="MARC Frameworks define which fields and subfields are available during cataloging."><?php echo e(__('Template_Configuration')); ?></span>
                    </div>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <div class="relative inline-block w-full sm:w-auto">
                    <select onchange="window.location.href = '?framework_id=' + this.value" 
                        class="pl-4 pr-10 py-2.5 w-full sm:w-64 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl text-sm font-bold text-gray-700 dark:text-slate-200 focus:ring-2 focus:ring-indigo-500 transition-all cursor-pointer appearance-none shadow-sm">
                        <?php $__currentLoopData = $frameworks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fw): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($fw->id); ?>" <?php echo e($currentFramework && $currentFramework->id == $fw->id ? 'selected' : ''); ?>>
                                <?php echo e($fw->name); ?> (<?php echo e($fw->code); ?>)
                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <div class="absolute right-3 top-3 pointer-events-none text-gray-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </div>
                </div>

                <button @click="isNewFrameworkOpen = true" class="bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600 dark:text-indigo-400 px-4 py-2.5 rounded-xl text-sm font-bold hover:bg-indigo-100 dark:hover:bg-indigo-900/40 transition">
                    + <?php echo e(__('New_Framework')); ?>

                </button>
            </div>
        </div>

        <?php if($currentFramework): ?>
            <div class="px-6 py-4 bg-gray-50/50 dark:bg-slate-800/30 flex justify-between items-center text-xs">
                <div class="flex space-x-6 text-gray-500 dark:text-slate-400 font-bold uppercase tracking-wider">
                    <span>Code: <span class="text-indigo-600 dark:text-indigo-400"><?php echo e($currentFramework->code); ?></span></span>
                    <span>Status: <span class="<?php echo e($currentFramework->is_active ? 'text-emerald-500' : 'text-rose-500'); ?>"><?php echo e($currentFramework->is_active ? 'Active' : 'Disabled'); ?></span></span>
                </div>
                <div class="flex space-x-3">
                    <button @click="editFramework(<?php echo \Illuminate\Support\Js::from($currentFramework)->toHtml() ?>)" class="text-indigo-400 hover:text-indigo-600 transition"><?php echo e(__('Edit_Framework')); ?></button>
                    <form action="<?php echo e(route('admin.marc.framework.destroy', $currentFramework->id)); ?>" method="POST" onsubmit="return confirm('Delete this framework and all its definitions?')">
                        <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                        <button type="submit" class="text-rose-400 hover:text-rose-600 transition"><?php echo e(__('Delete')); ?></button>
                    </form>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Toolbar: Search & Action -->
    <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
        <form action="<?php echo e(route('admin.marc.index')); ?>" method="GET" class="relative group w-full sm:w-80">
            <input type="hidden" name="framework_id" value="<?php echo e($frameworkId); ?>">
            <input type="text" name="search" value="<?php echo e($search); ?>" placeholder="<?php echo e(__('Search')); ?> Tag/Label..." 
                class="pl-10 pr-4 py-3 bg-white dark:bg-slate-900 border border-gray-200 dark:border-slate-800 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 dark:text-slate-100 transition-all w-full shadow-sm">
            <svg class="w-4 h-4 absolute left-3.5 top-3.5 text-gray-400 group-focus-within:text-indigo-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
            <?php if($search): ?>
                <a href="<?php echo e(route('admin.marc.index', ['framework_id' => $frameworkId])); ?>" class="absolute right-3 top-3 text-gray-400 hover:text-rose-500 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </a>
            <?php endif; ?>
        </form>

        <button @click="isNewTagOpen = true" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-xl text-sm font-bold transition flex items-center shadow-lg shadow-indigo-200 dark:shadow-none whitespace-nowrap">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            <?php echo e(__('Register_New_Tag')); ?>

        </button>
    </div>

    <!-- MARC Tags List -->
    <div class="grid grid-cols-1 gap-4">
        <?php $__empty_1 = true; $__currentLoopData = $tags; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-800 overflow-hidden group hover:border-indigo-200 dark:hover:border-indigo-900 transition-all duration-300" 
                 x-data="{ isOpen: <?php echo e($search ? 'true' : 'false'); ?> }">
                
                <div class="p-5 flex justify-between items-center cursor-pointer hover:bg-gray-50/50 dark:hover:bg-slate-800/50" @click="isOpen = !isOpen">
                    <div class="flex items-center space-x-4">
                        <div class="bg-indigo-600 text-white w-12 h-12 flex items-center justify-center rounded-xl font-mono font-bold text-lg shadow-sm group-hover:scale-105 transition-transform">
                            <?php echo e($tag->tag); ?>

                        </div>
                        <div>
                            <h3 class="font-bold text-gray-800 dark:text-slate-100 uppercase tracking-tight text-base"><?php echo e($tag->label); ?></h3>
                            <div class="flex items-center space-x-3 mt-1.5">
                                <span class="px-2 py-0.5 rounded-md text-[10px] font-bold tracking-widest border <?php echo e($tag->pivot->is_visible ? 'border-emerald-200 bg-emerald-50 text-emerald-600' : 'border-gray-200 bg-gray-50 text-gray-400'); ?>">
                                    <?php echo e($tag->pivot->is_visible ? __('VISIBLE') : __('HIDDEN')); ?>

                                </span>
                                <span class="text-[10px] text-indigo-400 font-bold uppercase tracking-widest opacity-70"><?php echo e($tag->subfields->count()); ?> Subfields</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex items-center space-x-3" @click.stop>
                        <button @click="editTag(<?php echo \Illuminate\Support\Js::from($tag)->toHtml() ?>)" class="bg-gray-50 dark:bg-slate-800 p-2.5 rounded-lg text-gray-400 hover:text-indigo-600 transition shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        </button>
                        <button @click="newSubfield(<?php echo \Illuminate\Support\Js::from($tag)->toHtml() ?>)" class="bg-indigo-50 dark:bg-indigo-900/30 px-3 py-2 rounded-lg text-indigo-600 dark:text-indigo-400 text-[10px] font-bold uppercase hover:bg-indigo-100 transition shadow-sm">
                            + Subfield
                        </button>
                        
                        <form action="<?php echo e(route('admin.marc.tag.destroy', ['tag' => $tag->id, 'framework_id' => $frameworkId])); ?>" method="POST" onsubmit="return confirm('<?php echo e(__('Remove this Tag from this framework?')); ?>')">
                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="bg-gray-50 dark:bg-slate-800 p-2.5 rounded-lg text-gray-400 hover:text-rose-500 transition shadow-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </form>

                        <div class="ml-2 border-l pl-4 border-gray-100 dark:border-slate-800 transition-transform duration-300" :class="isOpen ? 'rotate-180' : ''">
                             <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                </div>
                
                <div x-show="isOpen" x-cloak x-collapse class="border-t border-gray-50 dark:border-slate-800 bg-gray-50/20 dark:bg-slate-900/40">
                    <div class="p-6">
                        <?php if($tag->description): ?>
                            <div class="mb-6 p-4 bg-gray-100 dark:bg-slate-800 rounded-xl text-sm text-gray-600 dark:text-slate-400 italic">
                                <?php echo e($tag->description); ?>

                            </div>
                        <?php endif; ?>

                        <div class="overflow-x-auto rounded-xl border border-gray-100 dark:border-slate-800">
                            <table class="w-full text-sm text-left">
                                <thead class="bg-gray-50 dark:bg-slate-800 text-[10px] uppercase font-bold text-gray-500 dark:text-slate-400 tracking-wider font-mono">
                                    <tr>
                                        <th class="px-6 py-4">Code</th>
                                        <th class="px-6 py-4">Label</th>
                                        <th class="px-6 py-4">Constraints</th>
                                        <th class="px-6 py-4 text-right">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 dark:divide-slate-800 bg-white dark:bg-slate-900">
                                    <?php $__empty_2 = true; $__currentLoopData = $tag->subfields; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sub): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
                                        <tr class="hover:bg-indigo-50/30 dark:hover:bg-indigo-900/10 transition">
                                            <td class="px-6 py-4 font-mono font-bold text-indigo-600 dark:text-indigo-400">$<?php echo e($sub->code); ?></td>
                                            <td class="px-6 py-4 text-gray-700 dark:text-slate-300 font-medium"><?php echo e($sub->label); ?></td>
                                            <td class="px-6 py-3 flex flex-wrap gap-2">
                                                <?php if($sub->is_mandatory): ?>
                                                    <span class="bg-rose-100 text-rose-700 px-2 py-0.5 rounded text-[10px] font-bold uppercase ring-1 ring-rose-200">Mandatory</span>
                                                <?php endif; ?>
                                                <?php if($sub->is_repeatable): ?>
                                                    <span class="bg-indigo-100 text-indigo-700 px-2 py-0.5 rounded text-[10px] font-bold uppercase ring-1 ring-indigo-200">Repeatable</span>
                                                <?php endif; ?>
                                                <?php if(!$sub->is_visible): ?>
                                                    <span class="bg-gray-100 text-gray-500 px-2 py-0.5 rounded text-[10px] font-bold uppercase ring-1 ring-gray-200"><?php echo e(__('HIDDEN')); ?></span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="px-6 py-4 text-right">
                                                <div class="flex justify-end space-x-1">
                                                    <button @click="editSubfield(<?php echo \Illuminate\Support\Js::from($sub)->toHtml() ?>)" class="text-indigo-400 hover:text-indigo-700 p-2 rounded-lg hover:bg-indigo-50 dark:hover:bg-indigo-900/20 transition">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                                    </button>
                                                    <form action="<?php echo e(route('admin.marc.subfield.destroy', $sub->id)); ?>" method="POST">
                                                        <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                                        <button type="submit" class="text-gray-400 hover:text-rose-500 p-2 rounded-lg hover:bg-rose-50 dark:hover:bg-rose-900/20 transition">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
                                        <tr>
                                            <td colspan="4" class="px-6 py-10 text-center text-gray-400 italic text-xs bg-gray-50/10">No subfields defined.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="bg-white dark:bg-slate-900 p-20 rounded-2xl border-2 border-dashed border-gray-100 dark:border-slate-800 text-center">
                <div class="bg-indigo-50 dark:bg-slate-800 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6 shadow-sm">
                    <svg class="w-10 h-10 text-indigo-300 dark:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                </div>
                <h3 class="text-gray-900 dark:text-slate-100 font-bold uppercase tracking-widest text-base">No Data Found</h3>
                <p class="text-gray-500 dark:text-slate-400 text-sm mt-2 font-medium max-w-sm mx-auto">This framework doesn't have any tags registered yet. Start by defining your first MARC tag.</p>
                <button @click="isNewTagOpen = true" class="mt-6 bg-indigo-600 text-white px-8 py-3 rounded-xl font-bold uppercase text-xs hover:bg-indigo-700 transition shadow-lg shadow-indigo-100 dark:shadow-none font-mono">Register_New_Tag</button>
            </div>
        <?php endif; ?>
    </div>

    <!-- Modals -->
    <?php echo $__env->make('admin.marc_definitions.modals', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
</div>

<script>
function marcManager() {
    return {
        isNewFrameworkOpen: <?php echo json_encode($errors->has('code') && !old('tag_id'), 15, 512) ?>,
        isEditFrameworkOpen: false,
        isNewTagOpen: <?php echo json_encode($errors->has('tag'), 15, 512) ?>,
        isEditTagOpen: false,
        isNewSubfieldOpen: <?php echo json_encode($errors->has('code') && old('tag_id'), 15, 512) ?>,
        isEditSubfieldOpen: false,

        framework: { id: null, code: '', name: '', description: '', is_active: true },
        tag: { id: null, tag: <?php echo \Illuminate\Support\Js::from(old('tag', ''))->toHtml() ?>, label: <?php echo \Illuminate\Support\Js::from(old('label', ''))->toHtml() ?>, description: '', is_visible: true, framework_id: <?php echo \Illuminate\Support\Js::from($frameworkId)->toHtml() ?> },
        subfield: { id: null, tag_id: <?php echo \Illuminate\Support\Js::from(old('tag_id'))->toHtml() ?>, code: <?php echo \Illuminate\Support\Js::from(old('code', ''))->toHtml() ?>, label: <?php echo \Illuminate\Support\Js::from(old('label', ''))->toHtml() ?>, is_visible: true, is_mandatory: false, is_repeatable: false, help_text: '' },
        
        targetTag: <?php echo \Illuminate\Support\Js::from($errors->has('code') && old('tag_id') ? \App\Models\MarcTagDefinition::find(old('tag_id')) : ['tag' => '', 'label' => '', 'id' => null])->toHtml() ?>,

        editFramework(fw) {
            this.framework = { ...fw };
            this.isEditFrameworkOpen = true;
        },

        editTag(tag) {
            this.tag = { ...tag, is_visible: tag.pivot.is_visible };
            this.isEditTagOpen = true;
        },

        newSubfield(tag) {
            this.targetTag = tag;
            this.subfield = { tag_id: tag.id, code: '', label: '', is_visible: true, is_mandatory: false, is_repeatable: false, help_text: '' };
            this.isNewSubfieldOpen = true;
        },

        editSubfield(sub) {
            this.subfield = { ...sub };
            this.isEditSubfieldOpen = true;
        }
    }
}
</script>

<style>
    @keyframes fade-in-down {
        0% { opacity: 0; transform: translateY(-10px); }
        100% { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in-down { animation: fade-in-down 0.4s ease-out; }
    [x-cloak] { display: none !important; }
</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\Workspace\VTTU\Laravel\VTTLib\resources\views/admin/marc_definitions/index.blade.php ENDPATH**/ ?>