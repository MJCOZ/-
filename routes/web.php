<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\GenreController as AdminGenreController;
use App\Http\Controllers\Admin\TagController as AdminTagController;
use App\Http\Controllers\Admin\TitleController as AdminTitleController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PosterController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\ReviewLikeController;
use App\Http\Controllers\TitleController;
use App\Http\Controllers\WatchedController;
use App\Http\Controllers\WatchlistController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/titles', [TitleController::class, 'index'])->name('titles.index');
Route::get('/movies', [TitleController::class, 'movies'])->name('movies.index');
Route::get('/series', [TitleController::class, 'series'])->name('series.index');
Route::get('/watched', [WatchedController::class, 'index'])->name('watched.index');
Route::get('/genres/{genre}', [GenreController::class, 'show'])->name('genres.show');
Route::get('/tags/{tag}', [TagController::class, 'show'])->name('tags.show');
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

// المراجعات والتفاعل (المسجّلين فقط)
Route::middleware('auth')->group(function () {
    Route::post('/titles/{title}/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');

    // الردود على المراجعات
    Route::post('/reviews/{review}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');

    // التصويت (مفيد/غير مفيد)
    Route::post('/reviews/{review}/vote', [ReviewLikeController::class, 'store'])->name('reviews.vote');

    // قائمة "أريد مشاهدته"
    Route::get('/watchlist', [WatchlistController::class, 'index'])->name('watchlist.index');
    Route::post('/watchlist/{title}/toggle', [WatchlistController::class, 'toggle'])->name('watchlist.toggle');

    // الإشعارات
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllRead'])->name('notifications.readAll');
});

// لوحة الأدمن
Route::prefix('admin')->name('admin.')->group(function () {

    // المدير والمحرّر: إدارة المحتوى
    Route::middleware(['auth', 'role:admin,editor'])->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        Route::resource('titles', AdminTitleController::class)->except('show');
        Route::resource('genres', AdminGenreController::class)->except(['show', 'create']);
        Route::resource('tags', AdminTagController::class)->except(['show', 'create']);
    });

    // المدير فقط: إدارة المستخدمين
    Route::middleware(['auth', 'role:admin'])->group(function () {
        Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
        Route::put('/users/{user}', [AdminUserController::class, 'update'])->name('users.update');
    });
});
