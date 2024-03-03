<?php

namespace App\Repositories\Api\V1;

use App\Models\Api\V1\User;
use Illuminate\Support\Facades\DB;

class UserRepository
{
    public function create(array $data): User
    {
        return DB::transaction(function () use ($data) {

            $user = new User();

            $user->fill($data);

            $user-> save();

            return $user;

        });
    }

    public function update(array $data, User $user): User
    {
        $user->fill($data);

        $user->save();

        return $user;
    }
}
