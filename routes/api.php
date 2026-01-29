<?php

use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
 


Route::prefix('auth')->name('auth.')->namespace('Auth')->group(function () {
    Route::post('/login', [LoginController::class, 'login']);
});

Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth:sanctum');

Route::prefix('admin')->name('admin.')->middleware(['auth:sanctum'])->group(function () {
    Route::post('/youtube_detail',[PostController::class,'ytdetails']);

    Route::prefix('posts')->name('posts.')->group(function () { 
        Route::post('/save_youtube_post',[PostController::class,'createPost']);
        Route::get('/', [PostController::class, 'index']); 
        Route::put('/save_youtube_post/{id}', [PostController::class, 'update']);
        Route::delete('/posts/{id}', [PostController::class, 'destroy']);
    }); 
});