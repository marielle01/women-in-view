<?php

namespace App\Policies\Api\V1;

use App\Models\Api\V1\Movie;
use App\Models\Api\V1\User;
use Illuminate\Auth\Access\Response;
use Spatie\Permission\Traits\HasPermissions;

class MoviePolicy
{
    use HasPermissions;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        //return true;
        return $this->hasPermissionTo('index_movies');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Movie $movie): bool
    {
        return $this->hasPermissionTo('view_movies');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        //return true;
        return $this->hasPermissionTo('create_movies');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Movie $movie): bool
    {
        return $this->hasPermissionTo('update_movies');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Movie $movie): bool
    {
        return $this->hasPermissionTo('delete_movies');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Movie $movie): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Movie $movie): bool
    {
        //
    }
}
