<?php

namespace App\Http\Controllers;

use App\Http\Requests\Storeresultados_simulacrosRequest;
use App\Http\Requests\Updateresultados_simulacrosRequest;
use App\Models\resultados_simulacros;

class ResultadosSimulacrosController extends Controller
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
    public function store(Storeresultados_simulacrosRequest $request)
    {
        // id_estudiante,id_grado,puntaje,
    }

    /**
     * Display the specified resource.
     */
    public function show(resultados_simulacros $resultados_simulacros)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(resultados_simulacros $resultados_simulacros)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Updateresultados_simulacrosRequest $request, resultados_simulacros $resultados_simulacros)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(resultados_simulacros $resultados_simulacros)
    {
        //
    }
}
