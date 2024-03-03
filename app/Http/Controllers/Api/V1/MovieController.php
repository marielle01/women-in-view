<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Api\V1\StoreMovieRequest;
use App\Http\Requests\Api\V1\UpdateMovieRequest;
use App\Http\Resources\Api\V1\MovieResource;
use App\Http\Resources\Api\V1\UserMovieResource;
use App\Models\Api\V1\Movie;
use App\Models\Api\V1\User;
use App\Repositories\Api\V1\MovieRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\QueryBuilder\QueryBuilder;

class MovieController extends BaseController
{
    public function __construct(protected MovieRepository $movieRepository)
    {
        //$this->middleware(['role:subscriber', 'permission: index_movies|view_movies|create_movies|update_movies']);
        //$this->middleware(['role:admin']);
        //$this->authorizeResource(Movie::class, 'movie');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $movies = QueryBuilder::for(Movie::class)
            ->orderByDesc('updated_at')
            ->paginate(6);

        return $this->sendResponse(MovieResource::collection($movies));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMovieRequest $request): JsonResponse
    {
        $movie = $this->movieRepository->create($request->validated());

        return $this->sendResponse(new MovieResource($movie), 'Movie added successfully.');
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
    public function update(UpdateMovieRequest $request, Movie $movie): JsonResponse
    {
        $movie = $this->movieRepository->update($request->validated(), $movie);

        return $this->sendResponse($movie, 'Movie updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Movie $movie): JsonResponse
    {
        $movie->delete();
        return $this->sendResponse('Movie deleted successfully');
    }


    public function getUserMovies($userId): JsonResponse
    {
        // Get all movies linked to the specified user
        $movies = Movie::where('user_id', $userId)->get();

        return response()->json($movies);

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

    public function searchMovie(Request $request): JsonResponse
    {
        // search input
        $movie = $request->get('movie_name');

        // search all movies whose original title starts with that match with wildcard character
        $search1 = Movie::where('original_title','like','%'.$movie)->get();
        // search all movies whose original title ends with that match with wildcard character
        $search2 = Movie::where('original_title','like',$movie.'%')->get();
        $search3 = Movie::where('original_title','like','%'.$movie.'%')->get();

        $search =  $search1->merge( $search2, $search3);

        return $this->sendResponse($search);

    }

    public function getSearchMovies(): JsonResponse
    {
        $movies = Http::withToken(
            config('services.tmdb.token')
        )->get('https://api.themoviedb.org/3/search/movie')->json();
        return $this->sendResponse($movies);
    }
}
