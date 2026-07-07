<?php

namespace App\Http\Controllers\Bait;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\Personal;
use App\Models\bait\BaitVentas;
use App\Models\bait\BaitEstados;
use App\Models\bait\BaitTiendas;
use App\Models\bait\BaitHistoricos;
use App\Models\bait\BaitEstatus;
use App\Models\bait\BaitRespondio;
use App\Models\bait\BaitEstatusConcentra;
use App\Models\bait\BaitEstatusIntelix;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class Backoffice extends Controller

{
    private $sexo, $fvc, $gestion, $modalidades, $campania, $sns, $validatebo, $validacionalta;

    public function __construct($campania = 5)
    {
        $this->campania = $campania;

        $this->sexo = [
            'M' => 'Masculino',
            'F' => 'Femenino',
        ];

        $this->fvc          = [24 => "24 Horas", 48 => "48 Horas"];
        #$this->modalidades = ["CPP" => "CPP", "MPP" => "MPP", "ESIM" => "ESIM", "SIM" => "SIM"];
        $this->modalidades  = ["CPP" => "CPP"];
        $this->gestion      = ["1" => "Team 1", "2" => "Team 2", "3" => "Team 3", "4" => "Team Nocturno", "5" => "Team Agent IA"];

        $this->sns = config('app.sns');
        $this->validatebo = config('app.validatebo');
        $this->validacionalta = config('app.validacionalta');
    }

    public function index()
    {
        $cargo = "";
        $user = ENV('DEV_USER_CHANGE', false) ? Auth::loginUsingId(2, true) : Auth::user();

        switch ($cargo):
            case "Operador":
                $supervisores = Personal::with(['RelationUser'])->select('personal.id', 'users.nombre_apellido')->where("personal.campana_id", "=",  $this->campania)
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

        return view('bait.backoffice.index')->with([
            "supervisores" => $supervisores
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function SearchVentasFVC(Request $request)
    {
        $fecha = explode("-", $request->fecha);
        $data = array();
        $arreglo = array();
        $init = carbon::createFromFormat('d/m/Y', trim($fecha[0]));
        $end = carbon::createFromFormat('d/m/Y', trim($fecha[1]));

        $sql = BaitVentas::whereIn('estatus_id', [1, 4])
            ->whereBetween('bait_ventas.created_at', array($init->copy()->startOfDay(), $end->copy()->endOfDay()));
        $cargo = "";
        ///validamos si la persona tiene ficha de personal
        if (Auth::user()->ficha_personal == "Si") {
            $cargo = Auth::user()->personal->cargo->nombre_cargo;
        }
        /// se valida si viene valores de supervisor 
        if ($request->supervisor != "todos") {
            if ($cargo == 'Operador') {
                $sql->where("bait_ventas.backoffice_acargo", "=", Auth::user()->personal->id);
            } else {
                $sql->where("bait_ventas.supervisor_id", "=", $request->supervisor);
            }
        } else {
            ///si no, en base al cargo buscamos los datos
            switch ($cargo):
                case "backoffice":
                    $sql->where("bait_ventas.backoffice_acargo", "=", Auth::user()->personal->id)
                        ->orWhereNull("bait_ventas.backoffice_acargo");
                    break;
                case 'Coordinador':
                    $sql->where('bait_ventas.coordinador_id', '=', Auth::user()->personal->id);
                    break;
            endswitch;
        }

        if (Auth::user()->ficha_personal == "Si" || Auth::user()->hasPermission('bait.administrativo')) {
            $data = $sql->orderBy('fvc', 'DESC')->orderBy('created_at', 'DESC')->get();
        }

        $editarhtml = "";
        $deleteHtml = "";

        foreach ($data as $result) {

            $historico = BaitRespondio::where('idcontacto', $result->idcontacto)->orderby('id', 'desc')->first();

            $row["id"]              = $result->id;
            $row["creado"]          = date('d/m/Y', strtotime($result->created_at));
            $row["fvc"]             = $result->fvc == 24 ? '<span class="badge badge-danger">' . $result->fvc . ' Horas</span>' : '<span class="badge badge-warning">' . $result->fvc . ' Horas</span>';
            $row["identificador"]   = $result->numero_portar;
            $row["ciclo_vida"]      = $historico == null ? "<span class='badge badge-danger'>N/D</span>" : $historico->ciclo_de_vida;
            $row["nombreapellido"]  = $result->nombre_apellido;
            $row["agente"]          = $result->relationUser != null ? $result->relationUser->nombre_apellido : "N/D";
            $row["supervisor"]      = $result->supervisor_id != null ? $result->relationSupervisor->RelationUser->nombre_apellido : "N/D";
            $row["estatus"]         = $result->relationEstatus->descripcion;
            $vent = 'onclick="DestroyVentas(' . $result->id . '); "';

            switch ($result->estatus_id) {

                default:
                    $editarhtml = Auth::user()->HasPermission('bait.edit') ?
                        '<a href="' . route('bait.backoffice.edit', $result->id) . '" target="_blank" class="btn btn-sm btn-primary"  data-toggle="tooltip" data-placement="top" title="Actualizar Estatus"><i class="fas fa-edit"></i></a>'
                        : null;
                    $deleteHtml = Auth::user()->HasPermission('bait.destroy') ?
                        '<button type="button"  ' . $vent . ' class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="top" title="Remover" ><i class="fa fa-trash"</i></button>'
                        : null;
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

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $venta = BaitVentas::find($id);

        if ($venta->estatus_id == 2) {
            Session::flash('status', 'La venta ya se encuentra Ingresada');
            return redirect()->route('bait.backoffice.index');
        }

        $estados = BaitEstados::where('active', 1)->orderBy('estado', 'asc')->get();

        $tiendas    = BaitTiendas::find($venta->tienda_id);
        $municipios = $tiendas->TiendaPerteneceAMunicipio;
        $estado     = $municipios->MunicipioPerteneceAEstado;
        $intelix = BaitEstatusIntelix::where('grupo', "a")->where('active', 1)->get();
        return view('bait.backoffice.edit')->with([
            "sexo"          => $this->sexo,
            "venta"         => $venta,
            "estados"       => $estados,
            "fvc"           => $this->fvc,
            "modalidades"   => $this->modalidades,
            "gestion"       => $this->gestion,
            "estado_id"     => $estado->id,
            "municipio"  => $municipios,
            "tienda"     => $tiendas,
            "id"            => $id,
            "intelix"       => $intelix
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = Auth::user();

        if ($request->auditoria == "aprobada") {
            $estatus = 2; # 2 ingresada intelix
            $folio = $request->folio;
            $observaciones = "Venta Ingresada a Intelix";
            $estatus_intelix = "Ingresada";
            $autorizar = null;
        } else {
            $estatus            = $request->check_rechazo == 1 ? 6 : 3; #6 rechazada | 3 devuelta puede recuperarse
            $folio              = null;
            $estatus_intelix    = $request->estatus_intelix;
            $observaciones      = $request->observacionesAudit;
            $autorizar          = null;
        }

        try {
            $venta = BaitVentas::find($id);
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
            $venta->folio_venta             = $folio;
            $venta->backoffice_acargo       = $user->nombre_apellido;
            $venta->estatus_id              = $estatus;
            $venta->estatus_intelix         = strtoupper($estatus_intelix);
            $venta->autorizar               = $autorizar;
            $venta->save();

            $historico = new BaitHistoricos();
            $historico->bait_ventas_id      = $venta->id;
            $historico->estatus_id          = $estatus;
            $historico->usuario             = $user->nombre_apellido;
            $historico->estatus_intelix     = strtoupper($estatus_intelix);
            $historico->observaciones       = $observaciones;
            $historico->save();

            return redirect()->route('bait.backoffice.index')->with('success', 'Venta Auditada exitosamente');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al auditar la venta ' . $e->getMessage());
        }
    }

    public function IndexPostventa()
    {

        return view('bait.backoffice.postventas.index');
    }

    public function SearchPostventa()
    {

        $query = DB::table('bait_ventas as c')
            ->select(
                'c.id',
                'c.idcontacto',
                'c.numero_portar',
                'c.nombre_apellido',
                'c.fvc',
                'c.created_at as registrado',
                'sup.nombre_apellido as supervisor',
                'ag.nombre_apellido as agente',
                'e.descripcion as estatus',
                'c.estatus_intelix',
                'c.autorizar'
            )->leftJoin('personal as s', 's.id', '=', 'c.supervisor_id')
            ->leftJoin('users as sup', 'sup.id', '=', 's.user_id')
            ->leftJoin('personal as a', 'a.id', '=', 'c.personal_id')
            ->leftJoin('users as ag', 'ag.id', '=', 'a.user_id')
            ->leftJoin('bait_estatus as e', 'e.id', '=', 'c.estatus_id')
            ->whereNotIn('c.estatus_id', [1, 3, 4, 5])->orderBy('c.fvc', 'ASC');
        return DataTables::of($query)
            // Filtro para Supervisor (Usa la tabla sup y columna nombre_apellido)
            ->filterColumn('supervisor', function ($query, $keyword) {
                $query->whereRaw("sup.nombre_apellido LIKE ?", ["%{$keyword}%"]);
            })
            // Filtro para Agente (Usa la tabla ag y columna nombre_apellido)
            ->filterColumn('agente', function ($query, $keyword) {
                $query->whereRaw("ag.nombre_apellido LIKE ?", ["%{$keyword}%"]);
            })
            ->filterColumn('registrado', function ($query, $keyword) {
                $query->whereRaw("DATE_FORMAT(c.created_at, '%d/%m/%Y') LIKE ?", ["%{$keyword}%"]);
            })
            // Filtro para Estatus (Usa la tabla e y columna descripcion)
            ->filterColumn('estatus', function ($query, $keyword) {
                $query->whereRaw("e.descripcion LIKE ?", ["%{$keyword}%"]);
            })
            ->filterColumn('idcontacto', function ($query, $keyword) {
                $query->whereRaw("c.idcontacto LIKE ?", ["%{$keyword}%"]);
            })
            ->filterColumn('numero_portar', function ($query, $keyword) {
                $query->whereRaw("c.numero_portar LIKE ?", ["%{$keyword}%"]);
            })
            ->editColumn('registrado', function ($row) {
                return Carbon::parse($row->registrado)->format('d/m/Y');
            })
            ->editColumn('agente', function ($row) {
                return $row->agente == null ? 'N/D' : $row->agente;
            })
            ->editColumn('supervisor', function ($row) {
                return $row->supervisor == null ? 'N/D' : $row->supervisor;
            })
            ->editColumn('ciclo_vida', function ($row) {
                $respondio = BaitRespondio::where('idcontacto', $row->idcontacto)->orderby('created_at', 'DESC')->limit(1)->first();
                return $respondio == null ? 'N/D' : $respondio->ciclo_de_vida;
            })
            // ... tus editColumn y addColumn siguen igual
            ->editColumn('autorizar', function ($row) {
                return $row->autorizar == 1 ? '<span class="badge badge-danger"><i class="fas fa-lock"></i></span>' :
                    '<span class="badge badge-success"><i class="fas fa-lock-open"></i></span>';
            })
            ->editColumn('fvc', function ($row) {
                return $row->fvc == 24 ? '<span class="badge badge-danger">' . $row->fvc . ' Horas</span>' : '<span class="badge badge-warning">' . $row->fvc . ' Horas</span>';
            })
            ->editColumn('estatus_intelix', function ($row) {
                return $row->estatus_intelix == "" ? "N/D" : '<span class="badge badge-info">' . $row->estatus_intelix . '</span>';
            })
            ->addColumn('acciones', function ($row) {

                $buttondelete = '<button class="btn btn-danger btn-sm btn-icon" onclick="EliminarVenta(' . $row->id . ')" id="eliminarsales' . $row->id . '" data-href="' . route('bait.backoffice.postventa.delete', $row->id) . '" data-toggle="tooltip" data-placement="top" title="Eliminar"><i class="fas fa-trash"></i></button>';

                $buttonView = '<button class="btn btn-primary btn-sm btn-icon" data-href="' . route('bait.backoffice.postventa.historico') . '" onclick="VisualizarHistoricoVenta(' . $row->id . ')" id="historicoshow' . $row->id . '" data-toggle="tooltip" data-placement="top" title="Ver"><i class="fas fa-eye"></i></button>';
                $form = '<div class="btn-group">' . $buttonView . $buttondelete . '</div>';
                return $form;
            })
            ->rawColumns(['acciones', 'fvc', 'estatus_intelix', 'autorizar'])
            ->make(true);
    }

    public function ShowHistoricos(Request $request)
    {
        $historico = array();
        $venta = null;

        try {
            $venta          = BaitVentas::find($request->id);
            $tiendas        = BaitTiendas::find($venta->tienda_id);
            $estatusArray   = BaitEstatus::where('active', 1)->pluck('descripcion', 'id')->toArray();
            $estados        = BaitEstados::where('active', 1)->orderBy('estado', 'asc')->get();
            $municipios     = $tiendas->TiendaPerteneceAMunicipio;
            $estado         = $municipios->MunicipioPerteneceAEstado;
            $concentra      = BaitEstatusConcentra::where('active', 1);
            $Concentraarray = $concentra->pluck("descripcion", "id")->toArray();
            $historico      = $venta->relationHistorico()->orderby('id', 'desc')->get();

            $intelix        = BaitEstatusIntelix::where('grupo', "b")->where('active', 1)->get();
            $estatus_final  = BaitEstatus::wherein('id', [6, 7, 8, 9, 10, 11])->where('active', 1)->get();

            foreach ($historico as $key => $value) {
                $historico[$key]->sns = $historico[$key]->sns != null ? $historico[$key]->sns : "N/D";
                $historico[$key]->estatus_concentra = $historico[$key]->bait_concentra_id != null ? $Concentraarray[$value->bait_concentra_id] : "N/D";
                $historico[$key]->fecha = Carbon::parse($value->created_at)->format('d/m/Y H:i A');
                $historico[$key]->estatus = $value->estatus_id != null ? $estatusArray[$value->estatus_id] : "N/D";
            }

            $formventa = view('bait.backoffice.postventas.partial')->with([
                "sexo"          => $this->sexo,
                "venta"         => $venta,
                "estados"       => $estados,
                "fvc"           => $this->fvc,
                "modalidades"   => $this->modalidades,
                "gestion"       => $this->gestion,
                "estado_id"     => $estado->id,
                "municipio"     => $municipios,
                "tienda"        => $tiendas,
                "concentra"     => $concentra->get(),
                "sns"           => $this->sns,
                "intelix"       => $intelix,
                "estatus_final" => $estatus_final,
                "validatebo"    => $this->validatebo,
                "validacionalta" => $this->validacionalta
            ])->render();

            return response()->json(["venta" => $formventa, "historico" => $historico]);
        } catch (\Exception $e) {

            return response()->json([
                'error' => 'No es posible aperturar los Datos de la Venta, ' . $e->getMessage()
            ], 500);
        }
    }

    public function ShowModalUpdate(Request $request, $id)
    {
        //dd($request->all());
        $user = Auth::user();
        try {
            $venta = BaitVentas::find($id);
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
            $venta->backoffice_acargo       = $user->nombre_apellido;
            $venta->estatus_id              = $request->estatus_final;
            $venta->estatus_intelix         = strtoupper($request->intelix);
            $venta->autorizar               = 1;
            $venta->sns                     = strtoupper($request->sns);
            $venta->estatus_backoffice      = strtoupper($request->validar_bo);
            $venta->validador_alta          = strtoupper($request->validar_alta);
            $venta->bait_concentra_id       = $request->concentra;
            $venta->save();

            $historico = new BaitHistoricos();
            $historico->bait_ventas_id      = $venta->id;
            $historico->estatus_id          = $request->estatus_final;
            $historico->usuario             = $user->nombre_apellido;
            $historico->estatus_intelix     = strtoupper($request->intelix);
            $historico->sns                 = strtoupper($request->sns);
            $historico->estatus_backoffice  = strtoupper($request->validar_bo);
            $historico->validador_alta      = strtoupper($request->validar_alta);
            $historico->bait_concentra_id   = $request->concentra;
            $historico->observaciones       = strtoupper($request->observaciones_aclara);
            $historico->save();

            return redirect()->route('bait.backoffice.postventa')->with('success', 'Informacion Actualizada Correctamente');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al actualizar la venta' . $e->getMessage());
        }
    }

    public function EliminarVenta(Request $request)
    {
        try {
            $venta = BaitVentas::find($request->id);
            $venta->delete();
            return response()->json([
                'message' => 'Venta eliminada exitosamente',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al eliminar la venta' . $e->getMessage(),
            ], 500);
        }
    }

    public function BaitUnlockSeguimientos()
    {
        try {
            $venta = BaitVentas::where('autorizar', 1)->cursor();
            $conteo = $venta->count();
            foreach ($venta as $value) {
                $value->autorizar = null;
                $value->save();
            }
            return response()->json([
                'message' => 'Se desbloquearon ' . $conteo . ' ventas exitosamente',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al desbloquear las ventas ' . $e->getMessage(),
            ], 500);
        }
    }
}
