<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UrlController;
use App\Http\Controllers\RedirectController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

// Home/Welcome page
Route::get('/', function () {
    return view('welcome');
});

// Auth routes (MUST come before /{shortCode})
require __DIR__.'/auth.php';

// Authenticated routes
Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // URL management routes (with 'urls' prefix to avoid conflicts)
    Route::prefix('urls')->group(function () {
        Route::get('/', [UrlController::class, 'index'])->name('urls.index');
        Route::get('/create', [UrlController::class, 'create'])->name('urls.create');
        Route::post('/', [UrlController::class, 'store'])->name('urls.store');
        Route::get('/{url}', [UrlController::class, 'show'])->name('urls.show');
        Route::get('/{url}/edit', [UrlController::class, 'edit'])->name('urls.edit');
        Route::patch('/{url}', [UrlController::class, 'update'])->name('urls.update');
        Route::delete('/{url}', [UrlController::class, 'destroy'])->name('urls.destroy');
        Route::get('/{url}/qrcode', [UrlController::class, 'qrCode'])->name('urls.qrcode');
    });
});

// Public redirect route (MUST be LAST to avoid catching other routes)
Route::get('/{shortCode}', [RedirectController::class, 'redirect'])->name('redirect');
