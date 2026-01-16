<?php

namespace App\Exports;

use App\Models\Equipo;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

/**
 * Class EstadoTecnologicoExport
 *
 * Exporta a Excel un resumen de equipos activos
 * clasificados por su estado tecnológico.
 *
 * La exportación incluye:
 * - Estado tecnológico del equipo
 * - Cantidad total de equipos activos por estado
 *
 * Interfaces implementadas:
 * - FromCollection: provee los datos a exportar
 * - WithHeadings: define los encabezados del Excel
 * - WithStyles: aplica estilos al archivo
 * - ShouldAutoSize: ajusta automáticamente el ancho de columnas
 */
class EstadoTecnologicoExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize
{
    /**
     * Obtiene la colección de datos que será exportada a Excel
     *
     * - Filtra únicamente los equipos con estado "Activo"
     * - Clasifica los equipos según su estado tecnológico
     * - Cuenta la cantidad de equipos por cada estado
     *
     * Si un equipo no tiene estado tecnológico definido,
     * se considera como "Obsoleto".
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
         * Inicializa el contador de estados tecnológicos
         */
        $estadoTecnologico = [
            'Nuevo' => 0,
            'Actualizable' => 0,
            'Obsoleto' => 0,
        ];

        /**
         * Recorre cada equipo y contabiliza su estado tecnológico
         */
        foreach ($equipos as $equipo) {
            if (!empty($equipo->estado_tecnologico)) {
                $estadoTecnologico[$equipo->estado_tecnologico] =
                    ($estadoTecnologico[$equipo->estado_tecnologico] ?? 0) + 1;
            } else {
                // Si no tiene estado tecnológico, se considera obsoleto
                $estadoTecnologico['Obsoleto']++;
            }
        }

        /**
         * Prepara los datos finales para el Excel
         */
        $data = [];

        foreach ($estadoTecnologico as $estado => $count) {
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
     * El orden debe coincidir con la estructura
     * del arreglo generado en collection()
     *
     * @return array
     */
    public function headings(): array
    {
        return [
            'Estado Tecnológico',
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
