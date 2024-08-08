@extends('layouts.app')
@section('content')
<h1 class="mb-10 text-2xl">Ajouter une critique pour {{$book->title}}</h1>
<form method="POST" action="{{route('books.reviews.store', $book)}}">
    @csrf
    <label for="review">Critique</label>
    <textarea name="review" id="review" required class="input mb-4"></textarea>
    <label for="rating">Note</label>
    <select name="rating" id="rating" class="input mb-4" required>
        <option value="">Selectionner une note</option>
        @for ($i=1; $i <= 5; $i++)
            <option value="{{$i}}">{{$i}}</option>
        @endfor
    </select>
    <button type="submit" class="btn">Ajouter la critique</button>
</form>
@endsection