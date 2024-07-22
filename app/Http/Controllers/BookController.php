<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    /* #region V1 */
    // // Cette methode recupere une liste de livre depuis la DB. Si le titre est fournis, la liste est filtrée pour ne contenir que les livres correspondant a ce titre
    // //l'objet Request contient toutes les information de le requette HTTP entrante
    // public function index(Request $request)
    // {
    //     $title = $request->input('title');
    // //Methode when d'Eloquent, execute la fonction annonyme si elle n'est pas nul
    //     $books = Book::when($title, function ($query, $title) {
    //         return $query->title($title);
    //     })->get();
    // }
    /* #endregion */

    /* #region V2 */
    public function index(Request $request)
    {
        // Recupere la valeur du paramettre 'title' de la requete HTTP et la stock dans la variable $title
        $title = $request->input('title');
        // Recuper la valeur du paramettre 'filter' et si il n'y en a pas, la variable $filter sera une chaine vide
        $filter = $request->input('filter', '');

        /* #region V1 */
        // $books = Book::when($title, fn ($query, $title) => $query->title($title))->get();
        // // Le ressource controlleur genere les route automatiqument en suivant une convention de nomage nom action.
        // // Il est recommander de nomer les vue de la meme maniere que les routes. Ex book.index : index.blade.php
        // //                                                                           book.show  : show.blade.php
        // //                                                                           etc...

        // // On redirige vers la route books.index en passant en parametre un tableau associatif clé valeur.
        // // books est le nom de la variable
        // // $books est la valeur (cretenement une collection d'objet prise dans la DB)
        // return view('books.index', ['books' => $books]);
        /* #endregion */
        
        $books = Book::when($title, fn($query, $title) => $query->title($title));
        // Match est une expression qui ici, compare la valeur de la variable $filter avec les proposition et renvoie une valeur.
        // Celle ci est assignée a la variable $books
        $books = match ($filter) {
            'popular_last_month' => $books->popularLastMonth(),
            'popular_last_6months' => $books->popularLast6Months(),
            'highest_rated_last_month' => $books->highestRatedLastMonth(),
            'highest_rated_last_6months' => $books->highestRatedLast6Months(),
            default => $books->latest()
        };
        $books=$books->get();
        return view('books.index', ['books' => $books]);
    }
    /* #endregion */

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
