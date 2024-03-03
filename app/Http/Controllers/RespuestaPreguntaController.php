<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRespuestaPreguntaRequest;
use App\Http\Requests\UpdateRespuestaPreguntaRequest;
use App\Models\Pregunta;
use App\Models\RespuestaPregunta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class RespuestaPreguntaController extends Controller
{





    public function createRespondedAnswers(Request $request)
    {


        // Validar los datos del request
        $validatedData = $request->validate([
            'pregunta_id' => 'required|exists:preguntas,id',
            'correcta' => 'required|boolean',
            'grado_id' => 'required|exists:grados,id', // Asumiendo que hay una tabla grados
            'asignatura_id' => 'required|exists:asignaturas,id' // Asumiendo que hay una tabla asignaturas
        ]);

        $respuesta = RespuestaPregunta::create($request->all());


        return response()->json(['respuesta' => $respuesta], 201);

    }

    public function obtenerPreguntaIdsPorDificultad($asignaturaId, $dificultad)
    {

        // Define un arreglo con los grados válidos
        $gradosValidos = ['facil', 'intermedio', 'dificil'];

        // Verifica que la $asignaturaId sea válida y $grado sea uno de los valores válidos
        if (!is_numeric($asignaturaId) || !in_array($dificultad, $gradosValidos)) {
            return response()->json(['error' => 'Parámetros inválidos'], 400);
        }

        // Realiza la consulta para obtener el grado de dificultad de las preguntas
        $preguntaIds = RespuestaPregunta::where('asignatura_id', $asignaturaId)
            ->select('pregunta_id', DB::raw('SUM(resultado = 1) as respuestas_correctas, COUNT(*) as total_respuestas'))
            ->groupBy('pregunta_id')
            ->get();

        // Filtra las pregunta_ids según el grado especificado
        $preguntaIdsFiltrados = $preguntaIds->filter(function ($pregunta) use ($dificultad) {

            $porcentajeCorrectas = ($pregunta->respuestas_correctas / $pregunta->total_respuestas) * 100;

            if ($porcentajeCorrectas < 30) {
                return $dificultad === 'dificil';
            } elseif ($porcentajeCorrectas >= 30 && $porcentajeCorrectas < 70) {
                return $dificultad === 'intermedio';
            } else {
                return $dificultad === 'facil';
            }

        })->pluck('pregunta_id');

        return response()->json(['pregunta_ids' => $preguntaIdsFiltrados]);

    }


    public function obtenerPreguntaIdsPorDificultadPorgrado($grado, $dificultad)
    {

        // Define un arreglo con los grados válidos
        $gradosValidos = ['facil', 'intermedio', 'dificil'];

        // Verifica que la $asignaturaId sea válida y $grado sea uno de los valores válidos
        if (!is_numeric($grado) || !in_array($dificultad, $gradosValidos)) {
            return response()->json(['error' => 'Parámetros inválidos'], 400);
        }

        // Realiza la consulta para obtener el grado de dificultad de las preguntas
        $preguntaIds = RespuestaPregunta::where('grado_id', $grado)
            ->select('pregunta_id', DB::raw('SUM(resultado = 1) as respuestas_correctas, COUNT(*) as total_respuestas'))
            ->groupBy('pregunta_id')
            ->get();

        // Filtra las pregunta_ids según el grado especificado
        $preguntaIdsFiltrados = $preguntaIds->filter(function ($pregunta) use ($dificultad) {
            $porcentajeCorrectas = ($pregunta->respuestas_correctas / $pregunta->total_respuestas) * 100;
            if ($porcentajeCorrectas < 30) {
                return $dificultad === 'dificil';
            } elseif ($porcentajeCorrectas >= 30 && $porcentajeCorrectas < 70) {
                return $dificultad === 'intermedio';
            } else {
                return $dificultad === 'facil';
            }
        })->pluck('pregunta_id');

        return response()->json(['pregunta_ids' => $preguntaIdsFiltrados]);
    }

}