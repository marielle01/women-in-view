<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;

trait HasPermissions
{
    public function can(string $permission): bool
    {
        if (! $user = Auth::user()) {
            return false;
        }

        return in_array($permission, $user->getAllowedPermissions($permission), true);
    }
}
