<?php

namespace App\Services;

use App\Models\Api\V1\User;

/**
 * Class UserService.
 */
class UserService
{
    public function create(array $data): User
    {
        $user = new User();

        $user->fill($data);

        $user->save();

        return $user;
    }

    public function update(array $data, User $user): User
    {
        $user->fill($data);

        $user->save();

        return $user;
    }

}
