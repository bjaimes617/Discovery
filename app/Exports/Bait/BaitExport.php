<?php

namespace App\Exports\Bait;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
//use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use App\Models\bait\BaitEstatus;
use App\Models\bait\BaitEstatusConcentra;


class BaitExport implements ShouldAutoSize, FromCollection, WithHeadings, WithMapping, WithChunkReading
{
    protected $datos, $activeHistorico;

    public function __construct($datos, $activeHistorico = false)
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

    public function columnWidths(): array
    {
        return [
            'M' => 20, // Columna A con ancho 20         
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
                    'historico' => $venta->relationHistorico()->orderBy('id', 'desc')->first()
                ]);
            }
        }
        return $rows;
    }

    public function headings(): array
    {
        return [
            "ID CONTACTO",
            "Numero portabilidad",
            "Nombre y Apellido",
            "Fecha de Nacimiento",
            "Genero",
            "IMEI",
            "Codigo NIP",
            "Fecha de vigencia",
            "Correo electronico",
            "ID Tienda",
            "Estado",
            "Municipio",
            "Centro de atencion",
            "Numero de contacto",
            "Telefono",
            "Fecha",
            "Hora de cita",
            "FVC.",
            "Modalidad",
            "Ciclo de vida",
            "Fecha registro",
            "Día",
            "Registro",
            "Intervalo",
            "Semana",
            "Mes",
            "sns",
            "BackOffice A Cargo",
            "Estatus Concentra",
            "Estatus InteLix",
            "Estatus Bo",
            "Validador Alta",
            "CI",
            "Asesor",
            "Supervisor",
            "Coordinador",
            "Estatus Discovery",
            "Histórico Usuario",
            "Histórico Estatus",
            "Histórico Observación",
            "Histórico Fecha"
        ];
    }

    public function prepareRows($datos): array
    {
        return $datos;
    }

    public function map($row): array
    {
        $estatuses = BaitEstatus::all()->pluck('descripcion', 'id')->toArray();
        $estatus_concentra = BaitEstatusConcentra::all()->pluck('descripcion', 'id')->toArray();

        $venta      = $row['venta'];
        $historico  = $row['historico'];

        return [
            $venta->idcontacto,
            $venta->numero_portar,
            $venta->nombre_apellido,
            $venta->fecha_nacimiento,
            $venta->genero,
            $venta->imei,
            $venta->nip,
            $venta->vigencia_nip,
            $venta->email,
            $venta->tienda_id,
            $venta->relationTienda ? $venta->relationTienda->TiendaPerteneceAMunicipio->MunicipioPerteneceAEstado->estado : "", // Estado (puedes ajustar si tienes la relacion)
            $venta->relationTienda ? $venta->relationTienda->TiendaPerteneceAMunicipio->municipio : "", // Municipio
            $venta->relationTienda ? $venta->relationTienda->direccion : "", // Centro de atencion
            $venta->telefono_contacto,
            $venta->telefono_principal,
            $venta->fecha_cita ? \Carbon\Carbon::parse($venta->fecha_cita)->format('d/m/Y') : "",
            $venta->fecha_cita ? \Carbon\Carbon::parse($venta->fecha_cita)->format('H:i') : "",
            $venta->fvc,
            $venta->modalidad,
            $venta->ciclo_vida,
            $venta->created_at ? $venta->created_at->format('d/m/Y') : "",
            $venta->created_at ? $venta->created_at->format('l') . ' ' . $venta->created_at->format('d/m/Y') : "",
            $venta->created_at ? $venta->created_at->format('H:i:s') : "",
            $venta->created_at ? $venta->created_at->format('H') . " Hrs" : " intervalo hora",
            $venta->created_at ? "Semana " . $venta->created_at->weekOfMonth . " - " . $venta->created_at->format('Y') : "",
            $venta->created_at ? $venta->created_at->format('m') : "",
            $venta->sns,
            $venta->backoffice_acargo,
            $venta->bait_concentra_id != null ? $estatus_concentra[$venta->bait_concentra_id] : "",
            $venta->estatus_intelix, // Gestión
            $venta->estatus_backoffice,
            $venta->validador_atal,
            $venta->relationPersonal ? $venta->relationPersonal->numero_empleado : "", //cedula_empleado
            $venta->relationPersonal ? $venta->relationPersonal->RelationUser->nombre_apellido : "", // Asesor
            $venta->relationSupervisor ? $venta->relationSupervisor->RelationUser->nombre_apellido : "", // Supervisor
            $venta->relationCoordinador ? $venta->relationCoordinador->RelationUser->nombre_apellido : "", // Coordinador
            $estatuses[$venta->estatus_id],
            // HISTORICAL DATA
            $historico && $historico->usuario ? $historico->usuario : "",
            $historico && $historico->estatus_id ? $estatuses[$historico->estatus_id] : "",
            $historico && $historico->observaciones ? $historico->observaciones : "",
            $historico && $historico->created_at ? $historico->created_at->format('Y-m-d H:i:s') : "",
        ];
    }
}
