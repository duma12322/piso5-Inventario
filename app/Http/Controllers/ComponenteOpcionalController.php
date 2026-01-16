<?php

namespace App\Http\Controllers;

// -----------------------
// IMPORTACIONES
// -----------------------

// Importa el modelo Componente para manipular los componentes opcionales
use App\Models\ComponenteOpcional;

// Importa el modelo Componente para manipular los componentes principales
use App\Models\Componente;

// Importa el modelo Equipo para trabajar con información de los equipos
use App\Models\Equipo;

// Importa el modelo LogModel para guardar acciones de los usuarios en la base de datos
use App\Models\Log as LogModel;

// Importa la clase Request de Laravel para manejar solicitudes HTTP
use Illuminate\Http\Request;

// Importa la fachada Auth de Laravel para manejar la autenticación de usuarios
use Illuminate\Support\Facades\Auth;

// -----------------------
// DESCRIPCIÓN GENERAL DEL CONTROLADOR
// -----------------------
// Este controlador maneja todas las operaciones relacionadas con los componentes
// de los equipos, tanto los principales como los opcionales. Entre sus funciones
// se encuentran:
// 1. Creación, edición y eliminación de componentes.
// 2. Validaciones de compatibilidad (socket de procesador, tipo de RAM, slots libres, etc.).
// 3. Listado de componentes por equipo.
// 4. Preparación de datos para formularios, evitando duplicados de componentes únicos.
// 5. Registro de logs de acciones realizadas por los usuarios (creación, edición, eliminación).

// Nota: Este controlador hace uso de scopes y relaciones en los modelos para filtrar
// solo los componentes activos, calcular slots libres, y mantener la integridad de los datos.
class ComponenteOpcionalController extends Controller
{
    /**
     * Constructor del controlador
     * ----------------------------
     * Aplica el middleware 'auth' a todas las rutas de este controlador,
     * garantizando que solo usuarios autenticados puedan acceder a sus métodos.
     */
    public function __construct()
    {
        $this->middleware('auth'); // protege todas las rutas del controlador
    }

    /**
     * Lista todos los componentes opcionales activos.
     * ------------------------------------------------
     * Permite búsqueda por múltiples campos: tipo, marca, modelo, capacidad, estado,
     * o por atributos del equipo asociado (marca y modelo).
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $search = $request->input('search'); // Captura el término de búsqueda enviado desde la vista

        // Inicia la consulta, incluyendo la relación 'equipo' y solo componentes activos
        $query = ComponenteOpcional::activos()->with('equipo');

        if ($search) {
            // Limpiar caracteres especiales dejando solo letras, números y espacios
            $cleanSearch = preg_replace('/[^\wñÑáéíóúÁÉÍÓÚ ]+/u', ' ', $search);

            // Dividir la cadena en palabras individuales para búsqueda por cada término
            $terms = array_filter(explode(' ', $cleanSearch));

            // Agregar condición de búsqueda para cada palabra
            foreach ($terms as $term) {
                $query->where(function ($q) use ($term) {
                    // Búsqueda en campos del componente opcional
                    $q->where('tipo_opcional', 'like', "%{$term}%")
                        ->orWhere('marca', 'like', "%{$term}%")
                        ->orWhere('modelo', 'like', "%{$term}%")
                        ->orWhere('capacidad', 'like', "%{$term}%")
                        ->orWhere('estado', 'like', "%{$term}%")
                        // Búsqueda en los campos del equipo relacionado
                        ->orWhereHas('equipo', function ($eq) use ($term) {
                            $eq->where('marca', 'like', "%{$term}%")
                                ->orWhere('modelo', 'like', "%{$term}%");
                        });
                });
            }
        }

        // Ordenar los resultados por ID descendente y aplicar paginación de 10 por página
        $opcionales = $query->orderBy('id_opcional', 'desc')
            ->paginate(10)
            ->withQueryString(); // Mantener los parámetros de búsqueda en la paginación

        // Retorna la vista con los resultados y el término de búsqueda
        return view('componentesOpcionales.index', compact('opcionales', 'search'));
    }

    /**
     * Obtiene todos los componentes opcionales activos de un equipo específico.
     * -------------------------------------------------------------------------
     * Este método puede ser usado para consultas AJAX o listas dinámicas por equipo.
     *
     * @param int $id_equipo
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function obtenerPorEquipo($id_equipo)
    {
        // Retorna solo componentes activos asociados al ID de equipo dado
        return ComponenteOpcional::activos()
            ->where('id_equipo', $id_equipo)
            ->get();
    }

    /**
     * Mostrar formulario para crear un componente opcional.
     * ------------------------------------------------------
     * Si se proporciona 'id_equipo', se preselecciona en el formulario.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function create(Request $request)
    {
        // Obtener todos los equipos activos para el select
        $equipos = Equipo::where('estado', 'Activo')->get();

        // Capturar el ID de equipo si fue enviado en la solicitud
        $id_equipo = $request->get('id_equipo');

        // Retorna la vista de creación con los equipos y el ID seleccionado
        return view('componentesOpcionales.create', compact('equipos', 'id_equipo'));
    }

    /**
     * Guardar un nuevo componente opcional.
     *
     * Este método maneja dos escenarios principales:
     * 1. Componente opcional tipo "Memoria RAM" con validaciones especiales de tarjeta madre, slots, capacidad y frecuencia.
     * 2. Cualquier otro componente opcional con guardado estándar.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $id_equipo = $request->input('id_equipo');       // Captura el ID del equipo asociado
        $tipoOpcional = $request->input('tipo_opcional'); // Tipo de componente opcional

        /*
    |--------------------------------------------------------------------------
    | SECCIÓN ESPECIAL DE MEMORIA RAM
    |--------------------------------------------------------------------------
    */
        if ($tipoOpcional === 'Memoria Ram') {

            // Buscar la tarjeta madre activa del equipo
            $tarjetaMadre = Componente::where('id_equipo', $id_equipo)
                ->where('tipo_componente', 'Tarjeta Madre')
                ->where('estadoElim', 'Activo')
                ->first();

            // Validación: debe existir tarjeta madre
            if (!$tarjetaMadre) {
                return redirect()->back()
                    ->with('error', 'El equipo no tiene tarjeta madre registrada.')
                    ->withInput();
            }

            /* ------------------------- SLOTS VÁLIDOS ------------------------- */
            $cantidadSlots = (int) $tarjetaMadre->cantidad_slot_memoria;
            $slotsValidos = [];
            for ($i = 1; $i <= $cantidadSlots; $i++) {
                $slotsValidos[] = "Slot $i";
            }

            // Obtener los slots libres actualmente
            $slotsLibres = $this->obtenerSlotsLibres($id_equipo);

            /* ------------------------- MÚLTIPLES RAM ------------------------- */
            // Permite guardar varias memorias RAM en un solo envío
            $rams = $request->input('ram');

            if (!$rams) {
                // Normaliza los datos si vienen de campos individuales
                $rams = [
                    [
                        'marca' => $request->input('marca_ram'),
                        'tipo' => $request->input('tipo_ram'),
                        'capacidad' => $request->input('capacidad_ram'),
                        'frecuencia' => $request->input('frecuencia_ram'),
                        'estado' => $request->input('estado_ram') ?? 'Operativo',
                        'detalles' => $request->input('detalles_ram'),
                        'slot_memoria' => $request->input('slot_memoria'),
                    ]
                ];
            }

            /* ---------------------- CÁLCULO DE RAM DISPONIBLE --------------------- */
            $memoriaMaxima = (int) $tarjetaMadre->memoria_maxima;

            // RAM instalada actualmente en el equipo
            $ramInstalada = Componente::where('id_equipo', $id_equipo)
                ->where('tipo_componente', 'Memoria RAM')
                ->where('estadoElim', 'Activo')
                ->get()
                ->sum(fn($c) => max(0, intval(preg_replace('/\D/', '', $c->capacidad))));

            // RAM opcional existente ya registrada
            $ramOpcionalExistente = ComponenteOpcional::where('id_equipo', $id_equipo)
                ->where('tipo_opcional', 'Memoria Ram')
                ->where('estadoElim', 'Activo')
                ->get()
                ->sum(fn($c) => max(0, intval(preg_replace('/\D/', '', $c->capacidad))));

            // RAM total disponible para agregar
            $ramDisponible = $memoriaMaxima - $ramInstalada - $ramOpcionalExistente;

            /* ------------------------- RECORRER CADA RAM ------------------------- */
            foreach ($rams as $ramData) {

                // Normalizar slot de memoria
                $numeroSlot = (int) filter_var($ramData['slot_memoria'], FILTER_SANITIZE_NUMBER_INT);
                $ramData['slot_memoria'] = "Slot $numeroSlot";

                // Validar que el slot sea válido para esta tarjeta madre
                if (!in_array($ramData['slot_memoria'], $slotsValidos)) {
                    return redirect()->back()
                        ->withErrors(['slot_memoria' => "El {$ramData['slot_memoria']} no es válido para esta tarjeta madre."])
                        ->withInput();
                }

                // Validar que el slot esté libre
                if (!in_array($ramData['slot_memoria'], $slotsLibres)) {
                    return redirect()->back()
                        ->withErrors(['slot_memoria' => "El {$ramData['slot_memoria']} ya está ocupado."])
                        ->withInput();
                }

                /* ---- validar tipo RAM ---- */
                if ($tarjetaMadre->tipo) {
                    $tipoMother = $this->normalizarTipoRAM($tarjetaMadre->tipo);
                    $tipoRAM = $this->normalizarTipoRAM($ramData['tipo']);

                    if ($tipoMother !== $tipoRAM) {
                        return redirect()->back()
                            ->withErrors([
                                'tipo_ram' => "El tipo de RAM ({$ramData['tipo']}) no es compatible con la tarjeta madre ({$tarjetaMadre->tipo})."
                            ])
                            ->withInput();
                    }
                }

                /* ---- validar capacidad ---- */
                $capacidadIngresada = intval(preg_replace('/\D/', '', $ramData['capacidad']));

                if ($capacidadIngresada <= 0) {
                    return redirect()->back()
                        ->withErrors(['capacidad_ram' => "Debe ingresar una capacidad válida."])
                        ->withInput();
                }

                if ($capacidadIngresada > $ramDisponible) {
                    return redirect()->back()
                        ->withErrors([
                            'capacidad_ram' => "La RAM ingresada ({$capacidadIngresada} GB) excede la disponible ({$ramDisponible} GB)."
                        ])
                        ->withInput();
                }

                // Reducir RAM disponible tras cada inserción
                $ramDisponible -= $capacidadIngresada;

                /* ---- validar frecuencia ---- */
                $frecuenciasPermitidas = array_map('trim', explode(',', $tarjetaMadre->frecuencias_memoria ?? ''));
                $frecuenciaSel = (int) $ramData['frecuencia'];

                if ($frecuenciasPermitidas && !in_array($frecuenciaSel, $frecuenciasPermitidas)) {
                    $listado = implode(', ', $frecuenciasPermitidas);
                    return redirect()->back()
                        ->withErrors([
                            'frecuencia_ram' => "La frecuencia ($frecuenciaSel MHz) no es compatible. Válidas: $listado MHz."
                        ])
                        ->withInput();
                }

                /* ---- guardar la RAM ---- */
                ComponenteOpcional::create([
                    'id_equipo' => $id_equipo,
                    'tipo_opcional' => 'Memoria Ram',
                    'marca' => $ramData['marca'],
                    'tipo' => $ramData['tipo'],
                    'capacidad' => $capacidadIngresada . ' GB',
                    'frecuencia' => $ramData['frecuencia'],
                    'estado' => $ramData['estado'] ?? 'Operativo',
                    'detalles' => $ramData['detalles'],
                    'slot_memoria' => $ramData['slot_memoria'],
                    'estadoElim' => 'Activo'
                ]);

                // Actualizar slots libres
                $slotsLibres = array_diff($slotsLibres, [$ramData['slot_memoria']]);
            }

            /* ---- Registrar log de RAM ---- */
            $usuario = Auth::check() ? Auth::user()->usuario : 'Sistema';
            $equipo = Equipo::find($id_equipo);
            $equipoInfo = $equipo ? $equipo->marca . ' ' . $equipo->modelo : "ID $id_equipo";

            LogModel::create([
                'usuario' => $usuario,
                'accion' => 'Agregado RAM opcional para equipo: ' . $equipoInfo,
                'detalles' => json_encode($rams),
                'fecha' => now(),
            ]);

            /* ---- Redirección tras guardar RAM ---- */
            return $request->input('porEquipo')
                ? redirect()->route('componentes.porEquipo', $id_equipo)->with('success', 'RAM agregada correctamente.')
                : redirect()->route('componentesOpcionales.index')->with('success', 'RAM agregada correctamente.');
        }

        /*
    |--------------------------------------------------------------------------
    | GUARDAR CUALQUIER OTRO COMPONENTE (NO RAM)
    |--------------------------------------------------------------------------
    */
        $data = $this->procesarDatos($request->all());
        $data['id_equipo'] = $id_equipo;
        $data['tipo_opcional'] = $tipoOpcional;
        $data['estadoElim'] = 'Activo';

        ComponenteOpcional::create($data);

        $usuario = Auth::check() ? Auth::user()->usuario : 'Sistema';
        $equipo = Equipo::find($id_equipo);
        $equipoInfo = $equipo ? $equipo->marca . ' ' . $equipo->modelo : "ID $id_equipo";

        LogModel::create([
            'usuario' => $usuario,
            'accion' => "Agregado componente opcional: $tipoOpcional para equipo: $equipoInfo",
            'detalles' => json_encode($data),
            'fecha' => now(),
        ]);

        return $request->input('porEquipo')
            ? redirect()->route('componentes.porEquipo', $id_equipo)->with('success', 'Componente opcional agregado correctamente.')
            : redirect()->route('componentesOpcionales.index')->with('success', 'Componente opcional agregado correctamente.');
    }

    /**
     * Normalizar tipo de RAM
     * ----------------------
     * Convierte el tipo de RAM a minúsculas y elimina espacios para comparaciones consistentes.
     *
     * @param string $tipo
     * @return string
     */
    private function normalizarTipoRAM($tipo)
    {
        return strtolower(str_replace(' ', '', $tipo)); // "DDR3" => "ddr3"
    }

    /**
     * Mostrar formulario de edición de un componente opcional.
     *
     * @param int $id ID del componente opcional
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        // Obtener el componente opcional junto con la relación equipo
        $opcional = ComponenteOpcional::with('equipo')->findOrFail($id);

        // Obtener todos los equipos activos para el select
        $equipos = Equipo::where('estado', 'Activo')->get();

        // Retornar la vista de edición con los datos necesarios
        return view('componentesOpcionales.edit', [
            'opcional' => $opcional,
            'equipos' => $equipos,
            'porEquipo' => false, // Indica que no se está editando "por equipo"
            'id_equipo' => null,
        ]);
    }

    /**
     * Actualizar un componente opcional.
     *
     * Este método maneja:
     * 1. Validaciones especiales si el componente es Memoria RAM:
     *      - Slot válido y libre
     *      - Tipo compatible con la tarjeta madre
     *      - Capacidad no exceda la memoria disponible
     *      - Frecuencia permitida
     * 2. Actualización general para otros componentes
     *
     * @param Request $request
     * @param int $id ID del componente opcional
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        // Buscar el componente opcional
        $opcional = ComponenteOpcional::findOrFail($id);

        // Validar que esté activo
        if ($opcional->estadoElim !== 'Activo') {
            return redirect()->back()->with('error', 'No se puede actualizar un componente inactivo.');
        }

        // Procesar datos recibidos del formulario
        $data = $this->procesarDatos($request->all());

        // -------------------------------
        // Validaciones especiales para RAM
        // -------------------------------
        if (($data['tipo_opcional'] ?? '') === 'Memoria Ram') {

            // Obtener slot ingresado por el usuario
            $slotElegido = (int) filter_var($data['slot_memoria'], FILTER_SANITIZE_NUMBER_INT);

            // Buscar la tarjeta madre activa del equipo
            $tarjetaMadre = Componente::where('id_equipo', $opcional->id_equipo)
                ->where('tipo_componente', 'Tarjeta Madre')
                ->where('estadoElim', 'Activo')
                ->first();

            // Validación: tarjeta madre debe existir
            if (!$tarjetaMadre) {
                return redirect()->back()
                    ->with('error', 'El equipo no tiene tarjeta madre activa registrada.')
                    ->withInput();
            }

            // Validar que el slot elegido exista en la tarjeta madre
            $cantidadSlots = (int) $tarjetaMadre->cantidad_slot_memoria;
            if ($slotElegido < 1 || $slotElegido > $cantidadSlots) {
                return redirect()->back()
                    ->with('error', "El slot {$slotElegido} no es válido para esta tarjeta madre.")
                    ->withInput();
            }

            // Obtener slots ocupados por RAM opcional excluyendo este registro
            $slotsOcupadosOpcionales = ComponenteOpcional::where('id_equipo', $opcional->id_equipo)
                ->where('tipo_opcional', 'Memoria Ram')
                ->where('estadoElim', 'Activo')
                ->where('id_opcional', '!=', $opcional->id_opcional)
                ->pluck('slot_memoria')
                ->map(fn($s) => "Slot " . (int) filter_var($s, FILTER_SANITIZE_NUMBER_INT))
                ->toArray();

            // Obtener slots ocupados por RAM principal
            $slotsOcupadosComponente = Componente::where('id_equipo', $opcional->id_equipo)
                ->where('tipo_componente', 'Memoria RAM')
                ->where('estadoElim', 'Activo')
                ->pluck('slot_memoria')
                ->map(fn($s) => "Slot " . (int) filter_var($s, FILTER_SANITIZE_NUMBER_INT))
                ->toArray();

            // Combinar todos los slots ocupados
            $slotsOcupados = array_merge($slotsOcupadosOpcionales, $slotsOcupadosComponente);

            // Validar que el slot seleccionado no esté ocupado
            if (in_array("Slot $slotElegido", $slotsOcupados)) {
                return redirect()->back()
                    ->withErrors(['slot_memoria' => "El Slot {$slotElegido} ya está ocupado por otra RAM."])
                    ->withInput();
            }

            // Validar compatibilidad de tipo de RAM con la tarjeta madre
            if ($tarjetaMadre->tipo) {
                $tipoMother = $this->normalizarTipoRAM($tarjetaMadre->tipo);
                $tipoRAM = $this->normalizarTipoRAM($data['tipo']);
                if ($tipoMother !== $tipoRAM) {
                    return redirect()->back()
                        ->withErrors(['tipo_ram' => "El tipo de memoria RAM ({$data['tipo']}) no es compatible con la tarjeta madre ({$tarjetaMadre->tipo})."])
                        ->withInput();
                }
            }

            // Guardar slot normalizado
            $data['slot_memoria'] = "Slot $slotElegido";

            // -----------------------------
            // Validar capacidad máxima RAM
            // -----------------------------
            $memoriaMaxima = (int) $tarjetaMadre->memoria_maxima;

            // RAM instalada permanentemente
            $ramInstalada = Componente::where('id_equipo', $opcional->id_equipo)
                ->where('tipo_componente', 'Memoria RAM')
                ->where('estadoElim', 'Activo')
                ->get()
                ->sum(fn($c) => max(0, intval(preg_replace('/\D/', '', $c->capacidad))));

            // RAM opcional existente, excluyendo este registro
            $ramOpcionalExistente = ComponenteOpcional::where('id_equipo', $opcional->id_equipo)
                ->where('tipo_opcional', 'Memoria Ram')
                ->where('estadoElim', 'Activo')
                ->where('id_opcional', '!=', $opcional->id_opcional)
                ->get()
                ->sum(fn($c) => max(0, intval(preg_replace('/\D/', '', $c->capacidad))));

            // RAM disponible
            $ramDisponible = $memoriaMaxima - $ramInstalada - $ramOpcionalExistente;

            // Validar capacidad ingresada por el usuario
            $capacidadIngresada = intval(preg_replace('/\D/', '', $data['capacidad'] ?? '0'));
            if ($capacidadIngresada <= 0) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['capacidad_ram' => "Debe ingresar un valor de RAM válido."]);
            }

            if ($capacidadIngresada > $ramDisponible) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['capacidad_ram' => "La RAM ingresada ({$capacidadIngresada} GB) excede la memoria disponible ({$ramDisponible} GB)."]);
            }

            // Guardar capacidad en GB
            $data['capacidad'] = $capacidadIngresada . ' GB';

            // -----------------------------
            // Validar frecuencia RAM
            // -----------------------------
            $frecuenciaPermitida = array_map('trim', explode(',', $tarjetaMadre->frecuencias_memoria ?? ''));
            $frecuenciaSeleccionada = (int) $data['frecuencia'] ?? 0;

            if ($frecuenciaPermitida && !in_array($frecuenciaSeleccionada, $frecuenciaPermitida)) {
                $frecuenciasFormateadas = implode(', ', $frecuenciaPermitida);
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['frecuencia_ram' => "La frecuencia ingresada ({$frecuenciaSeleccionada} MHz) no es compatible con la tarjeta madre. Frecuencias válidas: {$frecuenciasFormateadas} MHz."]);
            }
        } else {
            // Para cualquier otro componente, eliminar slot memoria
            unset($data['slot_memoria']);
        }

        // Actualizar registro en base de datos
        $opcional->update($data);

        // Registrar acción en logs
        $usuario = Auth::check() ? Auth::user()->name ?? Auth::user()->usuario : 'Sistema';
        LogModel::create([
            'usuario' => $usuario,
            'accion' => 'Actualizado componente opcional: ' . $opcional->tipo_opcional,
            'detalles' => json_encode($data),
            'fecha' => now()
        ]);

        // Redirección según origen (porEquipo o listado general)
        if ($request->has('porEquipo') && $request->input('porEquipo')) {
            return redirect()->route('componentes.porEquipo', $opcional->id_equipo)
                ->with('success', 'Componente opcional actualizado correctamente.');
        } else {
            return redirect()->route('componentesOpcionales.index')
                ->with('success', 'Componente opcional actualizado correctamente.');
        }
    }

    /**
     * Eliminar un componente opcional.
     *
     * Este método no elimina físicamente el registro, solo cambia su estado a 'Inactivo'.
     * También genera un registro en la tabla de logs con la acción realizada.
     *
     * @param Request $request
     * @param int $id ID del componente opcional a eliminar
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, $id)
    {
        // Buscar el componente opcional por ID
        $opcional = ComponenteOpcional::findOrFail($id);

        // Marcarlo como inactivo en lugar de eliminarlo físicamente
        $opcional->estadoElim = 'Inactivo';
        $opcional->save();

        // Determinar el usuario que realiza la acción
        $usuario = Auth::check() ? Auth::user()->name ?? Auth::user()->usuario : 'Sistema';

        // Intentar registrar la acción en la tabla de logs
        try {
            LogModel::create([
                'usuario' => $usuario,
                'accion' => 'Eliminado componente opcional ID: ' . $opcional->tipo_opcional,
                'fecha' => now()
            ]);
        } catch (\Exception $e) {
            // En caso de error, registrarlo en el log de Laravel
            \Illuminate\Support\Facades\Log::error('Error guardando log: ' . $e->getMessage());
        }

        // Redirección según origen: porEquipo o desde listado general
        if ($request->input('porEquipo')) {
            return redirect()->route('componentes.porEquipo', $request->input('id_equipo'))
                ->with('success', 'Componente opcional eliminado correctamente.');
        } else {
            return redirect()->route('componentesOpcionales.index')
                ->with('success', 'Componente opcional eliminado correctamente.');
        }
    }

    /**
     * Procesa los datos recibidos según el tipo de componente opcional.
     *
     * Normaliza los campos para evitar errores de tipo, convierte arrays a strings,
     * y asigna valores por defecto cuando sea necesario.
     *
     * @param array $data Datos del request
     * @return array Datos procesados listos para guardar en la base de datos
     */
    private function procesarDatos(array $data)
    {
        // 🔹 Convertir arrays en strings separados por coma
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $data[$key] = implode(', ', $value);
            }
        }

        // Lista de campos que siempre deben existir
        $campos = [
            'marca',
            'modelo',
            'tipo',
            'capacidad',
            'frecuencia',
            'consumo',
            'ubicacion',
            'seguridad',
            'estado',
            'salidas_video',
            'salidas_audio',
            'velocidad',
            'drivers_sistema',
            'compatibilidad',
            'canales',
            'resolucion',
            'detalles',
            'slot_memoria'
        ];

        // Asegurar que todos los campos existan
        foreach ($campos as $c) {
            if (!isset($data[$c]))
                $data[$c] = null;
        }

        // Procesamiento según tipo de componente
        switch ($data['tipo_opcional'] ?? '') {

            case 'Memoria Ram':
                $data['marca'] = $data['marca_ram'] ?? '';
                $data['tipo'] = $data['tipo_ram'] ?? '';
                $data['capacidad'] = $data['capacidad_ram'] ?? '';
                $data['frecuencia'] = $data['frecuencia_ram'] ?? '';
                $data['estado'] = $data['estado_ram'] ?? 'Operativo';
                $data['detalles'] = $data['detalles_ram'] ?? $data['detalles'] ?? null;
                $data['slot_memoria'] = $data['slot_memoria'] ?? null;
                break;

            case 'Disco Duro':
                $data['marca'] = $data['marca_disco'] ?? '';
                $data['tipo'] = $data['tipo_disco'] ?? '';
                $data['capacidad'] = $data['capacidad_disco'] ?? '';
                $data['estado'] = $data['estado_disco'] ?? 'Operativo';
                $data['detalles'] = $data['detalles_disco'] ?? $data['detalles'] ?? null;
                break;

            case 'Fan Cooler':
                $data['marca'] = $data['marca_fan'] ?? '';
                $data['tipo'] = $data['tipo_fan'] ?? '';
                $data['consumo'] = $data['consumo_fan'] ?? '';
                $data['ubicacion'] = $data['ubicacion_fan'] ?? '';
                $data['estado'] = $data['estado_fan'] ?? 'Operativo';
                $data['detalles'] = $data['detalles_fan'] ?? $data['detalles'] ?? null;
                break;

            case 'Tarjeta Grafica':
                $data['marca'] = $data['marca_tarjeta_grafica'] ?? '';
                $data['modelo'] = $data['modelo_tarjeta_grafica'] ?? '';
                if (isset($data['salidas_video']) && is_array($data['salidas_video'])) {
                    $data['salidas_video'] = implode(', ', $data['salidas_video']);
                }
                $data['drivers'] = $data['drivers_sistema_tarjeta_grafica'] ?? '';
                $data['compatibilidad'] = $data['compatibilidad_tarjeta_grafica'] ?? '';
                $data['capacidad'] = $data['capacidad_tarjeta_grafica'] ?? '';
                $data['estado'] = $data['estado_tarjeta_grafica'] ?? 'Operativo';
                $data['detalles'] = $data['detalles_tarjeta_grafica'] ?? $data['detalles'] ?? null;
                break;

            case 'Tarjeta de Red':
                $data['marca'] = $data['marca_tarjeta_red'] ?? '';
                $data['modelo'] = $data['modelo_tarjeta_red'] ?? '';
                $data['velocidad'] = $data['velocidad_red'] ?? '';
                $data['drivers'] = $data['drivers_sistema_tarjeta_red'] ?? '';
                $data['compatibilidad'] = $data['compatibilidad_tarjeta_red'] ?? '';
                $data['estado'] = $data['estado_tarjeta_red'] ?? 'Operativo';
                $data['detalles'] = $data['detalles_tarjeta_red'] ?? $data['detalles'] ?? null;
                break;

            case 'Tarjeta WiFi':
                $data['marca'] = $data['marca_tarjeta_wifi'] ?? '';
                $data['modelo'] = $data['modelo_tarjeta_wifi'] ?? '';
                $data['tipo'] = $data['tipo_tarjeta_wifi'] ?? '';
                $data['velocidad'] = $data['velocidad_wifi'] ?? '';
                $data['frecuencia'] = $data['frecuencia_wifi'] ?? '';
                $seguridad = $data['seguridad_wifi'] ?? [];
                if (!is_array($seguridad)) {
                    $seguridad = [$seguridad];
                }
                if (!empty($data['seguridad_wifi_otro'])) {
                    $seguridad[] = $data['seguridad_wifi_otro'];
                }
                $data['seguridad'] = implode(', ', $seguridad);
                $data['bluetooth'] = $data['bluetooth_wifi'] ?? '';
                $data['drivers'] = $data['drivers_sistema_tarjeta_wifi'] ?? '';
                $data['compatibilidad'] = $data['compatibilidad_tarjeta_wifi'] ?? '';
                $data['estado'] = $data['estado_tarjeta_wifi'] ?? 'Operativo';
                $data['detalles'] = $data['detalles_tarjeta_wifi'] ?? $data['detalles'] ?? null;
                break;

            case 'Tarjeta de Sonido':
                $data['marca'] = $data['marca_tarjeta_sonido'] ?? '';
                $data['modelo'] = $data['modelo_tarjeta_sonido'] ?? '';
                $canales = $data['canales_tarjeta_sonido'] ?? [];
                if (!is_array($canales)) {
                    $canales = [$canales];
                }
                $data['canales'] = implode(', ', array_filter($canales));

                $salidas = $data['salidas_audio'] ?? [];
                if (!is_array($salidas)) {
                    $salidas = [$salidas];
                }
                $data['salidas_audio'] = implode(', ', array_filter($salidas));

                $resolucion = $data['resolucion_audio'] ?? [];
                if (!is_array($resolucion)) {
                    $resolucion = [$resolucion];
                }
                $data['resolucion'] = implode(', ', array_map('trim', array_filter($resolucion)));

                $data['drivers'] = $data['drivers_audio'] ?? '';
                $data['compatibilidad'] = $data['compatibilidad_tarjeta_audio'] ?? '';
                $data['estado'] = $data['estado_tarjeta_sonido'] ?? 'Operativo';
                $data['detalles'] = $data['detalles_tarjeta_sonido'] ?? $data['detalles'] ?? null;
                break;

            default:
                $data['estado'] = $data['estado'] ?? 'Operativo';
                break;
        }

        // Validar que 'estado' esté dentro de los permitidos
        $estadosValidos = ['Buen Funcionamiento', 'Operativo', 'Sin Funcionar', 'Medio dañado', 'Dañado'];
        if (!in_array($data['estado'], $estadosValidos)) {
            $data['estado'] = 'Operativo';
        }

        // Estado de eliminación por defecto
        if (empty($data['estadoElim'])) {
            $data['estadoElim'] = 'Activo';
        }

        return $data;
    }

    /**
     * Obtiene los slots libres de RAM de un equipo.
     *
     * Este método revisa la tarjeta madre asociada al equipo, determina
     * cuántos slots existen y cuáles ya están ocupados por componentes
     * permanentes o RAM opcional. Devuelve solo los slots libres.
     *
     * @param int $id_equipo ID del equipo
     * @return array Lista de slots libres (ej. ['Slot 1', 'Slot 3'])
     */
    private function obtenerSlotsLibres($id_equipo)
    {
        // Buscar la tarjeta madre del equipo
        $tarjetaMadre = Componente::where('id_equipo', $id_equipo)
            ->where('tipo_componente', 'Tarjeta Madre')
            ->first();

        if (!$tarjetaMadre)
            return []; // Si no hay tarjeta madre, no hay slots

        $cantidadSlots = (int) $tarjetaMadre->cantidad_slot_memoria;

        // Crear nombres estándar de todos los slots disponibles
        $todosLosSlots = [];
        for ($i = 1; $i <= $cantidadSlots; $i++) {
            $todosLosSlots[] = "Slot $i";
        }

        // --- Slots ocupados por RAM opcional ---
        $slotsOcupadosOpcionales = ComponenteOpcional::where('id_equipo', $id_equipo)
            ->where('tipo_opcional', 'Memoria Ram')
            ->where('estadoElim', 'Activo')
            ->pluck('slot_memoria')
            ->map(fn($s) => "Slot " . (int) filter_var($s, FILTER_SANITIZE_NUMBER_INT))
            ->toArray();

        // --- Slots ocupados por RAM permanente (componentes) ---
        $slotsOcupadosComponente = Componente::where('id_equipo', $id_equipo)
            ->where('tipo_componente', 'Memoria RAM')
            ->where('estadoElim', 'Activo') // solo activos
            ->pluck('slot_memoria')
            ->map(fn($s) => "Slot " . (int) filter_var($s, FILTER_SANITIZE_NUMBER_INT))
            ->toArray();

        // Unir y eliminar duplicados
        $slotsOcupados = array_unique(array_merge($slotsOcupadosOpcionales, $slotsOcupadosComponente));

        // Devolver solo los slots libres
        return array_values(array_diff($todosLosSlots, $slotsOcupados));
    }

    /**
     * Mostrar componentes y opcionales de un equipo específico.
     *
     * @param int $id_equipo ID del equipo
     * @return \Illuminate\View\View
     */
    public function porEquipo($id_equipo)
    {
        // Obtener equipo
        $equipo = Equipo::findOrFail($id_equipo);

        // Componentes permanentes del equipo
        $componentes = Componente::where('id_equipo', $id_equipo)->get();

        // Componentes opcionales del equipo
        $opcionales = ComponenteOpcional::obtenerPorEquipo($id_equipo);

        return view('componentes.porEquipo', compact('equipo', 'componentes', 'opcionales', 'id_equipo'));
    }

    /**
     * Mostrar formulario de creación de componente opcional para un equipo específico.
     *
     * @param int $id_equipo ID del equipo
     * @return \Illuminate\View\View
     */
    public function createPorEquipo($id_equipo)
    {
        // Obtener equipo seleccionado
        $equipoSeleccionado = Equipo::findOrFail($id_equipo);

        // Obtener todos los equipos (por si se necesita seleccionar otro)
        $equipos = Equipo::all();

        return view('componentesOpcionales.create', [
            'porEquipo' => true,                 // Indica que el formulario es por equipo
            'equipoSeleccionado' => $equipoSeleccionado,
            'equipos' => $equipos,
            'id_equipo' => $id_equipo,          // Para uso en la vista
        ]);
    }

    /**
     * Mostrar formulario de edición de componente opcional por equipo.
     *
     * @param int $id ID del componente opcional
     * @return \Illuminate\View\View
     */
    public function editPorEquipo($id)
    {
        // Obtener componente opcional junto con la relación con el equipo
        $opcional = ComponenteOpcional::with('equipo')->findOrFail($id);

        // Todos los equipos activos (para selector en la vista)
        $equipos = Equipo::where('estado', 'Activo')->get();

        return view('componentesOpcionales.edit', [
            'opcional' => $opcional,
            'equipos' => $equipos,
            'porEquipo' => true,                   // Para indicar que es edición por equipo
            'id_equipo' => $opcional->id_equipo,  // ID real del equipo del componente
            'equipoSeleccionado' => $opcional->equipo ?? null, // Equipo relacionado (opcional)
        ]);
    }
}
