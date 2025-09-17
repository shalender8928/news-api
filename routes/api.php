<?php

use Illuminate\Http\Request;
use App\Http\Controllers\API\V1\ArticleController;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::get('/ping', function () {
    return response()->json(['message' => 'API is working']);
});
Route::prefix('v1')->group(function () {
Route::get('/articles', [ArticleController::class, 'index'])->name('articles.index');
Route::get('/articles/{id}', [ArticleController::class, 'show'])->name('articles.show');
Route::get('/meta', [ArticleController::class, 'meta'])->name('articles.meta');
});