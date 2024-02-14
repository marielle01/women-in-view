<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Api\V1\StoreUserRequest;
use App\Http\Resources\Api\V1\UserResource;
use App\Models\Api\V1\User;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;

class UserController extends BaseController
{
    public function __construct(protected UserService $userService)
    {
        $this->authorizeResource(User::class, 'user');

    }
    /**
     * Display a listing of the resource.
     */
    public function index(): \Illuminate\Database\Eloquent\Collection
    {
        /*$users = UserResource::collection(User::all());
        return $this->sendResponse($users);*/
        return User::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request): JsonResponse
    {
        // Create a new user using the UserService.
        $user = $this->userService->create($request->validated());
        // Return the response with the created user resource.
        return $this->sendResponse(new UserResource($user));
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user): JsonResponse
    {
        // Return the response with the specified user resource.
        return $this->sendResponse(new UserResource($user), 'User retrieved successfully.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreUserRequest $request, User $user): JsonResponse
    {
        // Update the user using the UserService.
        $this->userService->update($request->validated(), $user);
        // Return the response with the updated user.
        return $this->sendResponse($user, 'User updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user): JsonResponse
    {
        // Delete the user from the database.
        $user->delete();
        // Return the response indicating that the user has been deleted.
        return $this->sendResponse('user deleted successfully');
    }

}
