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

    /**
     * @param string $movie
     * @param int $perPage
     * @return JsonResponse
     */


   /* public function searchMovie(string $movie, int $perPage = 12): JsonResponse
    {
        // search all movies whose original title starts with that match with wildcard character
        $search1 = Movie::where('original_title','like','%'.$movie)
            //->paginate($perPage)
            ->paginate(2)
            ->keyBy('tmdb_id');

        // search all movies whose original title ends with that match with wildcard character
        $search2 = Movie::where('original_title','like',$movie.'%')
            //->paginate($perPage)
            ->paginate(2)
            ->keyBy('tmdb_id');

        // search all movies whose original title contains that match with wildcard character
        $search3 = Movie::where('original_title','like','%'.$movie.'%')
            //->paginate($perPage)
            ->paginate(2)
            ->keyBy('tmdb_id');

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

        // return paginated results
        //return $this->sendResponse($search->forPage(request()->input('page', 1), $perPage));
        return $this->sendResponse($search);
    }*/

    public function searchMovie(string $movie, int $perPage = 5): JsonResponse
    {
        // search all movies whose original title starts with that match with wildcard character
        $search1 = Movie::where('original_title','like','%'.$movie)
            ->paginate($perPage)
            ->keyBy('tmdb_id');

        // search all movies whose original title ends with that match with wildcard character
        $search2 = Movie::where('original_title','like',$movie.'%')
            ->paginate($perPage)
            ->keyBy('tmdb_id');

        // search all movies whose original title contains that match with wildcard character
        $search3 = Movie::where('original_title','like','%'.$movie.'%')
            ->paginate($perPage)
            ->keyBy('tmdb_id');

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

        // get the current page
        $currentPage = request()->input('page', 1);

        // slice the collection to get the items for the current page
        $items = $search->slice(($currentPage - 1) * $perPage, $perPage)->values();

        // get the total number of pages
        $totalPages = ceil(count($search) / $perPage);

        // return the items, current page, total pages, and total results
        return response()->json([
            'data' => $items,
            'meta' => [
                'current_page' => $currentPage,
                'total_pages' => $totalPages,
                'total_results' => count($search)
            ]
        ]);
    }



    // movies linked to a user
    public function getUserMovies($userId): JsonResponse
    {
        $movies = Movie::where('user_id', $userId)->get();
        return response()->json($movies);
    }

}
