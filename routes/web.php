<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;
use App\Http\Controllers\ReviewController;

Route::get('/', function () {
    return redirect()->route('books.index'); 
});

// lorsqu'on accede a l'URL /books, la methode index est automatiquement appelée
// On specifie egalement les route qui seront uniquement autorisée pour certaine action 
Route::resource('books', BookController::class)
    ->only(['index', 'show']);

Route::resource('books.reviews', ReviewController::class)
    // la critique est dans la scope du livre
    ->scoped(['review' => 'book'])
    ->only(['create', 'store']);
