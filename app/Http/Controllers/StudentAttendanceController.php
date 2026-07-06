<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Period;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class StudentAttendanceController extends Controller
{
    public function index(Request $request)
    {
        // 1. Obtener el iduser del estudiante autenticado
        $userId = Auth::id();
        $student = Student::where('iduser', $userId)->first();

        if (!$student) {
            return redirect()->back()->with('error', 'No se encontró el perfil de estudiante vinculado a este usuario.');
        }

        // 2. Obtener el resumen de asistencias y metadatos de matrícula
        $attendances = DB::table('enrollments as e')
            ->join('sections as s', 'e.idsection', '=', 's.idsection')
            ->join('courses as c', 's.idcourse', '=', 'c.idcourse')
            ->join('semesters as sem', 'c.idsemester', '=', 'sem.idsemester')
            ->join('periods as p', 'sem.idperiod', '=', 'p.idperiod')
            ->leftJoin('attendance as a', function($join) use ($student) {
                $join->on('s.idsection', '=', 'a.idsection')
                     ->where('a.idstudent', '=', $student->idstudent);
            })
            ->where('e.idstudent', $student->idstudent)
            ->where('e.status', 1) // Matrícula activa
            ->select(
                'p.period_name',
                'c.course_name',
                's.section_name',
                's.idsection',
                DB::raw("SUM(CASE WHEN a.status = 'PRESENTE' THEN 1 ELSE 0 END) as total_presentes"),
                DB::raw("SUM(CASE WHEN a.status = 'TARDANZA' THEN 1 ELSE 0 END) as total_tardanzas"),
                DB::raw("SUM(CASE WHEN a.status = 'AUSENTE' THEN 1 ELSE 0 END) as total_ausentes"),
                DB::raw("COUNT(a.idattendance) as total_clases_tomadas")
            )
            ->groupBy('p.period_name', 'c.course_name', 's.section_name', 's.idsection')
            ->get();

        return view('students.attendance.index', compact('attendances'));
    }

    // AJAX: Obtener el desglose de fechas de asistencia para un curso/sección específico
    public function getDetails(Request $request)
    {
        $userId = Auth::id();
        $student = Student::where('iduser', $userId)->first();
        $idsection = $request->idsection;

        if (!$student) {
            return response()->json(['error' => 'Perfil no encontrado'], 404);
        }

        $details = DB::table('attendance as a')
            ->join('sections as s', 'a.idsection', '=', 's.idsection')
            ->where('a.idstudent', $student->idstudent)
            ->where('a.idsection', $idsection)
            ->select('a.attendance_date', 'a.status')
            ->orderBy('a.attendance_date', 'DESC')
            ->get();

        return response()->json($details);
    }
}