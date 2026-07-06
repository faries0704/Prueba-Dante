<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Models\Enrollment;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * HU-12: Generación de Constancias de Estudios.
 * El administrador emite constancias que acreditan que un alumno
 * cursa estudios en la institución, con vista previa e impresión en PDF.
 */
class CertificateController extends Controller
{
    /**
     * Listado de constancias emitidas.
     */
    public function index()
    {
        $certificates = Certificate::with('student')
            ->orderBy('idcertificate', 'DESC')
            ->get();

        return view('certificates.index', compact('certificates'));
    }

    /**
     * Formulario de generación de constancias (12.6).
     * Solo se listan alumnos con usuario activo.
     */
    public function create()
    {
        $students = Student::whereHas('user', function ($query) {
                $query->where('status', 1);
            })
            ->orderBy('full_name')
            ->get();

        return view('certificates.create', compact('students'));
    }

    /**
     * AJAX: datos del alumno y su matrícula activa para la
     * visualización previa del documento (12.7 y 12.8).
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

        $enrollment = $this->activeEnrollment($student->idstudent);

        if (!$enrollment) {
            return response()->json(['ok' => false, 'message' => 'El alumno no tiene una matrícula activa registrada.']);
        }

        $academic = $this->academicInfo($enrollment);

        return response()->json([
            'ok'      => true,
            'student' => [
                'full_name'  => $student->full_name,
                'dni'        => $student->dni,
                'gender'     => $student->gender,
                'birth_date' => $student->birth_date,
            ],
            'academic' => $academic,
        ]);
    }

    /**
     * Registra la constancia (12.7: validación de datos del estudiante).
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

        // Validación: matrícula activa vigente
        $enrollment = $this->activeEnrollment($student->idstudent);
        if (!$enrollment) {
            return back()->withInput()->withErrors([
                'idstudent' => 'El alumno no tiene una matrícula activa. No es posible emitir la constancia.',
            ]);
        }

        $certificate = Certificate::create([
            'certificate_code' => $this->generateCode(),
            'idstudent'        => $student->idstudent,
            'idenrollment'     => $enrollment->idenrollment,
            'purpose'          => $request->purpose,
            'observations'     => $request->observations,
            'issue_date'       => $request->issue_date,
            'issued_by'        => Auth::id(),
            'status'           => 1, // Vigente
        ]);

        return redirect()
            ->route('certificates.preview', $certificate->idcertificate)
            ->with('creado', 'OK');
    }

    /**
     * Vista previa del documento dentro del panel (12.8).
     */
    public function preview($id)
    {
        $certificate = Certificate::with(['student.user', 'issuer'])->findOrFail($id);
        $academic = $this->academicInfo($certificate->enrollment);

        return view('certificates.preview', compact('certificate', 'academic'));
    }

    /**
     * Versión imprimible del documento: el navegador la exporta a PDF (12.9).
     */
    public function print($id)
    {
        $certificate = Certificate::with(['student.user', 'issuer'])->findOrFail($id);

        // No se permite imprimir constancias anuladas
        if ($certificate->status != 1) {
            return redirect()
                ->route('certificates.index')
                ->withErrors(['certificate' => 'La constancia está anulada y no puede imprimirse.']);
        }

        $academic = $this->academicInfo($certificate->enrollment);

        return view('certificates.print', compact('certificate', 'academic'));
    }

    /**
     * Confirmación de anulación (borrado lógico, estilo del proyecto).
     */
    public function showDelete($id)
    {
        $certificate = Certificate::with('student')->findOrFail($id);
        return view('certificates.delete', compact('certificate'));
    }

    /**
     * Anula la constancia (status = 0).
     */
    public function destroy($id)
    {
        $certificate = Certificate::findOrFail($id);
        $certificate->update(['status' => 0]);

        return redirect()->route('certificates.index')->with('desactivado', 'OK');
    }

    /* ================= MÉTODOS DE APOYO ================= */

    /**
     * Última matrícula activa del alumno con toda la cadena académica.
     */
    private function activeEnrollment($idstudent)
    {
        return Enrollment::with([
                'section.course.degree',
                'section.course.subgrade',
                'section.course.semester.period',
            ])
            ->where('idstudent', $idstudent)
            ->where('status', 1)
            ->orderBy('idenrollment', 'DESC')
            ->first();
    }

    /**
     * Extrae la información académica de una matrícula para el documento.
     */
    private function academicInfo($enrollment)
    {
        $section  = $enrollment?->section;
        $course   = $section?->course;
        $semester = $course?->semester;

        return [
            'section'  => $section?->section_name,
            'course'   => $course?->course_name,
            'degree'   => $course?->degree?->degree_name,
            'subgrade' => $course?->subgrade?->subgrade_name,
            'semester' => $semester?->semester_name,
            'period'   => $semester?->period?->period_name,
        ];
    }

    /**
     * Genera un código correlativo único por año: CE-2026-0001.
     */
    private function generateCode()
    {
        $year = date('Y');
        $sequence = Certificate::where('certificate_code', 'like', "CE-{$year}-%")->count() + 1;

        do {
            $code = sprintf('CE-%s-%04d', $year, $sequence);
            $sequence++;
        } while (Certificate::where('certificate_code', $code)->exists());

        return $code;
    }
}
