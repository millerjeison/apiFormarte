<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RespuestaPregunta extends Model
{
    protected $fillable = ['pregunta_id', 'correcta','grado_id'];

    public function pregunta()
    {
        return $this->belongsTo('App\Pregunta');
    }
}