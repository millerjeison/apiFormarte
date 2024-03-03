<?php

namespace App\Http\Controllers;

use App\Http\Requests\Storeresultados_asignaturasRequest;
use App\Http\Requests\Updateresultados_asignaturasRequest;
use App\Models\resultados_asignaturas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ResultadosAsignaturasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validar y procesar los datos de la solicitud
        $request->validate([
            'id_estudiante' => 'required',
            'id_asignatura' => 'required',
            'puntaje' => 'required',
        ]);

        // Crear una nueva instancia del modelo ResultadoAsignatura con los datos de la solicitud
        $resultado = new resultados_asignaturas([
            'id_estudiante' => $request->input('id_estudiante'),
            'id_asignatura' => $request->input('id_asignatura'),
            'puntaje' => $request->input('puntaje'),
        ]);

        // Opcional: Asignar el ID del simulacro si se proporciona en la solicitud
        if ($request->has('id_simulacro')) {
            $resultado->id_simulacro = $request->input('id_simulacro');
        }

        // Guardar el resultado en la base de datos
        $resultado->save();

        // Puedes responder con un mensaje JSON u otra respuesta apropiada
        return response()->json(['message' => 'Resultado de asignatura creado exitosamente'], 201);
    }

    public function resultadosPorEstudiante($idEstudiante)
    {
        $resultados = DB::table('resultados_asignaturas')
        ->select('asignaturas.value as asignatura_value', 'resultados_asignaturas.puntaje')
        ->join(DB::raw("(SELECT id_asignatura, MAX(created_at) AS max_created_at
                          FROM resultados_asignaturas
                          WHERE id_estudiante = $idEstudiante
                          GROUP BY id_asignatura
                          ORDER BY max_created_at DESC
                          LIMIT 3) AS subquery"), function ($join) {
            $join->on('resultados_asignaturas.id_asignatura', '=', 'subquery.id_asignatura')
                ->on('resultados_asignaturas.created_at', '=', 'subquery.max_created_at');
        })
        ->join('asignaturas', 'resultados_asignaturas.id_asignatura', '=', 'asignaturas.id')
        ->get();
    
    
        return response()->json(['resultados' => $resultados]);
    }

    /**
     * Display the specified resource.
     */
    public function show(resultados_asignaturas $resultados_asignaturas)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(resultados_asignaturas $resultados_asignaturas)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Updateresultados_asignaturasRequest $request, resultados_asignaturas $resultados_asignaturas)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(resultados_asignaturas $resultados_asignaturas)
    {
        //
    }
}
