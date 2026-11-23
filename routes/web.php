<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PortfolioController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PortfolioController::class, 'index'])->name('home');
Route::get('/cv/download', [PortfolioController::class, 'download'])->name('cv.download');
Route::get('/media/{path}', [PortfolioController::class, 'media'])->where('path', '(portraits|portfolio)/[A-Za-z0-9_-]+\.(jpg|jpeg|png|webp)')->name('media');
Route::get('/admin/login', [AuthController::class, 'login'])->name('login');
Route::post('/admin/login', [AuthController::class, 'authenticate'])->middleware('throttle:5,1')->name('login.store');

Route::prefix('admin')->name('admin.')->middleware(['auth', 'can:manage-portfolio'])->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('index');
    Route::put('/profile', [AdminController::class, 'profile'])->name('profile');
    Route::post('/entries', [AdminController::class, 'store'])->name('entries.store');
    Route::put('/entries/{entry}', [AdminController::class, 'update'])->name('entries.update');
    Route::delete('/entries/{entry}', [AdminController::class, 'destroy'])->name('entries.destroy');
    Route::put('/password', [AdminController::class, 'password'])->name('password');
});
Route::post('/admin/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');
