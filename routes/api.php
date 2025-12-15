<?php

use App\Http\Controllers\Api\V1\PostController;
use App\Http\Controllers\Api\V1\StoryController as V1StoryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::get('/hello',function(){
    return ["message" => "Laravel Backend API Development!"];
});


// Route::get('/posts',[PostController::class,'index'])->name('posts.index');
// Route::post('/posts',[PostController::class,'store'])->name('posts.store');
// Route::get('/posts/{id}',[PostController::class,'show'])->name('posts.show');



Route::middleware(['auth:sanctum','throttle:api'])->prefix('v1')->group(function(){
    Route::apiResource('/stories',V1StoryController::class);
    Route::apiResource('/posts',PostController::class);
});


require __DIR__.'/auth.php';
