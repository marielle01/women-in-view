<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\AuthWivController;

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
/*Route::post('/register', [AuthController::class, 'createUser']);
Route::post('/login', [AuthController::class, 'loginUser']);*/

Route::post('/register', [AuthController::class, 'createUser'])
    ->middleware('guest');

Route::post('/login', [AuthController::class, 'loginUser']);

// Public routes
Route::post('/login2', [AuthWivController::class, 'login']);
Route::post('/register2', [AuthWivController::class, 'register']);

// Protected routes
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/logout2', [AuthWivController::class, 'logout']);
    //Route::resources('/', [Controller::class, '']);
});
