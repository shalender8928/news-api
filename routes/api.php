<?php

use App\Http\Controllers\API\V1\ArticleController;
use Illuminate\Support\Facades\Route;

Route::get('/ping', function () {
    return response()->json(['message' => 'API is working']);
});
Route::prefix('v1')->group(function () {
    Route::controller(ArticleController::class)->name('articles.')->group(function () {
        Route::get('/articles', 'index')->name('index');
        Route::get('/articles/{id}', 'show')->name('show');
        Route::get('/meta', 'meta')->name('meta');
    });
});