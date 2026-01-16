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

/**
 * Class InactivosExport
 *
 * Exporta a Excel un listado de equipos activos
 * que contienen componentes o componentes opcionales
 * en estado inactivo.
 *
 * El reporte puede filtrarse por:
 * - Dirección
 * - División
 * - Coordinación
 *
 * Interfaces implementadas:
 * - FromCollection: obtiene la colección base de datos
 * - WithHeadings: define encabezados del Excel
 * - WithMapping: transforma cada equipo en una fila
 * - WithStyles: aplica estilos visuales
 * - ShouldAutoSize: ajusta automáticamente el ancho de columnas
 */
class InactivosExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    /**
     * Parámetros de filtrado recibidos desde el request
     *
     * @var array
     */
    protected $request;

    /**
     * Constructor
     *
     * @param array $request
     */
    public function __construct($request)
    {
        $this->request = $request;
    }

    /**
     * Obtiene la colección de equipos a exportar
     *
     * - Filtra equipos con estado "Activo"
     * - Aplica filtros por dirección, división y coordinación
     * - Devuelve únicamente equipos que tengan
     *   componentes o componentes opcionales inactivos
     *
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        /**
         * Consulta base de equipos activos
         */
        $query = Equipo::where('estado', 'Activo');

        /**
         * Filtro por dirección
         */
        if (!empty($this->request['id_direccion'])) {
            $query->where('id_direccion', $this->request['id_direccion']);
        }

        /**
         * Filtro por división
         */
        if (!empty($this->request['id_division'])) {
            $query->where('id_division', $this->request['id_division']);
        }

        /**
         * Filtro por coordinación
         */
        if (!empty($this->request['id_coordinacion'])) {
            $query->where('id_coordinacion', $this->request['id_coordinacion']);
        }

        /**
         * Obtiene los equipos filtrados
         */
        $equipos = $query->get();

        /**
         * Filtra únicamente los equipos que poseen
         * componentes o componentes opcionales inactivos
         */
        $equiposFiltrados = $equipos->filter(function ($equipo) {

            /**
             * Verifica si existen componentes inactivos
             */
            $compInactivos = Componente::where('id_equipo', $equipo->id_equipo)
                ->where(function ($q) {
                    $q->where('estado', 'Inactivo')
                        ->orWhere('estadoElim', 'Inactivo');
                })
                ->exists();

            /**
             * Verifica si existen componentes opcionales inactivos
             */
            $opcInactivos = ComponenteOpcional::where('id_equipo', $equipo->id_equipo)
                ->where('estadoElim', 'Inactivo')
                ->exists();

            return $compInactivos || $opcInactivos;
        });

        /**
         * Retorna la colección final
         */
        return $equiposFiltrados;
    }

    /**
     * Define los encabezados del archivo Excel
     *
     * @return array
     */
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

    /**
     * Mapea cada equipo a una fila del Excel
     *
     * Incluye un resumen de todos los componentes
     * y componentes opcionales que se encuentran inactivos
     *
     * @param Equipo $equipo
     * @return array
     */
    public function map($equipo): array
    {
        /**
         * Obtiene componentes inactivos
         */
        $compInactivos = Componente::where('id_equipo', $equipo->id_equipo)
            ->where(function ($q) {
                $q->where('estado', 'Inactivo')
                    ->orWhere('estadoElim', 'Inactivo');
            })
            ->get();

        /**
         * Obtiene componentes opcionales inactivos
         */
        $opcInactivos = ComponenteOpcional::where('id_equipo', $equipo->id_equipo)
            ->where('estadoElim', 'Inactivo')
            ->get();

        /**
         * Construye la descripción de componentes inactivos
         */
        $compStr = "";

        foreach ($compInactivos as $c) {
            $compStr .= "{$c->tipo_componente} ({$c->marca}) - Inactivo | ";
        }

        foreach ($opcInactivos as $o) {
            $compStr .= "{$o->tipo_opcional} ({$o->marca} {$o->modelo}) - Inactivo | ";
        }

        /**
         * Retorna la fila final del Excel
         */
        return [
            $equipo->numero_bien ?? 'S/I',
            $equipo->marca . ' ' . $equipo->modelo,
            $equipo->direccion->nombre_direccion ?? '—',
            $equipo->division->nombre_division ?? '—',
            $equipo->coordinacion->nombre_coordinacion ?? '—',
            trim($compStr, " | ")
        ];
    }

    /**
     * Aplica estilos al archivo Excel
     *
     * Estilos aplicados a la fila de encabezados:
     * - Texto blanco
     * - Fuente en negrita
     * - Fondo rojo oscuro
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
