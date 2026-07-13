<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\bait\BaitRespondio;
use App\Models\bait\BaitVentas;
use App\Models\bait\BaitHistoricos;
use App\Models\bait\BaitTiendas;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class BaitApiController extends Controller
{

    public function __construct($campania = 5)
    {
        $this->middleware('CheckBaitPortabilidad')->only(['StoreVentas']);
    }   

    public function show(Request $request)
    {
       
        $iclos_mostrar_ventas = ["Ventas cargadas", "Re-gestión"];

        return response()->stream(function () use ($iclos_mostrar_ventas,$request) {
            $out = fopen('php://output', 'w');
            fwrite($out, '[');
            $init = carbon::create($request->inicio)->startOfDay();
            $fin =  carbon::create($request->fin)->endofDay();
            $first = true;

            $data = BaitRespondio::select(
                'bait_respondio.created_at',
                'bait_respondio.idcontacto',
                'bait_respondio.ciclo_de_vida',
                'bait_respondio.anuncio',
                'bait_respondio.usuario as responduser',
                'bait_ventas.email',
                'bait_ventas.nombre_apellido as cliente',
                'bait_ventas.numero_portar',
                'bait_ventas.nip',
                'bait_ventas.vigencia_nip',
                'bait_ventas.fecha_nacimiento',
                'bait_ventas.tienda_id',
                'bait_tiendas.unidad',
                'bait_municipios.municipio',
                'bait_estados.estado',
                'bait_ventas.telefono_contacto',
                'bait_ventas.fecha_cita',
                'bait_ventas.fvc',
                'bait_ventas.modalidad',
                'bait_ventas.nombre_apellido',
                'bait_ventas.supervisor_id',
                'bait_ventas.coordinador_id',
                'bait_ventas.personal_id',
                'op.numero_empleado as empleado',
                'sup.numero_empleado as supervisor',
                'coord.numero_empleado as coordinador')
                ->leftJoin('bait_ventas', 'bait_respondio.idcontacto', '=', 'bait_ventas.idcontacto')
                ->leftJoin('bait_tiendas', 'bait_ventas.tienda_id', '=', 'bait_tiendas.id')
                ->leftJoin('bait_municipios', 'bait_tiendas.municipio_id', '=', 'bait_municipios.id')
                ->leftJoin('bait_estados', 'bait_municipios.estado_id', '=', 'bait_estados.id')
                ->leftJoin('personal as op', 'bait_ventas.personal_id', '=', 'op.id')
                ->leftJoin('personal as sup', 'bait_ventas.supervisor_id', '=', 'sup.id')
                ->leftJoin('personal as coord', 'bait_ventas.coordinador_id', '=', 'coord.id')
                ->wherebetween('bait_respondio.created_at',array($init, $fin))
                ->cursor();

            foreach ($data as $value) {
                if (!$first) {
                    fwrite($out, ',');
                }
                $first = false;

                $active = in_array($value->ciclo_de_vida, $iclos_mostrar_ventas) ? true : false;
                $array = array(
                    'Fecha'                 => Carbon::create($value->created_at)->format('Y-m-d H:i:s'),
                    'ID de Contacto'        => $value->idcontacto,
                    'Ciclo de vida'         => $value->ciclo_de_vida,
                    'Nombre'                => 'Whastapp',
                    'Campaña'               => $value->anuncio,
                    'Correo electronico'    => $active ? $value->email : '',
                    'Telefono'              => $active ? $value->numero_portabilidad : '',
                    'Pais'                  => 'MX',
                    'Cesionario'            => '',
                    'IMEI'                  => $active ? $value->imei : '',
                    'Origen del Lead'       => 'Whatsapp',
                    'Fecha de Nacimiento'   => $active ? $value->fecha_nacimiento : '',
                    'Genero'                => $active ? $value->genero : '',
                    'Numero portabilidad'   => $active ? $value->numero_portabilidad : '',
                    'Codigo NIP'            => $active ? $value->nip : '',
                    'Fecha de vigencia'     => $active ? Carbon::parse($value->vigencia_nip)->format('Y-m-d') : '',
                    'ID Tienda'             => $active ? $value->tienda_id : '',
                    'Unidad'                => $active ? $value->unidad : '',
                    'Municipio'             => $active ? $value->municipio : '',
                    'Estado'                => $active ? $value->estado : '',
                    'Numero de contacto'    => $active ? $value->telefono_contacto : '',
                    'Hora de cita'          => $active ? Carbon::parse($value->fecha_cita)->format('H:i:s') : '',
                    'FVC'                   => $active ? $value->fvc : '',
                    'Modalidad'             => $active ? $value->modalidad : '',
                    'Nombre y Apellido'     => $active ? $value->cliente : '',
                    'Operador_discovery'    => $active ? $value->empleado : '',
                    'Supervisor_discovery'  => $active ? $value->supervisor : '',
                    'Coordinador_discovery' => $active ? $value->coordinador : '',
                    'Vendedor respond'      => $active ? $value->responduser : '',
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

    ///devuelve un array json con todos usando el estado como llave principal agrupando pro municipio las tiendas y sus id
    public function SendEstadosBait(Request $request)
    {
        // Se quitó limit(50) para asegurar que se agrupen TODAS las tiendas correctamente
        $tiendas = BaitTiendas::with('TiendaPerteneceAMunicipio', 'MunicipioPerteneceAEstado')
            ->where('active', 1)
            ->orderby('municipio_id', 'asc')
            ->cursor();

        $datos = [];

        // Primero construimos todo el arreglo agrupado
        foreach ($tiendas as $tienda) {
            $municipio = $tienda->TiendaPerteneceAMunicipio;
            $estado = $municipio->MunicipioPerteneceAEstado;

            $datos[$estado->estado][$municipio->municipio][] = array(
                'id_tienda' => $tienda->id,
                'direccion' => $tienda->direccion
            );
        }

        // Finalmente retornamos el objeto JSON completo ya agrupado
        return response()->json([$datos], 200, [
            'Cache-Control' => 'no-cache',
            'Connection' => 'keep-alive',
        ]);
    }

    public function StoreVentas(Request $request)
    {
        Log::info("Solicitud recibida en StoreVentas", [
            'Data' => $request->all()
        ]);
        // Limpiar el nombre_apellido tal como en JS: Mayúsculas y solo letras A-Z y espacios
        if ($request->has('nombre_apellido')) {
            $nombreLimpio = strtoupper($request->input('nombre_apellido'));
            $nombreLimpio = preg_replace('/[^A-Z\s]/', '', $nombreLimpio);
            $request->merge(['nombre_apellido' => $nombreLimpio]);
        }
        /// validamos los datos recibidos
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'idcontacto'            => 'required|max:10',
            'numero_portabilidad'   => 'required|max:10|min:10',
            'nombre_apellido'       => 'required',
            'fecha_nacimiento'      => 'required|date_format:d/m/Y',
            'genero'                => 'required|in:M,F',
            'imei'                  => 'required|digits:15',
            'codigo_nip'            => 'required|digits:4',
            'fecha_vigencia'        => 'required|date_format:d/m/Y',
            'correo_electronico'    => 'required|email',
            'telefono_contacto'     => 'required',
            'fvc'                   => 'required|in:24,48',
            'modalidad'             => 'required|in:CPP',
            'fecha_cita'            => 'required|date_format:d/m/Y H:i:s',
            'observaciones'         => 'nullable',
            'gestion'               => 'required|in:5',
            'tienda'                => 'required|exists:bait_tiendas,id',
        ], [
            'required'    => 'El campo :attribute es obligatorio.',
            'max'         => 'El campo :attribute no debe ser mayor a :max caracteres.',
            'min'         => 'El campo :attribute debe tener al menos :min caracteres.',
            'date_format' => 'El campo :attribute no corresponde con el formato :format.',
            'in'          => 'El valor seleccionado para :attribute es inválido.',
            'digits'      => 'El campo :attribute debe ser numérico y contener exactamente :digits dígitos.',
            'email'       => 'El campo :attribute debe ser una dirección de correo válida.',
            'exists'      => 'La tienda seleccionada no existe.',
        ], [
            'idcontacto'            => 'Identificador de contacto Respond.io',
            'numero_portabilidad'   => 'Número de portabilidad',
            'nombre_apellido'       => 'Nombre y Apellido',
            'fecha_nacimiento'      => 'Fecha de Nacimiento',
            'genero'                => 'Género',
            'imei'                  => 'IMEI',
            'codigo_nip'            => 'Codigo NIP',
            'fecha_vigencia'        => 'Fecha de vigencia',
            'correo_electronico'    => 'Correo electronico',
            'telefono_contacto'     => 'Telefono de contacto',
            'fvc'                   => 'FVC',
            'modalidad'             => 'Modalidad',
            'fecha_cita'            => 'Fecha de cita',
            'observaciones'         => 'Observaciones',
            'gestion'               => 'Gestion',
            'tienda'                => 'Tienda',
        ]);
        //retorna los fails
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $user =  $request->user();
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
            $venta->fecha_cita              = Carbon::createFromFormat('d/m/Y H:i:s', $request->fecha_cita)->format('Y-m-d H:i:s');
            $venta->observaciones           = $request->observaciones;
            $venta->grupo_gestion           = $request->gestion;

            if (BaitRespondio::where('idcontacto', $request->idcontacto)->latest()->limit(1)->exists()) {
                $venta->ciclo_vida = BaitRespondio::where('idcontacto', $request->idcontacto)->orderby('created_at', 'DESC')->limit(1)->first()->ciclo_de_vida;
            } else {
                $venta->ciclo_vida = null;
            }

            if ($user && $user->personal !== null) {
                $supervisor     = $user->personal->jefe_inmediato_id;
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
            $venta->user_id                 = $user ? $user->id : null;
            $venta->estatus_id              = 1;
            $venta->save();

            $historico = new BaitHistoricos();
            $historico->bait_ventas_id      = $venta->id;
            $historico->estatus_id          = 1;
            $historico->usuario             = $user ? $user->nombre_apellido : 'API (Sin autenticación)';
            $historico->observaciones       = $request->observaciones !== null ? $request->observaciones : "Venta registrada";
            $historico->save();

            return response()->json([
                'successVentas' => 'Venta registrada exitosamente | ID Venta: |' . $venta->id . '|'
            ], 200, [
                'Cache-Control' => 'no-cache',
                'Connection' => 'keep-alive',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'errorVentas' => 'Error al registrar la venta' . $e->getMessage() . '| Linea: ' . $e->getLine() . '| File: ' . $e->getFile()
            ], 500, [
                'Cache-Control' => 'no-cache',
                'Connection' => 'keep-alive',
            ]);
        }
    }

    public function updateVenta(Request $request)
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'id'            => 'required|exists:bait_ventas,id|regex:/^[0-9]+$/',
            'folio_venta'   => 'required|min:10|max:20|regex:/^[0-9]+$/',
        ], [
            'required'  => 'El campo :attribute es obligatorio.',
            'exists'    => 'La venta seleccionada no existe.',
            'max'       => 'El campo :attribute no debe ser mayor a :max caracteres.',
            'min'       => 'El campo :attribute debe tener al menos :min caracteres.',
            'regex'     => 'El campo :attribute debe ser solo numérico.',

        ], [
            'id'            => 'Venta',
            'folio_venta'   => 'Folio Venta',
        ]);

        //retorna los fails
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }
        $observaciones = "Venta Ingresada a Intelix";
        $estatus_intelix = "Ingresada";

        $user = $request->user() ?? null;

        $venta = BaitVentas::where('id', $request->id)->where('estatus_id', 1)->first();

        if ($venta) {
            $historico = new BaitHistoricos();
            $historico->bait_ventas_id      = $venta->id;
            $historico->estatus_id          = 2;
            $historico->usuario             = $user ? $user->nombre_apellido : 'API (Sin autenticación)';
            $historico->observaciones       = $observaciones;
            $historico->save();

            $venta->folio_venta             = $request->folio_venta;
            $venta->backoffice_acargo       = $user ? $user->nombre_apellido : 'API (Sin autenticación)';
            $venta->estatus_intelix         = strtoupper($estatus_intelix);
            $venta->estatus_id              = 2;
            $venta->save();

            return response()->json([
                'successVentas' => 'Venta Actualizada exitosamente'
            ], 200, [
                'Cache-Control' => 'no-cache',
                'Connection' => 'keep-alive',
            ]);
        } else {
            return response()->json([
                'errorVentas' => 'Registro de Venta ya se encuentra Ya Ingresada o no existe'
            ], 400, [
                'Cache-Control' => 'no-cache',
                'Connection' => 'keep-alive',
            ]);
        }
    }
}
