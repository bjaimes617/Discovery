<?php

namespace App\Imports\Bait;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\Importable;

use App\Models\bait\BaitVentas;
use App\Models\bait\BaitRespondio;
use App\Models\bait\BaitHistoricos;
use App\Models\bait\BaitEstatus;
use App\Models\bait\BaitEstatusConcentra;
use App\Models\bait\BaitEstatusIntelix;

use Carbon\Carbon;

class UploadBaitCM implements ToCollection, WithHeadingRow, WithChunkReading
{
    private $sns, $validatebo, $validacionalta, $estatusintelix, $CheckVentaBO;

    use Importable;

    public function __construct()
    {
        $this->sns = config('app.sns');
        $this->validatebo = config('app.validatebo');
        $this->validacionalta = config('app.validacionalta');
        $this->estatusintelix = BaitEstatusIntelix::where('grupo', 'b')->where('active', 1)->pluck('descripcion', 'id');
        $this->CheckVentaBO = array();
    }
    public function chunkSize(): int
    {
        return 500;
    }

    public function collection(Collection $collection)
    {
        $updateVentas = array();
        $insertHistorico = array();

        $datefinish = Carbon::now()->endOfDay();
        $dateInit = $datefinish->copy()->subMonth(3)->startOfMonth()->startOfDay();

        $portablidades      = $collection->pluck('dn');

        $ventasRegistradas  = BaitVentas::whereBetween('created_at', [$dateInit, $datefinish])->wherenotin('estatus_id', [11])->wherein('numero_portar', $portablidades)->pluck('id', 'numero_portar');

        foreach ($collection as $colets) {
            $estatus = $this->AsignacionEstatus($colets);
            if ($estatus !== false) {
                if (isset($ventasRegistradas[$colets["dn"]])) {
                    $updateVentas[] = [
                        "id"                => $ventasRegistradas[$colets["dn"]],
                        "estatus_id"        => $estatus["estatus_final"],
                        "sns"               => $estatus["sns"],
                        "estatus_intelix"   => $estatus["intelix"],
                        "estatus_backoffice" => $estatus["bo"],
                        "validador_alta"    => $estatus["validacion_alta"],
                        "bait_concentra_id" => $estatus["concentra"],
                        "updated_at"        => Carbon::now()->endOfDay()
                    ];

                    $insertHistorico[] = [
                        "bait_ventas_id"    => $ventasRegistradas[$colets["dn"]],
                        "estatus_id"        => $estatus["estatus_final"],
                        "sns"               => $estatus["sns"],
                        "estatus_intelix"   => $estatus["intelix"],
                        "estatus_backoffice" => $estatus["bo"],
                        "validador_alta"    => $estatus["validacion_alta"],
                        "bait_concentra_id" => $estatus["concentra"],
                        "usuario"           => Auth::user()->nombre_apellido,
                        "observaciones"     => "Informacion Recibida por CM: USO -> " . $colets["uso"] . " y PORT_IN :" . $colets["port_in"] . ".",
                        "created_at"        => Carbon::now(),
                        "updated_at"        => Carbon::now()
                    ];
                }
            }
        }

        if (count($updateVentas) > 0) {
            foreach ($updateVentas as $venta) {
                BaitVentas::where('id', $venta['id'])->update([
                    "estatus_id"        => $venta['estatus_id'],
                    "sns"               => $venta['sns'],
                    "estatus_intelix"   => $venta['estatus_intelix'],
                    "estatus_backoffice" => $venta['estatus_backoffice'],
                    "validador_alta"    => $venta['validador_alta'],
                    "bait_concentra_id" => $venta['bait_concentra_id'],
                    "updated_at"        => $venta['updated_at']
                ]);
            }
        }

        if (count($insertHistorico) > 0) {
            BaitHistoricos::insert($insertHistorico);
        }
        return true;
    }


    public function getCheckVentaBO()
    {
        return $this->CheckVentaBO;
    }

    private function AsignacionEstatus($registro)
    {
        $estatusxasignar = array();
        switch ($registro["estatus_abd"]) {
            case "Exitosa":
                if ($registro["port_in"] == 1 && $registro["uso"] == 1) {
                    $estatusxasignar = [
                        "concentra" => 1, #alta
                        "sns"       => $this->sns[1], #walmart
                        "intelix"   => $this->estatusintelix[9], #Alta
                        "bo"        => $this->validatebo[0], #alta
                        "estatus_final" => 11, #alta uso
                        "validacion_alta" => $this->validacionalta[2] #concentra
                    ];
                } else if ($registro["port_in"] == 0 && $registro["uso"] == 0) {
                    $estatusxasignar = [
                        "concentra" => 3, #Exitosa
                        "sns"       => $this->sns[1], #walmart
                        "intelix"   => $this->estatusintelix[9], #Alta
                        "bo"        => $this->validatebo[3], #PENDIENTE
                        "estatus_final" => 7, #PENDIENTE
                        "validacion_alta" => $this->validacionalta[2] #concentra
                    ];
                } else if ($registro["port_in"] == 1 && $registro["uso"] == 0) {
                    $estatusxasignar = [
                        "concentra" => 5, #FVC
                        "sns"       => $this->sns[1], #walmart
                        "intelix"   => $this->estatusintelix[9], #Alta
                        "bo"        => $this->validatebo[0], #alta
                        "estatus_final" => 11, #alta uso
                        "validacion_alta" => $this->validacionalta[2] #concentra
                    ];
                } else if ($registro["port_in"] == 0 && $registro["uso"] == 1) {


                    $revisarBo = "Atencion, Registro Recibido en BD con estatus " . $registro["estatus_abd"] . ", DN: " . $registro["dn"] . " USO: " . $registro["uso"] . " PORT_IN: " . $registro["port_in"];

                    array_push($this->CheckVentaBO, $revisarBo);
                    $estatusxasignar = false;
                }
                break;
            case "Fallida":
            case "Irrecuperable":
                $estatusxasignar = [
                    "concentra" => 2, #rechazo
                    "sns"       => $this->sns[0], #NO HA PERDIDO SEÑAL
                    "intelix"   => $this->estatusintelix[10], #rechazada
                    "bo"        => $this->validatebo[2], #rechazo
                    "estatus_final" => 6, #rechazo
                    "validacion_alta" => $this->validacionalta[2] #concentra
                ];
                break;
            case "Estatus pendiente":
                $estatusxasignar = [
                    "concentra" => 4, #pendiente
                    "sns"       => $this->sns[0], #NO HA PERDIDO SEÑAL
                    "intelix"   => $this->estatusintelix[14], #pendiente
                    "bo"        => $this->validatebo[3], #pendiente
                    "estatus_final" => 7, #pendiente
                    "validacion_alta" => $this->validacionalta[2] #concentra
                ];
                break;
        }

        return $estatusxasignar;
    }
}
