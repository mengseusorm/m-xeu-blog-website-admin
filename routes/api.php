<?php

use App\Http\Controllers\Api\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// routes/api.php
Route::post('/youtube_detail',[PostController::class,'ytdetails']);
Route::post('/save_youtube_post',[PostController::class,'createPost']);
Route::get('/posts', [PostController::class, 'index']); 
Route::put('/save_youtube_post/{id}', [PostController::class, 'update']);
Route::delete('/posts/{id}', [PostController::class, 'destroy']);