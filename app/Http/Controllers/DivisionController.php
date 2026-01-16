<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Division;
use App\Models\Direccion;
use App\Models\Log as LogModel;
use Illuminate\Support\Facades\Auth;

class DivisionController extends Controller
{
    /**
     * Constructor del controlador.
     * Aplica middleware 'auth' a todas las rutas de este controlador,
     * asegurando que solo usuarios autenticados puedan acceder.
     */
    public function __construct()
    {
        $this->middleware('auth'); // protege todas las rutas
    }

    /**
     * Mostrar todas las divisiones activas con su dirección asociada.
     * Permite buscar por nombre de división o nombre de dirección.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = Division::activas()->with('direccion');

        // Buscador por nombre_division o nombre_direccion
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('nombre_division', 'like', "%{$search}%")
                ->orWhereHas('direccion', function ($q) use ($search) {
                    $q->where('nombre_direccion', 'like', "%{$search}%");
                });
        }

        // Paginación: 10 registros por página, ordenados alfabéticamente
        $divisiones = $query->orderBy('nombre_division', 'asc')->paginate(10)->withQueryString();

        return view('divisiones.index', compact('divisiones'));
    }

    /**
     * Mostrar formulario para crear una nueva división.
     * Trae todas las direcciones activas para asignarlas a la división.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $direcciones = Direccion::activos()->get();
        return view('divisiones.create', compact('direcciones'));
    }

    /**
     * Guardar una nueva división en la base de datos.
     * Valida los campos requeridos y registra un log de la acción.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validación de campos
        $request->validate([
            'nombre_division' => 'required|string|max:255',
            'id_direccion' => 'required|integer|exists:direcciones,id_direccion',
        ]);

        // Crear la división
        $division = Division::create([
            'nombre_division' => $request->nombre_division,
            'id_direccion' => $request->id_direccion,
            'estado' => 'Activo',
        ]);

        // Obtener usuario actual o 'Sistema' si no está autenticado
        $usuario = Auth::check() ? Auth::user()->name ?? Auth::user()->usuario : 'Sistema';

        // Guardar log de acción
        try {
            LogModel::create([
                'usuario' => $usuario,
                'accion' => 'Agregó división: ' . $division->nombre_division,
                'detalles' => json_encode([
                    'nombre_division' => $request->nombre_division,
                    'id_direccion' => $request->id_direccion
                ]),
                'fecha' => now()
            ]);
        } catch (\Exception $e) {
            // Registrar error en log si falla el guardado del log
            \Illuminate\Support\Facades\Log::error('Error guardando log: ' . $e->getMessage());
        }

        return redirect()->route('divisiones.index')
            ->with('success', 'División agregada correctamente.');
    }

    /**
     * Mostrar formulario para editar una división existente.
     * Trae la división y todas las direcciones activas.
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $division = Division::findOrFail($id);
        $direcciones = Direccion::activos()->get();
        return view('divisiones.edit', compact('division', 'direcciones'));
    }

    /**
     * Actualizar la información de una división existente.
     * Valida los campos requeridos y registra un log de la acción.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        // Validación de campos
        $request->validate([
            'nombre_division' => 'required|string|max:255',
            'id_direccion' => 'required|integer|exists:direcciones,id_direccion',
        ]);

        // Actualizar la división
        $division = Division::findOrFail($id);
        $division->update([
            'nombre_division' => $request->nombre_division,
            'id_direccion' => $request->id_direccion,
        ]);

        $usuario = Auth::check() ? Auth::user()->name ?? Auth::user()->usuario : 'Sistema';

        // Guardar log de acción
        try {
            LogModel::create([
                'usuario' => $usuario,
                'accion' => 'Editó división: ' . $division->nombre_division,
                'detalles' => json_encode([
                    'nombre_division' => $request->nombre_division,
                    'id_direccion' => $request->id_direccion
                ]),
                'fecha' => now()
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error guardando log: ' . $e->getMessage());
        }

        return redirect()->route('divisiones.index')
            ->with('success', 'División actualizada correctamente.');
    }

    /**
     * Eliminar una división (borrado lógico, no físico).
     * Cambia el estado a 'Inactivo' y registra un log de la acción.
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $division = Division::findOrFail($id);
        $division->estado = 'Inactivo';
        $division->save();

        $usuario = Auth::check() ? Auth::user()->name ?? Auth::user()->usuario : 'Sistema';

        // Guardar log de acción
        try {
            LogModel::create([
                'usuario' => $usuario,
                'accion' => 'Eliminó división: ' . $division->nombre_division,
                'fecha' => now()
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error guardando log: ' . $e->getMessage());
        }

        return redirect()->route('divisiones.index')
            ->with('success', 'División eliminada correctamente.');
    }

    /**
     * Obtener divisiones activas por dirección vía AJAX.
     * Genera opciones HTML para un select.
     *
     * @param int $id_direccion
     * @return \Illuminate\Http\Response
     */
    public function getByDireccionAjax($id_direccion)
    {
        $divisiones = Division::activas()->where('id_direccion', $id_direccion)->get();
        $options = '<option value="">Seleccione</option>';
        foreach ($divisiones as $d) {
            $options .= "<option value='{$d->id_division}'>{$d->nombre_division}</option>";
        }
        return response($options);
    }
}
