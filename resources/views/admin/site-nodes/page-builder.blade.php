@extends('layouts.admin')

@section('content')
@php
    $itemTypeDescriptions = [
        'hero' => 'Banner chính với tiêu đề và nút',
        'features' => 'Danh sách tính năng nổi bật',
        'text' => 'Đoạn văn bản nội dung',
        'image' => 'Hình ảnh đơn lẻ',
        'button' => 'Nút bấm hành động',
        'gallery' => 'Bộ sưu tập hình ảnh',
        'testimonial' => 'Đánh giá từ khách hàng',
        'contact' => 'Thông tin liên hệ',
        'divider' => 'Đường kẻ phân cách',
    ];
@endphp
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold">{{ __('Page Builder') }}</h1>
            <p class="text-sm text-gray-400 mt-1">{{ __('Xây dựng trang: ') }}{{ $siteNode->display_name }}</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.site-nodes.edit', $siteNode) }}" class="btn-secondary">
                <i class="fas fa-arrow-left mr-2"></i>{{ __('Quay lại Edit') }}
            </a>
            <a href="{{ route('admin.site-nodes.index', ['language' => $siteNode->language]) }}" class="btn-secondary">
                <i class="fas fa-list mr-2"></i>{{ __('Danh sách') }}
            </a>
        </div>
    </div>

    <!-- Page Builder Form -->
    <div class="card-admin p-6">
        <form id="pageBuilderForm" action="{{ route('admin.site-nodes.page-builder.update', $siteNode) }}" method="POST">
            @csrf
            @method('PUT')
            
            <!-- Page Builder Interface -->
            <div class="flex gap-6">
                <!-- Left Sidebar - Available Items -->
                <div class="w-80 flex-shrink-0">
                    <div class="bg-gray-800 border border-gray-600 rounded-lg p-4">
                        <h3 class="text-lg font-bold mb-4 text-blue-400">Available Blocks</h3>
                        
                        <div class="space-y-2">
                            @foreach(App\Models\SiteNodeItem::getAvailableTypes() as $type => $label)
                            <div class="available-item bg-gray-700 hover:bg-gray-600 border border-gray-600 rounded-lg p-3 cursor-move transition-colors"
                                 data-item-type="{{ $type }}"
                                 draggable="true"
                                 ondragstart="handleDragStart(event)">
                                <div class="flex items-center">
                                    <i class="fas fa-grip-vertical text-gray-400 mr-3"></i>
                                    <div>
                                        <div class="font-medium text-sm text-gray-100">{{ $label }}</div>
                                        <div class="text-xs text-gray-400">{{ $itemTypeDescriptions[$type] ?? 'Custom content block' }}</div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        
                        <div class="mt-4 pt-4 border-t border-gray-700">
                            <button type="button" onclick="clearAllItems()" class="btn-secondary w-full text-sm">
                                <i class="fas fa-trash mr-2"></i>Clear All
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Canvas Area -->
                <div class="flex-1 bg-gray-800 border border-gray-600 rounded-lg p-4">
                    <div id="items-canvas" 
                         class="min-h-full bg-gray-900 rounded border-2 border-dashed border-gray-600 p-4"
                         ondrop="handleDrop(event)"
                         ondragover="handleDragOver(event)">
                        
                        @if(!empty($items))
                            @foreach($items as $item)
                            @php
                                $itemId = $item['id'] ?? ('new_' . uniqid());
                                $itemType = $item['item_type'] ?? 'text';
                                $itemData = $item['item_data'] ?? [];

                                switch ($itemType) {
                                    case 'hero':
                                        $previewText = 'Hero: ' . ($itemData['title'] ?? 'No title');
                                        break;
                                    case 'text':
                                        $previewText = 'Text: ' . \Illuminate\Support\Str::limit(($itemData['content'] ?? 'No content'), 50);
                                        break;
                                    case 'image':
                                        $previewText = 'Image: ' . ($itemData['url'] ?? 'No URL');
                                        break;
                                    case 'button':
                                        $previewText = 'Button: ' . ($itemData['text'] ?? 'No text');
                                        break;
                                    default:
                                        $previewText = $itemType . ' item';
                                }
                            @endphp
                            <div class="canvas-item mb-4 p-4 bg-gray-800 border border-gray-700 rounded-lg" 
                                 data-item-id="{{ $itemId }}"
                                 data-item-type="{{ $itemType }}"
                                 data-item-data="{{ json_encode($itemData) }}"
                                 draggable="true"
                                 ondragstart="handleItemDragStart(event)"
                                 ondragover="handleItemDragOver(event)"
                                 ondrop="handleItemDrop(event)">
                                <div class="flex items-start justify-between">
                                    <div class="flex items-center">
                                        <i class="fas fa-grip-vertical text-gray-400 mr-3 cursor-move"></i>
                                        <div>
                                            <div class="font-medium text-sm text-gray-100">{{ App\Models\SiteNodeItem::getAvailableTypes()[$itemType] ?? $itemType }}</div>
                                            <div class="text-xs text-gray-400 mt-1">
                                                {{ $previewText }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <button type="button" onclick="editItem('{{ $itemId }}')" class="text-blue-600 hover:text-blue-400">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button type="button" onclick="deleteItem('{{ $itemId }}')" class="text-red-600 hover:text-red-400">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        @else
                            <div class="text-center py-12 text-gray-400">
                                <i class="fas fa-layer-group text-4xl mb-3"></i>
                                <p>Kéo các block từ bên trái vào đây để bắt đầu</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- Hidden field to store items data -->
            <input type="hidden" name="items_data" id="items_data" value="">
            
            <!-- Submit Buttons -->
            <div class="flex justify-end gap-2 mt-6 pt-6 border-t border-gray-700">
                <a href="{{ route('admin.site-nodes.edit', $siteNode) }}" 
                   class="btn-secondary">
                    Hủy
                </a>
                <button type="submit" class="btn-primary">
                    <i class="fas fa-save mr-2"></i>Lưu Page Builder
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
    const item = document.querySelector(`[data-item-id="${itemId}"]`);
    if (item) {
        item.dataset.itemData = JSON.stringify(newData);
        const itemType = item.dataset.itemType;
        const previewText = getItemPreviewText(itemType, newData);
        
        // Update preview text
        const previewElement = item.querySelector('.text-xs.text-gray-500');
        if (previewElement) {
            previewElement.textContent = previewText;
        }
        
        updateItemsOrder();
    }
}

// Initialize items order on page load
document.addEventListener('DOMContentLoaded', function() {
    updateItemsOrder();
    
    // Update items_data before form submit
    const form = document.getElementById('pageBuilderForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            updateItemsOrder();
        });
    }
});
</script>
@endpush

@endsection
