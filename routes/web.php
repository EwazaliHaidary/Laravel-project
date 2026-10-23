<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactController;


Route::get("/contact", [ContactController::class, 'index']);
    

Route::get('/ping', function () {
    return response()->json(['pong' => true, 'time' => now()]);
});




