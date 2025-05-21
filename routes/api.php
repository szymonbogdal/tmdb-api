<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\MovieController;
use App\Http\Controllers\Api\SerieController;
use App\Http\Controllers\Api\GenreController;
use App\Http\Middleware\SetLocale;

Route::middleware(SetLocale::class)->group(function () {
    Route::get('/movies', [MovieController::class, 'index']);
    Route::get('/movies/{id}', [MovieController::class, 'show']);

    Route::get('/series', [SerieController::class, 'index']);
    Route::get('/series/{id}', [SerieController::class, 'show']);

    Route::get('/genres', [GenreController::class, 'index']);
    Route::get('/genres/{id}', [GenreController::class, 'show']);
});