<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Equipo;
use App\Models\Software;
use App\Models\Direccion;
use App\Models\Division;
use App\Models\Coordinacion;
use App\Models\Componente;
use App\Models\ComponenteOpcional;
use App\Models\Log as LogModel;
use Illuminate\Support\Facades\Auth;

class EquipoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Listado de equipos activos con buscador mejorado
    public function index(Request $request)
    {
        $search = trim($request->input('search'));

        $equipos = Equipo::with(['direccion', 'division', 'coordinacion'])
            ->where('estado', 'Activo')
            ->when($search, function ($query) use ($search) {
                // Limpiar y dividir términos
                $search = preg_replace('/[^\wñÑáéíóúÁÉÍÓÚ@.-]+/u', ' ', $search);
                $terms = array_filter(explode(' ', $search));

                $query->where(function ($q) use ($terms) {
                    foreach ($terms as $term) {
                        $term = "%{$term}%";
                        $q->where(function ($subQuery) use ($term) {
                            // Búsqueda en campos principales del equipo
                            $subQuery->where('marca', 'LIKE', $term)
                                ->orWhere('modelo', 'LIKE', $term)
                                ->orWhere('serial', 'LIKE', $term)
                                ->orWhere('numero_bien', 'LIKE', $term)
                                ->orWhere('estado_funcional', 'LIKE', $term)
                                ->orWhere('estado_tecnologico', 'LIKE', $term)
                                ->orWhere('estado_gabinete', 'LIKE', $term)
                                ->orWhere('tipo_gabinete', 'LIKE', $term)
                                // Búsqueda en relaciones
                                ->orWhereHas('direccion', function ($dirQuery) use ($term) {
                                $dirQuery->where('nombre_direccion', 'LIKE', $term);
                            })
                                ->orWhereHas('division', function ($divQuery) use ($term) {
                                $divQuery->where('nombre_division', 'LIKE', $term);
                            })
                                ->orWhereHas('coordinacion', function ($coordQuery) use ($term) {
                                $coordQuery->where('nombre_coordinacion', 'LIKE', $term);
                            });
                        });
                    }
                });
            })

            ->when($request->filled('sort_by'), function ($q) use ($request) {
                $sortBy = $request->input('sort_by');
                $order = $request->input('order', 'desc');

                // Mapeo seguro de campos válidos para ordenar
                $validSorts = [
                    'modelo' => 'modelo',
                    'estado_funcional' => 'estado_funcional',
                    'estado_tecnologico' => 'estado_tecnologico'
                ];

                if (array_key_exists($sortBy, $validSorts)) {
                    $q->orderBy($validSorts[$sortBy], $order);
                }
            }, function ($q) {
                // Orden por defecto si no se selecciona nada
                $q->orderBy('id_equipo', 'desc');
            })
            ->paginate(10)
            ->withQueryString();

        foreach ($equipos as $equipo) {
            $equipo->calcularEstadoTecnologico();
        }

        return view('equipos.index', compact('equipos', 'search'));
    }


    // Crear equipo
    public function create()
    {
        $direcciones = Direccion::all();
        $divisiones = Division::all();
        $coordinaciones = Coordinacion::with('division.direccion')->get()->map(function ($c) {
            return [
                'id_coordinacion' => $c->id_coordinacion,
                'nombre_coordinacion' => $c->nombre_coordinacion,
                'id_division' => $c->division->id_division ?? null,
                'id_direccion' => $c->division->direccion->id_direccion ?? null,
            ];
        });


        $tiposSoftware = [
            'Sistema Operativo' => ['Windows', 'Linux', 'MacOS'],
            'Ofimática' => ['Microsoft Office', 'LibreOffice', 'OnlyOffice', 'WPS Office', 'Otro'],
            'Navegador' => ['Chrome', 'Firefox', 'Edge', 'Opera', 'Internet Explore', 'Brave'],
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
        $coordinaciones = Coordinacion::with('division.direccion')->get()->map(function ($c) {
            return [
                'id_coordinacion' => $c->id_coordinacion,
                'nombre_coordinacion' => $c->nombre_coordinacion,
                'id_division' => $c->division->id_division ?? null,
                'id_direccion' => $c->division->direccion->id_direccion ?? null,
            ];
        });

        $tiposSoftware = [
            'Sistema Operativo' => ['Windows', 'Linux', 'MacOS'],
            'Ofimática' => ['Microsoft Office', 'LibreOffice', 'OnlyOffice', 'WPS Office', 'Otro'],
            'Navegador' => ['Chrome', 'Firefox', 'Edge', 'Opera', 'Internet Explore', 'Brave'],
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
            'estado_gabinete' => 'nullable|in:Nuevo,Deteriorado,Dañado,Buen Estado',
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

    public function indexInactivos(Request $request)
    {
        $direcciones = Direccion::orderBy('nombre_direccion', 'asc')->get();
        $divisiones = Division::orderBy('nombre_division', 'asc')->get();
        $coordinaciones = Coordinacion::orderBy('nombre_coordinacion', 'asc')->get();

        $query = Equipo::where('estado', 'Activo')
            ->with(['direccion', 'division', 'coordinacion', 'componentes.componentesOpcionales']);

        if ($request->filled('id_direccion')) {
            $query->where('id_direccion', $request->id_direccion);
        }
        if ($request->filled('id_division')) {
            $query->where('id_division', $request->id_division);
        }
        if ($request->filled('id_coordinacion')) {
            $query->where('id_coordinacion', $request->id_coordinacion);
        }

        $search = trim($request->input('search'));
        if ($search) {
            // Limpiar y dividir términos
            $search = preg_replace('/[^\wñÑáéíóúÁÉÍÓÚ@.-]+/u', ' ', $search);
            $terms = array_filter(explode(' ', $search));

            $query->where(function ($q) use ($terms) {
                foreach ($terms as $term) {
                    $term = "%{$term}%";
                    $q->where(function ($subQuery) use ($term) {
                        $subQuery->where('marca', 'LIKE', $term)
                            ->orWhere('modelo', 'LIKE', $term)
                            ->orWhere('serial', 'LIKE', $term)
                            ->orWhere('numero_bien', 'LIKE', $term)
                            ->orWhere('estado_funcional', 'LIKE', $term)
                            ->orWhere('estado_tecnologico', 'LIKE', $term)
                            ->orWhere('estado_gabinete', 'LIKE', $term)
                            ->orWhere('tipo_gabinete', 'LIKE', $term)
                            ->orWhereHas('direccion', function ($dirQuery) use ($term) {
                                $dirQuery->where('nombre_direccion', 'LIKE', $term);
                            })
                            ->orWhereHas('division', function ($divQuery) use ($term) {
                                $divQuery->where('nombre_division', 'LIKE', $term);
                            })
                            ->orWhereHas('coordinacion', function ($coordQuery) use ($term) {
                                $coordQuery->where('nombre_coordinacion', 'LIKE', $term);
                            });
                    });
                }
            });
        }

        // 🔥 Filtrar solo equipos que tengan componentes inactivos o opcionales inactivos
        $query->whereHas('componentes', function ($c) {
            $c->where('estado', 'Inactivo')
                ->orWhere('estadoElim', 'Inactivo')
                ->orWhereHas('componentesOpcionales', function ($o) {
                    $o->where('estadoElim', 'Inactivo');
                });
        });

        $equipos = $query->paginate(10)->withQueryString();

        // Agregar componentes y opcionales inactivos al resultado
        $equipos->transform(function ($equipo) {
            // Componentes inactivos
            $equipo->componentes_inactivos = $equipo->componentes
                ->filter(function ($c) {
                    return $c->estado === 'Inactivo' || $c->estadoElim === 'Inactivo';
                });

            // Opcionales inactivos (de cada componente)
            $opcionales = collect();
            foreach ($equipo->componentes as $componente) {
                $opcionales = $opcionales->merge(
                    $componente->componentesOpcionales->filter(function ($o) {
                        return $o->estadoElim === 'Inactivo';
                    })
                );
            }
            $equipo->opcionales_inactivos = $opcionales;

            return $equipo;
        });

        return view('equipos.inactivos', compact(
            'equipos',
            'direcciones',
            'divisiones',
            'coordinaciones'
        ));
    }

}
