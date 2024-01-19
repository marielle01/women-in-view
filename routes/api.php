<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\V1\PermissionController;
use App\Http\Controllers\Api\V1\RoleController;
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


Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function() {
    Route::get('/admin', [UserController::class, 'index']);
    Route::resource('/roles', RoleController::class);
    Route::resource('/permissions', PermissionController::class);
});

// Password Routes
Route::post('/users/forgot-password', [UserController::class, 'forgotPassword']);
Route::post('/users/reset-password', [UserController::class, 'resetPassword']);

Route::post('/users/change-password', [UserController::class, 'changePassword'])
    ->middleware('auth:sanctum');


Route::resources([
    'users' => UserController::class,
]);


/*Route::middleware('auth')->group(function (){
    //Route::resources('users' => UserController::class);
    Route::get('users', [UserController::class, 'index']);
    Route::post('users', [UserController::class, 'store']);
    Route::get('users/{id}', [UserController::class, 'show']);
    Route::put('users/{id}', [UserController::class, 'update']);
    Route::delete('users/{id}', [UserController::class, 'destroy']);
});*/
