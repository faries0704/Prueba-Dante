<?php
namespace App\Http\Controllers;

use App\Models\Degree;
use App\Models\Subgrade;
use App\Models\Course;
use App\Models\Section;
use Illuminate\Http\Request;


class FilterController extends Controller
{
    // Obtener grados por periodo
   public function getGrades($idperiod)
    {
        return Degree::whereHas('semester', function($q) use ($idperiod) {
            $q->where('idperiod', $idperiod);
        })
        ->where('status', 1)
        ->get();
    }

    // Obtener subgrados por grado
    public function getSubgrades($iddegree)
    {
        return Subgrade::where('iddegree', $iddegree)
                    ->where('status', 1)
                    ->get();
    }

    // Obtener cursos por subgrado
    public function getCourses($idsubgrade)
    {
        return Course::where('idsubgrade', $idsubgrade)
                     ->where('status', 1)
                     ->get();
    }

    // Obtener secciones por curso
    public function getSections($idcourse)
    {
        return \App\Models\Section::where('idcourse', $idcourse)
                                ->where('status', 1)
                                ->get();
    }

    // Obtener alumnos inscritos en una sección
    public function getStudentsBySection($idsection)
    {
        $section = Section::find($idsection);

        if (!$section) {
            return response()->json([]);
        }

        $students = $section->students() ->with('user') ->get();

        return response()->json($students);
    }

}