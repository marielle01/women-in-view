<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\MovieReviewResource;
use App\Models\Api\V1\MovieReview;
use App\Models\Api\V1\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class MovieReviewController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $movies = Http::withToken(
            config('services.tmdb.token')
        )->get('https://api.themoviedb.org/3/movie/popular')->json();
        //$movies = MovieReviewResource::collection(MovieReview::all());
        //dd($movies);
        return $this->sendResponse($movies);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(MovieReview $movie)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MovieReview $movie)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MovieReview $movie)
    {
        //
    }
}
