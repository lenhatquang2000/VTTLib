<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Sidebar;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    protected $userService;

    public function __construct(\App\Services\UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index()
    {
        $roles = Role::withCount('users')->get();
        return view('admin.roles.index', compact('roles'));
    }

    public function create()
    {
        $sidebars = Sidebar::whereNull('parent_id')->with('children')->get();
        return view('admin.roles.create', compact('sidebars'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:roles,name|max:255',
            'display_name' => 'required|string|max:255',
            'sidebars' => 'array',
            'sidebars.*' => 'exists:sidebars,id',
        ]);

        $role = Role::create([
            'name' => $validated['name'],
            'display_name' => $validated['display_name'],
        ]);

        if (isset($validated['sidebars'])) {
            $role->sidebars()->sync($validated['sidebars']);
        }

        return redirect()->route('admin.roles.index')->with('success', __('Role created successfully'));
    }

    public function edit(Role $role)
    {
        $sidebars = Sidebar::whereNull('parent_id')->with('children')->get();
        $roleSidebars = $role->sidebars->pluck('id')->toArray();
        return view('admin.roles.edit', compact('role', 'sidebars', 'roleSidebars'));
    }

    public function update(Request $request, Role $role)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
            'display_name' => 'required|string|max:255',
            'sidebars' => 'array',
            'sidebars.*' => 'exists:sidebars,id',
        ]);

        $role->update([
            'name' => $validated['name'],
            'display_name' => $validated['display_name'],
        ]);

        if (isset($validated['sidebars'])) {
            $role->sidebars()->sync($validated['sidebars']);
            
            // Sync new tabs to all users assigned to this role
            $this->userService->syncAllUsersToRoleSidebars($role->id);
        }

        return redirect()->route('admin.roles.index')->with('success', __('Role updated successfully and users synchronized'));
    }

    public function destroy(Role $role)
    {
        $role->delete();
        return redirect()->route('admin.roles.index')->with('success', __('Role deleted successfully'));
    }
}
