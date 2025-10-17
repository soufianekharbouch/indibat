<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('conseil_prof', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conseil_id')->constrained()->onDelete('cascade');
            $table->foreignId('prof_id')->constrained('users')->onDelete('cascade');
            $table->boolean('a_repondu')->default(false);
            $table->string('avis')->nullable();
            $table->text('justification')->nullable();
            $table->timestamp('repondu_le')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('conseil_prof');
    }
};