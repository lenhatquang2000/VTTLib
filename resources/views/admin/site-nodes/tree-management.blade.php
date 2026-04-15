@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold">{{ __('Quản lý Cấu trúc Cây') }}</h1>
            <p class="text-sm text-gray-400 mt-1">{{ __('Sắp xếp và quản lý cấu trúc cây của website') }}</p>
        </div>
        <div class="flex gap-2">
            <button onclick="expandAll()" class="btn-secondary text-sm">
                <i class="fas fa-expand-alt mr-1"></i> Mở rộng
            </button>
            <button onclick="collapseAll()" class="btn-secondary text-sm">
                <i class="fas fa-compress-alt mr-1"></i> Thu gọn
            </button>
            <button onclick="rebuildTree()" class="btn-primary text-sm">
                <i class="fas fa-sync-alt mr-1"></i> Lưu cấu trúc
            </button>
        </div>
    </div>

    <!-- Language Tabs -->
    <div class="card-admin p-4">
        <div class="flex gap-2">
            <a href="{{ route('admin.site-nodes.tree', ['language' => 'vi']) }}" 
               class="px-4 py-2 rounded-lg {{ $language === 'vi' ? 'bg-blue-600 text-white' : 'bg-gray-700 text-gray-300' }}">
                Tiếng Việt
            </a>
            <a href="{{ route('admin.site-nodes.tree', ['language' => 'en']) }}" 
               class="px-4 py-2 rounded-lg {{ $language === 'en' ? 'bg-blue-600 text-white' : 'bg-gray-700 text-gray-300' }}">
                English
            </a>
        </div>
    </div>

    <!-- Tree Container -->
    <div class="card-admin p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold">Cấu trúc cây</h3>
            <div class="flex items-center gap-2">
                <input type="checkbox" id="bulk-select" onchange="toggleBulkSelect()">
                <label for="bulk-select" class="text-sm">Chọn tất cả</label>
                <select id="bulk-action" class="input-field text-sm" onchange="performBulkAction()">
                    <option value="">Hành động hàng loạt</option>
                    <option value="activate">Kích hoạt</option>
                    <option value="deactivate">Vô hiệu hóa</option>
                    <option value="delete">Xóa</option>
                    <option value="move">Di chuyển</option>
                </select>
            </div>
        </div>
        
        <div id="tree-container" class="space-y-2">
            @include('admin.site-nodes.tree-draggable', ['nodes' => $tree, 'level' => 0])
        </div>
    </div>

    <!-- Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="card-admin p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-400">Tổng nodes</p>
                    <p class="text-2xl font-bold text-blue-400">{{ count($tree) }}</p>
                </div>
                <i class="fas fa-sitemap text-3xl text-blue-400 opacity-20"></i>
            </div>
        </div>
        <div class="card-admin p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-400">Đang hoạt động</p>
                    <p class="text-2xl font-bold text-green-400">{{ App\Models\SiteNode::active()->count() }}</p>
                </div>
                <i class="fas fa-check-circle text-3xl text-green-400 opacity-20"></i>
            </div>
        </div>
        <div class="card-admin p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-400">Cấp sâu nhất</p>
                    <p class="text-2xl font-bold text-yellow-400">{{ getMaxDepth($tree) }}</p>
                </div>
                <i class="fas fa-layer-group text-3xl text-yellow-400 opacity-20"></i>
            </div>
        </div>
        <div class="card-admin p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-400">Ngôn ngữ</p>
                    <p class="text-2xl font-bold text-purple-400">{{ $language }}</p>
                </div>
                <i class="fas fa-language text-3xl text-purple-400 opacity-20"></i>
            </div>
        </div>
    </div>
</div>

@php
function getMaxDepth($nodes, $currentDepth = 0) {
    $maxDepth = $currentDepth;
    foreach ($nodes as $node) {
        if (!empty($node['children'])) {
            $childDepth = getMaxDepth($node['children'], $currentDepth + 1);
            $maxDepth = max($maxDepth, $childDepth);
        }
    }
    return $maxDepth;
}
@endphp

@push('styles')
<style>
    .tree-node {
        border-left: 2px solid #374151;
        transition: all 0.3s ease;
        position: relative;
    }
    
    .tree-node:hover {
        border-left-color: #3b82f6;
        background-color: rgba(59, 130, 246, 0.05);
    }
    
    .tree-node.dragging {
        opacity: 0.5;
        border-left-color: #10b981;
    }
    
    .tree-node.drag-over {
        border-left-color: #f59e0b;
        background-color: rgba(245, 158, 11, 0.1);
    }
    
    .tree-node-content {
        display: flex;
        align-items: center;
        padding: 12px 16px;
        border-radius: 8px;
        transition: all 0.2s ease;
        cursor: move;
    }
    
    .tree-node-content:hover {
        background-color: rgba(59, 130, 246, 0.1);
    }
    
    .tree-children {
        margin-left: 24px;
        border-left: 1px dashed #4b5563;
        padding-left: 16px;
        margin-top: 8px;
    }
    
    .drag-handle {
        width: 20px;
        height: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: move;
        color: #6b7280;
        margin-right: 8px;
    }
    
    .drag-handle:hover {
        color: #3b82f6;
    }
    
    .node-checkbox {
        width: 16px;
        height: 16px;
        margin-right: 8px;
    }
    
    .node-icon {
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 6px;
        margin-right: 12px;
    }
    
    .node-actions {
        display: flex;
        gap: 4px;
        opacity: 0;
        transition: opacity 0.2s ease;
    }
    
    .tree-node:hover .node-actions {
        opacity: 1;
    }
    
    .action-btn {
        width: 28px;
        height: 28px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 4px;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    
    .action-btn:hover {
        background-color: rgba(59, 130, 246, 0.2);
    }
    
    .status-badge {
        padding: 2px 8px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 600;
    }
    
    .status-active {
        background-color: rgba(34, 197, 94, 0.2);
        color: #22c55e;
    }
    
    .status-inactive {
        background-color: rgba(239, 68, 68, 0.2);
        color: #ef4444;
    }
    
    .bulk-selected {
        background-color: rgba(59, 130, 246, 0.1);
        border-left-color: #3b82f6;
    }
</style>
@endpush

@push('scripts')
<script>
let draggedElement = null;
let selectedNodes = new Set();

function toggleNode(nodeId) {
    const children = document.getElementById(`children-${nodeId}`);
    const toggle = document.getElementById(`toggle-${nodeId}`);
    
    if (children) {
        children.classList.toggle('hidden');
        toggle.innerHTML = children.classList.contains('hidden') ? 
            '<i class="fas fa-chevron-right text-xs"></i>' : 
            '<i class="fas fa-chevron-down text-xs"></i>';
    }
}

function expandAll() {
    const allChildren = document.querySelectorAll('.tree-children');
    const allToggles = document.querySelectorAll('.toggle-btn i');
    
    allChildren.forEach(child => child.classList.remove('hidden'));
    allToggles.forEach(toggle => {
        toggle.className = 'fas fa-chevron-down text-xs';
    });
}

function collapseAll() {
    const allChildren = document.querySelectorAll('.tree-children');
    const allToggles = document.querySelectorAll('.toggle-btn i');
    
    allChildren.forEach(child => child.classList.add('hidden'));
    allToggles.forEach(toggle => {
        toggle.className = 'fas fa-chevron-right text-xs';
    });
}

function toggleBulkSelect() {
    const checkboxes = document.querySelectorAll('.node-checkbox');
    const selectAll = document.getElementById('bulk-select').checked;
    
    checkboxes.forEach(checkbox => {
        checkbox.checked = selectAll;
        const nodeId = checkbox.value;
        const nodeElement = document.getElementById(`node-${nodeId}`);
        
        if (selectAll) {
            selectedNodes.add(nodeId);
            nodeElement.classList.add('bulk-selected');
        } else {
            selectedNodes.delete(nodeId);
            nodeElement.classList.remove('bulk-selected');
        }
    });
}

function toggleNodeSelection(nodeId) {
    const nodeElement = document.getElementById(`node-${nodeId}`);
    
    if (selectedNodes.has(nodeId)) {
        selectedNodes.delete(nodeId);
        nodeElement.classList.remove('bulk-selected');
    } else {
        selectedNodes.add(nodeId);
        nodeElement.classList.add('bulk-selected');
    }
    
    // Update select all checkbox
    const allCheckboxes = document.querySelectorAll('.node-checkbox');
    const selectAll = document.getElementById('bulk-select');
    selectAll.checked = selectedNodes.size === allCheckboxes.length;
}

function performBulkAction() {
    const action = document.getElementById('bulk-action').value;
    
    if (!action || selectedNodes.size === 0) {
        alert('Vui lòng chọn node và hành động');
        return;
    }
    
    if (action === 'delete' && !confirm('Bạn có chắc muốn xóa các node đã chọn?')) {
        return;
    }
    
    const nodeIds = Array.from(selectedNodes);
    
    fetch('/topsecret/site-nodes/bulk-action', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            action: action,
            node_ids: nodeIds
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification(data.message, 'success');
            if (action === 'delete') {
                location.reload();
            }
        } else {
            showNotification(data.message, 'error');
        }
    })
    .catch(error => {
        showNotification('Lỗi khi thực hiện hành động', 'error');
    });
}

function rebuildTree() {
    const treeData = collectTreeData();
    
    fetch('/topsecret/site-nodes/tree/rebuild', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            tree: treeData
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification(data.message, 'success');
        } else {
            showNotification(data.message, 'error');
        }
    })
    .catch(error => {
        showNotification('Lỗi khi lưu cấu trúc', 'error');
    });
}

function collectTreeData() {
    const treeData = [];
    const nodes = document.querySelectorAll('.tree-node');
    
    nodes.forEach(node => {
        const nodeId = node.dataset.nodeId;
        const parentElement = node.closest('.tree-children');
        const parentId = parentElement ? parentElement.dataset.parentId : null;
        const order = Array.from(node.parentNode.children).indexOf(node);
        
        treeData.push({
            id: parseInt(nodeId),
            parent_id: parentId ? parseInt(parentId) : null,
            sort_order: order
        });
    });
    
    return treeData;
}

function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 ${
        type === 'success' ? 'bg-green-600 text-white' : 
        type === 'error' ? 'bg-red-600 text-white' : 
        'bg-blue-600 text-white'
    }`;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 3000);
}

// Drag and drop functionality
document.addEventListener('DOMContentLoaded', function() {
    const treeNodes = document.querySelectorAll('.tree-node');
    
    treeNodes.forEach(node => {
        node.addEventListener('dragstart', handleDragStart);
        node.addEventListener('dragover', handleDragOver);
        node.addEventListener('drop', handleDrop);
        node.addEventListener('dragend', handleDragEnd);
        node.addEventListener('dragenter', handleDragEnter);
        node.addEventListener('dragleave', handleDragLeave);
    });
});

function handleDragStart(e) {
    draggedElement = e.currentTarget;
    e.currentTarget.classList.add('dragging');
    e.dataTransfer.effectAllowed = 'move';
    e.dataTransfer.setData('text/html', e.currentTarget.innerHTML);
}

function handleDragOver(e) {
    if (e.preventDefault) {
        e.preventDefault();
    }
    e.dataTransfer.dropEffect = 'move';
    return false;
}

function handleDragEnter(e) {
    if (draggedElement !== e.currentTarget) {
        e.currentTarget.classList.add('drag-over');
    }
}

function handleDragLeave(e) {
    e.currentTarget.classList.remove('drag-over');
}

function handleDrop(e) {
    if (e.stopPropagation) {
        e.stopPropagation();
    }
    
    if (draggedElement !== e.currentTarget) {
        // Swap nodes or move to new parent
        const draggedParent = draggedElement.parentNode;
        const targetParent = e.currentTarget.parentNode;
        
        if (draggedParent === targetParent) {
            // Same parent, just swap order
            const allChildren = Array.from(targetParent.children);
            const draggedIndex = allChildren.indexOf(draggedElement);
            const targetIndex = allChildren.indexOf(e.currentTarget);
            
            if (draggedIndex < targetIndex) {
                targetParent.insertBefore(draggedElement, e.currentTarget.nextSibling);
            } else {
                targetParent.insertBefore(draggedElement, e.currentTarget);
            }
        } else {
            // Move to new parent
            targetParent.insertBefore(draggedElement, e.currentTarget);
        }
    }
    
    return false;
}

function handleDragEnd(e) {
    const treeNodes = document.querySelectorAll('.tree-node');
    treeNodes.forEach(node => {
        node.classList.remove('dragging', 'drag-over');
    });
}
</script>
@endpush
@endsection
