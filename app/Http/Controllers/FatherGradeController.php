<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class FatherGradeController extends Controller
{
   public function index(Request $request)
    {
        // 1. Obtener el iduser del apoderado autenticado (Rol FATHER)
        $iduser = Auth::id();
        $father = DB::table('fathers')->where('iduser', $iduser)->first();

        if (!$father) {
            return redirect()->back()->with('error', 'No se encontró el perfil de padre de familia para este usuario.');
        }

        // 2. Obtener la lista de todos los hijos vinculados a este padre
        $students = DB::table('father_student as fs')
            ->join('students as s', 'fs.idstudent', '=', 's.idstudent')
            ->where('fs.idfather', $father->idfather)
            ->select('s.idstudent', 's.full_name')
            ->get();

        // 3. Capturar el hijo seleccionado en el filtro (si no viene ninguno, toma el primero por defecto)
        $selectedStudent = $request->input('idstudent') ?? ($students->first()->idstudent ?? null);

        $grades = collect();

        // 4. Si el padre tiene al menos un hijo registrado, consultar sus notas
        if ($selectedStudent) {
            $grades = DB::table('enrollments as e')
                ->join('sections as sec', 'e.idsection', '=', 'sec.idsection')
                ->join('courses as c', 'sec.idcourse', '=', 'c.idcourse')
                ->join('subgrades as sg', 'c.idsubgrade', '=', 'sg.idsubgrade') // Relación añadida para Subgrado
                ->join('degrees as d', 'sg.iddegree', '=', 'd.iddegree')        // Relación añadida para Grado
                ->join('semesters as sem', 'c.idsemester', '=', 'sem.idsemester')
                ->join('periods as p', 'sem.idperiod', '=', 'p.idperiod')
                // Left join para traer las notas del alumno específico
                ->leftJoin('grades as g', function($join) use ($selectedStudent) {
                    $join->on('c.idcourse', '=', 'g.idcourse')
                        ->on('sec.idsection', '=', 'g.idsection')
                        ->where('g.idstudent', '=', $selectedStudent);
                })
                ->select(
                    'p.period_name',
                    'sem.semester_name',
                    'd.degree_name',
                    'sg.subgrade_name',
                    'c.course_name',
                    'sec.section_name',
                    // Ponderaciones o promedios por cada tipo de evaluación del alumno escogido
                    DB::raw('ROUND(AVG(CASE WHEN g.idevaluation_type = 1 THEN g.grade END), 2) as nota_practica'),
                    DB::raw('ROUND(AVG(CASE WHEN g.idevaluation_type = 2 THEN g.grade END), 2) as nota_examen'),
                    DB::raw('ROUND(AVG(CASE WHEN g.idevaluation_type = 3 THEN g.grade END), 2) as nota_trabajo'),
                    DB::raw('ROUND(AVG(CASE WHEN g.idevaluation_type = 4 THEN g.grade END), 2) as nota_final'),
                    // Promedio general del curso para este estudiante
                    DB::raw('ROUND(AVG(g.grade), 2) as promedio_general')
                )
                ->where('e.idstudent', $selectedStudent)
                ->where('e.status', 1) // Matricula activa
                ->groupBy(
                    'p.period_name',
                    'sem.semester_name',
                    'd.degree_name',
                    'sg.subgrade_name',
                    'c.idcourse',
                    'c.course_name',
                    'sec.section_name'
                )
                ->orderBy('p.period_name', 'DESC')
                ->get();
        }

        return view('fathers.grades.index', compact('students', 'grades', 'selectedStudent'));
    }
}