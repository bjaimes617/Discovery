<?php

namespace App\Exports\Bait;


use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use App\Models\bait\BaitEstatusConcentra;
use App\Models\bait\BaitTiendas;
use App\Models\Personal;
use Carbon\Carbon;

class RespondioExport implements ShouldAutoSize, FromCollection, WithHeadings, WithMapping, WithChunkReading
{
    protected $datos;
    protected $estatus_concentra;
    protected $personalCache = [];
    protected $tiendasCache = [];

    public function __construct($datos)
    {
        $this->datos = $datos;
        $this->estatus_concentra = BaitEstatusConcentra::pluck('descripcion', 'id')->toArray();
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
        return $this->datos;
    }

    protected function getPersonal($id)
    {
        if (!$id) return ["cedula_empleado" => "", "nombre_apellido" => ""];

        if (!isset($this->personalCache[$id])) {
            $p = Personal::with('RelationUser')->find($id);
            $this->personalCache[$id] = [
                "cedula_empleado" => $p ? $p->numero_empleado : "",
                "nombre_apellido" => $p && $p->RelationUser ? $p->RelationUser->nombre_apellido : ""
            ];
        }

        return $this->personalCache[$id];
    }

    protected function getTiendaData($tienda_id)
    {
        if (!$tienda_id) return ["estado" => "", "municipio" => "", "direccion" => ""];

        if (!isset($this->tiendasCache[$tienda_id])) {
            $tienda = BaitTiendas::with('TiendaPerteneceAMunicipio.MunicipioPerteneceAEstado')->find($tienda_id);
            $this->tiendasCache[$tienda_id] = [
                "estado" => $tienda && $tienda->TiendaPerteneceAMunicipio && $tienda->TiendaPerteneceAMunicipio->MunicipioPerteneceAEstado ? $tienda->TiendaPerteneceAMunicipio->MunicipioPerteneceAEstado->estado : "",
                "municipio" => $tienda && $tienda->TiendaPerteneceAMunicipio ? $tienda->TiendaPerteneceAMunicipio->municipio : "",
                "direccion" => $tienda ? $tienda->direccion : ""
            ];
        }

        return $this->tiendasCache[$tienda_id];
    }

    public function headings(): array
    {
        return [
            "Registro",
            "Hora",
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
            "Usuario_respondio",
            "CI",
            "Asesor",
            "Supervisor",
            "Coordinador"
        ];
    }

    public function map($venta): array
    {
        $tiendaData = $this->getTiendaData($venta->tienda_id);
        $personalData = $this->getPersonal($venta->personal_id);
        $supervisorData = $this->getPersonal($venta->supervisor_id);
        $coordinadorData = $this->getPersonal($venta->coordinador_id);

        $created_at = null;
        if ($venta->created_at) {
            $created_at = $venta->created_at instanceof Carbon ? $venta->created_at : Carbon::parse($venta->created_at);
        }

        return [
            date('d/m/Y', strtotime($venta->created_at)),
            date('H:i:s', strtotime($venta->created_at)),
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
            $tiendaData["estado"],
            $tiendaData["municipio"],
            $tiendaData["direccion"],
            $venta->telefono_contacto,
            $venta->telefono_principal,
            $venta->fecha_cita ? Carbon::parse($venta->fecha_cita)->format('d/m/Y') : "",
            $venta->fecha_cita ? Carbon::parse($venta->fecha_cita)->format('H:i') : "",
            $venta->fvc,
            $venta->modalidad,
            $venta->ciclo_de_vida,
            $created_at ? $created_at->format('d/m/Y') : "",
            $created_at ? $created_at->format('l') . ' ' . $created_at->format('d/m/Y') : "",
            $created_at ? $created_at->format('H:i:s') : "",
            $created_at ? $created_at->format('H') . " Hrs" : " intervalo hora",
            $created_at ? "Semana " . $created_at->weekOfMonth . " - " . $created_at->format('Y') : "",
            $created_at ? $created_at->format('m') : "",
            $venta->sns,
            $venta->backoffice_acargo,
            $venta->bait_concentra_id != null ? ($this->estatus_concentra[$venta->bait_concentra_id] ?? "") : "",
            $venta->estatus_intelix, // Gestión
            $venta->estatus_backoffice,
            $venta->validador_alta,
            $venta->usuario,
            $personalData['cedula_empleado'],
            $personalData['nombre_apellido'],
            $supervisorData['nombre_apellido'],
            $coordinadorData['nombre_apellido'],
        ];
    }
}
