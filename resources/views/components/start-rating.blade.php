@if ($rating)
    @for ($i = 1; $i <= 5; $i++)
        {{-- arrondi la valueur a l'entier le plus proche --}}
        {{$i <= round($rating) ? '★' : '☆'}}
    @endfor
@else
    Pas d'évaluation !
@endif