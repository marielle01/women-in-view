<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\MovieController;
use App\Http\Controllers\Api\V1\UserController;
use App\Http\Controllers\Api\V1\PermissionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


// Authentication
Route::post('/register', [AuthController::class, 'register'])->middleware('guest');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

// Password Routes
Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);
Route::post('/change-password', [AuthController::class, 'changePassword'])->middleware('auth:sanctum');


// CRUD
Route::middleware(['auth:sanctum'])->group(function() {
    Route::resources([
        'users' => UserController::class,
        'movies' => MovieController::class,
        'permissions' => PermissionController::class,
    ]);
});

/*Route::group(['middleware' => ['role:subscriber'], ['auth:sanctum']],function() {
    Route::resources([
        'users' => UserController::class,
        'movies' => MovieController::class,
    ]);
});*/

/*Route::group(['middleware' => ['auth:sanctum', 'role:admin']],function() {
    Route::resources([
        'movies' => MovieController::class,
        'users' => UserController::class,
    ]);
});

Route::group(['middleware' => ['auth:sanctum', 'role:admin|subscribe']],function() {
    Route::resources([
        'movies' => MovieController::class,
        'users' => UserController::class,
    ]);
});*/


// Search Route
Route::get('/search-movies', [MovieController::class, 'searchMovie'])->middleware('auth:sanctum');
Route::get('/search-moviesTmbd', [MovieController::class, 'getSearchMovies']);

// Dashboard
Route::get('user-movies/{user_id}', [MovieController::class, 'getUserMovies']);

Route::post('/db-seed-movies', [MovieController::class, 'dbSeedMovie']);
Route::get('/popular-movies', [MovieController::class, 'getPopularMovies']);
