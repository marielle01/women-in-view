<?php

namespace App\Models\Api\V1;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Api\V1\Role;


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];


    /**
     * @return HasMany
     */
    public function movie(): HasMany
    {
        return $this->hasMany(Movie::class);
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class)
            ->with('permissions', function ($item) {
                return $item->get(['id', 'name']);
            });
    }

    public function getAllowedPermissions($permissionName): array
    {
        $allowedPermissions = [];

        $role = $this->role()->first();

        if ($role === null) {
            return $allowedPermissions;
        }
        foreach ($role->permissions()->get() as $permission) {
            $allowedPermissions[$permission->name] = $permission->name;
        }
        return $allowedPermissions;
    }

}
