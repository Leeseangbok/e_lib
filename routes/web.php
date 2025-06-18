<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\SearchController;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/search', [SearchController::class, 'search'])->name('search');

Route::prefix('books')->name('books.')->group(function () {
    Route::get('/{id}', [BookController::class, 'show'])->name('show');
    Route::get('/{id}/read', [BookController::class, 'read'])->name('read');

    Route::middleware('auth')->group(function () {
        Route::post('/{id}/library', [BookController::class, 'addToLibrary'])->name('add-to-library');
        Route::patch('/{id}/progress', [BookController::class, 'updateProgress'])->name('update-progress');
    });
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
