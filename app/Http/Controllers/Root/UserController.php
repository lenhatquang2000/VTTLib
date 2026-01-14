<?php

namespace App\Http\Controllers\Root;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\Sidebar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $perPage = $request->query('per_page', 10);

        $roleUsers = \App\Models\RoleUser::with(['user', 'role', 'sidebars.sidebar'])
            ->when($search, function ($query) use ($search) {
                $query->whereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                })->orWhereHas('role', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                });
            })
            ->paginate($perPage)
            ->withQueryString();

        $users = User::all();
        $roles = Role::all();
        $sidebars = Sidebar::whereNull('parent_id')->with('children')->orderBy('order')->get();

        return view('root.users.index', compact('roleUsers', 'users', 'roles', 'sidebars', 'search', 'perPage'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'role_id' => 'required|exists:roles,id'
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        $user->roles()->attach($validated['role_id']);

        return redirect()->back()->with('success', 'User and initial role created successfully');
    }

    public function storeRole(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'role_id' => 'required|exists:roles,id'
        ]);

        $user = User::findOrFail($validated['user_id']);

        if ($user->roles()->where('role_id', $validated['role_id'])->exists()) {
            return redirect()->back()->with('error', 'User already has this role');
        }

        $user->roles()->attach($validated['role_id']);

        return redirect()->back()->with('success', 'Role assigned successfully');
    }

    public function removeRole($id)
    {
        $roleUser = \App\Models\RoleUser::findOrFail($id);

        if ($roleUser->role->name === 'root') {
            return redirect()->back()->with('error', 'Cannot remove root role');
        }

        $roleUser->delete();
        return redirect()->back()->with('success', 'Role removed successfully');
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
}
