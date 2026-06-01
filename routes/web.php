<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\PosterController;
use App\Http\Controllers\TitleController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/titles', [TitleController::class, 'index'])->name('titles.index');
Route::get('/titles/{title}/poster', [PosterController::class, 'show'])->name('titles.poster');
Route::get('/titles/{title}', [TitleController::class, 'show'])->name('titles.show');
