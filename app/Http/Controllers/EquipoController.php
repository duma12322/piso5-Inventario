<?php

namespace App\Http\Controllers;

// Importar la clase Request de Laravel para manejar solicitudes HTTP
use Illuminate\Http\Request;

// Importar los modelos necesarios para el controlador
use App\Models\Equipo;              // Modelo principal de equipos
use App\Models\Software;            // Modelo de software asociado a equipos
use App\Models\Direccion;           // Modelo de direcciones
use App\Models\Division;            // Modelo de divisiones
use App\Models\Coordinacion;        // Modelo de coordinaciones
use App\Models\Componente;          // Modelo de componentes de equipos
use App\Models\ComponenteOpcional;  // Modelo de componentes opcionales
use App\Models\Log as LogModel;     // Modelo de logs para registrar acciones

// Importar la fachada Auth de Laravel para la autenticación de usuarios
use Illuminate\Support\Facades\Auth;

/**
 * Este archivo define el namespace y las importaciones para el controlador de equipos.
 * 
 * Explicación de cada importación:
 * 
 * - Request: Permite acceder a los datos de la petición HTTP y validar formularios.
 * - Equipo: Modelo principal que representa un equipo en el inventario.
 * - Software: Modelo que guarda información sobre el software instalado en un equipo.
 * - Direccion: Modelo que representa las direcciones de la organización.
 * - Division: Modelo que representa las divisiones dentro de una dirección.
 * - Coordinacion: Modelo que representa coordinaciones dentro de una división.
 * - Componente: Modelo de los componentes físicos de un equipo.
 * - ComponenteOpcional: Modelo de componentes opcionales asociados a un componente.
 * - LogModel: Modelo para registrar todas las acciones realizadas sobre los equipos y su inventario.
 * - Auth: Se utiliza para obtener información del usuario autenticado que realiza la acción.
 */

class EquipoController extends Controller
{
    /**
     * Constructor del controlador.
     * Aplica middleware 'auth' para proteger todas las rutas.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Mostrar listado de equipos activos con buscador avanzado y paginación.
     * Permite búsqueda en campos del equipo y en relaciones (dirección, división, coordinación).
     * También permite ordenar por campos específicos.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $search = trim($request->input('search')); // Tomar el valor de búsqueda y limpiar espacios

        $equipos = Equipo::with(['direccion', 'division', 'coordinacion']) // Cargar relaciones
            ->where('estado', 'Activo') // Solo equipos activos
            ->when($search, function ($query) use ($search) {
                // Limpiar caracteres no válidos y dividir en términos
                $search = preg_replace('/[^\wñÑáéíóúÁÉÍÓÚ@.-]+/u', ' ', $search);
                $terms = array_filter(explode(' ', $search));

                $query->where(function ($q) use ($terms) {
                    foreach ($terms as $term) {
                        $term = "%{$term}%"; // Formato LIKE para búsqueda parcial
                        $q->where(function ($subQuery) use ($term) {
                            // Buscar en campos principales del equipo
                            $subQuery->where('marca', 'LIKE', $term)
                                ->orWhere('modelo', 'LIKE', $term)
                                ->orWhere('serial', 'LIKE', $term)
                                ->orWhere('numero_bien', 'LIKE', $term)
                                ->orWhere('estado_funcional', 'LIKE', $term)
                                ->orWhere('estado_tecnologico', 'LIKE', $term)
                                ->orWhere('estado_gabinete', 'LIKE', $term)
                                ->orWhere('tipo_gabinete', 'LIKE', $term)
                                // Buscar en relaciones
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
                // Ordenamiento personalizado
                $sortBy = $request->input('sort_by');
                $order = $request->input('order', 'desc');

                // Campos válidos para ordenar
                $validSorts = [
                    'modelo' => 'modelo',
                    'estado_funcional' => 'estado_funcional',
                    'estado_tecnologico' => 'estado_tecnologico'
                ];

                if (array_key_exists($sortBy, $validSorts)) {
                    $q->orderBy($validSorts[$sortBy], $order);
                }
            }, function ($q) {
                // Orden por defecto
                $q->orderBy('id_equipo', 'desc');
            })
            ->paginate(10) // Paginación de 10 por página
            ->withQueryString(); // Mantener parámetros en la URL

        // Calcular estado tecnológico para cada equipo
        foreach ($equipos as $equipo) {
            $equipo->calcularEstadoTecnologico();
        }

        // Retornar vista con los equipos y búsqueda
        return view('equipos.index', compact('equipos', 'search'));
    }

    /**
     * Mostrar formulario para crear un nuevo equipo.
     * Carga relaciones necesarias (direcciones, divisiones, coordinaciones)
     * y estructura de software por categorías.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        // Obtener todas las direcciones
        $direcciones = Direccion::all();

        // Obtener todas las divisiones
        $divisiones = Division::all();

        // Obtener todas las coordinaciones con info de división y dirección
        $coordinaciones = Coordinacion::with('division.direccion')->get()->map(function ($c) {
            return [
                'id_coordinacion' => $c->id_coordinacion,
                'nombre_coordinacion' => $c->nombre_coordinacion,
                'id_division' => $c->division->id_division ?? null,
                'id_direccion' => $c->division->direccion->id_direccion ?? null,
            ];
        });

        // Tipos de software predefinidos para selección
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

        // Retornar vista con datos
        return view('equipos.create', compact(
            'direcciones',
            'divisiones',
            'coordinaciones',
            'tiposSoftware',
            'softwareActual'
        ));
    }

    /**
     * Guardar un nuevo equipo junto con su software.
     * 
     * @param Request $request Datos enviados desde el formulario
     * @return \Illuminate\Http\RedirectResponse Redirige al listado de equipos con mensaje de éxito
     */
    public function store(Request $request)
    {
        // Crear registro del equipo en la base de datos
        $equipo = Equipo::create([
            'marca' => $request->input('marca'),
            'modelo' => $request->input('modelo'),
            'serial' => $request->input('serial'),
            'numero_bien' => $request->input('numero_bien'),
            'tipo_gabinete' => $request->input('tipo_gabinete'),
            'id_direccion' => $request->input('id_direccion'),
            'id_division' => $request->input('id_division'),
            'id_coordinacion' => $request->input('id_coordinacion'),
            'estado_funcional' => $request->input('estado_funcional', 'Bueno'), // Valor por defecto: Bueno
            'estado_tecnologico' => 'Nuevo', // Siempre inicia como Nuevo
            'estado_gabinete' => $request->input('estado_gabinete', 'Nuevo') // Valor por defecto: Nuevo
        ]);

        // Construir array de software desde la request
        $software = $this->buildSoftwareArray($request);

        // Guardar cada software asociado al equipo
        foreach ($software as $s) {
            $s['id_equipo'] = $equipo->id_equipo;
            Software::create($s);
        }

        // Obtener nombre de usuario actual o 'Sistema' si no hay sesión
        $usuario = Auth::check() ? Auth::user()->usuario : 'Sistema';

        // Información descriptiva del equipo para el log
        $equipoInfo = $equipo ? $equipo->marca . ' ' . $equipo->modelo : "ID: {$equipo->id_equipo}";

        // Crear registro de log
        LogModel::create([
            'usuario' => $usuario,
            'accion' => "Creado equipo: $equipoInfo",
            'fecha' => now()
        ]);

        // Redireccionar al listado de equipos con mensaje de éxito
        return redirect()->route('equipos.index')->with('success', 'Equipo agregado correctamente.');
    }

    /**
     * Mostrar formulario para editar un equipo existente junto con su software.
     *
     * @param int $id ID del equipo a editar
     * @return \Illuminate\View\View Vista con datos del equipo y software
     */
    public function edit($id)
    {
        // Obtener el equipo con todos sus softwareItems relacionados
        $equipo = Equipo::with('softwareItems')->findOrFail($id);

        // Obtener todas las direcciones disponibles
        $direcciones = Direccion::all();

        // Obtener todas las divisiones disponibles
        $divisiones = Division::all();

        // Obtener coordinaciones con sus divisiones y direcciones
        $coordinaciones = Coordinacion::with('division.direccion')->get()->map(function ($c) {
            return [
                'id_coordinacion' => $c->id_coordinacion,
                'nombre_coordinacion' => $c->nombre_coordinacion,
                'id_division' => $c->division->id_division ?? null,
                'id_direccion' => $c->division->direccion->id_direccion ?? null,
            ];
        });

        // Tipos de software predefinidos para el formulario
        $tiposSoftware = [
            'Sistema Operativo' => ['Windows', 'Linux', 'MacOS'],
            'Ofimática' => ['Microsoft Office', 'LibreOffice', 'OnlyOffice', 'WPS Office', 'Otro'],
            'Navegador' => ['Chrome', 'Firefox', 'Edge', 'Opera', 'Internet Explore', 'Brave'],
            'Otro' => ['Antivirus', 'Editor', 'Otro']
        ];

        // Estructura vacía inicial para software del equipo
        $softwareActual = [
            'SO' => null,
            'Ofimática' => null,
            'Navegador' => [],
            'Otro' => []
        ];

        // Llenar softwareActual con los softwareItems existentes
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
                    $softwareActual['Navegador'][] = $s->nombre; // Se agregan todos los navegadores
                    break;
                case 'Otro':
                    $softwareActual['Otro'][] = [
                        'nombre' => $s->nombre,
                        'version' => $s->version
                    ];
                    break;
            }
        }

        // Retornar la vista de edición con todos los datos necesarios
        return view('equipos.edit', compact(
            'equipo',
            'direcciones',
            'divisiones',
            'coordinaciones',
            'tiposSoftware',
            'softwareActual'
        ));
    }

    /**
     * Actualizar un equipo existente junto con su software.
     * 
     * @param Request $request Datos enviados desde el formulario
     * @param int $id ID del equipo a actualizar
     * @return \Illuminate\Http\RedirectResponse Redirige al listado con mensaje de éxito
     */
    public function update(Request $request, $id)
    {
        // Obtener el equipo con todos sus softwareItems
        $equipo = Equipo::with('softwareItems')->findOrFail($id);

        // Validación de los campos principales del equipo
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

        // Actualizar los datos principales del equipo
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

        // Construir array de software actualizado desde la request
        $nuevoSoftware = $this->buildSoftwareArray($request);

        // Eliminar software anterior para reemplazarlo por el nuevo
        $equipo->softwareItems()->delete();

        // Guardar cada elemento de software actualizado
        foreach ($nuevoSoftware as $s) {
            $s['id_equipo'] = $equipo->id_equipo;
            Software::create($s);
        }

        // Registrar acción en el log
        $usuario = Auth::check() ? Auth::user()->usuario : 'Sistema';
        $equipoInfo = $equipo->marca . ' ' . $equipo->modelo;

        LogModel::create([
            'usuario' => $usuario,
            'accion' => "Actualizó equipo: $equipoInfo",
            'fecha' => now()
        ]);

        // Redirigir al listado de equipos con mensaje de éxito
        return redirect()->route('equipos.index')->with('success', 'Equipo actualizado correctamente.');
    }

    /**
     * Eliminar un equipo y todo lo relacionado (borrado lógico).
     * 
     * @param int $id ID del equipo a eliminar
     * @return \Illuminate\Http\RedirectResponse Redirige al listado con mensaje de éxito
     */
    public function destroy($id)
    {
        // Obtener equipo con componentes, componentes opcionales y software
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

        // Registrar acción en log
        $usuario = Auth::check() ? Auth::user()->usuario : 'Sistema';
        $equipoInfo = $equipo ? $equipo->marca . ' ' . $equipo->modelo : "ID: {$equipo->id_equipo}";

        LogModel::create([
            'usuario' => $usuario,
            'accion' => "Eliminado equipo: $equipoInfo",
            'fecha' => now()
        ]);

        // Redirigir al listado con mensaje de éxito
        return redirect()->route('equipos.index')->with('success', 'Equipo y sus componentes eliminados correctamente.');
    }

    /**
     * Construye un array de software a partir de los datos de la request
     * 
     * @param Request $request Datos enviados desde el formulario
     * @return array Array listo para insertar en la tabla software
     */
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

        // Ofimática
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

    /**
     * Mostrar listado de equipos que tienen componentes o componentes opcionales inactivos.
     * Permite filtrar por dirección, división, coordinación y búsqueda por texto.
     *
     * @param Request $request Datos enviados desde el formulario de filtros/búsqueda
     * @return \Illuminate\View\View Vista con equipos y filtros
     */
    public function indexInactivos(Request $request)
    {
        // Traer todas las direcciones, divisiones y coordinaciones para los filtros
        $direcciones = Direccion::orderBy('nombre_direccion', 'asc')->get();
        $divisiones = Division::orderBy('nombre_division', 'asc')->get();
        $coordinaciones = Coordinacion::orderBy('nombre_coordinacion', 'asc')->get();

        // Iniciar query para equipos activos con relaciones necesarias
        $query = Equipo::where('estado', 'Activo')
            ->with(['direccion', 'division', 'coordinacion', 'componentes.componentesOpcionales']);

        // Filtros por dirección, división o coordinación si se enviaron
        if ($request->filled('id_direccion')) {
            $query->where('id_direccion', $request->id_direccion);
        }
        if ($request->filled('id_division')) {
            $query->where('id_division', $request->id_division);
        }
        if ($request->filled('id_coordinacion')) {
            $query->where('id_coordinacion', $request->id_coordinacion);
        }

        // Búsqueda por texto libre
        $search = trim($request->input('search'));
        if ($search) {
            // Limpiar la cadena de búsqueda y dividirla en términos
            $search = preg_replace('/[^\wñÑáéíóúÁÉÍÓÚ@.-]+/u', ' ', $search);
            $terms = array_filter(explode(' ', $search));

            // Aplicar búsqueda sobre campos del equipo y sus relaciones
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

        // 🔥 Filtrar solo equipos que tengan componentes o componentes opcionales inactivos
        $query->whereHas('componentes', function ($c) {
            $c->where('estado', 'Inactivo')
                ->orWhere('estadoElim', 'Inactivo')
                ->orWhereHas('componentesOpcionales', function ($o) {
                    $o->where('estadoElim', 'Inactivo');
                });
        });

        // Obtener resultados paginados
        $equipos = $query->paginate(10)->withQueryString();

        // Transformar colección para agregar componentes y opcionales inactivos
        $equipos->transform(function ($equipo) {
            // Filtrar componentes inactivos
            $equipo->componentes_inactivos = $equipo->componentes
                ->filter(function ($c) {
                    return $c->estado === 'Inactivo' || $c->estadoElim === 'Inactivo';
                });

            // Filtrar opcionales inactivos de cada componente
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

        // Retornar vista con resultados y filtros
        return view('equipos.inactivos', compact(
            'equipos',
            'direcciones',
            'divisiones',
            'coordinaciones'
        ));
    }
}
