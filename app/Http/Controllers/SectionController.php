<?php
namespace App\Http\Controllers;

use App\Models\Enrollment;
use App\Models\Period;
use App\Models\Section;
use App\Models\Student;
use Illuminate\Http\Request;

class SectionController extends Controller
{
    public function index()
    {
        $sections = Section::with([
            'course.semester.period', 
            'course.degree', 
            'course.subgrade', 
            'course.teachers'
        ])->orderBy('idsection', 'DESC')->get();

        return view('sections.index', compact('sections'));
    }

    public function create()
    {
        $periods = Period::where('status', 1)->get();
        return view('sections.create', compact('periods'));
    }

    public function store(Request $request)
{
    // Validamos la estructura nueva
    $request->validate([
        'txtsgrd'      => 'required', // ID del Subgrado
        'sections'     => 'required|array', // Letras seleccionadas (A, B...)
        'section_data' => 'required|array'  // Configuraciones internas
    ]);

    // Recorremos cada sección que el usuario activó
    foreach ($request->sections as $letra) {
        
        // Verificamos si para esta sección se asignaron cursos y capacidad
        if (isset($request->section_data[$letra])) {
            $data = $request->section_data[$letra];
            
            $capacity = $data['capacity'] ?? 30; // Capacidad específica de la sección
            $courses  = $data['courses'] ?? [];   // Cursos específicos marcados

            // Creamos un registro por cada curso seleccionado en esta sección
            foreach ($courses as $courseId) {
                Section::create([
                    'section_name' => $letra, // Guardará 'A', 'B', etc.
                    'idcourse'     => $courseId,
                    'capacity'     => $capacity,
                    'idsubgrade'   => $request->txtsgrd, // Recomendado mapear el subgrado si tu tabla lo tiene
                    'status'       => 1
                ]);
            }
        }
    }

    return redirect()->route('sections.index')->with('add_successSection', 'OK');
}

    public function edit($id)
    {
        $section = Section::with(['course.semester.period', 'course.degree', 'course.subgrade'])
            ->findOrFail($id);

        return view('sections.edit', compact('section'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'txtnamsecc' => 'required',
            'txtcapc'    => 'required|numeric',
            'txtstte'    => 'required'
        ]);

        $section = Section::findOrFail($id);
        $section->update([
            'section_name' => $request->txtnamsecc,
            'capacity'     => $request->txtcapc,
            'status'       => $request->txtstte
        ]);

        return redirect()->route('sections.index')->with('update_successSection', 'OK');
    }

    public function showDelete($id)
    {
        // Buscamos la sección con sus relaciones para mostrar información al usuario
        $section = Section::with(['course.semester.period', 'course.degree', 'course.subgrade'])
                        ->findOrFail($id);
        return view('sections.delete', compact('section'));
    }

    public function destroy($id)
    {
        $section = Section::findOrFail($id);

        $section->status = 0;
        $section->save();

        return redirect()->route('sections.index')->with('delete_successSection', 'OK');
    }

    public function manage($id)
    {

        $section = Section::with([
            'course.teachers.user', 
            'course.degree', 
            'course.subgrade',
            'students' 
        ])->findOrFail($id);

        $enrolledStudentIds = $section->students->pluck('idstudent')->toArray();
        $availableStudents = Student::whereNotIn('idstudent', $enrolledStudentIds)->get();

        return view('sections.manage', compact('section', 'availableStudents'));
    }

    public function enrollStudent(Request $request)
    {
        $request->validate([
            'idsection' => 'required',
            'idstudent' => 'required'
        ]);

        // Verificar capacidad
        $section = Section::findOrFail($request->idsection);
        if ($section->students()->count() >= $section->capacity) {
            return back()->with('error_capacity', 'OK');
        }

        // Crear la matrícula
        Enrollment::create([
            'idsection' => $request->idsection,
            'idstudent' => $request->idstudent,
            'enrollment_date' => now(),
            'status' => 1
        ]);

        return back()->with('add_successEnroll', 'OK');
    }

    public function unenrollStudent($id)
    {
        $enrollment = Enrollment::findOrFail($id);
        $enrollment->delete();

        return back()->with('delete_successEnroll', 'OK');
    }


}