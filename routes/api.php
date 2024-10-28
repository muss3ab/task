<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\StatsController;
use App\Http\Controllers\TagController;
// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');
Route::get('test', function () {
    return 'Hello World';
});
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::post('verify', [AuthController::class, 'verify']);


Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('tags', TagController::class);
    Route::apiResource('posts', PostController::class);
    Route::get('posts/deleted/list', [PostController::class, 'deleted']);
    Route::post('posts/{id}/restore', [PostController::class, 'restore']);
    Route::get('stats', [StatsController::class, 'index']);
});


