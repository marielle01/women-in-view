<?php

namespace App\Repositories\Api\V1;

use App\Models\Api\V1\User;

class UserRepository
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
