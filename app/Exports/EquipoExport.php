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

/**
 * Clase EquipoExport
 *
 * Exporta la información de un equipo específico a Excel,
 * incluyendo sus componentes y componentes opcionales.
 *
 * Implementa varias interfaces de Maatwebsite Excel para:
 * - Obtener la colección de datos
 * - Definir encabezados
 * - Mapear los datos
 * - Aplicar estilos
 * - Ajustar automáticamente el tamaño de las columnas
 */
class EquipoExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    /**
     * ID del equipo a exportar
     *
     * @var int
     */
    protected $id;

    /**
     * Constructor
     *
     * Recibe el ID del equipo que será exportado
     *
     * @param int $id
     */
    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * Obtiene la colección de equipos a exportar
     *
     * Se utiliza eager loading para cargar las relaciones:
     * - dirección
     * - división
     * - coordinación
     *
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Equipo::with(['direccion', 'division', 'coordinacion'])
            ->where('id_equipo', $this->id)
            ->get();
    }

    /**
     * Define los encabezados del archivo Excel
     *
     * El orden de los encabezados debe coincidir
     * con el orden del método map()
     *
     * @return array
     */
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

    /**
     * Mapea cada registro del equipo a una fila del Excel
     *
     * @param Equipo $equipo
     * @return array
     */
    public function map($equipo): array
    {
        /**
         * Obtiene los componentes activos del equipo
         */
        $componentes = Componente::where('id_equipo', $equipo->id_equipo)
            ->where('estadoElim', 'Activo')
            ->get();

        /**
         * Obtiene los componentes opcionales activos del equipo
         */
        $componentesOpcionales = ComponenteOpcional::where('id_equipo', $equipo->id_equipo)
            ->where('estadoElim', 'Activo')
            ->get();

        /**
         * Convierte los componentes en una cadena de texto
         * Ejemplo:
         * CPU Intel i7 3.4GHz | RAM Kingston 16GB
         */
        $compStr = $componentes->map(function ($c) {
            // Usa capacidad o frecuencia si existe
            $cap = $c->capacidad ?? $c->frecuencia ?? '';
            return "{$c->tipo_componente} {$c->marca} {$c->modelo} {$cap}";
        })->join(" | ");

        /**
         * Convierte los componentes opcionales en una cadena de texto
         */
        $opcStr = $componentesOpcionales->map(function ($o) {
            // Usa capacidad o velocidad si existe
            $cap = $o->capacidad ?? $o->velocidad ?? '';
            return "{$o->tipo_opcional} {$o->marca} {$o->modelo} {$cap}";
        })->join(" | ");

        /**
         * Retorna la fila completa del Excel
         */
        return [
            $equipo->numero_bien ?? 'S/I', // Número de bien o "Sin Información"
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

    /**
     * Aplica estilos al archivo Excel
     *
     * Se estiliza la fila 1 (encabezados):
     * - Texto en blanco
     * - Negrita
     * - Fondo color rojo oscuro
     *
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF']
                ],
                'fill' => [
                    'fillType' => 'solid',
                    'startColor' => ['rgb' => 'B91D47']
                ]
            ],
        ];
    }
}
