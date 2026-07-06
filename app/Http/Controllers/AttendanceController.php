<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Period;
use App\Models\Section;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Notification;
use App\Models\Student;

class AttendanceController extends Controller
{
    public function index()
    {
        $attendances = DB::table('attendance as a')
            ->join('sections as s', 'a.idsection', '=', 's.idsection')
            ->join('courses as c', 's.idcourse', '=', 'c.idcourse')
            ->join('degrees as d', 'c.iddegree', '=', 'd.iddegree') // Relación añadida para Grado
            ->join('subgrades as sub', 'c.idsubgrade', '=', 'sub.idsubgrade') // Relación añadida para Subgrado
            ->join('semesters as sem', 'c.idsemester', '=', 'sem.idsemester')
            ->join('periods as p', 'sem.idperiod', '=', 'p.idperiod')
            ->select(
                'p.period_name',
                'a.attendance_date',
                's.section_name',
                'c.course_name',
                'd.degree_name',     // Campo añadido
                'sub.subgrade_name', // Campo añadido
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
                's.section_name',
                'c.course_name',
                'd.degree_name',
                'sub.subgrade_name',
                'a.idsection',
                'c.idcourse'
            )
            ->orderBy('a.attendance_date', 'DESC')
            ->get();

        return view('attendance.index', compact('attendances'));
    }

    public function create()
    {
        $periods = Period::where('status', 1)->get();

        return view('attendance.create', compact('periods'));
    }

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

                $student = Student::with(['user', 'fathers.user'])
                    ->find($idstudent);

                // Notificación alumno
                if ($student && $student->user) {
                    Notification::create([
                        'iduser' => $student->user->iduser,
                        'title' => 'Registro de asistencia',
                        'description' => 'Tu asistencia del día ' . $date .
                                         ' fue registrada como: ' . strtoupper($status),
                        'type' => 'ATTENDANCE',
                        'is_read' => 0,
                        'created_at' => now()
                    ]);
                }

                // Notificación padres
                foreach ($student->fathers as $father) {
                    if ($father->user) {
                        Notification::create([
                            'iduser' => $father->user->iduser,
                            'title' => 'Asistencia de ' . $student->full_name,
                            'description' => 'La asistencia de ' . $student->full_name .
                                             ' del día ' . $date .
                                             ' fue registrada como: ' . strtoupper($status),
                            'type' => 'ATTENDANCE',
                            'is_read' => 0,
                            'created_at' => now()
                        ]);
                    }
                }
            }

            DB::commit();

            if ($request->ajax()) {
                return response()->json(['message' => 'Success']);
            }

            return redirect()->route('attendance.index')->with('attendance_success', 'OK');

        } catch (\Exception $e) {
            DB::rollBack();
            if ($request->ajax()) {
                return response()->json(['error' => $e->getMessage()], 500);
            }
            return redirect()->back()->withInput()->with('attendance_error', 'OK');
        }
    }

    public function getEditData(Request $request)
    {
        $date = $request->attendance_date;
        $idsection = $request->idsection;

        $students = DB::table('students as st')
            // TABLA CORRECTA
            ->join('enrollments as e', 'st.idstudent', '=', 'e.idstudent')
            ->leftJoin('attendance as a', function ($join) use ($date, $idsection) {
                $join->on('st.idstudent', '=', 'a.idstudent')
                    ->where('a.attendance_date', '=', $date)
                    ->where('a.idsection', '=', $idsection);
            })
            ->leftJoin('users as u', 'st.iduser', '=', 'u.iduser')
            ->where('e.idsection', $idsection)
            ->select(
                'st.idstudent',
                'st.full_name',
                'u.photo',
                DB::raw('COALESCE(a.status, "PRESENTE") as status')
            )
            ->get();

        return response()->json($students);
    }

    public function update(Request $request)
    {
        $request->validate([
            'attendance_date' => 'required|date',
            'seccion'         => 'required',
            'asistencias'     => 'required|array'
        ]);

        try {
            DB::beginTransaction();

            $date = $request->attendance_date;
            $idsection = $request->seccion;

            foreach ($request->asistencias as $idstudent => $status) {
                DB::table('attendance')->updateOrInsert(
                    [
                        'idstudent'       => $idstudent,
                        'idsection'       => $idsection,
                        'attendance_date' => $date
                    ],
                    [
                        'status'     => strtoupper($status),
                        'created_at' => now()
                    ]
                );
            }

            DB::commit();

            return response()->json([
                'success' => true
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

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
}