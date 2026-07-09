<?php

namespace App\Exports\Renovaciones;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use App\Models\renovaciones\renovacionesEstatusModel;
use App\Models\renovaciones\renovacionesObservacionesModel;
use Carbon\Carbon;

class renovacionesExport implements ShouldAutoSize, FromCollection, WithHeadings, WithMapping, WithChunkReading
{
     protected $datos, $activeHistorico;

    public function __construct($datos, $activeHistorico)
    {
        $this->datos = $datos;
        $this->activeHistorico = $activeHistorico;
    }

    public function chunkSize(): int
    {
        return 500;
    }

    public function startRow(): int
    {
        return 2;
    }

    public function headings(): array
    {
        $cabezera = [
                'Fecha y Hora',
                'Nombre del Ejecutivo',
                'Cedula del Ejecutivo',
                'DN',
                'Nombre del Cliente',
                'Equipo',
                'Plazo',
                'Entrega en',
                'N° de Orden Onix (Magento)',
                'Usuario de Conexión',
                'Precio del Equipo',
                'Dirección de Entrega',
                'Entre Calles',
                'Referencias',
                'Fecha',
                'Semana',
                'Nombre del Ejecutivo',
                'Cedula del Ejecutivo',
                'Intervalo',
                'Estatus',
                'Observaciones',
                'Latitud Direccion',
                'Longitud Direccion',
        ];
        if($this->activeHistorico){
            $historico = [
                'usuario',
                'Estatus',
                'Observaciones',
                'Comentarios',
                'estatus_concentra',
                'llamada_bo',
                'plan_anterior',
                'plan_actual',
                'monto_plan_anterior',
                'monto_plan_actual',                
            ];
            $TotalCabecera = array_merge($cabezera, $historico);
        } else {
            $TotalCabecera = $cabezera;
        }      
        return $TotalCabecera;
    }

     public function prepareRows($datos): array
    {
        return $datos;
    }

     public function map($row): array
    {
          $estatuses = renovacionesEstatusModel::all()->pluck('descripcion', 'id')->toArray();
          $observaciones = renovacionesObservacionesModel::all()->pluck('descripcion', 'id')->toArray();
          $venta = $row['venta'];
          $historico = $row['historico'];
            // 1. Creas la instancia de Carbon con tu fecha
            $fechaVenta = Carbon::create($venta->created_at);

            // 2. Obtienes la hora de inicio (ej. 13:00:00)
            $inicioIntervalo = $fechaVenta->copy()->startOfHour();

            // 3. Obtienes la hora de fin (ej. 14:00:00)
            $finIntervalo = $inicioIntervalo->copy()->addHour();

            // 4. Formateas el resultado como lo necesites (ej. "13:00 - 14:00")
            $intervaloTexto = $inicioIntervalo->format('H:i') . ' a ' . $finIntervalo->format('H:i'); 
        return [
            Carbon::create($venta->created_at)->format('d/m/Y H:i:s'),
            strtoupper($venta->RelationUser->nombre_apellido),
            $venta->RelationPersonal != null ? $venta->RelationPersonal->numero_empleado: null,
            $venta->dn,
            $venta->nombre_cliente,
            strtoupper($venta->equipo),     
            $venta->plazo." Meses",
            $venta->entrega_en,
            $venta->numero_orden_onix,
            $venta->RelationPersonal != null ? $venta->RelationPersonal->in_telefonico: null,
            number_format($venta->precio_equipo, 2),
            $venta->direccion_entrega,
            $venta->entre_calles,
            $venta->referencias,
            Carbon::parse($venta->created_at)->format('d/m/Y'),
            Carbon::parse($venta->created_at)->weekOfYear,
            strtoupper($venta->RelationUser->nombre_apellido),
            $venta->RelationPersonal != null ? $venta->RelationPersonal->numero_empleado: null,
            $intervaloTexto,
            $estatuses[$venta->estatus_id],
            $venta->observaciones_id != null ? $observaciones[$venta->observaciones_id] : null,
            $venta->latitud,
            $venta->longitud,
            $historico && $historico->usuario ? $historico->usuario : "",
            $historico && $historico->estatus_id ? $estatuses[$historico->estatus_id] : "",
            $historico && $historico->observaciones_id ? $observaciones[$historico->observaciones_id] : "",
            $historico && $historico->observaciones ? $historico->observaciones : "",
            $historico && $historico->estatus_concentra ? $historico->estatus_concentra : "",
            $historico && $historico->llamada_bo ? $historico->llamada_bo : "",
            $historico && $historico->plan_anterior ? $historico->plan_anterior : "",
            $historico && $historico->plan_actual ? $historico->plan_actual : "",
            $historico && $historico->monto_plan_anterior ? $historico->monto_plan_anterior : "",
            $historico && $historico->monto_plan_actual ? $historico->monto_plan_actual : "",
         ];
    }

    public function collection()
    {
        $rows = collect();
       if ($this->activeHistorico) {
            foreach ($this->datos as $venta) {
                if ($venta->relationHistorico && $venta->relationHistorico->count() > 0) {
                    foreach ($venta->relationHistorico as $historico) {
                        $rows->push([
                            'venta' => $venta,
                            'historico' => $historico
                        ]);
                    }
                } else {
                    $rows->push([
                        'venta' => $venta,
                        'historico' => null
                    ]);
                }
            }
        } else {
            foreach ($this->datos as $venta) {
                $rows->push([
                    'venta'     => $venta,
                    'historico' => null
                ]);
            }
        }
        return $rows;
    }
}
