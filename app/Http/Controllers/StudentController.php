<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorestudentRequest;
use App\Http\Requests\UpdatestudentRequest;
use App\Models\student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    // ... otros métodos ...

    /**
     * Crea o actualiza un estudiante basado en el id_student.
     */
    public function createOrUpdateStudent(Request $request)
    {
        $idStudent = $request->id_student;
        $score = $request->score; // Score por defecto es 0 si no se proporciona

        $student = Student::updateOrCreate(
            ['id_student' => $idStudent],
            ['score' => $score]
        );

        return response()->json($student);
    }

    /**
     * Obtiene la posición del estudiante basado en su puntuación.
     */
    public function getStudentPosition($idStudent)
    {
        $student = Student::where('id_student', $idStudent)->first();

        if (!$student) {
            return response()->json(['error' => 'Estudiante no encontrado'], 404);
        }

        $higherScoreCount = Student::where('score', '>', $student->score)->count();
        $position = $higherScoreCount + 1;

        return response()->json(['position' => $position,
    'score'=>$student->score
    ]);
    }

}
