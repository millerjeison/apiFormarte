<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorepreguntasMalasRequest;
use App\Http\Requests\UpdatepreguntasMalasRequest;
use App\Models\preguntasMalas;

class PreguntasMalasController extends Controller
{
     // Método para guardar una pregunta mala
     public function store(StorepreguntasMalasRequest $request)
     {
         $request->validate([
             'id_pregunta' => 'required',
         ]);
 
         $preguntaMala = new preguntasMalas();
         $preguntaMala->id_pregunta = $request->id_pregunta;
         $preguntaMala->save();
 
         return response()->json(['message' => 'Pregunta mala guardada con éxito'], 201);
     }
 
     // Método para eliminar una pregunta mala
     public function destroy($id)
     {
         $preguntaMala = preguntasMalas::find($id);
         if ($preguntaMala) {
             $preguntaMala->delete();
             return response()->json(['message' => 'Pregunta mala eliminada con éxito'], 200);
         }
 
         return response()->json(['message' => 'Pregunta mala no encontrada'], 404);
     }
}
