<?php

namespace App\Policies\Api\V1;

use App\Models\Api\V1\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;
use Spatie\Permission\Traits\HasPermissions;

class UserPolicy
{
    use HasPermissions;
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $this->hasPermissionTo('index_users');
        //return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, User $model): bool
    {
        return $this->hasPermissionTo('view_users');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        //return $this->hasPermissionTo('create_users');
        /*if($user->can('create_users')){
            return true;
        }*/
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, User $model): bool
    {
        return $this->hasPermissionTo('update_users');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, User $model): bool
    {
        return $this->hasPermissionTo('delete_users');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, User $model): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, User $model): bool
    {
        //
    }
}
