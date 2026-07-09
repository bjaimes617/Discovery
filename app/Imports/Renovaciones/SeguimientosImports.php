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


use Carbon\Carbon;
class SeguimientosImports implements ToCollection, WithChunkReading, WithHeadingRow, WithValidation
{
    private $estatus;
            /*array_search(trim(strtoupper($seguimiento["observaciones"])), array_map('strtoupper', $this->observaciones)),
            trim($seguimiento["observaciones"]),
             $this->observaciones*/
    public function __construct()
    {
        $this->estatus = renovacionesEstatusModel::wherenotin('id',[1,2,4])->pluck('descripcion','id')->toArray();
    }
    
    public function chunkSize(): int
    {
        return 500;
    }
    
    public function collection(Collection $collection)
    {
        
        $ordenOnix = $collection->pluck('orden_onix')->toArray();      
        $ventas    = renovacionesVentasModel::whereIn('numero_orden_onix', $ordenOnix)->pluck('id','numero_orden_onix');
        $Update = array();
        $insertHistorico = array();
        $noregistrados = array();

        foreach ($collection as $seguimiento) {         
      
            if(isset($ventas[$seguimiento["orden_onix"]])){
                $estatus =  array_search(strtoupper($seguimiento["estatus"]), $this->estatus);
                $observaciones = $seguimiento["observaciones"] != null ?
                                renovacionesEstatusModel::find($estatus)->RelationObservationes()->where('descripcion', strtoupper($seguimiento["observaciones"]))->first()->id:
                                null;

                $Update[] =[
                    "id"=> $ventas[$seguimiento["orden_onix"]],
                    "estatus_id"=> $estatus,
                    "observaciones_id"=> $observaciones,
                    'onix'=>$seguimiento["orden_onix"],
                    'llamada_bo'=>$seguimiento["llamada_bo"] != null ? strtoupper($seguimiento["llamada_bo"]):null,
                    'plan_anterior'=>$seguimiento["plan_anterior"]  != null ? 
                                     strtoupper($seguimiento["plan_anterior"]):
                                    null,
                    'plan_actual'=>$seguimiento["plan_actual"] != null ? 
                                    strtoupper($seguimiento["plan_actual"]):
                                    null,
                    'monto_plan_anterior'=>$seguimiento["monto_plan_anterior"] != null ? 
                                    $seguimiento["monto_plan_anterior"]:
                                    null,
                    'monto_plan_actual'=>$seguimiento["monto_plan_actual"] != null ? 
                                    $seguimiento["monto_plan_actual"]:
                                    null,  
                    'updated_at'=>Carbon::now()                  
                ];

                $insertHistorico[] = [
                        "renovaciones_ventas_id" => $ventas[$seguimiento["orden_onix"]],
                        "estatus_id"        => $estatus,
                        "observaciones_id"  => $observaciones,
                        "llamada_bo"        => $seguimiento["llamada_bo"] != null ? strtoupper($seguimiento["llamada_bo"]):null,
                        "plan_anterior"     => $seguimiento["plan_anterior"] != null ? strtoupper($seguimiento["plan_anterior"]):null,
                        "plan_actual"       => $seguimiento["plan_actual"] != null ? strtoupper($seguimiento["plan_actual"]):null,
                        "monto_plan_anterior" => $seguimiento["monto_plan_anterior"] != null ? $seguimiento["monto_plan_anterior"]:null,
                        "monto_plan_actual"   => $seguimiento["monto_plan_actual"] != null ? $seguimiento["monto_plan_actual"]:null,
                        "observaciones"     => "Seguimiento realizado el: ". Carbon::now()." | Estatus: ".strtoupper($seguimiento["estatus"]) ." |
                         Observaciones: ".strtoupper($seguimiento["observaciones"]),
                        "usuario"           => Auth::user()->nombre_apellido,
                        "created_at"        => Carbon::now(),
                        "updated_at"        => Carbon::now()
                    ];

            } else {
                $noregistrados[] = [
                    "DN"        =>$seguimiento['dn'],
                    "orden_onix"=>$seguimiento["orden_onix"],
                ];
            }               
        }

        if (count($Update) > 0) {
            foreach ($Update as $venta) {                  
                renovacionesVentasModel::where('id', $venta['id'])->update([
                    "estatus_id"        => $venta['estatus_id'],
                    "observaciones_id"  => $venta['observaciones_id'],
                    "llamada_bo"        => $venta['llamada_bo'],
                    "plan_anterior"     => $venta['plan_anterior'],
                    "plan_actual"       => $venta['plan_actual'],
                    "monto_plan_anterior" => $venta['monto_plan_anterior'],
                    "monto_plan_actual"   => $venta['monto_plan_actual'],
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
            "orden_onix" =>function($attribute, $value, $fail){
                if(trim($value) == ""){
                    $fail("La orden de onix es obligatoria");
                }
            },
            "observaciones" => new class implements ValidationRule, DataAwareRule {
                protected $data = [];
                public function setData(array $data) {
                    $this->data = $data;
                    return $this;
                }
                public function validate(string $attribute, mixed $value, \Closure $fail): void {
                    if (trim($value) == "") {
                        $fail("La observacion es obligatoria");
                        return;
                    }
                    
                    $rowIndex = explode('.', $attribute)[0];
                    $row = $this->data[$rowIndex] ?? [];                    
                    $estatusDescripcion = isset($row['estatus']) ? strtoupper(trim($row['estatus'])) : null;
                    if (!$estatusDescripcion) {
                        return; 
                    }

                    $estatus = renovacionesEstatusModel::where('descripcion', $estatusDescripcion)->first();
                    if (!$estatus) {
                        return;
                    }
                    $observacion = $estatus->RelationObservationes()->where('descripcion', strtoupper(trim($value)))->first();                    
                    if ($observacion == null) {
                        $fail("La observacion suministrada es invalida para el estatus '" . $estatusDescripcion . "'");
                    }
                }
            },
            "estatus" => function($attribute, $value, $fail){
                if(trim($value) == ""){
                    $fail("El estatus es obligatorio");
                }else{
                    $estatus = renovacionesEstatusModel::where('descripcion', strtoupper($value))->first();
                    if($estatus == null){
                        $fail("El estatus suministrado no existe en la base de datos");
                    }
                }
            },
        ];
    }
}
