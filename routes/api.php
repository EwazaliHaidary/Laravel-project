<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\BorrowingController;
use App\Http\Controllers\UserController;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {

    // Public routes: no token required
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    // Protected routes: token required
    Route::middleware('auth:sanctum')->group(function () {

        // User routes
        Route::get('/me', function (Request $request) {
            return new UserResource($request->user());
        });

        Route::post('/me/avatar', [UserController::class, 'uploadAvatar']);
        Route::post('/logout', [AuthController::class, 'logout']);

        // Library resources
        Route::apiResource('authors', AuthorController::class);
        Route::apiResource('books', BookController::class);
        Route::apiResource('categories', CategoryController::class);
        Route::apiResource('borrowings', BorrowingController::class);
    });
});
