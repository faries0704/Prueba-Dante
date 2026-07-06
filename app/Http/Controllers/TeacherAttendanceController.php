<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Teacher;
use App\Models\Student;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class TeacherAttendanceController extends Controller
{

    // MUESTRA EL RESUMEN DE ASISTENCIAS FILTRADO POR EL DOCENTE LOGUEADO
    // MUESTRA EL RESUMEN DE ASISTENCIAS FILTRADO POR EL DOCENTE LOGUEADO Y PERIODO
    public function index(Request $request)
{
    // 1. Obtener el iduser del profesor autenticado
    $userId = Auth::id();
    $teacher = Teacher::where('iduser', $userId)->first();

    if (!$teacher) {
        return redirect()->back()->with('error', 'No se encontró el perfil de docente vinculado a este usuario.');
    }

    // 2. Construir la consulta de asistencias filtrada rigurosamente por los cursos asignados al docente
    $attendances = DB::table('attendance as a')
        ->join('sections as s', 'a.idsection', '=', 's.idsection')
        ->join('courses as c', 's.idcourse', '=', 'c.idcourse')
        ->join('degrees as d', 'c.iddegree', '=', 'd.iddegree')
        ->join('subgrades as sg', 'c.idsubgrade', '=', 'sg.idsubgrade')
        ->join('semesters as sem', 'c.idsemester', '=', 'sem.idsemester')
        ->join('periods as p', 'sem.idperiod', '=', 'p.idperiod')
        ->join('course_teacher as ct', 'c.idcourse', '=', 'ct.idcourse')
        ->where('ct.idteacher', $teacher->idteacher)
        ->select(
            'p.period_name',
            'a.attendance_date',
            'd.degree_name',
            'sg.subgrade_name',
            's.section_name',
            'c.course_name',
            'a.idsection',
            'c.idcourse',
            DB::raw('COUNT(a.idstudent) as total_students'),
            DB::raw("SUM(CASE WHEN a.status = 'PRESENTE' THEN 1 ELSE 0 END) as total_presentes"),
            DB::raw("SUM(CASE WHEN a.status = 'AUSENTE' THEN 1 ELSE 0 END) as total_ausentes"),
            DB::raw("SUM(CASE WHEN a.status = 'TARDANZA' THEN 1 ELSE 0 END) as total_tardanzas")
        )
        ->groupBy(
            'p.period_name', 
            'a.attendance_date', 
            'd.degree_name', 
            'sg.subgrade_name', 
            's.section_name', 
            'c.course_name', 
            'a.idsection', 
            'c.idcourse'
        )
        ->orderBy('a.attendance_date', 'DESC')
        ->get();

    // Enviamos los datos limpios. Las opciones de los filtros se generarán dinámicamente con los registros del profesor
    return view('teachers.attendance.index', compact('attendances'));
}

    // AJAX: OBTIENE LOS ALUMNOS Y SUS ESTADOS PARA EL MODAL DE INFORMACIÓN (VISIBILITY)
    public function showDetails(Request $request)
    {
        $date = $request->attendance_date;
        $idsection = $request->idsection;

        $details = DB::table('attendance as a')
            ->join('students as st', 'a.idstudent', '=', 'st.idstudent')
            ->leftJoin('users as u', 'st.iduser', '=', 'u.iduser')
            ->where('a.attendance_date', $date)
            ->where('a.idsection', $idsection)
            ->select(
                'st.full_name',
                'u.photo',
                'a.status',
                'a.created_at'
            )
            ->orderBy('st.full_name', 'ASC')
            ->get();

        return response()->json($details);
    }

    // Mostrar la vista de filtros simplificada para el docente
    public function create()
    {
        // 1. Obtener el iduser del profesor autenticado
        $userId = Auth::id();

        // 2. Encontrar el registro del profesor en la tabla 'teachers'
        $teacher = Teacher::where('iduser', $userId)->first();

        if (!$teacher) {
            return redirect()->back()->with('error', 'No se encontró el perfil de docente vinculado a este usuario.');
        }

        // 3. Obtener SOLO los cursos asignados a este docente en la tabla pivote 'course_teacher'
        $courses = DB::table('courses as c')
            ->join('course_teacher as ct', 'c.idcourse', '=', 'ct.idcourse')
            ->where('ct.idteacher', $teacher->idteacher)
            ->where('c.status', 1)
            ->select('c.idcourse', 'c.course_name')
            ->get();

        return view('teachers.attendance.create', compact('courses'));
    }

    // AJAX: Obtener las secciones asociadas al curso seleccionado
    public function getSections($idcourse)
    {
        $sections = DB::table('sections')
            ->where('idcourse', $idcourse)
            ->where('status', 1)
            ->select('idsection', 'section_name')
            ->get();

        return response()->json($sections);
    }

    // AJAX: Obtener los alumnos matriculados en la sección seleccionada
    public function getStudentsBySection($idsection)
    {
        $students = DB::table('students as st')
            ->join('enrollments as e', 'st.idstudent', '=', 'e.idstudent')
            ->leftJoin('users as u', 'st.iduser', '=', 'u.iduser')
            ->where('e.idsection', $idsection)
            ->where('e.status', 1) // Matriculados activos
            ->select('st.idstudent', 'st.full_name', 'u.photo')
            ->orderBy('st.full_name', 'ASC')
            ->get();

        return response()->json($students);
    }

    // Guardar o actualizar el listado de asistencias generadas
    public function store(Request $request)
    {
        $request->validate([
            'attendance_date' => 'required|date',
            'seccion'         => 'required|exists:sections,idsection',
            'asistencias'     => 'required|array'
        ]);

        try {
            DB::beginTransaction();

            $idsection = $request->seccion;
            $date = $request->attendance_date;

            foreach ($request->asistencias as $idstudent => $status) {
                // Registrar o actualizar asistencia
                Attendance::updateOrCreate(
                    [
                        'idstudent'       => $idstudent,
                        'idsection'       => $idsection,
                        'attendance_date' => $date,
                    ],
                    [
                        'status'          => strtoupper($status),
                        'created_at'      => Carbon::now() 
                    ]
                );

                // Obtener datos del estudiante con sus relaciones para las notificaciones
                $student = Student::with(['user', 'fathers.user'])->find($idstudent);

                // 1. Notificación al Alumno
                if ($student && $student->user) {
                    Notification::create([
                        'iduser'      => $student->user->iduser,
                        'title'       => 'Registro de asistencia',
                        'description' => 'Tu asistencia del día ' . $date . ' fue registrada como: ' . strtoupper($status),
                        'type'        => 'ATTENDANCE',
                        'is_read'     => 0,
                        'created_at'  => now()
                    ]);
                }

                // 2. Notificación al Apoderado/Padre
                if ($student && $student->fathers) {
                    foreach ($student->fathers as $father) {
                        if ($father->user) {
                            Notification::create([
                                'iduser'      => $father->user->iduser,
                                'title'       => 'Asistencia de ' . $student->full_name,
                                'description' => 'La asistencia de ' . $student->full_name . ' del día ' . $date . ' fue registrada como: ' . strtoupper($status),
                                'type'        => 'ATTENDANCE',
                                'is_read'     => 0,
                                'created_at'  => now()
                            ]);
                        }
                    }
                }
            }

            DB::commit();

            if ($request->ajax()) {
                return response()->json(['message' => 'Success']);
            }

            return redirect()->route('teacher.attendance.index')->with('attendance_success', 'OK');

        } catch (\Exception $e) {
            DB::rollBack();
            if ($request->ajax()) {
                return response()->json(['error' => $e->getMessage()], 500);
            }
            return redirect()->back()->withInput()->with('attendance_error', 'OK');
        }
    }
}