<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Direccion;
use App\Models\Coordinacion;
use App\Models\Division;
use App\Models\Log as LogModel;
use Illuminate\Support\Facades\Auth;

/**
 * Controlador para manejar las Coordinaciones.
 *
 * Proporciona funcionalidades CRUD (Crear, Leer, Actualizar, Eliminar)
 * para las coordinaciones de la organización, incluyendo:
 * - Listado de coordinaciones con búsqueda y paginación.
 * - Creación y edición de coordinaciones.
 * - Eliminación lógica (inactivación) de coordinaciones.
 * - Registro de acciones en logs para auditoría.
 */
class CoordinacionController extends Controller
{
    /**
     * Constructor del controlador.
     *
     * Aplica el middleware 'auth' a todas las rutas, protegiéndolas para
     * que solo usuarios autenticados puedan acceder.
     */
    public function __construct()
    {
        $this->middleware('auth'); // protege todas las rutas
    }

    /**
     * Mostrar todas las coordinaciones activas con su división.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     *
     * Permite filtrar por nombre de coordinación o nombre de división
     * mediante un campo 'search' en la solicitud.
     */
    public function index(Request $request)
    {
        $query = Coordinacion::activos()->with('division');

        // Buscador por nombre_coordinacion o nombre_division
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('nombre_coordinacion', 'like', "%{$search}%")
                ->orWhereHas('division', function ($q) use ($search) {
                    $q->where('nombre_division', 'like', "%{$search}%");
                });
        }

        // Paginación 10 por página
        $coordinaciones = $query->orderBy('nombre_coordinacion', 'asc')->paginate(10)->withQueryString();

        return view('coordinaciones.index', compact('coordinaciones'));
    }

    /**
     * Obtener coordinaciones activas por división (AJAX).
     *
     * @param int $id_division
     * @return \Illuminate\Http\Response
     *
     * Devuelve un listado de <option> para un select en la vista
     * según la división seleccionada.
     */
    public function getByDivisionAjax($id_division)
    {
        $coordinaciones = Coordinacion::activos()->where('id_division', $id_division)->get();
        $options = '<option value="">Seleccione</option>';
        foreach ($coordinaciones as $c) {
            $options .= "<option value='{$c->id_coordinacion}'>{$c->nombre_coordinacion}</option>";
        }
        return response($options);
    }

    /**
     * Mostrar formulario de creación de una coordinación.
     *
     * Trae todas las divisiones y direcciones activas para poblar selects.
     */
    public function create()
    {
        $divisiones = Division::activas()->get();
        $direcciones = Direccion::activos()->get(); // Traer direcciones activas
        return view('coordinaciones.create', compact('divisiones', 'direcciones'));
    }

    /**
     * Guardar nueva coordinación en la base de datos.
     *
     * Valida los campos necesarios antes de crear la coordinación.
     * Registra la acción en la tabla de logs.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre_coordinacion' => 'required|string|max:255',
            'id_division' => 'required|integer|exists:divisiones,id_division'
        ]);

        $coordinacion = Coordinacion::create([
            'nombre_coordinacion' => $request->nombre_coordinacion,
            'id_division' => $request->id_division,
            'estado' => 'Activo',
        ]);

        $usuario = Auth::check() ? Auth::user()->name ?? Auth::user()->usuario : 'Sistema';

        try {
            LogModel::create([
                'usuario' => $usuario,
                'accion' => 'Agregó coordinación: ' . $coordinacion->nombre_coordinacion,
                'detalles' => json_encode([
                    'nombre_coordinacion' => $request->nombre_coordinacion,
                    'id_division' => $request->id_division
                ]),
                'fecha' => now()
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error guardando log: ' . $e->getMessage());
        }

        return redirect()->route('coordinaciones.index')
            ->with('success', 'Coordinación agregada correctamente.');
    }

    /**
     * Mostrar formulario de edición de una coordinación existente.
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $coordinacion = Coordinacion::with('division.direccion')->findOrFail($id);
        $divisiones = Division::with('direccion')->get();
        $direcciones = Direccion::all();

        return view('coordinaciones.edit', compact('coordinacion', 'divisiones', 'direcciones'));
    }

    /**
     * Actualizar coordinación existente.
     *
     * Valida los campos antes de actualizar.
     * Registra la acción en la tabla de logs.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre_coordinacion' => 'required|string|max:255',
            'id_division' => 'required|integer|exists:divisiones,id_division'
        ]);

        $coordinacion = Coordinacion::findOrFail($id);
        $coordinacion->update([
            'nombre_coordinacion' => $request->nombre_coordinacion,
            'id_division' => $request->id_division
        ]);

        $usuario = Auth::check() ? Auth::user()->name ?? Auth::user()->usuario : 'Sistema';

        try {
            LogModel::create([
                'usuario' => $usuario,
                'accion' => 'Editó coordinación: ' . $coordinacion->nombre_coordinacion,
                'detalles' => json_encode([
                    'nombre_coordinacion' => $request->nombre_coordinacion,
                    'id_division' => $request->id_division
                ]),
                'fecha' => now()
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error guardando log: ' . $e->getMessage());
        }

        return redirect()->route('coordinaciones.index')
            ->with('success', 'Coordinación actualizada correctamente.');
    }

    /**
     * Eliminar coordinación (borrado lógico).
     *
     * Cambia el estado a 'Inactivo' en lugar de eliminar físicamente.
     * Registra la acción en la tabla de logs.
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $coordinacion = Coordinacion::findOrFail($id);
        $coordinacion->estado = 'Inactivo';
        $coordinacion->save();

        $usuario = Auth::check() ? Auth::user()->name ?? Auth::user()->usuario : 'Sistema';

        try {
            LogModel::create([
                'usuario' => $usuario,
                'accion' => 'Eliminó coordinación: ' . $coordinacion->nombre_coordinacion,
                'fecha' => now()
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error guardando log: ' . $e->getMessage());
        }

        return redirect()->route('coordinaciones.index')
            ->with('success', 'Coordinación eliminada correctamente (lógicamente).');
    }
}
