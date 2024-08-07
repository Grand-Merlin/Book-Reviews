<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;

Route::get('/', function () {
    return redirect()->route('books.index'); 
});

// lorsqu'on accede a l'URL /books, la methode index est automatiquement appelée
Route::resource('books', BookController::class);
