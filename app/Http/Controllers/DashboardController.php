<?php

namespace App\Http\Controllers;

use App\Models\Equipo;
use App\Models\Usuario;
use App\Models\Direccion;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Constructor del controlador.
     * Aplica middleware 'auth' para que solo usuarios autenticados
     * puedan acceder a las rutas de este controlador.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Mostrar la vista principal del dashboard.
     * Reúne información estadística sobre equipos, usuarios y direcciones.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Obtener el usuario autenticado
        $usuario = Auth::user();

        // Traer todos los equipos activos como colección
        $equiposActivos = Equipo::where('estado', 'Activo')->get();

        // Traer todas las direcciones activas
        $direccionesActivos = Direccion::where('estado', 'Activo')->get();

        // Conteos generales
        $totalEquipos = $equiposActivos->count();        // Número total de equipos activos
        $totalUsuarios = Usuario::count();              // Número total de usuarios
        $totalDirecciones = $direccionesActivos->count(); // Número total de direcciones activas

        // Estado funcional de los equipos
        // Categoriza los equipos según su estado funcional
        $estadoFuncional = [
            'Operativo' => $equiposActivos->where('estado_funcional', 'Operativo')->count(),
            'Buen Funcionamiento' => $equiposActivos->where('estado_funcional', 'Buen Funcionamiento')->count(),
            'Sin Funcionar' => $equiposActivos->where('estado_funcional', 'Sin Funcionar')->count(),
        ];

        // Estado tecnológico de los equipos
        // Categoriza los equipos según su antigüedad o capacidad de actualización
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
            'Deteriorado' => $gabinetesActivos->where('estado_gabinete', 'Deteriorado')->count(),
            'Dañado' => $gabinetesActivos->where('estado_gabinete', 'Dañado')->count(),
        ];

        // Retornar la vista del dashboard con todos los datos
        return view('dashboard.index', compact(
            'usuario',          // Usuario autenticado
            'totalEquipos',     // Total de equipos activos
            'totalUsuarios',    // Total de usuarios registrados
            'totalDirecciones', // Total de direcciones activas
            'estadoFuncional',  // Conteo de equipos según estado funcional
            'estadoTecnologico', // Conteo de equipos según estado tecnológico
            'estadoGabinete'    // Conteo de gabinetes según su estado
        ));
    }
}
