<?php

namespace App\Imports\Concentra;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Models\User;
use App\Models\Personal;
use App\Models\Campania;
use App\Models\Cargo;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\Importable;
use Illuminate\Support\Facades\Session;
use App\Models\Concentra\VentaConcentra;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class VentasImport implements ToCollection, WithHeadingRow, WithValidation, WithChunkReading {

    use Importable;

    /**
     * @param Collection $collection
     */
      public function chunkSize(): int {
        return 1500;
    }
    
    public function collection(Collection $rows) {
        
        //$i = 1;
        foreach ($rows as $row) {
            
        $pmxVenta = new VentaConcentra();
        $pmxVenta->dn = $row['dn'];
        $pmxVenta->tipificacion_id = 2;
        $pmxVenta->recarga = $row['recarga'];
        $pmxVenta->fecha_venta = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject(trim($row['fecha_venta']))->format('Y-m-d');

        $pmxPersonal = Personal::where('login_telefonico', $row['usuario_tlmk'])->first();
        $pmxVenta->supervisor_id = $pmxPersonal->jefe_inmediato_id;
        $pmxVenta->personal_id = $pmxPersonal->id;
        $pmxVenta->numero_empleado = $pmxPersonal->numero_empleado;
        $pmxVenta->nombre_apellido = Auth::user()->nombre_apellido;
    
        
        $pmxVenta->save();


        }
    }

    public function rules(): array {
        return [
            'dn' => function($attribute, $value, $onFailure) {
                if (strlen($value) > 10) {
                    $onFailure('El dn no puede tener mas de 10 digitos.');
                }elseif(!ctype_digit($value)){
                    $onFailure('El dn no puede contener letras.');
                }
            },
            'usuario_tlmk' => function($attribute, $value, $onFailure) {
                if (!Personal::where('login_telefonico', '=', trim($value))->exists()) {
                    $onFailure('El usuario telemarketing no existe, verifique sus datos nuevamente.');
                }
            }
        ];
    }

}
