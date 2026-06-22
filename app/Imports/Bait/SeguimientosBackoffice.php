<?php

namespace App\Imports\Bait;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithValidation;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use App\Models\bait\BaitVentas;
use App\Models\bait\BaitRespondio;
use App\Models\bait\BaitHistoricos;
use App\Models\bait\BaitEstatusConcentra;
use App\Models\bait\BaitEstatusIntelix;
use App\Models\bait\BaitEstatus;

use App\Models\Personal;
use Carbon\Carbon;


class SeguimientosBackoffice implements ToCollection, WithHeadingRow, WithChunkReading, WithValidation
{

    private $sns, $validatebo, $validacionalta, $concentra, $estatusIntelix, $estatusfinal;

    public function __construct()
    {
        $this->sns              = ["NO HA PERDIDO SEÑAL", "WALMART"];
        $this->validatebo       = ["ALTA", "FVC", "RECHAZO", "PENDIENTE"];
        $this->validacionalta   = ["LLAMADA", "RESPOND", "CONCENTRA"];
        $this->estatusIntelix   = array_map('strtoupper', BaitEstatusIntelix::all()->pluck('descripcion')->toArray());
        $this->concentra        = array_map('strtoupper', BaitEstatusConcentra::all()->pluck('descripcion', 'id')->toArray());
        $this->estatusfinal     = array_map('strtoupper', BaitEstatus::all()->pluck('descripcion', 'id')->toArray());
    }

    public function chunkSize(): int
    {
        return 500;
    }

    public function collection(Collection $collection)
    {
        foreach ($collection as $item) {
            $fechaventa = Date::excelToDateTimeObject(trim($item['fecha_venta']))->format('d-m-Y');
            $backoffice = personal::where('numero_empleado', trim($item['backoffice']))->first();

            if ($backoffice) {
                $backoffice = $backoffice->RelationUser->nombre_apellido;
            } else {
                $backoffice = null;
            }
            // dd(array_search(strtoupper(trim($item['estatus_final'])), $this->estatusfinal));
            $registroventa = BaitVentas::whereRaw('DATE_FORMAT(created_at, "%d-%m-%Y") = ?', $fechaventa)->where('numero_portar', $item['numero_portabildiad'])->where('idcontacto', '=', trim($item['id_contacto']))->first();

            if ($registroventa) {
                $registroventa->sns                 = strtoupper(trim($item['sns']));
                $registroventa->estatus_intelix     = strtoupper(trim($item['intelix']));
                $registroventa->backoffice_acargo   = $backoffice;
                $registroventa->estatus_backoffice  = strtoupper(trim($item['bo']));
                $registroventa->bait_concentra_id   = array_search(strtoupper(trim($item['concentra'])), $this->concentra);
                $registroventa->validador_alta      = strtoupper(trim($item['validacion_alta']));
                $registroventa->estatus_id          = array_search(strtoupper(trim($item['estatus_final'])), $this->estatusfinal);
                $registroventa->save();

                $historico = new BaitHistoricos();
                $historico->bait_ventas_id      = $registroventa->id;
                $historico->estatus_id          = $registroventa->estatus_id;
                $historico->usuario             = $backoffice;
                $historico->estatus_intelix     = $registroventa->estatus_intelix;
                $historico->sns                 = $registroventa->sns;
                $historico->estatus_backoffice  = $registroventa->estatus_backoffice;
                $historico->validador_alta      = $registroventa->validador_alta;
                $historico->bait_concentra_id   = $registroventa->bait_concentra_id;
                $historico->observaciones       = "Actualizacion de Estatus del Registro de la Venta realizada de forma Masiva.";
                $historico->save();
            }
        }
        return true;
    }

    public function rules(): array
    {
        return [
            'fecha_venta' => function ($attribute, $value, $onFailure) {
                if (trim($value) == "") {
                    $onFailure('La fecha de venta es obligatoria.');
                } else {
                    if (!Date::excelToDateTimeObject(trim($value))) {
                        $onFailure('La fecha de venta no es valida, el Formato debe ser D/M/Y (4/12/2026) o Y-m-d (2026-12-04)');
                    }
                }
            },
            'numero_portabildiad' => function ($attribute, $value, $onFailure) {
                if (trim($value) == "") {
                    $onFailure('El Numero de Portabilidad es obligatorio');
                } else {
                    if (!is_numeric($value)) {
                        $onFailure('El numero de portabilidad no es valido');
                    }
                    if (strlen($value) != 10) {
                        $onFailure('El numero de portabilidad debe tener 10 digitos');
                    }
                }
            },
            'id_contacto' => function ($attribute, $value, $onFailure) {
                if (trim($value) == "") {
                    $onFailure('El Id Contacto es obligatorio');
                } else {
                    if (strlen($value) > 10) {
                        $onFailure('El Id Contacto no puede superar los 10 caracteres');
                    }
                }
            },
            'sns' => function ($attribute, $value, $onFailure) {
                if (trim($value) !== "") {
                    if (!in_array(strtoupper(trim($value)), $this->sns)) {
                        $onFailure('El SNS no es valido');
                    }
                }
            },
            'concentra' => function ($attribute, $value, $onFailure) {
                if (trim($value) == "") {
                    $onFailure('El estatus Concentra es obligatorio');
                } else {
                    if (!in_array(strtoupper(trim($value)), $this->concentra)) {
                        $onFailure('El Estatus Concentra no es valido');
                    }
                }
            },
            'intelix' => function ($attribute, $value, $onFailure) {
                if (trim($value) == "") {
                    $onFailure('El estatus Intelix es obligatorio');
                } else {
                    if (!in_array(strtoupper(trim($value)), $this->estatusIntelix)) {
                        $onFailure('El Estatus Intelix no es valido');
                    }
                }
            },
            'bo' => function ($attribute, $value, $onFailure) {
                if (trim($value) == "") {
                    $onFailure('El estatus BO es obligatorio');
                } else {
                    if (!in_array(strtoupper(trim($value)), $this->validatebo)) {
                        $onFailure('El Estatus BO ingresado no es valido');
                    }
                }
            },
            'backoffice' => function ($attribute, $value, $onFailure) {
                if (trim($value) == "") {
                    $onFailure('La cedula del backoffice es obligatoria');
                } else {
                    if (!Personal::query()->where('numero_empleado', trim($value))->exists()) {
                        $onFailure('La cedula del backoffice no existe');
                    }
                }
            },
            'validacion_alta' => function ($attribute, $value, $onFailure) {
                if (trim($value) == "") {
                    $onFailure('La validacion alta es obligatoria');
                } else {
                    if (!in_array(strtoupper(trim($value)), $this->validacionalta)) {
                        $onFailure('La validacion alta ingresada no es valida');
                    }
                }
            },
            'estatus_final' => function ($attribute, $value, $onFailure) {
                if (trim($value) == "") {
                    $onFailure('El estatus final es obligatorio');
                } else {
                    if (!in_array(strtoupper(trim($value)), $this->estatusfinal)) {
                        $onFailure('El estatus final ingresado no es valido');
                    }
                }
            },
        ];
    }
}
