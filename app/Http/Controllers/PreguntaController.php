<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePreguntaRequest;
use App\Http\Requests\UpdatePreguntaRequest;
use App\Models\Asignatura;
use App\Models\Estado;
use App\Models\Pregunta;
use App\Models\Respuesta;
use App\Models\Grado;
use App\Models\Area;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;


class PreguntaController extends Controller
{
    public function all()
    {
        // Traer todas las preguntas con sus respuestas cargadas
        $preguntas = Pregunta::with('respuestas')->get();

        return response()->json(['preguntas' => $preguntas], 200);
    }

    public function store(Request $request)
    {
        // Obtener los datos del request
        $data = $request->all();
        if (!empty($data['pregfechamodificaciont'])) {
            $data['pregfechamodificaciont'] = Carbon::parse($data['pregfechamodificaciont'])->format('Y-m-d H:i:s');
        }

        // Validar y obtener el ID del grado
        $grado_nombre = $request->grado;
        $grado_id = Grado::firstOrCreate(['value' => $grado_nombre])->id;

        // Validar y obtener el ID del área
        $area_nombre = $request->area;
        $area_id = Area::firstOrCreate(['value' => $area_nombre])->id;

        // Validar y obtener el ID del estado (si es necesario)
        $estado_nombre = $request->estado;
        $estado_id = Estado::firstOrCreate(['value' => $estado_nombre])->id;

        // Validar y obtener el ID de la asignatura (si es necesario)
        $asignatura_nombre = $request->asignatura;
        $asignatura_id = Asignatura::firstOrCreate([
            'value' => $asignatura_nombre,
            'grado_id' => $grado_id
            
            ])->id;

        // Agregar los datos de relación
        $data['gradidn'] = $grado_id;
        $data['areaidn'] = $area_id;
        $data['estaidn'] = $estado_id; // Si se envía un campo 'estado' en la solicitud
        $data['asignatura_id'] = $asignatura_id; // Si se envía un campo 'asignatura' en la solicitud

        // Crear la pregunta con todas las relaciones y datos
        $pregunta = Pregunta::create($data);

        // Manejo de respuestas (asumiendo que las respuestas se envían en el formato JSON)
        $respuestasData = $request->input('respuestas');

        foreach ($respuestasData as $respuestaData) {
            $respuestaData['pregunta_id'] = $pregunta->id;
            Respuesta::create($respuestaData);
        }

        return response()->json(['message' => 'Pregunta y respuestas creadas exitosamente.'], 201);
    }
    public function index(Request $request)
    {
        // Filtrar preguntas por grado y área si se proporcionan los parámetros
        $grado_id = $request->input('grado_id');
        $area_id = $request->input('area_id');

        $query = Pregunta::query()->with('respuestas'); // Cargar las respuestas relacionadas

        if ($grado_id) {
            $query->whereHas('grado', function ($q) use ($grado_id) {
                $q->where('id', $grado_id);
            });
        }
        if ($area_id) {
            $query->whereHas('area', function ($q) use ($area_id) {
                $q->where('id', $area_id);
            });
        }
        $preguntas = $query->get();
        return response()->json(['preguntas' => $preguntas], 200);
    }

    // Resto de las funciones CRUD...

    // Funciones para manejar las respuestas (podrían ser parte de otro controlador)
    public function storeRespuesta(Request $request, Pregunta $pregunta)
    {
        $data = $request->validate([
            // Define las reglas de validación aquí para el almacenamiento de respuestas en formato JSON
        ]);

        Respuesta::create(array_merge($data, ['pregunta_id' => $pregunta->id]));

        return response()->json(['message' => 'Respuesta creada exitosamente.'], 201);
    }

    public function updateRespuesta(Request $request, Pregunta $pregunta, Respuesta $respuesta)
    {
        $data = $request->validate([
            // Define las reglas de validación aquí para la actualización de respuestas en formato JSON
        ]);

        $respuesta->update($data);

        return response()->json(['message' => 'Respuesta actualizada exitosamente.'], 200);
    }

    public function destroyRespuesta(Pregunta $pregunta, Respuesta $respuesta)
    {
        $respuesta->delete();
        return response()->json(['message' => 'Respuesta eliminada exitosamente.'], 204);
    }



    public function getPreguntasConRespuestasPorGrado($grado)
    {
        $preguntas = Pregunta::where('grado', $grado)
            ->inRandomOrder()
            ->limit(10) // Limita a 10 preguntas
            ->with('respuestas') // Carga las respuestas relacionadas
            ->get();

        return response()->json(['preguntas' => $preguntas], 200);
    }

    public function getPreguntasConRespuestasPorGradoYAsignatura($grado, $asignatura)
    {
        $preguntas = Pregunta::where('grado', $grado)
            ->where('asignatura', $asignatura)
            ->inRandomOrder()
            ->limit(10) // Limita a 10 preguntas
            ->with('respuestas') // Carga las respuestas relacionadas
            ->get();
        return response()->json(['preguntas' => $preguntas], 200);
    }
}