<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreMovieRequest;
use App\Http\Resources\Api\V1\MovieResource;
use App\Models\Api\V1\Movie;
use App\Models\Api\V1\User;
use App\Repositories\Api\V1\MovieRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class MovieController extends BaseController
{
    public function __construct(protected MovieRepository $movieRepository)
    {
        $this->authorizeResource(Movie::class, 'movie');

    }

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $movies = MovieResource::collection(Movie::all());
        return $this->sendResponse($movies);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMovieRequest $request): JsonResponse
    {
        $movie = $this->movieRepository->create($request->validated());
        return $this->sendResponse(new MovieResource($movie));
    }

    /**
     * Display the specified resource.
     */
    public function show(Movie $movie): JsonResponse
    {
        return $this->sendResponse(new MovieResource($movie), 'Movie retrieved successfully.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Movie $movie)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Movie $movie): JsonResponse
    {
        $movie->delete();
        return $this->sendResponse('Movie deleted successfully');
    }

    public function dbSeedMovie(): JsonResponse
    {
        $movies = Http::withToken(
            config('services.tmdb.token')
        )->get('https://api.themoviedb.org/3/movie/popular')->json();

        foreach ($movies['results'] as $movie) {
            //dd($movie);
        Movie::create([
            'overview'=>$movie['overview'],
            //'imdb_id'=>$movie['imdb_id'],
            'original_title'=>$movie['original_title'],
            'poster_path'=>$movie['poster_path'],
            'release_date'=>$movie['release_date'],
            ]);
        }

        return $this->sendResponse('Movies added');
    }

    public function getPopularMovies(): JsonResponse
    {
        $movies = Http::withToken(
            config('services.tmdb.token')
        )->get('https://api.themoviedb.org/3/movie/popular')->json();
        return $this->sendResponse($movies);
    }
}
