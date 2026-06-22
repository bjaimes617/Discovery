<?php

namespace App\Exports\Masivos;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
use App\Models\masivos\RecaudosModel;

class MasivosExport implements ShouldAutoSize, FromCollection, WithHeadings, WithMapping, WithColumnWidths, WithChunkReading
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
            'id_contacto',
            'fecha',
            'tipo_venta',
            'agencia',
            'nombre',
            'apellido_1',
            'apellido_2',
            'identificacion',
            'segmento',
            'Plan GPON',
            'Plan Pospago',
            'plan DTH',
            'precio',
            'coordenadas',
            'provincia',
            'canton',
            'distrito',
            'detalle_direccion',
            'telefono_a_llamar',
            'email',
            'equipo',
            'numero_portar',
            'anticipo',
            'nombre_refencia_1',
            'telefono_refencia_1',
            'parentesco_refencia_1',
            'nombre_refencia_2',
            'telefono_refencia_2',
            'parentesco_refencia_2',
            'nombre_refencia_3',
            'telefono_refencia_3',
            'parentesco_refencia_3',
            'observaciones',
            'producto',
            'coordinador',
            'supervisor',
            'numero_empleado',
            'nombre_apellido',
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
        $columns = [
            'id_contacto' => $data['id_contacto'],
            'fecha' => $data['fecha'],
            'tipo_venta' => $data['tipo_venta'],
            'agencia' => $data['agencia'],
            'nombre' => $data['nombre'],
            'apellido_1' => $data['apellido_1'],
            'apellido_2' => $data['apellido_2'],
            'identificacion' => $data['identificacion'],
            'segmento' => $data['segmento'],
            'Plan GPON' => $data['Plan_GPON'],
            'Plan Pospago' => $data['Plan_Pospago'],
            'plan DTH' => $data['Plan_DTH'],
            'precio' => $data['precio'],
            'coordenadas' => $data['coordenadas'],
            'provincia' => $data['provincia'],
            'canton' => $data['canton'],
            'distrito' => $data['distrito'],
            'detalle_direccion' => $data['detalle_direccion'],
            'telefono_a_llamar' => $data['telefono_a_llamar'],
            'email' => $data['email'],
            'equipo' => $data['equipo'],
            'numero_portar' => $data['numero_portar'],
            'anticipo' => $data['anticipo'],
            'nombre_refencia_1' => $data['nombre_refencia_1'],
            'telefono_refencia_1' => $data['telefono_refencia_1'],
            'parentesco_refencia_1' => $data['parentesco_refencia_1'],
            'nombre_refencia_2' => $data['nombre_refencia_2'],
            'telefono_refencia_2' => $data['telefono_refencia_2'],
            'parentesco_refencia_2' => $data['parentesco_refencia_2'],
            'nombre_refencia_3' => $data['nombre_refencia_3'],
            'telefono_refencia_3' => $data['telefono_refencia_3'],
            'parentesco_refencia_3' => $data['parentesco_refencia_3'],
            'observaciones' => $data['observaciones'],
            'producto' => $data['producto'],
            'coordinador' => $data['coordinador'],
            'supervisor' => $data['supervisor'],
            'numero_empleado' => $data['numero_empleado'],
            'nombre_apellido' => $data['nombre_apellido'],
            'Nombre Auditor' => array_key_exists('auditor', $data) ? $data['auditor'] : "",
            'estatus' => $data['estatus']
        ];

        foreach ($this->recaudos as $key => $value) {
            $columns[$value] = $data[$value] ?? "";
        }

        return $columns;
    }
}
