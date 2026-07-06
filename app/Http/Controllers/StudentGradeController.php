<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class StudentGradeController extends Controller
{
    public function index()
{
    // 1. Obtener el idstudent del usuario que ha iniciado sesión
    $studentId = DB::table('students')
        ->where('iduser', Auth::id())
        ->value('idstudent');

    if (!$studentId) {
        return redirect()->route('dashboard.index')
            ->with('error', 'No se encontró un perfil de estudiante asociado a este usuario.');
    }

    // 2. Consultar las notas del estudiante agrupadas por curso, semestre y periodo
    $grades = DB::table('enrollments as e')
        ->join('sections as sec', 'e.idsection', '=', 'sec.idsection')
        ->join('courses as c', 'sec.idcourse', '=', 'c.idcourse')
        ->join('semesters as sem', 'c.idsemester', '=', 'sem.idsemester')
        ->join('periods as p', 'sem.idperiod', '=', 'p.idperiod')
        // Hacemos leftJoin con grades asegurando filtrar por el estudiante actual
        ->leftJoin('grades as g', function($join) use ($studentId) {
            $join->on('c.idcourse', '=', 'g.idcourse')
                 ->on('sec.idsection', '=', 'g.idsection')
                 ->where('g.idstudent', '=', $studentId);
        })
        ->select(
            'p.period_name',
            'sem.semester_name',
            'c.course_name',
            'sec.section_name',
            // Ponderaciones/Filtros por cada tipo de evaluación
            DB::raw('ROUND(AVG(CASE WHEN g.idevaluation_type = 1 THEN g.grade END), 2) as nota_practica'),
            DB::raw('ROUND(AVG(CASE WHEN g.idevaluation_type = 2 THEN g.grade END), 2) as nota_examen'),
            DB::raw('ROUND(AVG(CASE WHEN g.idevaluation_type = 3 THEN g.grade END), 2) as nota_trabajo'),
            DB::raw('ROUND(AVG(CASE WHEN g.idevaluation_type = 4 THEN g.grade END), 2) as nota_final'),
            // Promedio general de todas las notas de este curso
            DB::raw('ROUND(AVG(g.grade), 2) as promedio_general')
        )
        ->where('e.idstudent', $studentId)
        ->where('e.status', 1) // Asegurar matrícula activa si aplica
        ->groupBy(
            'p.period_name',
            'sem.semester_name',
            'c.idcourse',
            'c.course_name',
            'sec.section_name'
        )
        ->orderBy('p.period_name', 'DESC')
        ->get();

    return view('students.grades.index', compact('grades'));
}
}