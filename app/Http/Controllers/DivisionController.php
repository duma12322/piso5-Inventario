<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Division;
use App\Models\Direccion;
use App\Models\Log as LogModel;
use Illuminate\Support\Facades\Auth;

class DivisionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); // protege todas las rutas
    }

    /**
     * Mostrar todas las divisiones activas con su dirección.
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

        // Paginación 10 por página
        $divisiones = $query->orderBy('nombre_division', 'asc')->paginate(10)->withQueryString();

        return view('divisiones.index', compact('divisiones'));
    }

    /**
     * Mostrar formulario de creación.
     */
    public function create()
    {
        $direcciones = Direccion::activos()->get();
        return view('divisiones.create', compact('direcciones'));
    }

    /**
     * Guardar nueva división.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre_division' => 'required|string|max:255',
            'id_direccion' => 'required|integer|exists:direcciones,id_direccion',
        ]);

        $division = Division::create([
            'nombre_division' => $request->nombre_division,
            'id_direccion' => $request->id_direccion,
            'estado' => 'Activo',
        ]);

        $usuario = Auth::check() ? Auth::user()->name ?? Auth::user()->usuario : 'Sistema';

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
            \Illuminate\Support\Facades\Log::error('Error guardando log: ' . $e->getMessage());
        }

        return redirect()->route('divisiones.index')
            ->with('success', 'División agregada correctamente.');
    }

    /**
     * Mostrar formulario de edición.
     */
    public function edit($id)
    {
        $division = Division::findOrFail($id);
        $direcciones = Direccion::activos()->get();
        return view('divisiones.edit', compact('division', 'direcciones'));
    }

    /**
     * Actualizar división.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre_division' => 'required|string|max:255',
            'id_direccion' => 'required|integer|exists:direcciones,id_direccion',
        ]);

        $division = Division::findOrFail($id);
        $division->update([
            'nombre_division' => $request->nombre_division,
            'id_direccion' => $request->id_direccion,
        ]);

        $usuario = Auth::check() ? Auth::user()->name ?? Auth::user()->usuario : 'Sistema';

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
     * Eliminar división (borrado lógico).
     */
    public function destroy($id)
    {
        $division = Division::findOrFail($id);
        $division->estado = 'Inactivo';
        $division->save();

        $usuario = Auth::check() ? Auth::user()->name ?? Auth::user()->usuario : 'Sistema';

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
     * Obtener divisiones activas por dirección (AJAX)
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
