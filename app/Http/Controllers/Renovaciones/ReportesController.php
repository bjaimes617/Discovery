<?php

namespace App\Http\Controllers\Renovaciones;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\renovaciones\renovacionesVentasModel;
use App\Models\renovaciones\renovacionesHistoricoModel;
use App\Exports\Renovaciones\renovacionesExport;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;

class ReportesController extends Controller
{
    
    private $typeReportes;

    public function __construct()
    {
        $this->typeReportes = ["general" => "Discovery - Informacion de Ventas y seguimiento", "ventas" => "Discovery - Ventas Unicas"];
    }
    
    public function index()
    {
       
        return view('renovaciones.export.index')->with(['reportes' => $this->typeReportes]);
    }

    public function store(Request $request)
    {
        
        $fecha = explode("-", $request->fecha);
        $init  = Carbon::createfromFormat('d/m/Y', trim($fecha[0]))->startOfDay();
        $end   = Carbon::createfromFormat('d/m/Y', trim($fecha[1]))->endOfDay();
       // dd($request->all());
        switch ($request->reporte) {
            case 'general':
                $historico = true;
                $datos = renovacionesVentasModel::with('relationHistorico', 'relationTienda')
                ->whereBetween('created_at', [$init->copy()->format('Y-m-d H:m:s'), $end->copy()->format('Y-m-d H:m:s')])->cursor();
                return Excel::download(new renovacionesExport($datos,$historico), 'Reporte de Tipificaciones ' . $init->copy()->format('d-m-Y') . ' al ' . $end->copy()->format('d-m-Y') . '.xlsx');

                break;
            case 'ventas':
                $historico = false;
                 $datos = renovacionesVentasModel::with('relationHistorico', 'relationTienda')
                ->whereBetween('created_at', [$init->copy()->format('Y-m-d H:m:s'), $end->copy()->format('Y-m-d H:m:s')])->cursor();
                return Excel::download(new renovacionesExport($datos,$historico), 'Reporte de Ventas ' . $init->copy()->format('d-m-Y') . ' al ' . $end->copy()->format('d-m-Y') . '.xlsx');

                break;
            }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
