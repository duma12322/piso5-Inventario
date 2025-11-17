<?php

namespace App\Http\Controllers;

use App\Models\Log;
use Illuminate\Http\Request;

class LogController extends Controller
{
    public function index(Request $request)
    {
        // Iniciar query de logs
        $query = Log::with('usuario')->orderBy('fecha', 'desc');

        // Filtro por usuario
        if ($request->filled('search_usuario')) {
            $query->whereHas('usuario', function ($q) use ($request) {
                $q->where('usuario', 'like', "%{$request->search_usuario}%");
            });
        }

        // Filtro por acción
        if ($request->filled('search_accion')) {
            $query->where('accion', 'like', "%{$request->search_accion}%");
        }

        // Filtro por fecha (ejemplo: dd/mm/yyyy)
        if ($request->filled('search_fecha')) {
            $query->whereDate('fecha', \Carbon\Carbon::createFromFormat('d/m/Y', $request->search_fecha)->format('Y-m-d'));
        }

        // Paginación 10 por página
        $logs = $query->paginate(10)->withQueryString();

        return view('logs.index', compact('logs'));
    }
}
