<?php

namespace App\Services\Api\V1;

use App\Models\Api\V1\Movie;
use Illuminate\Support\Facades\DB;

/**
 * Class movieService.
 */
class MovieService
{
    public function create(array $data): Movie
    {
        return DB::transaction(function () use ($data) {

            $movie = new Movie();

            $movie->fill($data);

            $movie->save();

            return $movie;

        });

    }

    public function update(array $data, Movie $movie): Movie
    {
        $movie->fill($data);

        $movie->save();

        return $movie;
    }
}

