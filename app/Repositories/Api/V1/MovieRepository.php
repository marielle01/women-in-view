<?php

namespace App\Repositories\Api\V1;

use App\Models\Api\V1\Movie;

class MovieRepository
{
    public function create(array $data): Movie
    {
        $movie = new Movie();

        $movie->fill($data);

        $movie->save();

        return $movie;
    }

    public function update(array $data, Movie $movie): Movie
    {
        $movie->fill($data);

        $movie->save();

        return $movie;
    }
}
