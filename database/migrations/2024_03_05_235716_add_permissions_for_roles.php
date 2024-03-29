<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $adminRoleId = 1; // ID du rôle admin
        $subscriberRoleId = 2; // ID du rôle subscriber

        $newPermissionsAdmin = [
            ['name' => 'viewAnyMovies'],
            ['name' => 'viewMovies'],
            ['name' => 'createMovies'],
            ['name' => 'updateMovies'],
            ['name' => 'deleteMovies'],
            ['name' => 'viewAnyUsers'],
            ['name' => 'viewUsers'],
            ['name' => 'createUsers'],
            ['name' => 'updateUsers'],
            ['name' => 'deleteUsers'],
        ];

        foreach ($newPermissionsAdmin as $permission) {
            $id = DB::table('permissions')->insertGetId($permission);
            DB::table('permission_role')->insert(['permission_id' => $id, 'role_id' => $adminRoleId]);

        }

        $newPermissionsSubscriber = [
            ['name' => 'viewAnyMovies'],
            ['name' => 'viewMovies'],
            ['name' => 'createMovies'],
            ['name' => 'updateMovies'],
            ['name' => 'deleteMovies'],
            ['name' => 'viewUsers'],
            ['name' => 'updateUsers'],
            ['name' => 'deleteUsers'],
        ];

        foreach ($newPermissionsSubscriber as $permission) {
            $id = DB::table('permissions')->insertGetId($permission);
            DB::table('permission_role')->insert(['permission_id' => $id, 'role_id' => $subscriberRoleId]);

        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('permissions')->whereIn('name', [
            'viewAnyMovies',
            'viewMovies',
            'createMovies',
            'updateMovies',
            'deleteMovies',
            'viewAnyUsers',
            'viewUsers',
            'createUsers',
            'updateUsers',
            'deleteUsers',
        ])->delete();
    }
};
