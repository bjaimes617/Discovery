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


class RegistroVentasEmergencia implements ToCollection, WithHeadingRow, WithChunkReading, WithValidation
{

    private $sns, $validatebo, $validacionalta, $concentra, $estatusIntelix, $estatusfinal;

    public function __construct()
    {
       
    }

    public function chunkSize(): int
    {
        return 500;
    }

    public function collection(Collection $collection)
    {
        foreach ($collection as $item) {
          

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
        
        return true;
    }

    public function rules(): array
    {
        return [
           
        ];
    }
}
