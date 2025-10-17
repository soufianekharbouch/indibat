<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('rapports', function (Blueprint $table) {
            $table->index(['eleve_id', 'prof_id', 'created_at']);
        });
    }

    public function down()
    {
        Schema::table('rapports', function (Blueprint $table) {
            $table->dropIndex(['eleve_id', 'prof_id', 'created_at']);
        });
    }
};