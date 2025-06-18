<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\SearchController;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/search', [SearchController::class, 'search'])->name('search');

Route::prefix('books')->name('books.')->group(function () {
    // MODIFIED: Changed the route parameter to be more descriptive and accept slashes.
    Route::get('/{openLibraryId}', [BookController::class, 'show'])->name('show')->where('openLibraryId', '.*');

    // MODIFIED: Also update the other routes to use the same logic.
    Route::get('/{openLibraryId}/read', [BookController::class, 'read'])->name('read')->where('openLibraryId', '.*');

    Route::middleware('auth')->group(function () {
        Route::post('/{openLibraryId}/library', [BookController::class, 'addToLibrary'])->name('add-to-library')->where('openLibraryId', '.*');
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
