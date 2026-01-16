<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Software;
use App\Models\Equipo;
use Illuminate\Support\Facades\Auth;

/**
 * Controlador para manejar los Softwares.
 *
 * Proporciona funcionalidades CRUD (Crear, Leer, Actualizar, Eliminar)
 * para los softwares asociados a los equipos de la organización, incluyendo:
 * - Listado de software con sus equipos relacionados.
 * - Creación y edición de registros de software.
 * - Eliminación de software.
 * - Registro de acciones en logs para auditoría.
 */

class SoftwareController extends Controller
{
    /**
     * Listar todos los softwares con sus equipos relacionados.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $software = Software::with('equipo')->get();
        return view('software.index', compact('software'));
    }

    /**
     * Mostrar formulario para agregar un nuevo software.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $equipos = Equipo::all();
        return view('software.create', compact('equipos'));
    }

    /**
     * Guardar un nuevo software en la base de datos.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'id_equipo' => 'required|integer|exists:equipos,id_equipo',
            'version' => 'nullable|string|max:50',
            'licencia' => 'nullable|string|max:255',
        ]);

        Software::create([
            'nombre' => $request->nombre,
            'id_equipo' => $request->id_equipo,
            'version' => $request->version,
            'licencia' => $request->licencia,
        ]);

        /** @var \App\Models\Usuario|null $usuario */
        $usuario = Auth::user();
        logAction($usuario->name ?? 'sistema', "Agregó software: " . $request->nombre);

        return redirect()->route('software.index')->with('success', 'Software agregado correctamente.');
    }

    /**
     * Mostrar formulario para editar un software existente.
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $software = Software::findOrFail($id);
        $equipos = Equipo::all();
        return view('software.edit', compact('software', 'equipos'));
    }

    /**
     * Actualizar los datos de un software existente.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $software = Software::findOrFail($id);

        $request->validate([
            'nombre' => 'required|string|max:255',
            'id_equipo' => 'required|integer|exists:equipos,id_equipo',
            'version' => 'nullable|string|max:50',
            'licencia' => 'nullable|string|max:255',
        ]);

        $software->update([
            'nombre' => $request->nombre,
            'id_equipo' => $request->id_equipo,
            'version' => $request->version,
            'licencia' => $request->licencia,
        ]);

        /** @var \App\Models\Usuario|null $usuario */
        $usuario = Auth::user();
        logAction($usuario->name ?? 'sistema', "Editó software ID: $id");

        return redirect()->route('software.index')->with('success', 'Software actualizado correctamente.');
    }

    /**
     * Eliminar un software de la base de datos.
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $software = Software::findOrFail($id);
        $software->delete();

        /** @var \App\Models\Usuario|null $usuario */
        $usuario = Auth::user();
        logAction($usuario->name ?? 'sistema', "Eliminó software ID: $id");

        return redirect()->route('software.index')->with('success', 'Software eliminado correctamente.');
    }
}
