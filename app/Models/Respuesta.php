<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Respuesta extends Model
{
    protected $fillable = [
        'pregunta_id', 'resptextov', 'responidentificadorv', 'respmaterialrefuerzov',
        'respdiagnosticov', 'respnivelacercionn', 'resppuntosn', 'resppropiedadesj',
        'opprrespuestacorrectab', 'opprvalorrespuestad', 'opciordenn', 'gradidn',
        'gradnombrev','pregyear'
    ];

    public function pregunta()
    {
        return $this->belongsTo(Pregunta::class);
    }
}