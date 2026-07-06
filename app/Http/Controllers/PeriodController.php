<?php
namespace App\Http\Controllers;

use App\Models\Period;
use Illuminate\Http\Request;

class PeriodController extends Controller
{
    public function index()
    {
        $periods = Period::orderBy('idperiod', 'DESC')->get();
        return view('periods.index', compact('periods'));
    }

    public function create()
    {
        return view('periods.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'period_name' => 'required|string|max:100',
            'start_date'  => 'required|date',
            'end_date'    => 'required|date|after_or_equal:start_date',
        ]);

        Period::create([
            'period_name' => $request->period_name,
            'start_date'  => $request->start_date,
            'end_date'    => $request->end_date,
            'status'      => 1, // Activo por defecto
        ]);

        return redirect()->route('periods.index')->with('add_successPerid', 'OK');
    }

    public function edit($id)
    {
        // Buscamos el periodo por su ID primario
        $period = Period::findOrFail($id);
        return view('periods.edit', compact('period'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'period_name' => 'required|string|max:100',
            'start_date'  => 'required|date',
            'end_date'    => 'required|date|after_or_equal:start_date',
            'status'      => 'required|in:1,0',
        ]);

        $period = Period::findOrFail($id);
        $period->update([
            'period_name' => $request->period_name,
            'start_date'  => $request->start_date,
            'end_date'    => $request->end_date,
            'status'      => $request->status,
        ]);

        return redirect()->route('periods.index')->with('update_successPerid', 'OK');
    }

    public function showDelete($id)
    {
        $period = Period::findOrFail($id);
        return view('periods.delete', compact('period'));
    }

    public function destroy($id)
    {
        $period = Period::findOrFail($id);
        
        $period->update(['status' => 0]);

        return redirect()->route('periods.index')->with('delete_successPerid', 'OK');
    }
}