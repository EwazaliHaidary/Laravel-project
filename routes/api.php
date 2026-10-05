<?php

use App\Http\Controllers\BookController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\BorrowingController;

    Route::apiResource('authors', AuthorController::class);
    Route::apiResource("books",BookController::class);
    Route::apiResource("categories",CategoryController::class);
    Route::apiResource("borrowings",BorrowingController::class);