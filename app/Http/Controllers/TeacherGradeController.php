<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use App\Models\Teacher;
use App\Models\Period;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class TeacherGradeController extends Controller
{
    public function index(Request $request)
    {
        // 1. Obtener el iduser del profesor autenticado
        $userId = Auth::id();
        $teacher = Teacher::where('iduser', $userId)->first();

        if (!$teacher) {
            return redirect()->back()->with('error', 'No se encontró el perfil de docente vinculado a este usuario.');
        }

        // 2. Obtener los Periodos para el filtro superior
        $periods = Period::where('status', 1)->orderBy('created_at', 'DESC')->get();

        // 3. Obtener SOLO los cursos que este docente enseña para llenar su filtro de cursos
        $teacherCourses = DB::table('courses as c')
            ->join('course_teacher as ct', 'c.idcourse', '=', 'ct.idcourse')
            ->where('ct.idteacher', $teacher->idteacher)
            ->where('c.status', 1)
            ->select('c.idcourse', 'c.course_name')
            ->get();

        // 4. Capturar los filtros seleccionados
        $selectedPeriod = $request->input('idperiod');
        $selectedCourse = $request->input('idcourse');

        // 5. Construir la consulta de calificaciones filtrando por el profesor logueado
        $query = DB::table('grades as g')
            ->join('courses as c', 'g.idcourse', '=', 'c.idcourse')
            ->join('subgrades as sg', 'c.idsubgrade', '=', 'sg.idsubgrade')
            ->join('degrees as d', 'sg.iddegree', '=', 'd.iddegree')
            ->join('semesters as sem', 'c.idsemester', '=', 'sem.idsemester')
            ->join('periods as p', 'sem.idperiod', '=', 'p.idperiod')
            ->join('sections as sec', 'g.idsection', '=', 'sec.idsection')
            ->join('course_teacher as ct', 'c.idcourse', '=', 'ct.idcourse')
            ->where('ct.idteacher', $teacher->idteacher);

        // Aplicar filtro condicional de Periodo
        if (!empty($selectedPeriod)) {
            $query->where('p.idperiod', $selectedPeriod);
        }

        // Aplicar filtro condicional de Curso
        if (!empty($selectedCourse)) {
            $query->where('c.idcourse', $selectedCourse);
        }

        // Calcular promedios grupales
        $grades = $query->select(
                'p.period_name',
                'sem.semester_name',
                'd.degree_name',
                'sg.subgrade_name',
                'sec.section_name',
                'sec.idsection',
                'c.course_name',
                'c.idcourse',
                DB::raw('COUNT(DISTINCT g.idstudent) as total_students'),
                DB::raw('ROUND(AVG(CASE WHEN g.idevaluation_type = 1 THEN g.grade END), 2) as avg_practica'),
                DB::raw('ROUND(AVG(CASE WHEN g.idevaluation_type = 2 THEN g.grade END), 2) as avg_examen'),
                DB::raw('ROUND(AVG(g.grade), 2) as section_average')
            )
            ->groupBy(
                'p.period_name',
                'sem.semester_name',
                'd.degree_name',
                'sg.subgrade_name',
                'sec.section_name',
                'sec.idsection',
                'c.course_name',
                'c.idcourse'
            )
            ->orderBy('p.period_name', 'DESC')
            ->get();

        return view('teachers.grades.index', compact('grades', 'periods', 'teacherCourses', 'selectedPeriod', 'selectedCourse'));
    }

    // AJAX: Obtener tipos de evaluación para el modal
    public function getEvaluationTypes()
    {
        return response()->json(DB::table('evaluation_types')->get());
    }

    public function create()
    {
        $userId = Auth::id();
        $teacher = Teacher::where('iduser', $userId)->first();

        if (!$teacher) {
            return redirect()->back()->with('error', 'No se encontró el perfil de docente vinculado a este usuario.');
        }

        // CORRECCIÓN: Filtramos los cursos para que SOLO aparezcan los del semestre ACTIVO (status = 1)
        $courses = DB::table('courses as c')
            ->join('course_teacher as ct', 'c.idcourse', '=', 'ct.idcourse')
            ->join('semesters as sem', 'c.idsemester', '=', 'sem.idsemester') // Unimos con semestres
            ->where('ct.idteacher', $teacher->idteacher)
            ->where('c.status', 1)
            ->where('sem.status', 1) // <-- CRUCIAL: Solo semestre abierto
            ->select('c.idcourse', 'c.course_name', 'sem.semester_name')
            ->get();

        $evaluationTypes = DB::table('evaluation_types')->get();

        return view('teachers.grades.create', compact('courses', 'evaluationTypes'));
    }

    // AJAX: Obtener las secciones asignadas al curso seleccionado
    public function getSections($idcourse)
    {
        // CORRECCIÓN: Nos aseguramos de que el curso también pertenezca al semestre activo por seguridad
        $sections = DB::table('sections as sec')
            ->join('courses as c', 'sec.idcourse', '=', 'c.idcourse')
            ->join('semesters as sem', 'c.idsemester', '=', 'sem.idsemester')
            ->where('sec.idcourse', $idcourse)
            ->where('sec.status', 1)
            ->where('sem.status', 1) // <-- Solo si el semestre está activo
            ->select('sec.idsection', 'sec.section_name')
            ->get();

        return response()->json($sections);
    }

    // AJAX: Obtener alumnos y calificaciones existentes para edición
    public function getEditData(Request $request)
    {
        // Mantenemos tu consulta, el filtro del frontend ya restringe el curso
        $data = DB::table('students as s')
            ->join('users as u', 's.iduser', '=', 'u.iduser')
            ->join('enrollments as e', 's.idstudent', '=', 'e.idstudent')
            ->leftJoin('grades as g', function($join) use ($request) {
                $join->on('s.idstudent', '=', 'g.idstudent')
                    ->where('g.idcourse', $request->idcourse)
                    ->where('g.idsection', $request->idsection)
                    ->where('g.idevaluation_type', $request->idevaluation_type);
            })
            ->select('s.idstudent', 's.full_name', 'u.photo', 'g.grade')
            ->where('e.idsection', $request->idsection)
            ->where('e.status', 1)
            ->orderBy('s.full_name')
            ->get();

        return response()->json($data);
    }

    // Procesar actualización masiva de notas desde el modal
    public function update(Request $request)
    {
        $request->validate([
            'idcourse'          => 'required',
            'idsection'         => 'required',
            'idevaluation_type' => 'required',
            'notas'             => 'required|array'
        ]);

        // SEGURIDAD BACKEND: Verificar si el semestre del curso sigue activo antes de guardar
        $isSemesterActive = DB::table('courses as c')
            ->join('semesters as sem', 'c.idsemester', '=', 'sem.idsemester')
            ->where('c.idcourse', $request->idcourse)
            ->where('sem.status', 1) // <-- Debe estar activo
            ->exists();

        if (!$isSemesterActive) {
            return response()->json([
                'success' => false, 
                'message' => 'El semestre correspondiente a este curso ya ha sido cerrado. No se pueden registrar ni modificar notas.'
            ], 422); // Código 422: Unprocessable Entity
        }

        try {
            DB::beginTransaction();

            foreach ($request->notas as $idstudent => $grade_value) {
                if ($grade_value !== null && $grade_value !== '') {
                    Grade::updateOrCreate(
                        [
                            'idstudent'         => $idstudent,
                            'idcourse'          => $request->idcourse,
                            'idsection'         => $request->idsection,
                            'idevaluation_type' => $request->idevaluation_type,
                        ],
                        [
                            'grade'             => $grade_value,
                            'created_at'        => now()
                        ]
                    );
                }
            }

            DB::commit();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}