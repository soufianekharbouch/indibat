<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('comportements', function (Blueprint $table) {
            $table->id();
            $table->string('nom_fr', 191);
            $table->string('nom_ar', 191);
            $table->integer('points_retires')->default(5);
            $table->string('categorie', 191)->default('general');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('comportements');
    }
};