<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class StartRating extends Component
{
    /**
     * Create a new component instance.
     */
    //readonly = imuable (assignée une seule fois et ne change plus)
    // mettre des propriete dans un constructeur permet d'initialiser ces proriete directmeent a la creation de l'instance de cette classe
    //le ?avant le float signifie que la propriete peut etre null et optionel
    public function __construct(public readonly ?float $rating)
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.start-rating');
    }
}
