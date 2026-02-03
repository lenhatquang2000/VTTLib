<?php

namespace App\Http\Controllers\Root;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Sidebar;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::withCount('users')->get();
        return view('root.roles.index', compact('roles'));
    }

    public function create()
    {
        $sidebars = Sidebar::whereNull('parent_id')->with('children')->get();
        return view('root.roles.create', compact('sidebars'));
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

        return redirect()->route('root.roles.index')->with('success', __('Role created successfully'));
    }

    public function edit(Role $role)
    {
        $sidebars = Sidebar::whereNull('parent_id')->with('children')->get();
        $roleSidebars = $role->sidebars->pluck('id')->toArray();
        return view('root.roles.edit', compact('role', 'sidebars', 'roleSidebars'));
    }

    public function update(Request $request, Role $role)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
            'display_name' => 'required|string|max:255',
            'sidebars' => 'array',
            'sidebars.*' => 'exists:sidebars,id',
        ]);

        // Don't allow changing name of core roles if needed, but for now let's allow it
        $role->update([
            'name' => $validated['name'],
            'display_name' => $validated['display_name'],
        ]);

        if (isset($validated['sidebars'])) {
            $role->sidebars()->sync($validated['sidebars']);
        }

        return redirect()->route('root.roles.index')->with('success', __('Role updated successfully'));
    }

    public function destroy(Role $role)
    {
        $role->delete();
        return redirect()->route('root.roles.index')->with('success', __('Role deleted successfully'));
    }
}
