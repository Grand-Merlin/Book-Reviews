 <?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            // FK de la table Books (même type que que la PK de Books)
            $table->unsignedBigInteger('book_id');
            
            $table->text('review');
            // unsigned = valeur uniquement positive, tinyinteger = entier tres petit
            $table->unsignedTinyInteger('rating');
            $table->timestamps();

            //Contraintes
            //cascade signifie que si je supprime un livre de ma DB, toutes les review qui s'y referent seront egalement supprimée
            $table->foreign('book_id')->references('id')->on('books')->onDelete('cascade');

            //version courte de déclaration de contrainte, en une seul ligne
            // $table->foreignId('book_id')->constrained()->cascadeOnDelete();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
