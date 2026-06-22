<?php

namespace App\Http\Controllers\Claro;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

use App\Models\masivos\EquiposModel;
use App\Models\masivos\EstatusModel;
use App\Models\masivos\ParentescoModel;
use App\Models\masivos\PlanesModel;
use App\Models\masivos\VentasModel;
use App\Models\masivos\ProductosModel;
use App\Models\masivos\AuditoriaModel;
use App\Models\masivos\RecaudosModel;
use App\Models\masivos\MavisoHistoricosModel;
use App\Models\Personal;

use App\Exports\Masivos\MasivosExport;
use Maatwebsite\Excel\Facades\Excel;

use Google\Client;
use Carbon\Carbon;

use App\Http\Controllers\Api\GoogleApi;

class MasivosController extends Controller
{
    private $tipo_cliente;
    private $anticipo;
    private $campania;
    private $producto;
    private $tipo_venta;
    private $reporte;

    public function __construct($tipo_cliente = null, $anticipo = null, $campania = 3, $reporte = array(), $producto = array(), $tipo_venta = null)
    {
        $this->tipo_cliente = ["No Asalariado", "Asalariado", "Segmento A", "Segmento B", "Segmento C"];
        $this->anticipo     = ["No Paga", "Paga", "Paga Instalacion de Modem"];
        $this->tipo_venta   = ["Hogar", "Portabilidad", "Linea Nueva", "Modem", "Migracion"];
        $this->campania     = $campania;
        $this->producto     = ProductosModel::where('active', 1)->get();
        $this->reporte     = ["Tipificaciones Generales", "Tipificaciones Ultimo Estatus"];
    }

    public function index()
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
                if (Auth::user()->hasPermission('claro.masivos.administrativo')) {
                    $supervisores = Personal::with(['relationUser'])->select('personal.id', 'users.nombre_apellido')
                        ->where("personal.campana_id", "=", 3)->where("personal.cargo_id", "=", 4)
                        ->join('users', 'personal.user_id', '=', 'users.id')->where('personal.estatus', 1)
                        ->orderBy('users.nombre_apellido')->get();
                } else {
                    $supervisores = array();
                }
        endswitch;

        return view('claro.masivos.index')->with(["producto" => $this->producto, 'supervisores' => $supervisores]);
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
                ->whereBetween('claro_masivo_ventas.created_at', array($init, $end));

            if ($request->productos != "todos") {
                $sql->where('claro_masivo_ventas.producto_id', $request->productos);
            }

            $cargo = "";
            ///validamos si la persona tiene ficha de personal
            if (Auth::user()->ficha_personal == "Si") {
                $cargo = Auth::user()->personal->cargo->nombre_cargo;
            }
            /// se valida si viene valores de supervisor 
            if ($request->supervisor != "") {
                if ($cargo == 'Operador') {
                    $sql->where("claro_masivo_ventas.personal_id", "=", Auth::user()->personal->id);
                } else {
                    $sql->where("claro_masivo_ventas.supervisor_id", "=", $request->supervisor);
                }
            } else {
                ///si no, en base al cargo buscamos los datos
                switch ($cargo):
                    case "Operador":
                        $sql->where("claro_masivo_ventas.personal_id", "=", Auth::user()->personal->id);
                        break;
                    case 'Supervisor':
                        $sql->where("claro_masivo_ventas.supervisor_id", "=", Auth::user()->personal->id);
                        break;
                    case 'Coordinador':
                        $sql->where('claro_masivo_ventas.coordinador_id', '=', Auth::user()->personal->id);
                        break;
                endswitch;
            }

            if (Auth::user()->ficha_personal == "Si" || Auth::user()->hasPermission('claro.masivos.administrativo')) {
                $data = $sql->get();
            }
            foreach ($data as $result) {
                $historico = $result->relationHistorico()->where('estatus_id', $result->estatus_id)->first();
                $row["id"]              = $result->id;
                $row["creado"]          = date('d/m/Y', strtotime($result->created_at));
                $row["producto"]        = $result->relationProducto !== null ? $result->relationProducto->descripcion : " - ";
                $row["identificador"]   = $result->identificacion;
                $row["nombreapellido"]  = $result->nombre . " " . $result->apellido_1;
                $row["segmento"]    = $result->segmento;
                $row["plan"]        = $result->relationPlanes->descripcion;
                $row["agente"]      = $result->relationUser != null ? $result->relationUser->nombre_apellido : null;
                $row["supervisor"]  = $result->supervisor_id != null ? $result->relationSupervisor->RelationUser->nombre_apellido : "N/A";
                $row["estatus"]     = $result->relationEstatus->descripcion;

                $vent = 'onclick="DestroyVentas(' . $result->id . '); "';

                switch ($result->estatus_id) {
                    case 1: # registrada
                        $editarhtml = Auth::user()->HasPermission('claro.masivos.edit') ?
                            '<a href="' . route('claro.masivos.edit', $result->id) . '" target="_blank" class="btn btn-sm btn-primary"  data-toggle="tooltip" data-placement="top" title="Editar Datos"><i class="fas fa-edit"></i></a>'
                            : null;

                        $deleteHtml = Auth::user()->HasPermission('claro.masivos.destroy') ?
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
                            $editarhtml = Auth::user()->HasPermission('claro.masivos.edit') ?
                                '<a href="' . route('claro.masivos.edit', $result->id) . '" target="_blank" class="btn btn-sm btn-warning"  data-toggle="tooltip" data-placement="top" title="Editar Datos"><i class="fas fa-edit"></i></a>'
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

                $formulario = '<form method="POST" id="eliminar' . $result->id . '" action="' . route('claro.masivos.delete', $result->id) . '">
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

        $equip = EquiposModel::where('active', 1)->get();
        $parentezco = ParentescoModel::where('active', 1)->get();

        return view('claro.masivos.create')->with([
            "tipo_cliente" => $this->tipo_cliente,
            "equipos"   => $equip,
            "anticipo"  => $this->anticipo,
            "parentesco" => $parentezco,
            "tipo_venta" => $this->tipo_venta,
            "producto" => $this->producto,
        ]);
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        try {
            $venta = new VentasModel();
            $venta->producto_id     = $request->producto;
            $venta->id_contacto     = $request->idcontacto;
            $venta->tipo_venta      = $request->tipoventa;
            $venta->agencia         = $request->agencia;
            $venta->nombre          = ucfirst(strtolower($request->nombre));
            $venta->apellido_1      = ucfirst(strtolower($request->apellido1));
            $venta->apellido_2      = ucfirst(strtolower($request->apellido2));
            $venta->identificacion  = $request->identificacion;
            $venta->segmento        = $request->tipo_cliente;
            $venta->plan_id         = $request->tipo_plan;
            $venta->coordenadas     = $request->coordenadas;
            $venta->precio          = $request->precioplan;
            $venta->provincia       = ucfirst(strtolower($request->provincia));
            $venta->canton          = ucfirst(strtolower($request->canton));
            $venta->distrito        = ucfirst(strtolower($request->distrito));
            $venta->detalle_direccion = ucfirst(strtolower($request->direccion));
            $venta->telefono_a_llamar = $request->telefono;
            $venta->email             = strtolower($request->email);
            $venta->equipo_id       = $request->equipo;
            $venta->numero_portar   = $request->numero_portar;
            $venta->anticipo        = $request->anticipo;
            $venta->nombre_refencia_1       = ucfirst(strtolower($request->nombreref1));
            $venta->telefono_refencia_1     = $request->telefonoref1;
            $venta->parentesco_refencia_1   = $request->parentescoref1;
            $venta->nombre_refencia_2       = ucfirst(strtolower($request->nombreref2));
            $venta->telefono_refencia_2     = $request->telefonoref2;
            $venta->parentesco_refencia_2   = $request->parentescoref2;
            $venta->nombre_refencia_3       = ucfirst(strtolower($request->nombreref3));
            $venta->telefono_refencia_3     = $request->telefonoref3;
            $venta->parentesco_refencia_3   = $request->parentescoref3;
            $venta->observaciones           = ucfirst(strtolower($request->observacion));
            $venta->supervisor_id           = $user->personal !== null ? $user->personal->jefe_inmediato_id : null;
            $venta->coordinador_id          = $user->personal !== null ? $user->personal->jefe_inmediato_segundo_id : null;
            $venta->personal_id             = $user->personal !== null ? $user->personal->id : null;
            $venta->user_id                 = $user->id;
            $venta->estatus_id              = 1;
            $venta->save();

            $historico = new MavisoHistoricosModel();
            $historico->claro_masivo_ventas_id = $venta->id;
            $historico->estatus_id             = $venta->estatus_id;
            $historico->document_checks_partials = json_encode(["" => ""]);
            $historico->usuario                = $user->usuario;
            $historico->observaciones          = "Venta registrada exitosamente.";
            $historico->save();


            Session::flash('successVentas', 'La venta fue registrada exitosamente.');
            return redirect()->route('claro.masivos.create');
        } catch (\Throwable $e) {
            Session::flash('errorVentas', $e->getMessage());
            return redirect()->back();
        }
    }

    public function edit($id)
    {
        $venta = VentasModel::find($id);
        $equip = EquiposModel::where('active', 1)->get();
        $parentezco = ParentescoModel::where('active', 1)->get();
        $planes = PlanesModel::where('active', 1)->where('producto_id', $venta->producto_id)->get();
        $group =  PlanesModel::select('group')->where('producto_id', $venta->producto_id)->where('active', 1)->groupby('group')->get();

        return view('claro.masivos.edit')->with([
            "tipo_cliente"  => $this->tipo_cliente,
            "equipos"       => $equip,
            "anticipo"      => $this->anticipo,
            "parentesco"    => $parentezco,
            "planes"        => $planes,
            "venta"         => $venta,
            "group"         => $group,
            "producto"      => $this->producto,
            "tipo_venta" => $this->tipo_venta,
        ]);
    }

    public function update(Request $request, $id)
    {
        try {
            $venta = VentasModel::find($id);
            $venta->producto_id     = $request->producto;
            $venta->id_contacto     = $request->idcontacto;
            $venta->tipo_venta      = $request->tipoventa;
            $venta->agencia         = $request->agencia;
            $venta->nombre          = ucfirst(strtolower($request->nombre));
            $venta->apellido_1      = ucfirst(strtolower($request->apellido1));
            $venta->apellido_2      = ucfirst(strtolower($request->apellido2));
            $venta->identificacion  = $request->identificacion;
            $venta->segmento        = $request->tipo_cliente;
            $venta->plan_id         = $request->tipo_plan;
            $venta->coordenadas     = $request->coordenadas;
            $venta->precio          = $request->precioplan;
            $venta->provincia       = $request->provincia;
            $venta->canton          = $request->canton;
            $venta->distrito        = $request->distrito;
            $venta->detalle_direccion = $request->direccion;
            $venta->telefono_a_llamar = $request->telefono;
            $venta->email             = $request->email;
            $venta->equipo_id       = $request->equipo;
            $venta->numero_portar   = $request->numero_portar;
            $venta->anticipo        = $request->anticipo;
            $venta->nombre_refencia_1       = ucfirst(strtolower($request->nombreref1));
            $venta->telefono_refencia_1     = $request->telefonoref1;
            $venta->parentesco_refencia_1   = $request->parentescoref1;
            $venta->nombre_refencia_2       = ucfirst(strtolower($request->nombreref2));
            $venta->telefono_refencia_2     = $request->telefonoref2;
            $venta->parentesco_refencia_2   = $request->parentescoref2;
            $venta->nombre_refencia_3       = ucfirst(strtolower($request->nombreref3));
            $venta->telefono_refencia_3     = $request->telefonoref3;
            $venta->parentesco_refencia_3   = $request->parentescoref3;
            $venta->observaciones           = ucfirst(strtolower($request->observacion));
            #si la venta tiene estatus 3 rechazada y se edito, pasa a estatus 4 revisada, para que pueda ser auditada nuevamente.
            $estatus = $venta->estatus_id == 3 ? 4 : $venta->estatus_id;
            $venta->estatus_id              = $estatus;
            $venta->save();

            $historico = new MavisoHistoricosModel();
            $historico->claro_masivo_ventas_id = $id;
            $historico->estatus_id             = $estatus;
            $historico->document_checks_partials = json_encode(["" => ""]);
            $historico->usuario                = Auth::user()->usuario;
            $historico->observaciones          = "Correccion de datos de la Venta Rechazada, recuperable";
            $historico->save();

            Session::flash('success', 'La venta fue actualizada exitosamente.');
            return redirect()->route('claro.masivos.index');
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
                if (Auth::user()->hasPermission('claro.masivos.administrativo')) {
                    $supervisores = Personal::with(['relationUser'])->select('personal.id', 'users.nombre_apellido')
                        ->where("personal.campana_id", "=", 3)->where("personal.cargo_id", "=", 4)
                        ->join('users', 'personal.user_id', '=', 'users.id')->where('personal.estatus', 1)
                        ->orderBy('users.nombre_apellido')->get();
                } else {
                    $supervisores = array();
                }
        endswitch;

        return view('claro.masivos.auditoria.index')->with(["producto" => $this->producto, 'supervisores' => $supervisores, "estatussegumientos" => $SegumientoEstatus, "estatus" => $estatus]);
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
            )->whereBetween('claro_masivo_ventas.created_at', array($init, $end));

            if ($request->productos !== "todos") {
                $sql->where('claro_masivo_ventas.producto_id', $request->productos);
            }

            if ($request->estatus != "") {
                $sql->where('estatus_id', $request->estatus);
            }

            $cargo = "";
            ///validamos si la persona tiene ficha de personal
            if (Auth::user()->ficha_personal == "Si") {
                $cargo = Auth::user()->personal->cargo->nombre_cargo;
            }
            /// se valida si viene valores de supervisor 
            if ($request->supervisor !== "todos") {
                if ($cargo == 'Operador') {
                    $sql->where("claro_masivo_ventas.personal_id", "=", Auth::user()->personal->id);
                } else {
                    $sql->where("claro_masivo_ventas.supervisor_id", "=", $request->supervisor);
                }
            } else {
                ///si no, en base al cargo buscamos los datos
                switch ($cargo):
                    case "Operador":
                        $sql->where("claro_masivo_ventas.personal_id", "=", Auth::user()->personal->id);
                        break;
                    case 'Supervisor':
                        $sql->where("claro_masivo_ventas.supervisor_id", "=", Auth::user()->personal->id);
                        break;
                    case 'Coordinador':
                        $sql->where('claro_masivo_ventas.coordinador_id', '=', Auth::user()->personal->id);
                        break;
                endswitch;
            }

            if (Auth::user()->ficha_personal == "Si" || Auth::user()->hasPermission('claro.masivos.administrativo')) {
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
                $row["plan"]        = $result->relationPlanes->descripcion;
                $row["agente"]      = $result->relationUser->nombre_apellido;
                $row["supervisor"]  = $result->supervisor_id != null ? $result->relationSupervisor->RelationUser->nombre_apellido : "N/A";
                $row["auditado"]    = $historico !== null ? $historico->usuario : "N/A";
                $row["estatus"]     = $result->relationEstatus->descripcion;
                $Permissionedit      = Auth::user()->HasPermission('claro.masivos.auditoria.edit');
                $botonEditar        = '';
                $botonSegumientos   = '';
                $botonVer           = '';

                switch ($result->estatus_id) {
                    case 1: #recien registrada

                        $icon = '<i class="fas fa-edit"></i>';
                        $style = 'btn-primary';
                        $toggleText = "Auditar Venta";
                        $botonEditar = '<a href="' . route('claro.masivos.audit', $result->id) . '" target="_blank" class="btn btn-sm ' . $style . '" 
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
                                    $botonEditar = '<a href="' . route('claro.masivos.audit', $result->id) . '" target="_blank" class="btn btn-sm btn-primary" 
                        data-toggle="tooltip" data-placement="top" title="' . $toggleText . '">' . $icon . '</a>';
                                } else {
                                    $botonEditar = '';
                                }
                                break;
                            case false: #no existe registro de auditoria 
                                $toggleText = "Auditar Venta";
                                $icon = '<i class="fas fa-edit"></i>';
                                $style = 'btn-primary';
                                $botonEditar = '<a href="' . route('claro.masivos.audit', $result->id) . '" target="_blank" class="btn btn-sm btn-primary" 
                        data-toggle="tooltip" data-placement="top" title="' . $toggleText . '">' . $icon . '</a>';

                                break;
                        }
                        break;
                    case 13: #instalada - no se meustra nada
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
            $producto = in_array($request->producto, [1, 4]) ? [1] : [$request->producto];
            // dd($producto);
            $venta = VentasModel::find($request->venta);
            $recaudos = RecaudosModel::whereIn("producto_id", $producto)->where('active', 1)->get();


            $historico = MavisoHistoricosModel::where("claro_masivo_ventas_id", $request->venta)
                ->orderBy('id', 'desc')->limit(1)->first();
            $checks = json_decode($historico->document_checks_partials);

            $historico = MavisoHistoricosModel::where("claro_masivo_ventas_id", $request->venta)
                ->orderBy('id', 'desc')->limit(3)->get();


            foreach ($historico as $key => $value) {
            }
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

            $historico = new MavisoHistoricosModel();
            $historico->claro_masivo_ventas_id = $request->venta;
            $historico->usuario                = Auth::user()->usuario;
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
        $venta = VentasModel::find($id);
        $equip = EquiposModel::where('active', 1)->get();
        $parentezco = ParentescoModel::where('active', 1)->get();
        $planes = PlanesModel::where('active', 1)->where('producto_id', $venta->producto_id)->get();
        $group =  PlanesModel::select('group')->where('producto_id', $venta->producto_id)->where('active', 1)->groupby('group')->get();

        return view('claro.masivos.auditoria.edit')->with([
            "tipo_cliente"  => $this->tipo_cliente,
            "equipos"       => $equip,
            "anticipo"      => $this->anticipo,
            "parentesco"    => $parentezco,
            "planes"        => $planes,
            "group"         => $group,
            "venta"         => $venta,
            "producto"      => $this->producto,
            "tipo_venta" => $this->tipo_venta,
        ]);
    }

    public function auditoriaStore(request $request, $id)
    {
        try {
            $venta = VentasModel::find($id);
            $venta->producto_id     = $request->producto;
            $venta->tipo_venta      = $request->tipoventa;
            $venta->id_contacto     = $request->idcontacto;
            $venta->agencia         = $request->agencia;
            $venta->nombre          = ucfirst(strtolower($request->nombre));
            $venta->apellido_1      = ucfirst(strtolower($request->apellido1));
            $venta->apellido_2      = ucfirst(strtolower($request->apellido2));
            $venta->identificacion  = $request->identificacion;
            $venta->segmento        = $request->tipo_cliente;
            $venta->plan_id         = $request->tipo_plan;
            $venta->coordenadas     = $request->coordenadas;
            $venta->precio          = $request->precioplan;
            $venta->provincia       = $request->provincia;
            $venta->canton          = $request->canton;
            $venta->distrito        = $request->distrito;
            $venta->detalle_direccion = $request->direccion;
            $venta->telefono_a_llamar = $request->telefono;
            $venta->email             = $request->email;
            $venta->equipo_id       = $request->equipo;
            $venta->numero_portar   = $request->numero_portar;
            $venta->anticipo        = $request->anticipo;
            $venta->nombre_refencia_1       = ucfirst(strtolower($request->nombreref1));
            $venta->telefono_refencia_1     = $request->telefonoref1;
            $venta->parentesco_refencia_1   = $request->parentescoref1;
            $venta->nombre_refencia_2       = ucfirst(strtolower($request->nombreref2));
            $venta->telefono_refencia_2     = $request->telefonoref2;
            $venta->parentesco_refencia_2   = $request->parentescoref2;
            $venta->nombre_refencia_3       = ucfirst(strtolower($request->nombreref3));
            $venta->telefono_refencia_3     = $request->telefonoref3;
            $venta->parentesco_refencia_3   = $request->parentescoref3;
            $venta->observaciones           = ucfirst(strtolower($request->observacion));
            $venta->estatus_id              = $request->auditoria == "aprobada" ? 2 : 3;
            $venta->enviado                 = $request->auditoria === "aprobada" ? $request->auditoriatext : null;
            $venta->recuperable             = $request->recuperable;
            $venta->save();

            $valorSinComillas = str_replace(['"', "'"], '', $request->auditoriatext);

            $historico = new MavisoHistoricosModel();
            $historico->claro_masivo_ventas_id = $id;
            $historico->estatus_id             = $request->auditoria === "aprobada" ? 2 : 3;
            $historico->document_checks_partials = json_encode(["" => ""]);
            $historico->usuario                = Auth::user()->nombre_apellido;
            $historico->observaciones          = $request->auditoria === "aprobada" ? "Venta Aprobada" : $valorSinComillas;
            $historico->save();

            try {

                //$this->SendDataSheet($venta, $enviar = $request->auditoria === "aprobada" ? "aprobada" : "rechazada");

                Session::flash('success', 'Auditoria procesada exitosamente.');
                return redirect()->route('claro.masivos.auditoriaIndex');
            } catch (\Google\Service\Exception $gr) {
                Session::flash('error', 'Ocurrio un problema al Enviar Al Sheet. Comuniquese con el Administrador.');
                return back();
            }
        } catch (\Throwable $th) {
            Session::flash('error', "Error general al Procesar " . $th->getMessage());
            return back();
        }
    }

    private function SendDataSheet($venta, $enviar)
    {
        $array = [
            $venta->id_contacto,
            carbon::create($venta->created_at)->format('d/m/Y H:i:s'),
            $venta->tipo_venta == null ? "" : $venta->tipo_venta,
            $venta->agencia,
            $venta->nombre,
            $venta->apellido_1,
            $venta->apellido_2,
            $venta->identificacion,
            $venta->segmento,
            in_array($venta->producto_id, [1, 2]) ? $venta->relationPlanes->descripcion : "", #plan Hogar - Gpon
            in_array($venta->producto_id, [3]) ? $venta->relationPlanes->descripcion : "", #plan POSPAGO
            in_array($venta->producto_id, [4]) ? $venta->relationPlanes->descripcion : "", #plan DTH
            $venta->precio,
            $venta->coordenadas,
            $venta->provincia,
            $venta->canton,
            $venta->distrito,
            $venta->detalle_direccion,
            $venta->telefono_a_llamar,
            $venta->email,
            $venta->equipo_id !== null ? EquiposModel::find($venta->equipo_id)->descripcion : "", #equipo
            $venta->numero_portar,
            $venta->anticipo,
            $venta->nombre_refencia_1,
            $venta->telefono_refencia_1,
            ParentescoModel::find($venta->parentesco_refencia_1)->descripcion,
            $venta->nombre_refencia_2       !== null ?  $venta->nombre_refencia_2 : "",
            $venta->telefono_refencia_2     !== null ? $venta->telefono_refencia_2 : "",
            $venta->parentesco_refencia_2   !== null ? ParentescoModel::find($venta->parentesco_refencia_2)->descripcion : "",
            $venta->nombre_refencia_3       !== null ? $venta->nombre_refencia_3 : "",
            $venta->telefono_refencia_3     !== null ? $venta->telefono_refencia_3 : "",
            $venta->parentesco_refencia_3   !== null ? ParentescoModel::find($venta->parentesco_refencia_3)->descripcion : "",
            $venta->observaciones,
            ProductosModel::find($venta->producto_id)->descripcion,
            $venta->relationCoordinador != null ? $venta->relationCoordinador->RelationUser->nombre_apellido : "No Disponible",
            $venta->relationSupervisor  != null ? $venta->relationSupervisor->RelationUser->nombre_apellido : "No Disponible",
            $venta->relationPersonal    != null ? $venta->relationPersonal->numero_empleado : "No Disponible",
            $venta->relationUser->nombre_apellido,
            $venta->relationEstatus->descripcion,
        ];

        $dataMatrix = [array_values($array)];
        /*
        try {

            if ($enviar == "aprobada") {
                $googleApi = new GoogleApi(
                    "Discovery",
                    ENV('CLARO_MASIVOS_CREDENTIALS', "directagroupingsoftware-51fef28451b7.json"),
                    ENV('CLARO_MASIVOS_SHEET', "1rLM202rK0t1ZY11fihY_-EYbMlUVPiGuyYAiFr_34d4"),
                    ENV('CLARO_MASIVOS_NOMBRE', "Ventas Movil")
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
                if (Auth::user()->hasPermission('claro.masivos.administrativo')) {
                    $supervisores = Personal::with(['relationUser'])->select('personal.id', 'users.nombre_apellido')
                        ->where("personal.campana_id", "=", 3)->where("personal.cargo_id", "=", 4)
                        ->join('users', 'personal.user_id', '=', 'users.id')->where('personal.estatus', 1)
                        ->orderBy('users.nombre_apellido')->get();
                } else {
                    $supervisores = array();
                }
        endswitch;

        return view("claro.masivos.reportes.index")->with(["producto" => $this->producto, "reporte" => $this->reporte, 'supervisores' => $supervisores]);
    }

    public function ReportesData(Request $request)
    {
        $date       = explode("-", $request->fecha);
        $end        = trim($date[1]);
        $date       = Carbon::createFromFormat('d/m/Y', trim($date[0]))->startOfDay()->format('Y-m-d H:i:s');
        $dateEnd    = Carbon::createFromFormat('d/m/Y', $end)->endOfDay()->format('Y-m-d H:i:s');

        $ventas     = VentasModel::whereBetween('created_at', [$date, $dateEnd]);

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
            $sql = $ventas->get();
        }

        $estatus    = EstatusModel::all()->pluck('descripcion', 'id')->toArray();

        switch ($request->reporte) {
            case 0:
                $nombre = "Claro Masivos Tipificacion General";
                $data = $this->GetAllData($sql, $estatus);
                break;
            case 1:
                $nombre = "Claro Masivos Tipificacion Ultimo Estatus";
                $data = $this->getUltimateEstatus($sql, $estatus);
                break;
        }

        usort($data, function ($a, $b) {
            $fechaA = Carbon::createFromFormat('d/m/Y H:i:s', $a['fecha']);
            $fechaB = Carbon::createFromFormat('d/m/Y H:i:s', $b['fecha']);
            return $fechaA->getTimestamp() <=> $fechaB->getTimestamp();
        });

        return Excel::download(new MasivosExport($data), $nombre . ' ' . $date . ' - ' . $dateEnd . '.xlsx');
    }

    private function GetAllData($ventas, $estatus)
    {
        $array      = array();
        $data       = array();

        foreach ($ventas as $venta) {
            /*   $array["id"] = $venta->id;
            $array["id_contacto"] = $venta->id_contacto;
            $array["fecha"] = Carbon::create($venta->created_at)->format('d/m/Y H:i:s');
            $array["tipo_venta"] = $venta->tipo_venta == null ? "" : $venta->tipo_venta;
            $array["agencia"] = $venta->agencia;
            $array["nombre"] = $venta->nombre;
            $array["apellido_1"] = $venta->apellido_1;
            $array["apellido_2"] = $venta->apellido_2;
            $array["identificacion"] = $venta->identificacion;
            $array["segmento"] = $venta->segmento;
            $array["Plan_GPON"] = in_array($venta->producto_id, [1, 2]) ? $venta->relationPlanes->descripcion : ""; #plan Hogar - Gpon
            $array["Plan_Pospago"] = in_array($venta->producto_id, [3]) ? $venta->relationPlanes->descripcion : ""; #plan POSPAGO
            $array["Plan_DTH"] = in_array($venta->producto_id, [4]) ? $venta->relationPlanes->descripcion : ""; #plan DTH
            $array["precio"] = $venta->precio;
            $array["coordenadas"] = $venta->coordenadas;
            $array["provincia"] = $venta->provincia;
            $array["canton"] = $venta->canton;
            $array["distrito"] = $venta->distrito;
            $array["detalle_direccion"] = $venta->detalle_direccion;
            $array["telefono_a_llamar"] = $venta->telefono_a_llamar;
            $array["email"] = $venta->email;
            $array["equipo"] = $venta->equipo_id !== null ? EquiposModel::find($venta->equipo_id)->descripcion : ""; #equipo
            $array["numero_portar"] = $venta->numero_portar;
            $array["anticipo"] = $venta->anticipo;
            $array["nombre_refencia_1"] = $venta->nombre_refencia_1;
            $array["telefono_refencia_1"] = $venta->telefono_refencia_1;
            $array["parentesco_refencia_1"] = ParentescoModel::find($venta->parentesco_refencia_1)->descripcion;
            $array["nombre_refencia_2"] = $venta->nombre_refencia_2 !== null ? $venta->nombre_refencia_2 : "";
            $array["telefono_refencia_2"] = $venta->telefono_refencia_2 !== null ? $venta->telefono_refencia_2 : "";
            $array["parentesco_refencia_2"] = $venta->parentesco_refencia_2 !== null ? ParentescoModel::find($venta->parentesco_refencia_2)->descripcion : "";
            $array["nombre_refencia_3"] = $venta->nombre_refencia_3 !== null ? $venta->nombre_refencia_3 : "";
            $array["telefono_refencia_3"] = $venta->telefono_refencia_3 !== null ? $venta->telefono_refencia_3 : "";
            $array["parentesco_refencia_3"] = $venta->parentesco_refencia_3 !== null ? ParentescoModel::find($venta->parentesco_refencia_3)->descripcion : "";
            $array["observaciones"] = $venta->observaciones;
            $array["producto"] = ProductosModel::find($venta->producto_id)->descripcion;
            $array["coordinador"] = $venta->relationCoordinador != null ? $venta->relationCoordinador->RelationUser->nombre_apellido : "No Disponible";
            $array["supervisor"] = $venta->relationSupervisor != null ? $venta->relationSupervisor->RelationUser->nombre_apellido : "No Disponible";
            $array["numero_empleado"] = $venta->relationPersonal != null ? $venta->relationPersonal->numero_empleado : "No Disponible";
            $array["nombre_apellido"] = $venta->relationUser->nombre_apellido;
            $array["estatus"] = $estatus[$venta->estatus_id];
            $data[] = $array;*/

            if (!$venta->relationHistorico->isEmpty()) {
                foreach ($venta->relationHistorico as $historico) {
                    $array2["idhistorico"] = $historico->id;
                    $array2["id_contacto"] = $venta->id_contacto;
                    $array2["fecha"] = Carbon::create($historico->created_at)->format('d/m/Y H:i:s');
                    $array2["tipo_venta"] = $venta->tipo_venta == null ? "" : $venta->tipo_venta;
                    $array2["agencia"] = $venta->agencia;
                    $array2["nombre"] = $venta->nombre;
                    $array2["apellido_1"] = $venta->apellido_1;
                    $array2["apellido_2"] = $venta->apellido_2;
                    $array2["identificacion"] = $venta->identificacion;
                    $array2["segmento"] = $venta->segmento;
                    $array2["Plan_GPON"] = in_array($venta->producto_id, [1, 2]) ? $venta->relationPlanes->descripcion : ""; #plan Hogar - Gpon
                    $array2["Plan_Pospago"] = in_array($venta->producto_id, [3]) ? $venta->relationPlanes->descripcion : ""; #plan POSPAGO
                    $array2["Plan_DTH"] = in_array($venta->producto_id, [4]) ? $venta->relationPlanes->descripcion : ""; #plan DTH
                    $array2["precio"] = $venta->precio;
                    $array2["coordenadas"] = $venta->coordenadas;
                    $array2["provincia"] = $venta->provincia;
                    $array2["canton"] = $venta->canton;
                    $array2["distrito"] = $venta->distrito;
                    $array2["detalle_direccion"] = $venta->detalle_direccion;
                    $array2["telefono_a_llamar"] = $venta->telefono_a_llamar;
                    $array2["email"] = $venta->email;
                    $array2["equipo"] = $venta->equipo_id !== null ? EquiposModel::find($venta->equipo_id)->descripcion : ""; #equipo
                    $array2["numero_portar"] = $venta->numero_portar;
                    $array2["anticipo"] = $venta->anticipo;
                    $array2["nombre_refencia_1"] = $venta->nombre_refencia_1;
                    $array2["telefono_refencia_1"] = $venta->telefono_refencia_1;
                    $array2["parentesco_refencia_1"] = ParentescoModel::find($venta->parentesco_refencia_1)->descripcion;
                    $array2["nombre_refencia_2"] = $venta->nombre_refencia_2 !== null ? $venta->nombre_refencia_2 : "";
                    $array2["telefono_refencia_2"] = $venta->telefono_refencia_2 !== null ? $venta->telefono_refencia_2 : "";
                    $array2["parentesco_refencia_2"] = $venta->parentesco_refencia_2 !== null ? ParentescoModel::find($venta->parentesco_refencia_2)->descripcion : "";
                    $array2["nombre_refencia_3"] = $venta->nombre_refencia_3 !== null ? $venta->nombre_refencia_3 : "";
                    $array2["telefono_refencia_3"] = $venta->telefono_refencia_3 !== null ? $venta->telefono_refencia_3 : "";
                    $array2["parentesco_refencia_3"] = $venta->parentesco_refencia_3 !== null ? ParentescoModel::find($venta->parentesco_refencia_3)->descripcion : "";
                    $array2["producto"] = ProductosModel::find($venta->producto_id)->descripcion;
                    $array2["coordinador"] = $venta->relationCoordinador != null ? $venta->relationCoordinador->RelationUser->nombre_apellido : "No Disponible";
                    $array2["supervisor"] = $venta->relationSupervisor != null ? $venta->relationSupervisor->RelationUser->nombre_apellido : "No Disponible";
                    $array2["numero_empleado"] = $venta->relationPersonal != null ? $venta->relationPersonal->numero_empleado : "No Disponible";
                    $array2["nombre_apellido"] = $venta->relationUser->nombre_apellido;
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
            $array["fecha"] = Carbon::create($venta->created_at)->format('d/m/Y H:i:s');
            $array["tipo_venta"] = $venta->tipo_venta == null ? "" : $venta->tipo_venta;
            $array["agencia"] = $venta->agencia;
            $array["nombre"] = $venta->nombre;
            $array["apellido_1"] = $venta->apellido_1;
            $array["apellido_2"] = $venta->apellido_2;
            $array["identificacion"] = $venta->identificacion;
            $array["segmento"] = $venta->segmento;
            $array["Plan_GPON"] = in_array($venta->producto_id, [1, 2]) ? $venta->relationPlanes->descripcion : ""; #plan Hogar - Gpon
            $array["Plan_Pospago"] = in_array($venta->producto_id, [3]) ? $venta->relationPlanes->descripcion : ""; #plan POSPAGO
            $array["Plan_DTH"] = in_array($venta->producto_id, [4]) ? $venta->relationPlanes->descripcion : ""; #plan DTH
            $array["precio"] = $venta->precio;
            $array["coordenadas"] = $venta->coordenadas;
            $array["provincia"] = $venta->provincia;
            $array["canton"] = $venta->canton;
            $array["distrito"] = $venta->distrito;
            $array["detalle_direccion"] = $venta->detalle_direccion;
            $array["telefono_a_llamar"] = $venta->telefono_a_llamar;
            $array["email"] = $venta->email;
            $array["equipo"] = $venta->equipo_id !== null ? EquiposModel::find($venta->equipo_id)->descripcion : ""; #equipo
            $array["numero_portar"] = $venta->numero_portar;
            $array["anticipo"] = $venta->anticipo;
            $array["nombre_refencia_1"] = $venta->nombre_refencia_1;
            $array["telefono_refencia_1"] = $venta->telefono_refencia_1;
            $array["parentesco_refencia_1"] = ParentescoModel::find($venta->parentesco_refencia_1)->descripcion;
            $array["nombre_refencia_2"] = $venta->nombre_refencia_2 !== null ? $venta->nombre_refencia_2 : "";
            $array["telefono_refencia_2"] = $venta->telefono_refencia_2 !== null ? $venta->telefono_refencia_2 : "";
            $array["parentesco_refencia_2"] = $venta->parentesco_refencia_2 !== null ? ParentescoModel::find($venta->parentesco_refencia_2)->descripcion : "";
            $array["nombre_refencia_3"] = $venta->nombre_refencia_3 !== null ? $venta->nombre_refencia_3 : "";
            $array["telefono_refencia_3"] = $venta->telefono_refencia_3 !== null ? $venta->telefono_refencia_3 : "";
            $array["parentesco_refencia_3"] = $venta->parentesco_refencia_3 !== null ? ParentescoModel::find($venta->parentesco_refencia_3)->descripcion : "";
            $array["producto"] = ProductosModel::find($venta->producto_id)->descripcion;
            $array["coordinador"] = $venta->relationCoordinador != null ? $venta->relationCoordinador->RelationUser->nombre_apellido : "No Disponible";
            $array["supervisor"] = $venta->relationSupervisor != null ? $venta->relationSupervisor->RelationUser->nombre_apellido : "No Disponible";
            $array["numero_empleado"]   = $venta->relationPersonal != null ? $venta->relationPersonal->numero_empleado : "No Disponible";
            $array["nombre_apellido"]   = $venta->relationUser->nombre_apellido;
            $array["estatus"]           = $estatus[$venta->estatus_id];

            if (MavisoHistoricosModel::where('claro_masivo_ventas_id', $venta->id)->orderBy('id', 'DESC')->exists()) {
                $historico =  MavisoHistoricosModel::where('claro_masivo_ventas_id', $venta->id)->orderBy('id', 'DESC')->limit(1)->first();
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
