@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-slate-100">{{ __('Site Management') }}</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">{{ __('Manage website structure and content') }}</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.site-nodes.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition-colors duration-200">
                <i class="fas fa-plus mr-2"></i>{{ __('Add Node') }}
            </a>
        </div>
    </div>

    <!-- Language Info -->
    <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-slate-200 dark:border-slate-800 p-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <i class="fas fa-language text-xl text-slate-500 dark:text-slate-400"></i>
                <div>
                    <p class="text-sm font-medium text-slate-900 dark:text-slate-100">{{ __('Current Language') }}</p>
                    <p class="text-xs text-slate-500 dark:text-slate-400">{{ strtoupper($language) }} - {{ $language === 'vi' ? 'Tiếng Việt' : 'English' }}</p>
                </div>
            </div>
            <p class="text-xs text-slate-400 dark:text-slate-500">{{ __('Use language switcher in topbar to change') }}</p>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-slate-200 dark:border-slate-800 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-500 dark:text-slate-400">Total Nodes</p>
                    <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $stats['total'] ?? 0 }}</p>
                </div>
                <i class="fas fa-sitemap text-3xl text-blue-600 dark:text-blue-400 opacity-20"></i>
            </div>
        </div>
        <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-slate-200 dark:border-slate-800 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-500 dark:text-slate-400">Published</p>
                    <p class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $stats['published'] ?? 0 }}</p>
                </div>
                <i class="fas fa-check-circle text-3xl text-green-600 dark:text-green-400 opacity-20"></i>
            </div>
        </div>
        <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-slate-200 dark:border-slate-800 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-500 dark:text-slate-400">Draft</p>
                    <p class="text-2xl font-bold text-gray-600 dark:text-gray-400">{{ $stats['draft'] ?? 0 }}</p>
                </div>
                <i class="fas fa-edit text-3xl text-gray-600 dark:text-gray-400 opacity-20"></i>
            </div>
        </div>
        <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-slate-200 dark:border-slate-800 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-500 dark:text-slate-400">Current Language</p>
                    <p class="text-2xl font-bold text-purple-600 dark:text-purple-400">{{ strtoupper($language) }}</p>
                </div>
                <i class="fas fa-language text-3xl text-purple-600 dark:text-purple-400 opacity-20"></i>
            </div>
        </div>
    </div>

    <!-- Tree View -->
    <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-slate-200 dark:border-slate-800 p-6">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-lg font-bold text-gray-900 dark:text-slate-100">{{ __('Site Structure') }}</h3>
            <div class="flex gap-2">
                <button onclick="expandAll()" class="inline-flex items-center px-3 py-2 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-medium rounded-lg transition-colors duration-200 text-sm">
                    <i class="fas fa-expand-alt mr-1"></i> {{ __('Expand All') }}
                </button>
                <button onclick="collapseAll()" class="inline-flex items-center px-3 py-2 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-medium rounded-lg transition-colors duration-200 text-sm">
                    <i class="fas fa-compress-alt mr-1"></i> {{ __('Collapse All') }}
                </button>
            </div>
        </div>
        
        @if(count($tree) > 0)
        <div id="site-tree" class="space-y-2">
            @include('admin.site-nodes.tree', ['nodes' => $tree, 'level' => 0])
        </div>
        @else
        <div class="text-center py-12">
            <div class="bg-slate-100 dark:bg-slate-800 w-24 h-24 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-sitemap text-slate-400 dark:text-slate-500 text-3xl"></i>
            </div>
            <h3 class="text-xl font-semibold text-slate-600 dark:text-slate-400 mb-2">{{ __('No nodes found') }}</h3>
            <p class="text-slate-500 dark:text-slate-400 mb-4">{{ __('Start by creating your first page') }}</p>
            <a href="{{ route('admin.site-nodes.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition-colors duration-200">
                <i class="fas fa-plus mr-2"></i>{{ __('Create First Node') }}
            </a>
        </div>
        @endif
    </div>
</div>

@push('styles')
<style>
    .tree-node {
        border-left: 2px solid #374151;
        transition: all 0.3s ease;
    }
    
    .tree-node:hover {
        border-left-color: #3b82f6;
        background-color: rgba(59, 130, 246, 0.05);
    }
    
    .tree-node-content {
        display: flex;
        align-items: center;
        padding: 12px 16px;
        border-radius: 8px;
        transition: all 0.2s ease;
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
    
    .toggle-btn {
        width: 20px;
        height: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 4px;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    
    .toggle-btn:hover {
        background-color: rgba(59, 130, 246, 0.2);
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
</style>
@endpush

@push('scripts')
<script>
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

function toggleStatus(nodeId) {
    fetch(`/topsecret/site-nodes/${nodeId}/toggle-status`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const badge = document.getElementById(`status-${nodeId}`);
            badge.className = `status-badge ${data.is_active ? 'status-active' : 'status-inactive'}`;
            badge.textContent = data.is_active ? 'Hoạt động' : 'Ẩn';
            
            // Show success message
            showNotification(data.message, 'success');
        } else {
            showNotification(data.message, 'error');
        }
    })
    .catch(error => {
        showNotification('Lỗi khi cập nhật trạng thái!', 'error');
    });
}

function showNotification(message, type = 'info') {
    // Simple notification implementation
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

// Drag and drop for reordering
document.addEventListener('DOMContentLoaded', function() {
    // Initialize drag and drop functionality here
    console.log('Site management initialized');
});
</script>
@endpush
@endsection
