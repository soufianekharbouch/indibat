<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('visites', function (Blueprint $table) {
            $table->id();
            $table->string('identifiant'); // IP pour non-auth, username pour auth
            $table->string('type'); // 'visiteur' ou 'utilisateur'
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->string('appareil')->nullable(); // Nom de l'appareil
            $table->integer('nombre_visites')->default(1);
            $table->timestamp('derniere_visite')->useCurrent();
            $table->timestamps();
            
            $table->index(['identifiant', 'type']);
            $table->index('derniere_visite');
        });
    }

    public function down()
    {
        Schema::dropIfExists('visites');
    }
};