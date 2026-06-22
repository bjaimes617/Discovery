<?php

namespace App\Exports\Concentra;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithHeadings;

class VentasExport implements FromView, ShouldAutoSize, WithEvents {

    private $data;
    private $date;

    public function __construct($data, $date) {
        $this->data = $data;
        $this->date = $date;
    }

    public function view(): View {
        return view('concentra.reportes.xlsx.ventas', [
            'data' => $this->data,
            'date' => $this->date
        ]);
    }

    public function registerEvents(): array {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                // All headers - set font size to 14           
                

                if(count($this->data)){
                    $cantidad = count($this->data) + 7;
                }else
                    $cantidad = 7;


            },
        ];
    }

}
