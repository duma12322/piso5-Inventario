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

class EquiposGlobalExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $filtros;
    protected $estadoTecnologicoParam;

    public function __construct($filtros = [], $estadoTecnologicoParam = null)
    {
        $this->filtros = $filtros;
        $this->estadoTecnologicoParam = $estadoTecnologicoParam;
    }

    public function collection()
    {
        $query = Equipo::with(['direccion', 'division', 'coordinacion'])
            ->where('estado', 'Activo');

        // Aplicar mismos filtros que en el PDF Controller
        if (!empty($this->filtros['search'])) {
            $clean = preg_replace('/[^\wñÑáéíóúÁÉÍÓÚ ]+/u', ' ', $this->filtros['search']);
            $terms = array_filter(explode(' ', $clean));

            $query->where(function ($q) use ($terms) {
                foreach ($terms as $t) {
                    $q->where(function ($sub) use ($t) {
                        $sub->where('marca', 'LIKE', "%{$t}%")
                            ->orWhere('modelo', 'LIKE', "%{$t}%")
                            ->orWhere('estado_funcional', 'LIKE', "%{$t}%")
                            ->orWhere('estado_tecnologico', 'LIKE', "%{$t}%")
                            ->orWhere('estado_gabinete', 'LIKE', "%{$t}%")
                            ->orWhereHas('direccion', fn($rel) => $rel->where('nombre_direccion', 'LIKE', "%{$t}%"))
                            ->orWhereHas('division', fn($rel) => $rel->where('nombre_division', 'LIKE', "%{$t}%"))
                            ->orWhereHas('coordinacion', fn($rel) => $rel->where('nombre_coordinacion', 'LIKE', "%{$t}%"));
                    });
                }
            });
        }

        if (!empty($this->filtros['marca']))
            $query->where('marca', 'LIKE', "%{$this->filtros['marca']}%");
        if (!empty($this->filtros['modelo']))
            $query->where('modelo', 'LIKE', "%{$this->filtros['modelo']}%");
        if (!empty($this->filtros['division']))
            $query->where('division_id', $this->filtros['division']);
        if (!empty($this->filtros['direccion']))
            $query->where('direccion_id', $this->filtros['direccion']);
        if (!empty($this->filtros['estado_tecnologico']))
            $query->where('estado_tecnologico', $this->filtros['estado_tecnologico']);
        if (!empty($this->filtros['estado_funcional']))
            $query->where('estado_funcional', $this->filtros['estado_funcional']);

        if ($this->estadoTecnologicoParam) {
            $query->where('estado_tecnologico', $this->estadoTecnologicoParam);
        }

        return $query->get();
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
            'Detalles Tecnológicos'
        ];
    }

    public function map($equipo): array
    {
        // Lógica de cálculo tecnológico (reutilizada del PDF)
        $anioActual = Carbon::now()->year;
        $componentes = Componente::where('id_equipo', $equipo->id_equipo)
            ->where('estadoElim', 'Activo')
            ->get();

        $componentesTecnologicos = $componentes->filter(fn($c) => in_array(strtolower($c->tipo_componente), ['tarjeta madre', 'procesador', 'memoria ram']));
        $explicacion = '';

        // ... (Simplificando la explicación para una celda de Excel)
        foreach ($componentesTecnologicos as $comp) {
            $explicacion .= "{$comp->tipo_componente} ({$comp->marca} {$comp->modelo}) | ";
        }

        return [
            $equipo->numero_bien ?? 'S/I',
            $equipo->marca,
            $equipo->modelo,
            $equipo->direccion->nombre_direccion ?? 'N/A',
            $equipo->division->nombre_division ?? 'N/A',
            $equipo->coordinacion->nombre_coordinacion ?? 'N/A',
            $equipo->estado_funcional,
            $equipo->estado_gabinete,
            $equipo->estado_tecnologico, // Asumiendo que este campo ya tiene el valor calculado o guardado
            trim($explicacion, " | ")
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']], 'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => 'B91D47']]],
        ];
    }
}
