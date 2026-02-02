@extends('layouts.admin')

@section('content')
<div class="space-y-8">
    @if(session('success'))
        <div class="bg-green-900/20 border border-green-500 text-green-400 p-4 text-xs font-mono rounded">
            [OK] {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-900/20 border border-red-500 text-red-400 p-4 text-xs font-mono rounded">
            [ERROR] {{ session('error') }}
        </div>
    @endif

    @if(!$hasYaz)
    <div class="bg-yellow-900/20 border border-yellow-500 text-yellow-400 p-4 text-sm rounded">
        <strong>⚠️ {{ __('YAZ_Extension_Not_Installed') }}</strong><br>
        {{ __('YAZ_extension_required_for_full_Z3950_functionality') }}
        <code class="bg-gray-800 px-2 py-1 rounded ml-2">php-yaz</code>
    </div>
    @endif

    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold">{{ __('Z3950_Servers') }}</h1>
            <p class="text-sm text-gray-400 mt-1">{{ __('Manage_Z3950_database_connections_for_cataloging') }}</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.z3950.search') }}" class="btn-secondary">
                {{ __('Search_Catalog') }}
            </a>
            <button onclick="openModal('addServerModal')" class="btn-primary">
                {{ __('Add_Server') }}
            </button>
        </div>
    </div>

    <!-- Servers Table -->
    <div class="card-admin rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-800 text-xs uppercase">
                    <tr>
                        <th class="p-3 text-left">{{ __('Server') }}</th>
                        <th class="p-3 text-left">{{ __('Connection') }}</th>
                        <th class="p-3 text-center">{{ __('Status') }}</th>
                        <th class="p-3 text-center">{{ __('Last_Test') }}</th>
                        <th class="p-3 text-left">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700">
                    @forelse($servers as $server)
                    <tr class="hover:bg-gray-800/50">
                        <td class="p-3">
                            <div class="flex items-center gap-2">
                                @if($server->is_active)
                                    <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                                @else
                                    <span class="w-2 h-2 bg-gray-500 rounded-full"></span>
                                @endif
                                <div>
                                    <div class="font-medium">{{ $server->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $server->description }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="p-3">
                            <code class="text-xs bg-gray-800 px-2 py-1 rounded">{{ $server->connection_string }}</code>
                            <div class="text-xs text-gray-500 mt-1">
                                {{ $server->record_syntax }} | {{ $server->charset }}
                            </div>
                        </td>
                        <td class="p-3 text-center">
                            @php
                                $statusClass = match($server->last_status) {
                                    'success' => 'bg-green-900/50 text-green-400',
                                    'failed' => 'bg-red-900/50 text-red-400',
                                    default => 'bg-gray-900/50 text-gray-400'
                                };
                            @endphp
                            <span class="px-2 py-1 rounded text-xs font-bold {{ $statusClass }}" id="status-{{ $server->id }}">
                                {{ __(ucfirst($server->last_status)) }}
                            </span>
                        </td>
                        <td class="p-3 text-center text-xs text-gray-400">
                            {{ $server->last_connected_at ? $server->last_connected_at->diffForHumans() : '-' }}
                        </td>
                        <td class="p-3">
                            <button onclick="testConnection({{ $server->id }})" class="text-blue-400 hover:text-blue-300 text-xs mr-2">
                                {{ __('Test') }}
                            </button>
                            <button onclick="editServer({{ json_encode($server) }})" class="text-yellow-400 hover:text-yellow-300 text-xs mr-2">
                                {{ __('Edit') }}
                            </button>
                            <form action="{{ route('admin.z3950.destroy', $server) }}" method="POST" class="inline"
                                onsubmit="return confirm('{{ __('Delete_this_server?') }}')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-400 hover:text-red-300 text-xs">{{ __('Delete') }}</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="p-8 text-center text-gray-500">{{ __('No_Z3950_servers_configured') }}</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Info Box -->
    <div class="card-admin rounded-lg p-4">
        <h3 class="text-sm font-bold text-gray-300 mb-3">{{ __('About_Z3950') }}</h3>
        <p class="text-xs text-gray-400 mb-3">
            {{ __('Z3950_is_a_standard_protocol_for_searching_bibliographic_databases') }}
        </p>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-xs">
            <div class="bg-gray-800/50 p-3 rounded">
                <strong class="text-blue-400">Library of Congress</strong>
                <p class="text-gray-500 mt-1">z3950.loc.gov:7090/VOYAGER</p>
            </div>
            <div class="bg-gray-800/50 p-3 rounded">
                <strong class="text-blue-400">Thư viện Quốc gia VN</strong>
                <p class="text-gray-500 mt-1">z3950.nlv.gov.vn:210/INNOPAC</p>
            </div>
            <div class="bg-gray-800/50 p-3 rounded">
                <strong class="text-blue-400">OCLC WorldCat</strong>
                <p class="text-gray-500 mt-1">zcat.oclc.org:210/OLUCWorldCat</p>
            </div>
        </div>
    </div>
</div>

<!-- Add Server Modal -->
<div id="addServerModal" class="modal hidden">
    <div class="modal-backdrop" onclick="closeModal('addServerModal')"></div>
    <div class="modal-content max-w-2xl">
        <h3 class="text-lg font-bold mb-4">{{ __('Add_Z3950_Server') }}</h3>
        <form action="{{ route('admin.z3950.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium mb-1">{{ __('Server_Name') }} *</label>
                    <input type="text" name="name" required class="input-field w-full" placeholder="{{ __('e.g._Library_of_Congress') }}">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">{{ __('Host') }} *</label>
                    <input type="text" name="host" required class="input-field w-full" placeholder="z3950.loc.gov">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">{{ __('Port') }} *</label>
                    <input type="number" name="port" value="210" required min="1" max="65535" class="input-field w-full">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">{{ __('Database_Name') }} *</label>
                    <input type="text" name="database_name" required class="input-field w-full" placeholder="VOYAGER">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">{{ __('Record_Syntax') }} *</label>
                    <select name="record_syntax" required class="input-field w-full">
                        <option value="USMARC">USMARC</option>
                        <option value="UNIMARC">UNIMARC</option>
                        <option value="MARC21">MARC21</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">{{ __('Username') }}</label>
                    <input type="text" name="username" class="input-field w-full">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">{{ __('Password') }}</label>
                    <input type="password" name="password" class="input-field w-full">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">{{ __('Charset') }} *</label>
                    <input type="text" name="charset" value="UTF-8" required class="input-field w-full">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">{{ __('Timeout') }} (s) *</label>
                    <input type="number" name="timeout" value="30" required min="5" max="120" class="input-field w-full">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">{{ __('Max_Records') }} *</label>
                    <input type="number" name="max_records" value="100" required min="10" max="500" class="input-field w-full">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">{{ __('Order') }}</label>
                    <input type="number" name="order" value="0" min="0" class="input-field w-full">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium mb-1">{{ __('Description') }}</label>
                    <textarea name="description" class="input-field w-full" rows="2"></textarea>
                </div>
                <div class="md:col-span-2 flex gap-6">
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="is_active" value="1" checked class="rounded">
                        <span class="text-sm">{{ __('Active') }}</span>
                    </label>
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="use_ssl" value="1" class="rounded">
                        <span class="text-sm">{{ __('Use_SSL') }}</span>
                    </label>
                </div>
            </div>
            <div class="flex justify-end gap-2 mt-6">
                <button type="button" onclick="closeModal('addServerModal')" class="btn-secondary">{{ __('Cancel') }}</button>
                <button type="submit" class="btn-primary">{{ __('Create') }}</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Server Modal -->
<div id="editServerModal" class="modal hidden">
    <div class="modal-backdrop" onclick="closeModal('editServerModal')"></div>
    <div class="modal-content max-w-2xl">
        <h3 class="text-lg font-bold mb-4">{{ __('Edit_Z3950_Server') }}</h3>
        <form id="editServerForm" method="POST">
            @csrf @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium mb-1">{{ __('Server_Name') }} *</label>
                    <input type="text" name="name" id="editName" required class="input-field w-full">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">{{ __('Host') }} *</label>
                    <input type="text" name="host" id="editHost" required class="input-field w-full">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">{{ __('Port') }} *</label>
                    <input type="number" name="port" id="editPort" required min="1" max="65535" class="input-field w-full">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">{{ __('Database_Name') }} *</label>
                    <input type="text" name="database_name" id="editDbName" required class="input-field w-full">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">{{ __('Record_Syntax') }} *</label>
                    <select name="record_syntax" id="editSyntax" required class="input-field w-full">
                        <option value="USMARC">USMARC</option>
                        <option value="UNIMARC">UNIMARC</option>
                        <option value="MARC21">MARC21</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">{{ __('Username') }}</label>
                    <input type="text" name="username" id="editUsername" class="input-field w-full">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">{{ __('Password') }}</label>
                    <input type="password" name="password" class="input-field w-full" placeholder="{{ __('Leave_empty_to_keep_current') }}">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">{{ __('Charset') }} *</label>
                    <input type="text" name="charset" id="editCharset" required class="input-field w-full">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">{{ __('Timeout') }} (s) *</label>
                    <input type="number" name="timeout" id="editTimeout" required min="5" max="120" class="input-field w-full">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">{{ __('Max_Records') }} *</label>
                    <input type="number" name="max_records" id="editMaxRecords" required min="10" max="500" class="input-field w-full">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">{{ __('Order') }}</label>
                    <input type="number" name="order" id="editOrder" min="0" class="input-field w-full">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium mb-1">{{ __('Description') }}</label>
                    <textarea name="description" id="editDescription" class="input-field w-full" rows="2"></textarea>
                </div>
                <div class="md:col-span-2 flex gap-6">
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="is_active" id="editActive" value="1" class="rounded">
                        <span class="text-sm">{{ __('Active') }}</span>
                    </label>
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="use_ssl" id="editSsl" value="1" class="rounded">
                        <span class="text-sm">{{ __('Use_SSL') }}</span>
                    </label>
                </div>
            </div>
            <div class="flex justify-end gap-2 mt-6">
                <button type="button" onclick="closeModal('editServerModal')" class="btn-secondary">{{ __('Cancel') }}</button>
                <button type="submit" class="btn-primary">{{ __('Update') }}</button>
            </div>
        </form>
    </div>
</div>

<style>
    .modal { position: fixed; inset: 0; z-index: 50; display: flex; align-items: center; justify-content: center; }
    .modal.hidden { display: none; }
    .modal-backdrop { position: absolute; inset: 0; background: rgba(0,0,0,0.8); }
    .modal-content { position: relative; background: #1f2937; border: 1px solid #374151; border-radius: 0.5rem; padding: 1.5rem; max-width: 28rem; width: 100%; max-height: 90vh; overflow-y: auto; }
    .modal-content.max-w-2xl { max-width: 42rem; }
    .input-field { background: #111827; border: 1px solid #374151; border-radius: 0.375rem; padding: 0.5rem 0.75rem; color: #fff; font-size: 0.875rem; }
    .input-field:focus { outline: none; border-color: #3b82f6; }
    .btn-primary { background: #3b82f6; color: #fff; padding: 0.5rem 1rem; border-radius: 0.375rem; font-size: 0.875rem; font-weight: 500; }
    .btn-primary:hover { background: #2563eb; }
    .btn-secondary { background: #374151; color: #fff; padding: 0.5rem 1rem; border-radius: 0.375rem; font-size: 0.875rem; font-weight: 500; }
    .btn-secondary:hover { background: #4b5563; }
    .card-admin { background: #1f2937; border: 1px solid #374151; }
</style>

<script>
    function openModal(id) {
        document.getElementById(id).classList.remove('hidden');
    }
    
    function closeModal(id) {
        document.getElementById(id).classList.add('hidden');
    }

    function editServer(server) {
        document.getElementById('editServerForm').action = '/topsecret/z3950/' + server.id;
        document.getElementById('editName').value = server.name;
        document.getElementById('editHost').value = server.host;
        document.getElementById('editPort').value = server.port;
        document.getElementById('editDbName').value = server.database_name;
        document.getElementById('editSyntax').value = server.record_syntax;
        document.getElementById('editUsername').value = server.username || '';
        document.getElementById('editCharset').value = server.charset;
        document.getElementById('editTimeout').value = server.timeout;
        document.getElementById('editMaxRecords').value = server.max_records;
        document.getElementById('editOrder').value = server.order;
        document.getElementById('editDescription').value = server.description || '';
        document.getElementById('editActive').checked = server.is_active;
        document.getElementById('editSsl').checked = server.use_ssl;
        openModal('editServerModal');
    }

    function testConnection(serverId) {
        const statusEl = document.getElementById('status-' + serverId);
        statusEl.textContent = '{{ __("Testing") }}...';
        statusEl.className = 'px-2 py-1 rounded text-xs font-bold bg-blue-900/50 text-blue-400';

        fetch('/topsecret/z3950/' + serverId + '/test', {
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
                statusEl.className = 'px-2 py-1 rounded text-xs font-bold bg-green-900/50 text-green-400';
            } else {
                statusEl.textContent = '{{ __("Failed") }}';
                statusEl.className = 'px-2 py-1 rounded text-xs font-bold bg-red-900/50 text-red-400';
                alert(data.message);
            }
        })
        .catch(err => {
            statusEl.textContent = '{{ __("Failed") }}';
            statusEl.className = 'px-2 py-1 rounded text-xs font-bold bg-red-900/50 text-red-400';
        });
    }
</script>
@endsection
