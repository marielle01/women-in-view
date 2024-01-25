<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\TvMovie;
use App\Models\User;
use Illuminate\Database\Seeder;
use App\Enums\RoleEnum;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        //User::factory(5)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        // TvMovie::factory(5)->create();

        /*$this->call(class: RoleSeeder::class);

        // creation Editor
        $userRole = Role::firstWhere('name', RoleEnum::EDITOR->value);

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

        User::factory(2)
            ->create()
            ->each(
                fn(User $user) => $user->assignRole($adminRole)
            );*/

        $this->call(RoleSeeder::class);
        $this->call(AdminSeeder::class);

    }
}
