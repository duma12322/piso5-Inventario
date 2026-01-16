<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\EquiposGlobalExport;
use App\Exports\EquipoExport;
use App\Exports\EstadoFuncionalExport;
use App\Exports\EstadoTecnologicoExport;
use App\Exports\EstadoGabineteExport;
use App\Exports\InactivosExport;

class ReportesExcelController extends Controller
{
    /**
     * Exporta todos los equipos en un archivo Excel global.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function equiposGlobal(Request $request)
    {
        // Descarga el Excel con todos los equipos aplicando los filtros recibidos en el request
        return Excel::download(new EquiposGlobalExport($request->all()), 'equipos_global.xlsx');
    }

    /**
     * Exporta la ficha de un equipo específico.
     *
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function equipo($id)
    {
        // Descarga el Excel con la información de un equipo específico
        return Excel::download(new EquipoExport($id), 'ficha_equipo_' . $id . '.xlsx');
    }

    /**
     * Exporta el estado funcional de todos los equipos activos.
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function estadoFuncional()
    {
        // Descarga el Excel con el resumen del estado funcional
        return Excel::download(new EstadoFuncionalExport, 'estado_funcional.xlsx');
    }

    /**
     * Exporta el estado tecnológico de todos los equipos activos.
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function estadoTecnologico()
    {
        // Descarga el Excel con el resumen del estado tecnológico
        return Excel::download(new EstadoTecnologicoExport, 'estado_tecnologico.xlsx');
    }

    /**
     * Exporta el estado físico de los gabinetes de los equipos activos.
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function estadoGabinete()
    {
        // Descarga el Excel con el resumen del estado de los gabinetes
        return Excel::download(new EstadoGabineteExport, 'estado_gabinete.xlsx');
    }

    /**
     * Exporta los equipos que tienen componentes u opcionales inactivos.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function inactivos(Request $request)
    {
        // Descarga el Excel con los equipos que tengan componentes inactivos
        return Excel::download(new InactivosExport($request->all()), 'equipos_inactivos.xlsx');
    }
}
