<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class FatherDashboardController extends Controller
{
    public function index()
    {
        // 1. Obtener el idfather del usuario logueado
        $fatherId = DB::table('fathers')
            ->where('iduser', Auth::id())
            ->value('idfather');

        if (!$fatherId) {
            Auth::logout();
            return redirect()->route('login')->with('error', 'Su usuario no cuenta con un perfil de Apoderado.');
        }

        // 2. Tarjeta 1: Total de Hijos vinculados
        $totalHijos = DB::table('father_student')
            ->where('idfather', $fatherId)
            ->count();

        // 3. Tarjeta 2: Total de Cursos acumulados entre todos sus hijos
        $totalCursosHijos = DB::table('father_student as fs')
            ->join('enrollments as e', 'fs.idstudent', '=', 'e.idstudent')
            ->where('fs.idfather', $fatherId)
            ->where('e.status', 1)
            ->count();

        // 4. Tarjeta 3 y 4: Consolidado de Asistencias y Faltas de todos sus hijos
        $asistenciasTotales = DB::table('father_student as fs')
            ->join('attendance as a', 'fs.idstudent', '=', 'a.idstudent')
            ->where('fs.idfather', $fatherId)
            ->where('a.status', 'PRESENTE')
            ->count();

        $faltasTotales = DB::table('father_student as fs')
            ->join('attendance as a', 'fs.idstudent', '=', 'a.idstudent')
            ->where('fs.idfather', $fatherId)
            ->where('a.status', 'AUSENTE')
            ->count();

        // 5. Bloque Inferior Izquierdo: Tabla de Alumnos (Hijos) vinculados con sus detalles actuales
        $misHijos = DB::table('father_student as fs')
            ->join('students as s', 'fs.idstudent', '=', 's.idstudent')
            ->leftJoin('users as u', 's.iduser', '=', 'u.iduser')
            ->select(
                's.idstudent',
                's.full_name',
                'u.email',
                'u.photo',
                // Subconsulta para obtener el promedio general de este hijo específico
                DB::raw('(SELECT ROUND(AVG(grade), 2) FROM grades WHERE idstudent = s.idstudent) as promedio_hijo')
            )
            ->where('fs.idfather', $fatherId)
            ->get();

        return view('fathers.dashboard', compact(
            'totalHijos',
            'totalCursosHijos',
            'asistenciasTotales',
            'faltasTotales',
            'misHijos'
        ));
    }
}