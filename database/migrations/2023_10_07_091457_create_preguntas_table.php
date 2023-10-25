<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('preguntas', function (Blueprint $table) {
            $table->id();
            $table->text('pregtextov')->nullable();
            $table->string('pregcomentariov')->nullable();
            $table->timestamp('pregfechacreaciont')->nullable();
            $table->string('pregautoriav')->nullable();
            $table->integer('pregyear');
            $table->boolean('pregcorrecta');
            $table->text('pregmaterialrefuerzov')->nullable();
            $table->time('pregtiempon')->nullable();
            $table->string('pregautoriacelularv')->nullable();
            $table->string('pregfuentev')->nullable();
            $table->integer('pregpuntosn')->nullable();
            $table->string('pregcodigointernov')->nullable();
            $table->string('pregpropiedadv')->nullable();
            $table->timestamp('pregfechamodificaciont')->nullable();
            $table->string('componente');
            $table->string('tema')->nullable();
            $table->string('competencia');
            $table->integer('areaidn');
            $table->integer('asignatura_id');
            $table->string('area');
            $table->string('asignatura');
            $table->string('aprendizaje')->nullable();
            $table->string('dba')->nullable();
            $table->integer('tipoidn');
            $table->string('tiponombrev');
            $table->integer('empridn');
            $table->string('emprnombrev');
            $table->integer('usuaidn');
            $table->string('usualoginv');
            $table->integer('estaidn');
            $table->string('estado');
            $table->integer('gradidn')->nullable();
            $table->string('grado')->nullable();
            $table->string('dificultad')->nullable();
            $table->string('periodo')->nullable();
            $table->string('pregestructuraj')->nullable();
            $table->string('recunombrev')->nullable();
            $table->text('recutexto')->nullable();
            $table->boolean('acierto');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('preguntas');
    }
};
