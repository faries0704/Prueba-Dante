<?php

namespace App\Http\Controllers;

use App\Models\Degree;
use App\Models\Subgrade;
use Illuminate\Http\Request;

class SubgradeController extends Controller
{
    public function index()
    {
        $subgrades = Subgrade::with('degree.semester.period')
                    ->orderBy('idsubgrade', 'DESC')
                    ->get();

        return view('subgrades.index', compact('subgrades'));
    }
    public function create()
    {
        $degrees = Degree::with('semester.period')->where('status', 1)->get();
        
        // Agrupamos los subgrados por el nivel académico correspondiente
        $subgradosGrupos = [
            'Inicial' => [
                '3 Años', '4 Años', '5 Años'
            ],
            'Primaria' => [
                '1° Grado', '2° Grado', '3° Grado', '4° Grado', '5° Grado', '6° Grado'
            ],
            'Secundaria' => [
                '1° Grado', '2° Grado', '3° Grado', '4° Grado', '5° Grado'
            ],
            'Secciones Adicionales' => [
                'Sección A', 'Sección B', 'Sección Única'
            ]
        ];

        return view('subgrades.create', compact('degrees', 'subgradosGrupos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'iddegree'  => 'required|exists:degrees,iddegree',
            'subgrades' => 'required|array',
        ]);

        $contadorAgregados = 0;

        // Recorremos los subgrados enviados desde el formulario
        foreach ($request->subgrades as $subgrade) {
            // Solo guardamos los que tienen el switch activado (selected == 1)
            if (isset($subgrade['selected']) && $subgrade['selected'] == '1') {
                Subgrade::create([
                    'iddegree'      => $request->iddegree,
                    'subgrade_name' => $subgrade['name'], // Toma el nombre del input
                    'status'        => 1,
                ]);
                $contadorAgregados++;
            }
        }

        // Si no seleccionó ninguno, lo regresamos con un error
        if ($contadorAgregados == 0) {
            return back()->withErrors(['error' => 'Debe seleccionar al menos un subgrado para guardar.'])->withInput();
        }

        return redirect()->route('subgrades.index')->with('add_successSubgrade', 'OK');
    }
    public function edit($id)
    {
        $subgrade = Subgrade::findOrFail($id);
        
        $degrees = Degree::with('semester.period')->get();
        
        return view('subgrades.edit', compact('subgrade', 'degrees'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'subgrade_name' => 'required|string|max:50',
            'iddegree'      => 'required|exists:degrees,iddegree',
            'status'        => 'required|in:0,1'
        ]);

        $subgrade = Subgrade::findOrFail($id);
        $subgrade->update([
            'subgrade_name' => $request->subgrade_name,
            'iddegree'      => $request->iddegree,
            'status'        => $request->status,
        ]);

        return redirect()->route('subgrades.index')->with('update_successSubgrade', 'OK');
    }
    public function showDelete($id)
    {
        $subgrade = Subgrade::with('degree.semester.period')->findOrFail($id);
        return view('subgrades.delete', compact('subgrade'));
    }

    public function destroy($id)
    {
        $subgrade = Subgrade::findOrFail($id);
        
        $subgrade->update(['status' => 0]);

        return redirect()->route('subgrades.index')->with('delete_successSubgrade', 'OK');
    }

}