<?php

namespace App\Exports;

use App\Models\Equipo;
use App\Models\Componente;
use App\Models\ComponenteOpcional;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

class EquipoExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function collection()
    {
        return Equipo::with(['direccion', 'division', 'coordinacion'])
            ->where('id_equipo', $this->id)
            ->get();
    }

    public function headings(): array
    {
        return [
            'N° Bien',
            'Marca',
            'Modelo',
            'Dirección',
            'División',
            'Coordinación',
            'Estado Funcional',
            'Estado Gabinete',
            'Estado Tecnológico',
            'Componentes',
            'Componentes Opcionales'
        ];
    }

    public function map($equipo): array
    {
        $componentes = Componente::where('id_equipo', $equipo->id_equipo)
            ->where('estadoElim', 'Activo')
            ->get();

        $componentesOpcionales = ComponenteOpcional::where('id_equipo', $equipo->id_equipo)
            ->where('estadoElim', 'Activo')
            ->get();

        $compStr = $componentes->map(function ($c) {
            $cap = $c->capacidad ?? $c->frecuencia ?? '';
            return "{$c->tipo_componente} {$c->marca} {$c->modelo} {$cap}";
        })->join(" | ");

        $opcStr = $componentesOpcionales->map(function ($o) {
            $cap = $o->capacidad ?? $o->velocidad ?? '';
            return "{$o->tipo_opcional} {$o->marca} {$o->modelo} {$cap}";
        })->join(" | ");

        return [
            $equipo->numero_bien ?? 'S/I',
            $equipo->marca,
            $equipo->modelo,
            $equipo->direccion->nombre_direccion ?? 'N/A',
            $equipo->division->nombre_division ?? 'N/A',
            $equipo->coordinacion->nombre_coordinacion ?? 'N/A',
            $equipo->estado_funcional,
            $equipo->estado_gabinete,
            $equipo->estado_tecnologico,
            $compStr,
            $opcStr
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']], 'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => 'B91D47']]],
        ];
    }
}
