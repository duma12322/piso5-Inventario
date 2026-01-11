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
    public function equiposGlobal(Request $request)
    {
        return Excel::download(new EquiposGlobalExport($request->all()), 'equipos_global.xlsx');
    }

    public function equipo($id)
    {
        return Excel::download(new EquipoExport($id), 'ficha_equipo_' . $id . '.xlsx');
    }

    public function estadoFuncional()
    {
        return Excel::download(new EstadoFuncionalExport, 'estado_funcional.xlsx');
    }

    public function estadoTecnologico()
    {
        return Excel::download(new EstadoTecnologicoExport, 'estado_tecnologico.xlsx');
    }

    public function estadoGabinete()
    {
        return Excel::download(new EstadoGabineteExport, 'estado_gabinete.xlsx');
    }

    public function inactivos(Request $request)
    {
        return Excel::download(new InactivosExport($request->all()), 'equipos_inactivos.xlsx');
    }
}
