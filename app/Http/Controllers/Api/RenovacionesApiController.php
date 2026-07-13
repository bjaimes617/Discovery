<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\renovaciones\renovacionesVentasModel;
use App\Models\renovaciones\renovacionesHistoricoModel;
use App\Models\renovaciones\renovacionesEstatusModel;
use App\Models\renovaciones\renovacionesObservacionesModel;
use App\Exports\Renovaciones\renovacionesExport;
use Carbon\Carbon;

class RenovacionesApiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function show(Request $request)
    {
        

        return response()->stream(function () use ($request) {
            $out = fopen('php://output', 'w');
            fwrite($out, '[');
            $init = carbon::create($request->inicio)->startOfDay();
            $fin =  carbon::create($request->fin)->endofDay();
            $first = true;
            $datos = renovacionesVentasModel::select(
                'renovaciones_ventas.created_at',
                'users.nombre_apellido as nombre_user',
                'op.numero_empleado as empleado',
                'renovaciones_ventas.dn',
                'nombre_cliente',
                'equipo',
                'plazo',
                'entrega_en',
                'numero_orden_onix',
                'op.in_telefonico',
                'precio_equipo',
                'direccion_entrega',
                'entre_calles',
                'referencias',
                'renovaciones_ventas.observaciones_id',
                'renovaciones_ventas.estatus_id',
                'latitud',
                'longitud',
                'sup.numero_empleado as supervisor',
                'coord.numero_empleado as coordinador')
                ->leftJoin('personal as op', 'renovaciones_ventas.personal_id', '=', 'op.id')
                ->leftJoin('personal as sup', 'renovaciones_ventas.supervisor_id', '=', 'sup.id')
                ->leftJoin('personal as coord', 'renovaciones_ventas.coordinador_id', '=', 'coord.id')
                ->leftJoin('users as users', 'renovaciones_ventas.user_id', '=', 'users.id')
                ->whereBetween('renovaciones_ventas.created_at', [$init, $fin])->cursor();
                
                $estatuses = renovacionesEstatusModel::all()->pluck('descripcion', 'id')->toArray();
                $observaciones = renovacionesObservacionesModel::all()->pluck('descripcion', 'id')->toArray();
                
                foreach ($datos as $value) {
                    $fechaVenta = Carbon::create($value->created_at);
                    // 2. Obtienes la hora de inicio (ej. 13:00:00)
                    $inicioIntervalo = $fechaVenta->copy()->startOfHour();
                    // 3. Obtienes la hora de fin (ej. 14:00:00)
                    $finIntervalo = $inicioIntervalo->copy()->addHour();
                    // 4. Formateas el resultado como lo necesites (ej. "13:00 - 14:00")
                    $intervaloTexto = $inicioIntervalo->format('H:i') . ' a ' . $finIntervalo->format('H:i'); 
                    
                    if (!$first) {
                        fwrite($out, ',');
                    }

                    $first = false;
                    $array = array(
                       'Fecha y Hora'=> Carbon::create($value->created_at)->format('d/m/Y H:i:s'),
                       'Nombre del Ejecutivo'   => strtoupper($value->nombre_user),
                       'Cedula del Ejecutivo'   =>$value->empleado,
                        'DN'                => $value->dn,
                        'Nombre del Cliente'=> $value->nombre_cliente,
                        'Equipo'            => $value->equipo,
                        'Plazo'             => $value->plazo." Meses",
                        'Entrega en'        => $value->entrega_en,
                        'N° de Orden Onix (Magento)'=>$value->numero_orden_onix,
                        'Usuario de Conexión'       => $value->in_telefonico,
                        'Precio del Equipo'         =>number_format($value->precio_equipo, 2),
                        'Dirección de Entrega'      =>$value->direccion_entrega,
                        'Entre Calles'          =>$value->entre_calles,
                        'Referencias'           =>$value->referencias,
                        'Fecha'                 =>Carbon::parse($value->created_at)->format('d/m/Y'),
                        'Semana'                =>Carbon::parse($value->created_at)->weekOfYear,
                        'Nombre del Ejecutivo2'  =>strtoupper($value->nombre_user),
                        'Cedula del Ejecutivo2'  =>$value->empleado,
                        'Intervalo'             => $intervaloTexto,
                        'Estatus'               => $estatuses[$value->estatus_id],
                        'Observaciones'         => $value->observaciones_id != null ? $observaciones[$value->observaciones_id] : null,
                        'Latitud Direccion'     => $value->latitud,
                        'Longitud Direccion'    => $value->longitud,
                    ); 
                    fwrite($out, json_encode($array));
                }
            fwrite($out, ']'); // <--- IMPORTANTE: Cierra el array JSON
            fclose($out);
        }, 200, [
            'Content-Type' => 'application/json', // Cambiado de text/event-stream
            'Cache-Control' => 'no-cache',
            'Connection' => 'keep-alive',
        ]);

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
