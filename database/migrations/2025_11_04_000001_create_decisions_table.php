<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDecisionsTable extends Migration
{
    public function up()
    {
        Schema::create('decisions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('eleve_id');
            $table->unsignedBigInteger('motasarrif_id'); // user id (role: motasarrif)

            $table->date('decision_date')->nullable();
            $table->time('decision_time')->nullable();

            // Liste des décisions choisies (plusieurs valeurs)
            $table->json('choices')->nullable();

            // Texte libre: تفاصيل الإجراء
            $table->text('details')->nullable();

            $table->timestamps();

            $table->foreign('eleve_id')->references('id')->on('eleves')->onDelete('cascade');
            $table->foreign('motasarrif_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('decisions');
    }
}
