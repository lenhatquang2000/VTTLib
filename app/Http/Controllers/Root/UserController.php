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

    /**
     * Identity Management - Main Users Table
     */
    public function index(Request $request)
    {
        $search = $request->query('search');
        $roleId = $request->query('role_id');
        $perPage = $request->query('per_page', 10);

        $users = $this->userService->getUsersForRoot($search, $roleId, $perPage);
        $roles = Role::all();
        $stats = $this->userService->getUserStats();

        return view('root.users.index', compact('users', 'roles', 'search', 'roleId', 'perPage', 'stats'));
    }

    /**
     * Privilege Management - Role User Pivot Table
     */
    public function privileges(Request $request)
    {
        $search = $request->query('search');
        $roleId = $request->query('role_id');
        $perPage = $request->query('per_page', 10);

        $roleUsers = $this->userService->getUsersWithFilters($search, $roleId, $perPage);
        $roles = Role::all();
        $sidebars = Sidebar::whereNull('parent_id')->with('children')->orderBy('order')->get();
        $stats = $this->userService->getUserStats();

        return view('root.users.privileges', compact('roleUsers', 'roles', 'sidebars', 'search', 'roleId', 'perPage', 'stats'));
    }

    public function store(StoreUserRequest $request)
    {
        try {
            $validated = $request->validated();
            $maxIdSent = $request->input('max_id');
            $currentMaxId = User::max('id') ?? 0;

            if ($maxIdSent !== null && (int)$maxIdSent !== (int)$currentMaxId) {
                return redirect()->back()->with('error', 'Cơ sở dữ liệu đã có sự thay đổi (Max ID mismatch). Vui lòng thực hiện lại thao tác khởi tạo.');
            }

            $this->userService->createUser($validated, $validated['role_id']);
            return redirect()->back()->with('success', 'User and initial role created successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to create user: ' . $e->getMessage());
        }
    }

    public function checkUsername(Request $request)
    {
        $username = $request->query('username');
        $result = $this->userService->checkUsername($username);
        return response()->json($result);
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

    public function syncTabs($roleUserId)
    {
        try {
            $roleUserPivot = \App\Models\RoleUser::findOrFail($roleUserId);
            $this->userService->syncRoleSidebars($roleUserPivot->user_id, $roleUserPivot->role_id);
            return redirect()->back()->with('success', 'Sidebar tabs synced from role template successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to sync tabs: ' . $e->getMessage());
        }
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
