@extends('layouts.app')
@section('content')
    <h1 class="mb-10 text-2x1">Livres</h1>
    {{-- Le formulaire recherche des livre par leur titre en envoyant une requete GET --}}
    {{-- Ce code garde egalement l'input en memoir en cas de rafraichissament de la page car GET cree une URL de type : /books?title=titre --}}
    {{-- rafraichir la page reviens donc a renvoyer la meme requete au serveur --}}
    <form method="GET" action="{{route('books.index')}}" class="mb-4 flex items-center space-x-2"> 
        <input type="text" name="title" placeholder="Recherche par titre" value="{{request('title')}}" class="input h-10"/>
        {{-- un element input caché permet de garder la valeur actuel du filtre (/books?filter=) lorsque l'utilisateur interagit avec d'autre élément --}}
        <input type="hidden" name="filter" value="{{request('filter')}}"/>
        <button type="submit" class="btn h-10">Recherche</button>
        <a href="{{route('books.index')}}" class="btn h-10">Effacer</a>
    </form>

    {{-- Cette div sert a afficher les different filtre pré-enregister --}}
    <div class="filter-container mb-4 flex">
        {{-- execussion de code PHP a l'interieur d'un fichier blade --}}
        @php
        // tableau associatif clé valeur nommé filters
            $filters = [
                ''=> 'Derniers',
                'popular_last_month' => 'Populaire le mois dernier',
                'popular_last_6months' => 'Populaire les 6 derniers mois',
                'highest_rated_last_month' => 'Mieux noté le mois dernier',
                'highest_rated_last_6months' => 'Mieux noté les 6 derniers mois',

    ];
        @endphp
        {{-- Maniere courrante de parcourir un tableau associatif en PHP y compris dans un template blade --}}
        {{-- Pour chaque itération du tableau,
        key contient la cle actuelle du tableau
        label contient la valeur associée a cette clé --}}

        {{-- Pour chaque paire cle-valeur, un lien HTML est généré --}}
        @foreach ($filters as $key => $label)
            {{-- <a href="#" class="filter-item"> --}}
                {{-- Lorsqu'un utilisateur clique sur un lien, une URL de format /books?filter= sera générée. --}}
                {{-- La condition ternaire ici récupère la valeur du paramètre 'filter' pour déterminer si elle correspond à la clé actuelle du tableau, ou si aucun filtre n'est défini et que la clé est vide, et applique une classe CSS personnalisée en conséquence --}}
            {{-- <a href="{{route('books.index', ['filter' => $key])}}" class="{{request('filter') === $key || (request('filter')=== null && $key === '')?'filter-item-active':'filter-item'}}"> --}}
               
                {{-- request()->query() récupère tous les paramètres de requête actuels de l'URL --}}
                {{-- Le spread operator (...)assure que tous les paramètres existants de l'URL sont conservés --}}
            <a href="{{route('books.index', [...request()->query(),'filter' => $key])}}" class="{{request('filter') === $key || (request('filter')=== null && $key === '')?'filter-item-active':'filter-item'}}">
                {{-- affiche la valeur de la clé a l'interieur du lien --}}
                {{$label}}
            </a>
        @endforeach
    </div>

    <ul>
      {{-- @forelse est une directive Blade qui permet de parcourir une collection et de gérer le cas où la collection est vide. --}}
      {{-- la collection $books vient d'etre crée dans le Controller --}}
        @forelse ($books as $book)
        <li class="mb-4">
            <div class="book-item">
              <div
                class="flex flex-wrap items-center justify-between">
                <div class="w-full flex-grow sm:w-auto">
                    {{-- laravel va generer une URL /books/{book} => comme on utilise eloquent, c'est le parametre id du model Book qui sera automatiquement charger --}}
                  <a href="{{ route('books.show', $book)}}" class="book-title">{{ $book->title}}</a>
                  <span class="book-author">by {{$book->author}}</span>
                </div>
                <div>
                  <div class="book-rating">
                    {{-- Formater les nombre avec des separateur de millier. Si le nombre n'est pas un entier, il faut également specifier le nombre de chiffre apres la virgule --}}
                    {{-- {{number_format($book->reviews_avg_rating, 1)}} --}}
                    {{-- on utilie egalement le component pour afficher les etoile --}}
                    <x-start-rating :rating="$book->reviews_avg_rating" />
                  </div>
                  <div class="book-review-count">
                    {{-- la methode Str::plural determine si le mot 'review' dois etre mis au singulier ou au plurier en fonction de la valeur de reviews_count --}}
                    out of {{$book->reviews_count}} {{Str::plural('review', $book->reviews_count)}}
                  </div>
                </div>
              </div>
            </div>
          </li>
        @empty
        <li class="mb-4">
            <div class="empty-book-item">
              <p class="empty-text">Pas de livre trouvé</p>
              {{-- ce lien renvoie vers une page sans filtre (Index est un bon choix) --}}
              <a href="{{route('books.index')}}" class="reset-link">Reset criteria</a>
            </div>
          </li>
        @endforelse
    </ul>
@endsection