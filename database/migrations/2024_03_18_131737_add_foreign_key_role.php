<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use App\Models\Api\V1\Role;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function ($table) {
            $table->foreignIdFor(Role::class)->nullable()->constrained()->cascadeOnDelete();
        });


        DB::table('users')->insert(
            [
                [
                    'email' => config('app.user_email'),
                    'password' => Hash::make(config('app.user_password')),
                    'name' => config('app.user_name'),
                    'created_at' => now(),
                    'updated_at' => now(),
                    'role_id' => config('app.user_role'),
                ],
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function ($table) {
            $table->dropForeign(['role_id']);
            $table->dropColumn('role_id');
        });
    }
};
