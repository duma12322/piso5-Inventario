<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Software;
use App\Models\Equipo;
use Illuminate\Support\Facades\Auth;


class SoftwareController extends Controller
{
    // Listar todos los softwares
    public function index()
    {
        $software = Software::with('equipo')->get();
        return view('software.index', compact('software'));
    }

    // Mostrar formulario para agregar software
    public function create()
    {
        $equipos = Equipo::all();
        return view('software.create', compact('equipos'));
    }

    // Guardar nuevo software
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

    // Mostrar formulario para editar software
    public function edit($id)
    {
        $software = Software::findOrFail($id);
        $equipos = Equipo::all();
        return view('software.edit', compact('software', 'equipos'));
    }

    // Actualizar software
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

    // Eliminar software
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
