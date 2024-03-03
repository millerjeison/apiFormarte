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
        Schema::create('preguntas_malas', function (Blueprint $table) {
            $table->id();
            $table->integer('id_pregunta')->unique();
            $table->timestamps();
        
            $table->foreign('id_pregunta')->references('id')->on('preguntas');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('preguntas_malas');
    }
};
