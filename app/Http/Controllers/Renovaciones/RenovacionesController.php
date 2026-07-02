<?php

namespace App\Http\Controllers\Renovaciones;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\renovaciones\renovacionesVentasModel;
use App\Models\renovaciones\renovacionesHistoricoModel;
use App\Models\Personal;
use Carbon\Carbon;

class RenovacionesController extends Controller
{
 
    private $plazos, $campania, $corddefault;

    public function __construct(){
      
        $this->plazos = [12, 18];
        $this->campania =6;
        $this->corddefault = [23.594941655693194 , -102.85721280844986]; #mexico
    }
        
    public function checkOrderOnix(Request $request)
    {       
        if ($request->ajax()) {
            $numero_portar = trim($request->ordenonix);

            if (!preg_match("/^[0-9]{10}$/", $numero_portar)) {
                return response()->json(false);
            }

            $query = renovacionesVentasModel::where('numero_orden_onix', $numero_portar);

            if ($request->filled('idventa') && is_numeric($request->idventa)) {
                $query->where('id', '!=', $request->idventa);
            }

            $venta = $query->orderBy('created_at', 'desc')->first();

            if ($venta != null) {
                 return response()->json(false);
            }

            return response()->json(true);
        }
    }
    
    public function index()
    {
        $cargo = "";
        $user = ENV('DEV_USER_CHANGE', false) ? Auth::loginUsingId(2, true) : Auth::user();

        if ($user->ficha_personal == "Si")
            $cargo = $user->personal->cargo->nombre_cargo;
        switch ($cargo):
            case "Operador":
                $supervisores = Personal::with(['RelationUser'])->select('personal.id', 'users.nombre_apellido')->where("personal.campana_id", "=",  $this->campania)
                    ->where("personal.cargo_id", "=", 4)
                    ->where('personal.id', '=', Auth::user()->personal->jefe_inmediato_id)
                    ->join('users', 'personal.user_id', '=', 'users.id')
                    ->orderBy('users.nombre_apellido')->get();
                break;
            case 'Supervisor':
                $supervisores = Personal::with(['RelationUser'])->select('personal.id', 'users.nombre_apellido')->where("personal.campana_id", "=", 3)
                    ->where("personal.cargo_id", "=", 4)->where('personal.id', '=', Auth::user()->personal->id)
                    ->join('users', 'personal.user_id', '=', 'users.id')
                    ->orderBy('users.nombre_apellido')->get();
                break;
            case 'Coordinador':
                $supervisores = Personal::with(['RelationUser'])->select('personal.id', 'users.nombre_apellido')
                    ->where("personal.campana_id", "=",  $this->campania)
                    ->where("personal.cargo_id", "=", 4)->where('personal.jefe_inmediato_id', '=', Auth::user()->personal->id)
                    ->join('users', 'personal.user_id', '=', 'users.id')->where('personal.estatus', 1)
                    ->orderBy('users.nombre_apellido')->get();
                break;
            default:
                if (Auth::user()->hasPermission('renovaciones.administrativo')) {
                    $supervisores = Personal::with(['relationUser'])->select('personal.id', 'users.nombre_apellido')
                        ->where("personal.campana_id", "=",  $this->campania)->where("personal.cargo_id", "=", 4)
                        ->join('users', 'personal.user_id', '=', 'users.id')->where('personal.estatus', 1)
                        ->orderBy('users.nombre_apellido')->get();
                } else {
                    $supervisores = array();
                }
        endswitch;

        return view('renovaciones.index')->with([
            "supervisores" => $supervisores
        ]);
    }

    public function search(Request $request){
       
        $fecha = explode("-", $request->fecha);
        $data = array();
        $arreglo = array();
        $init = carbon::createFromFormat('d/m/Y', trim($fecha[0]));
        $end = carbon::createFromFormat('d/m/Y', trim($fecha[1]));

        $sql = renovacionesVentasModel::whereBetween('renovaciones_ventas.created_at', array($init->copy()->startOfDay(), $end->copy()->endOfDay()));
       
        $cargo = "";
        ///validamos si la persona tiene ficha de personal
        if (Auth::user()->ficha_personal == "Si") {
            $cargo = Auth::user()->personal->cargo->nombre_cargo;
        }
        /// se valida si viene valores de supervisor 
        if ($request->supervisor != "todos") {
            if ($cargo == 'Operador') {
                $sql->where("renovaciones_ventas.personal_id", "=", Auth::user()->personal->id);
            } else {
                $liberar = true;
                $sql->where("renovaciones_ventas.supervisor_id", "=", $request->supervisor);
            }
        } else {
            ///si no, en base al cargo buscamos los datos
            switch ($cargo):
                case "Operador":
                    $sql->where("renovaciones_ventas.personal_id", "=", Auth::user()->personal->id);
                    break;
                case 'Supervisor':
                    $sql->where("renovaciones_ventas.supervisor_id", "=", Auth::user()->personal->id);
                    $liberar = true;
                    break;
                case 'Coordinador':
                    $sql->where('renovaciones_ventas.coordinador_id', '=', Auth::user()->personal->id);
                    $liberar = true;
                    break;
            endswitch;
        }

        if (Auth::user()->ficha_personal == "Si" || !Auth::user()->hasPermission('renovaciones.administrativo')) {
            $data = $sql->orderBy('created_at', 'DESC')->get();          
        }
      
        if ($request->numero_orden_onix != "" && $init->diffInDays($end) == 0 && $request->supervisor == "todos") {
            $sql2 = renovacionesVentasModel::where('numero_orden_onix', $request->numero_orden_onix);
            switch ($cargo):
                case "Operador":
                    $sql2->where("renovaciones_ventas.personal_id", "=", Auth::user()->personal->id);                    
                    break;
                case 'Supervisor':
                    $sql2->where("renovaciones_ventas.supervisor_id", "=", Auth::user()->personal->id);                  
                    break;
                case 'Coordinador':
                    $sql2->where('renovaciones_ventas.coordinador_id', '=', Auth::user()->personal->id);                 
                    break;
            endswitch;

            if (Auth::user()->ficha_personal == "Si" || !Auth::user()->hasPermission('renovaciones.administrativo')) {
                $data = $sql2->orderBy('created_at', 'DESC')->get();
             
            }
        }
        $editarhtml = "";
        $deleteHtml = "";
       
        foreach ($data as $result) {           
            $histotoque             = $result->RelationHistorico();
            $row["id"]              = $result->id;
            $row["creado"]          = date('d/m/Y', strtotime($result->created_at));
            $row["hora"]            = date('h:i A', strtotime($result->created_at));        
            $row["dn"]              = $result->dn;
            $row["nombreapellido"]  = $result->nombre_cliente;
            $row["agente"]          = $result->RelationUser != null ? $result->RelationUser->nombre_apellido : "N/D";
            $row["supervisor"]      = $result->supervisor_id != null ? $result->relationSupervisor->RelationUser->nombre_apellido : "N/D";           
            $row["orden"]           = $result->numero_orden_onix;
            $row["estatus"]         = '<span class="badge badge-info">' . $result->relationEstatus->descripcion . '</span>';
            $vent = 'onclick="DestroyVentas(' . $result->id . '); "';
            switch ($result->estatus_id) {
                case 1:
                    $editarhtml = !Auth::user()->HasPermission('renovaciones.edit') ?
                        '<a href="' . route('renovaciones.edit', $result->id) . '" target="_blank" class="btn btn-sm btn-primary"  data-toggle="tooltip" data-placement="top" title="Editar Datos"><i class="fas fa-edit"></i></a>'
                        : null;

                    $deleteHtml = !Auth::user()->HasPermission('renovaciones.destroy') ?
                        '<button type="button"  ' . $vent . ' class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="top" title="Remover" ><i class="fa fa-trash"</i></button>'
                        : null;
                    break;                
                default:
                    $row["estatus"] = '<span class="badge badge-info">' . $result->relationEstatus->descripcion . '</span>';
                    $histotoque         = $histotoque->orderby('id', 'desc')->first();
                    $textoParaCopiar = $histotoque != null ? 'Gestionado por: ' . $histotoque->usuario . "\n Observaciones: " . $histotoque->observaciones : "N/D";
                    $icon = '<i class="fas fa-info-circle"></i>';
                    $editarhtml = "";
                    $deleteHtml = '<button type="button" class=" btn btn-sm btn-primary"  data-text="' . $textoParaCopiar . '"  id="button' . $result->id . '" onclick="CopyText(' . $result->id . ');" data-estatus="' . $result->estatus_id . '" data-toggle="tooltip" data-placement="top" title="Informacion de la Venta" > 
                        ' . $icon . '</button>';
                    break;
            }
            $formulario = '<form method="POST" id="eliminar' . $result->id . '" action="' . route('renovaciones.delete', $result->id) . '">
                    <input type="hidden" name="_token" value="' . csrf_token() . '" >
                    <input type="hidden" name="_method" value="delete">
                    <div class="btn-group">' . $editarhtml . $deleteHtml . '</div></form>';

            $row["acciones"] = $formulario;
            $arreglo[] = $row;
        }

        usort($arreglo, function ($a, $b) {
            // Comparar por cedula (numérico)
            $datos = strcmp($a['fvc'], $b['fvc']);
            return $datos;
        });

        return response()->json($arreglo);
    }

    public function create()
    {       
         return view('renovaciones.create')->with(["plazos"=>$this->plazos,"cordmap"=>json_encode($this->corddefault)]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
      
        $user = Auth::user();
        try {
        $venta = new renovacionesVentasModel();
        $venta->dn              = $request->dn;
        $venta->nombre_cliente  = mb_strtoupper(strtolower($request->nombre_apellido));
        $venta->equipo          = mb_strtoupper(strtolower($request->equipo));
        $venta->plazo           = $request->plazos;
        $venta->direccion_entrega   =  mb_strtoupper(strtolower($request->direccion));
        $venta->entrega_en      = mb_strtoupper(strtolower($request->entrega_en));
        $venta->numero_orden_onix = $request->ordenonix;
        $venta->precio_equipo   = $request->precio;      
        $venta->latitud         = $request->latitud;
        $venta->longitud        = $request->longitud;      
        $venta->referencias     =  mb_strtoupper(strtolower($request->referencia));
        $venta->observaciones   =  mb_strtoupper(strtolower($request->observaciones));
        
         if ($user->personal !== null) {
                $supervisor     =  $user->personal->jefe_inmediato_id;
                $coordinador    = $user->personal->jefe_inmediato_segundo_id;
                $idPersonal     = $user->personal->id;
            } else {
                $supervisor = null;
                $coordinador = null;
                $idPersonal = null;
            }

            $venta->supervisor_id           = $supervisor;
            $venta->coordinador_id          = $coordinador;
            $venta->personal_id             = $idPersonal;
            $venta->user_id                 = $user->id;
            $venta->estatus_id              = 1;
            $venta->save();

            $historico = new renovacionesHistoricoModel();
            $historico->renovaciones_ventas_id        = $venta->id;
            $historico->usuario         = $user->nombre_apellido;
            $historico->estatus_id      = 1;
            $historico->observaciones   = "Venta Registrada en Sistema";
            $historico->save();

            return back()->with('success', 'Venta guardada correctamente');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al guardar la venta: ' . $e->getMessage());
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
        $venta = renovacionesVentasModel::find($id);
        $latitud = (float)$venta->latitud;
        $longitud = (float)$venta->longitud;
        $cordenada = [$latitud , $longitud];
      
        return view('renovaciones.edit')->with(["plazos"=>$this->plazos,"cordmap"=>json_encode($cordenada),"venta"=>$venta]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        
        $user = Auth::user();
        try {
            $venta = renovacionesVentasModel::find($id);
            $venta->dn              = $request->dn;
            $venta->nombre_cliente  = mb_strtoupper(strtolower($request->nombre_apellido));
            $venta->equipo          = mb_strtoupper(strtolower($request->equipo));
            $venta->plazo           = $request->plazos;
            $venta->direccion_entrega   =  mb_strtoupper(strtolower($request->direccion));
            $venta->entrega_en      = mb_strtoupper(strtolower($request->entrega_en));
            $venta->numero_orden_onix = $request->ordenonix;
            $venta->precio_equipo   = $request->precio;      
            $venta->latitud         = $request->latitud;
            $venta->longitud        = $request->longitud;      
            $venta->referencias     =  mb_strtoupper(strtolower($request->referencia));
            $venta->observaciones   =  mb_strtoupper(strtolower($request->observaciones));
        
            $venta->estatus_id  = 1;
            $venta->save();

            $historico = new renovacionesHistoricoModel();
            $historico->renovaciones_ventas_id = $venta->id;
            $historico->usuario                = $user->nombre_apellido;
            $historico->estatus_id             = 1;
            $historico->observaciones          = "Venta Actualizada en Sistema";
            $historico->save();

            return back()->with('success', 'Venta actualizada correctamente');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al actualizar la venta: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
