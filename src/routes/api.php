<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ArticleController;

Route::prefix('articles')->group(function () {
    Route::get('/', [ArticleController::class, 'index']);           // GET /api/articles
    Route::get('/search', [ArticleController::class, 'search']);     // GET /api/articles/search
    Route::get('/filter', [ArticleController::class, 'filter']);     // GET /api/articles/filter
    Route::get('/{id}', [ArticleController::class, 'show']);         // GET /api/articles/{id}
});