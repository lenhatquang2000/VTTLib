@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    @if(session('success'))
        <div class="bg-emerald-50 border-l-4 border-emerald-400 p-4 shadow-sm animate-fade-in-down">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-emerald-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-emerald-800">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">{{ __('MARC21_Framework_Manager') }}</h2>
            <p class="text-sm text-gray-500 mt-1">Configure tag visibility, mandatory constraints, and subfield definitions.</p>
        </div>
        
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
            <!-- Search Bar -->
            <form action="{{ route('admin.marc.index') }}" method="GET" class="relative group">
                <input type="text" name="search" value="{{ $search }}" placeholder="{{ __('Search') }} Tag/Label..." 
                    class="pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all w-full sm:w-64">
                <svg class="w-4 h-4 absolute left-3 top-3.5 text-gray-400 group-focus-within:text-indigo-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                @if($search)
                    <a href="{{ route('admin.marc.index') }}" class="absolute right-3 top-3 text-gray-400 hover:text-rose-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </a>
                @endif
            </form>

            <button onclick="openModal('addTagModal')" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-lg text-sm font-semibold transition flex items-center justify-center shadow-lg shadow-indigo-200">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                {{ __('Register_New_Tag') }}
            </button>
        </div>
    </div>

    <!-- MARC Tags List -->
    <div class="grid grid-cols-1 gap-4">
        @forelse($tags as $tag)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden group hover:border-indigo-200 transition" 
                 x-data="{ isOpen: {{ $search ? 'true' : 'false' }} }">
                
                <!-- HEADER (Clickable to Toggle) -->
                <div class="p-4 flex justify-between items-center cursor-pointer hover:bg-gray-50/50 transition" @click="isOpen = !isOpen">
                    <div class="flex items-center space-x-4">
                        <span class="bg-indigo-600 text-white px-3 py-1 rounded-md font-mono font-bold text-lg shadow-sm">{{ $tag->tag }}</span>
                        <div>
                            <h3 class="font-bold text-gray-800 uppercase tracking-tight">{{ $tag->label }}</h3>
                            <div class="flex space-x-4 mt-1">
                                <span class="text-[10px] uppercase font-bold {{ $tag->is_visible ? 'text-emerald-600' : 'text-gray-400' }}">
                                    {{ $tag->is_visible ? __('VISIBLE') : __('HIDDEN') }}
                                </span>
                                <span class="text-[10px] text-indigo-400 font-bold uppercase tracking-widest">{{ $tag->subfields->count() }} Subfields</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex items-center space-x-4" @click.stop>
                        <button onclick="openEditTagModal({{ json_encode($tag) }})" class="text-gray-400 hover:text-indigo-600 p-1 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        </button>
                        <button onclick="openSubfieldModal('{{ $tag->tag }}', '{{ $tag->label }}')" class="text-indigo-600 hover:text-indigo-800 text-xs font-bold uppercase underline decoration-dotted">{{ __('Inject_Subfield') }}</button>
                        
                        <form action="{{ route('admin.marc.tag.destroy', $tag->id) }}" method="POST" onsubmit="return confirm('{{ __('CRITICAL: Remove this Tag definition?') }}')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-rose-400 hover:text-rose-600 p-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </form>

                        <div class="ml-2 border-l pl-4 border-gray-100 italic transition-transform duration-200" :class="isOpen ? 'rotate-180' : ''">
                             <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                </div>
                
                <!-- CONTENT (Collapsible) -->
                <div x-show="isOpen" x-cloak x-collapse class="border-t border-gray-50">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-gray-50/30 text-[10px] uppercase font-bold text-gray-500 tracking-wider font-mono">
                            <tr>
                                <th class="px-6 py-3">Code</th>
                                <th class="px-6 py-3">Label</th>
                                <th class="px-6 py-3">Constraints</th>
                                <th class="px-6 py-3 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($tag->subfields as $sub)
                                <tr class="hover:bg-indigo-50/30 transition">
                                    <td class="px-6 py-3 font-mono font-bold text-indigo-600">${{ $sub->code }}</td>
                                    <td class="px-6 py-3 text-gray-700 leading-relaxed font-medium">{{ $sub->label }}</td>
                                    <td class="px-6 py-3 flex flex-wrap gap-2">
                                        @if($sub->is_mandatory)
                                            <span class="bg-rose-100 text-rose-700 px-2 py-0.5 rounded text-[10px] font-bold uppercase ring-1 ring-rose-200">{{ __('Mandatory_Field') }}</span>
                                        @endif
                                        @if($sub->is_repeatable)
                                            <span class="bg-blue-100 text-blue-700 px-2 py-0.5 rounded text-[10px] font-bold uppercase ring-1 ring-blue-200">{{ __('Repeatable_Data') }}</span>
                                        @endif
                                        @if(!$sub->is_visible)
                                            <span class="bg-gray-100 text-gray-500 px-2 py-0.5 rounded text-[10px] font-bold uppercase ring-1 ring-gray-200">{{ __('HIDDEN') }}</span>
                                        @else
                                            <span class="bg-emerald-100 text-emerald-700 px-2 py-0.5 rounded text-[10px] font-bold uppercase ring-1 ring-emerald-200">{{ __('VISIBLE') }}</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-3 text-right">
                                        <div class="flex justify-end space-x-1">
                                            <button onclick="openEditSubfieldModal({{ json_encode($sub) }})" class="text-indigo-400 hover:text-indigo-600 transition p-2 rounded-lg hover:bg-indigo-50">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                            </button>
                                            <form action="{{ route('admin.marc.subfield.destroy', $sub->id) }}" method="POST">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="text-gray-400 hover:text-rose-500 transition p-2 rounded-lg hover:bg-rose-50">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-6 text-center text-gray-400 italic text-xs">No subfields defined for this tag.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @empty
            <div class="bg-white p-12 rounded-xl border-2 border-dashed border-gray-100 text-center">
                <div class="bg-gray-50 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <h3 class="text-gray-900 font-bold uppercase tracking-widest text-sm">No_Tags_Matched</h3>
                <p class="text-gray-500 text-xs mt-1">Try adjusting your search criteria or register a new tag.</p>
                <button onclick="openModal('addTagModal')" class="mt-4 text-indigo-600 font-bold uppercase text-[10px] hover:underline">Register_New_Tag</button>
            </div>
        @endforelse
    </div>
</div>

<!-- MODALS (Sourced from existing structure, localized labels added) -->
<div id="addTagModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="min-h-screen px-4 text-center flex items-center justify-center">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="closeModal('addTagModal')"></div>
        <div class="inline-block bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
            <div class="bg-white p-8">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold text-gray-900 uppercase tracking-tighter">{{ __('Register_New_Tag') }}</h3>
                    <button onclick="closeModal('addTagModal')" class="text-gray-400 hover:text-gray-600 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                <form action="{{ route('admin.marc.tag.store') }}" method="POST" class="space-y-5">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Tag_ID (3 digits)</label>
                        <input type="text" name="tag" placeholder="e.g. 245" maxlength="3" required class="w-full border-gray-200 bg-gray-50 rounded-xl p-3 text-sm focus:ring-2 focus:ring-indigo-500 transition">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Display_Label</label>
                        <input type="text" name="label" placeholder="e.g. Title Statement" required class="w-full border-gray-200 bg-gray-50 rounded-xl p-3 text-sm focus:ring-2 focus:ring-indigo-500 transition">
                    </div>
                    <div class="flex items-center space-x-3 bg-indigo-50/50 p-3 rounded-xl">
                        <input type="checkbox" name="is_visible" value="1" id="tag_visible" checked class="w-4 h-4 rounded text-indigo-600 focus:ring-indigo-500">
                        <label for="tag_visible" class="text-sm font-semibold text-indigo-900">Make this tag visible in cataloging forms</label>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Context / Description (Optional)</label>
                        <textarea name="description" class="w-full border-gray-200 bg-gray-50 rounded-xl p-3 text-sm focus:ring-2 focus:ring-indigo-500 transition" rows="2"></textarea>
                    </div>
                    <div class="pt-4 flex gap-3">
                        <button type="button" onclick="closeModal('addTagModal')" class="flex-1 px-4 py-3 bg-gray-100 hover:bg-gray-200 text-gray-600 font-bold rounded-xl transition uppercase text-xs">Abort</button>
                        <button type="submit" class="flex-1 px-4 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl transition uppercase text-xs shadow-lg shadow-indigo-100">{{ __('Establish_Tag') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div id="editTagModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="min-h-screen px-4 text-center flex items-center justify-center">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="closeModal('editTagModal')"></div>
        <div class="inline-block bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
            <div class="bg-white p-8">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold text-gray-900 uppercase tracking-tighter">{{ __('Modify_MARC_Tag') }}</h3>
                    <button onclick="closeModal('editTagModal')" class="text-gray-400 hover:text-gray-600 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                <form id="editTagForm" method="POST" class="space-y-5">
                    @csrf @method('PUT')
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Tag_ID (Immutable)</label>
                        <input type="text" id="edit_tag_id" disabled class="w-full border-gray-100 bg-gray-100 rounded-xl p-3 text-sm text-gray-400 font-mono">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Display_Label</label>
                        <input type="text" name="label" id="edit_tag_label" required class="w-full border-gray-200 bg-gray-50 rounded-xl p-3 text-sm focus:ring-2 focus:ring-indigo-500 transition">
                    </div>
                    <div class="flex items-center space-x-3 bg-indigo-50/50 p-3 rounded-xl">
                        <input type="checkbox" name="is_visible" value="1" id="edit_tag_visible" class="w-4 h-4 rounded text-indigo-600 focus:ring-indigo-500">
                        <label for="edit_tag_visible" class="text-sm font-semibold text-indigo-900">Visible in cataloging forms</label>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Context / Description</label>
                        <textarea name="description" id="edit_tag_description" class="w-full border-gray-200 bg-gray-50 rounded-xl p-3 text-sm focus:ring-2 focus:ring-indigo-500 transition" rows="2"></textarea>
                    </div>
                    <div class="pt-4 flex gap-3">
                        <button type="submit" class="w-full px-4 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl transition uppercase text-xs shadow-lg shadow-indigo-100">{{ __('Update_Definition') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div id="addSubfieldModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="min-h-screen px-4 text-center flex items-center justify-center">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="closeModal('addSubfieldModal')"></div>
        <div class="inline-block bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md w-full">
            <div class="bg-white p-8">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h3 class="text-xl font-bold text-gray-900 uppercase tracking-tighter">{{ __('Inject_Subfield') }}</h3>
                        <p class="text-[10px] text-indigo-600 font-bold mt-1 uppercase" id="targetTagInfo"></p>
                    </div>
                    <button onclick="closeModal('addSubfieldModal')" class="text-gray-400 hover:text-gray-600 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                <form action="{{ route('admin.marc.subfield.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <input type="hidden" name="tag" id="hiddenTagField">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Subfield_Code (1 char)</label>
                        <input type="text" name="code" placeholder="e.g. a" maxlength="1" required class="w-full border-gray-200 bg-gray-50 rounded-xl p-3 text-sm focus:ring-2 focus:ring-indigo-500 transition font-mono font-bold">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Label</label>
                        <input type="text" name="label" placeholder="e.g. Principal title" required class="w-full border-gray-200 bg-gray-50 rounded-xl p-3 text-sm focus:ring-2 focus:ring-indigo-500 transition">
                    </div>
                    <div class="grid grid-cols-1 gap-3">
                        <div class="flex items-center space-x-3 bg-gray-50 p-3 rounded-xl border border-gray-100">
                            <input type="checkbox" name="is_visible" value="1" id="sub_visible" checked class="w-4 h-4 rounded text-indigo-600 focus:ring-indigo-500">
                            <label for="sub_visible" class="text-xs font-bold text-gray-600">{{ __('VISIBLE') }}</label>
                        </div>
                        <div class="flex items-center space-x-3 bg-gray-50 p-3 rounded-xl border border-gray-100">
                            <input type="checkbox" name="is_mandatory" value="1" id="sub_mandatory" class="w-4 h-4 rounded text-indigo-600 focus:ring-indigo-500">
                            <label for="sub_mandatory" class="text-xs font-bold text-gray-600 uppercase">{{ __('Mandatory_Field') }}</label>
                        </div>
                        <div class="flex items-center space-x-3 bg-gray-50 p-3 rounded-xl border border-gray-100">
                            <input type="checkbox" name="is_repeatable" value="1" id="sub_repeatable" class="w-4 h-4 rounded text-indigo-600 focus:ring-indigo-500">
                            <label for="sub_repeatable" class="text-xs font-bold text-gray-600 uppercase">{{ __('Repeatable_Data') }}</label>
                        </div>
                    </div>
                    <div class="pt-4 flex gap-3">
                        <button type="submit" class="w-full px-4 py-4 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl transition uppercase text-xs shadow-lg shadow-indigo-100">{{ __('Commit_Definition') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div id="editSubfieldModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="min-h-screen px-4 text-center flex items-center justify-center">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="closeModal('editSubfieldModal')"></div>
        <div class="inline-block bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md w-full">
            <div class="bg-white p-8">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold text-gray-900 uppercase tracking-tighter">{{ __('Modify_Subfield') }}</h3>
                    <button onclick="closeModal('editSubfieldModal')" class="text-gray-400 hover:text-gray-600 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                <form id="editSubfieldForm" method="POST" class="space-y-4">
                    @csrf @method('PUT')
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Subfield_Code (Immutable)</label>
                        <input type="text" id="edit_sub_code" disabled class="w-full border-gray-100 bg-gray-100 rounded-xl p-3 text-sm text-gray-400 font-mono font-bold">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Label</label>
                        <input type="text" name="label" id="edit_sub_label" required class="w-full border-gray-200 bg-gray-50 rounded-xl p-3 text-sm focus:ring-2 focus:ring-indigo-500 transition">
                    </div>
                    <div class="grid grid-cols-1 gap-3">
                        <div class="flex items-center space-x-3 bg-gray-50 p-3 rounded-xl border border-gray-100">
                            <input type="checkbox" name="is_visible" value="1" id="edit_sub_visible" class="w-4 h-4 rounded text-indigo-600 focus:ring-indigo-500">
                            <label for="edit_sub_visible" class="text-xs font-bold text-gray-600 uppercase">{{ __('VISIBLE') }}</label>
                        </div>
                        <div class="flex items-center space-x-3 bg-gray-50 p-3 rounded-xl border border-gray-100">
                            <input type="checkbox" name="is_mandatory" value="1" id="edit_sub_mandatory" class="w-4 h-4 rounded text-indigo-600 focus:ring-indigo-500">
                            <label for="edit_sub_mandatory" class="text-xs font-bold text-gray-600 uppercase">{{ __('Mandatory_Field') }}</label>
                        </div>
                        <div class="flex items-center space-x-3 bg-gray-50 p-3 rounded-xl border border-gray-100">
                            <input type="checkbox" name="is_repeatable" value="1" id="edit_sub_repeatable" class="w-4 h-4 rounded text-indigo-600 focus:ring-indigo-500">
                            <label for="edit_sub_repeatable" class="text-xs font-bold text-gray-600 uppercase">{{ __('Repeatable_Data') }}</label>
                        </div>
                    </div>
                    <div class="pt-4">
                        <button type="submit" class="w-full px-4 py-4 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl transition uppercase text-xs shadow-lg shadow-indigo-100">{{ __('Save_Changes') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function openModal(id) {
        document.getElementById(id).classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
    function closeModal(id) {
        document.getElementById(id).classList.add('hidden');
        document.body.style.overflow = '';
    }
    function openSubfieldModal(tag, label) {
        document.getElementById('hiddenTagField').value = tag;
        document.getElementById('targetTagInfo').innerText = `Injecting into Tag: ${tag} (${label})`;
        openModal('addSubfieldModal');
    }
    
    function openEditTagModal(tag) {
        const form = document.getElementById('editTagForm');
        form.action = `/topsecret/marc-definitions/tag/${tag.id}`;
        document.getElementById('edit_tag_id').value = tag.tag;
        document.getElementById('edit_tag_label').value = tag.label;
        document.getElementById('edit_tag_description').value = tag.description;
        document.getElementById('edit_tag_visible').checked = tag.is_visible;
        openModal('editTagModal');
    }

    function openEditSubfieldModal(subfield) {
        const form = document.getElementById('editSubfieldForm');
        form.action = `/topsecret/marc-definitions/subfield/${subfield.id}`;
        document.getElementById('edit_sub_code').value = '$' + subfield.code;
        document.getElementById('edit_sub_label').value = subfield.label;
        document.getElementById('edit_sub_visible').checked = subfield.is_visible;
        document.getElementById('edit_sub_mandatory').checked = subfield.is_mandatory;
        document.getElementById('edit_sub_repeatable').checked = subfield.is_repeatable;
        openModal('editSubfieldModal');
    }

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            document.querySelectorAll('.fixed').forEach(m => m.classList.add('hidden'));
            document.body.style.overflow = '';
        }
    });
</script>

<style>
    @keyframes fade-in-down {
        0% { opacity: 0; transform: translateY(-10px); }
        100% { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in-down { animation: fade-in-down 0.4s ease-out; }
    [x-cloak] { display: none !important; }
</style>
@endsection
