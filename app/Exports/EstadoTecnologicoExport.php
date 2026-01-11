<?php

namespace App\Exports;

use App\Models\Equipo;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class EstadoTecnologicoExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize
{
    public function collection()
    {
        $equipos = Equipo::where('estado', 'Activo')->get();
        // Contar estado tecnológico
        $estadoTecnologico = [
            'Nuevo' => 0,
            'Actualizable' => 0,
            'Obsoleto' => 0,
        ];

        foreach ($equipos as $equipo) {
            if (!empty($equipo->estado_tecnologico)) {
                $estadoTecnologico[$equipo->estado_tecnologico] = ($estadoTecnologico[$equipo->estado_tecnologico] ?? 0) + 1;
            } else {
                $estadoTecnologico['Obsoleto']++;
            }
        }

        $data = [];
        foreach ($estadoTecnologico as $estado => $count) {
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
            'Estado Tecnológico',
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
