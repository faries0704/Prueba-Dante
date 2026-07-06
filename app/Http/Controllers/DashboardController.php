<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $padres = DB::table('fathers')->count();
        $docentes = DB::table('teachers')->count();
        $alumnos = DB::table('students')->count();
        $usuarios = DB::table('users')->count(); 


        $alumnosRecientes = DB::table('students as s')
            ->join('users as u', 's.iduser', '=', 'u.iduser')
            ->select('s.idstudent', 's.full_name', 'u.email', 'u.photo')
            ->orderBy('s.idstudent', 'desc')
            ->limit(5)
            ->get();

        // 3. Docentes Recientes
        $docentesRecientes = DB::table('teachers as t')
            ->join('users as u', 't.iduser', '=', 'u.iduser')
            ->select('t.idteacher', 't.full_name', 'u.status')
            ->orderBy('t.idteacher', 'desc')
            ->limit(5)
            ->get();

        return view('dashboard.index', compact(
            'padres',
            'docentes',
            'alumnos',
            'usuarios',
            'alumnosRecientes',
            'docentesRecientes'
        ));
    }
}
