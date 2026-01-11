<?php

namespace App\Exports;

use App\Models\Equipo;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class EstadoFuncionalExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize
{
    public function collection()
    {
        $equipos = Equipo::where('estado', 'Activo')->get();

        $estadosFuncionales = ['Buen Funcionamiento', 'Operativo', 'Sin Funcionar'];
        $data = [];

        foreach ($estadosFuncionales as $estado) {
            $count = $equipos->where('estado_funcional', $estado)->count();
            $data[] = [
                'Estado' => $estado,
                'Cantidad' => $count
            ];
        }

        return collect($data);
    }

    public function headings(): array
    {
        return [
            'Estado Funcional',
            'Cantidad de Equipos'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']], 'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => 'B91D47']]],
        ];
    }
}
