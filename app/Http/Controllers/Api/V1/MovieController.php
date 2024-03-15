<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Api\V1\StoreMovieRequest;
use App\Http\Requests\Api\V1\UpdateMovieRequest;
use App\Http\Resources\Api\V1\MovieResource;
use App\Models\Api\V1\Movie;
use App\Repositories\Api\V1\MovieRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Spatie\QueryBuilder\QueryBuilder;

class MovieController extends BaseController
{
    public function __construct(protected MovieRepository $movieRepository)
    {
        $this->authorizeResource(Movie::class, 'movie');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        $movies = QueryBuilder::for(Movie::class)
            ->orderByDesc('updated_at')
            ->paginate(12)
            ->appends(request()->query());

        return MovieResource::collection($movies);
        //return $this->sendResponse(MovieResource::collection($movies));
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
        return $this->sendResponse('Movie deleted successfully.');
    }

    // search function
    public function searchMovie(Request $request): JsonResponse
    {
        // search input
        $movie = $request->get('movie_name');

        // search all movies whose original title starts with that match with wildcard character
        $search1 = Movie::where('original_title','like','%'.$movie)->get()->keyBy('tmdb_id');

        // search all movies whose original title ends with that match with wildcard character
        $search2 = Movie::where('original_title','like',$movie.'%')->get()->keyBy('tmdb_id');

        // search all movies whose original title contains that match with wildcard character
        $search3 = Movie::where('original_title','like','%'.$movie.'%')->get()->keyBy('tmdb_id');

        // search movies from external API
        $movies = Http::withToken(
            config('services.tmdb.token')
        )->get('https://api.themoviedb.org/3/search/movie?query='.$movie)->json();

        // convert results to a collection and key them by tmdb_id
        $collection = collect($movies['results'])->keyBy('id')->map(function ($item) {
            return array_merge($item, ['tmdb_id' => $item['id']]);
        });

        // merge search results and external API results
        $search = $search1->merge($search2)->merge($search3)->concat($collection);

        // remove duplicates based on tmdb_id
        $search = $search->unique('tmdb_id');

        return $this->sendResponse($search);

    }

    // movies linked to a user
    public function getUserMovies($userId): JsonResponse
    {
        $movies = Movie::where('user_id', $userId)->get();
        return response()->json($movies);
    }



    // tmbd functions
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

    public function getSearchMovies(): JsonResponse
    {
        $movies = Http::withToken(
            config('services.tmdb.token')
        )->get('https://api.themoviedb.org/3/search/movie')->json();
        return $this->sendResponse($movies);
    }


    /*public function getSearchMovies(string $movieName): array
    {
        $response = Http::withToken(config('services.tmdb.token'))
            ->get('https://api.themoviedb.org/3/search/movie', [
                'query' => [
                    'query' => $movieName
                ]
            ])->json();

        return $response['results'];
    }*/

    public function searchMovie2(Request $request): JsonResponse
    {
        // search input
        $movie = $request->get('movie_name');

        // search all movies whose original title starts or ends with or contains the search term
        $searchResults = Movie::where('original_title', 'like', '%' . $movie . '%')->get();

        // If no local results found, search externally
        if ($searchResults->isEmpty()) {
            $externalMovies = $this->getSearchMovies($movie);
            return $this->sendResponse($externalMovies);
        }

        return $this->sendResponse($searchResults);
    }

    public function searchMovie3(Request $request): JsonResponse
    {
        // search input
        $movie = $request->get('movie_name');

        // search all movies whose original title starts or ends with or contains the search term
        $searchResults = Movie::where('original_title', 'like', '%' . $movie . '%')->get();

        // If no local results found, search externally
        if ($searchResults->isEmpty()) {
            $externalMovies = $this->getSearchMovies($movie);
            return $this->sendResponse($externalMovies);
        } else {
            // Merge external results with local results
            $externalMovies = $this->getSearchMovies($movie);
            $searchResults = $searchResults->merge($externalMovies);
        }

        return $this->sendResponse($searchResults);
    }

    public function searchMovie0(Request $request): JsonResponse
    {
        // search input
        $movie = $request->get('movie_name');

        // search all movies whose original title starts with that match with wildcard character
        $search1 = Movie::where('original_title','like','%'.$movie.'%')->get();

        // search movies from external API
        $externalMovies = Http::withToken(
            config('services.tmdb.token')
        )->get('https://api.themoviedb.org/3/search/movie?query='.$movie)->json();

        // merge results
        $search = $search1->merge($externalMovies['results']);

        // convert results to JSON
        $externalMoviesJson = json_encode($externalMovies);

        // return response with merged results and external movies as JSON
        return $this->sendResponse([
            'results' => $search,
            'external_movies' => $externalMoviesJson
        ]);
    }

}
