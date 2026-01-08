<?php

namespace App\Exports;

use App\Models\Equipo;
use App\Models\Componente;
use App\Models\ComponenteOpcional;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class InactivosExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $query = Equipo::where('estado', 'Activo');

        if (!empty($this->request['id_direccion'])) {
            $query->where('id_direccion', $this->request['id_direccion']);
        }
        if (!empty($this->request['id_division'])) {
            $query->where('id_division', $this->request['id_division']);
        }
        if (!empty($this->request['id_coordinacion'])) {
            $query->where('id_coordinacion', $this->request['id_coordinacion']);
        }

        $equipos = $query->get();

        // Filtrar solo los que tienen componentes inactivos
        $equiposFiltrados = $equipos->filter(function ($equipo) {
            $compInactivos = Componente::where('id_equipo', $equipo->id_equipo)
                ->where(function ($q) {
                    $q->where('estado', 'Inactivo')
                        ->orWhere('estadoElim', 'Inactivo');
                })->exists();

            $opcInactivos = ComponenteOpcional::where('id_equipo', $equipo->id_equipo)
                ->where('estadoElim', 'Inactivo')
                ->exists();

            return $compInactivos || $opcInactivos;
        });

        return $equiposFiltrados;
    }

    public function headings(): array
    {
        return [
            'N° Bien',
            'Equipo',
            'Dirección',
            'División',
            'Coordinación',
            'Componentes Inactivos'
        ];
    }

    public function map($equipo): array
    {
        $compInactivos = Componente::where('id_equipo', $equipo->id_equipo)
            ->where(function ($q) {
                $q->where('estado', 'Inactivo')
                    ->orWhere('estadoElim', 'Inactivo');
            })->get();

        $opcInactivos = ComponenteOpcional::where('id_equipo', $equipo->id_equipo)
            ->where('estadoElim', 'Inactivo')
            ->get();

        $compStr = "";
        foreach ($compInactivos as $c) {
            $compStr .= "{$c->tipo_componente} ({$c->marca}) - Inactivo | ";
        }
        foreach ($opcInactivos as $o) {
            $compStr .= "{$o->tipo_opcional} ({$o->marca} {$o->modelo}) - Inactivo | ";
        }

        return [
            $equipo->numero_bien ?? 'S/I',
            $equipo->marca . ' ' . $equipo->modelo,
            $equipo->direccion->nombre_direccion ?? '—',
            $equipo->division->nombre_division ?? '—',
            $equipo->coordinacion->nombre_coordinacion ?? '—',
            trim($compStr, " | ")
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']], 'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => 'B91D47']]],
        ];
    }
}
