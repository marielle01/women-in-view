<?php

namespace Tests;

use App\Models\Api\V1\Permission;
use App\Models\Api\V1\Role;
use App\Models\Api\V1\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;


    protected User $user;
    protected Role $userRole;

    public function __construct(string $name)
    {
        parent::__construct($name);
    }

    protected function setUserPermissions(array $permissions): void
    {
        $this->user = User::factory()->create();
        $this->userRole = Role::factory()->create();
        $this->userRole->permissions()->attach(
            Permission::whereIn('name', $permissions)->pluck('id')
        );
        $this->user->role()->associate($this->userRole->id);
    }
}
