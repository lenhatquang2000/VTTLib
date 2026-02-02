<?php

namespace App\Services;

use App\Models\User;
use App\Models\Role;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class UserService
{
    /**
     * Create a new user with role assignment
     */
    public function createUser(array $userData, int $roleId): User
    {
        return DB::transaction(function () use ($userData, $roleId) {
            $user = User::create([
                'name' => $userData['name'],
                'email' => $userData['email'],
                'password' => Hash::make($userData['password']),
            ]);

            $user->roles()->attach($roleId);

            // Log activity
            $this->logActivity('user_created', $user, [
                'name' => $user->name,
                'email' => $user->email,
                'role_id' => $roleId
            ]);

            return $user;
        });
    }

    /**
     * Update user information
     */
    public function updateUser(User $user, array $userData): User
    {
        return DB::transaction(function () use ($user, $userData) {
            $oldData = $user->only(['name', 'email']);
            
            $user->update([
                'name' => $userData['name'],
                'email' => $userData['email'],
            ]);

            // Update password if provided
            if (!empty($userData['password'])) {
                $user->update([
                    'password' => Hash::make($userData['password'])
                ]);
                $oldData['password_changed'] = true;
            }

            // Log activity
            $this->logActivity('user_updated', $user, [
                'old_data' => $oldData,
                'new_data' => $userData
            ]);

            return $user;
        });
    }

    /**
     * Delete user with safety checks
     */
    public function deleteUser(User $user): bool
    {
        // Safety checks
        if (Auth::id() == $user->id) {
            throw new \Exception('Cannot delete your own account');
        }

        if ($user->hasRole('root')) {
            throw new \Exception('Cannot delete root user');
        }

        return DB::transaction(function () use ($user) {
            $userData = $user->only(['name', 'email']);
            
            // Detach all roles before deletion
            $user->roles()->detach();
            
            // Log activity before deletion
            $this->logActivity('user_deleted', $user, $userData);
            
            return $user->delete();
        });
    }

    /**
     * Assign role to user
     */
    public function assignRole(User $user, int $roleId): void
    {
        DB::transaction(function () use ($user, $roleId) {
            // Check if user already has this role
            if ($user->roles()->where('role_id', $roleId)->exists()) {
                throw new \Exception('User already has this role');
            }

            $role = Role::findOrFail($roleId);
            $user->roles()->attach($roleId);

            // Log activity
            $this->logActivity('role_assigned', $user, [
                'role_name' => $role->name,
                'role_id' => $roleId
            ]);
        });
    }

    /**
     * Remove role from user
     */
    public function removeRole(int $roleUserId): void
    {
        DB::transaction(function () use ($roleUserId) {
            $roleUser = \App\Models\RoleUser::findOrFail($roleUserId);
            
            // Cannot remove root role
            if ($roleUser->role->name === 'root') {
                throw new \Exception('Cannot remove root role');
            }

            $userData = $roleUser->user->only(['name', 'email']);
            $roleData = $roleUser->role->only(['name', 'display_name']);

            // Log activity before removal
            $this->logActivity('role_removed', $roleUser->user, [
                'user_data' => $userData,
                'role_data' => $roleData
            ]);

            $roleUser->delete();
        });
    }

    /**
     * Get users with search and pagination
     */
    public function getUsersWithFilters(?string $search = null, int $perPage = 10)
    {
        $query = \App\Models\RoleUser::with(['user', 'role', 'sidebars.sidebar']);

        if ($search) {
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            })->orWhereHas('role', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        return $query->paginate($perPage)->withQueryString();
    }

    /**
     * Log user activities
     */
    private function logActivity(string $action, $model, array $details = []): void
    {
        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'model_type' => get_class($model),
            'model_id' => $model->id,
            'details' => $details,
            'ip_address' => request()->ip(),
        ]);
    }

    /**
     * Reset user password
     */
    public function resetPassword(User $user, string $newPassword): void
    {
        DB::transaction(function () use ($user, $newPassword) {
            $user->update([
                'password' => Hash::make($newPassword)
            ]);

            $this->logActivity('password_reset', $user, [
                'email' => $user->email,
                'reset_by' => Auth::user()->name
            ]);
        });
    }
}
