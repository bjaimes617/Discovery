<?php

namespace App\Http\Controllers\Claro;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

use App\Models\pymes\EquiposModel;
use App\Models\pymes\EstatusModel;
use App\Models\pymes\ParentescoModel;
use App\Models\pymes\PlanesModel;
use App\Models\pymes\VentasModel;
use App\Models\pymes\ProductosModel;
use App\Models\pymes\AuditoriaModel;
use App\Models\pymes\RecaudosModel;
use App\Models\pymes\PymesHistoricosModel;
use App\Models\Personal;
use App\Exports\Pymes\PymesExport;
use Google\Client;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Api\GoogleApi;

class PymesController extends Controller
{
    private $tipo_cliente;
    private $anticipo;
    private $campania;
    private $producto;
    private $tipo_venta;
    private $reporte;

    public function __construct($tipo_cliente = null, $anticipo = null, $campania = 4, $producto = array(), $tipo_venta = null, $reporte = array())
    {
        $this->tipo_cliente = ["AFILIADOS", "PYMES", "SOHOS"];
        $this->campania     = $campania;
        $this->producto     = ProductosModel::where('active', 1)->get();
        $this->reporte     = ["Tipificaciones Generales", "Tipificaciones Ultimo Estatus"];
    }

    public function index()
    {
        $cargo = "";
        $user = ENV('DEV_USER_CHANGE', false) ?
            Auth::loginUsingId(2, true) :
            Auth::user();

        if ($user->ficha_personal == "Si")
            $cargo = $user->personal->cargo->nombre_cargo;
        switch ($cargo):
            case "Operador":
                $supervisores = Personal::with(['RelationUser'])->select('personal.id', 'users.nombre_apellido')
                    ->where("personal.campana_id", "=", $this->campania)
                    ->where("personal.cargo_id", "=", 4)->where('personal.id', '=', Auth::user()->personal->jefe_inmediato_id)
                    ->join('users', 'personal.user_id', '=', 'users.id')
                    ->orderBy('users.nombre_apellido')->get();
                break;
            case 'Supervisor':
                $supervisores = Personal::with(['RelationUser'])->select('personal.id', 'users.nombre_apellido')
                    ->where("personal.campana_id", "=", $this->campania)
                    ->where("personal.cargo_id", "=", 4)->where('personal.id', '=', Auth::user()->personal->id)
                    ->join('users', 'personal.user_id', '=', 'users.id')
                    ->orderBy('users.nombre_apellido')->get();
                break;
            case 'Coordinador':
                $supervisores = Personal::with(['RelationUser'])->select('personal.id', 'users.nombre_apellido')
                    ->where("personal.campana_id", "=", $this->campania)
                    ->where("personal.cargo_id", "=", 4)->where('personal.jefe_inmediato_id', '=', Auth::user()->personal->id)
                    ->join('users', 'personal.user_id', '=', 'users.id')->where('personal.estatus', 1)
                    ->orderBy('users.nombre_apellido')->get();
                break;
            default:
                if (Auth::user()->hasPermission('claro.pymes.administrativo')) {
                    $supervisores = Personal::with(['relationUser'])->select('personal.id', 'users.nombre_apellido')
                        ->where("personal.campana_id", "=", $this->campania)->where("personal.cargo_id", "=", 4)
                        ->join('users', 'personal.user_id', '=', 'users.id')->where('personal.estatus', 1)
                        ->orderBy('users.nombre_apellido')->get();
                } else {
                    $supervisores = array();
                }
        endswitch;

        return view('claro.pymes.index')->with(["producto" => $this->producto, 'supervisores' => $supervisores]);
    }

    public function GetPlanes(Request $request)
    {
        $planes =  PlanesModel::where('active', 1)->where('producto_id', $request->producto)->get();
        $group =  PlanesModel::select('group')->where('active', 1)->where('producto_id', $request->producto)->groupby('group')->get();
        return json_encode([$group, $planes]);
    }

    public function search(Request $request)
    {

        try {
            $fecha = explode("-", $request->fecha);
            $data = array();
            $arreglo = array();
            $init = carbon::createFromFormat('d/m/Y', trim($fecha[0]))->startOfDay();
            $end = carbon::createFromFormat('d/m/Y', trim($fecha[1]))->endOfDay();

            $sql = VentasModel::with('relationPlanes', 'relationUser', 'relationProducto', 'relationPersonal', 'relationCoordinador', 'relationSupervisor', 'relationEstatus')
                ->whereBetween('claro_pymes_ventas.created_at', array($init, $end));

            if ($request->productos != "todos") {
                $sql->where('claro_pymes_ventas.producto_id', $request->productos);
            }

            $cargo = "";
            ///validamos si la persona tiene ficha de personal
            if (Auth::user()->ficha_personal == "Si") {
                $cargo = Auth::user()->personal->cargo->nombre_cargo;
            }
            /// se valida si viene valores de supervisor 
            if ($request->supervisor != "") {
                if ($cargo == 'Operador') {
                    $sql->where("claro_pymes_ventas.personal_id", "=", Auth::user()->personal->id);
                } else {
                    $sql->where("claro_pymes_ventas.supervisor_id", "=", $request->supervisor);
                }
            } else {
                ///si no, en base al cargo buscamos los datos
                switch ($cargo):
                    case "Operador":
                        $sql->where("claro_pymes_ventas.personal_id", "=", Auth::user()->personal->id);
                        break;
                    case 'Supervisor':
                        $sql->where("claro_pymes_ventas.supervisor_id", "=", Auth::user()->personal->id);
                        break;
                    case 'Coordinador':
                        $sql->where('claro_pymes_ventas.coordinador_id', '=', Auth::user()->personal->id);
                        break;
                endswitch;
            }

            if (Auth::user()->ficha_personal == "Si" || Auth::user()->hasPermission('claro.pymes.administrativo')) {
                $data = $sql->get();
            }
            foreach ($data as $result) {
                $historico = $result->relationHistorico()->where('estatus_id', $result->estatus_id)->first();
                $row["id"]              = $result->id;
                $row["creado"]          = date('d/m/Y', strtotime($result->created_at));
                $row["producto"]        = $result->relationProducto !== null ? $result->relationProducto->descripcion : " - ";
                $row["identificador"]   = $result->identificacion;
                $row["nombreapellido"]  = $result->nombre . " " . $result->apellido_1;
                $row["tipo_cliente"]    = $this->tipo_cliente[$result->tipo_venta];
                $row["plan"]        = $result->relationPlanes->descripcion;
                $row["agente"]      = $result->relationUser != null ? $result->relationUser->nombre_apellido : null;
                $row["supervisor"]  = $result->supervisor_id != null ? $result->relationSupervisor->RelationUser->nombre_apellido : "N/A";
                $row["estatus"]     = $result->relationEstatus->descripcion;

                $vent = 'onclick="DestroyVentas(' . $result->id . '); "';

                switch ($result->estatus_id) {
                    case 1: # registrada
                        $editarhtml = Auth::user()->HasPermission('claro.pymes.edit') ?
                            '<a href="' . route('claro.pymes.edit', $result->id) . '" target="_blank" class="btn btn-sm btn-primary"  data-toggle="tooltip" data-placement="top" title="Editar Datos"><i class="fas fa-edit"></i></a>'
                            : null;

                        $deleteHtml = Auth::user()->HasPermission('claro.pymes.destroy') ?
                            '<button type="button"  ' . $vent . ' class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="top" title="Remover" ><i class="fa fa-trash"</i></button>'
                            : null;
                        break;
                    case 2: #auditada
                        $textoParaCopiar = $result->enviado !== null ?  htmlspecialchars($result->enviado, ENT_QUOTES, 'UTF-8') : "";
                        $icon = '<i class="fas fa-copy"></i>';
                        $togle = '';
                        $estatus = $result->estatus_id;

                        $editarhtml = '<button type="button" class="btn btn-sm btn-success" id="button' . $result->id . '" onclick="CopyText(' . $result->id . ');" data-estatus="' . $estatus . '" data-text="' . $textoParaCopiar . '" >' . $icon . '</button>';
                        $deleteHtml = '';
                        break;
                    case 3: # rechazada                        
                        if ($result->recuperable == 1) {
                            $row["estatus"]     = $result->relationEstatus->descripcion . ' Recuperable ';
                            $editarhtml = Auth::user()->HasPermission('claro.pymes.edit') ?
                                '<a href="' . route('claro.pymes.edit', $result->id) . '" target="_blank" class="btn btn-sm btn-warning"  data-toggle="tooltip" data-placement="top" title="Editar Datos"><i class="fas fa-edit"></i></a>'
                                : null;
                        } else {
                            $editarhtml = '';
                        }
                        $textoParaCopiar = $historico != null ? $historico->observaciones : "";
                        $icon = '<i class="fas fa-exclamation-triangle"></i>';
                        $deleteHtml = '<button type="button" class=" btn btn-sm btn-danger"  data-text="' . $textoParaCopiar . '"  id="button' . $result->id . '" onclick="CopyText(' . $result->id . ');" data-estatus="' . $result->estatus_id . '" data-toggle="tooltip" data-placement="top" title="Rechazada" > 
                        ' . $icon . '</button>';
                        break;
                    default:
                        $editarhtml = '';
                        $deleteHtml = '';
                        break;
                }

                $formulario = '<form method="POST" id="eliminar' . $result->id . '" action="' . route('claro.pymes.delete', $result->id) . '">
                    <input type="hidden" name="_token" value="' . csrf_token() . '" >
                    <input type="hidden" name="_method" value="DELETE">
                    <div class="btn-group">' . $editarhtml . $deleteHtml . '</div></form>';

                $row["acciones"] = $formulario;
                $arreglo[] = $row;
            }

            return response()->json($arreglo, 200);
        } catch (\exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }

    public function create()
    {
        return view('claro.pymes.create')->with([
            "tipo_cliente" => $this->tipo_cliente,
            "producto" => $this->producto,
        ]);
    }

    public function store(Request $request)
    {

        $user = Auth::user();
        try {
            $venta = new VentasModel();

            $venta->id_contacto     = $request->idcontacto;
            $venta->tipo_venta      = $request->tipo_cliente;

            switch ($request->tipo_cliente) {
                case 0: #AFILIADOS
                    $venta->identificacion = $request->cedulatitular;
                    $venta->ordenpatronal  = $request->ordenpatronal;
                    break;
                case 1: #PYMES
                    $venta->identificacion      = $request->personeriajuridica;
                    $venta->representantelegal  = $request->representantelegal;
                    break;
                case 2: #soho
                    $venta->identificacion = $request->cedulatitularpymes;
                    break;
            }
            $venta->nombre            = ucfirst(strtolower($request->nombre));
            $venta->email             = strtolower($request->email);
            $venta->telefono_a_llamar = $request->telefono;

            $venta->provincia       = ucfirst(strtolower($request->provincia));
            $venta->canton          = ucfirst(strtolower($request->canton));
            $venta->distrito        = ucfirst(strtolower($request->distrito));
            $venta->barrio          = ucfirst(strtolower($request->barrio));
            $venta->detalle_direccion = ucfirst(strtolower($request->direccion));

            $venta->producto_id     = $request->producto;
            $venta->plan_id         = $request->tipo_plan;

            switch ($request->producto) {
                case 1: #gpon
                    $venta->cantidad = $request->cantidadstb;
                    $venta->cordenadas = $request->coordenadas;
                    break;
                case 2:
                    $venta->equipo = $request->equipo;
                    $venta->portabilidad = $request->portabilidad;
                    break;
            }
            $venta->precio_plan = $request->precioplan;

            $venta->observaciones           = ucfirst(strtolower($request->observacion));
            $venta->supervisor_id           = $user->personal !== null ? $user->personal->jefe_inmediato_segundo : null;
            $venta->coordinador_id          = $user->personal !== null ? $user->personal->jefe_inmediato_segundo_id : null;
            $venta->personal_id             = $user->personal !== null ? $user->personal->id : null;
            $venta->user_id                 = $user->id;
            $venta->estatus_id              = 1;
            $venta->save();

            $historico = new PymesHistoricosModel();
            $historico->claro_pymes_ventas_id = $venta->id;
            $historico->estatus_id             = $venta->estatus_id;
            $historico->document_checks_partials = json_encode(["" => ""]);
            $historico->usuario                = $user->nombre_apellido;
            $historico->observaciones          = "Venta registrada exitosamente.";
            $historico->save();


            Session::flash('successVentas', 'La venta fue registrada exitosamente.');
            return redirect()->route('claro.pymes.create');
        } catch (\Throwable $e) {
            Session::flash('errorVentas', $e->getMessage());
            return redirect()->back();
        }
    }

    public function edit($id)
    {
        try {
            $venta = VentasModel::find($id);
            $planes = PlanesModel::where('active', 1)->where('producto_id', $venta->producto_id)->get();
            $group = PlanesModel::select('group')->where('active', 1)->where('producto_id', $venta->producto_id)->groupby('group')->get();

            return view('claro.pymes.edit')->with([
                "tipo_cliente" => $this->tipo_cliente,
                "producto" => $this->producto,
                "planes" => $planes,
                "group" => $group,
                "venta" => $venta
            ]);
        } catch (\Throwable $e) {

            return abort(404, "lo Sentimos, no se encontro los datos de la venta");
        }
    }

    public function update(Request $request, $id)
    {
        //  dd($request->all());
        $user = Auth::user();
        try {
            $venta =  VentasModel::find($id);

            $venta->id_contacto     = $request->idcontacto;
            $venta->tipo_venta      = $request->tipo_cliente;

            switch ($request->tipo_cliente) {
                case "0": #AFILIADOS
                    $venta->identificacion = $request->cedulatitular;
                    $venta->ordenpatronal  = $request->ordenpatronal;
                    break;
                case "1": #PYMES
                    $venta->identificacion      = $request->personeriajuridica;
                    $venta->representantelegal  = $request->representantelegal;
                    break;
                case "2": #soho
                    $venta->identificacion = $request->cedulatitularpymes;
                    break;
            }
            $venta->nombre            = ucfirst(strtolower($request->nombre));
            $venta->email             = strtolower($request->email);
            $venta->telefono_a_llamar = $request->telefono;

            $venta->provincia       = ucfirst(strtolower($request->provincia));
            $venta->canton          = ucfirst(strtolower($request->canton));
            $venta->distrito        = ucfirst(strtolower($request->distrito));
            $venta->barrio          = ucfirst(strtolower($request->barrio));
            $venta->detalle_direccion = ucfirst(strtolower($request->direccion));

            $venta->producto_id     = $request->producto;
            $venta->plan_id         = $request->tipo_plan;

            switch ($request->producto) {
                case 1: #gpon
                    $venta->cantidad = $request->cantidadstb;
                    $venta->cordenadas = $request->coordenadas;
                    break;
                case 2:
                    $venta->equipo = $request->equipo;
                    $venta->poetabilidad = $request->poetabilidad;
                    break;
            }
            $venta->precio_plan = $request->precioplan;

            $venta->observaciones           = ucfirst(strtolower($request->observacion));
            $venta->supervisor_id           = $user->personal !== null ? $user->personal->jefe_inmediato_segundo : null;
            $venta->coordinador_id          = $user->personal !== null ? $user->personal->jefe_inmediato_segundo_id : null;
            $venta->personal_id             = $user->personal !== null ? $user->personal->id : null;
            $venta->user_id                 = $user->id;
            $venta->estatus_id              = 1;
            $venta->save();

            Session::flash('success', 'La venta fue actualizada exitosamente.');
            return redirect()->route('claro.pymes.index');
        } catch (\Throwable $th) {
            Session::flash('error', $th->getMessage());
            return back();
        }
    }

    public function destroy($id)
    {
        try {

            $venta = VentasModel::find($id);
            $venta->delete();

            return response()->json("registro Eliminado exitosamente.", 200);
        } catch (\Throwable $e) {
            return response()->json($e->getMessage(), 500);
        }
    }

    public function auditoriaIndex()
    {
        $cargo = "";
        $user = ENV('DEV_USER_CHANGE', false) ?
            Auth::loginUsingId(2, true) :
            Auth::user();

        $estatus = EstatusModel::all();
        $SegumientoEstatus  = EstatusModel::whereIn("id", [5, 6, 7, 8, 9, 10, 11, 12, 13])->get();
        if ($user->ficha_personal == "Si")
            $cargo = $user->personal->cargo->nombre_cargo;
        switch ($cargo):
            case "Operador":
                $supervisores = Personal::with(['RelationUser'])->select('personal.id', 'users.nombre_apellido')
                    ->where("personal.campana_id", "=", $this->campania)
                    ->where("personal.cargo_id", "=", 4)->where('personal.id', '=', Auth::user()->personal->jefe_inmediato_id)
                    ->join('users', 'personal.user_id', '=', 'users.id')
                    ->orderBy('users.nombre_apellido')->get();
                break;
            case 'Supervisor':
                $supervisores = Personal::with(['RelationUser'])->select('personal.id', 'users.nombre_apellido')
                    ->where("personal.campana_id", "=", $this->campania)
                    ->where("personal.cargo_id", "=", 4)->where('personal.id', '=', Auth::user()->personal->id)
                    ->join('users', 'personal.user_id', '=', 'users.id')
                    ->orderBy('users.nombre_apellido')->get();
                break;
            case 'Coordinador':
                $supervisores = Personal::with(['RelationUser'])->select('personal.id', 'users.nombre_apellido')
                    ->where("personal.campana_id", "=", $this->campania)
                    ->where("personal.cargo_id", "=", 4)->where('personal.jefe_inmediato_id', '=', Auth::user()->personal->id)
                    ->join('users', 'personal.user_id', '=', 'users.id')->where('personal.estatus', 1)
                    ->orderBy('users.nombre_apellido')->get();
                break;
            default:
                if (Auth::user()->hasPermission('claro.pymes.administrativo')) {
                    $supervisores = Personal::with(['relationUser'])->select('personal.id', 'users.nombre_apellido')
                        ->where("personal.campana_id", "=", $this->campania)->where("personal.cargo_id", "=", 4)
                        ->join('users', 'personal.user_id', '=', 'users.id')->where('personal.estatus', 1)
                        ->orderBy('users.nombre_apellido')->get();
                } else {
                    $supervisores = array();
                }
        endswitch;

        return view('claro.pymes.auditoria.index')->with([
            "producto" => $this->producto,
            'supervisores' => $supervisores,
            "estatussegumientos" => $SegumientoEstatus,
            "estatus" => $estatus
        ]);
    }

    public function auditoriaSearch(Request $request)
    {
        try {
            $fecha = explode("-", $request->fecha);
            $data = array();
            $arreglo = array();
            $init = carbon::createFromFormat('d/m/Y', trim($fecha[0]))->startOfDay();
            $end = carbon::createFromFormat('d/m/Y', trim($fecha[1]))->endOfDay();

            $sql = VentasModel::with(
                'relationPlanes',
                'relationUser',
                'relationPersonal',
                'relationCoordinador',
                'relationSupervisor',
                'relationEstatus'
            )
                ->whereBetween('claro_pymes_ventas.created_at', array($init, $end));

            if ($request->productos != "todos") {
                $sql->where('claro_pymes_ventas.producto_id', $request->productos);
            }

            if ($request->estatus != "todos") {
                $sql->where('estatus_id', $request->estatus);
            }

            $cargo = "";
            ///validamos si la persona tiene ficha de personal
            if (Auth::user()->ficha_personal == "Si") {
                $cargo = Auth::user()->personal->cargo->nombre_cargo;
            }
            /// se valida si viene valores de supervisor 
            if ($request->supervisor != "todos") {
                if ($cargo == 'Operador') {
                    $sql->where("claro_pymes_ventas.personal_id", "=", Auth::user()->personal->id);
                } else {
                    $sql->where("claro_pymes_ventas.supervisor_id", "=", $request->supervisor);
                }
            } else {
                ///si no, en base al cargo buscamos los datos
                switch ($cargo):
                    case "Operador":
                        $sql->where("claro_pymes_ventas.personal_id", "=", Auth::user()->personal->id);
                        break;
                    case 'Supervisor':
                        $sql->where("claro_pymes_ventas.supervisor_id", "=", Auth::user()->personal->id);
                        break;
                    case 'Coordinador':
                        $sql->where('claro_pymes_ventas.coordinador_id', '=', Auth::user()->personal->id);
                        break;
                endswitch;
            }

            if (Auth::user()->ficha_personal == "Si" || Auth::user()->hasPermission('claro.pymes.administrativo')) {
                $data = $sql->get();
            }

            if ($request->identificador != "" && $request->supervisor == "todos" && $request->productos == "todos") {

                $data = VentasModel::with(
                    'relationPlanes',
                    'relationUser',
                    'relationPersonal',
                    'relationCoordinador',
                    'relationSupervisor',
                    'relationEstatus'
                )->where("identificacion", 'like', '%' . $request->identificador . '%')->get();
            }

            foreach ($data as $result) {
                $historico = $result->relationHistorico()->where('estatus_id', $result->estatus_id)->first();

                $row["id"]              = $result->id;
                $row["creado"]          = date('d/m/Y', strtotime($result->created_at));
                $row["producto"]        = $result->relationProducto !== null ? $result->relationProducto->descripcion : " - ";
                $row["identificador"]   = $result->identificacion;
                $row["nombreapellido"]  = $result->nombre . " " . $result->apellido_1;
                $row["tipo_cliente"]    = $this->tipo_cliente[$result->tipo_venta];
                $row["plan"]        = $result->relationPlanes->descripcion;
                $row["agente"]      = $result->relationUser != null ? $result->relationUser->nombre_apellido : null;
                $row["supervisor"]  = $result->supervisor_id != null ? $result->relationSupervisor->RelationUser->nombre_apellido : "N/A";
                $row["estatus"]     = $result->relationEstatus->descripcion;

                $row["auditado"]    = $result->relationAuditoria != null ? $result->relationAuditoria->relationAuditUser->nombre_apellido : "N/D";

                $Permissionedit      = Auth::user()->HasPermission('claro.pymes.auditoria.edit');
                $botonEditar        = '';
                $botonSegumientos   = '';
                $botonVer           = '';
                $vent = 'onclick="DestroyVentas(' . $result->id . '); "';

                $editarhtml = '<a href="' . route('claro.pymes.audit', $result->id) . '" target="_blank" class="btn btn-sm btn-primary"  data-toggle="tooltip" data-placement="top" title="Editar Datos"><i class="fas fa-edit"></i></a>';
                $styleBtn = $result->estatus_id === 3 ? "btn-danger" : "btn-success";
                switch ($result->estatus_id) {
                    case 1: #recien registrada

                        $icon = '<i class="fas fa-edit"></i>';
                        $style = 'btn-primary';
                        $toggleText = "Auditar Venta";
                        $botonEditar = '<a href="' . route('claro.pymes.audit', $result->id) . '" target="_blank" class="btn btn-sm ' . $style . '" 
                        data-toggle="tooltip" data-placement="top" title="' . $toggleText . '">' . $icon . '</a>';

                        break;
                    case 2: #Aprobada
                        $textoParaCopiar = htmlspecialchars($result->enviado != null ? $result->enviado : "", ENT_QUOTES, 'UTF-8');
                        $icon = '<i class="fas fa-copy"></i>';
                        $style = 'btn-success';
                        $toggleText = "Detalles De la Venta";

                        $botonVer = '<button class="btn btn-sm ' . $style . '" id="button' . $result->id . '" onclick="CopyText(' . $result->id . ');" 
                        data-estatus="' . $result->estatus_id . '" data-text="' . $textoParaCopiar . '"  data-toggle="tooltip" data-placement="top" 
                        title="' . $toggleText . '" >' . $icon . '</button>';

                        $botonSegumientos = '<button class="btn btn-sm btn-warning" 
                        data-toggle="tooltip" data-placement="top" id="buttonSegumiento' . $result->id . '"  data-estatus="' . $result->estatus_id . '" data-producto="' . $result->producto_id . '" data-venta="' . $result->id . '" onclick="ActiveRegisterSegumiento(' . $result->id . ');" title="Añadir Seguimiento">
                        <i class="far fa-plus-square"></i></button>';
                        break;
                    case 3: #rechazada
                    case 4: #revisada
                        $textoParaCopiar = $historico != null ? $historico->observaciones : "";
                        $icon = '<i class="fas fa-exclamation-triangle"></i>';
                        $style = 'btn-danger';
                        $toggleText = "Detalles De la Venta";
                        $row["estatus"] = $historico !== null ? $result->relationEstatus->descripcion : "Sin Auditoria Existente";

                        $botonVer = '<button class="btn btn-sm btn-danger" id="button' . $result->id . '" onclick="CopyText(' . $result->id . ');" 
                       data-estatus="3" data-text="' . $textoParaCopiar . '"  data-toggle="tooltip" data-placement="top" title="' . $toggleText . '" >' . $icon . '</button>';

                        switch ($historico !== null) {
                            case true: #existe registro de auditoria
                                $toggleText = "Auditar Venta";
                                $icon = '<i class="fas fa-edit"></i>';
                                $style = 'btn-primary';
                                if ($result->recuperable == 1) {
                                    $botonEditar = '<a href="' . route('claro.pymes.audit', $result->id) . '" target="_blank" class="btn btn-sm btn-primary" 
                        data-toggle="tooltip" data-placement="top" title="' . $toggleText . '">' . $icon . '</a>';
                                } else {
                                    $botonEditar = '';
                                }
                                break;
                            case false: #no existe registro de auditoria 
                                $toggleText = "Auditar Venta";
                                $icon = '<i class="fas fa-edit"></i>';
                                $style = 'btn-primary';
                                $botonEditar = '<a href="' . route('claro.pymes.audit', $result->id) . '" target="_blank" class="btn btn-sm btn-primary" 
                        data-toggle="tooltip" data-placement="top" title="' . $toggleText . '">' . $icon . '</a>';

                                break;
                        }
                        break;
                    case 12: #instalada - no se meustra nada
                        break;
                    default: #seguimientos
                        $botonSegumientos = '<button class="btn btn-sm btn-warning" 
                        data-toggle="tooltip" data-placement="top" id="buttonSegumiento' . $result->id . '"  data-estatus="' . $result->estatus_id . '" data-producto="' . $result->producto_id . '" data-venta="' . $result->id . '" onclick="ActiveRegisterSegumiento(' . $result->id . ');" title="Añadir Seguimiento">
                        <i class="far fa-plus-square"></i></button>';
                        break;
                }
                $botontes = $botonVer . ($Permissionedit ? $botonEditar : '') . $botonSegumientos;

                $formulario = '<div class="btn-group">' . $botontes . '</div>';

                $row["acciones"] = $formulario;
                $arreglo[] = $row;
            }

            return response()->json($arreglo, 200);
        } catch (\exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }

    public function seguimientosItems(Request $request)
    {
        try {
            $venta = VentasModel::find($request->venta);

            $recaudos = RecaudosModel::where("producto_id", $request->producto)->where('active', 1)->get();

            $historico = PymesHistoricosModel::where("claro_pymes_ventas_id", $request->venta)
                ->orderBy('id', 'desc')->limit(1)->first();

            $checks = json_decode($historico->document_checks_partials);

            return response()->json([$recaudos, $checks, $historico], 200);
        } catch (\exception $e) {

            return response()->json($e->getMessage() . ' ' . $e, 500);
        }
    }
    public function seguimientosStore(Request $request)
    {
        try {

            $venta = VentasModel::find($request->venta);
            $venta->estatus_id = $request->newestatus;
            $venta->save();

            $array = array();
            foreach (RecaudosModel::where("producto_id", $venta->producto_id)->where('active', 1)->get()  as $documents) {
                if ($request->input('check' . $documents->id)) {
                    $array[$documents->id] = $request->input('check' . $documents->id);
                }
            }

            $historico = new PymesHistoricosModel();
            $historico->claro_pymes_ventas_id = $request->venta;
            $historico->usuario                = Auth::user()->nombre_apellido;
            $historico->estatus_id = $request->newestatus;
            $historico->document_checks_partials = json_encode($array);
            $historico->observaciones = $request->observaciones;
            $historico->save();

            try {
                //   $this->SendDataSheet($venta, $enviar = "aprobada");

                return response()->json(["message" => "Seguimiento guardado correctamente"], 200);
            } catch (\Google\Service\Exception $gr) {
                return response()->json(["message" => "Ocurrio un problema al Enviar Al Sheet. Comuniquese con el Administrador."], 500);
            }
        } catch (\exception $e) {
            return response()->json($e->getMessage() . ' ' . $e, 500);
        }
    }

    public function auditoriaEdit($id)
    {
        try {
            $venta = VentasModel::find($id);
            $planes = PlanesModel::where('active', 1)->where('producto_id', $venta->producto_id)->get();
            $group = PlanesModel::select('group')->where('active', 1)->where('producto_id', $venta->producto_id)->groupby('group')->get();

            return view('claro.pymes.auditoria.edit')->with([
                "tipo_cliente" => $this->tipo_cliente,
                "producto" => $this->producto,
                "planes" => $planes,
                "group" => $group,
                "venta" => $venta
            ]);
        } catch (\Throwable $e) {

            return abort(404, "lo Sentimos, no se encontro los datos de la venta");
        }
    }

    public function auditoriaStore(request $request, $id)
    {
        //dd($request->all());
        $user = Auth::user();
        try {
            $venta =  VentasModel::find($id);

            $venta->id_contacto     = $request->idcontacto;
            $venta->tipo_venta      = $request->tipo_cliente;

            switch ($request->tipo_cliente) {
                case "0": #AFILIADOS
                    $venta->identificacion = $request->cedulatitular;
                    $venta->ordenpatronal  = $request->ordenpatronal;
                    break;
                case "1": #PYMES
                    $venta->identificacion      = $request->personeriajuridica;
                    $venta->representantelegal  = $request->representantelegal;
                    break;
                case "2": #soho
                    $venta->identificacion = $request->cedulatitularpymes;
                    break;
            }
            $venta->nombre            = ucfirst(strtolower($request->nombre));
            $venta->email             = strtolower($request->email);
            $venta->telefono_a_llamar = $request->telefono;

            $venta->provincia       = ucfirst(strtolower($request->provincia));
            $venta->canton          = ucfirst(strtolower($request->canton));
            $venta->distrito        = ucfirst(strtolower($request->distrito));
            $venta->barrio          = ucfirst(strtolower($request->barrio));
            $venta->detalle_direccion = ucfirst(strtolower($request->direccion));

            $venta->producto_id     = $request->producto;
            $venta->plan_id         = $request->tipo_plan;

            switch ($request->producto) {
                case 1: #gpon
                    $venta->cantidad = $request->cantidadstb;
                    $venta->cordenadas = $request->coordenadas;
                    break;
                case 2:
                    $venta->equipo = $request->equipo;
                    $venta->portabilidad = $request->portabilidad;
                    break;
            }
            $venta->precio_plan = $request->precioplan;
            $venta->estatus_id = $request->auditoria == "aprobada" ? 2 : 3;
            $venta->save();

            $auditoria = AuditoriaModel::where('claro_pymes_ventas_id', $venta->id)->exists() ?
                AuditoriaModel::where('claro_pymes_ventas_id', $venta->id)->first() :
                new AuditoriaModel();

            $auditoria->claro_pymes_ventas_id = $id;
            $auditoria->user_id         = Auth::user()->id;
            $auditoria->enviado         = $request->auditoria === "aprobada" ? $request->auditoriatext : null;
            $auditoria->observaciones   = $request->auditoria === "aprobada" ? null : $request->auditoriatext;
            $auditoria->save();

            try {
                //$this->SendDataSheet($venta, $enviar = $request->auditoria === "aprobada" ? "aprobada" : "rechazada");               
                Session::flash('success', 'Auditoria procesada exitosamente.');
                return redirect()->route('claro.pymes.auditoriaIndex');
            } catch (\Google\Service\Exception $gr) {
                Session::flash('error', 'Ocurrio un problema al Enviar Al Sheet. Comuniquese con el Administrador.');
                return back();
            }
        } catch (\Throwable $th) {
            Session::flash('error', "Error general ak Procesar " . $th->getMessage());
            return back();
        }
    }

    private function SendDataSheet($venta, $enviar)
    {
        $array = [
            $venta->id_contacto,
            carbon::create($venta->created_at)->format('d/m/Y H:i:s'),
            $venta->tipo_venta == null ? "" : $this->tipo_cliente[$venta->tipo_venta],
            $venta->identificacion,
            $venta->tipo_venta == 0 ? $venta->ordenpatronal : "",
            $venta->tipo_venta == 1 ? $venta->representantelegal : "",
            $venta->nombre,
            $venta->telefono_a_llamar,
            $venta->email,
            $venta->provincia,
            $venta->canton,
            $venta->distrito,
            $venta->barrio,
            $venta->detalle_direccion,
            $venta->relationProducto->descripcion, #producto gpon / movil
            $venta->producto_id == 1 ? $venta->relationPlanes->descripcion : "", #plan GPon
            $venta->producto_id == 1 ? $venta->cordenadas : "",
            $venta->producto_id == 1 ? $venta->cantidad : "",

            $venta->producto_id == 2 ? $venta->relationPlanes->descripcion : "", #plan pospago
            $venta->producto_id == 2 ? $venta->equipo : "", #equipo
            $venta->producto_id == 2 ? $venta->portabilidad : "", #portabilidad         
            $venta->precio_plan,
            $venta->observaciones != null ? $venta->observaciones : "",
            $venta->relationCoordinador != null ? $venta->relationCoordinador->RelationUser->nombre_apellido : "No Disponible",
            $venta->relationSupervisor != null ? $venta->relationSupervisor->RelationUser->nombre_apellido : "No Disponible",
            $venta->relationPersonal != null ? $venta->relationPersonal->numero_empleado : "No Disponible",
            $venta->relationUser->nombre_apellido
        ];
        //dd($array, $venta->relationPersonal);
        $dataMatrix = [array_values($array)];
        /*
        try {

             if ($request->auditoria == "aprobada") {
                    $googleApi = new GoogleApi(
                        "Discovery",
                        ENV('CLARO_pymes_CREDENTIALS', "directagroupingsoftware-51fef28451b7.json"),
                        ENV('CLARO_pymes_SHEET', "1C3KRVzmnWvpmmg27nZXJYXbiDztmmSDk_g02lifC8YI"),
                        ENV('CLARO_pymes_NOMBRE', "Ventas Pymes")
                    );
                    $googleApi->storeResourcesSheets($dataMatrix);
                }

            return true;
        } catch (\Google\Service\Exception $gr) {
            return false;
        }*/
    }

    public function ReportesIndex()
    {

        $cargo = "";
        $user = ENV('DEV_USER_CHANGE', false) ? Auth::loginUsingId(2, true) : Auth::user();

        if ($user->ficha_personal == "Si")
            $cargo = $user->personal->cargo->nombre_cargo;
        switch ($cargo):
            case "Operador":
                $supervisores = Personal::with(['RelationUser'])->select('personal.id', 'users.nombre_apellido')->where("personal.campana_id", "=", 3)
                    ->where("personal.cargo_id", "=", 4)->where('personal.id', '=', Auth::user()->personal->jefe_inmediato_id)
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
                    ->where("personal.campana_id", "=", 3)
                    ->where("personal.cargo_id", "=", 4)->where('personal.jefe_inmediato_id', '=', Auth::user()->personal->id)
                    ->join('users', 'personal.user_id', '=', 'users.id')->where('personal.estatus', 1)
                    ->orderBy('users.nombre_apellido')->get();
                break;
            default:
                if (Auth::user()->hasPermission('claro.pymes.administrativo')) {
                    $supervisores = Personal::with(['relationUser'])->select('personal.id', 'users.nombre_apellido')
                        ->where("personal.campana_id", "=",  $this->campania)->where("personal.cargo_id", "=", 4)
                        ->join('users', 'personal.user_id', '=', 'users.id')->where('personal.estatus', 1)
                        ->orderBy('users.nombre_apellido')->get();
                } else {
                    $supervisores = array();
                }
        endswitch;

        return view("claro.pymes.reportes.index")->with(["producto" => $this->producto, "reporte" => $this->reporte, 'supervisores' => $supervisores]);
    }

    public function ReportesData(Request $request)
    {
        $date       = explode("-", $request->fecha);
        $end        = trim($date[1]);
        $dateinit   = Carbon::createFromFormat('d/m/Y', trim($date[0]))->startOfDay()->format('Y-m-d H:i:s');
        $dateEnd    = Carbon::createFromFormat('d/m/Y', $end)->endOfDay()->format('Y-m-d H:i:s');

        $ventas     = VentasModel::whereBetween('created_at', [$dateinit, $dateEnd]);

        if ($request->productos !== "todos") {
            $ventas->where('producto_id', $request->productos);
        }

        $cargo = "";
        ///validamos si la persona tiene ficha de personal
        if (Auth::user()->ficha_personal == "Si") {
            $cargo = Auth::user()->personal->cargo->nombre_cargo;
        }
        /// se valida si viene valores de supervisor 
        if ($request->supervisor !== "todos") {
            if ($cargo == 'Operador') {
                $ventas->where("personal_id", "=", Auth::user()->personal->id);
            } else {
                $ventas->where("supervisor_id", "=", $request->supervisor);
            }
        } else {
            ///si no, en base al cargo buscamos los datos
            switch ($cargo):
                case "Operador":
                    $ventas->where("claro_masivo_ventas.personal_id", "=", Auth::user()->personal->id);
                    break;
                case 'Supervisor':
                    $ventas->where("claro_masivo_ventas.supervisor_id", "=", Auth::user()->personal->id);
                    break;
                case 'Coordinador':
                    $ventas->where('claro_masivo_ventas.coordinador_id', '=', Auth::user()->personal->id);
                    break;
            endswitch;
        }

        if (Auth::user()->ficha_personal == "Si" || Auth::user()->hasPermission('claro.masivos.administrativo')) {
            $data = $ventas->get();
        }

        $estatus    = EstatusModel::all()->pluck('descripcion', 'id')->toArray();

        switch ($request->reporte) {
            case 0:
                $nombre = "Claro Pymes Tipificacion General";
                $data = $this->GetAllData($data, $estatus);
                break;
            case 1:
                $nombre = "Claro Pymes Tipificacion Ultimo Estatus";
                $data = $this->getUltimateEstatus($data, $estatus);
                break;
        }

        usort($data, function ($a, $b) {
            $fechaA = Carbon::createFromFormat('d/m/Y H:i:s', $a['fecha']);
            $fechaB = Carbon::createFromFormat('d/m/Y H:i:s', $b['fecha']);
            return $fechaA->getTimestamp() <=> $fechaB->getTimestamp();
        });

        return Excel::download(new PymesExport($data), $nombre . ' ' . $dateinit . ' - ' . $dateEnd . '.xlsx');
    }

    private function GetAllData($ventas, $estatus)
    {
        $array      = array();
        $data       = array();

        foreach ($ventas as $venta) {

            if (!$venta->relationHistorico->isEmpty()) {
                foreach ($venta->relationHistorico as $historico) {
                    $array2["id"] = $venta->id;
                    $array2["id_contacto"] = $venta->id_contacto;
                    $array2["fecha"] = carbon::create($venta->created_at)->format('d/m/Y H:i:s');
                    $array2["tipo_venta"] = $venta->tipo_venta == null ? "" : $this->tipo_cliente[$venta->tipo_venta];
                    $array2["identificacion"] = $venta->identificacion;
                    $array2["ordenpatronal"] = $venta->tipo_venta == 0 ? $venta->ordenpatronal : "";
                    $array2["representantelegal"] = $venta->tipo_venta == 1 ? $venta->representantelegal : "";
                    $array2["nombre"] = $venta->nombre;
                    $array2["telefono_a_llamar"] = $venta->telefono_a_llamar;
                    $array2["email"] = $venta->email;
                    $array2["provincia"] = $venta->provincia;
                    $array2["canton"] = $venta->canton;
                    $array2["distrito"] = $venta->distrito;
                    $array2["barrio"] = $venta->barrio;
                    $array2["detalle_direccion"] = $venta->detalle_direccion;
                    $array2["producto"] = $venta->relationProducto->descripcion; #producto gpon / movil
                    $array2["plan_gpon"] = $venta->producto_id == 1 ? $venta->relationPlanes->descripcion : ""; #plan GPon
                    $array2["cordenadas"] = $venta->producto_id == 1 ? $venta->cordenadas : "";
                    $array2["cantidad"] = $venta->producto_id == 1 ? $venta->cantidad : "";
                    $array2["plan_pospago"] = $venta->producto_id == 2 ? $venta->relationPlanes->descripcion : ""; #plan pospago
                    $array2["equipo"] = $venta->producto_id == 2 ? $venta->equipo : ""; #equipo
                    $array2["portabilidad"] = $venta->producto_id == 2 ? $venta->portabilidad : ""; #portabilidad         
                    $array2["precio_plan"] = $venta->precio_plan;
                    $array2["observaciones"] = $venta->observaciones != null ? $venta->observaciones : "";
                    $array2["coordinador"] = $venta->relationCoordinador != null ? $venta->relationCoordinador->RelationUser->nombre_apellido : "No Disponible";
                    $array2["supervisor"] = $venta->relationSupervisor != null ? $venta->relationSupervisor->RelationUser->nombre_apellido : "No Disponible";
                    $array2["personal"] = $venta->relationPersonal != null ? $venta->relationPersonal->numero_empleado : "No Disponible";
                    $array2["user"] = $venta->relationUser->nombre_apellido;
                    $array2["auditor"] = $historico->usuario;
                    $array2["observaciones"] = $historico->observaciones;
                    $array2["estatus"] = $estatus[$historico->estatus_id];
                    $array2["documentos"] = json_decode($historico->document_checks_partials, true);
                    $data[] = $array2;
                }
            } else {
                continue;
            }
        }

        return $data;
    }

    private function getUltimateEstatus($ventas, $estatus)
    {
        $array      = array();
        $data       = array();

        foreach ($ventas as $venta) {
            $array["id"] = $venta->id;
            $array["id_contacto"] = $venta->id_contacto;
            $array["fecha"] = carbon::create($venta->created_at)->format('d/m/Y H:i:s');
            $array["tipo_venta"] = $venta->tipo_venta == null ? "" : $this->tipo_cliente[$venta->tipo_venta];
            $array["identificacion"] = $venta->identificacion;
            $array["ordenpatronal"] = $venta->tipo_venta == 0 ? $venta->ordenpatronal : "";
            $array["representantelegal"] = $venta->tipo_venta == 1 ? $venta->representantelegal : "";
            $array["nombre"] = $venta->nombre;
            $array["telefono_a_llamar"] = $venta->telefono_a_llamar;
            $array["email"] = $venta->email;
            $array["provincia"] = $venta->provincia;
            $array["canton"] = $venta->canton;
            $array["distrito"] = $venta->distrito;
            $array["barrio"] = $venta->barrio;
            $array["detalle_direccion"] = $venta->detalle_direccion;
            $array["producto"] = $venta->relationProducto->descripcion; #producto gpon / movil
            $array["plan_gpon"] = $venta->producto_id == 1 ? $venta->relationPlanes->descripcion : ""; #plan GPon
            $array["cordenadas"] = $venta->producto_id == 1 ? $venta->cordenadas : "";
            $array["cantidad"] = $venta->producto_id == 1 ? $venta->cantidad : "";

            $array["plan_pospago"] = $venta->producto_id == 2 ? $venta->relationPlanes->descripcion : ""; #plan pospago
            $array["equipo"] = $venta->producto_id == 2 ? $venta->equipo : ""; #equipo
            $array["portabilidad"] = $venta->producto_id == 2 ? $venta->portabilidad : ""; #portabilidad         
            $array["precio_plan"] = $venta->precio_plan;
            $array["observaciones"] = $venta->observaciones != null ? $venta->observaciones : "";
            $array["coordinador"] = $venta->relationCoordinador != null ? $venta->relationCoordinador->RelationUser->nombre_apellido : "No Disponible";
            $array["supervisor"] = $venta->relationSupervisor != null ? $venta->relationSupervisor->RelationUser->nombre_apellido : "No Disponible";
            $array["personal"] = $venta->relationPersonal != null ? $venta->relationPersonal->numero_empleado : "No Disponible";
            $array["user"] = $venta->relationUser->nombre_apellido;
            $array["estatus"] = $estatus[$venta->estatus_id];

            if (PymesHistoricosModel::where('claro_pymes_ventas_id', $venta->id)->orderBy('id', 'DESC')->exists()) {
                $historico =  PymesHistoricosModel::where('claro_pymes_ventas_id', $venta->id)->orderBy('id', 'DESC')->limit(1)->first();
                $array["observaciones"] = $historico->observaciones;
                $array["documentos"] = json_decode($historico->document_checks_partials, true);
                $array["auditor"] = $historico->usuario;
            } else {
                $array["documentos"] = array();
                $array["observaciones"] = "";
                $array["auditor"] = "";
            }
            $data[] = $array;
        }

        return $data;
    }
}
