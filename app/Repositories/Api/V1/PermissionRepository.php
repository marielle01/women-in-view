<?php

namespace App\Repositories\Api\V1;


use Spatie\Permission\Models\Permission;

class PermissionRepository
{
    public function create(array $data): Permission
    {
        $permission = new Permission();

        $permission->fill($data);

        $permission->save();

        return $permission;
    }
}
