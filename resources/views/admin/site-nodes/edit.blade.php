@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold">{{ __('Edit Node') }}</h1>
            <p class="text-sm text-gray-400 mt-1">{{ __('Update information for page: ') }}{{ $siteNode->display_name }}</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.site-nodes.page-builder', $siteNode) }}" class="btn-primary">
                <i class="fas fa-layer-group mr-2"></i>{{ __('Page Builder') }}
                <i class="fas fa-arrow-right ml-2"></i>
            </a>
            <a href="{{ route('admin.site-nodes.index', ['language' => $siteNode->language]) }}" class="btn-secondary">
                <i class="fas fa-arrow-left mr-2"></i>{{ __('Back') }}
            </a>
        </div>
    </div>

    <!-- Form -->
    <div class="card-admin p-6">
        <form id="siteNodeForm" action="{{ route('admin.site-nodes.update', $siteNode) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Left Column -->
                <div class="space-y-4">
                    <!-- Basic Information -->
                    <div class="border-b border-gray-700 pb-4">w
                        <h3 class="text-lg font-bold mb-4 text-blue-400">{{ __('Basic Information') }}</h3>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium mb-1">{{ __('Node Code') }} *</label>
                                <input type="text" name="node_code" required
                                       class="input-field w-full" 
                                       placeholder="{{ __('vi-du: about, contact') }}"
                                       value="{{ old('node_code', $siteNode->node_code) }}">
                                <p class="text-xs text-gray-400 mt-1">{{ __('Unique code, no accents, used for URL') }}</p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium mb-1">{{ __('Internal Name') }} *</label>
                                <input type="text" name="node_name" required
                                       class="input-field w-full" 
                                       placeholder="{{ __('Internal name') }}"
                                       value="{{ old('node_name', $siteNode->node_name) }}">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium mb-1">{{ __('Display Name') }} *</label>
                                <input type="text" name="display_name" required
                                       class="input-field w-full" 
                                       placeholder="{{ __('Name shown on menu') }}"
                                       value="{{ old('display_name', $siteNode->display_name) }}">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium mb-1">{{ __('Description') }}</label>
                                <textarea name="description" rows="3"
                                          class="input-field w-full"
                                          placeholder="{{ __('Brief description') }}">{{ old('description', $siteNode->description) }}</textarea>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Hierarchy & Icon -->
                    <div class="border-b border-gray-700 py-4">
                        <h3 class="text-lg font-bold mb-4 text-green-400">{{ __('Hierarchy & Style') }}</h3>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium mb-1">{{ __('Parent Node') }}</label>
                                <select name="parent_id" class="input-field w-full">
                                    <option value="">-- {{ __('Root (Root)') }} --</option>
                                    @foreach($parents as $id => $name)
                                        <option value="{{ $id }}" {{ old('parent_id', $siteNode->parent_id) == $id ? 'selected' : '' }}>
                                            {{ $name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium mb-1">{{ __('Icon') }}</label>
                                <input type="text" name="icon" 
                                       class="input-field w-full" 
                                       placeholder="fas fa-home"
                                       value="{{ old('icon', $siteNode->icon) }}">
                                <p class="text-xs text-gray-400 mt-1">{{ __('Font Awesome class') }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Display Settings -->
                    <div class="border-b border-gray-700 py-4">
                        <h3 class="text-lg font-bold mb-4 text-emerald-400">{{ __('Display Settings') }}</h3>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium mb-1">{{ __('Display Type') }}</label>
                                <select name="display_type" id="display_type" class="input-field w-full">
                                    @foreach($displayTypes as $value => $label)
                                        <option value="{{ $value }}" {{ old('display_type', $siteNode->display_type) == $value ? 'selected' : '' }}>
                                            {{ __($label) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium mb-1">{{ __('Open Link') }}</label>
                                <select name="target" class="input-field w-full">
                                    <option value="_self" {{ old('target', $siteNode->target) == '_self' ? 'selected' : '' }}>{{ __('Same tab (_self)') }}</option>
                                    <option value="_blank" {{ old('target', $siteNode->target) == '_blank' ? 'selected' : '' }}>{{ __('New tab (_blank)') }}</option>
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium mb-1">{{ __('Sort Order') }}</label>
                                <input type="number" name="sort_order" 
                                       class="input-field w-full" 
                                       value="{{ old('sort_order', $siteNode->sort_order) }}">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium mb-1">{{ __('Language') }}</label>
                                <select name="language" class="input-field w-full">
                                    <option value="vi" {{ old('language', $siteNode->language) == 'vi' ? 'selected' : '' }}>{{ __('Vietnamese') }}</option>
                                    <option value="en" {{ old('language', $siteNode->language) == 'en' ? 'selected' : '' }}>{{ __('English') }}</option>
                                </select>
                            </div>
                            
                            <div class="flex items-center">
                                <input type="checkbox" name="is_active" id="is_active" 
                                       class="mr-2" {{ old('is_active', $siteNode->is_active) ? 'checked' : '' }}>
                                <label for="is_active" class="text-sm">{{ __('Active') }}</label>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Right Column -->
                <div class="space-y-4">
                    <!-- Access Control -->
                    <div class="border-b border-gray-700 pb-4">
                        <h3 class="text-lg font-bold mb-4 text-orange-400">{{ __('Access Permissions') }}</h3>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium mb-1">{{ __('Access Type') }}</label>
                                <select name="access_type" id="access_type" 
                                        class="input-field w-full"
                                        onchange="toggleAccessOptions()">
                                    @foreach($accessTypes as $value => $label)
                                        <option value="{{ $value }}" {{ old('access_type', $siteNode->access_type) == $value ? 'selected' : '' }}>
                                            {{ __($label) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div id="role_options" class="{{ old('access_type', $siteNode->access_type) === 'roles' ? '' : 'hidden' }}">
                                <label class="block text-sm font-medium mb-2">{{ __('Allowed Roles') }}</label>
                                <div class="space-y-2 bg-gray-900/50 p-4 rounded border border-gray-700">
                                    <div class="flex items-center">
                                        <input type="checkbox" name="allowed_roles[]" value="admin" 
                                               class="mr-2" {{ in_array('admin', old('allowed_roles', $siteNode->allowed_roles ?? [])) ? 'checked' : '' }}>
                                        <label class="text-sm">{{ __('Admin') }}</label>
                                    </div>
                                    <div class="flex items-center">
                                        <input type="checkbox" name="allowed_roles[]" value="librarian" 
                                               class="mr-2" {{ in_array('librarian', old('allowed_roles', $siteNode->allowed_roles ?? [])) ? 'checked' : '' }}>
                                        <label class="text-sm">{{ __('Librarian') }}</label>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="flex items-center">
                                <input type="checkbox" name="allow_guest" id="allow_guest" 
                                       class="mr-2" {{ old('allow_guest', $siteNode->allow_guest) ? 'checked' : '' }}>
                                <label for="allow_guest" class="text-sm">{{ __('Allow Guests') }}</label>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Link Settings -->
                    <div class="border-b border-gray-700 pb-4">
                        <h3 class="text-lg font-bold mb-4 text-purple-400">{{ __('Link & Content') }}</h3>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium mb-1">{{ __('System Route') }}</label>
                                <select name="route_name" id="route_name" 
                                        class="input-field w-full"
                                        onchange="toggleContentOptions()">
                                    <option value="">-- {{ __('Select route') }} --</option>
                                    @foreach($routes as $value => $label)
                                        <option value="{{ $value }}" {{ old('route_name', $siteNode->route_name) == $value ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium mb-1">{{ __('Custom URL') }}</label>
                                <input type="text" name="url" id="url"
                                       class="input-field w-full" 
                                       placeholder="https://example.com"
                                       value="{{ old('url', $siteNode->url) }}">
                            </div>
                            
                            @include('admin.site-nodes.partials.content-options', ['siteNode' => $siteNode])
                        </div>
                    </div>
                    
                    <!-- SEO Settings -->
                    <div>
                        <h3 class="text-lg font-bold mb-4 text-red-400">SEO</h3>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium mb-1">Meta Title</label>
                                <input type="text" name="meta_title" 
                                       class="input-field w-full" 
                                       placeholder="{{ __('SEO Title') }}"
                                       value="{{ old('meta_title', $siteNode->meta_title) }}">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium mb-1">Meta Description</label>
                                <textarea name="meta_description" rows="3"
                                          class="input-field w-full"
                                          placeholder="{{ __('SEO Description') }}">{{ old('meta_description', $siteNode->meta_description) }}</textarea>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium mb-1">Meta Keywords</label>
                                <input type="text" name="meta_keywords" 
                                       class="input-field w-full" 
                                       placeholder="{{ __('keyword 1, keyword 2, keyword 3') }}"
                                       value="{{ old('meta_keywords', $siteNode->meta_keywords) }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Action Buttons -->
    <div class="flex justify-end items-center gap-4 mt-8 pt-6 border-t border-gray-700">
        <a href="{{ route('admin.site-nodes.index', ['language' => $siteNode->language]) }}" 
           class="px-6 py-2.5 rounded-xl border border-gray-600 text-gray-400 font-semibold hover:bg-gray-700 hover:text-white transition-all duration-200 flex items-center">
            <i class="fas fa-times mr-2 text-sm opacity-70"></i>{{ __('Cancel') }}
        </a>
        <button type="submit" 
                class="px-10 py-2.5 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-bold shadow-lg shadow-blue-900/40 hover:from-blue-500 hover:to-indigo-500 transform hover:-translate-y-0.5 active:scale-95 transition-all duration-200 flex items-center">
            <i class="fas fa-save mr-2 text-sm"></i>{{ __('Update Node') }}
        </button>
    </div>
</form>
    </div>
</div>

@push('scripts')
<script>
// Simple Drag & Drop variables
let draggedItemType = null;
let draggedItemId = null;

// Drag & Drop functions
function handleDragStart(event) {
    draggedItemType = event.target.closest('.available-item').dataset.itemType;
    draggedItemId = null;
    event.dataTransfer.effectAllowed = 'copy';
}

function handleItemDragStart(event) {
    draggedItemId = event.target.closest('.canvas-item').dataset.itemId;
    draggedItemType = null;
    event.dataTransfer.effectAllowed = 'move';
}

function handleDragOver(event) {
    event.preventDefault();
    event.dataTransfer.dropEffect = draggedItemId ? 'move' : 'copy';
}

function handleItemDragOver(event) {
    event.preventDefault();
}

function handleDrop(event) {
    event.preventDefault();
    
    const canvas = document.getElementById('items-canvas');
    
    if (draggedItemType) {
        // Add new item
        addItemToCanvas(draggedItemType);
    } else if (draggedItemId) {
        // Reorder existing item
        const draggedElement = document.querySelector(`[data-item-id="${draggedItemId}"]`);
        if (draggedElement && event.target.closest('.canvas-item') !== draggedElement) {
            // Simple reorder - just append to end for now
            canvas.appendChild(draggedElement);
        }
    }
    
    updateItemsOrder();
}

function handleItemDrop(event) {
    event.preventDefault();
    event.stopPropagation();
    
    const targetItem = event.target.closest('.canvas-item');
    const draggedElement = document.querySelector(`[data-item-id="${draggedItemId}"]`);
    
    if (targetItem && draggedElement && targetItem !== draggedElement) {
        const canvas = document.getElementById('items-canvas');
        const allItems = [...canvas.querySelectorAll('.canvas-item')];
        const targetIndex = allItems.indexOf(targetItem);
        const draggedIndex = allItems.indexOf(draggedElement);
        
        if (draggedIndex < targetIndex) {
            targetItem.parentNode.insertBefore(draggedElement, targetItem.nextSibling);
        } else {
            targetItem.parentNode.insertBefore(draggedElement, targetItem);
        }
        
        updateItemsOrder();
    }
}

function addItemToCanvas(itemType) {
    const canvas = document.getElementById('items-canvas');
    const emptyState = canvas.querySelector('.text-center');
    if (emptyState) {
        emptyState.remove();
    }
    
    const itemTypes = @json(App\Models\SiteNodeItem::getAvailableTypes());
    const itemId = 'new_' + Date.now();
    const defaultData = getDefaultItemData(itemType);
    const previewText = getItemPreviewText(itemType, defaultData);
    
    const itemElement = document.createElement('div');
    itemElement.className = 'canvas-item mb-4 p-4 bg-white border border-gray-200 rounded-lg';
    itemElement.dataset.itemId = itemId;
    itemElement.dataset.itemType = itemType;
    itemElement.dataset.itemData = JSON.stringify(defaultData);
    itemElement.draggable = true;
    itemElement.setAttribute('ondragstart', 'handleItemDragStart(event)');
    itemElement.setAttribute('ondragover', 'handleItemDragOver(event)');
    itemElement.setAttribute('ondrop', 'handleItemDrop(event)');
    
    itemElement.innerHTML = `
        <div class="flex items-start justify-between">
            <div class="flex items-center">
                <i class="fas fa-grip-vertical text-gray-400 mr-3 cursor-move"></i>
                <div>
                    <div class="font-medium text-sm">${itemTypes[itemType] || itemType}</div>
                    <div class="text-xs text-gray-500 mt-1">${previewText}</div>
                </div>
            </div>
            <div class="flex items-center space-x-2">
                <button type="button" onclick="editItem('${itemId}')" class="text-blue-600 hover:text-blue-800">
                    <i class="fas fa-edit"></i>
                </button>
                <button type="button" onclick="deleteItem('${itemId}')" class="text-red-600 hover:text-red-800">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
    `;
    
    canvas.appendChild(itemElement);
}

function getDefaultItemData(itemType) {
    const defaults = {
        hero: { title: 'Tiêu đề Hero', subtitle: 'Subtitle Hero', button_text: 'Xem thêm', button_url: '#' },
        features: { title: 'Tính năng nổi bật', features: [] },
        text: { content: 'Nội dung văn bản của bạn...' },
        image: { url: 'https://via.placeholder.com/600x400', alt: 'Image' },
        button: { text: 'Nút bấm', url: '#', style: 'primary' },
        gallery: { images: [] },
        testimonial: { content: 'Nội dung testimonial', author: 'Tên tác giả', role: 'Vai trò' },
        contact: { email: 'contact@example.com', phone: '0123456789' },
        divider: { style: 'solid' }
    };
    
    return defaults[itemType] || {};
}

function getItemPreviewText(itemType, data) {
    switch(itemType) {
        case 'hero':
            return `Hero: ${data.title || 'No title'}`;
        case 'text':
            return `Text: ${(data.content || 'No content').substring(0, 50)}...`;
        case 'image':
            return `Image: ${data.url || 'No URL'}`;
        case 'button':
            return `Button: ${data.text || 'No text'}`;
        default:
            return `${itemType} item`;
    }
}

function editItem(itemId) {
    // Simple prompt for now - can be enhanced with modal
    const itemType = getItemTypeById(itemId);
    const currentData = getItemDataById(itemId) || getDefaultItemData(itemType);
    
    let newValue = prompt('Edit item content (JSON format):', JSON.stringify(currentData, null, 2));
    if (newValue) {
        try {
            const newData = JSON.parse(newValue);
            updateItemData(itemId, newData);
        } catch (e) {
            alert('Invalid JSON format');
        }
    }
}

function deleteItem(itemId) {
    if (confirm('Bạn có chắc muốn xóa item này?')) {
        const item = document.querySelector(`[data-item-id="${itemId}"]`);
        if (item) {
            item.remove();
            checkEmptyCanvas();
            updateItemsOrder();
        }
    }
}

function clearAllItems() {
    if (confirm('Bạn có chắc muốn xóa tất cả các item?')) {
        const canvas = document.getElementById('items-canvas');
        canvas.innerHTML = `
            <div class="text-center py-12 text-gray-400">
                <i class="fas fa-layer-group text-4xl mb-3"></i>
                <p>Kéo các block từ bên trái vào đây để bắt đầu</p>
            </div>
        `;
        updateItemsOrder();
    }
}

function checkEmptyCanvas() {
    const canvas = document.getElementById('items-canvas');
    const items = canvas.querySelectorAll('.canvas-item');
    if (items.length === 0) {
        canvas.innerHTML = `
            <div class="text-center py-12 text-gray-400">
                <i class="fas fa-layer-group text-4xl mb-3"></i>
                <p>Kéo các block từ bên trái vào đây để bắt đầu</p>
            </div>
        `;
    }
}

function updateItemsOrder() {
    const canvas = document.getElementById('items-canvas');
    const items = canvas.querySelectorAll('.canvas-item');
    const itemsData = [];
    
    items.forEach((item, index) => {
        const itemId = item.dataset.itemId;
        const itemType = getItemTypeById(itemId);
        const itemData = getItemDataById(itemId) || getDefaultItemData(itemType);
        
        itemsData.push({
            id: itemId.startsWith('new_') ? null : itemId,
            item_type: itemType,
            item_data: itemData,
            sort_order: index
        });
    });
    
    document.getElementById('items_data').value = JSON.stringify(itemsData);
}

function getItemTypeById(itemId) {
    const item = document.querySelector(`[data-item-id="${itemId}"]`);
    if (item) {
        return item.dataset.itemType || 'text';
    }
    return 'text';
}

function getItemDataById(itemId) {
    const item = document.querySelector(`[data-item-id="${itemId}"]`);
    if (item && item.dataset.itemData) {
        try {
            return JSON.parse(item.dataset.itemData);
        } catch (e) {
            return null;
        }
    }
    return null;
}

function updateItemData(itemId, newData) {
    // This would need to be implemented to update the item data
    // and refresh the preview
    console.log('Update item:', itemId, newData);
}

// Original functions
function toggleAccessOptions() {
    const accessType = document.getElementById('access_type').value;
    const roleOptions = document.getElementById('role_options');
    
    if (accessType === 'roles') {
        roleOptions.classList.remove('hidden');
    } else {
        roleOptions.classList.add('hidden');
    }
}

function toggleContentOptions() {
    const routeName = document.getElementById('route_name').value;
    const url = document.getElementById('url').value;
    const contentOptions = document.getElementById('content_options');
    const contentTextarea = document.getElementById('content');
    
    if (routeName || url) {
        contentOptions.style.opacity = '0.5';
        if (contentTextarea) contentTextarea.disabled = true;
    } else {
        contentOptions.style.opacity = '1';
        if (contentTextarea) contentTextarea.disabled = false;
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    toggleAccessOptions();
    toggleContentOptions();
    updateItemsOrder();
    
    // Update items_data before form submit
    const form = document.getElementById('siteNodeForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            updateItemsOrder();
            const itemsDataValue = document.getElementById('items_data').value;
            console.log('Form submit - items_data:', itemsDataValue);
            alert('items_data: ' + itemsDataValue.substring(0, 200) + '...');
        });
    }
});
</script>
@endpush
@endsection
