<?php

namespace Database\Factories\Api\V1;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TvMovie>
 */
class MovieFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'tmdb_id' => fake()->numberBetween(),
            'original_title' => fake()->sentence(2),
            'poster_path' => '/oYuLEt3zVCKq57qu2F8dT7NIa6f.jpg',
            'backdrop_path' => '/JeGkRdNsOuMrgwBdtB0hp763MU.jpg',
            'overview' => fake()->text(30),
            'release_date' => fake()->date(),
            'rating' => fake()->numberBetween(1, 3),
            'user_id' => 1,
        ];
    }
}
