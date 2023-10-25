<?php

use App\Http\Controllers\AsignaturaController;
use App\Http\Controllers\RespuestaPreguntaController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PreguntaController;
use App\Http\Controllers\RespuestaController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


// Rutas para preguntas
Route::prefix('preguntas')->group(function () {
    Route::get('/', [PreguntaController::class, 'all']); // Obtener todas las preguntas con respuestas
    Route::post('/', [PreguntaController::class, 'store']); // Crear una nueva pregunta
    Route::get('/{pregunta}', [PreguntaController::class, 'show']); // Obtener una pregunta específica
    Route::put('/{pregunta}', [PreguntaController::class, 'update']); // Actualizar una pregunta
    Route::delete('/{pregunta}', [PreguntaController::class, 'destroy']); // Eliminar una pregunta
    Route::post('/grado/{grado}',[PreguntaController::class ,'getPreguntasConRespuestasPorGrado']);
    Route::post('/gardo_asignatura/{grado}/{asignatura}',[PreguntaController::class ,'getPreguntasConRespuestasPorGradoYAsignatura']);

    // Rutas para respuestas
    Route::post('/{pregunta}/respuestas', [PreguntaController::class, 'storeRespuesta']); // Crear una nueva respuesta para una pregunta
    Route::put('/{pregunta}/respuestas/{respuesta}', [PreguntaController::class, 'updateRespuesta']); // Actualizar una respuesta
    Route::delete('/{pregunta}/respuestas/{respuesta}', [PreguntaController::class, 'destroyRespuesta']); // Eliminar una respuesta

/* 
calcularDificultadPromedioPorGrado($gradoId)
calcularDificultadPromedioPorAsignatura($asignaturaId)
calcularDificultadPromedioYListadoPreguntas($gradoId, $asignaturaId)

*/

});

// se calcula la dificultad.
Route::prefix('calular_dificultad')->group(function () {
    Route::post('/grado/{grado}',[RespuestaPreguntaController::class ,'calcularDificultadPromedioPorGrado']);
    Route::post('/asignatura/{asignatura}',[RespuestaPreguntaController::class ,'calcularDificultadPromedioPorAsignatura']);
    Route::post('/gardo_asignatura/{grado}/{asignatura}',[RespuestaPreguntaController::class ,'calcularDificultadPromedioYListadoPreguntas']);
});

// Rutas para respuestas
Route::prefix('respuestas')->group(function () {
    Route::get('/', [RespuestaController::class, 'index']); // Listar respuestas
    Route::post('/', [RespuestaController::class, 'store']); // Crear una nueva respuesta
    Route::get('/{respuesta}', [RespuestaController::class, 'show']); // Mostrar una respuesta específica
    Route::put('/{respuesta}', [RespuestaController::class, 'update']); // Actualizar una respuesta
    Route::delete('/{respuesta}', [RespuestaController::class, 'destroy']); // Eliminar una respuesta
});


Route::prefix('asignatura')->group(function () {
    Route::get('/{grado_id}', [AsignaturaController::class, 'show']); // Listas de asignaturas
});