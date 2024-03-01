<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StorePermissionRequest;
use App\Repositories\Api\V1\PermissionRepository;

class PermissionController extends Controller
{
    public function __construct(protected PermissionRepository $permissionRepository)
    {

    }
    public function index(): \Illuminate\Database\Eloquent\Collection
    {
        return \Spatie\Permission\Models\Permission::all();
    }

    public function store(StorePermissionRequest $request)
    {
        $permission = $this->permissionRepository->create($request->validated());

        return $this->sendResponse(new PermissionResource($permission), 'Permission added successfully.');
    }

    public function show()
    {

    }

    public function update()
    {

    }

    public function delete()
    {

    }

}
