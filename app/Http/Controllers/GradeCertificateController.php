<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use App\Models\GradeCertificate;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * HU-13: Emisión de Constancias de Notas.
 * El administrador emite constancias con las calificaciones del alumno
 * en su periodo escolar más reciente, con vista previa e impresión en PDF.
 */
class GradeCertificateController extends Controller
{
    /**
     * Historial de constancias de notas emitidas (13.8).
     */
    public function index()
    {
        $certificates = GradeCertificate::with(['student', 'period'])
            ->orderBy('idgradecertificate', 'DESC')
            ->get();

        return view('grade_certificates.index', compact('certificates'));
    }

    /**
     * Formulario de emisión.
     */
    public function create()
    {
        $students = Student::whereHas('user', function ($query) {
                $query->where('status', 1);
            })
            ->orderBy('full_name')
            ->get();

        return view('grade_certificates.create', compact('students'));
    }

    /**
     * AJAX: consulta de notas del alumno para la vista previa (13.4).
     * Incluye la validación de integridad de la información (13.7).
     */
    public function getStudentData($id)
    {
        $student = Student::with('user')->find($id);

        if (!$student) {
            return response()->json(['ok' => false, 'message' => 'El alumno no existe.'], 404);
        }

        if (!$student->user || $student->user->status != 1) {
            return response()->json(['ok' => false, 'message' => 'El alumno no tiene un usuario activo en el sistema.']);
        }

        $report = $this->gradesReport($student->idstudent);

        if (!$report) {
            return response()->json(['ok' => false, 'message' => 'El alumno no tiene calificaciones registradas. No es posible emitir la constancia.']);
        }

        return response()->json([
            'ok'      => true,
            'student' => [
                'full_name' => $student->full_name,
                'dni'       => $student->dni,
            ],
            'report'   => [
                'period_name'     => $report['period_name'],
                'courses'         => array_map(function ($c) {
                    return ['course' => $c['course'], 'average' => $c['average']];
                }, $report['courses']),
                'general_average' => $report['general_average'],
            ],
            'warnings' => $report['warnings'],
        ]);
    }

    /**
     * Registra y genera automáticamente la constancia (13.5).
     */
    public function store(Request $request)
    {
        $request->validate([
            'idstudent'    => 'required|integer|exists:students,idstudent',
            'purpose'      => 'required|string|max:255',
            'issue_date'   => 'required|date',
            'observations' => 'nullable|string|max:1000',
        ]);

        $student = Student::with('user')->findOrFail($request->idstudent);

        // Validación: usuario del alumno activo
        if (!$student->user || $student->user->status != 1) {
            return back()->withInput()->withErrors([
                'idstudent' => 'El alumno seleccionado no tiene un usuario activo.',
            ]);
        }

        // Validación de integridad (13.7): debe tener notas registradas
        $report = $this->gradesReport($student->idstudent);
        if (!$report) {
            return back()->withInput()->withErrors([
                'idstudent' => 'El alumno no tiene calificaciones registradas. No es posible emitir la constancia.',
            ]);
        }

        $enrollment = $this->activeEnrollment($student->idstudent);

        $certificate = GradeCertificate::create([
            'certificate_code' => $this->generateCode(),
            'idstudent'        => $student->idstudent,
            'idenrollment'     => $enrollment?->idenrollment,
            'idperiod'         => $report['idperiod'],
            'purpose'          => $request->purpose,
            'observations'     => $request->observations,
            'issue_date'       => $request->issue_date,
            'issued_by'        => Auth::id(),
            'status'           => 1, // Vigente
        ]);

        return redirect()
            ->route('gradeCertificates.preview', $certificate->idgradecertificate)
            ->with('creado', 'OK');
    }

    /**
     * Vista previa del documento dentro del panel.
     */
    public function preview($id)
    {
        $certificate = GradeCertificate::with(['student.user', 'period', 'issuer'])->findOrFail($id);
        $report = $this->gradesReport($certificate->idstudent, $certificate->idperiod);
        $academic = $this->academicInfo($certificate->enrollment);

        return view('grade_certificates.preview', compact('certificate', 'report', 'academic'));
    }

    /**
     * Versión imprimible: el navegador la exporta a PDF (13.6).
     */
    public function print($id)
    {
        $certificate = GradeCertificate::with(['student.user', 'period', 'issuer'])->findOrFail($id);

        if ($certificate->status != 1) {
            return redirect()
                ->route('gradeCertificates.index')
                ->withErrors(['certificate' => 'La constancia está anulada y no puede imprimirse.']);
        }

        $report = $this->gradesReport($certificate->idstudent, $certificate->idperiod);
        $academic = $this->academicInfo($certificate->enrollment);

        return view('grade_certificates.print', compact('certificate', 'report', 'academic'));
    }

    /**
     * Confirmación de anulación (borrado lógico, estilo del proyecto).
     */
    public function showDelete($id)
    {
        $certificate = GradeCertificate::with('student')->findOrFail($id);
        return view('grade_certificates.delete', compact('certificate'));
    }

    /**
     * Anula la constancia (status = 0).
     */
    public function destroy($id)
    {
        $certificate = GradeCertificate::findOrFail($id);
        $certificate->update(['status' => 0]);

        return redirect()->route('gradeCertificates.index')->with('desactivado', 'OK');
    }

    /* ================= MÉTODOS DE APOYO ================= */

    /**
     * Consulta de notas del alumno (13.4), agrupadas por curso y tipo
     * de evaluación, con promedios calculados en escala vigesimal.
     * Si no se indica $idperiod, usa el periodo más reciente con notas.
     */
    private function gradesReport($idstudent, $idperiod = null)
    {
        $rows = DB::table('grades as g')
            ->join('courses as c', 'g.idcourse', '=', 'c.idcourse')
            ->join('evaluation_types as et', 'g.idevaluation_type', '=', 'et.idevaluation_type')
            ->join('semesters as sem', 'c.idsemester', '=', 'sem.idsemester')
            ->join('periods as p', 'sem.idperiod', '=', 'p.idperiod')
            ->where('g.idstudent', $idstudent)
            ->select(
                'p.idperiod',
                'p.period_name',
                'c.course_name',
                'et.evaluation_name',
                'g.grade'
            )
            ->orderBy('c.course_name')
            ->get();

        if ($rows->isEmpty()) {
            return null;
        }

        // Periodo indicado o el más reciente con notas
        $idperiod = $idperiod ?: $rows->max('idperiod');
        $rows = $rows->where('idperiod', $idperiod);

        if ($rows->isEmpty()) {
            return null;
        }

        // Columnas dinámicas según los tipos de evaluación registrados
        $evaluations = $rows->pluck('evaluation_name')->unique()->values()->all();

        $courses  = [];
        $warnings = [];

        foreach ($rows->groupBy('course_name') as $courseName => $items) {
            $gradesByEval = [];

            foreach ($items as $item) {
                // 13.7: validación de integridad — escala vigesimal 0-20
                if (!is_numeric($item->grade) || $item->grade < 0 || $item->grade > 20) {
                    $warnings[] = "Nota fuera de rango en {$courseName} ({$item->evaluation_name}).";
                }
                $gradesByEval[$item->evaluation_name] = $item->grade;
            }

            $values = array_filter($gradesByEval, function ($v) {
                return is_numeric($v);
            });

            $courses[] = [
                'course'  => $courseName,
                'grades'  => $gradesByEval,
                'average' => count($values) ? round(array_sum($values) / count($values), 2) : null,
            ];
        }

        $averages = array_filter(array_column($courses, 'average'), function ($v) {
            return $v !== null;
        });

        return [
            'idperiod'        => $idperiod,
            'period_name'     => $rows->first()->period_name,
            'evaluations'     => $evaluations,
            'courses'         => $courses,
            'general_average' => count($averages) ? round(array_sum($averages) / count($averages), 2) : null,
            'warnings'        => $warnings,
        ];
    }

    /**
     * Última matrícula activa del alumno (para grado/sección del encabezado).
     */
    private function activeEnrollment($idstudent)
    {
        return Enrollment::with([
                'section.course.degree',
                'section.course.subgrade',
            ])
            ->where('idstudent', $idstudent)
            ->where('status', 1)
            ->orderBy('idenrollment', 'DESC')
            ->first();
    }

    /**
     * Información académica de la matrícula (puede venir vacía).
     */
    private function academicInfo($enrollment)
    {
        $section = $enrollment?->section;
        $course  = $section?->course;

        return [
            'section'  => $section?->section_name,
            'degree'   => $course?->degree?->degree_name,
            'subgrade' => $course?->subgrade?->subgrade_name,
        ];
    }

    /**
     * Genera un código correlativo único por año: CN-2026-0001.
     */
    private function generateCode()
    {
        $year = date('Y');
        $sequence = GradeCertificate::where('certificate_code', 'like', "CN-{$year}-%")->count() + 1;

        do {
            $code = sprintf('CN-%s-%04d', $year, $sequence);
            $sequence++;
        } while (GradeCertificate::where('certificate_code', $code)->exists());

        return $code;
    }
}
