<?php

namespace Database\Seeders;

use App\Enums\RoleEnum;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
       // Reset cache roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        Permission::create(['name' => 'index_movies', 'guard_name' => 'api']);
        Permission::create(['name' => 'view_movies', 'guard_name' => 'api']);
        Permission::create(['name' => 'create_movies', 'guard_name' => 'api']);
        Permission::create(['name' => 'update_movies', 'guard_name' => 'api']);
        Permission::create(['name' => 'delete_movies', 'guard_name' => 'api']);

        Permission::create(['name' => 'index_users', 'guard_name' => 'api']);
        Permission::create(['name' => 'view_users', 'guard_name' => 'api']);
        Permission::create(['name' => 'create_users', 'guard_name' => 'api']);
        Permission::create(['name' => 'update_users', 'guard_name' => 'api']);
        Permission::create(['name' => 'delete_users', 'guard_name' => 'api']);

// create roles and assign created permissions
        $roleAdmin = Role::create(['name' => 'admin', 'guard_name' => 'api']);
        $roleAdmin->givePermissionTo([
            'index_movies',
            'view_movies',
            'create_movies',
            'update_movies',
            'delete_movies',
            'index_users',
            'view_users',
            'create_users',
            'update_users',
            'delete_users',
        ]);

        $roleSubscriber = Role::create(['name' => 'subscriber', 'guard_name' => 'api']);
        $roleSubscriber->givePermissionTo([
            'index_movies',
            'view_movies',
            'create_movies',
            'update_movies',
        ]);

    }
}
