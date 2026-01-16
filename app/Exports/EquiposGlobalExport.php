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
 * Class EquiposGlobalExport
 *
 * Exporta un listado global de equipos a Excel aplicando
 * los mismos filtros utilizados en el controlador de PDF.
 *
 * Incluye información general del equipo y un resumen
 * de los componentes tecnológicos asociados.
 *
 * Interfaces implementadas:
 * - FromCollection: obtiene los datos desde una colección
 * - WithHeadings: define encabezados del Excel
 * - WithMapping: mapea cada registro a una fila
 * - WithStyles: aplica estilos al archivo
 * - ShouldAutoSize: ajusta automáticamente el ancho de columnas
 */
class EquiposGlobalExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    /**
     * Filtros recibidos desde el request (buscador, selects, etc.)
     *
     * @var array
     */
    protected $filtros;

    /**
     * Filtro adicional por estado tecnológico (opcional)
     *
     * @var string|null
     */
    protected $estadoTecnologicoParam;

    /**
     * Constructor
     *
     * @param array $filtros
     * @param string|null $estadoTecnologicoParam
     */
    public function __construct($filtros = [], $estadoTecnologicoParam = null)
    {
        $this->filtros = $filtros;
        $this->estadoTecnologicoParam = $estadoTecnologicoParam;
    }

    /**
     * Obtiene la colección de equipos a exportar
     *
     * Se aplican:
     * - Relación con dirección, división y coordinación
     * - Filtro de estado activo
     * - Filtros dinámicos iguales al PDF
     *
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $query = Equipo::with(['direccion', 'division', 'coordinacion'])
            ->where('estado', 'Activo');

        /**
         * Filtro de búsqueda general (search)
         * Limpia caracteres especiales y separa en términos
         */
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

        /**
         * Filtros individuales
         */
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

        /**
         * Filtro directo por estado tecnológico recibido como parámetro
         */
        if ($this->estadoTecnologicoParam) {
            $query->where('estado_tecnologico', $this->estadoTecnologicoParam);
        }

        return $query->get();
    }

    /**
     * Encabezados del archivo Excel
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
            'Detalles Tecnológicos'
        ];
    }

    /**
     * Mapea cada equipo a una fila del Excel
     *
     * @param Equipo $equipo
     * @return array
     */
    public function map($equipo): array
    {
        /**
         * Año actual (usado en lógica tecnológica, si aplica)
         */
        $anioActual = Carbon::now()->year;

        /**
         * Obtiene los componentes activos del equipo
         */
        $componentes = Componente::where('id_equipo', $equipo->id_equipo)
            ->where('estadoElim', 'Activo')
            ->get();

        /**
         * Filtra solo componentes tecnológicos relevantes
         */
        $componentesTecnologicos = $componentes->filter(
            fn($c) => in_array(
                strtolower($c->tipo_componente),
                ['tarjeta madre', 'procesador', 'memoria ram']
            )
        );

        /**
         * Construye una explicación simplificada
         * para mostrarla en una sola celda de Excel
         */
        $explicacion = '';

        foreach ($componentesTecnologicos as $comp) {
            $explicacion .= "{$comp->tipo_componente} ({$comp->marca} {$comp->modelo}) | ";
        }

        /**
         * Retorno final de la fila
         */
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
            trim($explicacion, " | ")
        ];
    }

    /**
     * Estilos del archivo Excel
     *
     * Aplica estilos a la fila de encabezados:
     * - Texto en blanco
     * - Negrita
     * - Fondo rojo
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
