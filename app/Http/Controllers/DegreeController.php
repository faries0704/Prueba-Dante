<?php

namespace App\Http\Controllers;

use App\Models\Degree;
use App\Models\Semester;
use Illuminate\Http\Request;

class DegreeController extends Controller
{
    public function index()
    {
        $degrees = Degree::with('semester.period')
                    ->orderBy('iddegree', 'DESC')
                    ->get();

        return view('degrees.index', compact('degrees'));
    }

    public function create()
    {
        $semesters = Semester::with('period')->where('status', 1)->get();
        return view('degrees.create', compact('semesters'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'degree_name' => 'required|string|max:50',
            'idsemester'  => 'required|exists:semesters,idsemester',
        ]);

        Degree::create([
            'degree_name' => $request->degree_name,
            'idsemester'  => $request->idsemester,
            'status'      => 1,
        ]);

        return redirect()->route('degrees.index')->with('add_successDegre', 'OK');
    }

    public function edit($id)
    {
        $degree = Degree::findOrFail($id);
        
        $semesters = Semester::with('period')->get();
        
        return view('degrees.edit', compact('degree', 'semesters'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'degree_name' => 'required|string|max:50',
            'idsemester'  => 'required|exists:semesters,idsemester',
            'status'      => 'required|in:0,1'
        ]);

        $degree = Degree::findOrFail($id);
        $degree->update([
            'degree_name' => $request->degree_name,
            'idsemester'  => $request->idsemester,
            'status'      => $request->status,
        ]);

        return redirect()->route('degrees.index')->with('update_successDegre', 'OK');
    }

    public function showDelete($id)
    {
        $degree = Degree::with('semester.period')->findOrFail($id);
        return view('degrees.delete', compact('degree'));
    }

    public function destroy($id)
    {
        $degree = Degree::findOrFail($id);
        
        $degree->update(['status' => 0]);

        return redirect()->route('degrees.index')->with('delete_successDegree', 'OK');
    }

}