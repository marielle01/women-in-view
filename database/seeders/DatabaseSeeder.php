<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Enums\RoleEnum;
use App\Models\TvMovie;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(class: RoleSeeder::class);

        // creation Users
        $userRole = Role::firstWhere('name', RoleEnum::USER->value);

        User::factory(5)
            ->create()
            ->each(
                fn(User $user) => $user->assignRole($userRole)
            );


        // creation Super Admin
        User::factory()->create([
             'name' => 'Super Admin',
             'email' => 'superAdmin@test.com',
        ])->assignRole(Role::firstWhere('name', RoleEnum::SUPER_ADMIN->value));


        // creation Admins
        $adminRole = Role::firstWhere('name', RoleEnum::ADMIN->value);

        User::factory(5)
            ->create()
            ->each(
                fn(User $user) => $user->assignRole($adminRole)
            );

        //TvMovie::factory(5)->create();
    }
}
