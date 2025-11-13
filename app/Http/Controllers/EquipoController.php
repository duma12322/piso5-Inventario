<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Equipo;
use App\Models\Software;
use App\Models\Direccion;
use App\Models\Division;
use App\Models\Coordinacion;
use App\Models\Usuario;
use App\Models\Log as LogModel;
use Illuminate\Support\Facades\Auth;

class EquipoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Listado de equipos activos
    public function index()
    {
        $equipos = Equipo::with(['direccion', 'division', 'coordinacion'])
            ->activos()
            ->get();

        foreach ($equipos as $e) {
            $resultado = $e->calcularEstadoTecnologico();
            $e->estado = $resultado['estado'];
            $e->mensajes = $resultado['mensajes'] ?? [];
        }

        return view('equipos.index', compact('equipos'));
    }

    // Crear equipo
    public function create()
    {
        $direcciones = Direccion::all();
        $divisiones = Division::all();
        $coordinaciones = Coordinacion::all();

        $tiposSoftware = [
            'Sistema Operativo' => ['Windows', 'Linux', 'MacOS'],
            'Ofimática' => ['Microsoft Office', 'LibreOffice', 'OnlyOffice', 'WPS Office', 'Otro'],
            'Navegador' => ['Chrome', 'Firefox', 'Edge', 'Opera', 'Internet Explore'],
            'Otro' => ['Antivirus', 'Editor', 'Otro']
        ];


        // Estructura vacía para nuevo equipo
        $softwareActual = [
            'SO' => null,
            'Ofimática' => null,
            'Navegador' => [],
            'Otro' => []
        ];

        return view('equipos.create', compact(
            'direcciones',
            'divisiones',
            'coordinaciones',
            'tiposSoftware',
            'softwareActual'
        ));
    }


    // Guardar equipo + software
    public function store(Request $request)
    {
        $equipo = Equipo::create([
            'marca' => $request->input('marca'),
            'modelo' => $request->input('modelo'),
            'serial' => $request->input('serial'),
            'numero_bien' => $request->input('numero_bien'),
            'tipo_gabinete' => $request->input('tipo_gabinete'),
            'id_direccion' => $request->input('id_direccion'),
            'id_division' => $request->input('id_division'),
            'id_coordinacion' => $request->input('id_coordinacion'),
            'estado_funcional' => $request->input('estado_funcional', 'Bueno'),
            'estado_tecnologico' => 'Nuevo',
            'estado_gabinete' => $request->input('estado_gabinete', 'Nuevo')
        ]);

        // Guardar software
        $software = $this->buildSoftwareArray($request);
        foreach ($software as $s) {
            $s['id_equipo'] = $equipo->id_equipo;
            Software::create($s);
        }

        $usuario = Auth::check() ? Auth::user()->usuario : 'Sistema';

        // Obtener información del equipo
        $equipoInfo = $equipo ? $equipo->marca . ' ' . $equipo->modelo : "ID: {$equipo->id_equipo}";

        LogModel::create([
            'usuario' => $usuario,
            'accion' => "Creado equipo: $equipoInfo",
            'fecha' => now()
        ]);


        return redirect()->route('equipos.index')->with('success', 'Equipo agregado correctamente.');
    }

    // Editar equipo
    public function edit($id)
    {
        $equipo = Equipo::with('softwareItems')->findOrFail($id);

        $direcciones = Direccion::all();
        $divisiones = Division::all();
        $coordinaciones = Coordinacion::all();

        $tiposSoftware = [
            'Sistema Operativo' => ['Windows', 'Linux', 'MacOS'],
            'Ofimática' => ['Microsoft Office', 'LibreOffice', 'OnlyOffice', 'WPS Office', 'Otro'],
            'Navegador' => ['Chrome', 'Firefox', 'Edge', 'Opera', 'Internet Explore'],
            'Otro' => ['Antivirus', 'Editor', 'Otro']
        ];

        $softwareActual = [
            'SO' => null,
            'Ofimática' => null,
            'Navegador' => [],
            'Otro' => []
        ];

        foreach ($equipo->softwareItems as $s) {
            switch ($s->tipo) {
                case 'Sistema Operativo':
                    $softwareActual['SO'] = [
                        'nombre' => $s->nombre,
                        'version' => $s->version,
                        'bits' => $s->bits
                    ];
                    break;
                case 'Ofimática':
                    $softwareActual['Ofimática'] = [
                        'nombre' => $s->nombre,
                        'version' => $s->version,
                        'bits' => $s->bits
                    ];
                    break;
                case 'Navegador':
                    $softwareActual['Navegador'][] = $s->nombre; // Aquí agregamos todos los navegadores
                    break;
                case 'Otro':
                    $softwareActual['Otro'][] = [
                        'nombre' => $s->nombre,
                        'version' => $s->version
                    ];
                    break;
            }
        }


        return view('equipos.edit', compact(
            'equipo',
            'direcciones',
            'divisiones',
            'coordinaciones',
            'tiposSoftware',
            'softwareActual'
        ));
    }


    // Actualizar equipo + software
    // Actualizar equipo + software
    public function update(Request $request, $id)
    {
        $equipo = Equipo::with('softwareItems')->findOrFail($id);

        // Validación básica
        $request->validate([
            'marca' => 'required|string|max:255',
            'modelo' => 'required|string|max:255',
            'serial' => 'nullable|string|max:255',
            'numero_bien' => 'nullable|string|max:255',
            'tipo_gabinete' => 'nullable|string|max:255',
            'estado_gabinete' => 'nullable|in:Nuevo,Deteriorado,Dañado',
            'estado_funcional' => 'nullable|in:Buen Funcionamiento,Operativo,Sin Funcionar',
            'estado_tecnologico' => 'nullable|in:Nuevo,Actualizable,Obsoleto',
            'id_direccion' => 'nullable|exists:direcciones,id_direccion',
            'id_division' => 'nullable|exists:divisiones,id_division',
            'id_coordinacion' => 'nullable|exists:coordinaciones,id_coordinacion',
        ]);

        // Actualizar datos del equipo
        $equipo->update([
            'marca' => $request->marca,
            'modelo' => $request->modelo,
            'serial' => $request->serial,
            'numero_bien' => $request->numero_bien,
            'tipo_gabinete' => $request->tipo_gabinete,
            'estado_gabinete' => $request->estado_gabinete,
            'estado_funcional' => $request->estado_funcional,
            'estado_tecnologico' => $request->estado_tecnologico,
            'id_direccion' => $request->id_direccion,
            'id_division' => $request->id_division,
            'id_coordinacion' => $request->id_coordinacion,
        ]);

        // Actualizar software
        $nuevoSoftware = $this->buildSoftwareArray($request);

        // Eliminar software anterior
        $equipo->softwareItems()->delete();

        // Crear nuevo software
        foreach ($nuevoSoftware as $s) {
            $s['id_equipo'] = $equipo->id_equipo;
            Software::create($s);
        }

        // Registrar log
        $usuario = Auth::check() ? Auth::user()->usuario : 'Sistema';
        $equipoInfo = $equipo->marca . ' ' . $equipo->modelo;

        LogModel::create([
            'usuario' => $usuario,
            'accion' => "Actualizó equipo: $equipoInfo",
            'fecha' => now()
        ]);

        return redirect()->route('equipos.index')->with('success', 'Equipo actualizado correctamente.');
    }


    // Eliminar (Logico) equipo y todo lo relacionado
    public function destroy($id)
    {
        $equipo = Equipo::with(['componentes.componentesOpcionales', 'softwareItems'])->findOrFail($id);

        // Marcar equipo como inactivo
        $equipo->estado = 'Inactivo';
        $equipo->save();

        // Eliminar software relacionado
        $equipo->softwareItems()->delete();

        // Eliminar componentes y sus opcionales
        foreach ($equipo->componentes as $componente) {
            $componente->componentesOpcionales()->delete();
            $componente->delete();
        }

        // Registrar log
        $usuario = Auth::check() ? Auth::user()->usuario : 'Sistema';
        $equipoInfo = $equipo ? $equipo->marca . ' ' . $equipo->modelo : "ID: {$equipo->id_equipo}";

        LogModel::create([
            'usuario' => $usuario,
            'accion' => "Eliminado equipo: $equipoInfo",
            'fecha' => now()
        ]);

        return redirect()->route('equipos.index')->with('success', 'Equipo y sus componentes eliminados correctamente.');
    }

    // Construye array de software para guardar
    private function buildSoftwareArray(Request $request)
    {
        $software = [];

        // Sistema Operativo
        if ($request->filled('software_nombre.SO')) {
            $software[] = [
                'tipo' => 'Sistema Operativo',
                'nombre' => $request->input('software_nombre.SO'),
                'version' => $request->input('software_version.SO', ''),
                'bits' => $request->input('software_bits.SO', null)
            ];
        }

        // Ofimática (select)
        if ($request->filled('software_nombre_ofimatica')) {
            $software[] = [
                'tipo' => 'Ofimática',
                'nombre' => $request->input('software_nombre_ofimatica'),
                'version' => $request->input('software_version_ofimatica', ''),
                'bits' => $request->input('software_bits_ofimatica', null)
            ];
        }

        // Navegadores (checkboxes)
        $navs = $request->input('software_navegadores', []);
        foreach ($navs as $nav) {
            if (!empty($nav)) {
                $software[] = [
                    'tipo' => 'Navegador',
                    'nombre' => $nav,
                    'version' => '',
                    'bits' => null
                ];
            }
        }

        return $software;
    }
}
