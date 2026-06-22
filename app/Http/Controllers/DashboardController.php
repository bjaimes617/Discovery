<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\Models\bait\BaitRespondio;
use App\Models\bait\BaitVentas;
use App\Models\Personal;
use App\Models\User;
use Carbon\Carbon;
use Auth;
use DivisionByZeroError;

class DashboardController extends Controller
{


    //protected $redirectTo = '/login';

    public function __construct()
    {
        $this->middleware(['auth', 'verified', 'check.password']);
    }

    /**
     * Display a client login form view.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('dashboard.index');
    }

    public function bait(request $request)
    {
        if ($request->method() == "GET") {
            $inicio = Carbon::now()->startOfDay();
            $fin    = $inicio->copy()->endOfDay();
            $response = false;
        } else {
            $response = true;
            $fecha = explode("-", $request->fecha);
            $inicio = Carbon::createFromFormat('d/m/Y', trim($fecha[0]))->startOfDay();
            $fin    = Carbon::createFromFormat('d/m/Y', trim($fecha[1]))->endOfDay();
        }

        $row = array();
        $ciclos = array();
        $usuariosrespond = array();
        $conversacionnueva = 0;
        $data   =  BaitRespondio::whereBetween('created_at', [$inicio, $fin])->cursor();

        ##Extraemos todos los ciclos de vida existentes
        $data->each(function ($item) use (&$ciclos, &$usuariosrespond) {
            if (!in_array($item->ciclo_de_vida, $ciclos)) {
                $ciclos[] = $item->ciclo_de_vida;
            }

            if (!array_key_exists($cedula = explode(" ", $item->usuario)[0], $usuariosrespond)) {
                $cedula = explode(" ", $item->usuario);
                $usuariosrespond[$cedula[0]] = $cedula[0];
            }
        });

        usort($ciclos, function ($a, $b) {
            return strcmp($a, $b);
        });

        #totalventas registradas
        $countventas = BaitVentas::leftJoin('personal', 'bait_ventas.personal_id', 'personal.id')
            ->whereBetween('bait_ventas.created_at', [$inicio, $fin])->get();

        ##total Ingresadas a Intelix
        $ingresadas          = $countventas->where('estatus_id', 2)->count();
        $fvc24               = $countventas->where('fvc', 24)->count();
        $fvc48               = $countventas->where('fvc', 48)->count();

        $totalventascargadas = $countventas->countBy('numero_empleado')->toArray();

        #suma total de ventas cargadas
        $sumaventascargadas = array_sum($totalventascargadas);

        #totalizacion de ciclos de_vida
        $totalciclos = db::table('bait_respondio')->selectRaw('count(id) as total, ciclo_de_vida')->wherein('ciclo_de_vida', $ciclos)->whereBetween('created_at', [$inicio, $fin])->groupBy('ciclo_de_vida')->cursor()->pluck('ciclo_de_vida', 'total')->toarray();

        #totalizacion de leads asignados 
        $countleadsAsignados = db::table('bait_respondio')->selectRaw('count(id) as total, DATE(created_at) AS fecha, ciclo_de_vida, usuario')
            ->wherein('ciclo_de_vida', ["Leads asignados", "Ventas cargadas"])
            ->whereBetween('created_at', [$inicio, $fin])
            ->whereNotNull('usuario')
            ->groupByRaw('DATE(created_at), ciclo_de_vida, usuario')->get();

        #arreglo con los datos del usuario
        $Datospersonales = Personal::leftjoin("personal as superv", "superv.id", "personal.jefe_inmediato_id")
            ->leftjoin("users as superv_users", "superv_users.id", "superv.user_id")
            ->leftjoin("users as user", "user.id", "personal.user_id")
            ->wherein("personal.numero_empleado", $usuariosrespond);

        $personal       = $Datospersonales->pluck("user.nombre_apellido", "personal.numero_empleado")->toArray();
        $super          = $Datospersonales->pluck("superv_users.nombre_apellido", "personal.numero_empleado")->toArray();

        $tablemetricas = array();
        $venta_respondido = 0;
        $leads_asignados = 0;
        ##recorrido para la tabla de agentes
        foreach ($countleadsAsignados as $countrespondlead) {
            $nombre = "No disponible";
            $cedula = array();

            switch ($countrespondlead->usuario) {
                case "Asesor Ventas IA":
                case "1 IA DANIELA":
                    $cedula[0] = "Agente IA";
                    $nombre = $cedula[0];
                    break;
                case null:
                    $cedula[0] = "Sin Usuario";
                    $nombre = $cedula[0];
                    break;
                case count(explode(" ", $countrespondlead->usuario)) < 3:
                    $nombre = explode(" ", $countrespondlead->usuario)[0];
                default:
                    $cedula = explode(" ", $countrespondlead->usuario);
                    $nombre = array_key_exists($cedula[0], $personal) ? $personal[$cedula[0]] : "<span class='badge badge-warning'><b> No Registrer Discovery: " . $cedula[0] . "</b></span>";
                    break;
            }
            if (!array_key_exists($cedula[0], $tablemetricas)) {

                $venta_respondido = $countrespondlead->ciclo_de_vida == 'Ventas cargadas' ? $countrespondlead->total : 0;
                $leads_asignados  = $countrespondlead->ciclo_de_vida == 'Leads asignados' ? $countrespondlead->total : 0;

                $tablemetricas[$cedula[0]] = array(
                    "supervisor" => $super[$cedula[0]] ?? "No disponible",
                    "nombre"   => $nombre,
                    "leads"    => $leads_asignados,
                    "meta"     => $countrespondlead->total,
                    "venta_respondido" => $venta_respondido,
                    "cargadas" => $totalventascargadas[$cedula[0]] ?? 0,
                    "conversion" => $countrespondlead->total,
                );
            } else {
                $venta_respondido = $countrespondlead->ciclo_de_vida == 'Ventas cargadas' ? $countrespondlead->total : 0;
                $leads_asignados  = $countrespondlead->ciclo_de_vida == 'Leads asignados' ? $countrespondlead->total : 0;

                $tablemetricas[$cedula[0]]["leads"]          += $leads_asignados;
                $tablemetricas[$cedula[0]]["venta_respondido"] += $venta_respondido;
                $tablemetricas[$cedula[0]]["meta"]           += $leads_asignados;
                $tablemetricas[$cedula[0]]["conversion"]     += $leads_asignados;
            }
        }
        // dd($tablemetricas);
        ### recalcular los porcentames de metas y conversiones de la tabla
        foreach ($tablemetricas as $key => $value) {
            if ($value["leads"] > 0) {
                $conversion_calculada = ($value["cargadas"] /  $value["leads"]) * 100;
                $calculo = round($conversion_calculada, 2);
                if ($calculo < 25) {
                    $color = "badge badge-danger";
                } elseif ($calculo > 35) {
                    $color = "badge badge-success";
                } else {
                    $color = "badge badge-warning";
                }
                $conversion = '<span class="' . $color . '"><b>' . $calculo . '%</b></span>';
            } else {
                $conversion = '<span class="badge badge-danger"><b>0 %</b></span>';
            }
            $tablemetricas[$key]["conversion"] = $conversion;
            $tablemetricas[$key]["meta"] = round($value["leads"] * 0.30, 0);
        }


        ##calculo de conversion conversacion nueva / ventas
        $ventaXconversacion = 0;
        foreach ($totalciclos as $key => $tolciclo) {
            if ($tolciclo == "Conversaciones Nuevas") {
                if ($key > 0) {
                    $ventaXconversacion = round($sumaventascargadas / $key * 100, 2);
                } else {
                    $ventaXconversacion = 0;
                }
            }
        }

        try {
            $contacto_a_lead = round(array_search("Leads asignados", $totalciclos) / array_search("Conversaciones Nuevas", $totalciclos) * 100, 2);
            $perdida_contacto = round(100 - (array_search("Leads asignados", $totalciclos) / array_search("Conversaciones Nuevas", $totalciclos) * 100), 2);
        } catch (\DivisionByZeroError $e) {
            $contacto_a_lead = 0;
            $perdida_contacto = 0;
        }
        $row = array(
            "lead_asignados" => array_search("Leads asignados", $totalciclos) ? array_search("Leads asignados", $totalciclos) : 0,
            "meta_venta" => array_search("Leads asignados", $totalciclos) ? round(array_search("Leads asignados", $totalciclos) * 0.30, 0) : 0,
            "ventas_respondio" => array_search("Ventas cargadas", $totalciclos) ? array_search("Ventas cargadas", $totalciclos) : 0,
            "contacto_a_lead" => $contacto_a_lead,
            "perdida_contacto" => $perdida_contacto,
            "ventas_discovery" => $countventas->count(),
            "ingresadas_intelix" => $ingresadas,
            "no_cargado" => $countventas->count() - $ingresadas,
            "fvc24" => $fvc24,
            "fvc48" => $fvc48,
            "conversion_global" => array_search("Leads asignados", $totalciclos) ? round($sumaventascargadas /  array_search("Leads asignados", $totalciclos) * 100, 2) : 0,
            "usuarios" => $tablemetricas,
            "conversacionXventa" => $ventaXconversacion
        );

        if ($response) {
            return response()->json($row);
        } else {
            return view('dashboard.bait', compact('row'));
        }
    }
}
