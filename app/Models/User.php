<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    public function roles()
    {
        return $this->belongsToMany(Role::class)
            ->using(RoleUser::class)
            ->withPivot('id');
    }

    public function getSidebarTabs()
    {
        $roleUserIds = $this->roles->map(fn($role) => $role->pivot->id);

        return Sidebar::whereNull('parent_id')
            ->where(function ($query) use ($roleUserIds) {
                // Return parent if it is directly assigned
                $query->whereHas('userRoleSidebars', function ($q) use ($roleUserIds) {
                    $q->whereIn('role_user_id', $roleUserIds);
                })
                    // OR if any of its children are assigned
                    ->orWhereHas('children', function ($q) use ($roleUserIds) {
                    $q->whereHas('userRoleSidebars', function ($sq) use ($roleUserIds) {
                        $sq->whereIn('role_user_id', $roleUserIds);
                    });
                });
            })
            ->orderBy('order')
            ->get();
    }

    public function hasRole($role)
    {
        return $this->roles()->where('name', $role)->exists();
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
