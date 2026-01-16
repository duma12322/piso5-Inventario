<?php

namespace App\Exports;

use App\Models\Equipo;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

/**
 * Class EstadoGabineteExport
 *
 * Exporta a Excel un resumen de equipos activos
 * agrupados por el estado físico de su gabinete.
 *
 * La exportación incluye:
 * - Estado del gabinete
 * - Cantidad total de equipos activos por estado
 *
 * Interfaces implementadas:
 * - FromCollection: obtiene la información a exportar
 * - WithHeadings: define los encabezados del Excel
 * - WithStyles: aplica estilos al archivo
 * - ShouldAutoSize: ajusta automáticamente el ancho de columnas
 */
class EstadoGabineteExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize
{
    /**
     * Obtiene la colección de datos que será exportada
     *
     * - Filtra solo equipos con estado "Activo"
     * - Agrupa los equipos según el estado del gabinete
     * - Cuenta la cantidad de equipos por estado
     *
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        /**
         * Obtiene todos los equipos activos del sistema
         */
        $equipos = Equipo::where('estado', 'Activo')->get();

        /**
         * Estados físicos de gabinete definidos en el sistema
         */
        $estadosGabinete = [
            'Nuevo',
            'Deteriorado',
            'Dañado'
        ];

        /**
         * Arreglo que almacenará la información final
         */
        $data = [];

        /**
         * Recorre cada estado de gabinete y cuenta
         * cuántos equipos pertenecen a dicho estado
         */
        foreach ($estadosGabinete as $estado) {
            $count = $equipos->where('estado_gabinete', $estado)->count();

            $data[] = [
                'Estado' => $estado,
                'Cantidad' => $count
            ];
        }

        /**
         * Retorna la colección que será exportada a Excel
         */
        return collect($data);
    }

    /**
     * Define los encabezados del archivo Excel
     *
     * El orden debe coincidir con la estructura
     * del arreglo generado en collection()
     *
     * @return array
     */
    public function headings(): array
    {
        return [
            'Estado Físico de Gabinetes',
            'Cantidad de Equipos'
        ];
    }

    /**
     * Aplica estilos al archivo Excel
     *
     * Estilo aplicado a la fila de encabezados:
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
