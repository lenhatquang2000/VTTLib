

<?php $__env->startSection('content'); ?>
<div class="space-y-8 animate-in fade-in duration-700">
    <!-- Notifications -->
    <?php if(session('success')): ?>
        <div class="bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-100 dark:border-emerald-500/20 text-emerald-600 dark:text-emerald-400 p-4 rounded-xl text-sm font-bold flex items-center space-x-3">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            <span><?php echo e(session('success')); ?></span>
        </div>
    <?php endif; ?>
    
    <?php if(session('error')): ?>
        <div class="bg-rose-50 dark:bg-rose-500/10 border border-rose-100 dark:border-rose-500/20 text-rose-600 dark:text-rose-400 p-4 rounded-xl text-sm font-bold flex items-center space-x-3">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span><?php echo e(session('error')); ?></span>
        </div>
    <?php endif; ?>

    <?php if(!$hasYaz): ?>
    <div class="bg-amber-50 dark:bg-amber-500/10 border border-amber-100 dark:border-amber-500/20 text-amber-600 dark:text-amber-400 p-4 rounded-xl text-sm">
        <div class="flex items-center space-x-3 mb-1">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            <strong class="font-bold"><?php echo e(__('YAZ_Extension_Not_Installed')); ?></strong>
        </div>
        <p class="font-medium opacity-90"><?php echo e(__('YAZ_extension_required_for_full_Z3950_functionality')); ?> <code class="bg-amber-100 dark:bg-amber-900/30 px-2 py-0.5 rounded text-xs font-mono ml-1">php-yaz</code></p>
    </div>
    <?php endif; ?>

    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6" x-data="{ showAddModal: false }">
        <div class="flex items-center space-x-4">
            <div class="w-12 h-12 rounded-xl bg-indigo-600 flex items-center justify-center shadow-lg shadow-indigo-200 dark:shadow-none">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path>
                </svg>
            </div>
            <div>
                <h1 class="text-3xl font-extrabold text-slate-900 dark:text-slate-100 tracking-tight"><?php echo e(__('Z3950_Servers')); ?></h1>
                <p class="text-slate-500 dark:text-slate-400 font-medium mt-1"><?php echo e(__('Manage_Z3950_database_connections_for_cataloging')); ?></p>
            </div>
        </div>
        <div class="flex items-center space-x-3">
            <a href="<?php echo e(route('admin.z3950.search')); ?>" class="inline-flex items-center px-6 py-3 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 font-bold rounded-xl hover:bg-slate-200 dark:hover:bg-slate-700 transition-all text-sm">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                <?php echo e(__('Search_Catalog')); ?>

            </a>
            <button @click="$dispatch('open-modal', 'add-server')" class="inline-flex items-center px-6 py-3 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-200 dark:shadow-none text-sm">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                <?php echo e(__('Add_Server')); ?>

            </button>
        </div>
    </div>

    <!-- Servers Table -->
    <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-800 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-slate-50/50 dark:bg-slate-800/50 border-b border-slate-100 dark:border-slate-800 text-left">
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest"><?php echo e(__('Server')); ?></th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest"><?php echo e(__('Connection')); ?></th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest"><?php echo e(__('Status')); ?></th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest"><?php echo e(__('Last_Test')); ?></th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest"><?php echo e(__('Actions')); ?></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 dark:divide-slate-800">
                    <?php $__empty_1 = true; $__currentLoopData = $servers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $server): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-slate-50/30 dark:hover:bg-slate-800/30 transition-colors group">
                        <td class="px-6 py-4">
                            <div class="flex items-center space-x-3">
                                <span class="w-2.5 h-2.5 rounded-full <?php echo e($server->is_active ? 'bg-emerald-500 shadow-sm shadow-emerald-200' : 'bg-slate-300'); ?>"></span>
                                <div>
                                    <div class="text-sm font-bold text-slate-900 dark:text-slate-100"><?php echo e($server->name); ?></div>
                                    <div class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 line-clamp-1 max-w-[200px]"><?php echo e($server->description); ?></div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-col space-y-1">
                                <code class="text-[10px] bg-slate-100 dark:bg-slate-800 px-2 py-1 rounded-md text-slate-600 dark:text-slate-300 font-mono inline-block w-fit">
                                    <?php echo e($server->host); ?>:<?php echo e($server->port); ?>/<?php echo e($server->database_name); ?>

                                </code>
                                <div class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">
                                    <?php echo e($server->record_syntax); ?> | <?php echo e($server->charset); ?>

                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <?php
                                $statusClasses = match($server->last_status) {
                                    'success' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400',
                                    'failed' => 'bg-rose-100 text-rose-700 dark:bg-rose-500/10 dark:text-rose-400',
                                    default => 'bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-400'
                                };
                            ?>
                            <span class="inline-flex px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider <?php echo e($statusClasses); ?>" id="status-<?php echo e($server->id); ?>">
                                <?php echo e(__(ucfirst($server->last_status ?: 'Unknown'))); ?>

                            </span>
                        </td>
                        <td class="px-6 py-4 text-center text-xs text-slate-500 dark:text-slate-400 font-medium whitespace-nowrap">
                            <?php echo e($server->last_connected_at ? $server->last_connected_at->diffForHumans() : '-'); ?>

                        </td>
                        <td class="px-6 py-4 text-right space-x-2 whitespace-nowrap">
                            <button onclick="testConnection(<?php echo e($server->id); ?>)" class="p-2 text-slate-400 hover:text-indigo-600 transition-colors bg-slate-50 dark:bg-slate-800 rounded-lg" title="<?php echo e(__('Test Connection')); ?>">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                            </button>
                            <button @click="$dispatch('open-modal', 'edit-server'); $dispatch('set-edit-server', <?php echo \Illuminate\Support\Js::from($server)->toHtml() ?>)" class="p-2 text-slate-400 hover:text-amber-600 transition-colors bg-slate-50 dark:bg-slate-800 rounded-lg" title="<?php echo e(__('Edit')); ?>">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </button>
                            <form action="<?php echo e(route('admin.z3950.destroy', $server)); ?>" method="POST" class="inline-block" onsubmit="return confirm(<?php echo \Illuminate\Support\Js::from(__('Delete_this_server?'))->toHtml() ?>)">
                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="p-2 text-slate-400 hover:text-rose-600 transition-colors bg-slate-50 dark:bg-slate-800 rounded-lg" title="<?php echo e(__('Delete')); ?>">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-slate-400 dark:text-slate-500">
                             <div class="flex flex-col items-center">
                                <svg class="w-12 h-12 text-slate-200 dark:text-slate-800 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                <p class="text-sm font-medium"><?php echo e(__('No_Z3950_servers_configured')); ?></p>
                             </div>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Recommended Servers Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-1 flex flex-col justify-center bg-indigo-50 dark:bg-indigo-900/20 p-8 rounded-3xl border border-indigo-100 dark:border-indigo-500/20">
            <h3 class="text-xl font-black text-indigo-900 dark:text-indigo-300 mb-2 uppercase tracking-tight"><?php echo e(__('Integration Nodes')); ?></h3>
            <p class="text-indigo-600 dark:text-indigo-400/80 text-sm font-medium leading-relaxed">
                <?php echo e(__('Z3950_is_a_standard_protocol_for_searching_bibliographic_databases')); ?>

            </p>
        </div>
        <div class="lg:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="p-6 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-3xl group hover:border-indigo-500 transition-all duration-300">
                <div class="flex items-center space-x-3 mb-3">
                    <div class="w-2 h-2 rounded-full bg-blue-500"></div>
                    <strong class="text-sm text-slate-800 dark:text-slate-200 uppercase tracking-wider font-black">Library of Congress</strong>
                </div>
                <code class="text-[10px] text-slate-400 font-mono bg-slate-50 dark:bg-slate-950 px-2.5 py-1.5 rounded-lg block">z3950.loc.gov:7090/VOYAGER</code>
            </div>
            <div class="p-6 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-3xl group hover:border-indigo-500 transition-all duration-300">
                <div class="flex items-center space-x-3 mb-3">
                    <div class="w-2 h-2 rounded-full bg-emerald-500"></div>
                    <strong class="text-sm text-slate-800 dark:text-slate-200 uppercase tracking-wider font-black">NLV (Việt Nam)</strong>
                </div>
                <code class="text-[10px] text-slate-400 font-mono bg-slate-50 dark:bg-slate-950 px-2.5 py-1.5 rounded-lg block">z3950.nlv.gov.vn:210/INNOPAC</code>
            </div>
            <div class="p-6 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-3xl group hover:border-indigo-500 transition-all duration-300 md:col-span-2">
                <div class="flex items-center space-x-3 mb-3">
                    <div class="w-2 h-2 rounded-full bg-indigo-500"></div>
                    <strong class="text-sm text-slate-800 dark:text-slate-200 uppercase tracking-wider font-black">OCLC WorldCat</strong>
                </div>
                <code class="text-[10px] text-slate-400 font-mono bg-slate-50 dark:bg-slate-950 px-2.5 py-1.5 rounded-lg block">zcat.oclc.org:210/OLUCWorldCat</code>
            </div>
        </div>
    </div>
</div>

<!-- Modal Manager (Shared Alpine Component) -->
<div x-data="{ 
    showAdd: false, 
    showEdit: false, 
    server: {},
    init() {
        window.addEventListener('open-modal', (e) => {
            if (e.detail === 'add-server') this.showAdd = true;
            if (e.detail === 'edit-server') this.showEdit = true;
        });
        window.addEventListener('set-edit-server', (e) => {
            this.server = e.detail;
            this.server.is_active = !!this.server.is_active;
            this.server.use_ssl = !!this.server.use_ssl;
        });
    }
}">
    <!-- Add Modal -->
    <template x-if="showAdd">
        <div class="fixed inset-0 z-[100] overflow-y-auto px-4 py-6 sm:px-0 flex items-center justify-center animate-in fade-in duration-300">
            <div class="fixed inset-0 transition-opacity bg-slate-900/60 backdrop-blur-sm" @click="showAdd = false"></div>
            
            <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] shadow-2xl border border-slate-100 dark:border-slate-800 overflow-hidden w-full max-w-2xl relative">
                <form action="<?php echo e(route('admin.z3950.store')); ?>" method="POST" class="p-8 md:p-10">
                    <?php echo csrf_field(); ?>
                    <div class="flex justify-between items-center mb-8">
                        <div>
                            <h3 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight uppercase"><?php echo e(__('Expand Registry')); ?></h3>
                            <p class="text-slate-500 text-xs font-bold"><?php echo e(__('Initialize new Z39.50 connection terminal')); ?></p>
                        </div>
                        <button type="button" @click="showAdd = false" class="p-2.5 bg-slate-50 dark:bg-slate-800 text-slate-400 hover:text-rose-500 transition-colors rounded-2xl">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2 space-y-2">
                             <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] pl-1"><?php echo e(__('Server Identity')); ?> (Name)</label>
                             <input type="text" name="name" required class="w-full px-5 py-4 bg-slate-50 dark:bg-slate-950 border-2 border-transparent rounded-2xl focus:bg-white dark:focus:bg-slate-800 focus:border-indigo-500 font-bold transition-all text-sm" placeholder="e.g. British Library">
                        </div>
                        <div class="space-y-2">
                             <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] pl-1"><?php echo e(__('Endpoint Host')); ?></label>
                             <input type="text" name="host" required class="w-full px-5 py-4 bg-slate-50 dark:bg-slate-950 border-2 border-transparent rounded-2xl focus:bg-white dark:focus:bg-slate-800 focus:border-indigo-500 font-bold transition-all text-sm" placeholder="z3950.bl.uk">
                        </div>
                        <div class="space-y-2">
                             <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] pl-1"><?php echo e(__('Communication Port')); ?></label>
                             <input type="number" name="port" value="210" required class="w-full px-5 py-4 bg-slate-50 dark:bg-slate-950 border-2 border-transparent rounded-2xl focus:bg-white dark:focus:bg-slate-800 focus:border-indigo-500 font-bold transition-all text-sm">
                        </div>
                        <div class="space-y-2">
                             <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] pl-1"><?php echo e(__('Internal DB Path')); ?></label>
                             <input type="text" name="database_name" required class="w-full px-5 py-4 bg-slate-50 dark:bg-slate-950 border-2 border-transparent rounded-2xl focus:bg-white dark:focus:bg-slate-800 focus:border-indigo-500 font-bold transition-all text-sm" placeholder="Main">
                        </div>
                        <div class="space-y-2">
                             <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] pl-1"><?php echo e(__('Dialect')); ?> (Record Syntax)</label>
                             <select name="record_syntax" required class="w-full px-5 py-4 bg-slate-50 dark:bg-slate-950 border-2 border-transparent rounded-2xl focus:bg-white dark:focus:bg-slate-800 focus:border-indigo-500 font-bold transition-all text-sm appearance-none">
                                <option value="MARC21">MARC21</option>
                                <option value="USMARC">USMARC</option>
                                <option value="UNIMARC">UNIMARC</option>
                             </select>
                        </div>
                    </div>

                    <div class="mt-10 flex items-center justify-between gap-4 border-t border-slate-50 dark:border-slate-800 pt-8">
                        <button type="button" @click="showAdd = false" class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest hover:text-rose-500 transition-colors">
                            <?php echo e(__('Abort')); ?>

                        </button>
                        <button type="submit" class="px-10 py-4 bg-indigo-600 text-white font-black rounded-3xl shadow-xl shadow-indigo-200 dark:shadow-none hover:bg-indigo-700 transition-all uppercase tracking-widest text-[10px]">
                            <?php echo e(__('Deploy Server')); ?>

                        </button>
                    </div>
                </form>
            </div>
        </div>
    </template>

    <!-- Edit Modal -->
    <template x-if="showEdit">
        <div class="fixed inset-0 z-[100] overflow-y-auto px-4 py-6 sm:px-0 flex items-center justify-center animate-in fade-in duration-300">
            <div class="fixed inset-0 transition-opacity bg-slate-900/60 backdrop-blur-sm" @click="showEdit = false"></div>
            
            <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] shadow-2xl border border-slate-100 dark:border-slate-800 overflow-hidden w-full max-w-2xl relative">
                <form :action="'<?php echo e(url('topsecret/z3950')); ?>/' + server.id" method="POST" class="p-8 md:p-10">
                    <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
                    <div class="flex justify-between items-center mb-8">
                        <div>
                            <h3 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight uppercase"><?php echo e(__('Modify Node')); ?></h3>
                            <p class="text-slate-500 text-xs font-bold"><?php echo e(__('Updating parameters for')); ?> <span class="text-indigo-500" x-text="server.name"></span></p>
                        </div>
                        <button type="button" @click="showEdit = false" class="p-2.5 bg-slate-50 dark:bg-slate-800 text-slate-400 hover:text-rose-500 transition-colors rounded-2xl">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2 space-y-2">
                             <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] pl-1"><?php echo e(__('Server Identity')); ?></label>
                             <input type="text" name="name" x-model="server.name" required class="w-full px-5 py-4 bg-slate-50 dark:bg-slate-950 border-2 border-transparent rounded-2xl focus:bg-white dark:focus:bg-slate-800 focus:border-indigo-500 font-bold transition-all text-sm">
                        </div>
                        <div class="space-y-2">
                             <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] pl-1"><?php echo e(__('Endpoint Host')); ?></label>
                             <input type="text" name="host" x-model="server.host" required class="w-full px-5 py-4 bg-slate-50 dark:bg-slate-950 border-2 border-transparent rounded-2xl focus:bg-white dark:focus:bg-slate-800 focus:border-indigo-500 font-bold transition-all text-sm">
                        </div>
                        <div class="space-y-2">
                             <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] pl-1"><?php echo e(__('Communication Port')); ?></label>
                             <input type="number" name="port" x-model="server.port" required class="w-full px-5 py-4 bg-slate-50 dark:bg-slate-950 border-2 border-transparent rounded-2xl focus:bg-white dark:focus:bg-slate-800 focus:border-indigo-500 font-bold transition-all text-sm">
                        </div>
                        <div class="space-y-2">
                             <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] pl-1"><?php echo e(__('Internal DB Path')); ?></label>
                             <input type="text" name="database_name" x-model="server.database_name" required class="w-full px-5 py-4 bg-slate-50 dark:bg-slate-950 border-2 border-transparent rounded-2xl focus:bg-white dark:focus:bg-slate-800 focus:border-indigo-500 font-bold transition-all text-sm">
                        </div>
                        <div class="space-y-2">
                             <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] pl-1"><?php echo e(__('Status')); ?></label>
                             <div class="flex items-center space-x-3 h-[54px] px-5 bg-slate-50 dark:bg-slate-950 rounded-2xl">
                                <input type="checkbox" name="is_active" x-model="server.is_active" value="1" id="edit_is_active_check" class="rounded border-slate-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <label for="edit_is_active_check" class="text-xs font-bold text-slate-600 dark:text-slate-400 uppercase tracking-widest"><?php echo e(__('Online')); ?></label>
                             </div>
                        </div>
                    </div>

                    <!-- Simplified/Hidden fields to keep UI clean, can add back as needed -->
                    <input type="hidden" name="record_syntax" x-model="server.record_syntax">
                    <input type="hidden" name="charset" x-model="server.charset">
                    <input type="hidden" name="timeout" x-model="server.timeout">
                    <input type="hidden" name="max_records" x-model="server.max_records">

                    <div class="mt-10 flex items-center justify-between gap-4 border-t border-slate-50 dark:border-slate-800 pt-8">
                        <button type="button" @click="showEdit = false" class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest hover:text-rose-500 transition-colors">
                            <?php echo e(__('Abort')); ?>

                        </button>
                        <button type="submit" class="px-10 py-4 bg-amber-500 hover:bg-amber-600 text-white font-black rounded-3xl shadow-xl shadow-amber-200 dark:shadow-none transition-all uppercase tracking-widest text-[10px]">
                            <?php echo e(__('Commit Changes')); ?>

                        </button>
                    </div>
                </form>
            </div>
        </div>
    </template>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
    function testConnection(serverId) {
        const statusEl = document.getElementById('status-' + serverId);
        const originalText = statusEl.textContent;
        const originalClass = statusEl.className;
        
        statusEl.textContent = '<?php echo e(__("Testing")); ?>...';
        statusEl.className = 'inline-flex px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider bg-indigo-100 text-indigo-700 dark:bg-indigo-500/10 dark:text-indigo-400 animate-pulse';

        fetch('/topsecret/z3950/' + serverId + '/test', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                'Accept': 'application/json',
            }
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                statusEl.textContent = '<?php echo e(__("Success")); ?>';
                statusEl.className = 'inline-flex px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider bg-emerald-100 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400';
                
                window.dispatchEvent(new CustomEvent('toast', {
                    detail: { message: '<?php echo e(__("Connection established successfully")); ?>', type: 'success' }
                }));
            } else {
                statusEl.textContent = '<?php echo e(__("Failed")); ?>';
                statusEl.className = 'inline-flex px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider bg-rose-100 text-rose-700 dark:bg-rose-500/10 dark:text-rose-400';
                
                window.dispatchEvent(new CustomEvent('toast', {
                    detail: { message: data.message || '<?php echo e(__("Connection failed")); ?>', type: 'error' }
                }));
            }
        })
        .catch(err => {
            statusEl.textContent = '<?php echo e(__("Error")); ?>';
            statusEl.className = 'inline-flex px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider bg-rose-100 text-rose-700 dark:bg-rose-500/10 dark:text-rose-400';
        });
    }
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\Workspace\VTTU\Laravel\VTTLib\resources\views/admin/z3950/index.blade.php ENDPATH**/ ?>