<?php

namespace App\Observers;

use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class UserObserver
{
    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        if (Auth::check()) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => 'user_created',
                'model_type' => User::class,
                'model_id' => $user->id,
                'details' => [
                    'name' => $user->name,
                    'email' => $user->email,
                    'created_at' => $user->created_at,
                ],
                'ip_address' => request()->ip(),
            ]);
        }
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        if (Auth::check()) {
            $changes = [];
            
            // Track specific changes
            if ($user->wasChanged('name')) {
                $changes['name'] = [
                    'old' => $user->getOriginal('name'),
                    'new' => $user->name,
                ];
            }
            
            if ($user->wasChanged('email')) {
                $changes['email'] = [
                    'old' => $user->getOriginal('email'),
                    'new' => $user->email,
                ];
            }
            
            if ($user->wasChanged('password')) {
                $changes['password'] = 'changed';
            }

            if (!empty($changes)) {
                ActivityLog::create([
                    'user_id' => Auth::id(),
                    'action' => 'user_updated',
                    'model_type' => User::class,
                    'model_id' => $user->id,
                    'details' => $changes,
                    'ip_address' => request()->ip(),
                ]);
            }
        }
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        if (Auth::check()) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => 'user_deleted',
                'model_type' => User::class,
                'model_id' => $user->id,
                'details' => [
                    'name' => $user->name,
                    'email' => $user->email,
                    'deleted_at' => now(),
                ],
                'ip_address' => request()->ip(),
            ]);
        }
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        if (Auth::check()) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => 'user_restored',
                'model_type' => User::class,
                'model_id' => $user->id,
                'details' => [
                    'name' => $user->name,
                    'email' => $user->email,
                    'restored_at' => now(),
                ],
                'ip_address' => request()->ip(),
            ]);
        }
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
        if (Auth::check()) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => 'user_force_deleted',
                'model_type' => User::class,
                'model_id' => $user->id,
                'details' => [
                    'name' => $user->name,
                    'email' => $user->email,
                    'force_deleted_at' => now(),
                ],
                'ip_address' => request()->ip(),
            ]);
        }
    }
}
