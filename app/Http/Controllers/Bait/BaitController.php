<?php

namespace App\Http\Controllers\Bait;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\bait\BaitEstados;
use App\Models\bait\BaitMunicipios;
use App\Models\bait\BaitTiendas;
use App\Models\bait\BaitVentas;
use App\Models\bait\BaitHistoricos;
use App\Models\bait\BaitRespondio;

use App\Models\Personal;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

class BaitController extends Controller
{
    private $sexo, $fvc, $gestion, $modalidades, $campania;

    public function __construct($campania = 5)
    {
        $this->middleware('CheckBaitPortabilidad')->only(['store']);

        $this->campania = $campania;

        $this->sexo = [
            'M' => 'Masculino',
            'F' => 'Femenino',
        ];

        $this->fvc          = [24 => "24 Horas", 48 => "48 Horas"];
        #$this->modalidades  = ["CPP" => "CPP", "MPP" => "MPP", "ESIM" => "ESIM", "SIM" => "SIM"];
        $this->modalidades  = ["CPP" => "CPP"];
        $this->gestion      = ["1" => "Team 1", "2" => "Team 2", "3" => "Team 3", "4" => "Team Nocturno", "5" => "Team Agent IA"];
    }
    //Validador de Numero portabilidad jvalidate
    public function BaitNumeroPortarCheck(Request $request)
    {
        if ($request->ajax()) {
            $numero_portar = trim($request->numero_portabilidad);

            if (!preg_match("/^[0-9]{10}$/", $numero_portar)) {
                return response()->json(false);
            }

            $query = BaitVentas::where('numero_portar', $numero_portar);

            if ($request->filled('idventa') && is_numeric($request->idventa)) {
                $query->where('id', '!=', $request->idventa);
            }

            $venta = $query->orderBy('created_at', 'desc')->first();

            if ($venta != null) {
                if ($venta->estatus_id == 6) {
                    return response()->json(true);
                }

                $mesesTranscurridos = Carbon::parse($venta->created_at)->diffInMonths(Carbon::now());
                if ($mesesTranscurridos < 3) {
                    return response()->json(false);
                }
            }

            return response()->json(true);
        }
    }

    public function index()
    {
        $cargo = "";

        $user = ENV('DEV_USER_CHANGE', false) ?
            \Auth::loginUsingId(ENV('OTHER_USER_ID', false), true) :
            \Auth::user();

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
                $supervisores = Personal::with(['RelationUser'])->select('personal.id', 'users.nombre_apellido')->where("personal.campana_id", "=",   $this->campania)
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
                if (Auth::user()->hasPermission('bait.administrativo')) {
                    $supervisores = Personal::with(['relationUser'])->select('personal.id', 'users.nombre_apellido')
                        ->where("personal.campana_id", "=",  $this->campania)->where("personal.cargo_id", "=", 4)
                        ->join('users', 'personal.user_id', '=', 'users.id')->where('personal.estatus', 1)
                        ->orderBy('users.nombre_apellido')->get();
                } else {
                    $supervisores = array();
                }
        endswitch;

        return view('bait.index')->with([
            "supervisores" => $supervisores
        ]);
    }

    public function search(Request $request)
    {
        $liberar = false;
        $fecha = explode("-", $request->fecha);
        $data = array();
        $arreglo = array();
        $init = carbon::createFromFormat('d/m/Y', trim($fecha[0]));
        $end = carbon::createFromFormat('d/m/Y', trim($fecha[1]));

        $sql = BaitVentas::whereBetween('bait_ventas.created_at', array($init->copy()->startOfDay(), $end->copy()->endOfDay()));
        $cargo = "";
        ///validamos si la persona tiene ficha de personal
        if (Auth::user()->ficha_personal == "Si") {
            $cargo = Auth::user()->personal->cargo->nombre_cargo;
        }
        /// se valida si viene valores de supervisor 
        if ($request->supervisor != "todos") {
            if ($cargo == 'Operador') {
                $sql->where("bait_ventas.personal_id", "=", Auth::user()->personal->id);
            } else {
                $liberar = true;
                $sql->where("bait_ventas.supervisor_id", "=", $request->supervisor);
            }
        } else {
            ///si no, en base al cargo buscamos los datos
            switch ($cargo):
                case "Operador":
                    $sql->where("bait_ventas.personal_id", "=", Auth::user()->personal->id);
                    break;
                case 'Supervisor':
                    $sql->where("bait_ventas.supervisor_id", "=", Auth::user()->personal->id);
                    $liberar = true;
                    break;
                case 'Coordinador':
                    $sql->where('bait_ventas.coordinador_id', '=', Auth::user()->personal->id);
                    $liberar = true;
                    break;
            endswitch;
        }

        if (Auth::user()->ficha_personal == "Si" || Auth::user()->hasPermission('bait.administrativo')) {
            $data = $sql->orderBy('fvc', 'DESC')->get();
            $liberar = true;
        }

        if ($request->numero_portar != "" && $init->diffInDays($end) == 0 && $request->supervisor == "todos") {
            $sql2 = BaitVentas::where('numero_portar', $request->numero_portar);
            switch ($cargo):
                case "Operador":
                    $sql2->where("bait_ventas.personal_id", "=", Auth::user()->personal->id);
                    $liberar = false;
                    break;
                case 'Supervisor':
                    $sql2->where("bait_ventas.supervisor_id", "=", Auth::user()->personal->id);
                    $liberar = true;
                    break;
                case 'Coordinador':
                    $sql2->where('bait_ventas.coordinador_id', '=', Auth::user()->personal->id);
                    $liberar = true;
                    break;
            endswitch;

            if (Auth::user()->ficha_personal == "Si" || Auth::user()->hasPermission('bait.administrativo')) {
                $data = $sql2->orderBy('fvc', 'ASC')->get();
                $liberar = true;
            }
        }
        $editarhtml = "";
        $deleteHtml = "";

        foreach ($data as $result) {

            $respondio = BaitRespondio::where('idcontacto', $result->idcontacto)->orderby('id', 'desc')->first();
            $histotoque = $result->RelationHistorico();
            $row["id"]              = $result->id;
            $row["creado"]          = date('d/m/Y', strtotime($result->created_at));
            $row["hora"]            = date('h:i A', strtotime($result->created_at));
            $row["idcontacto"]      = $result->idcontacto;
            $row["fvc"]             = $result->fvc == 24 ? '<span class="badge badge-danger">' . $result->fvc . ' Horas</span>' : '<span class="badge badge-warning">' . $result->fvc . ' Horas</span>';
            $row["identificador"]   = $result->numero_portar;
            $row["intelix"]   = $result->estatus_intelix;
            $row["nombreapellido"]  = $result->nombre_apellido;
            $row["agente"]          = $result->RelationUser != null ? $result->RelationUser->nombre_apellido : "N/D";
            $row["supervisor"]      = $result->supervisor_id != null ? $result->relationSupervisor->RelationUser->nombre_apellido : "N/D";
            $row["estatus"]         = $result->autorizar == 1 && !in_array($result->estatus_id, [3, 4, 5, 1]) ? '<span class="badge badge-info"><i class="fas fa-lock"></i></span>' : '<span class="badge badge-success">' . $result->relationEstatus->descripcion . '</span>';

            $row["ciclo_vida"]      = $respondio == null ? "<span class='badge badge-danger'>N/D</span>" : $respondio->ciclo_de_vida;
            $vent = 'onclick="DestroyVentas(' . $result->id . '); "';

            switch ($result->estatus_id) {
                case 1:
                    $editarhtml = Auth::user()->HasPermission('bait.edit') ?
                        '<a href="' . route('bait.edit', $result->id) . '" target="_blank" class="btn btn-sm btn-primary"  data-toggle="tooltip" data-placement="top" title="Editar Datos"><i class="fas fa-edit"></i></a>'
                        : null;

                    $deleteHtml = Auth::user()->HasPermission('bait.destroy') ?
                        '<button type="button"  ' . $vent . ' class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="top" title="Remover" ><i class="fa fa-trash"</i></button>'
                        : null;
                    break;
                case 3: #Devuelta
                case 5: #reasignada
                    $row["estatus"] = '<span class="badge badge-warning">' . $result->relationEstatus->descripcion . '</span>';

                    $histotoque         = $histotoque->where('estatus_id', 3)->orderby('id', 'desc')->first();
                    $textoParaCopiar    = $histotoque != null ? 'Gestionado por: ' . $histotoque->usuario . "\n Observaciones: " . $histotoque->observaciones : "N/D";

                    $icon = '<i class="fas fa-exclamation-triangle"></i>';

                    if ($liberar && $result->autorizar == null || Auth::user()->hasPermission('bait.administrativo')) {

                        $editarhtml = Auth::user()->HasPermission('bait.edit') ?
                            '<a href="' . route('bait.edit', $result->id) . '" target="_blank" class="btn btn-sm btn-primary"  data-toggle="tooltip" data-placement="top" title="Editar Datos"><i class="fas fa-edit"></i></a>'
                            : null;
                    } else {
                        $editarhtml = "";
                    }
                    $deleteHtml = '<button type="button" class=" btn btn-sm btn-danger"  data-text="' . $textoParaCopiar . '"  id="button' . $result->id . '" onclick="CopyText(' . $result->id . ');" data-estatus="' . $result->estatus_id . '" data-toggle="tooltip" data-placement="top" title="Rechazada" > 
                        ' . $icon . '</button>';
                    break;
                case 6: #rechazo definitivo
                    $row["estatus"] = '<span class="badge badge-warning">' . $result->relationEstatus->descripcion . '</span>';
                    $histotoque         = $histotoque->orderby('id', 'desc')->first();
                    $textoParaCopiar = $histotoque != null ? 'Gestionado por: ' . $histotoque->usuario . "\n Observaciones: " . $histotoque->observaciones : "N/D";
                    $icon = '<i class="fas fa-exclamation-circle"></i>';
                    $editarhtml = "";
                    $deleteHtml = '<button type="button" class=" btn btn-sm btn-danger"  data-text="' . $textoParaCopiar . '"  id="button' . $result->id . '" onclick="CopyText(' . $result->id . ');" data-estatus="' . $result->estatus_id . '" data-toggle="tooltip" data-placement="top" title="Rechazo Definitivo" > 
                        ' . $icon . '</button>';
                    break;
                default:
                    $editarhtml = "";
                    $deleteHtml = "";
                    break;
            }
            $formulario = '<form method="POST" id="eliminar' . $result->id . '" action="' . route('bait.delete', $result->id) . '">
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
        $estados = BaitEstados::where('active', 1)->orderBy('estado', 'asc')->get();
        unset($this->gestion[5]);

        return view('bait.create')->with([
            "sexo" => $this->sexo,
            "estados" => $estados,
            "fvc" => $this->fvc,
            "modalidades" => $this->modalidades,
            "gestion" => $this->gestion
        ]);
    }

    public function GetMunicipio(Request $request)
    {
        $estado = BaitEstados::find($request->estado);
        $municipios = $estado->RelationsMunicipios;

        return response()->json($municipios);
    }

    public function GetTiendas(Request $request)
    {
        $municipio = BaitMunicipios::find($request->municipio);
        $tiendas = $municipio->RelationsTiendas;

        return response()->json($tiendas);
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        try {
            $venta = new BaitVentas();
            $venta->idcontacto              = $request->idcontacto;
            $venta->numero_portar           = $request->numero_portabilidad;
            $venta->nombre_apellido         = $request->nombre_apellido;
            $venta->fecha_nacimiento        = Carbon::createFromFormat('d/m/Y', $request->fecha_nacimiento)->format('Y-m-d');
            $venta->genero                  = $request->genero;
            $venta->imei                    = $request->imei;
            $venta->nip                     = (string) $request->codigo_nip;
            $venta->vigencia_nip            = Carbon::createFromFormat('d/m/Y', $request->fecha_vigencia)->format('Y-m-d');
            $venta->email                   = $request->correo_electronico;
            $venta->telefono_principal      = $request->numero_portabilidad;
            $venta->telefono_contacto       = $request->telefono_contacto;
            $venta->fvc                     = $request->fvc;
            $venta->modalidad               = $request->modalidad;
            $venta->fecha_cita              = Carbon::createFromFormat('d/m/Y h:i A', $request->fecha_cita . ' ' . $request->hora_cita)->format('Y-m-d H:i:s');
            $venta->observaciones           = $request->observaciones;
            $venta->grupo_gestion           = $request->gestion;
            $venta->autorizar = null;
            if (BaitRespondio::where('idcontacto', $request->idcontacto)->latest()->limit(1)->exists()) {
                $venta->ciclo_vida = BaitRespondio::where('idcontacto', $request->idcontacto)->orderby('created_at', 'DESC')->limit(1)->first()->ciclo_de_vida;
            } else {
                $venta->ciclo_vida = null;
            }

            if ($user->personal !== null) {
                if ($user->personal->cargo_id == 4) {
                    $supervisor     = $user->personal->id;
                } else {
                    $supervisor     = $user->personal->jefe_inmediato_id;
                }
                $coordinador    = $user->personal->jefe_inmediato_segundo_id;
                $idPersonal     = $user->personal->id;
            } else {
                $supervisor = null;
                $coordinador = null;
                $idPersonal = null;
            }

            $venta->tienda_id               = $request->tienda;
            $venta->supervisor_id           = $supervisor;
            $venta->coordinador_id          = $coordinador;
            $venta->personal_id             = $idPersonal;
            $venta->user_id                 = $user->id;
            $venta->estatus_id              = 1;
            $venta->save();

            $historico = new BaitHistoricos();
            $historico->bait_ventas_id      = $venta->id;
            $historico->estatus_id          = 1;
            $historico->usuario             = $user->nombre_apellido;
            $historico->observaciones       = $request->observaciones !== null ? $request->observaciones : "Venta registrada";
            $historico->save();

            return redirect()->route('bait.create')->with('successVentas', 'Venta registrada exitosamente');
        } catch (\Exception $e) {
            return back()->with('errorVentas', 'Error al registrar la venta' . $e->getMessage());
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
        $user = Auth::user();
        $cargo = "";
        if ($user->ficha_personal == "Si")
            $cargo = $user->personal->cargo->nombre_cargo;
        switch ($cargo):
            case "Operador":
                $operadores = array();
                $autorizado = false;
                break;
            case 'Supervisor':
                $operadores = Personal::with(['RelationUser'])->select('personal.id', 'users.nombre_apellido', 'usuario')
                    ->where("personal.campana_id", "=", $this->campania)
                    ->where("personal.cargo_id", "=", 7)->where('personal.jefe_inmediato_id', '=', Auth::user()->personal->id)
                    ->join('users', 'personal.user_id', '=', 'users.id')
                    ->orderBy('users.nombre_apellido')->get();
                $autorizado = true;
                break;
            case 'Coordinador':
                $operadores = Personal::with(['RelationUser'])->select('personal.id', 'users.nombre_apellido', 'usuario')
                    ->where("personal.campana_id", "=", $this->campania)
                    ->where("personal.cargo_id", "=", 7)
                    ->where('personal.jefe_inmediato_segundo_id', '=', Auth::user()->personal->id)
                    ->join('users', 'personal.user_id', '=', 'users.id')->where('personal.estatus', 1)
                    ->orderBy('users.nombre_apellido')->get();
                $autorizado = true;
                break;
            default:
                if (Auth::user()->hasPermission('bait.administrativo')) {
                    $operadores = Personal::with(['relationUser'])->select('personal.id', 'users.nombre_apellido', 'usuario')
                        ->where("personal.campana_id", "=",  $this->campania)->where("personal.cargo_id", "=", 7)
                        ->join('users', 'personal.user_id', '=', 'users.id')->where('personal.estatus', 1)
                        ->orderBy('users.nombre_apellido')->get();
                    $autorizado = true;
                } else {
                    $autorizado = false;
                    $operadores = array();
                }
        endswitch;

        $venta = BaitVentas::find($id);
        $estados = BaitEstados::where('active', 1)->orderBy('estado', 'asc')->get();
        # retiramos team Agente de los front
        unset($this->gestion[5]);

        $tiendas = BaitTiendas::find($venta->tienda_id);
        $municipios = $tiendas->TiendaPerteneceAMunicipio;
        $estado = $municipios->MunicipioPerteneceAEstado;

        return view('bait.edit')->with([
            "sexo"          => $this->sexo,
            "venta"         => $venta,
            "estados"       => $estados,
            "fvc"           => $this->fvc,
            "modalidades"   => $this->modalidades,
            "gestion"       => $this->gestion,
            "estado_id"     => $estado->id,
            "municipio"     => $municipios,
            "tienda"        => $tiendas,
            "id"            => $id,
            "operadores"    => $operadores,
            "autorizado"    => $autorizado
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = Auth::user();
        try {
            $venta = BaitVentas::find($id);
            /// si el Operador enviado es diferente al que esta registrado, solo cambiamos la venta de agente y mantenemos el estatus
            // dd($request->all());
            if ($request->operador !=  $venta->personal_id && $request->autorizado == true) {
                $historico = new BaitHistoricos();
                $historico->bait_ventas_id      = $venta->id;
                $historico->estatus_id          = 5; /// Ventas Cedidas
                $historico->estatus_intelix     = $venta->estatus_intelix;
                $historico->usuario             = $user->nombre_apellido;
                $historico->observaciones       = $request->observaciones !== null ? $request->observaciones : "Registro de la Venta Reasignado a nuevo Agente para Recuperacion";
                $historico->save();

                $personal                       = Personal::find($request->operador);
                $venta->personal_id             = $request->operador;
                $venta->supervisor_id           = $personal->jefe_inmediato_id;
                $venta->user_id                 = $personal->user_id;
                $venta->coordinador_id          = $personal->jefe_inmediato_segundo_id;
                $venta->estatus_id              = 5;
                $venta->autorizar               = null;
                $venta->save();

                return redirect()->route('bait.index')->with('success', 'Registro de la Venta Reasignado exitosamente.');
            }

            $venta->idcontacto              = $request->idcontacto;
            $venta->numero_portar           = $request->numero_portabilidad;
            $venta->nombre_apellido         = $request->nombre_apellido;
            $venta->fecha_nacimiento        = Carbon::createFromFormat('d/m/Y', $request->fecha_nacimiento)->format('Y-m-d');
            $venta->genero                  = $request->genero;
            $venta->imei                    = $request->imei;
            $venta->nip                     = $request->codigo_nip;
            $venta->vigencia_nip            = Carbon::createFromFormat('d/m/Y', $request->fecha_vigencia)->format('Y-m-d');
            $venta->email                   = $request->correo_electronico;
            $venta->telefono_principal      = $request->numero_portabilidad;
            $venta->telefono_contacto       = $request->telefono_contacto;
            $venta->fvc                     = $request->fvc;
            $venta->modalidad               = $request->modalidad;
            $venta->fecha_cita              = Carbon::createFromFormat('d/m/Y h:i A', $request->fecha_cita . ' ' . $request->hora_cita)->format('Y-m-d H:i:s');
            $venta->observaciones           = $request->observaciones;
            $venta->grupo_gestion           = $request->gestion;
            $venta->tienda_id               = $request->tienda;

            if (Auth::user()->ficha_personal == "Si") {
                $cargo = Auth::user()->personal->cargo->nombre_cargo;
            } else {
                $cargo = null;
            }
            switch ($venta->estatus_id) {
                case 1:
                    $venta->estatus_id = 1; ## si tiene esttus cargada y lo actualiza se queda cargada
                    break;
                case 3: #devuelta
                case 5: #reasignada    
                    if ($request->habilitar_operador == 1) {
                        $venta->autorizar = null;
                        $venta->estatus_id = 5; ##si recibimos para habilitar al operador que la generase asigna venta por recuperar
                    } else {
                        $venta->estatus_id = 4; ##si el estatus de la venta estaba devuelta o reasignada se coloca recuperada
                    }
                    break;
                default:
                    break;
            }
            $venta->save();

            $historico = new BaitHistoricos();
            $historico->bait_ventas_id      = $venta->id;
            $historico->estatus_id          = $venta->estatus_id;
            $historico->usuario             = $user->nombre_apellido;
            $historico->observaciones       = $request->observaciones == null ? "No se registraron observaciones para la venta" : $request->observaciones;
            $historico->save();

            return redirect()->route('bait.index')->with('success', 'Datos de la Venta Actualizados exitosamente');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al actualizar los datos de la venta' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $venta = BaitVentas::find($id);
            $venta->delete();
            return response()->json("registro Eliminado exitosamente.", 200);
        } catch (\Throwable $e) {
            return response()->json($e->getMessage(), 500);
        }
    }
}
