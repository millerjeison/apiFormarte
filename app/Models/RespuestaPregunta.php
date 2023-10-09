<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RespuestaPregunta extends Model
{
    protected $fillable = [
        'pregunta_id',
        'resultado',
        'grado_id',
        'asignatura_id'
    ];

    public function pregunta()
    {
        return $this->belongsTo('App\Pregunta');
    }
}