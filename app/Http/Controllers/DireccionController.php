<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Direccion;
use App\Models\Division;
use App\Models\Log as LogModel;
use Illuminate\Support\Facades\Auth;

class DireccionController extends Controller
{
    /**
     * Constructor del controlador.
     * Aplica middleware 'auth' a todas las rutas para protegerlas
     * y asegurarse de que solo usuarios autenticados puedan acceder.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Mostrar todas las direcciones activas.
     * Permite buscar por nombre de dirección y paginar resultados.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = Direccion::activos();

        // Buscador por nombre_direccion
        if ($request->filled('search')) {
            $query->where('nombre_direccion', 'like', "%{$request->search}%");
        }

        // Paginación: 10 registros por página, ordenados alfabéticamente
        $direcciones = $query->orderBy('nombre_direccion', 'asc')->paginate(10)->withQueryString();

        return view('direcciones.index', compact('direcciones'));
    }

    /**
     * Mostrar formulario de creación de nueva dirección.
     * Trae todas las direcciones activas por si se necesitan referencias.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $direcciones = Direccion::activos()->get(); // traer direcciones activas
        return view('direcciones.create', compact('direcciones'));
    }

    /**
     * Guardar una nueva dirección en la base de datos.
     * Valida el nombre y registra un log de la acción.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validación de campos requeridos
        $request->validate([
            'nombre_direccion' => 'required|string|max:255',
        ]);

        // Crear la dirección
        $direccion = Direccion::create([
            'nombre_direccion' => $request->nombre_direccion,
            'estado' => 'Activo'
        ]);

        // Obtener usuario actual o 'Sistema' si no está autenticado
        $usuario = Auth::check() ? Auth::user()->name ?? Auth::user()->usuario : 'Sistema';

        // Guardar log de acción
        try {
            LogModel::create([
                'usuario' => $usuario,
                'accion' => 'Agregó dirección: ' . $direccion->nombre_direccion,
                'detalles' => json_encode(['nombre_direccion' => $request->nombre_direccion]),
                'fecha' => now()
            ]);
        } catch (\Exception $e) {
            // Registrar error si falla el guardado del log
            \Illuminate\Support\Facades\Log::error('Error guardando log: ' . $e->getMessage());
        }

        return redirect()->route('direcciones.index')
            ->with('success', 'Dirección agregada correctamente.');
    }

    /**
     * Mostrar formulario de edición para una dirección existente.
     *
     * @param Direccion $direccion
     * @return \Illuminate\View\View
     */
    public function edit(Direccion $direccion)
    {
        return view('direcciones.edit', compact('direccion'));
    }

    /**
     * Actualizar la información de una dirección existente.
     * Valida los campos requeridos y registra un log de la acción.
     *
     * @param Request $request
     * @param Direccion $direccion
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Direccion $direccion)
    {
        // Validación de campos
        $request->validate([
            'nombre_direccion' => 'required|string|max:255',
        ]);

        // Actualizar la dirección
        $direccion->update([
            'nombre_direccion' => $request->nombre_direccion
        ]);

        $usuario = Auth::check() ? Auth::user()->name ?? Auth::user()->usuario : 'Sistema';

        // Guardar log de acción
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
     * Eliminar una dirección (borrado lógico).
     * Cambia el estado a 'Inactivo' y registra un log de la acción.
     *
     * @param Direccion $direccion
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Direccion $direccion)
    {
        $direccion->estado = 'Inactivo';
        $direccion->save();

        $usuario = Auth::check() ? Auth::user()->name ?? Auth::user()->usuario : 'Sistema';

        // Guardar log de acción
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
