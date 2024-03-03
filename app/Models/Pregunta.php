<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pregunta extends Model
{
    protected $fillable = [
        'pregtextov',
        'pregcomentariov',
        'pregautoriav',
        'pregyear',
        'pregcorrecta',
        'pregmaterialrefuerzov',
        'asignatura_id',
        'pregfechamodificaciont',
        'componente',
        'competencia',
        'areaidn',
        'area',
        'asignatura',
        'aprendizaje',
        'tipoidn',
        'tiponombrev',
        'empridn',
        'emprnombrev',
        'usuaidn',
        'usualoginv',
        'estaidn',
        'estado',
        'gradidn',
        'grado',
        'dificultad',
        'periodo',
        'recunombrev',
        'recutexto',
        'acierto'
    ];

    public function area()
    {
        return $this->belongsTo(Area::class, 'areaidn', 'areaidn');
    }

    public function asignatura()
    {
        return $this->belongsTo(Asignatura::class, 'asignatura_id');
    }
    public function respuestas()
    {
        return $this->hasMany(Respuesta::class, 'pregunta_id');
    }
}