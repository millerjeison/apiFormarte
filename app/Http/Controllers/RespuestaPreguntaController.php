<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRespuestaPreguntaRequest;
use App\Http\Requests\UpdateRespuestaPreguntaRequest;
use App\Models\Pregunta;
use App\Models\RespuestaPregunta;
use Illuminate\Http\Request;

class RespuestaPreguntaController extends Controller
{
    public function registrarRespuesta(Request $request)
    {
        // Validación de la solicitud (puedes personalizarla según tus necesidades)
        $request->validate([
            'pregunta_id' => 'required|exists:preguntas,id',
            'correcta' => 'required|boolean',
        ]);

        // Registrar la respuesta en la tabla respuestas_preguntas
        RespuestaPregunta::create([
            'pregunta_id' => $request->pregunta_id,
            'correcta' => $request->correcta,
        ]);

        // Calcular la dificultad de la pregunta y actualizarla (simplificado)
        $pregunta = Pregunta::find($request->pregunta_id);
        $pregunta->dificultad = $this->calcularDificultad($pregunta);
        $pregunta->save();

        return response()->json(['message' => 'Respuesta registrada exitosamente.']);
    }

    // Función para calcular la dificultad de una pregunta (ejemplo simplificado)
    private function calcularDificultad(Pregunta $pregunta)
    {
        $totalRespuestas = $pregunta->respuestas->count();
        $respuestasCorrectas = $pregunta->respuestas->where('correcta', true)->count();

        // Lógica de cálculo de dificultad (personalizada según tus necesidades)
        if ($totalRespuestas > 0) {
            $proporcionCorrectas = $respuestasCorrectas / $totalRespuestas;
            if ($proporcionCorrectas >= 0.8) {
                return 'Fácil';
            } elseif ($proporcionCorrectas >= 0.5) {
                return 'Regular';
            } else {
                return 'Difícil';
            }
        }

        return 'Sin respuesta';
    }
}