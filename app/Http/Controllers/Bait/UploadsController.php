<?php

namespace App\Http\Controllers\Bait;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Imports\Bait\UploadBaitCM;
use App\Imports\Bait\SeguimientosBackoffice;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\bait\BaitVentas;
use App\Models\bait\BaitHistoricos;
use App\Models\bait\BaitRespondio;
use Carbon\Carbon;
use App\Exports\Bait\BaitExport;
use App\Exports\Bait\RespondioExport;


class UploadsController extends Controller
{
    private $typeReportes;

    public function __construct()
    {
        $this->typeReportes = ["general" => "Discovery - Tipificaciones Generales", "ventas" => "Discovery - Ventas Unicas", "respondio" => "Respond.io - Historico de Ciclos de vida"];
    }
    public function IndexCMConcentra()
    {
        return view('bait.uploads.index');
    }

    public function StoreCMConcentra(Request $request)
    {
        try {
            $import = new UploadBaitCM();
            Excel::import($import, $request->file('archivo'));
            $checks = $import->getCheckVentaBO();

            if (is_array($checks) && count($checks) > 0) {
                return redirect()->back()->with('warningVentas', $checks);
            } else {
                return redirect()->back()->with('successVentas', 'Procesamiento del Archivo completado con éxito');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('errorVentas', 'Error al procesar el archivo: ' . $e->getMessage());
        }
    }

    public function IndexSeguimientosMasivos()
    {
        return view('bait.uploads.seguimientos');
    }

    public function UploadSeguimientosMasivos(Request $request)
    {
        try {
            Excel::import(new SeguimientosBackoffice, $request->file('archivo'));
            return redirect()->back()->with('successVentas', 'Procesamiento del Archivo completado con éxito');
        } catch (\Illuminate\Validation\ValidationException $e) {
            $failures = $e->failures();

            $errors = [];
            foreach ($failures as $failure) {
                $row = $failure->row();
                $error = $failure->errors()[0] ?? '';
                $errors[] = "Fila {$row}: {$error}";
            }
            return redirect()->back()->withErrors($errors);
        }
    }

    public function IndexReportes()
    {
        return view('bait.reportes.index')->with(["reportes" => $this->typeReportes]);
    }

    public function DownloadReportes(Request $request)
    {

        $fecha = explode("-", $request->fecha);
        $init = Carbon::createfromFormat('d/m/Y', trim($fecha[0]))->startOfDay();
        $end = Carbon::createfromFormat('d/m/Y', trim($fecha[1]))->endOfDay();

        switch ($request->reporte) {
            case 'general':
                $datos = BaitVentas::with('relationHistorico', 'relationTienda')->whereBetween('created_at', [$init->copy()->format('Y-m-d H:m:s'), $end->copy()->format('Y-m-d H:m:s')])->cursor();

                return Excel::download(new BaitExport($datos, true), 'Reporte de Tipificaciones ' . $init->copy()->format('d-m-Y') . ' al ' . $end->copy()->format('d-m-Y') . '.xlsx');
                break;
            case 'ventas':
                $datos = BaitVentas::with('relationTienda')
                    ->whereBetween('created_at', [$init->copy()->format('Y-m-d H:m:s'), $end->copy()->format('Y-m-d H:m:s')])
                    ->cursor();
                return Excel::download(new BaitExport($datos, false), 'Reporte de Ventas ' . $init->copy()->format('d-m-Y') . ' al ' . $end->copy()->format('d-m-Y') . '.xlsx');
                break;
            case 'respondio':
                $datos = BaitRespondio::select(
                    'bait_respondio.created_at',
                    'bait_respondio.usuario',
                    'bait_respondio.idcontacto',
                    'bait_respondio.ciclo_de_vida',
                    'bait_respondio.anuncio',
                    'bait_ventas.numero_portar',
                    'bait_ventas.nombre_apellido',
                    'bait_ventas.fecha_nacimiento',
                    'bait_ventas.genero',
                    'bait_ventas.imei',
                    'bait_ventas.nip',
                    'bait_ventas.vigencia_nip',
                    'bait_ventas.email',
                    'bait_ventas.tienda_id',
                    'bait_ventas.telefono_contacto',
                    'bait_ventas.telefono_principal',
                    'bait_ventas.fecha_cita',
                    'bait_ventas.fvc',
                    'bait_ventas.modalidad',
                    'bait_ventas.sns',
                    'bait_ventas.backoffice_acargo',
                    'bait_ventas.bait_concentra_id',
                    'bait_ventas.estatus_intelix',
                    'bait_ventas.estatus_backoffice',
                    'bait_ventas.validador_alta',
                    'bait_ventas.personal_id',
                    'bait_ventas.supervisor_id',
                    'bait_ventas.coordinador_id'
                )->leftjoin('bait_ventas', 'bait_respondio.idcontacto', 'bait_ventas.idcontacto')
                    ->whereBetween('bait_respondio.created_at', [
                        $init->copy()->format('Y-m-d H:m:s'),
                        $end->copy()->format('Y-m-d H:m:s')
                    ])->get();
                //dd($datos);

                return Excel::download(new RespondioExport($datos), 'Reporte de Ventas ' . $init->copy()->format('d-m-Y') . ' al ' . $end->copy()->format('d-m-Y') . '.xlsx');
                break;
            default:
                return redirect()->back()->with('error', 'Reporte no encontrado');
                break;
        }
    }
}
