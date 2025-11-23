<?php

namespace App\Http\Controllers;

use App\Models\ComponenteOpcional;
use App\Models\Componente;
use App\Models\Equipo;
use App\Models\Log as LogModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ComponenteOpcionalController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); // protege todas las rutas
    }

    /**
     * Muestra la lista de componentes opcionales.
     */
    public function index(Request $request)
    {
        $query = ComponenteOpcional::activos()->with('equipo');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('tipo_opcional', 'like', "%{$search}%")
                    ->orWhere('marca', 'like', "%{$search}%")
                    ->orWhere('modelo', 'like', "%{$search}%")
                    ->orWhere('capacidad', 'like', "%{$search}%")
                    ->orWhere('estado', 'like', "%{$search}%")
                    ->orWhereHas('equipo', function ($eq) use ($search) {
                        $eq->where('marca', 'like', "%{$search}%")
                            ->orWhere('modelo', 'like', "%{$search}%");
                    });
            });
        }

        $opcionales = $query->orderBy('id_opcional', 'desc')->paginate(10)->withQueryString();

        return view('componentesOpcionales.index', compact('opcionales'));
    }

    public function obtenerPorEquipo($id_equipo)
    {
        return ComponenteOpcional::activos()->where('id_equipo', $id_equipo)->get();
    }

    /**
     * Mostrar formulario de creación.
     */
    public function create(Request $request)
    {
        $equipos = Equipo::where('estado', 'Activo')->get();

        $id_equipo = $request->get('id_equipo');

        return view('componentesOpcionales.create', compact('equipos', 'id_equipo'));
    }


    /**
     * Guardar un nuevo componente opcional.
     */

    public function store(Request $request)
    {
        $id_equipo = $request->input('id_equipo');
        $tipoOpcional = $request->input('tipo_opcional');

        /*
    |--------------------------------------------------------------------------
    |   SECCIÓN ESPECIAL DE MEMORIA RAM
    |--------------------------------------------------------------------------
    */
        if ($tipoOpcional === 'Memoria Ram') {

            // Buscar tarjeta madre
            $tarjetaMadre = Componente::where('id_equipo', $id_equipo)
                ->where('tipo_componente', 'Tarjeta Madre')
                ->where('estadoElim', 'Activo')
                ->first();

            if (!$tarjetaMadre) {
                return redirect()->back()
                    ->with('error', 'El equipo no tiene tarjeta madre registrada.')
                    ->withInput();
            }

            /* ------------------------- SLOTS VALIDOS ------------------------- */
            $cantidadSlots = (int) $tarjetaMadre->cantidad_slot_memoria;
            $slotsValidos = [];
            for ($i = 1; $i <= $cantidadSlots; $i++) {
                $slotsValidos[] = "Slot $i";
            }

            $slotsLibres = $this->obtenerSlotsLibres($id_equipo);

            /* ------------------------- MÚLTIPLES RAM ------------------------- */
            $rams = $request->input('ram');

            if (!$rams) {
                $rams = [[
                    'marca' => $request->input('marca_ram'),
                    'tipo' => $request->input('tipo_ram'),
                    'capacidad' => $request->input('capacidad_ram'),
                    'frecuencia' => $request->input('frecuencia_ram'),
                    'estado' => $request->input('estado_ram') ?? 'Operativo',
                    'detalles' => $request->input('detalles_ram'),
                    'slot_memoria' => $request->input('slot_memoria'),
                ]];
            }

            /* ---------------------- SUMA RAM YA INSTALADA --------------------- */
            $memoriaMaxima = (int) $tarjetaMadre->memoria_maxima;

            $ramInstalada = Componente::where('id_equipo', $id_equipo)
                ->where('tipo_componente', 'Memoria RAM')
                ->where('estadoElim', 'Activo')
                ->get()
                ->sum(fn($c) => max(0, intval(preg_replace('/\D/', '', $c->capacidad))));

            $ramOpcionalExistente = ComponenteOpcional::where('id_equipo', $id_equipo)
                ->where('tipo_opcional', 'Memoria Ram')
                ->where('estadoElim', 'Activo')
                ->get()
                ->sum(fn($c) => max(0, intval(preg_replace('/\D/', '', $c->capacidad))));

            $ramDisponible = $memoriaMaxima - $ramInstalada - $ramOpcionalExistente;

            /* ------------------------- RECORRER RAM ------------------------- */
            foreach ($rams as $ramData) {

                /* ---- normalizar slot ---- */
                $numeroSlot = (int) filter_var($ramData['slot_memoria'], FILTER_SANITIZE_NUMBER_INT);
                $ramData['slot_memoria'] = "Slot $numeroSlot";

                if (!in_array($ramData['slot_memoria'], $slotsValidos)) {
                    return redirect()->back()
                        ->with('error', "El {$ramData['slot_memoria']} no es válido para esta tarjeta madre.")
                        ->withInput();
                }

                if (!in_array($ramData['slot_memoria'], $slotsLibres)) {
                    return redirect()->back()
                        ->with('error', "El {$ramData['slot_memoria']} ya está ocupado.")
                        ->withInput();
                }

                /* ---- validar tipo RAM ---- */
                if ($tarjetaMadre->tipo) {
                    $tipoMother = $this->normalizarTipoRAM($tarjetaMadre->tipo);
                    $tipoRAM = $this->normalizarTipoRAM($ramData['tipo']);

                    if ($tipoMother !== $tipoRAM) {
                        return redirect()->back()
                            ->with('error', "El tipo de RAM ({$ramData['tipo']}) no es compatible con la tarjeta madre ({$tarjetaMadre->tipo}).")
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

                $slotsLibres = array_diff($slotsLibres, [$ramData['slot_memoria']]);
            }

            /* ---- LOG RAM ---- */
            $usuario = Auth::check() ? Auth::user()->usuario : 'Sistema';
            $equipo = Equipo::find($id_equipo);
            $equipoInfo = $equipo ? $equipo->marca . ' ' . $equipo->modelo : "ID $id_equipo";

            LogModel::create([
                'usuario' => $usuario,
                'accion' => 'Agregado RAM opcional para equipo: ' . $equipoInfo,
                'detalles' => json_encode($rams),
                'fecha' => now(),
            ]);

            /* ---- Redirección tras RAM ---- */
            return $request->input('porEquipo')
                ? redirect()->route('componentes.porEquipo', $id_equipo)->with('success', 'RAM agregada correctamente.')
                : redirect()->route('componentesOpcionales.index')->with('success', 'RAM agregada correctamente.');
        }

        /*
    |--------------------------------------------------------------------------
    |   GUARDAR CUALQUIER OTRO COMPONENTE (NO RAM)
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


    // -----------------------
    // Normalizar tipo de RAM
    // -----------------------
    private function normalizarTipoRAM($tipo)
    {
        return strtolower(str_replace(' ', '', $tipo)); // "DDR3" => "ddr3"
    }

    /**
     * Mostrar formulario de edición.
     */
    public function edit($id)
    {
        $opcional = ComponenteOpcional::with('equipo')->findOrFail($id);
        $equipos = Equipo::where('estado', 'Activo')->get();

        return view('componentesOpcionales.edit', [
            'opcional' => $opcional,
            'equipos' => $equipos,
            'porEquipo' => false, // para que el botón sepa que no es por equipo
            'id_equipo' => null,
        ]);
    }

    /**
     * Actualizar un componente opcional.
     */
    public function update(Request $request, $id)
    {
        $opcional = ComponenteOpcional::findOrFail($id);

        if ($opcional->estadoElim !== 'Activo') {
            return redirect()->back()->with('error', 'No se puede actualizar un componente inactivo.');
        }

        $data = $this->procesarDatos($request->all());

        if (($data['tipo_opcional'] ?? '') === 'Memoria Ram') {
            $slotElegido = (int) filter_var($data['slot_memoria'], FILTER_SANITIZE_NUMBER_INT);

            $tarjetaMadre = Componente::where('id_equipo', $opcional->id_equipo)
                ->where('tipo_componente', 'Tarjeta Madre')
                ->where('estadoElim', 'Activo') // 🔹 Solo tarjeta madre activa
                ->first();

            if (!$tarjetaMadre) {
                return redirect()->back()
                    ->with('error', 'El equipo no tiene tarjeta madre activa registrada.')
                    ->withInput();
            }

            $cantidadSlots = (int) $tarjetaMadre->cantidad_slot_memoria;
            if ($slotElegido < 1 || $slotElegido > $cantidadSlots) {
                return redirect()->back()
                    ->with('error', "El slot {$slotElegido} no es válido para esta tarjeta madre.")
                    ->withInput();
            }

            $slotsOcupadosOpcionales = ComponenteOpcional::where('id_equipo', $opcional->id_equipo)
                ->where('tipo_opcional', 'Memoria Ram')
                ->where('estadoElim', 'Activo')
                ->where('id_opcional', '!=', $opcional->id_opcional)
                ->pluck('slot_memoria')
                ->map(fn($s) => "Slot " . (int) filter_var($s, FILTER_SANITIZE_NUMBER_INT))
                ->toArray();

            $slotsOcupadosComponente = Componente::where('id_equipo', $opcional->id_equipo)
                ->where('tipo_componente', 'Memoria RAM')
                ->where('estadoElim', 'Activo')
                ->pluck('slot_memoria')
                ->map(fn($s) => "Slot " . (int) filter_var($s, FILTER_SANITIZE_NUMBER_INT))
                ->toArray();

            $slotsOcupados = array_merge($slotsOcupadosOpcionales, $slotsOcupadosComponente);

            if (in_array("Slot $slotElegido", $slotsOcupados)) {
                return redirect()->back()
                    ->withErrors(['slot_memoria' => "El Slot {$slotElegido} ya está ocupado por otra RAM."])
                    ->withInput();
            }

            // 🔹 Validación de tipo RAM con la tarjeta madre
            if ($tarjetaMadre->tipo) {
                $tipoMother = $this->normalizarTipoRAM($tarjetaMadre->tipo);
                $tipoRAM = $this->normalizarTipoRAM($data['tipo']);
                if ($tipoMother !== $tipoRAM) {
                    return redirect()->back()
                        ->withErrors(['tipo_ram' => "El tipo de memoria RAM ({$data['tipo']}) no es compatible con la tarjeta madre ({$tarjetaMadre->tipo})."])
                        ->withInput();
                }
            }

            $data['slot_memoria'] = "Slot $slotElegido";

            // CAPACIDAD MÁXIMA DISPONIBLE
            $memoriaMaxima = (int) $tarjetaMadre->memoria_maxima;

            // RAM instalada por componentes permanentes
            $ramInstalada = Componente::where('id_equipo', $opcional->id_equipo)
                ->where('tipo_componente', 'Memoria RAM')
                ->where('estadoElim', 'Activo')
                ->get() // traer los registros primero
                ->sum(function ($c) {
                    return max(0, intval(preg_replace('/\D/', '', $c->capacidad)));
                });

            // RAM opcional existente, excluyendo la que estamos editando
            $ramOpcionalExistente = ComponenteOpcional::where('id_equipo', $opcional->id_equipo)
                ->where('tipo_opcional', 'Memoria Ram')
                ->where('estadoElim', 'Activo')
                ->where('id_opcional', '!=', $opcional->id_opcional)
                ->get() // traer los registros primero
                ->sum(function ($c) {
                    return max(0, intval(preg_replace('/\D/', '', $c->capacidad)));
                });

            // RAM disponible
            $ramDisponible = $memoriaMaxima - $ramInstalada - $ramOpcionalExistente;

            // Capacidad que intenta ingresar el usuario
            $capacidadIngresada = intval(preg_replace('/\D/', '', $data['capacidad'] ?? '0'));

            if ($capacidadIngresada <= 0) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['capacidad_ram' => "Debe ingresar un valor de RAM válido."]);
            }

            if ($capacidadIngresada > $ramDisponible) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors([
                        'capacidad_ram' => "La RAM ingresada ({$capacidadIngresada} GB) excede la memoria disponible ({$ramDisponible} GB)."
                    ]);
            }

            // Asignar capacidad limpia al registro Y Guardar siempre con "GB"
            $data['capacidad'] = $capacidadIngresada . ' GB';

            // 🔹 Validación frecuencia máxima
            $frecuenciaPermitida = array_map('trim', explode(',', $tarjetaMadre->frecuencias_memoria ?? ''));

            $frecuenciaSeleccionada = (int) $data['frecuencia'] ?? 0;

            if ($frecuenciaPermitida && !in_array($frecuenciaSeleccionada, $frecuenciaPermitida)) {
                $frecuenciasFormateadas = implode(', ', $frecuenciaPermitida);
                return redirect()->back()
                    ->withInput()
                    ->withErrors([
                        'frecuencia_ram' => "La frecuencia ingresada ({$frecuenciaSeleccionada} MHz) no es compatible con la tarjeta madre. Frecuencias válidas: {$frecuenciasFormateadas} MHz."
                    ]);
            }
        } else {
            unset($data['slot_memoria']);
        }

        $opcional->update($data);

        $usuario = Auth::check() ? Auth::user()->name ?? Auth::user()->usuario : 'Sistema';
        LogModel::create([
            'usuario' => $usuario,
            'accion' => 'Actualizado componente opcional: ' . $opcional->tipo_opcional,
            'detalles' => json_encode($data),
            'fecha' => now()
        ]);

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
     */
    public function destroy(Request $request, $id)
    {
        $opcional = ComponenteOpcional::findOrFail($id);
        $opcional->estadoElim = 'Inactivo';
        $opcional->save();

        $usuario = Auth::check() ? Auth::user()->name ?? Auth::user()->usuario : 'Sistema';

        try {
            LogModel::create([
                'usuario' => $usuario,
                'accion' => 'Eliminado componente opcional ID: ' .  $opcional->tipo_opcional,
                'fecha' => now()
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error guardando log: ' . $e->getMessage());
        }

        // Redirección según origen
        if ($request->input('porEquipo')) {
            return redirect()->route('componentes.porEquipo', $request->input('id_equipo'))
                ->with('success', 'Componente opcional eliminado correctamente.');
        } else {
            return redirect()->route('componentesOpcionales.index')
                ->with('success', 'Componente opcional eliminado correctamente.');
        }
    }

    /**
     * Procesa los datos según el tipo de componente.
     */
    private function procesarDatos(array $data)
    {
        // 🔹 Convertir cualquier array en string (para evitar errores "Array to string conversion")
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $data[$key] = implode(', ', $value);
            }
        }

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

        foreach ($campos as $c) {
            if (!isset($data[$c])) $data[$c] = '';
        }

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
                    $seguridad = [$seguridad]; // convertimos a array si viene como string
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

                // Soporte de canales
                $canales = $data['canales_tarjeta_sonido'] ?? [];
                if (!is_array($canales)) {
                    $canales = [$canales];
                }
                $data['canales'] = implode(', ', array_filter($canales));

                // Tipos de salida
                $salidas = $data['salidas_audio'] ?? [];
                if (!is_array($salidas)) {
                    $salidas = [$salidas];
                }
                $data['salidas_audio'] = implode(', ', array_filter($salidas));

                // Resolución de audio
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

        // Esto asegura que 'estado' siempre sea uno de los permitidos
        $estadosValidos = ['Operativo', 'Medio dañado', 'Dañado'];
        if (!in_array($data['estado'], $estadosValidos)) {
            $data['estado'] = 'Operativo';
        }

        // Estado activo por defecto
        if (empty($data['estadoElim'])) $data['estadoElim'] = 'Activo';

        return $data;
    }

    private function obtenerSlotsLibres($id_equipo)
    {
        $tarjetaMadre = Componente::where('id_equipo', $id_equipo)
            ->where('tipo_componente', 'Tarjeta Madre')
            ->first();

        if (!$tarjetaMadre) return [];

        $cantidadSlots = (int) $tarjetaMadre->cantidad_slot_memoria;

        // Creamos los nombres estándar
        $todosLosSlots = [];
        for ($i = 1; $i <= $cantidadSlots; $i++) {
            $todosLosSlots[] = "Slot $i";
        }

        // --- Normalizamos los ocupados de ambos orígenes ---
        $slotsOcupadosOpcionales = ComponenteOpcional::where('id_equipo', $id_equipo)
            ->where('tipo_opcional', 'Memoria Ram')
            ->where('estadoElim', 'Activo')
            ->pluck('slot_memoria')
            ->map(fn($s) => "Slot " . (int) filter_var($s, FILTER_SANITIZE_NUMBER_INT))
            ->toArray();

        $slotsOcupadosComponente = Componente::where('id_equipo', $id_equipo)
            ->where('tipo_componente', 'Memoria RAM')
            ->where('estadoElim', 'Activo') // <- agregar este filtro
            ->pluck('slot_memoria')
            ->map(fn($s) => "Slot " . (int) filter_var($s, FILTER_SANITIZE_NUMBER_INT))
            ->toArray();

        $slotsOcupados = array_unique(array_merge($slotsOcupadosOpcionales, $slotsOcupadosComponente));

        // Devolvemos sólo los libres
        return array_values(array_diff($todosLosSlots, $slotsOcupados));
    }

    public function porEquipo($id_equipo)
    {
        $equipo = Equipo::findOrFail($id_equipo);
        $componentes = Componente::where('id_equipo', $id_equipo)->get();
        $opcionales = ComponenteOpcional::obtenerPorEquipo($id_equipo);

        return view('componentes.porEquipo', compact('equipo', 'componentes', 'opcionales', 'id_equipo'));
    }

    public function createPorEquipo($id_equipo)
    {
        $equipoSeleccionado = Equipo::findOrFail($id_equipo);
        $equipos = Equipo::all();

        return view('componentesOpcionales.create', [
            'porEquipo' => true,
            'equipoSeleccionado' => $equipoSeleccionado,
            'equipos' => $equipos,
            'id_equipo' => $id_equipo, // <--- agregar esto
        ]);
    }


    public function editPorEquipo($id)
    {
        $opcional = ComponenteOpcional::with('equipo')->findOrFail($id);
        $equipos = Equipo::where('estado', 'Activo')->get();

        return view('componentesOpcionales.edit', [
            'opcional' => $opcional,
            'equipos' => $equipos,
            'porEquipo' => true,
            'id_equipo' => $opcional->id_equipo, // <--- usar id_equipo real
            'equipoSeleccionado' => $opcional->equipo ?? null, // opcional si quieres usarlo en la vista
        ]);
    }
}
