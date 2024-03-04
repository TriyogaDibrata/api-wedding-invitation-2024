<?php

use App\Http\Controllers\API\CommentController;
use App\Http\Controllers\API\DashboardController;
use App\Http\Controllers\API\GuestController;
use App\Http\Controllers\API\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::name('auth.')->group(function () {
    Route::post('login', [UserController::class, 'login'])->name('login');
    Route::post('register', [UserController::class, 'register'])->name('register');

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('logout', [UserController::class, 'logout'])->name('logout');
        Route::get('user', [UserController::class, 'fetch'])->name('fetch');
    });
});

// Guest API
Route::prefix('guest')->middleware('auth:sanctum')->name('guest.')->group(function () {
    Route::get('', [GuestController::class, 'fetch'])->name('fetch');
    Route::post('', [GuestController::class, 'create'])->name('create');
    Route::get('{id}', [GuestController::class, 'show'])->name('show');
    Route::post('update/{id}', [GuestController::class, 'update'])->name('update');
    Route::delete('{id}', [GuestController::class, 'destroy'])->name('delete');
});


// Comment API
Route::prefix('comment')->middleware('auth:sanctum')->name('comment.')->group(function () {
    Route::get('', [CommentController::class, 'fetch'])->name('fetch');
    Route::post('', [CommentController::class, 'create'])->name('create');
    Route::get('{id}', [CommentController::class, 'show'])->name('show');
    Route::post('update/{id}', [CommentController::class, 'update'])->name('update');
    Route::delete('{id}', [CommentController::class, 'destroy'])->name('delete');
});

// Dashboard API
Route::prefix('dashboard')->middleware('auth:sanctum')->name('dashboard.')->group(function () {
    Route::get('', [DashboardController::class, 'index'])->name('index');
});

//public API

Route::name('public.')->group(function() {
    Route::get('public/guest/{id}', [GuestController::class, 'show']);
    Route::post('public/rsvp/{id}', [GuestController::class, 'rsvp']);
    Route::get('public/comments', [CommentController::class, 'fetch']);
});
