<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
   use HasFactory;

   // importance de nomé les noms de methode au pluriel pour hasmany et belongtomany
   //  avec tinker: $reviews = $book->reviews; : cette commande permet d'appeller toutes les revues sur un livre specifique
   public function reviews()
   {
      // hasmany = relation one to many
      return $this->hasMany(Review::class);
   }

   //   Methode de portée (scope) par convention, le nom est toujous "scopeNomFonction"
   //   Builder est une classe qui permet de construire des requete SQL
   //   Les methode de la classe Builder sont chaînable (l'une a la suite de l'autre)
   public function scopeTitle(Builder $query, string $title): Builder
   {
      return $query->where('title', 'LIKE', '%' . $title . '%');
   }

   public function scopeWithReviewsCount(Builder $query, $from = null, $to = null): Builder
   {
      return $query->withCount([
         'reviews' => fn (Builder $q) => $this->dateRangeFilter($q, $from, $to)
      ]);
   }

   public function scopeWithAvgRating(Builder $query, $from = null, $to = null): Builder
   {
      return $query->withAvg(['reviews' => fn (Builder $q) => $this->dateRangeFilter($q, $from, $to)], 'rating');
   }

   /* #region V1 */
   // Methode pour compter le nombre de critique sur un livre et affiche le resultat decroissant, de sorte d'avoir les plus populaire en premier
   // public function scopePopular(Builder $query): Builder
   // {
   //    return $query->withCount('reviews') //reviews est le nom de la relation
   //       ->orderBy('reviews_count', 'desc');
   // }
   /* #endregion */

   /* #region V2 */
   //Meme fonction mais on trie les livre les plus populaire entre deux date
   // public function scopePopular(Builder $query, $from = null, $to = null): Builder
   // {
   //    //use permet a la fonction d'acceder aux variable from et to qui sont en dehors de la portée de la fonction
   //    return $query->withCount(['reviews' => function(Builder $q) use ($from, $to){
   //       if($from && !$to){
   //          $q->where('created_at', '>=', '$from');
   //       }
   //       elseif(!$from && $to){
   //          $q->where('created_at', '<=', '$to');
   //       }
   //       elseif($from && $to){
   //          $q->where('created_at', [$from, $to]);
   //       }
   //    }])
   //       ->orderBy('reviews_count', 'desc');
   // }
   /* #endregion */

   /* #region V3 */
   // public function scopePopular(Builder $query, $from = null, $to = null): Builder
   // {
   //    return $query->withCount([
   //       // fonction anonyme lambda s'ecrit fn et capture automatiquemenet les variable du scope parents (donc pas de use)
   //       // la limitation d'une lambda est qu'il ne px y avoir qu'une seule expression a l'interieur
   //       'reviews' => fn (Builder $q) => $this->dateRangeFilter($q, $from, $to)
   //    ])
   //       ->orderBy('reviews_count', 'desc');
   // }
   /* #endregion */

   /* #region V4 */
   public function scopePopular(Builder $query, $from = null, $to = null): Builder
   {
      return $query->withReviewsCount()
         ->orderBy('reviews_count', 'desc');
   }
   /* #endregion */

   /* #region V1 */
   // public function scopeHighestRated(Builder $query): Builder
   // {
   //    return $query->withAvg('reviews', 'rating')
   //       ->orderBy('reviews_avg_rating', 'desc');
   // }
   /* #endregion */

   /* #region V2 */
   public function scopeHighestRated(Builder $query, $from = null, $to = null): Builder
   {
      return $query->withAvgRating()
         ->orderBy('reviews_avg_rating', 'desc');
   }
   /* #endregion */


   // Fonction qui permet de filtre les livres qui on un minimum de critique
   public function scopeMinReviews(Builder $query, int $minReviews): Builder
   {
      return $query->having('reviews_count', '>=', $minReviews);
   }

   private function dateRangeFilter(Builder $query, $from = null, $to = null)
   {
      if ($from && !$to) {
         $query->where('created_at', '>=', '$from');
      } elseif (!$from && $to) {
         $query->where('created_at', '<=', '$to');
      } elseif ($from && $to) {
         $query->whereBetween('created_at', [$from, $to]);
      }
   }

   public function scopePopularLastMonth(Builder $query): Builder
   {
      return $query->popular(now()->subMonth(), now())
         ->highestRated(now()->subMonth(), now())
         ->minReviews(2);
   }

   public function scopePopularLast6Months(Builder $query): Builder
   {
      // Methode de la bibliotheque carbon, utilisée pour manipuler des date. submonth = soustrait un mois de cette date.
      // On px egalement preciser le nombre de mois a soustraire entre parenthese
      return $query->popular(now()->subMonths(6), now())
         ->highestRated(now()->subMonths(6), now())
         ->minReviews(5);
   }

   public function scopeHighestRatedLastMonth(Builder $query): Builder
   {
      // L'ordre d'appel est important
      return $query->highestRated(now()->subMonth(), now())
         ->popular(now()->subMonth(), now())
         ->minReviews(2);
   }

   public function scopeHighestRatedLast6Months(Builder $query): Builder
   {
      // L'ordre d'appel est important
      return $query->highestRated(now()->subMonths(6), now())
         ->popular(now()->subMonths(6), now())
         ->minReviews(5);
   }

   protected static function booted()
   {
      static::updated(fn (Book $book) => cache()->forget('book:' . $book->id));
      static::deleted(fn (Book $book) => cache()->forget('book:' . $book->id));
   }
}
