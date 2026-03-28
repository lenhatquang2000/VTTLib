<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sidebar;
use App\Models\UserRoleSidebar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class SidebarManagementController extends Controller
{
    public function index()
    {
        $sidebarItems = Sidebar::with('children', 'userRoleSidebars')
            ->orderBy('order')
            ->orderBy('name')
            ->get();

        // Group by parent for tree structure
        $rootItems = $sidebarItems->where('parent_id', null);
        $childItems = $sidebarItems->where('parent_id', '!=', null);

        return view('admin.sidebar.index', compact('rootItems', 'childItems', 'sidebarItems'));
    }

    public function updateOrder(Request $request)
    {
        $request->validate([
            'orders'             => 'required|array',
            'orders.*.id'        => 'required|integer|exists:sidebars,id',
            'orders.*.order'     => 'required|integer|min:0',
            'orders.*.parent_id' => 'nullable|integer|exists:sidebars,id',
        ]);

        DB::beginTransaction();
        try {
            foreach ($request->orders as $item) {
                $fields = ['order' => $item['order']];

                // Also update parent_id if provided in the payload
                if (array_key_exists('parent_id', $item)) {
                    $fields['parent_id'] = $item['parent_id'] ?: null;
                }

                Sidebar::where('id', $item['id'])->update($fields);
            }

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => __('Sidebar order updated successfully'),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => __('Error updating sidebar order') . ': ' . $e->getMessage(),
            ], 500);
        }
    }

    public function updateParent(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|exists:sidebars,id',
            'parent_id' => 'nullable|integer|exists:sidebars,id',
        ]);

        // Prevent circular reference
        if ($request->parent_id) {
            $this->preventCircularReference($request->id, $request->parent_id);
        }

        Sidebar::where('id', $request->id)
            ->update(['parent_id' => $request->parent_id]);

        return response()->json([
            'success' => true,
            'message' => __('Sidebar parent updated successfully')
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'icon' => 'nullable|string|max:255',
            'route_name' => 'nullable|string|max:255',
            'parent_id' => 'nullable|integer|exists:sidebars,id',
            'is_active' => 'boolean',
        ]);

        $data = $request->only(['name', 'icon', 'route_name', 'parent_id', 'is_active']);

        // Set default icon if not provided
        if (empty($data['icon'])) {
            $data['icon'] = '<i class="fas fa-circle"></i>';
        }

        // Set order based on existing items
        $maxOrder = Sidebar::max('order') ?? 0;
        $data['order'] = $maxOrder + 1;

        // Prevent circular reference if parent is set
        if ($data['parent_id']) {
            $this->preventCircularReference(null, $data['parent_id']); // New item has no ID yet
        }

        $sidebar = Sidebar::create($data);

        return response()->json([
            'success' => true,
            'message' => __('Sidebar item created successfully'),
            'item' => $sidebar
        ]);
    }

    private function preventCircularReference($itemId, $parentId)
    {
        $currentParent = $parentId;
        $maxDepth = 10; // Prevent infinite loops
        $depth = 0;

        while ($currentParent && $depth < $maxDepth) {
            if ($currentParent == $itemId) {
                throw new \Exception(__('Cannot set parent: circular reference detected'));
            }

            $parent = Sidebar::find($currentParent);
            $currentParent = $parent ? $parent->parent_id : null;
            $depth++;
        }

        if ($depth >= $maxDepth) {
            throw new \Exception(__('Cannot set parent: maximum depth exceeded'));
        }
    }
}
