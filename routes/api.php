<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GenresController;
use App\Http\Controllers\MoviesController;
use App\Http\Controllers\ScreeningsController;
use App\Http\Controllers\SeatsController;
use App\Http\Controllers\PaymentsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\ReportsController;

Route::controller(AuthController::class)->prefix('auth')->group(function () {

    Route::middleware('auth:api')->group(function () {
        Route::get('user-profile', 'userProfile')->name('auth.user-profile');
        Route::post('logout', 'logout')->name('auth.logout');
    });

    Route::post('login', 'login')->name('auth.login');
    Route::post('register', 'register')->name('auth.register');
    Route::post('forgot-password', 'forgotPassword')->name('auth.forgot-password');
    Route::post('reset-password', 'resetPassword')->name('auth.reset-password');

});

Route::controller(GenresController::class)->prefix('genres')->group(function () {
    Route::get('/', 'index')->name('genres.index');
});

Route::controller(MoviesController::class)->prefix('movies')->group(function () {
    Route::get('/', 'index')->name('movies.index');
    Route::get('/{id}', 'show')->name('movies.show');
    Route::middleware('auth:api')->group(function () {
        Route::post('/', 'store')->name('movies.store');
        Route::put('/{id}', 'update')->name('movies.update');
        Route::delete('/{id}', 'destroy')->name('movies.destroy');
    });
});

Route::controller(ScreeningsController::class)->prefix('screenings')->group(function () {
    Route::get('/by-date', 'getByDate')->name('screenings.by-date');
    Route::get('/{id}', 'show')->name('screenings.show');
    Route::middleware('auth:api')->group(function () {
        Route::post('/', 'store')->name('screenings.store');
    });
});

Route::controller(SeatsController::class)->prefix('screenings')->group(function () {
    Route::get('/{screeningId}/seats', 'getSeatsForScreening')->name('screenings.seats');
});

Route::controller(PaymentsController::class)->prefix('payments')->middleware('auth:api')->group(function () {
    Route::post('/create-intent', 'createIntent')->name('payments.create-intent');
    Route::post('/confirm', 'confirm')->name('payments.confirm');
});

Route::controller(DashboardController::class)->prefix('dashboard')->middleware('auth:api')->group(function () {
    Route::get('/stats', 'stats')->name('dashboard.stats');
});

Route::controller(UsersController::class)->prefix('users')->middleware('auth:api')->group(function () {
    Route::get('/', 'index')->name('users.index');
    Route::put('/{id}', 'update')->name('users.update');
    Route::delete('/{id}', 'destroy')->name('users.destroy');
});

Route::controller(ReportsController::class)->prefix('reports')->middleware('auth:api')->group(function () {
    Route::get('/', 'index')->name('reports.index');
    Route::get('/{id}', 'show')->name('reports.show');
});