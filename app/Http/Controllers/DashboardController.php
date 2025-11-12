<?php

namespace App\Http\Controllers;

use App\Models\Equipo;
use App\Models\Usuario;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $usuario = Auth::user();

        // Traemos todos los equipos activos como colección
        $equiposActivos = Equipo::where('estado', 'Activo')->get();

        // Conteos generales
        $totalEquipos = $equiposActivos->count();
        $totalUsuarios = Usuario::count();

        // Estado funcional de los equipos
        $estadoFuncional = [
            'Buen Funcionamiento' => $equiposActivos->where('estado_funcional', 'Bueno')->count(),
            'Operativo' => $equiposActivos->where('estado_funcional', 'Intermedio')->count(),
            'Sin Funcionar' => $equiposActivos->where('estado_funcional', 'Dañado')->count(),
        ];

        // Estado tecnológico de los equipos
        $estadoTecnologico = [
            'Nuevo' => $equiposActivos->where('estado_tecnologico', 'Nuevo')->count(),
            'Actualizable' => $equiposActivos->where('estado_tecnologico', 'Actualizable')->count(),
            'Obsoleto' => $equiposActivos->where('estado_tecnologico', 'Obsoleto')->count(),
        ];

        // Estado de los gabinetes (solo equipos con tipo_gabinete definido)
        $gabinetesActivos = $equiposActivos->filter(function ($equipo) {
            return !is_null($equipo->tipo_gabinete);
        });

        $estadoGabinete = [
            'Nuevo' => $gabinetesActivos->where('estado_gabinete', 'Nuevo')->count(),
            'Semi nuevo' => $gabinetesActivos->where('estado_gabinete', 'Semi nuevo')->count(),
            'Deteriorado' => $gabinetesActivos->where('estado_gabinete', 'Deteriorado')->count(),
            'Dañado' => $gabinetesActivos->where('estado_gabinete', 'Dañado')->count(),
        ];

        return view('dashboard.index', compact(
            'usuario',
            'totalEquipos',
            'totalUsuarios',
            'estadoFuncional',
            'estadoTecnologico',
            'estadoGabinete'
        ));
    }
}
