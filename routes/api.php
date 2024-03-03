<?php

use App\Http\Controllers\AsignaturaController;
use App\Http\Controllers\PreguntaController;
use App\Http\Controllers\PreguntasMalasController;
use App\Http\Controllers\RespuestaController;
use App\Http\Controllers\RespuestaPreguntaController;
use App\Http\Controllers\ResultadosAsignaturasController;
use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Route;

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

Route::get('/eliminarPreguntasRepetidas', [PreguntaController::class, 'eliminarPreguntasRepetidas']);

// Rutas para preguntas
Route::prefix('preguntas')->group(function () {
    Route::get('/', [PreguntaController::class, 'all']); // Obtener todas las preguntas con respuestas
    Route::post('/', [PreguntaController::class, 'store']); // Crear una nueva pregunta
    Route::get('/{pregunta}', [PreguntaController::class, 'show']); // Obtener una pregunta específica
    Route::put('/{pregunta}', [PreguntaController::class, 'update']); // Actualizar una pregunta
    Route::delete('/{pregunta}', [PreguntaController::class, 'destroy']); // Eliminar una pregunta
    Route::get('/grado/{grado}/{cantidad}', [PreguntaController::class, 'getPreguntasConRespuestasPorGrado']);
    Route::get('/asignatura/{asignatura}/{cantidad}', [PreguntaController::class, 'getPreguntasConRespuestasPorAsignatura']);
    Route::get('/gardo_asignatura/{grado}/{asignatura}/{cantidad}', [PreguntaController::class, 'getPreguntasConRespuestasPorGradoYAsignatura']);

    // Rutas para respuestas
    Route::post('/{pregunta}/respuestas', [PreguntaController::class, 'storeRespuesta']); // Crear una nueva respuesta para una pregunta
    Route::put('/{pregunta}/respuestas/{respuesta}', [PreguntaController::class, 'updateRespuesta']); // Actualizar una respuesta
    Route::delete('/{pregunta}/respuestas/{respuesta}', [PreguntaController::class, 'destroyRespuesta']); // Eliminar una respuesta

    /* 
    calcularDificultadPromedioPorGrado($gradoId)
    calcularDificultadPromedioPorAsignatura($asignaturaId)
    calcularDificultadPromedioYListadoPreguntas($gradoId, $asignaturaId)

    */

    Route::post('/preguntas-malas', [PreguntasMalasController::class, 'store']);

});
Route::prefix('students')->group(function () {

    Route::post('/', [StudentController::class, 'createOrUpdateStudent']);
    Route::get('/position/{id}', [StudentController::class, 'getStudentPosition']);

});







//hola ya se guardo sera?

// se calcula la dificultad.
Route::prefix('responded_answers')->group(function () {
    Route::post('/createRespondedAnswers', [RespuestaPreguntaController::class, 'createRespondedAnswers']);
    Route::post('/asignatura/{asignatura}', [RespuestaPreguntaController::class, 'calcularDificultadPromedioPorAsignatura']);
    Route::post('/gardo_asignatura/{grado}/{asignatura}', [RespuestaPreguntaController::class, 'calcularDificultadPromedioYListadoPreguntas']);
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

// registrar resultado asignaturas ResultadosAsignaturasController
Route::prefix('resultadoasignatura')->group(function () {
    Route::post('/crear', [ResultadosAsignaturasController::class, 'store']); // Listas de asignaturas
    Route::get('/resultados/{idU}', [ResultadosAsignaturasController::class, 'resultadosPorEstudiante']);

});
// registrar resultado students StudentController
Route::prefix('student')->group(function () {
    Route::post('/crear', [StudentController::class, 'createOrUpdateStudent']); // Listas de asignaturas
    Route::get('/get/{idU}', [StudentController::class, 'getStudentPosition']);

});


