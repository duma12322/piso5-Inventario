<?php

namespace App\Http\Controllers;

use App\Models\Log;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LogController extends Controller
{
    /**
     * Muestra el listado de logs con filtros opcionales y paginación.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Iniciar la consulta de logs y cargar la relación con usuario
        $query = Log::with('usuario')->orderBy('fecha', 'desc');

        // --- FILTROS ---

        // Filtro por nombre de usuario
        if ($request->filled('search_usuario')) {
            $query->whereHas('usuario', function ($q) use ($request) {
                $q->where('usuario', 'like', "%{$request->search_usuario}%");
            });
        }

        // Filtro por acción realizada
        if ($request->filled('search_accion')) {
            $query->where('accion', 'like', "%{$request->search_accion}%");
        }

        // Filtro por fecha (formato esperado: dd/mm/yyyy)
        if ($request->filled('search_fecha')) {
            // Convertir fecha al formato Y-m-d de la base de datos
            $fechaFormateada = Carbon::createFromFormat('d/m/Y', $request->search_fecha)->format('Y-m-d');
            $query->whereDate('fecha', $fechaFormateada);
        }

        // Obtener resultados con paginación de 10 registros por página
        $logs = $query->paginate(10)->withQueryString();

        // Retornar la vista con los logs
        return view('logs.index', compact('logs'));
    }
}
