<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('nom', 191);
            $table->string('prenom', 191);
            $table->enum('role', ['root', 'admin', 'prof', 'motasarrif']);
            $table->string('username', 191)->unique();
            $table->string('password', 191);
            $table->string('matiere', 191)->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
};