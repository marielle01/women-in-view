<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Api\V1\StoreUserRequest;
use App\Http\Requests\Api\V1\UpdateUserRequest;
use App\Http\Resources\Api\V1\UserResource;
use App\Models\Api\V1\User;
use App\Repositories\Api\V1\UserRepository;
use Illuminate\Http\JsonResponse;
use Spatie\QueryBuilder\QueryBuilder;

class UserController extends BaseController
{
    public function __construct(
        protected UserRepository $userRepository
    )
    {
        $this->authorizeResource(User::class, 'user');
    }
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $users = QueryBuilder::for(User::class)
            ->orderByDesc('updated_at')
            ->paginate(12)
            ->appends(request()->query());

        return  $this->sendResponse(UserResource::collection($users));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request): JsonResponse
    {
        // Create a new user using the UserRepository.
        $user = $this->userRepository->create($request->validated());
        // Return the response with the created user resource.
        return $this->sendResponse(new UserResource($user), 'User added successfully.');
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
    public function update(UpdateUserRequest $request, User $user): JsonResponse
    {
        // Update the user using the UserRepository.
        $this->userRepository->update($request->validated(), $user);
        return $this->sendResponse(new UserResource($user), 'User updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user): JsonResponse
    {
        $user->delete();
        // Return the response indicating that the user has been deleted.
        return $this->sendResponse('user deleted successfully');
    }

}
