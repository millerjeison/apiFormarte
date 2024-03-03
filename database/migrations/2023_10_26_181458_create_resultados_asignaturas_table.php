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
        Schema::create('resultados_asignaturas', function (Blueprint $table) {
            $table->id();
            $table->int('id_estudiante');
            $table->unsignedBigInteger('id_asignatura');
            $table->integer('puntaje');
            $table->unsignedBigInteger('id_simulacro')->nullable();
            $table->timestamps();

            // Definir las relaciones con las tablas correspondientes
            $table->foreign('id_asignatura')->references('id')->on('asignaturas');
            $table->foreign('id_simulacro')->references('id')->on('resultados_simulacros');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resultados_asignaturas');
    }
};
