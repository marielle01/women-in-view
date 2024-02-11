<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\V1\PermissionController;
use App\Http\Controllers\Api\V1\RoleController;
use App\Http\Controllers\Api\V1\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\MovieReviewController;
use App\Http\Controllers\Api\V1\ReviewController;

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

Route::get('user', [AuthController::class, 'user']);

// Authentication
Route::post('/register', [AuthController::class, 'createUser'])->middleware('guest');
Route::post('/login', [AuthController::class, 'loginUser']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

// Password Routes
Route::post('/users/forgot-password', [UserController::class, 'forgotPassword']);
Route::post('/users/reset-password', [UserController::class, 'resetPassword']);
Route::post('/users/change-password', [UserController::class, 'changePassword'])
    ->middleware('auth:sanctum');


// Roles and Permissions
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function() {
    Route::get('/admin', [UserController::class, 'index']);
    Route::resource('/roles', RoleController::class);
    Route::resource('/permissions', PermissionController::class);
});


// CRUD
Route::resources([
    'users' => UserController::class,
    'movies' => MovieReviewController::class,
    'review' => ReviewController::class,
]);
