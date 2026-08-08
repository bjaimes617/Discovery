<?php

namespace App\Imports\Bait;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

use PhpOffice\PhpSpreadsheet\Shared\Date;

use App\Models\bait\BaitVentas;
use App\Models\bait\BaitHistoricos;

use Carbon\Carbon;

class ChangePaymentsBait implements ToCollection, WithHeadingRow, WithValidation
{
    public function __construct() {}

    public function chunkSize(): int
    {
        return 100;
    }
    public function collection(Collection $collection)
    {
        $portabilidad = $collection->pluck('dn')->toArray();

        $ventas = BaitVentas::whereIn('numero_portar', $portabilidad)
            ->where('estatus_id', '!=', 12)
            ->orderBy('id', 'ASC')
            ->pluck('id', 'numero_portar');

        $Update = array();
        $insertHistorico = array();

        foreach ($collection as $seguimiento) {
            if (isset($ventas[$seguimiento["dn"]])) {
                $estatus = 12;
                $observaciones =  "Venta Paga el al Agente: " . Date::excelToDateTimeObject(trim($seguimiento["fecha_de_pago"]))->format('Y-m-d');

                $Update[] = [
                    "id"                  => $ventas[$seguimiento["dn"]],
                    "estatus_id"          => $estatus,
                    'pagada_el'           => Date::excelToDateTimeObject(trim($seguimiento["fecha_de_pago"]))->format('Y-m-d'),
                    'updated_at'          => Carbon::now()
                ];

                $insertHistorico[] = [
                    "bait_ventas_id" => $ventas[$seguimiento["dn"]],
                    "estatus_id"        => $estatus,
                    "pagada_el"         => Date::excelToDateTimeObject(trim($seguimiento["fecha_de_pago"]))->format('Y-m-d'),
                    "observaciones"     => "Registro de Pago realizado el: " . Carbon::now() . " | Observaciones: " . $observaciones,
                    "usuario"           => Auth::user()->nombre_apellido,
                    "created_at"        => Carbon::now(),
                    "updated_at"        => Carbon::now()
                ];
            }
        }

        if (count($Update) > 0) {
            foreach ($Update as $venta) {
                BaitVentas::where('id', $venta['id'])->update([
                    "estatus_id"        => $venta['estatus_id'],
                    "pagada_el"         => $venta['pagada_el'],
                    "updated_at"        => $venta['updated_at']
                ]);
            }
        }

        if (count($insertHistorico) > 0) {
            BaitHistoricos::insert($insertHistorico);
        }

        return true;
    }

    public function rules(): array
    {
        return [
            "dn" => function ($attribute, $value, $fail) {
                if (trim($value) == "") {
                    $fail("El Numero a Portar es obligatorio");
                } else if (strlen(trim($value)) > 10) {
                    $fail("El Numero a Portar debe tener 10 digitos");
                }
            },
            "fecha_de_pago" => function ($attribute, $value, $fail) {
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
