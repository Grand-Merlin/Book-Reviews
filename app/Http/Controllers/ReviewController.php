<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;

class ReviewController extends Controller
{


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    // La propriete est un objet book correspondant a l'ID du livre passe dans L'URL (route model binding)
    // ne pas oublier d'importer classe dans le namespace (use)
    public function create(Book $book)
    {
        // pour connaitre le nom de la route, il suffit de copier coller celle qui corres^pont a l'action désirée avec un php artisan route:list
        // donc retourne la vue corespondante a create.blade.php (le formulaire pour creer un nouvel avis)
        return view('books.reviews.create', ['book' => $book]);
    }

    /**
     * Store a newly created resource in storage.
     */
    // on ajoute egalement l'objet book dans les proprieters
    // l'ojet Request est déja present et sert a recuperer et valider les données
    public function store(Request $request, Book $book)
    {
        // la fonction validate permet de validé les données entree par l'utilisateur. si la validation échoue, une reponse de redirection sera automatiquement généree
        $data = $request->validate([
            // required = obligatoir et max 15 caractere
            'review' => 'required|min:15',
            // obligatoir, min 1, max 5 et dois etre un entier
            'rating' => 'required|min:1|max:5|integer'
        ]);
        // association automatique sion cree une critique sur la relation 'review' d'un livre, la critique est automatiquement associee au livre
        // les propriete du model review doivent etre fillable pour que la methode create puisse remplir les champs en masse
        $book->reviews()->create($data);

        return redirect()->route('books.show', $book);
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
