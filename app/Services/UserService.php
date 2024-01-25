<?php

namespace App\Services;

use App\Models\User;

/**
 * Class UserService.
 */
class UserService
{
    public function create(array $data): User
    {
        //dd($data);
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
