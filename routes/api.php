
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\BorrowingController;

Route::prefix('v1')->group(function () {
    Route::apiResource('authors', AuthorController::class);
    Route::apiResource('books', BookController::class);
    Route::apiResource('categories', CategoryController::class);
    Route::apiResource('borrowings', BorrowingController::class);
});
