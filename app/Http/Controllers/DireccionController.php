<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Direccion;
use App\Models\Division;
use App\Models\Log as LogModel;
use Illuminate\Support\Facades\Auth;

class DireccionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); // protege todas las rutas
    }

    /**
     * Mostrar todas las direcciones activas.
     */
    public function index(Request $request)
    {
        $query = Direccion::activos();

        // Buscador por nombre_direccion
        if ($request->filled('search')) {
            $query->where('nombre_direccion', 'like', "%{$request->search}%");
        }

        // Paginación 10 por página
        $direcciones = $query->orderBy('nombre_direccion', 'asc')->paginate(10)->withQueryString();

        return view('direcciones.index', compact('direcciones'));
    }

    /**
     * Mostrar formulario de creación.
     */
    public function create()
    {
        $direcciones = Direccion::activos()->get(); // traer direcciones activas
        return view('coordinaciones.create', compact('direcciones'));
    }

    /**
     * Guardar una nueva dirección.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre_direccion' => 'required|string|max:255',
        ]);

        $direccion = Direccion::create([
            'nombre_direccion' => $request->nombre_direccion,
            'estado' => 'Activo'
        ]);

        $usuario = Auth::check() ? Auth::user()->name ?? Auth::user()->usuario : 'Sistema';

        try {
            LogModel::create([
                'usuario' => $usuario,
                'accion' => 'Agregó dirección: ' . $direccion->nombre_direccion,
                'detalles' => json_encode(['nombre_direccion' => $request->nombre_direccion]),
                'fecha' => now()
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error guardando log: ' . $e->getMessage());
        }

        return redirect()->route('direcciones.index')
            ->with('success', 'Dirección agregada correctamente.');
    }

    /**
     * Mostrar formulario de edición.
     */
    public function edit(Direccion $direccion)
    {
        return view('direcciones.edit', compact('direccion'));
    }

    /**
     * Actualizar una dirección.
     */
    public function update(Request $request, Direccion $direccion)
    {
        $request->validate([
            'nombre_direccion' => 'required|string|max:255',
        ]);

        $direccion->update([
            'nombre_direccion' => $request->nombre_direccion
        ]);

        $usuario = Auth::check() ? Auth::user()->name ?? Auth::user()->usuario : 'Sistema';

        try {
            LogModel::create([
                'usuario' => $usuario,
                'accion' => 'Editó dirección: ' . $direccion->nombre_direccion,
                'detalles' => json_encode(['nombre_direccion' => $request->nombre_direccion]),
                'fecha' => now()
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error guardando log: ' . $e->getMessage());
        }

        return redirect()->route('direcciones.index')
            ->with('success', 'Dirección actualizada correctamente.');
    }

    /**
     * Eliminar una dirección (lógico).
     */
    public function destroy(Direccion $direccion)
    {
        $direccion->estado = 'Inactivo';
        $direccion->save();

        $usuario = Auth::check() ? Auth::user()->name ?? Auth::user()->usuario : 'Sistema';

        try {
            LogModel::create([
                'usuario' => $usuario,
                'accion' => 'Eliminó dirección: ' . $direccion->nombre_direccion,
                'fecha' => now()
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error guardando log: ' . $e->getMessage());
        }

        return redirect()->route('direcciones.index')
            ->with('success', 'Dirección eliminada correctamente.');
    }
}
