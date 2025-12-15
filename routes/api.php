<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GenresController;
use App\Http\Controllers\MoviesController;

Route::controller(AuthController::class)->prefix('auth')->group(function () {

    Route::middleware('auth:api')->group(function () {
        Route::get('user-profile', 'userProfile')->name('auth.user-profile');
        Route::post('logout', 'logout')->name('auth.logout');
    });

    Route::post('login', 'login')->name('auth.login');
    Route::post('register', 'register')->name('auth.register');

});

Route::controller(GenresController::class)->prefix('genres')->group(function () {
    Route::get('/', 'index')->name('genres.index');
});

Route::controller(MoviesController::class)->prefix('movies')->group(function () {
    Route::get('/', 'index')->name('movies.index');
});