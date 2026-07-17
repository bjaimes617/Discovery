<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use App\Models\bait\BaitRespondio;
use App\Models\bait\BaitVentas;
use App\Models\Personal;
use App\Models\User;
use Carbon\Carbon;
use DivisionByZeroError;
use Exception;
use Throwable;

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

        $asignadosnoVentas = $this->asignadossinventas( $inicio ,$fin);
         #calculamos por agente que ventas estan en discovery que no estan en respondi
        $notipificadoRespond = $this->CargadasDiscoverySinRespondio($inicio,$fin);

        $row = array();
        $ciclos = array();
        $usuariosrespond = array();
        $conversacionnueva = 0;
        $data   =  BaitRespondio::whereBetween('created_at', [$inicio, $fin])->cursor();
        $cargo = "";

        ///validamos si la persona tiene ficha de personal
        if (Auth::user()->ficha_personal == "Si") {
            $cargo = Auth::user()->personal->cargo->nombre_cargo;
        }

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

        // Validar el rol del usuario para restringir las métricas
        switch ($cargo):
            case 'Supervisor':
                // Si es Supervisor, obtenemos únicamente las cédulas de los asesores a su cargo
                $usuariosrespond = Personal::where("jefe_inmediato_id", "=", Auth::user()->personal->id)
                    ->orWhere("id", "=", Auth::user()->personal->id)
                    ->pluck("numero_empleado")->toArray();
                // Bandera para aplicar filtros adicionales en las consultas SQL
                $solosusventas = true;
                break;
            default:
                // Para otros roles (como Admin), se mostrarán las métricas de todos los asesores
                $solosusventas = false;
                break;
        endswitch;

        usort($ciclos, function ($a, $b) {
            return strcmp($a, $b);
        });

        #totalventas registradas
        $sql = BaitVentas::leftJoin('personal', 'bait_ventas.personal_id', 'personal.id');

        // Filtramos las ventas: solo las del equipo si es supervisor, o todas si no lo es
        if ($solosusventas) {
            $countventas = $sql->where("supervisor_id", Auth::user()->personal->id)->whereBetween('bait_ventas.created_at', [$inicio, $fin])->get();          
        } else {
            $countventas = $sql->whereBetween('bait_ventas.created_at', [$inicio, $fin])->get();           
        }       
        #total Ingresadas a Intelix estatu
        $ingresadas          = $countventas->whereNotIn('estatus_id', [1,3,4,5,6])->count();
        
        #rechazadas de auditorias
        $rechazadas          = $countventas->whereIn('estatus_id', [3,6])->count();
       
        $fvc24               = $countventas->where('fvc', 24)->count();
        $fvc48               = $countventas->where('fvc', 48)->count();

        $totalventascargadas = $countventas->countBy('numero_empleado')->toArray();

        #suma total de ventas cargadas
        $sumaventascargadas = array_sum($totalventascargadas);

        #totalizacion de ciclos de_vida
        $queryCiclos = db::table('bait_respondio')
            ->selectRaw('count(id) as total, ciclo_de_vida')
            ->wherein('ciclo_de_vida', $ciclos)
            ->whereBetween('created_at', [$inicio, $fin])
            ->groupBy('ciclo_de_vida');

        // Si es supervisor, cruzamos la primera palabra del string 'usuario' (que es la cédula) 
        // con nuestro array de asesores ($usuariosrespond) para contar solo sus métricas
        if ($solosusventas) {
            $queryCiclos->whereIn(DB::raw("SUBSTRING_INDEX(usuario, ' ', 1)"), $usuariosrespond);
        }

        // Formateamos el resultado de forma clave => valor usando total y ciclo de vida
        $totalciclos = $queryCiclos->cursor()->pluck('ciclo_de_vida', 'total')->toarray();

        #totalizacion de leads asignados 
        $countleadsAsignados = db::table('bait_respondio')->selectRaw('count(id) as total, DATE(created_at) AS fecha, ciclo_de_vida, usuario')
            ->wherein('ciclo_de_vida', ["Leads asignados", "Ventas cargadas"])
            ->whereBetween('created_at', [$inicio, $fin])
            ->whereNotNull('usuario')
            ->groupByRaw('DATE(created_at), ciclo_de_vida, usuario')->get();

        #arreglo con los datos del usuario para metricas
        $Datospersonales = Personal::leftjoin("personal as superv", "superv.id", "personal.jefe_inmediato_id")
            ->leftjoin("users as superv_users", "superv_users.id", "superv.user_id")
            ->leftjoin("users as user", "user.id", "personal.user_id")
            ->whereIn("personal.numero_empleado", $usuariosrespond);

        ///arrays con informacion de supers y agentes en variable $personal, a;adimos los datos igual del supervisor puesto que tambien cargan ventas
        $personal       = $Datospersonales->orWhereIn('personal.cargo_id',[3,4,7])->pluck("user.nombre_apellido", "personal.numero_empleado")->toArray();
        $super          = $Datospersonales->pluck("superv_users.nombre_apellido", "personal.numero_empleado")->toArray();

        $tablemetricas = array();
        $venta_respondido = 0;
        $leads_asignados = 0;
        ##recorrido para la tabla de agentes
        foreach ($countleadsAsignados as $countrespondlead) {
            $nombre = "No disponible";
            $cedula = array();

            // Validamos a quién pertenece este lead asignado o respondido
            switch ($countrespondlead->usuario) {
                case "Asesor Ventas IA":
                case "1 IA DANIELA":
                    // Los agentes IA no pertenecen al equipo de un supervisor, por lo que los ignoramos (continue)
                    if ($cargo == "Supervisor") {
                        continue 2;
                    }
                    $cedula[0] = "Agente IA";
                    $nombre = $cedula[0];
                    break;
                case null:
                    // De igual forma, omitimos registros sin usuario para las métricas de supervisores
                    if ($cargo == "Supervisor") {
                        continue 2;
                    }
                    $cedula[0] = "Sin Usuario";
                    $nombre = $cedula[0];
                    break;
                default:
                    $cedula = explode(" ", $countrespondlead->usuario);
                    if ($cargo == "Supervisor") {
                        // Si la cédula del lead no está en la lista de agentes del supervisor, la saltamos
                        if (!in_array($cedula[0], $usuariosrespond)) {
                            continue 2;
                        }
                        // Buscamos el nombre del asesor, si no existe mostramos una etiqueta de advertencia
                        $nombre = array_key_exists($cedula[0], $personal) ? $personal[$cedula[0]] : "<span class='badge badge-warning'><b> No Registrer Discovery: " . $cedula[0] . "</b></span>";
                    } else {
                        // Para el resto de cargos (ej. Admin), mostramos el nombre del usuario sin importar si es de su equipo o no
                        $nombre = array_key_exists($cedula[0], $personal) ? $personal[$cedula[0]] : "<span class='badge badge-warning'><b> No Registrer Discovery: " . $cedula[0] . "</b></span>";
                    }
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
    
        // Añadimos a los usuarios que tienen ventas cargadas pero que no tuvieron registros en bait_respondio (leads)
        foreach ($totalventascargadas as $cedula_ventas => $cargadas) {
            if (!array_key_exists($cedula_ventas, $tablemetricas)) {
                                              
                $nombre = array_key_exists($cedula_ventas, $personal) ? $personal[$cedula_ventas] : "<span class='badge badge-warning'><b> No Registrer Discovery: " . $cedula_ventas . "</b></span>";
              //  dd($totalventascargadas,$cedula_ventas,$super);
                $tablemetricas[$cedula_ventas] = array(
                    "supervisor" => $super[$cedula_ventas] ?? "No disponible",
                    "nombre"     => $nombre,
                    "leads"      => 0,
                    "meta"       => 0,
                    "venta_respondido" => 0,
                    "cargadas"   => $cargadas,
                    "conversion" => 0,
                );
            }
        }
    
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
            "rechazadas"        => $rechazadas,
            "ingresadas_intelix" => $ingresadas,
            "no_cargado" => $countventas->count() - $ingresadas - $rechazadas,
            "fvc24" => $fvc24,
            "fvc48" => $fvc48,
            "conversion_global" => array_search("Leads asignados", $totalciclos) ? round($sumaventascargadas /  array_search("Leads asignados", $totalciclos) * 100, 2) : 0,
            "usuarios" => $tablemetricas,
            "conversacionXventa" => $ventaXconversacion,
           
        );

        if ($response) {
            return response()->json([$row,$asignadosnoVentas,$notipificadoRespond]);
        } else {
            return view('dashboard.bait', compact('row', 'asignadosnoVentas','notipificadoRespond'));
        }
    }

    private function asignadossinventas($inicio, $fin)
    {
     
            $ventas = BaitRespondio::select('idcontacto')->where('ciclo_de_vida', 'Ventas cargadas')->whereBetween('created_at', [$inicio, $fin])->pluck('idcontacto');

            $contactosLeads = BaitRespondio::select('idcontacto')->where('ciclo_de_vida', 'Leads asignados')->whereNot(function ($query) use ($ventas) {
                $query->whereIn('idcontacto', $ventas);
            })->whereBetween('created_at', [$inicio, $fin])->pluck('idcontacto');

            $bait = BaitRespondio::whereIn('idcontacto', $contactosLeads)
                ->orderBy('created_at', 'DESC')
                ->orderBy('id', 'DESC')
                ->get()
                ->unique('idcontacto');

            $Datospersonales = Personal::leftjoin("personal as superv", "superv.id", "personal.jefe_inmediato_id")
                ->leftjoin("users as superv_users", "superv_users.id", "superv.user_id")
                ->leftjoin("users as user", "user.id", "personal.user_id");

            ///arrays con informacion de supers y agentes
            $personal       = $Datospersonales->pluck("user.nombre_apellido", "personal.numero_empleado")->toArray();
            $super          = $Datospersonales->pluck("superv_users.nombre_apellido", "personal.numero_empleado")->toArray();

            $data = array();

            foreach ($bait as $key => $value) {
                $cedula = explode(" ", $value->usuario);
                $leand["fecha"] = Carbon::create($value->created_at)->format('d/m/Y');
                $leand["hora"] = Carbon::create($value->created_at)->format('H:i:s');
                $leand["ciclo_de_vida"] = $value->ciclo_de_vida == "Leads asignados" ?
                                '<span class="badge badge-warning"><b>' . $value->ciclo_de_vida . '</b></span>' :
                                $value->ciclo_de_vida;
                $leand["numero_contacto"] = $value->numero_contacto;
                $leand["idcontacto"] = $value->idcontacto;
                $leand["agente"]    = $personal[$cedula[0]] ?? $value->usuario;
                $leand["supervisor"] = $super[$cedula[0]] ?? "- - ";
                $data[] = $leand;
            }
            usort($data, function ($a, $b) {
                return strcmp($a['agente'], $b['agente']);
            });

            return$data;
        
    }

    private function CargadasDiscoverySinRespondio($inicio, $fin)
    {
        $cargo = '';
        
        if (Auth::user()->ficha_personal == "Si") {
            $cargo = Auth::user()->personal->cargo->nombre_cargo;
        }

        #ubicamos los id de contacto que registrados en discovery que no hemos recibido desde respondio.
        $cargadas = BaitRespondio::where('ciclo_de_vida','Ventas Cargadas')           
            ->whereBetween('bait_respondio.created_at', [$inicio, $fin])
            ->groupby('idcontacto')
            ->pluck('idcontacto');

        $noven = BaitVentas::with(['RelationUser'])
            ->whereNotIn('idcontacto',$cargadas)
            ->whereBetween('created_at', [$inicio, $fin]);          

        if ($cargo == "Supervisor") {
            $nocargada = $noven->where("supervisor_id", Auth::user()->personal->id)->whereBetween('bait_ventas.created_at', [$inicio, $fin])->get();          
        } else {
            $nocargada = $noven->whereBetween('bait_ventas.created_at', [$inicio, $fin])->get();           
        }  

        $data = array();
   
        foreach ($nocargada as $key => $value) {
            $leand["fecha"] = Carbon::create($value->created_at)->format('d/m/Y');
            $leand["hora"] = Carbon::create($value->created_at)->format('H:i:s');
            $leand["ciclo_de_vida"] = $value->ciclo_vida ? $value->ciclo_vida : 'Ventas cargadas';
            $leand["portabilidad"] = $value->numero_portar;
            $leand["numero_contacto"] = $value->telefono_principal ? $value->telefono_principal : $value->numero_portar;
            $leand["idcontacto"] = $value->idcontacto;
            $leand["agente"]    = $value->RelationUser ? $value->RelationUser->nombre_apellido : '- -';           
            $data[] = $leand;
        }

        return $data;        
    }
}
