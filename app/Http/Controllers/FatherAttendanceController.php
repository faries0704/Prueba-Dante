<?php

namespace App\Http\Controllers;

use App\Models\Period;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class FatherAttendanceController extends Controller
{
    public function index(Request $request)
    {
        // 1. Obtener el iduser del apoderado autenticado
        $userId = Auth::id();
        $father = DB::table('fathers')->where('iduser', $userId)->first();

        if (!$father) {
            return redirect()->back()->with('error', 'No se encontró el perfil de apoderado vinculado a este usuario.');
        }

        // 2. Obtener los IDs de los estudiantes vinculados a este apoderado
        $studentIds = DB::table('father_student')
            ->where('idfather', $father->idfather)
            ->pluck('idstudent');

        // 3. Obtener el resumen de asistencias de TODOS los hijos del apoderado
        $attendances = DB::table('enrollments as e')
            ->join('students as st', 'e.idstudent', '=', 'st.idstudent')
            ->join('sections as s', 'e.idsection', '=', 's.idsection')
            ->join('courses as c', 's.idcourse', '=', 'c.idcourse')
            ->join('semesters as sem', 'c.idsemester', '=', 'sem.idsemester')
            ->join('periods as p', 'sem.idperiod', '=', 'p.idperiod')
            ->leftJoin('attendance as a', function($join) {
                $join->on('s.idsection', '=', 'a.idsection')
                     ->on('e.idstudent', '=', 'a.idstudent');
            })
            ->whereIn('e.idstudent', $studentIds)
            ->where('e.status', 1) // Matrícula activa
            ->select(
                'st.idstudent',
                'st.full_name as student_name',
                'p.period_name',
                'c.course_name',
                's.section_name',
                's.idsection',
                DB::raw("SUM(CASE WHEN a.status = 'PRESENTE' THEN 1 ELSE 0 END) as total_presentes"),
                DB::raw("SUM(CASE WHEN a.status = 'TARDANZA' THEN 1 ELSE 0 END) as total_tardanzas"),
                DB::raw("SUM(CASE WHEN a.status = 'AUSENTE' THEN 1 ELSE 0 END) as total_ausentes"),
                DB::raw("COUNT(a.idattendance) as total_clases_tomadas")
            )
            ->groupBy('st.idstudent', 'st.full_name', 'p.period_name', 'c.course_name', 's.section_name', 's.idsection')
            ->get();

        return view('fathers.attendance.index', compact('attendances'));
    }

    // AJAX: Obtener el desglose de fechas de asistencia para un hijo y curso específico
    public function getDetails(Request $request)
    {
        $userId = Auth::id();
        $father = DB::table('fathers')->where('iduser', $userId)->first();
        
        $idsection = $request->idsection;
        $idstudent = $request->idstudent;

        if (!$father) {
            return response()->json(['error' => 'Perfil no encontrado'], 404);
        }

        // Validación de seguridad: Verificar que el estudiante consultado pertenezca realmente al apoderado
        $isChild = DB::table('father_student')
            ->where('idfather', $father->idfather)
            ->where('idstudent', $idstudent)
            ->exists();

        if (!$isChild) {
            return response()->json(['error' => 'Acceso no autorizado al historial del estudiante.'], 403);
        }

        $details = DB::table('attendance as a')
            ->where('a.idstudent', $idstudent)
            ->where('a.idsection', $idsection)
            ->select('a.attendance_date', 'a.status')
            ->orderBy('a.attendance_date', 'DESC')
            ->get();

        return response()->json($details);
    }
}