@extends('layouts.admin')

@section('content')
<div class="w-full space-y-4 animate-in fade-in duration-500 pb-8">
    <!-- Notifications -->
    @if(session('success'))
        <div class="p-3 bg-emerald-500/10 border border-emerald-500/20 text-emerald-600 dark:text-emerald-400 text-xs font-mono rounded-sm flex items-center gap-2">
            <i data-lucide="check-circle" class="w-4 h-4 text-emerald-500"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif
    
    @if(session('error'))
        <div class="p-3 bg-destructive/10 border border-destructive/20 text-destructive text-xs font-mono rounded-sm flex items-center gap-2">
            <i data-lucide="x-circle" class="w-4 h-4 text-destructive"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    @if(!$hasYaz)
    <div class="p-3 bg-amber-500/10 border border-amber-500/20 text-amber-600 dark:text-amber-400 rounded-sm text-xs space-y-1">
        <div class="flex items-center gap-2">
            <i data-lucide="alert-triangle" class="w-4 h-4 text-amber-500"></i>
            <strong class="font-bold">{{ __('YAZ_Extension_Not_Installed') }}</strong>
        </div>
        <p class="font-medium opacity-90">{{ __('YAZ_extension_required_for_full_Z3950_functionality') }} <code class="bg-amber-500/20 px-1.5 py-0.5 rounded text-[10px] font-mono">php-yaz</code></p>
    </div>
    @endif

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3" x-data="{ showAddModal: false }">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-sm bg-primary/10 border border-primary/20 flex items-center justify-center text-primary">
                <i data-lucide="server" class="w-5 h-5"></i>
            </div>
            <div>
                <h1 class="text-lg font-bold text-foreground tracking-tight">{{ __('Z3950_Servers') }}</h1>
                <p class="text-xs text-muted-foreground mt-0.5">{{ __('Manage_Z3950_database_connections_for_cataloging') }}</p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.z3950.search') }}" class="btn-compact-secondary">
                <i data-lucide="search" class="w-4 h-4 mr-1"></i>
                <span>{{ __('Search_Catalog') }}</span>
            </a>
            <button @click="$dispatch('open-modal', 'add-server')" class="btn-compact-primary">
                <i data-lucide="plus" class="w-4 h-4 mr-1"></i>
                <span>{{ __('Add_Server') }}</span>
            </button>
        </div>
    </div>

    <!-- Servers Table -->
    <div class="bg-card text-foreground rounded-md border border-border shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-muted/50 border-b border-border text-muted-foreground uppercase font-bold text-[10px] tracking-wider">
                    <tr>
                        <th class="py-2 px-3">{{ __('Server') }}</th>
                        <th class="py-2 px-3 w-64">{{ __('Connection') }}</th>
                        <th class="py-2 px-3 w-28 text-center">{{ __('Status') }}</th>
                        <th class="py-2 px-3 w-32 text-center">{{ __('Last_Test') }}</th>
                        <th class="py-2 px-3 w-32 text-right">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @forelse($servers as $server)
                    <tr class="table-row-hover group">
                        <td class="py-2 px-3">
                            <div class="flex items-center gap-2.5">
                                <span class="relative flex h-2 w-2">
                                    @if($server->is_active)
                                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                        <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                                    @else
                                        <span class="relative inline-flex rounded-full h-2 w-2 bg-muted-foreground/30"></span>
                                    @endif
                                </span>
                                <div>
                                    <div class="text-xs font-bold text-foreground leading-tight">{{ $server->name }}</div>
                                    @if($server->description)
                                        <div class="text-[10px] text-muted-foreground truncate max-w-xs mt-0.5">{{ $server->description }}</div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="py-2 px-3">
                            <div class="flex flex-col space-y-0.5">
                                <code class="text-[10px] bg-muted px-1.5 py-0.5 rounded text-foreground font-mono inline-block w-fit">
                                    {{ $server->host }}:{{ $server->port }}/{{ $server->database_name }}
                                </code>
                                <div class="text-[9px] text-muted-foreground/75 font-bold uppercase tracking-wider">
                                    {{ $server->record_syntax }} | {{ $server->charset }}
                                </div>
                            </div>
                        </td>
                        <td class="py-2 px-3 text-center">
                            @php
                                $statusClasses = match($server->last_status) {
                                    'success' => 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20',
                                    'failed' => 'bg-destructive/10 text-destructive border border-destructive/20',
                                    default => 'bg-muted text-muted-foreground border border-border'
                                };
                            @endphp
                            <span class="inline-flex px-1.5 py-0.5 rounded-sm text-[9px] font-bold uppercase tracking-wider {{ $statusClasses }}" id="status-{{ $server->id }}">
                                {{ __(ucfirst($server->last_status ?: 'Unknown')) }}
                            </span>
                        </td>
                        <td class="py-2 px-3 text-center text-xs text-muted-foreground font-medium whitespace-nowrap">
                            {{ $server->last_connected_at ? $server->last_connected_at->diffForHumans() : '-' }}
                        </td>
                        <td class="py-2 px-3 text-right">
                            <div class="flex justify-end items-center gap-1.5">
                                <button onclick="testConnection({{ $server->id }})" class="btn-icon-compact text-primary" title="{{ __('Test Connection') }}">
                                    <i data-lucide="zap" class="w-3.5 h-3.5"></i>
                                </button>
                                <button @click="$dispatch('open-modal', 'edit-server'); $dispatch('set-edit-server', @js($server))" class="btn-icon-compact text-amber-500" title="{{ __('Edit') }}">
                                    <i data-lucide="edit-2" class="w-3.5 h-3.5"></i>
                                </button>
                                <form action="{{ route('admin.z3950.destroy', $server) }}" method="POST" class="inline" onsubmit="return confirm(@js(__('Delete_this_server?')))">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-icon-danger" title="{{ __('Delete') }}">
                                        <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-8 text-center text-muted-foreground italic text-xs">
                             <div class="flex flex-col items-center">
                                <i data-lucide="server-off" class="w-8 h-8 text-muted-foreground/35 mb-2"></i>
                                <p>{{ __('No_Z3950_servers_configured') }}</p>
                             </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Recommended Servers Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-3">
        <div class="lg:col-span-1 flex flex-col justify-center bg-primary/10 border border-primary/20 p-4 rounded-md text-primary">
            <h3 class="text-sm font-extrabold uppercase tracking-wider mb-1">{{ __('Integration Nodes') }}</h3>
            <p class="opacity-80 text-xs font-medium leading-relaxed">
                {{ __('Z3950_is_a_standard_protocol_for_searching_bibliographic_databases') }}
            </p>
        </div>
        <div class="lg:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-3">
            <div class="p-3 bg-card border border-border rounded-md group hover:border-primary/40 transition-all duration-300">
                <div class="flex items-center gap-2 mb-2">
                    <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                    <strong class="text-xs text-foreground uppercase tracking-wider font-extrabold">Library of Congress</strong>
                </div>
                <code class="text-[10px] text-muted-foreground font-mono bg-muted/65 px-2 py-1 rounded block">z3950.loc.gov:7090/VOYAGER</code>
            </div>
            <div class="p-3 bg-card border border-border rounded-md group hover:border-primary/40 transition-all duration-300">
                <div class="flex items-center gap-2 mb-2">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                    <strong class="text-xs text-foreground uppercase tracking-wider font-extrabold">NLV (Việt Nam)</strong>
                </div>
                <code class="text-[10px] text-muted-foreground font-mono bg-muted/65 px-2 py-1 rounded block">z3950.nlv.gov.vn:210/INNOPAC</code>
            </div>
            <div class="p-3 bg-card border border-border rounded-md group hover:border-primary/40 transition-all duration-300 md:col-span-2">
                <div class="flex items-center gap-2 mb-2">
                    <span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span>
                    <strong class="text-xs text-foreground uppercase tracking-wider font-extrabold">OCLC WorldCat</strong>
                </div>
                <code class="text-[10px] text-muted-foreground font-mono bg-muted/65 px-2 py-1 rounded block">zcat.oclc.org:210/OLUCWorldCat</code>
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
            <div class="fixed inset-0 transition-opacity bg-slate-950/60 backdrop-blur-sm" @click="showAdd = false"></div>
            
            <div class="bg-card text-foreground rounded-md shadow-2xl border border-border overflow-hidden w-full max-w-lg relative z-10">
                <form action="{{ route('admin.z3950.store') }}" method="POST">
                    @csrf
                    <div class="px-4 py-3 bg-muted/30 border-b border-border flex justify-between items-center">
                        <div>
                            <h3 class="text-xs font-bold uppercase tracking-wider">{{ __('Expand Registry') }}</h3>
                            <p class="text-[10px] text-muted-foreground font-medium mt-0.5">{{ __('Initialize new Z39.50 connection terminal') }}</p>
                        </div>
                        <button type="button" @click="showAdd = false" class="text-muted-foreground hover:text-foreground">
                            <i data-lucide="x" class="w-5 h-5"></i>
                        </button>
                    </div>

                    <div class="p-4 space-y-3">
                        <div class="space-y-1">
                             <label class="block text-[10px] font-bold text-muted-foreground uppercase tracking-wider">{{ __('Server Identity') }} (Name)</label>
                             <input type="text" name="name" required class="w-full h-9 px-3 py-1.5 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all" placeholder="e.g. British Library">
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div class="space-y-1">
                                 <label class="block text-[10px] font-bold text-muted-foreground uppercase tracking-wider">{{ __('Endpoint Host') }}</label>
                                 <input type="text" name="host" required class="w-full h-9 px-3 py-1.5 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary font-mono transition-all" placeholder="z3950.bl.uk">
                            </div>
                            <div class="space-y-1">
                                 <label class="block text-[10px] font-bold text-muted-foreground uppercase tracking-wider">{{ __('Communication Port') }}</label>
                                 <input type="number" name="port" value="210" required class="w-full h-9 px-3 py-1.5 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary font-mono transition-all">
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div class="space-y-1">
                                 <label class="block text-[10px] font-bold text-muted-foreground uppercase tracking-wider">{{ __('Internal DB Path') }}</label>
                                 <input type="text" name="database_name" required class="w-full h-9 px-3 py-1.5 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all" placeholder="Main">
                            </div>
                            <div class="space-y-1">
                                 <label class="block text-[10px] font-bold text-muted-foreground uppercase tracking-wider">{{ __('Dialect') }} (Record Syntax)</label>
                                 <select name="record_syntax" required class="w-full h-9 px-3 py-1.5 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all">
                                    <option value="MARC21">MARC21</option>
                                    <option value="USMARC">USMARC</option>
                                    <option value="UNIMARC">UNIMARC</option>
                                 </select>
                            </div>
                        </div>
                    </div>

                    <div class="px-4 py-3 border-t border-border flex justify-end gap-2 bg-muted/10">
                        <button type="button" @click="showAdd = false" class="btn-compact-secondary h-9">
                            {{ __('Abort') }}
                        </button>
                        <button type="submit" class="btn-compact-primary h-9">
                            {{ __('Deploy Server') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </template>

    <!-- Edit Modal -->
    <template x-if="showEdit">
        <div class="fixed inset-0 z-[100] overflow-y-auto px-4 py-6 sm:px-0 flex items-center justify-center animate-in fade-in duration-300">
            <div class="fixed inset-0 transition-opacity bg-slate-950/60 backdrop-blur-sm" @click="showEdit = false"></div>
            
            <div class="bg-card text-foreground rounded-md shadow-2xl border border-border overflow-hidden w-full max-w-lg relative z-10">
                <form :action="'{{ url('topsecret/z3950') }}/' + server.id" method="POST">
                    @csrf @method('PUT')
                    <div class="px-4 py-3 bg-muted/30 border-b border-border flex justify-between items-center">
                        <div>
                            <h3 class="text-xs font-bold uppercase tracking-wider">{{ __('Modify Node') }}</h3>
                            <p class="text-[10px] text-muted-foreground font-medium mt-0.5">{{ __('Updating parameters for') }} <span class="text-primary font-bold" x-text="server.name"></span></p>
                        </div>
                        <button type="button" @click="showEdit = false" class="text-muted-foreground hover:text-foreground">
                            <i data-lucide="x" class="w-5 h-5"></i>
                        </button>
                    </div>

                    <div class="p-4 space-y-3">
                        <div class="space-y-1">
                             <label class="block text-[10px] font-bold text-muted-foreground uppercase tracking-wider">{{ __('Server Identity') }}</label>
                             <input type="text" name="name" x-model="server.name" required class="w-full h-9 px-3 py-1.5 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all">
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div class="space-y-1">
                                 <label class="block text-[10px] font-bold text-muted-foreground uppercase tracking-wider">{{ __('Endpoint Host') }}</label>
                                 <input type="text" name="host" x-model="server.host" required class="w-full h-9 px-3 py-1.5 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary font-mono transition-all">
                            </div>
                            <div class="space-y-1">
                                 <label class="block text-[10px] font-bold text-muted-foreground uppercase tracking-wider">{{ __('Communication Port') }}</label>
                                 <input type="number" name="port" x-model="server.port" required class="w-full h-9 px-3 py-1.5 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary font-mono transition-all">
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div class="space-y-1">
                                 <label class="block text-[10px] font-bold text-muted-foreground uppercase tracking-wider">{{ __('Internal DB Path') }}</label>
                                 <input type="text" name="database_name" x-model="server.database_name" required class="w-full h-9 px-3 py-1.5 text-sm border border-input rounded-sm bg-background text-foreground focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all">
                            </div>
                            <div class="space-y-1">
                                 <label class="block text-[10px] font-bold text-muted-foreground uppercase tracking-wider">{{ __('Status') }}</label>
                                 <label class="flex items-center gap-2 cursor-pointer h-9 group">
                                    <input type="checkbox" name="is_active" x-model="server.is_active" value="1" id="edit_is_active_check" class="w-4 h-4 rounded-sm border-input bg-background text-primary focus:ring-primary">
                                    <span class="text-xs font-semibold text-muted-foreground group-hover:text-primary transition-colors">{{ __('Online') }}</span>
                                 </label>
                            </div>
                        </div>
                    </div>

                    <!-- Simplified/Hidden fields to keep UI clean, can add back as needed -->
                    <input type="hidden" name="record_syntax" x-model="server.record_syntax">
                    <input type="hidden" name="charset" x-model="server.charset">
                    <input type="hidden" name="timeout" x-model="server.timeout">
                    <input type="hidden" name="max_records" x-model="server.max_records">

                    <div class="px-4 py-3 border-t border-border flex justify-end gap-2 bg-muted/10">
                        <button type="button" @click="showEdit = false" class="btn-compact-secondary h-9">
                            {{ __('Abort') }}
                        </button>
                        <button type="submit" class="btn-compact-primary h-9">
                            {{ __('Commit Changes') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </template>
</div>

@push('scripts')
<script>
    function testConnection(serverId) {
        const statusEl = document.getElementById('status-' + serverId);
        const originalText = statusEl.textContent;
        const originalClass = statusEl.className;
        
        statusEl.textContent = '{{ __("Testing") }}...';
        statusEl.className = 'inline-flex px-1.5 py-0.5 rounded-sm text-[9px] font-bold uppercase tracking-wider bg-primary/10 text-primary border border-primary/20 animate-pulse';

        fetch(`{{ route('admin.z3950.test', ['server' => ':id']) }}`.replace(':id', serverId), {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            }
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                statusEl.textContent = '{{ __("Success") }}';
                statusEl.className = 'inline-flex px-1.5 py-0.5 rounded-sm text-[9px] font-bold uppercase tracking-wider bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20';
                
                window.dispatchEvent(new CustomEvent('toast', {
                    detail: { message: '{{ __("Connection established successfully") }}', type: 'success' }
                }));
            } else {
                statusEl.textContent = '{{ __("Failed") }}';
                statusEl.className = 'inline-flex px-1.5 py-0.5 rounded-sm text-[9px] font-bold uppercase tracking-wider bg-destructive/10 text-destructive border border-destructive/20';
                
                window.dispatchEvent(new CustomEvent('toast', {
                    detail: { message: data.message || '{{ __("Connection failed") }}', type: 'error' }
                }));
            }
        })
        .catch(err => {
            statusEl.textContent = '{{ __("Error") }}';
            statusEl.className = 'inline-flex px-1.5 py-0.5 rounded-sm text-[9px] font-bold uppercase tracking-wider bg-destructive/10 text-destructive border border-destructive/20';
        });
    }
</script>
@endpush
@endsection
