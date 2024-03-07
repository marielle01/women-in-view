<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Spatie\Permission\Models\Role;

/**
 * @method sendResponse(string $string)
 */
class PermissionRoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function createRole(String $role): JsonResponse
    {
        $roleCreated = Role::create(['name' => $role]);
        return $this->sendResponse("Role $roleCreated created");
    }
}
