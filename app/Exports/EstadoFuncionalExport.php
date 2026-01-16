<?php

namespace App\Exports;

use App\Models\Equipo;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

/**
 * Class EstadoFuncionalExport
 *
 * Exporta a Excel un resumen de equipos agrupados
 * por su estado funcional.
 *
 * La exportación incluye:
 * - Estado funcional del equipo
 * - Cantidad total de equipos activos en cada estado
 *
 * Interfaces implementadas:
 * - FromCollection: provee los datos a exportar
 * - WithHeadings: define los encabezados del Excel
 * - WithStyles: aplica estilos al archivo
 * - ShouldAutoSize: ajusta automáticamente el ancho de columnas
 */
class EstadoFuncionalExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize
{
    /**
     * Obtiene la colección de datos que será exportada a Excel
     *
     * - Filtra únicamente los equipos con estado "Activo"
     * - Agrupa los equipos según su estado funcional
     * - Cuenta cuántos equipos existen por cada estado
     *
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        /**
         * Obtiene todos los equipos activos
         */
        $equipos = Equipo::where('estado', 'Activo')->get();

        /**
         * Estados funcionales definidos en el sistema
         */
        $estadosFuncionales = [
            'Buen Funcionamiento',
            'Operativo',
            'Sin Funcionar'
        ];

        /**
         * Arreglo donde se almacenará la información final
         */
        $data = [];

        /**
         * Recorre cada estado funcional y cuenta
         * cuántos equipos pertenecen a ese estado
         */
        foreach ($estadosFuncionales as $estado) {
            $count = $equipos->where('estado_funcional', $estado)->count();

            $data[] = [
                'Estado' => $estado,
                'Cantidad' => $count
            ];
        }

        /**
         * Retorna la colección que será exportada
         */
        return collect($data);
    }

    /**
     * Define los encabezados del archivo Excel
     *
     * El orden de los encabezados coincide con
     * la estructura del array retornado en collection()
     *
     * @return array
     */
    public function headings(): array
    {
        return [
            'Estado Funcional',
            'Cantidad de Equipos'
        ];
    }

    /**
     * Aplica estilos al archivo Excel
     *
     * Estilos aplicados a la fila de encabezados:
     * - Texto en color blanco
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
