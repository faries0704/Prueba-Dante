<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class StudentDashboardController extends Controller
{
    public function index()
    {
        // 1. Identificar al estudiante autenticado
        $studentId = DB::table('students')
            ->where('iduser', Auth::id())
            ->value('idstudent');

        if (!$studentId) {
            Auth::logout();
            return redirect()->route('login')->with('error', 'Tu usuario no tiene un perfil de estudiante asignado.');
        }

        // 2. Tarjeta 1: Total de Cursos Inscritos en periodos activos
        $totalCursos = DB::table('enrollments as e')
            ->join('sections as sec', 'e.idsection', '=', 'sec.idsection')
            ->where('e.idstudent', $studentId)
            ->where('e.status', 1)
            ->count();

        // 3. Tarjeta 2 e Tarjeta 3: Conteo de Asistencias y Faltas (Ausente)
        $asistencias = DB::table('attendance')
            ->where('idstudent', $studentId)
            ->where('status', 'PRESENTE')
            ->count();

        $faltas = DB::table('attendance')
            ->where('idstudent', $studentId)
            ->where('status', 'AUSENTE')
            ->count();

        // 4. Tarjeta 4: Promedio General de todos sus cursos
        $promedioGeneral = DB::table('grades')
            ->where('idstudent', $studentId)
            ->avg('grade');
        $promedioGeneral = $promedioGeneral ? round($promedioGeneral, 2) : 0.00;

        // 5. Bloque Inferior Izquierdo: Horario semanal del estudiante
        $horario = DB::table('enrollments as e')
            ->join('sections as sec', 'e.idsection', '=', 'sec.idsection')
            ->join('courses as c', 'sec.idcourse', '=', 'c.idcourse')
            ->join('schedules as sch', 'sec.idsection', '=', 'sch.idsection')
            ->select('c.course_name', 'sec.section_name', 'sch.day_week', 'sch.start_time', 'sch.end_time')
            ->where('e.idstudent', $studentId)
            ->where('e.status', 1)
            ->orderByRaw("FIELD(sch.day_week, 'LUNES', 'MARTES', 'MIERCOLES', 'JUEVES', 'VIERNES')")
            ->orderBy('sch.start_time', 'ASC')
            ->get();

        // 6. Bloque Inferior Derecho: Rendimiento por curso (Para tabla y gráfico)
        $rendimientoCursos = DB::table('enrollments as e')
            ->join('sections as sec', 'e.idsection', '=', 'sec.idsection')
            ->join('courses as c', 'sec.idcourse', '=', 'c.idcourse')
            ->leftJoin('grades as g', function($join) use ($studentId) {
                $join->on('c.idcourse', '=', 'g.idcourse')
                     ->on('sec.idsection', '=', 'g.idsection')
                     ->where('g.idstudent', '=', $studentId);
            })
            ->select(
                'c.course_name',
                DB::raw('ROUND(AVG(g.grade), 2) as promedio_curso')
            )
            ->where('e.idstudent', $studentId)
            ->where('e.status', 1)
            ->groupBy('c.idcourse', 'c.course_name')
            ->get();

        return view('students.dashboard', compact(
            'totalCursos', 
            'asistencias', 
            'faltas', 
            'promedioGeneral', 
            'horario', 
            'rendimientoCursos'
        ));
    }
}