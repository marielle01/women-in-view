<?php

namespace App\Repositories\Api\V1;

use App\Models\Api\V1\Movie;
use App\Models\Api\V1\Permission;
use App\Models\Api\V1\Role;
use App\Models\Api\V1\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PermissionRoleRepository
{
    public function create(array $data): Role
    {
        $role = new Role();

        $role->fill($data);

        $role->save();

        return $role;
    }
}
