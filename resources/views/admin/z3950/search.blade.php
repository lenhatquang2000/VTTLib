@extends('layouts.admin')

@section('content')
<div class="space-y-8">
    @if(!$hasYaz)
    <div class="bg-yellow-900/20 border border-yellow-500 text-yellow-400 p-4 text-sm rounded">
        <strong>⚠️ {{ __('YAZ_Extension_Not_Installed') }}</strong><br>
        {{ __('YAZ_extension_required_for_full_Z3950_functionality') }}
    </div>
    @endif

    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold">{{ __('Z3950_Search') }}</h1>
            <p class="text-sm text-gray-400 mt-1">{{ __('Search_external_catalogs_for_bibliographic_records') }}</p>
        </div>
        <a href="{{ route('admin.z3950.index') }}" class="btn-secondary">
            {{ __('Manage_Servers') }}
        </a>
    </div>

    <!-- Search Form -->
    <div class="card-admin rounded-lg p-6">
        <form id="searchForm" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1">{{ __('Server') }} *</label>
                    <select name="server_id" id="serverId" required class="input-field w-full">
                        @foreach($servers as $server)
                        <option value="{{ $server->id }}">{{ $server->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">{{ __('Search_Type') }} *</label>
                    <select name="search_type" id="searchType" required class="input-field w-full">
                        <option value="keyword">{{ __('Keyword') }}</option>
                        <option value="title">{{ __('Title') }}</option>
                        <option value="author">{{ __('Author') }}</option>
                        <option value="isbn">ISBN</option>
                        <option value="issn">ISSN</option>
                        <option value="subject">{{ __('Subject') }}</option>
                        <option value="publisher">{{ __('Publisher') }}</option>
                        <option value="year">{{ __('Year') }}</option>
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium mb-1">{{ __('Search_Query') }} *</label>
                    <div class="flex gap-2">
                        <input type="text" name="query" id="searchQuery" required class="input-field flex-1" 
                            placeholder="{{ __('Enter_search_term') }}..." autofocus>
                        <button type="submit" class="btn-primary px-6" id="searchBtn">
                            {{ __('Search') }}
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Results -->
    <div id="resultsContainer" class="hidden">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-bold">{{ __('Search_Results') }}</h2>
            <span id="resultCount" class="text-sm text-gray-400"></span>
        </div>
        
        <div id="resultsList" class="space-y-4">
            <!-- Results will be loaded here -->
        </div>
    </div>

    <!-- Loading -->
    <div id="loadingIndicator" class="hidden text-center py-12">
        <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-500"></div>
        <p class="mt-4 text-gray-400">{{ __('Searching') }}...</p>
    </div>

    <!-- No Results -->
    <div id="noResults" class="hidden card-admin rounded-lg p-8 text-center">
        <p class="text-gray-500">{{ __('No_records_found') }}</p>
    </div>

    <!-- Error -->
    <div id="errorContainer" class="hidden bg-red-900/20 border border-red-500 text-red-400 p-4 rounded">
        <span id="errorMessage"></span>
    </div>
</div>

<!-- Record Detail Modal -->
<div id="recordDetailModal" class="modal hidden">
    <div class="modal-backdrop" onclick="closeModal('recordDetailModal')"></div>
    <div class="modal-content max-w-4xl">
        <div class="flex justify-between items-start mb-4">
            <h3 class="text-lg font-bold">{{ __('Record_Details') }}</h3>
            <button onclick="closeModal('recordDetailModal')" class="text-gray-400 hover:text-white">&times;</button>
        </div>
        <div id="recordDetailContent" class="max-h-96 overflow-y-auto">
            <!-- Detail will be loaded here -->
        </div>
        <div class="flex justify-end gap-2 mt-6 pt-4 border-t border-gray-700">
            <button type="button" onclick="closeModal('recordDetailModal')" class="btn-secondary">{{ __('Close') }}</button>
            <button type="button" onclick="importCurrentRecord()" class="btn-primary bg-green-600 hover:bg-green-700">
                {{ __('Import_to_Catalog') }}
            </button>
        </div>
    </div>
</div>

<style>
    .modal { position: fixed; inset: 0; z-index: 50; display: flex; align-items: center; justify-content: center; }
    .modal.hidden { display: none; }
    .modal-backdrop { position: absolute; inset: 0; background: rgba(0,0,0,0.8); }
    .modal-content { position: relative; background: #1f2937; border: 1px solid #374151; border-radius: 0.5rem; padding: 1.5rem; max-width: 28rem; width: 100%; max-height: 90vh; overflow-y: auto; }
    .modal-content.max-w-4xl { max-width: 56rem; }
    .input-field { background: #111827; border: 1px solid #374151; border-radius: 0.375rem; padding: 0.5rem 0.75rem; color: #fff; font-size: 0.875rem; }
    .input-field:focus { outline: none; border-color: #3b82f6; }
    .btn-primary { background: #3b82f6; color: #fff; padding: 0.5rem 1rem; border-radius: 0.375rem; font-size: 0.875rem; font-weight: 500; }
    .btn-primary:hover { background: #2563eb; }
    .btn-secondary { background: #374151; color: #fff; padding: 0.5rem 1rem; border-radius: 0.375rem; font-size: 0.875rem; font-weight: 500; }
    .btn-secondary:hover { background: #4b5563; }
    .card-admin { background: #1f2937; border: 1px solid #374151; }
    .result-card { background: #1f2937; border: 1px solid #374151; border-radius: 0.5rem; padding: 1rem; }
    .result-card:hover { border-color: #3b82f6; }
</style>

<script>
    let currentRecord = null;

    document.getElementById('searchForm').addEventListener('submit', function(e) {
        e.preventDefault();
        performSearch();
    });

    function performSearch() {
        const serverId = document.getElementById('serverId').value;
        const searchType = document.getElementById('searchType').value;
        const query = document.getElementById('searchQuery').value;

        // Hide all containers
        document.getElementById('resultsContainer').classList.add('hidden');
        document.getElementById('noResults').classList.add('hidden');
        document.getElementById('errorContainer').classList.add('hidden');
        document.getElementById('loadingIndicator').classList.remove('hidden');
        document.getElementById('searchBtn').disabled = true;

        fetch('/topsecret/z3950/search', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            },
            body: JSON.stringify({
                server_id: serverId,
                query: query,
                search_type: searchType,
                max_records: 20
            })
        })
        .then(r => r.json())
        .then(data => {
            document.getElementById('loadingIndicator').classList.add('hidden');
            document.getElementById('searchBtn').disabled = false;

            if (data.yaz_required) {
                showError(data.message);
                return;
            }

            if (!data.success) {
                showError(data.message);
                return;
            }

            if (data.records.length === 0) {
                document.getElementById('noResults').classList.remove('hidden');
                return;
            }

            displayResults(data);
        })
        .catch(err => {
            document.getElementById('loadingIndicator').classList.add('hidden');
            document.getElementById('searchBtn').disabled = false;
            showError('{{ __("Search_failed") }}: ' + err.message);
        });
    }

    function showError(message) {
        document.getElementById('errorMessage').textContent = message;
        document.getElementById('errorContainer').classList.remove('hidden');
    }

    function displayResults(data) {
        const container = document.getElementById('resultsList');
        container.innerHTML = '';

        document.getElementById('resultCount').textContent = 
            `{{ __("Found") }} ${data.total} {{ __("records") }}` + 
            (data.records.length < data.total ? ` ({{ __("showing") }} ${data.records.length})` : '');

        data.records.forEach((record, index) => {
            const card = document.createElement('div');
            card.className = 'result-card';
            card.innerHTML = `
                <div class="flex justify-between items-start">
                    <div class="flex-1">
                        <h3 class="font-bold text-blue-400">${escapeHtml(record.title || '{{ __("No_title") }}')}</h3>
                        <p class="text-sm text-gray-400 mt-1">
                            ${record.author ? '<span class="text-gray-300">' + escapeHtml(record.author) + '</span>' : ''}
                            ${record.publisher ? ' — ' + escapeHtml(record.publisher) : ''}
                            ${record.year ? ' (' + escapeHtml(record.year) + ')' : ''}
                        </p>
                        <div class="flex gap-4 mt-2 text-xs text-gray-500">
                            ${record.isbn ? '<span>ISBN: <code>' + escapeHtml(record.isbn) + '</code></span>' : ''}
                            ${record.subjects && record.subjects.length ? '<span>{{ __("Subjects") }}: ' + record.subjects.slice(0,3).map(s => escapeHtml(s)).join(', ') + '</span>' : ''}
                        </div>
                    </div>
                    <div class="flex gap-2 ml-4">
                        <button onclick="viewRecord(${index})" class="text-blue-400 hover:text-blue-300 text-xs">
                            {{ __("View") }}
                        </button>
                        <button onclick="importRecord(${index})" class="text-green-400 hover:text-green-300 text-xs">
                            {{ __("Import") }}
                        </button>
                    </div>
                </div>
            `;
            container.appendChild(card);
        });

        // Store records for later use
        window.searchResults = data.records;
        document.getElementById('resultsContainer').classList.remove('hidden');
    }

    function escapeHtml(text) {
        if (!text) return '';
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    function viewRecord(index) {
        const record = window.searchResults[index];
        currentRecord = record;

        let html = '<table class="w-full text-sm">';
        html += `<tr class="border-b border-gray-700"><td class="py-2 text-gray-400 w-32">Leader</td><td class="py-2 font-mono text-xs">${escapeHtml(record.leader)}</td></tr>`;
        
        if (record.fields) {
            for (const [tag, values] of Object.entries(record.fields)) {
                values.forEach(value => {
                    html += `<tr class="border-b border-gray-700"><td class="py-2 text-gray-400">${tag}</td><td class="py-2 font-mono text-xs">${escapeHtml(value)}</td></tr>`;
                });
            }
        }
        html += '</table>';

        document.getElementById('recordDetailContent').innerHTML = html;
        openModal('recordDetailModal');
    }

    function importRecord(index) {
        const record = window.searchResults[index];
        currentRecord = record;
        importCurrentRecord();
    }

    function importCurrentRecord() {
        if (!currentRecord || !currentRecord.raw) {
            alert('{{ __("No_record_selected") }}');
            return;
        }

        // For now, show the raw data - in full implementation, this would redirect to cataloging form
        fetch('/topsecret/z3950/import', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            },
            body: JSON.stringify({ raw: currentRecord.raw })
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                alert('{{ __("Record_parsed_successfully") }}. {{ __("Ready_for_cataloging") }}.');
                // In full implementation: redirect to cataloging form with pre-filled data
                // window.location.href = '/topsecret/cataloging/create?import=' + encodeURIComponent(JSON.stringify(data.data));
                closeModal('recordDetailModal');
            } else {
                alert(data.message);
            }
        })
        .catch(err => {
            alert('{{ __("Import_failed") }}: ' + err.message);
        });
    }

    function openModal(id) {
        document.getElementById(id).classList.remove('hidden');
    }
    
    function closeModal(id) {
        document.getElementById(id).classList.add('hidden');
    }
</script>
@endsection
