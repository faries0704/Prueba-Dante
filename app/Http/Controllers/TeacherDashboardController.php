<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class TeacherDashboardController extends Controller
{
    public function index()
    {
        // 1. Obtener el idteacher del usuario docente logueado
        $teacherId = DB::table('teachers')
            ->where('iduser', Auth::id())
            ->value('idteacher');

        if (!$teacherId) {
            Auth::logout();
            return redirect()->route('login')->with('error', 'Su perfil no está asignado como Docente.');
        }

        // 2. Tarjeta 1: Total de cursos asignados al docente
        $totalCursos = DB::table('course_teacher')
            ->where('idteacher', $teacherId)
            ->count();

        // 3. Tarjeta 2: Total de estudiantes únicos matriculados en sus secciones asignadas
        $totalAlumnos = DB::table('course_teacher as ct')
            ->join('sections as sec', 'ct.idcourse', '=', 'sec.idcourse')
            ->join('enrollments as e', 'sec.idsection', '=', 'e.idsection')
            ->where('ct.idteacher', $teacherId)
            ->where('e.status', 1)
            ->distinct('e.idstudent')
            ->count('e.idstudent');

        // 4. Tarjeta 3 y 4: Asistencias y Tardanzas tomadas por el docente en sus secciones
        $asistencias = DB::table('course_teacher as ct')
            ->join('sections as sec', 'ct.idcourse', '=', 'sec.idcourse')
            ->join('attendance as a', 'sec.idsection', '=', 'a.idsection')
            ->where('ct.idteacher', $teacherId)
            ->where('a.status', 'PRESENTE')
            ->count();

        $tardanzas = DB::table('course_teacher as ct')
            ->join('sections as sec', 'ct.idcourse', '=', 'sec.idcourse')
            ->join('attendance as a', 'sec.idsection', '=', 'a.idsection')
            ->where('ct.idteacher', $teacherId)
            ->where('a.status', 'TARDANZA')
            ->count();

        // 5. Bloque Inferior Izquierdo: Horario de dictado del Docente
        $horarioDocente = DB::table('course_teacher as ct')
            ->join('courses as c', 'ct.idcourse', '=', 'c.idcourse')
            ->join('sections as sec', 'c.idcourse', '=', 'sec.idcourse')
            ->join('schedules as sch', 'sec.idsection', '=', 'sch.idsection')
            ->select('c.course_name', 'sec.section_name', 'sch.day_week', 'sch.start_time', 'sch.end_time')
            ->where('ct.idteacher', $teacherId)
            ->orderByRaw("FIELD(sch.day_week, 'LUNES', 'MARTES', 'MIERCOLES', 'JUEVES', 'VIERNES')")
            ->orderBy('sch.start_time', 'ASC')
            ->get();

        // 6. Bloque Inferior Derecho: Cantidad de alumnos desaprobados (Promedio < 11) por cada curso
        // Útil para el gráfico de barras de alerta pedagógica.
        $alumnosRiesgo = DB::table('course_teacher as ct')
            ->join('courses as c', 'ct.idcourse', '=', 'c.idcourse')
            ->join('sections as sec', 'c.idcourse', '=', 'sec.idcourse')
            ->join('grades as g', 'sec.idsection', '=', 'g.idsection')
            ->select('c.course_name', DB::raw('COUNT(DISTINCT CASE WHEN g.grade < 11 THEN g.idstudent END) as total_desaprobados'))
            ->where('ct.idteacher', $teacherId)
            ->groupBy('c.idcourse', 'c.course_name')
            ->get();

        return view('teachers.dashboard', compact(
            'totalCursos',
            'totalAlumnos',
            'asistencias',
            'tardanzas',
            'horarioDocente',
            'alumnosRiesgo'
        ));
    }
}