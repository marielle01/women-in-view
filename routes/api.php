<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\V1\UserController;
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

/*Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});*/

Route::get('user', [AuthController::class, 'user']);


Route::post('/register', [AuthController::class, 'createUser'])->middleware('guest');
Route::post('/login', [AuthController::class, 'loginUser']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');


// Protected routes
/*Route::group(['middleware' => ['auth:sanctum']], function () {
    //Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
});*/

// Resources routes
Route::resources([
    'users' => UserController::class,
]);
