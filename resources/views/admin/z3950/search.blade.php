@extends('layouts.admin')

@section('content')
<div class="space-y-8 animate-in fade-in duration-700">
    @if(!$hasYaz)
    <div class="bg-amber-50 dark:bg-amber-500/10 border border-amber-100 dark:border-amber-500/20 text-amber-600 dark:text-amber-400 p-4 rounded-xl text-sm">
        <div class="flex items-center space-x-3 mb-1">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            <strong class="font-bold">{{ __('YAZ_Extension_Not_Installed') }}</strong>
        </div>
        <p class="font-medium opacity-90">{{ __('YAZ_extension_required_for_full_Z3950_functionality') }}</p>
    </div>
    @endif

    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div class="flex items-center space-x-4">
            <div class="w-12 h-12 rounded-xl bg-indigo-600 flex items-center justify-center shadow-lg shadow-indigo-200 dark:shadow-none">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
            <div>
                <h1 class="text-3xl font-extrabold text-slate-900 dark:text-slate-100 tracking-tight">{{ __('Z3950_Search') }}</h1>
                <p class="text-slate-500 dark:text-slate-400 font-medium mt-1">{{ __('Search_external_catalogs_for_bibliographic_records') }}</p>
            </div>
        </div>
        <a href="{{ route('admin.z3950.index') }}" class="inline-flex items-center px-6 py-3 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 font-bold rounded-xl hover:bg-slate-200 dark:hover:bg-slate-700 transition-all text-sm">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
            {{ __('Manage_Servers') }}
        </a>
    </div>

    <!-- Quick Search Widget -->
    <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] shadow-xl shadow-slate-200/50 dark:shadow-none border border-slate-100 dark:border-slate-800 overflow-hidden">
        <form id="searchForm" class="p-8 md:p-10">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-end">
                <div class="md:col-span-3 space-y-2">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] pl-1">{{ __('Search Node') }}</label>
                    <select name="server_id" id="serverId" required class="w-full px-5 py-4 bg-slate-50 dark:bg-slate-950 border-2 border-transparent rounded-2xl focus:bg-white dark:focus:bg-slate-800 focus:border-indigo-500 font-bold transition-all text-sm appearance-none cursor-pointer">
                        @foreach($servers as $server)
                        <option value="{{ $server->id }}">{{ $server->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="md:col-span-3 space-y-2">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] pl-1">{{ __('Field Type') }}</label>
                    <select name="search_type" id="searchType" required class="w-full px-5 py-4 bg-slate-50 dark:bg-slate-950 border-2 border-transparent rounded-2xl focus:bg-white dark:focus:bg-slate-800 focus:border-indigo-500 font-bold transition-all text-sm appearance-none cursor-pointer">
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
                <div class="md:col-span-6 space-y-2">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] pl-1">{{ __('Search Query') }}</label>
                    <div class="relative group">
                        <input type="text" name="query" id="searchQuery" required 
                            class="w-full pl-14 pr-5 py-4 bg-slate-50 dark:bg-slate-950 border-2 border-transparent rounded-2xl focus:bg-white dark:focus:bg-slate-800 focus:border-indigo-500 font-bold transition-all text-sm" 
                            placeholder="{{ __('Identify resources by titles, authors, or identifiers...') }}" autofocus>
                        <div class="absolute left-5 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-indigo-600 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-8 flex justify-end">
                <button type="submit" id="searchBtn" class="px-12 py-4 bg-indigo-600 text-white font-black rounded-2xl shadow-xl shadow-indigo-200 dark:shadow-none hover:bg-indigo-700 transition-all uppercase tracking-[0.2em] text-[10px] flex items-center">
                    <span id="btnText">{{ __('Execute Universal Search') }}</span>
                </button>
            </div>
        </form>
    </div>

    <!-- Results Section -->
    <div id="resultsContainer" class="hidden space-y-6">
        <div class="flex items-center justify-between px-2">
            <div>
                <h2 class="text-xl font-black text-slate-900 dark:text-white uppercase tracking-tight">{{ __('Identified Records') }}</h2>
                <p id="resultCount" class="text-xs font-bold text-slate-400 mt-1 uppercase tracking-widest"></p>
            </div>
        </div>
        
        <div id="resultsList" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Results will be loaded here -->
        </div>
    </div>

    <!-- Loading State -->
    <div id="loadingIndicator" class="hidden flex flex-col items-center justify-center py-20 space-y-6">
        <div class="relative w-16 h-16">
            <div class="absolute inset-0 border-4 border-slate-100 dark:border-slate-800 rounded-full"></div>
            <div class="absolute inset-0 border-4 border-indigo-600 rounded-full border-t-transparent animate-spin"></div>
        </div>
        <div class="text-center">
            <p class="text-slate-900 dark:text-white font-black uppercase tracking-widest text-sm">{{ __('Synchronizing with Node') }}</p>
            <p class="text-slate-400 text-xs font-bold mt-1 uppercase tracking-widest">{{ __('Retrieving bibliographic dataset') }}...</p>
        </div>
    </div>

    <!-- Empty State -->
    <div id="noResults" class="hidden py-20 text-center bg-slate-50 dark:bg-slate-900/50 rounded-[3rem] border-2 border-dashed border-slate-200 dark:border-slate-800">
        <div class="max-w-xs mx-auto">
            <div class="w-16 h-16 bg-white dark:bg-slate-800 rounded-2xl shadow-sm mx-auto flex items-center justify-center text-slate-300 mb-6">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
            </div>
            <h3 class="text-slate-900 dark:text-white font-black uppercase tracking-widest text-sm mb-2">{{ __('No Matches Identified') }}</h3>
            <p class="text-slate-500 dark:text-slate-400 text-xs font-bold">{{ __('Refine your query parameters and attempt synchronization again.') }}</p>
        </div>
    </div>

    <!-- Error State -->
    <div id="errorContainer" class="hidden bg-rose-50 dark:bg-rose-500/10 border border-rose-100 dark:border-rose-500/20 text-rose-600 dark:text-rose-400 p-6 rounded-[2rem] flex items-center space-x-4">
        <div class="w-12 h-12 rounded-2xl bg-rose-100 dark:bg-rose-500/20 flex items-center justify-center shrink-0">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
        <div>
            <h4 class="font-black text-sm uppercase tracking-widest">{{ __('Transmission Error') }}</h4>
            <p id="errorMessage" class="text-xs font-bold mt-1 opacity-80"></p>
        </div>
    </div>
</div>

<!-- Modal Manager -->
<div x-data="{ 
    showDetail: false, 
    record: {},
    init() {
        window.addEventListener('open-modal', (e) => {
            if (e.detail === 'record-detail') this.showDetail = true;
        });
        window.addEventListener('set-record', (e) => {
            this.record = e.detail;
        });
    }
}">
    <!-- Detail Modal -->
    <template x-if="showDetail">
        <div class="fixed inset-0 z-[100] overflow-y-auto px-4 py-12 flex items-center justify-center">
            <div class="fixed inset-0 transition-opacity bg-slate-900/60 backdrop-blur-sm" @click="showDetail = false"></div>
            
            <div class="bg-white dark:bg-slate-900 rounded-[3rem] shadow-2xl border border-slate-100 dark:border-slate-800 overflow-hidden w-full max-w-4xl relative animate-in zoom-in-95 duration-300">
                <div class="p-10">
                    <div class="flex justify-between items-start mb-10">
                        <div class="flex-1 pr-12">
                            <div class="flex items-center space-x-3 mb-3">
                                <span class="px-3 py-1 bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 rounded-lg text-[10px] font-black uppercase tracking-widest border border-indigo-100 dark:border-indigo-500/20">MARC STRUCTURE</span>
                                <span class="text-slate-400 text-[10px] font-black uppercase tracking-widest" x-text="record.record_syntax"></span>
                            </div>
                            <h3 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight leading-tight" x-text="record.title"></h3>
                            <p class="text-indigo-600 dark:text-indigo-400 font-bold mt-2" x-text="record.author"></p>
                        </div>
                        <button type="button" @click="showDetail = false" class="p-3 bg-slate-50 dark:bg-slate-800 text-slate-400 hover:text-rose-500 transition-colors rounded-2xl shadow-sm">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-12 gap-10">
                        <!-- Technical Summary -->
                        <div class="md:col-span-12 space-y-6">
                            <div class="bg-slate-50 dark:bg-slate-950 p-8 rounded-[2rem] border-2 border-transparent focus-within:border-indigo-500 transition-all">
                                <div class="max-h-[350px] overflow-y-auto pr-4 scrollbar-thin scrollbar-thumb-slate-200 dark:scrollbar-thumb-slate-800">
                                    <table class="w-full text-left">
                                        <thead>
                                            <tr class="border-b-2 border-slate-200 dark:border-slate-800">
                                                <th class="pb-4 text-[10px] font-black text-slate-400 uppercase tracking-widest w-24">Tag</th>
                                                <th class="pb-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Metadata Fragment</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                                            <tr class="group">
                                                <td class="py-4 font-mono text-[11px] text-indigo-600 dark:text-indigo-400 font-bold tracking-widest">LDR</td>
                                                <td class="py-4 font-mono text-[11px] text-slate-500 dark:text-slate-400 break-all leading-relaxed" x-text="record.leader"></td>
                                            </tr>
                                            <template x-for="(values, tag) in record.fields" :key="tag">
                                                <template x-for="(value, index) in values" :key="tag + '-' + index">
                                                    <tr class="group hover:bg-white dark:hover:bg-slate-900 transition-colors rounded-lg">
                                                        <td class="py-4 font-mono text-[11px] text-indigo-600 dark:text-indigo-400 font-bold tracking-widest" x-text="tag"></td>
                                                        <td class="py-4 font-mono text-[11px] text-slate-600 dark:text-slate-300 break-all leading-relaxed" x-text="value"></td>
                                                    </tr>
                                                </template>
                                            </template>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-10 pt-10 border-t border-slate-100 dark:border-slate-800 flex justify-end space-x-4">
                        <button type="button" @click="showDetail = false" class="px-10 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest hover:text-rose-500 transition-colors">
                            {{ __('Abort View') }}
                        </button>
                        <button type="button" onclick="importCurrentRecord()" class="px-12 py-5 bg-emerald-600 text-white font-black rounded-3xl shadow-xl shadow-emerald-200 dark:shadow-none hover:bg-emerald-700 transition-all uppercase tracking-widest text-[10px] flex items-center">
                            <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                            {{ __('Commit to Repository') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </template>
</div>

@push('scripts')
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

        document.getElementById('resultsContainer').classList.add('hidden');
        document.getElementById('noResults').classList.add('hidden');
        document.getElementById('errorContainer').classList.add('hidden');
        document.getElementById('loadingIndicator').classList.remove('hidden');
        document.getElementById('searchBtn').disabled = true;
        document.getElementById('btnText').textContent = '{{ __("SYNCHRONIZING") }}...';

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
            document.getElementById('btnText').textContent = '{{ __("Execute Universal Search") }}';

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
            document.getElementById('btnText').textContent = '{{ __("Execute Universal Search") }}';
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
            `Identified ${data.total} records` + 
            (data.records.length < data.total ? ` (Showing top ${data.records.length})` : '');

        data.records.forEach((record, index) => {
            const card = document.createElement('div');
            card.className = 'bg-white dark:bg-slate-900 rounded-[2rem] border border-slate-100 dark:border-slate-800 p-8 shadow-sm hover:shadow-xl hover:border-indigo-500/50 hover:-translate-y-1 transition-all duration-300 group relative flex flex-col h-full';
            
            const titleHtml = escapeHtml(record.title || '{{ __("No_title") }}');
            const authorHtml = record.author ? escapeHtml(record.author) : '{{ __("Unknown Author") }}';
            const metaHtml = [
                record.publisher ? escapeHtml(record.publisher) : null,
                record.year ? escapeHtml(record.year) : null
            ].filter(v => v).join(' — ');

            card.innerHTML = `
                <div class="flex-1">
                    <div class="flex items-center space-x-2 mb-4">
                        <span class="px-2 py-0.5 bg-slate-100 dark:bg-slate-800 text-slate-400 group-hover:text-indigo-400 transition-colors rounded text-[9px] font-black uppercase tracking-widest">RECORD NODE</span>
                    </div>
                    <h3 class="font-black text-slate-900 dark:text-white line-clamp-2 leading-tight group-hover:text-indigo-600 transition-colors mb-3">${titleHtml}</h3>
                    <p class="text-[10px] font-black text-indigo-500 uppercase tracking-widest mb-4">${authorHtml}</p>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest leading-loose line-clamp-2">${metaHtml}</p>
                    
                    ${record.isbn ? `
                        <div class="mt-6 flex items-center space-x-2">
                            <span class="text-[8px] font-black text-slate-300 uppercase tracking-widest">Global ID</span>
                            <code class="text-[10px] text-slate-500 dark:text-slate-400 bg-slate-50 dark:bg-slate-950 px-2 py-1 rounded-lg border border-slate-100 dark:border-slate-800 font-mono">${escapeHtml(record.isbn)}</code>
                        </div>
                    ` : ''}
                </div>
                
                <div class="mt-8 pt-8 border-t border-slate-50 dark:border-slate-800 flex items-center justify-between">
                    <button onclick="viewRecord(${index})" class="text-[10px] font-black text-slate-400 uppercase tracking-widest hover:text-indigo-600 transition-colors">
                        Details
                    </button>
                    <button onclick="importRecord(${index})" class="text-[10px] font-black text-emerald-600 uppercase tracking-widest hover:text-emerald-500 transition-colors flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        Import
                    </button>
                </div>
            `;
            container.appendChild(card);
        });

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

        // Dispatch events to Alpha.js modal manager
        window.dispatchEvent(new CustomEvent('set-record', { detail: record }));
        window.dispatchEvent(new CustomEvent('open-modal', { detail: 'record-detail' }));
    }

    function importRecord(index) {
        const record = window.searchResults[index];
        currentRecord = record;
        importCurrentRecord();
    }

    function importCurrentRecord() {
        if (!currentRecord || !currentRecord.raw) {
            window.dispatchEvent(new CustomEvent('toast', {
                detail: { message: '{{ __("No record selected") }}', type: 'error' }
            }));
            return;
        }

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
                window.dispatchEvent(new CustomEvent('toast', {
                    detail: { message: '{{ __("Record parsed successfully. Transferring to catalog...") }}', type: 'success' }
                }));
                // Real implementation would redirect or open catalog form
            } else {
                window.dispatchEvent(new CustomEvent('toast', {
                    detail: { message: data.message, type: 'error' }
                }));
            }
        })
        .catch(err => {
            window.dispatchEvent(new CustomEvent('toast', {
                detail: { message: '{{ __("Import failed") }}: ' + err.message, type: 'error' }
            }));
        });
    }
</script>
@endpush
@endsection
