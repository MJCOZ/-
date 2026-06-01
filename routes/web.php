<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PosterController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\TitleController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/titles', [TitleController::class, 'index'])->name('titles.index');
Route::get('/titles/{title}/poster', [PosterController::class, 'show'])->name('titles.poster');
Route::get('/titles/{title}', [TitleController::class, 'show'])->name('titles.show');

// المصادقة (الضيوف فقط)
Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store']);
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);
});

// تسجيل الخروج (المسجّلين فقط)
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')->name('logout');

// الملف الشخصي (المسجّلين فقط)
Route::get('/profile', [ProfileController::class, 'show'])
    ->middleware('auth')->name('profile');

// المراجعات (المسجّلين فقط)
Route::middleware('auth')->group(function () {
    Route::post('/titles/{title}/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');
});
