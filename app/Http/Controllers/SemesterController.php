<?php

namespace App\Http\Controllers;

use App\Models\Period;
use App\Models\Semester;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class SemesterController extends Controller
{
    public function index()
    {
        $semesters = Semester::with('period')->orderBy('idsemester', 'DESC')->get();
        
        return view('semesters.index', compact('semesters'));
    }

    public function create()
    {
        $periods = Period::where('status', 1)->get();
        return view('semesters.create', compact('periods'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'semester_name' => 'required|string|max:50',
            'idperiod'      => 'required|exists:periods,idperiod',
        ]);

        Semester::create([
            'semester_name' => $request->semester_name,
            'idperiod'      => $request->idperiod,
            'status'        => 1, // Por defecto activo
        ]);

        return redirect()->route('semesters.index')->with('add_successSemes', 'OK');
    }

    public function edit($id)
    {
        $semester = Semester::findOrFail($id);
        
        $periods = Period::all(); 
        
        return view('semesters.edit', compact('semester', 'periods'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'semester_name' => 'required|string|max:50',
            'idperiod'      => 'required|exists:periods,idperiod',
            'status'        => 'required|in:0,1'
        ]);

        $semester = Semester::findOrFail($id);
        $semester->update([
            'semester_name' => $request->semester_name,
            'idperiod'      => $request->idperiod,
            'status'        => $request->status,
        ]);

        return redirect()->route('semesters.index')->with('update_successSemes', 'OK');
    }

    public function showDelete($id)
    {
        $semester = Semester::findOrFail($id);
        return view('semesters.delete', compact('semester'));
    }

    public function destroy($id)
    {
        $semester = Semester::findOrFail($id);
        
        // Desactivación lógica: cambiamos status a 0
        $semester->update(['status' => 0]);

        return redirect()->route('semesters.index')->with('delete_successSemes', 'OK');
    }


}