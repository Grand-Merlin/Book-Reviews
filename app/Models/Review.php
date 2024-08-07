<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    //vu dans le projet tasklist, permet l'assignation en masse
    protected $fillable = ['review', 'rating'];

    // importance de nomé les noms de methode au singulier pour hasone et belongto
    public function book(){
        // belongsto = relation many to one
        // par convention, laravel utilise comme nom de FK book_id, si jamais la FK porte un autre nom, il faut le precicer, exemple:
        // return $this->belongsTo(Book::class, 'bookCustom_id');
        return $this->belongsTo(Book::class);
    }

    protected static function booted()
    {
        // ajoute un gestionnaire d'evenement (ici pour l'evenement update)
        // l'evenenet update est déclenché chaque fois qu'un enregistrement du model est modifier
        // Ici, chaque fois qu'une critique est mise a jour, le cache est invalidé.
        // Cas ou le gestionnaire d'evemenet de sera pas appeler :
                //Modification directe de la base de données
                //Utilisation de l'affectation massive (mass assignment)
                //Utilisation des requêtes SQL brutes (ex: DB::statement ou DB::update)
                //Transactions de base de données (rollback)
        static::updated(fn(Review $review) => cache()->forget('book:' . $review->book_id));
        static::deleted(fn(Review $review) => cache()->forget('book:' . $review->book_id));
    }
}
