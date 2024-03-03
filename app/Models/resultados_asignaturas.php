<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class resultados_asignaturas extends Model
{
    protected $fillable = ['id_estudiante', 'id_asignatura', 'puntaje', 'id_simulacro'];

    // Define la relación con el modelo Simulacro (opcional)
    public function simulacro()
    {
        return $this->belongsTo(resultados_simulacros::class, 'id_simulacro');
    }}
