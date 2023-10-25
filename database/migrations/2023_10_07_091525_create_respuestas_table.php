<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRespuestasTable extends Migration
{
    public function up()
    {
        Schema::create('respuestas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pregunta_id');
            $table->text('resptextov')->nullable();
            $table->string('responidentificadorv');
            $table->string('respmaterialrefuerzov')->nullable();
            $table->string('respdiagnosticov')->nullable();
            $table->integer('respnivelacercionn')->nullable();
            $table->integer('resppuntosn')->nullable();
            $table->json('resppropiedadesj')->nullable();
            $table->boolean('opprrespuestacorrectab');
            $table->integer('opprvalorrespuestad')->nullable();
            $table->integer('opciordenn');
            $table->unsignedBigInteger('gradidn')->nullable();
            $table->string('gradnombrev')->nullable();
            $table->timestamps();

            $table->foreign('pregunta_id')->references('id')->on('preguntas')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('respuestas');
    }
}

