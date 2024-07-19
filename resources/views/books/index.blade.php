@extends('layouts.app')
@section('content')
    <h1 class="mb-10 text-2x1">Livres</h1>
    <form></form>
    <ul>
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
                    {{number_format($book->reviews_avg_rating, 1)}}
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
              <p class="empty-text">No books found</p>
              {{-- ce lien renvoie vers une page sans filtre (Index est un bon choix) --}}
              <a href="{{route('books.index')}}" class="reset-link">Reset criteria</a>
            </div>
          </li>
        @endforelse
    </ul>
@endsection