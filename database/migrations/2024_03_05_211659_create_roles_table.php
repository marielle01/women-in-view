<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        DB::table('roles')->insert([
            ['name' => 'admin'],
            ['name' => 'subscriber'],
        ]);

        Schema::table('users', function ($table) {
            $table->foreignIdFor(\App\Models\Api\V1\Role::class)->nullable()->constrained();
        });

        DB::table('users')->insert(
            [
                [
                    'email' => config('app.user_email'),
                    'password' => Hash::make(config('app.user_password')),
                    'name' => config('app.user_name'),
                    'email_verified_at' => date('Y-m-d h:i:s'),
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
            $table->dropColumn(['role_id']);
        });
        Schema::dropIfExists('roles');
    }
};
