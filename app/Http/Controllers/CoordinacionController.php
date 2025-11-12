<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Direccion;
use App\Models\Coordinacion;
use App\Models\Division;
use App\Models\Log as LogModel;
use Illuminate\Support\Facades\Auth;

class CoordinacionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); // protege todas las rutas
    }

    /**
     * Mostrar todas las coordinaciones activas con su división.
     */
    public function index()
    {
        $coordinaciones = Coordinacion::activos()->with('division')->get();
        return view('coordinaciones.index', compact('coordinaciones'));
    }


    /**
     * Obtener coordinaciones activas por división (AJAX)
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
     * Mostrar formulario de creación.
     */
    public function create()
    {
        $divisiones = Division::activas()->get();
        $direcciones = Direccion::activos()->get(); // Traer direcciones activas
        return view('coordinaciones.create', compact('divisiones', 'direcciones'));
    }


    /**
     * Guardar nueva coordinación.
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
     * Mostrar formulario de edición.
     */
    public function edit($id)
    {
        $coordinacion = Coordinacion::with('division.direccion')->findOrFail($id);
        $divisiones = Division::with('direccion')->get();
        $direcciones = \App\Models\Direccion::all();

        return view('coordinaciones.edit', compact('coordinacion', 'divisiones', 'direcciones'));
    }


    /**
     * Actualizar coordinación.
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
