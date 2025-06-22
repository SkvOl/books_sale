<?php

use App\Http\Controllers\AuthorController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\BuyerController;
use Illuminate\Support\Facades\Route;
use App\Models\Book;

Route::apiResource('books', BookController::class)->middleware('auth:sanctum');
Route::apiResource('authors', AuthorController::class)->middleware('auth:sanctum');
Route::apiResource('sales', SaleController::class)->middleware('auth:sanctum');
Route::apiResource('buyers', BuyerController::class)->middleware('auth:sanctum');

Route::post('/books/{book}/buy', [BookController::class, 'buy'])->middleware('auth:sanctum');


Route::controller(UserController::class)->prefix('user')->group(function () {
    Route::post('/signup', 'signup');
    Route::post('/signin', 'signin');

    Route::post('/', 'user')->middleware('auth:sanctum');
    Route::post('/current', 'current_user')->middleware('auth:sanctum');
    Route::post('/logout', 'logout')->middleware('auth:sanctum');
});