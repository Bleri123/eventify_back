<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GenresController;
use App\Http\Controllers\MoviesController;
use App\Http\Controllers\ScreeningsController;
use App\Http\Controllers\SeatsController;
use App\Http\Controllers\PaymentsController;

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
    Route::get('/{id}', 'show')->name('movies.show');
});

Route::controller(ScreeningsController::class)->prefix('screenings')->group(function () {
    Route::get('/{id}', 'show')->name('screenings.show');
});

Route::controller(SeatsController::class)->prefix('screenings')->group(function () {
    Route::get('/{screeningId}/seats', 'getSeatsForScreening')->name('screenings.seats');
});

Route::controller(PaymentsController::class)->prefix('payments')->middleware('auth:api')->group(function () {
    Route::post('/create-intent', 'createIntent')->name('payments.create-intent');
    Route::post('/confirm', 'confirm')->name('payments.confirm');
});