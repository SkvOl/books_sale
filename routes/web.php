<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route(Auth::check() ? 'books': 'login');
});

Route::get('/books', function () {
    return view('books');
})->middleware(['auth', 'verified'])->name('books');

Route::get('/authors', function () {
    return view('authors');
})->middleware(['auth', 'verified'])->name('authors');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
