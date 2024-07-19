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
}
