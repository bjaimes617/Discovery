<?php

namespace App\Exports\Concentra;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PersonalExport implements FromView, ShouldAutoSize, WithEvents {

    private $data;

    public function __construct($data) {
        $this->data = $data;
    }

    public function view(): View {
        return view('concentra.reportes.xlsx.personal', [
            'data' => $this->data
        ]);
    }

    public function registerEvents(): array {
        return [
            AfterSheet::class => function(AfterSheet $event) {
              
            },
        ];
    }

}
