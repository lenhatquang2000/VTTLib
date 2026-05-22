<div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-gray-100 dark:border-slate-800 overflow-hidden">
    <div class="bg-slate-900 px-6 py-4 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div class="flex items-center space-x-3">
            <span class="bg-indigo-600 text-[10px] font-bold text-white px-2 py-0.5 rounded uppercase tracking-wider">MARC PREVIEW</span>
            <h3 class="text-white font-bold text-sm leading-none"><?php echo e(__('MARC21_Cataloging_Form')); ?></h3>
        </div>
        <span class="font-mono text-indigo-400 text-xs tracking-widest opacity-80" x-text="leader"></span>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full border-collapse">
            <thead>
                <tr class="bg-slate-50 dark:bg-slate-800/50 text-left border-b border-slate-100 dark:border-slate-800">
                    <th class="pl-8 py-4 text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em] w-64"><?php echo e(__('Tag')); ?></th>
                    <th class="px-4 py-4 text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em] text-center w-24"><?php echo e(__('Ind')); ?></th>
                    <th class="py-4 pr-8 text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em]"><?php echo e(__('Content_Data')); ?></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50 dark:divide-slate-800 font-mono text-sm">
                <tr class="hover:bg-slate-50/50 transition-colors">
                    <td class="pl-8 py-4 whitespace-nowrap align-top">
                        <div class="flex items-baseline space-x-3">
                            <span class="text-indigo-600 font-bold tracking-tight">000</span>
                            <span class="text-[9px] text-slate-400 uppercase font-sans tracking-tight leading-tight">LEADER</span>
                        </div>
                    </td>
                    <td class="px-4 py-4 text-center border-x border-slate-50 dark:border-slate-800 align-top">
                        <span class="text-slate-400 tracking-[0.2em] text-xs opacity-60 font-mono">##</span>
                    </td>
                    <td class="py-4 pr-8">
                        <span class="text-slate-700 dark:text-slate-300 font-mono" x-text="leader"></span>
                    </td>
                </tr>

                <template x-for="field in getActiveFields()" :key="field.tag">
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="pl-8 py-4 whitespace-nowrap align-top">
                            <div class="flex items-baseline space-x-3">
                                <span class="text-indigo-600 font-bold tracking-tight" x-text="field.tag"></span>
                                <span class="text-[9px] text-slate-400 uppercase font-sans tracking-tight leading-tight" x-text="field.label"></span>
                            </div>
                        </td>
                        <td class="px-4 py-4 text-center border-x border-slate-50 dark:border-slate-800 align-top">
                            <span class="text-slate-400 tracking-[0.2em] text-xs opacity-60 font-mono" x-text="(field.ind1?.trim() || '#') + (field.ind2?.trim() || '#')"></span>
                        </td>
                        <td class="py-4 pr-8">
                            <div class="flex flex-col space-y-2">
                                <template x-for="(sub, subIdx) in field.subfields.filter(s => s.code || s.value)" :key="subIdx">
                                    <div class="flex items-start space-x-2.5">
                                        <span class="text-emerald-600 dark:text-emerald-400 font-bold shrink-0 font-mono" x-text="'$' + (sub.code || '?')"></span>
                                        <span class="text-slate-700 dark:text-slate-300 grow break-words min-w-0 leading-relaxed" x-text="sub.value || '...'"></span>
                                        <span class="shrink-0 bg-slate-100 dark:bg-slate-800 text-slate-400 dark:text-slate-500 text-[8px] px-1.5 py-0.5 rounded font-sans uppercase tracking-widest mt-1"
                                            x-show="sub.code && getSubfieldLabel(field.tag, sub.code)"
                                            x-text="'[' + getSubfieldLabel(field.tag, sub.code) + ']'"></span>
                                    </div>
                                </template>
                            </div>
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>
    </div>
</div>
<?php /**PATH E:\Workspace\VTTU\Laravel\VTTLib\resources\views/admin/marc_books/components/preview_tab.blade.php ENDPATH**/ ?>