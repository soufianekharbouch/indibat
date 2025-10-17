<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('conseils', function (Blueprint $table) {
            $table->id();
            $table->foreignId('eleve_id')->constrained()->onDelete('cascade');
            $table->foreignId('admin_id')->constrained('users')->onDelete('cascade');
            $table->string('raison_principale');
            $table->text('description')->nullable();
            $table->date('date_fermeture')->nullable();
            $table->enum('statut', ['ouvert', 'ferme'])->default('ouvert');
            $table->text('decision_finale')->nullable();
            $table->boolean('reinitialiser_score')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('conseils');
    }
};