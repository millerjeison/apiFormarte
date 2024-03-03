<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePreguntaRequest;
use App\Http\Requests\UpdatePreguntaRequest;
use App\Models\Asignatura;
use App\Models\Estado;
use App\Models\Pregunta;
use App\Models\preguntasMalas;
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




    public function getPreguntasConRespuestasPorGrado($grado,$cantidadPreguntas)
    {
        // Obtén el ID del grado o crea uno si no existe
        $idGrado = Grado::firstOrNew(['value' => $grado])->id;

        // Obtén todas las asignaturas para el grado dado
        $asignaturas = Asignatura::where('grado_id', $idGrado)->get();

        $preguntasPorAsignatura = $cantidadPreguntas ; // Número de preguntas por asignatura

        $preguntas = collect(); // Creamos una colección vacía para almacenar preguntas

        // Iteramos a través de las asignaturas para obtener preguntas equitativamente
        foreach ($asignaturas as $asignatura) {
            // Obtenemos las preguntas para esta asignatura
            $preguntasAsignatura = Pregunta::where('asignatura_id', $asignatura->id)
            ->whereNotIn('id', preguntasMalas::pluck('id_pregunta')) // Excluye las preguntas malas

                ->inRandomOrder()
                ->limit($preguntasPorAsignatura)
                ->with('respuestas')
                ->get();

            // Agregamos las preguntas de esta asignatura a la colección principal
            $preguntas = $preguntas->concat($preguntasAsignatura);
        }

        // Ordenar las preguntas por asignatura
        $preguntas = $preguntas->sortBy('asignatura.value');

        return response()->json(['preguntas' => $preguntas], 200);
    }


    public function getPreguntasConRespuestasPorAsignatura($asignatura,$cantidadPreguntas)
    {
        $preguntas = Pregunta::where('asignatura', $asignatura)
        ->whereNotIn('id', preguntasMalas::pluck('id_pregunta')) // Excluye las preguntas malas

            ->inRandomOrder()
            ->limit($cantidadPreguntas) 
            ->with('respuestas') // Carga las respuestas relacionadas
            ->get();

        return response()->json(['preguntas' => $preguntas], 200);
    }

    public function getPreguntasConRespuestasPorGradoYAsignatura($grado, $asignatura,$cantidadPreguntas)
    {
        $preguntas = Pregunta::where('grado', $grado)
        ->whereNotIn('id', preguntasMalas::pluck('id_pregunta')) // Excluye las preguntas malas

            ->where('asignatura', $asignatura)
            ->inRandomOrder()
            ->limit($cantidadPreguntas??10) 
            ->with('respuestas') // Carga las respuestas relacionadas
            ->get();
        return response()->json(['preguntas' => $preguntas], 200);
    }



    public function eliminarPreguntasRepetidas()
    {
        // Obtén todas las preguntas agrupadas por su texto y respuesta
        $preguntasRepetidas = Pregunta::select('pregtextov')
            ->groupBy('pregtextov')
            ->havingRaw('COUNT(*) > 1')
            ->get();

            $totalPreguntasRepetidas = $preguntasRepetidas->count();
            var_dump($totalPreguntasRepetidas);

        // foreach ($preguntasRepetidas as $preguntaRepetida) {
        //     // Encuentra todas las preguntas con el mismo texto
        //     $preguntas = Pregunta::where('pregtextov', $preguntaRepetida->pregtextov)->get();
    

        //     // Elimina las respuestas relacionadas
        //     foreach ($preguntas as $pregunta) {
        //         // Respuesta::where('pregunta_id', $pregunta->id)->delete();
        //     }

        //     // Elimina las preguntas repetidas
        //     // Pregunta::where('pregtextov', $preguntaRepetida->pregtextov)->delete();
        // }

        return "Preguntas repetidas eliminadas junto con sus respuestas.";
    }
}