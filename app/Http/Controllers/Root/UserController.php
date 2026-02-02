<?php

namespace App\Http\Controllers\Root;

use App\Http\Controllers\Controller;
use App\Http\Requests\Root\StoreUserRequest;
use App\Http\Requests\Root\UpdateUserRequest;
use App\Http\Requests\Root\AssignRoleRequest;
use App\Models\User;
use App\Models\Role;
use App\Models\Sidebar;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index(Request $request)
    {
        $search = $request->query('search');
        $perPage = $request->query('per_page', 10);

        $roleUsers = $this->userService->getUsersWithFilters($search, $perPage);
        $users = User::all();
        $roles = Role::all();
        $sidebars = Sidebar::whereNull('parent_id')->with('children')->orderBy('order')->get();

        return view('root.users.index', compact('roleUsers', 'users', 'roles', 'sidebars', 'search', 'perPage'));
    }

    public function store(StoreUserRequest $request)
    {
        try {
            $validated = $request->validated();
            $this->userService->createUser($validated, $validated['role_id']);
            return redirect()->back()->with('success', 'User and initial role created successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to create user: ' . $e->getMessage());
        }
    }

    public function storeRole(AssignRoleRequest $request)
    {
        try {
            $validated = $request->validated();
            $user = User::findOrFail($validated['user_id']);
            $this->userService->assignRole($user, $validated['role_id']);
            return redirect()->back()->with('success', 'Role assigned successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to assign role: ' . $e->getMessage());
        }
    }

    public function removeRole($id)
    {
        try {
            $this->userService->removeRole($id);
            return redirect()->back()->with('success', 'Role removed successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to remove role: ' . $e->getMessage());
        }
    }

    public function assignTabs(Request $request, $roleUserId)
    {
        $request->validate([
            'sidebar_ids' => 'array',
            'sidebar_ids.*' => 'exists:sidebars,id'
        ]);

        $roleUserPivot = \App\Models\RoleUser::findOrFail($roleUserId);

        $roleUserPivot->sidebars()->delete();
        if ($request->has('sidebar_ids')) {
            foreach ($request->sidebar_ids as $sidebar_id) {
                $roleUserPivot->sidebars()->create(['sidebar_id' => $sidebar_id]);
            }
        }

        return redirect()->back()->with('success', 'Sidebar tabs updated successfully');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $roles = Role::all();
        
        return view('root.users.edit', compact('user', 'roles'));
    }

    public function update(UpdateUserRequest $request, $id)
    {
        try {
            $user = User::findOrFail($id);
            $validated = $request->validated();
            $this->userService->updateUser($user, $validated);
            return redirect()->route('root.users.index')->with('success', 'User updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update user: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
            $this->userService->deleteUser($user);
            return redirect()->route('root.users.index')->with('success', 'User deleted successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete user: ' . $e->getMessage());
        }
    }
}
