<?php

namespace Database\Seeders;

use App\Enums\RoleEnum;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       /* // roles creation
        foreach (RoleEnum::cases() as $roleEnum) {
            Role::create([
                'name' => $roleEnum->value,
            ]);
        }

        // permission group in wildCard
        (Permission::create([
            'name' => 'user-cards.*'
        ]))->assignRole(
            Role::firstWhere('name', RoleEnum::EDITOR->value),
        );

        (Permission::create([
            'name' => 'admin-cards.*'
        ]))->assignRole(
            Role::firstWhere('name', RoleEnum::ADMIN->value),
        );*/

        Role::create(['name' => 'admin']);
        Role::create(['name' => 'writer']);
        Role::create(['name' => 'user']);
    }
}
