<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

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

        // Requete de base pour recupéré tous les livre
        // Si $title est non vide, applique le scope 'title' pour filtrer les livres par titre. Sinon, récupère tous les livres.
        $books = Book::when($title, fn ($query, $title) => $query->title($title));

        // Match est une expression qui ici, compare la valeur de la variable $filter avec les proposition et renvoie une valeur.
        // Celle ci est assignée a la variable $books
        $books = match ($filter) {
            'popular_last_month' => $books->popularLastMonth(),
            'popular_last_6months' => $books->popularLast6Months(),
            'highest_rated_last_month' => $books->highestRatedLastMonth(),
            'highest_rated_last_6months' => $books->highestRatedLast6Months(),
            default => $books->latest()->withAvgRating()->withReviewsCount()
        };
        /* #region V1 sans mise en cache */
        // $books = $books->get();
        /* #endregion */

        /* #region V2 avec mise en cache */
        // Cette methode, mes en cache une valeur pour une durée determinée, ici 1h (3600 seconde), sous la clé books. Si la clé n'est pas trouvée, la fonction lambda recupere les données du livres et les met en cache
        // $books = Cache::remember('books', 3600, fn () => $books->get());

        // Autre façon de faire la meme chose
        // $books = cache()->remember('books', 3600, fn () => $books->get());

        // Autre façon de faire egalement (En tenant en compte qu'il faut cree une cles de cache pour chaque filtre de la requete)
        // Concatenation de chaine (ex: 'books:Science Fiction:Dune')
        $cacheKey = 'books:' . $filter . ':' . $title;
        // les deux code ci dessous font exactement la meme chose, le deuxieme façon n'utilise pas de lambda ce qui permet d'ajouter un message dump and die
        $books = cache()->remember($cacheKey, 3600, fn () => $books->get());
        // $books = cache()->remember($cacheKey, 3600, function() use ($books){
        //     // Ajout d'un message qui precices que les info ne viennent pas du cache. donc si on a pas de message, cela valide le fait que les donneés viennent bien du cache
        //     dd('Ne viens pas du cache !');
        //     return $books->get();
        // });
        /* #endregion */
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
    // Laravel utilise l'injection de dépendances pour injecter une instance du modèle Book correspondant à l'ID fourni dans l'URL.
    // public function show(Book $book)

    //ici on change la signature de la fonction afin de pourvoir utilise des methode statique car (Book $book) est deja un objet
    public function show(int $id)
    {
        /* #region V1 */
        // On retourne la vue show et on lui passe un tableau associatif contenant le model book
        // return view('books.show', ['book' => $book]);
        /* #endregion */
        /* #region V2 */
        // return view(
        //     'books.show',
        //     [
        //         // La méthode load est utilisée pour charger des relations sur un modèle existant en y ajoutant des contrainte ou des modifivcations
        //         'book' => $book->load([
        //             'reviews' => fn ($query) => $query->latest()
        //         ])
        //     ]
        // );
        /* #endregion */

        $cacheKey = 'book:' . $id;
        $book = cache()->remember(
            $cacheKey,
            3600,
            fn () => Book::with([
                'reviews' => fn ($query) => $query->latest()
            ])->withAvgRating()->withReviewsCount()->findOrFail($id)
        );
        return view('books.show', ['book' => $book]);
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
