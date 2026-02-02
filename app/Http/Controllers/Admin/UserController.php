<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $activeRole = $request->query('role', 'admin'); // Default to admin or first available role
        
        $roles = Role::all();
        
        $query = User::with('roles')
            ->when($activeRole && $activeRole !== 'all', function($q) use ($activeRole) {
                return $q->whereHas('roles', function($roleQuery) use ($activeRole) {
                    $roleQuery->where('name', $activeRole);
                });
            })
            ->when($search, function($q, $search) {
                return $q->where(function($subQuery) use ($search) {
                    $subQuery->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            });

        $users = $query->latest()
            ->paginate(15)
            ->withQueryString();

        $stats = [
            'total' => User::count(),
            'active' => User::where('status', 'active')->count(),
            'new' => User::where('created_at', '>=', now()->subDays(7))->count(),
        ];

        return view('admin.users.index', compact('users', 'search', 'roles', 'activeRole', 'stats'));
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'status' => 'required|in:active,inactive,suspended',
            'roles' => 'array',
            'roles.*' => 'exists:roles,id',
        ]);

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'status' => $validated['status'],
        ]);

        if (isset($validated['roles'])) {
            $user->roles()->sync($validated['roles']);
        }

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully');
    }
}
