<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Repositories\Api\V1\PermissionRoleRepository;
use Illuminate\Http\JsonResponse;
use App\Models\Api\V1\Role;

/**
 * @method sendResponse(string $string)
 */
class PermissionRoleController extends BaseController
{
    public function __construct(protected PermissionRoleRepository $permissionRoleRepository)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function createRole(String $role): JsonResponse
    {
        $roleCreated = $this->permissionRoleRepository->create(['name' => $role]);
        //$roleCreated = $this->permissionRoleRepository->create($request->validated());
        return $this->sendResponse("Role $roleCreated created");
    }
}
