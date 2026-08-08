<?php

namespace App\Imports\Renovaciones;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\DataAwareRule;

use Maatwebsite\Excel\Concerns\ToCollection;
use App\Models\renovaciones\renovacionesEstatusModel;
use App\Models\renovaciones\renovacionesHistoricoModel;
use App\Models\renovaciones\renovacionesObservacionesModel;
use App\Models\renovaciones\renovacionesVentasModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use PhpOffice\PhpSpreadsheet\Shared\Date;

use Carbon\Carbon;

class ChangeSalesPayment implements ToCollection, WithHeadingRow, WithValidation
{

    public function __construct() {}

    public function chunkSize(): int
    {
        return 100;
    }

    public function collection(Collection $collection)
    {

        $dn = $collection->pluck('dn')->toArray();

        $ventas    = renovacionesVentasModel::whereIn('dn', $dn)
            ->where('estatus_id', '!=', 11)
            ->pluck('id', 'dn');

        $Update = array();
        $insertHistorico = array();
        $noregistrados = array();

        foreach ($collection as $seguimiento) {
            if (isset($ventas[$seguimiento["dn"]])) {
                $estatus = 11;
                $observaciones =  "Venta Paga el al Agente: " . Date::excelToDateTimeObject(trim($seguimiento["pagada_el"]))->format('d/m/Y');

                $Update[] = [
                    "id"                  => $ventas[$seguimiento["dn"]],
                    "estatus_id"          => $estatus,
                    "pagable" => 0,
                    'pagada_el'           => Date::excelToDateTimeObject(trim($seguimiento["pagada_el"]))->format('Y-m-d'),
                    'updated_at'          => Carbon::now()
                ];

                $insertHistorico[] = [
                    "renovaciones_ventas_id" => $ventas[$seguimiento["dn"]],
                    "estatus_id"        => $estatus,
                    "pagada_el"         => Date::excelToDateTimeObject(trim($seguimiento["pagada_el"]))->format('Y-m-d'),
                    "observaciones"     => "Registro de Pago realizado el: " . Carbon::now() . " | Observaciones: " . $observaciones,
                    "usuario"           => Auth::user()->nombre_apellido,
                    "pagable"           => 0,
                    "created_at"        => Carbon::now(),
                    "updated_at"        => Carbon::now()
                ];
            }
        }

        if (count($Update) > 0) {
            foreach ($Update as $venta) {
                renovacionesVentasModel::where('id', $venta['id'])->update([
                    "estatus_id"        => $venta['estatus_id'],
                    "pagable"           => $venta['pagable'],
                    "pagada_el"         => $venta['pagada_el'],
                    "updated_at"        => $venta['updated_at']
                ]);
            }
        }

        if (count($insertHistorico) > 0) {
            renovacionesHistoricoModel::insert($insertHistorico);
        }

        return true;
    }

    public function rules(): array
    {
        return [
            "dn" => function ($attribute, $value, $fail) {
                if (trim($value) == "") {
                    $fail("El DN es obligatorio");
                } else if (strlen(trim($value)) > 10) {
                    $fail("El DN debe tener 10 digitos");
                }
            },
            "pagada_el" => function ($attribute, $value, $fail) {
                if (trim($value) == "") {
                    $fail("La fecha de pago es obligatoria");
                }

                if (!is_numeric($value) || !Date::excelToDateTimeObject(trim($value))) {
                    $fail("La fecha de pago debe ser una fecha valida");
                }
            },
        ];
    }
}
