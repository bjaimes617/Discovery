<?php

namespace App\Exports\Pymes;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
use App\Models\pymes\RecaudosModel;

class PymesExport implements ShouldAutoSize, FromCollection, WithHeadings, WithMapping, WithColumnWidths, WithChunkReading
{

    private $data;
    private $recaudos;

    public function __construct($data)
    {
        $this->data = collect($data);
        $this->recaudos = RecaudosModel::where('active', 1)->pluck('documento', 'id');
    }

    public function getCsvSettings(): array
    {
        return [
            'input_encoding' => 'utf8mb4_general_ci',
            'delimiter' => ';',
            "Content-type" => "text/csv; charset=utf8mb4",
            'use_bom' => true,
        ];
    }

    public function chunkSize(): int
    {
        return 50;
    }

    public function startRow(): int
    {
        return 2;
    }

    public function columnWidths(): array
    {
        return [
            'R' => 20, // Columna A con ancho 20
            'AG' => 20, // Columna A con ancho 20
        ];
    }
    public function headings(): array
    {

        $columns = [
            'ID CONTACTO',
            'FECHA',
            'TIPO DE CLIENTE',
            'CEDULA TITULAR / REPRESENTANTE LEGAL',
            'ORDEN PATRONAL',
            'PERSONERIA JURIDICA',
            'NOMBRE COMPLETO',
            'TELEFONO CONTACTO',
            'EMAIL',
            'PROVINCIA',
            'CANTON',
            'DISTRITO',
            'BARRIO',
            'DETALLE DIRECCION',
            'PRODUCTO',
            'PLAN GPON',
            'COORDENADAS',
            'CANTIDAD STB',
            'PLAN MOVIL',
            'EQUIPO',
            'PORTABILIDAD',
            'PRECIO PLAN',
            'OBSERVACIONES',
            'COORDINADOR',
            'SUPERVISOR',
            'CI EJECUTIVO TELEFONICO',
            'EJECUTIVO TELEFONICO',
            'Nombre Auditor',
            'estatus'
        ];

        foreach ($this->recaudos as $key => $value) {
            in_array($value, $columns) ? null : array_push($columns, $value);
        }

        return $columns;
    }

    public function collection()
    {
        $this->data->each(function ($data) {
            $this->map($data);
        });

        return $this->data;
    }

    public function prepareRows($data): array
    {
        return array_map(function ($change) {


            if (array_key_exists('documentos', $change)) {
                /// recorremos los documentos seleccionados            
                foreach ($change["documentos"] as $key => $value) {
                    /// cambiamos los id por los values de los documentos
                    if ($key !== "") {
                        $documentos[$this->recaudos[$key]] = "SI";
                    }
                }

                /// recorremos la lista de documentos
                foreach ($this->recaudos as $key => $value) {
                    //si ya existe en el array de documentos, mandamos el valor respectivo
                    $change[$value] = $documentos[$value] ?? "";
                }
            }
            return $change;
        }, $data);
    }

    public function map($data): array
    {
        // dd($data);
        $columns = [
            'ID CONTACTO' => $data['id_contacto'],
            'FECHA' => $data['fecha'],
            'TIPO DE CLIENTE' => $data['tipo_venta'],
            'CEDULA TITULAR / REPRESENTANTE LEGAL' => $data['identificacion'],
            'ORDEN PATRONAL' => $data['ordenpatronal'],
            'PERSONERIA JURIDICA' => $data['representantelegal'],
            'NOMBRE COMPLETO' => $data['nombre'],
            'TELEFONO CONTACTO' => $data['telefono_a_llamar'],
            'EMAIL' => $data['email'],
            'PROVINCIA' => $data['provincia'],
            'CANTON' => $data['canton'],
            'DISTRITO' => $data['distrito'],
            'BARRIO' => $data['barrio'],
            'DETALLE DIRECCION' => $data['detalle_direccion'],
            'PRODUCTO' => $data['producto'],
            'PLAN GPON' => $data['plan_gpon'],
            'COORDENADAS' => $data['cordenadas'],
            'CANTIDAD STB' => $data['cantidad'],
            'PLAN MOVIL' => $data['plan_pospago'],
            'EQUIPO' => $data['equipo'],
            'PORTABILIDAD' => $data['portabilidad'],
            'PRECIO PLAN' => $data['precio_plan'],
            'OBSERVACIONES' => $data['observaciones'],
            'COORDINADOR' => $data['coordinador'],
            'SUPERVISOR' => $data['supervisor'],
            'CI EJECUTIVO TELEFONICO' => $data['personal'],
            'EJECUTIVO TELEFONICO' => $data['user'],
            'Nombre Auditor' => array_key_exists('auditor', $data) ? $data['auditor'] : "",
            'estatus' => $data['estatus']
        ];

        foreach ($this->recaudos as $key => $value) {
            $columns[$value] = $data[$value] ?? "";
        }

        return $columns;
    }
}
