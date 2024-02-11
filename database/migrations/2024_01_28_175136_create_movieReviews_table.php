<?php

use App\Models\Api\V1\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('movieReviews', function (Blueprint $table) {
            $table->id();
            $table->integer('tmdbId')->unique();
            $table->string('title');
            $table->string('posterPath');
            $table->longText('synopsis')->nullable();
            $table->date('year');
            $table->enum('rating', [0, 1, 2, 3])->default(0);
            $table->foreignIdFor(User::class)->nullable()->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movieReviews');
    }
};
