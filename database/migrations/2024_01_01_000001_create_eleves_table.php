<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('eleves', function (Blueprint $table) {
            $table->id();
            $table->string('code_massar', 191)->unique();
            $table->string('nom_ar', 191);
            $table->string('prenom_ar', 191);
            $table->string('classe', 191);
            $table->integer('score_discipline')->default(100); // Score initial à 100
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('eleves');
    }
};