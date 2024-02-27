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
            'tmdb_id' => 915935,
            'original_title'=> 'Anatomie d\'une chute',
            'poster_path'=> '/kQs6keheMwCxJxrzV83VUwFtHkB.jpg',
            'backdrop_path'=> '/fGe1ej335XbqN1j9teoDpofpbLX.jpg',
            'overview'=> 'A woman is suspected of her husband’s murder, and their blind son faces a moral dilemma as the sole witness.',
            'release_date'=> '2023-08-23',
            'rating'=> 3,
            'user_id'=> 1,
            'created_at'=> now(),
            'updated_at'=> now(),
        ];
    }
}
