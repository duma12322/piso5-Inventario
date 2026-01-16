<?php

namespace App\Http\Controllers;

// -----------------------
// IMPORTACIONES
// -----------------------

// Importa la clase Request de Laravel para manejar solicitudes HTTP
use Illuminate\Http\Request;

// Importa el modelo Componente para manipular los componentes principales
use App\Models\Componente;

// Importa el modelo Equipo para trabajar con información de los equipos
use App\Models\Equipo;

// Importa el modelo ComponenteOpcional para manejar los componentes opcionales
use App\Models\ComponenteOpcional;

// Importa el modelo LogModel para guardar acciones de los usuarios en la base de datos
use App\Models\Log as LogModel;

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
class ComponenteController extends Controller
{
    /**
     * ------------------------------------------------------------------
     * Constructor del controlador
     * ------------------------------------------------------------------
     * Aplica el middleware de autenticación a todas las rutas del
     * controlador, asegurando que solo usuarios autenticados
     * puedan acceder.
     */
    public function __construct()
    {
        $this->middleware('auth'); // protege todas las rutas
    }

    /**
     * ------------------------------------------------------------------
     * Método: index
     * ------------------------------------------------------------------
     * Lista todos los componentes activos con búsqueda avanzada.
     * 
     * Funcionalidades:
     *  - Filtra por múltiples campos del componente y del equipo
     *  - Permite búsqueda por palabras separadas
     *  - Limpia caracteres especiales
     *  - Ordena por id_componente descendente
     *  - Paginación con preservación de query string
     *
     * Parámetros:
     * @param \Illuminate\Http\Request $request
     *
     * Retorna:
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        // Consulta base: solo componentes activos con relación al equipo
        $query = Componente::activos()->with('equipo');

        if ($search) {
            // 1. Limpiar caracteres no deseados
            $cleanSearch = preg_replace('/[^\wñÑáéíóúÁÉÍÓÚ ]+/u', ' ', $search);

            // 2. Dividir texto en palabras
            $terms = array_filter(explode(' ', $cleanSearch));

            // 3. Aplicar filtros por cada término
            foreach ($terms as $term) {
                $query->where(function ($q) use ($term) {
                    $q->where('tipo_componente', 'like', "%{$term}%")
                        ->orWhere('marca', 'like', "%{$term}%")
                        ->orWhere('modelo', 'like', "%{$term}%")
                        ->orWhere('estado', 'like', "%{$term}%")
                        ->orWhere('capacidad', 'like', "%{$term}%")
                        ->orWhereHas('equipo', function ($qe) use ($term) {
                            $qe->where('marca', 'like', "%{$term}%")
                                ->orWhere('modelo', 'like', "%{$term}%");
                        });
                });
            }
        }

        // Ordenar, paginar y mantener query string
        $componentes = $query->orderBy('id_componente', 'desc')
            ->paginate(10)
            ->withQueryString();

        // Retornar vista con los datos
        return view('componentes.index', compact('componentes', 'search'));
    }

    /**
     * ------------------------------------------------------------------
     * Método: create
     * ------------------------------------------------------------------
     * Muestra el formulario de creación de componentes.
     * 
     * Provee:
     *  - Lista de equipos activos
     *  - Componentes únicos predefinidos
     *  - Indicadores de creación por equipo o general
     *
     * Retorna:
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $equipos = Equipo::where('estado', 'Activo')->get();
        $componentesUnicos = [
            'Tarjeta Madre',
            'Procesador',
            'Fuente de Poder',
            'Tarjeta Grafica',
            'Tarjeta Red',
            'Tarjeta de Sonido Integrada'
        ];

        $porEquipo = false;
        $equipo_seleccionado = null;

        return view('componentes.create', compact('equipos', 'componentesUnicos', 'porEquipo', 'equipo_seleccionado'));
    }

    /**
     * ------------------------------------------------------------------
     * Método: store
     * ------------------------------------------------------------------
     * Guarda un nuevo componente realizando validaciones técnicas
     * según el tipo de componente:
     * 
     * Validaciones incluidas:
     *  - Compatibilidad de socket entre procesador y tarjeta madre
     *  - Tipo de RAM y slots disponibles
     *  - Frecuencia de RAM compatible con la tarjeta madre
     *
     * Además:
     *  - Registra la acción en el log
     *  - Redirige según si se creó por equipo o de forma general
     *
     * Parámetros:
     * @param \Illuminate\Http\Request $request
     *
     * Retorna:
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Procesar y normalizar datos
        $data = $this->procesarDatos($request->all());

        /**
         * --------------------------------------------------------------
         * VALIDACIÓN DE SOCKET PARA PROCESADOR
         * --------------------------------------------------------------
         */
        if ($data['tipo_componente'] === 'Procesador') {
            $tarjetaMadre = Componente::where('id_equipo', $data['id_equipo'])
                ->where('tipo_componente', 'Tarjeta Madre')
                ->activos()
                ->first();

            if ($tarjetaMadre) {
                $socketMother = $this->normalizarSocket($tarjetaMadre->socket);
                $socketCPU = $this->normalizarSocket($data['socket']);

                if ($socketMother !== $socketCPU) {
                    return back()->withInput()->withErrors([
                        'socket' => "El socket del procesador ({$data['socket']}) no es compatible con la tarjeta madre ({$tarjetaMadre->socket})."
                    ]);
                }
            }
        }

        /**
         * --------------------------------------------------------------
         * VALIDACIÓN DE SOCKET PARA TARJETA MADRE
         * --------------------------------------------------------------
         */
        if ($data['tipo_componente'] === 'Tarjeta Madre') {
            $procesador = Componente::where('id_equipo', $data['id_equipo'])
                ->where('tipo_componente', 'Procesador')
                ->activos()
                ->first();

            if ($procesador) {
                $socketMother = $this->normalizarSocket($data['socket']);
                $socketCPU = $this->normalizarSocket($procesador->socket);

                if ($socketMother !== $socketCPU) {
                    return back()->withInput()->withErrors([
                        'socket' => "El socket de la tarjeta madre ({$data['socket']}) no es compatible con el procesador ({$procesador->socket})."
                    ]);
                }
            }
        }

        /**
         * --------------------------------------------------------------
         * VALIDACIÓN DE MEMORIA RAM
         * --------------------------------------------------------------
         */
        if ($data['tipo_componente'] === 'Memoria RAM') {
            $tarjetaMadre = Componente::where('id_equipo', $data['id_equipo'])
                ->where('tipo_componente', 'Tarjeta Madre')
                ->activos()
                ->first();

            // Verificar que exista tarjeta madre
            if (!$tarjetaMadre) {
                return back()->withInput()->withErrors([
                    'tarjeta_madre' => 'El equipo no tiene tarjeta madre registrada, no se puede agregar RAM.'
                ]);
            }

            // Validar tipo de RAM
            if ($tarjetaMadre->tipo) {
                $tipoMother = $this->normalizarTipoRAM($tarjetaMadre->tipo);
                $tipoRAM = $this->normalizarTipoRAM($data['tipo']);
                if ($tipoMother !== $tipoRAM) {
                    return back()->withInput()->withErrors([
                        'tipo' => "El tipo de memoria RAM ({$data['tipo']}) no es compatible con la tarjeta madre ({$tarjetaMadre->tipo})."
                    ]);
                }
            }

            // Validación de slot
            $slot = $data['slot_memoria'] ?? null;
            if (!$slot) {
                return back()->withInput()->withErrors([
                    'slot_memoria' => 'Debe seleccionar un slot de memoria.'
                ]);
            }

            $numeroSlot = (int) filter_var($slot, FILTER_SANITIZE_NUMBER_INT);
            $slot = "Slot $numeroSlot";

            // Validar slots según la tarjeta madre
            $cantidadSlots = (int) $tarjetaMadre->cantidad_slot_memoria;
            $slotsValidos = [];
            for ($i = 1; $i <= $cantidadSlots; $i++) {
                $slotsValidos[] = "Slot $i";
            }

            if (!in_array($slot, $slotsValidos)) {
                return back()->withInput()->withErrors([
                    'slot_memoria' => "El {$slot} no es válido para esta tarjeta madre."
                ]);
            }

            // Validar que el slot esté libre
            $slotsLibres = $this->obtenerSlotsLibres($data['id_equipo']);
            if (!in_array($slot, $slotsLibres)) {
                return back()->withInput()->withErrors([
                    'slot_memoria' => "El {$slot} ya está ocupado por otra RAM."
                ]);
            }

            $data['slot_memoria'] = $slot;

            // Validación de frecuencia
            if ($tarjetaMadre->frecuencias_memoria) {
                $frecuenciasPermitidas = array_map('trim', explode(',', $tarjetaMadre->frecuencias_memoria));
                $frecuenciaSeleccionada = (int) $data['frecuencia'];

                if (!in_array($frecuenciaSeleccionada, $frecuenciasPermitidas)) {
                    $frecuenciasFormateadas = implode(', ', $frecuenciasPermitidas);
                    return back()->withInput()->withErrors([
                        'frecuencia' => "La frecuencia ingresada ({$frecuenciaSeleccionada} MHz) no es compatible con la tarjeta madre. Frecuencias válidas: {$frecuenciasFormateadas} MHz."
                    ]);
                }
            }
        }

        /**
         * --------------------------------------------------------------
         * CREACIÓN DEL COMPONENTE
         * --------------------------------------------------------------
         */
        $componente = Componente::create($data);

        /**
         * --------------------------------------------------------------
         * REGISTRO EN LOG
         * --------------------------------------------------------------
         */
        $usuario = Auth::check() ? Auth::user()->usuario : 'Sistema';
        LogModel::create([
            'usuario' => $usuario,
            'accion' => 'Creado el componente ID: ' . $componente->tipo_componente,
            'fecha' => now()
        ]);

        /**
         * --------------------------------------------------------------
         * REDIRECCIÓN FINAL CON MENSAJE
         * --------------------------------------------------------------
         */
        $mensaje = 'Componente agregado correctamente.';
        if ($request->has('porEquipo') && $request->porEquipo) {
            return redirect()->route('componentes.porEquipo', $request->id_equipo)
                ->with('success', $mensaje);
        } else {
            return redirect()->route('componentes.index')
                ->with('success', $mensaje);
        }
    }

    // -----------------------
    // Función privada para normalizar socket
    // -----------------------
    // Esta función convierte cualquier valor de socket a minúsculas y
    // elimina espacios, para que la comparación sea uniforme.
    private function normalizarSocket($socket)
    {
        return strtolower(str_replace(' ', '', $socket));
    }

    // -----------------------
    // Normalizar tipo de RAM
    // -----------------------
    // Convierte el tipo de RAM a minúsculas y elimina espacios.
    // Ejemplo: "DDR3" => "ddr3"
    private function normalizarTipoRAM($tipo)
    {
        return strtolower(str_replace(' ', '', $tipo));
    }

    // -----------------------
    // Mostrar formulario de edición de un componente
    // -----------------------
    // Recupera un componente por su ID y prepara datos para la vista:
    //  - Equipos activos
    //  - Componentes únicos
    //  - Tipos de componentes existentes
    //  - Tipos de componentes generales
    // Además pasa el indicador $porEquipo para decidir flujo de la vista
    public function edit($id, $porEquipo = false)
    {
        $componente = Componente::findOrFail($id);
        $equipos = Equipo::where('estado', 'Activo')->get();

        // Componentes únicos (solo uno por equipo)
        $componentesUnicos = [
            'Tarjeta Madre',
            'Procesador',
            'Fuente de Poder',
            'Tarjeta Grafica',
            'Tarjeta Red',
            'Tarjeta de Sonido Integrada'
        ];

        // Todos los tipos de componentes (para el select)
        $tiposComponentes = [
            'Tarjeta Madre',
            'Procesador',
            'Fuente de Poder',
            'Tarjeta Grafica',
            'Tarjeta Red',
            'Tarjeta de Sonido Integrada',
            'Memoria RAM',
            'Disco Duro',
            'Unidad Optica',
            'Fan Cooler'
        ];

        // Componentes únicos ya existentes en el equipo (excepto el actual)
        $componentesExistentes = Componente::where('id_equipo', $componente->id_equipo)
            ->whereIn('tipo_componente', $componentesUnicos)
            ->where('id_componente', '<>', $componente->id_componente)
            ->where('estadoElim', 'Activo')  // solo activos
            ->pluck('tipo_componente')
            ->toArray();

        // Pasamos todo a la vista
        return view('componentes.edit', compact(
            'componente',
            'equipos',
            'componentesUnicos',
            'componentesExistentes',
            'tiposComponentes',
            'porEquipo'
        ));
    }

    // -----------------------
    // Actualizar componente
    // -----------------------
    // Este método actualiza un componente existente con múltiples validaciones:
    //  - Compatibilidad de socket entre procesador y tarjeta madre
    //  - Validación de tipo y slots de memoria RAM
    //  - Validación de capacidad máxima de RAM
    //  - Validación de frecuencia de RAM
    // Registra todas las acciones en la tabla de logs.
    public function update(Request $request, $id)
    {
        $componente = Componente::findOrFail($id);
        $data = $this->procesarDatos($request->all());

        // -----------------------
        // Validación de socket: Procesador
        // -----------------------
        if ($data['tipo_componente'] === 'Procesador') {
            $tarjetaMadre = Componente::where('id_equipo', $data['id_equipo'])
                ->where('tipo_componente', 'Tarjeta Madre')
                ->activos()
                ->first();

            if ($tarjetaMadre) {
                $socketMother = $this->normalizarSocket($tarjetaMadre->socket);
                $socketCPU = $this->normalizarSocket($data['socket']);

                if ($socketMother !== $socketCPU) {
                    return back()->withInput()->withErrors([
                        'socket' => "El socket del procesador ({$data['socket']}) no es compatible con la tarjeta madre ({$tarjetaMadre->socket})."
                    ]);
                }
            }
        }

        // -----------------------
        // Validación de socket: Tarjeta Madre
        // -----------------------
        if ($data['tipo_componente'] === 'Tarjeta Madre') {
            $procesador = Componente::where('id_equipo', $data['id_equipo'])
                ->where('tipo_componente', 'Procesador')
                ->activos()
                ->first();

            if ($procesador) {
                $socketMother = $this->normalizarSocket($data['socket']);
                $socketCPU = $this->normalizarSocket($procesador->socket);

                if ($socketMother !== $socketCPU) {
                    return back()->withInput()->withErrors([
                        'socket' => "El socket de la tarjeta madre ({$data['socket']}) no es compatible con el procesador ({$procesador->socket})."
                    ]);
                }
            }
        }

        // -----------------------
        // Validación de memoria RAM
        // -----------------------
        if ($data['tipo_componente'] === 'Memoria RAM') {
            $tarjetaMadre = Componente::where('id_equipo', $data['id_equipo'])
                ->where('tipo_componente', 'Tarjeta Madre')
                ->activos()
                ->first();

            if (!$tarjetaMadre) {
                return back()->withInput()->withErrors([
                    'tarjeta_madre' => 'El equipo no tiene tarjeta madre registrada, no se puede actualizar RAM.'
                ]);
            }

            // Normalizamos slot ingresado
            $slot = $data['slot_memoria'] ?? null;
            if (!$slot) {
                return back()->withInput()->withErrors([
                    'slot_memoria' => 'Debe seleccionar un slot de memoria.'
                ]);
            }

            // Formato estándar: "Slot X"
            $numeroSlot = (int) filter_var($slot, FILTER_SANITIZE_NUMBER_INT);
            $slot = "Slot $numeroSlot";

            // Validar rango de slots
            $cantidadSlots = (int) $tarjetaMadre->cantidad_slot_memoria;
            if ($numeroSlot < 1 || $numeroSlot > $cantidadSlots) {
                return back()->withInput()->withErrors([
                    'slot_memoria' => "El slot {$numeroSlot} no es válido para esta tarjeta madre."
                ]);
            }

            $slotAnterior = $componente->slot_memoria;
            if ($slot != $slotAnterior) {
                $slotsLibres = $this->obtenerSlotsLibres($data['id_equipo']);
                if (!in_array($slot, $slotsLibres)) {
                    return back()->withInput()->withErrors([
                        'slot_memoria' => "El {$slot} ya está ocupado por otra RAM."
                    ]);
                }
            }

            // Validar compatibilidad de tipo RAM
            if ($tarjetaMadre->tipo) {
                $tipoMother = $this->normalizarTipoRAM($tarjetaMadre->tipo);
                $tipoRAM = $this->normalizarTipoRAM($data['tipo']);
                if ($tipoMother !== $tipoRAM) {
                    return back()->withInput()->withErrors([
                        'tipo' => "El tipo de memoria RAM ({$data['tipo']}) no es compatible con la tarjeta madre ({$tarjetaMadre->tipo})."
                    ]);
                }
            }

            $data['slot_memoria'] = $slot;

            // Validación de capacidad máxima de RAM
            $memoriaMaxima = (int) $tarjetaMadre->memoria_maxima;
            $capacidadIngresada = (int) $data['capacidad'];
            $ramExistente = Componente::where('id_equipo', $data['id_equipo'])
                ->where('tipo_componente', 'Memoria RAM')
                ->where('id_componente', '!=', $id)
                ->activos()
                ->sum('capacidad');

            if (($ramExistente + $capacidadIngresada) > $memoriaMaxima) {
                return back()->withInput()->withErrors([
                    'capacidad' => "La RAM total excede la capacidad máxima de la tarjeta madre ({$memoriaMaxima} GB)."
                ]);
            }

            // Validación de frecuencia
            if ($tarjetaMadre->frecuencias_memoria) {
                $frecuenciasPermitidas = array_map('trim', explode(',', $tarjetaMadre->frecuencias_memoria));
                $frecuenciaSeleccionada = (int) $data['frecuencia'];

                if (!in_array($frecuenciaSeleccionada, $frecuenciasPermitidas)) {
                    $frecuenciasFormateadas = implode(', ', $frecuenciasPermitidas);
                    return back()->withInput()->withErrors([
                        'frecuencia' => "La frecuencia ingresada ({$frecuenciaSeleccionada} MHz) no es compatible con la tarjeta madre. Frecuencias válidas: {$frecuenciasFormateadas} MHz."
                    ]);
                }
            }
        } else {
            unset($data['slot_memoria']); // No aplicable a otros componentes
        }

        // -----------------------
        // Actualizar componente en la BD
        // -----------------------
        $componente->update($data);

        // -----------------------
        // Registrar acción en logs
        // -----------------------
        $usuario = Auth::check() ? Auth::user()->usuario : 'Sistema';
        try {
            LogModel::create([
                'usuario' => $usuario,
                'accion' => 'Actualizado el componente ID: ' . $componente->tipo_componente,
                'detalles' => json_encode($data),
                'fecha' => now()
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error guardando log: ' . $e->getMessage());
        }

        // -----------------------
        // Redirección final
        // -----------------------
        if ($request->input('porEquipo')) {
            return redirect()->route('componentes.porEquipo', $request->input('id_equipo'))
                ->with('success', 'Componente actualizado correctamente.');
        } else {
            return redirect()->route('componentes.index')
                ->with('success', 'Componente actualizado correctamente.');
        }
    }


    // -----------------------
    // Eliminación lógica de un componente
    // -----------------------
    // Este método cambia el estado del componente a "Inactivo" en lugar
    // de eliminar físicamente el registro de la base de datos.
    // También registra la acción en la tabla de logs y redirige según el contexto.
    public function destroy(Request $request, $id)
    {
        $componente = Componente::findOrFail($id);

        // Cambiar estado a inactivo (eliminación lógica)
        $componente->estadoElim = 'Inactivo';
        $componente->save();

        // Registrar acción en log
        $usuario = Auth::check() ? Auth::user()->usuario : 'Sistema';
        try {
            LogModel::create([
                'usuario' => $usuario,
                'accion' => 'Eliminado el componente ID: ' . $componente->tipo_componente,
                'fecha' => now()
            ]);
        } catch (\Exception $e) {
            // Registrar error en log del sistema si falla
            \Illuminate\Support\Facades\Log::error('Error guardando log: ' . $e->getMessage());
        }

        // Redirección según contexto
        if ($request->input('porEquipo')) {
            return redirect()->route('componentes.porEquipo', $request->input('id_equipo'))
                ->with('success', 'Componente eliminado correctamente.');
        } else {
            return redirect()->route('componentes.index')
                ->with('success', 'Componente eliminado correctamente.');
        }
    }

    // -----------------------
    // Listar componentes de un equipo específico
    // -----------------------
    // Recupera todos los componentes y opcionales activos asociados al equipo
    // y los envía a la vista correspondiente.
    public function porEquipo($id_equipo)
    {
        // Solo componentes activos
        $componentes = Componente::where('id_equipo', $id_equipo)
            ->where('estadoElim', 'Activo')
            ->get();

        // Solo componentes opcionales activos
        $opcionales = ComponenteOpcional::where('id_equipo', $id_equipo)
            ->where('estadoElim', 'Activo')
            ->get();

        // Pasamos datos a la vista
        return view('componentes.porEquipo', compact('componentes', 'opcionales', 'id_equipo'));
    }

    // -----------------------
    // Procesar datos del request para guardado/actualización
    // -----------------------
    // Normaliza, valida y prepara los datos del formulario según el tipo de componente.
    // - Convierte arrays a strings
    // - Normaliza campos de texto y numéricos
    // - Ajusta campos específicos según el tipo de componente
    // - Establece valores por defecto
    private function procesarDatos(array $data)
    {
        // Campos de texto generales
        $camposTexto = [
            'marca',
            'modelo',
            'arquitectura',
            'tipo',
            'ubicacion',
            'rgb_led',
            'ranuras_expansion',
            'puertos_internos',
            'puertos_externos',
            'conectores_alimentacion',
            'bios_uefi',
            'socket',
            'soporte_memoria',
            'tipo_conector',
            'conectividad_soporte',
            'salidas_video',
            'soporte_apis',
            'fabricante_controlador',
            'modelo_red',
            'tipo_conector_fisico',
            'mac_address',
            'drivers_sistema',
            'tipos_discos',
            'interfaz_conexion',
            'tipo_cooler',
            'estadoElim',
            'slot_memoria',
            'frecuencias_memoria',
            'memoria_maxima',
            'capacidad'
        ];

        // Campos numéricos
        $camposNumericos = [
            'frecuencia',
            'potencia',
            'voltajes_fuente',
            'nucleos',
            'velocidad_transferencia',
            'consumo',
            'consumo_electrico',
            'cantidad_slot_memoria'
        ];

        // Convertir arrays a strings separados por comas
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $data[$key] = implode(',', $value);
            }
        }

        // Campos enumerados con valores permitidos
        $camposEnum = ['estado', 'compatibilidad'];

        // Campos de fecha
        $camposFecha = ['fecha_instalacion'];

        // Inicializar campos si no existen
        foreach ($camposTexto as $c) $data[$c] = $data[$c] ?? null;
        foreach ($camposNumericos as $c) $data[$c] = isset($data[$c]) && $data[$c] !== '' ? $data[$c] : null;
        foreach ($camposFecha as $c) $data[$c] = !empty($data[$c]) ? $data[$c] : null;
        foreach ($camposEnum as $c) $data[$c] = in_array($data[$c] ?? '', ['Operativo', 'Medio dañado', 'Dañado', 'Sí', 'Parcialmente', 'No']) ? $data[$c] : null;

        // -----------------------
        // Ajustes según tipo de componente
        // -----------------------
        switch ($data['tipo_componente'] ?? '') {

            // Tarjeta Madre
            case 'Tarjeta Madre':
                $data['marca'] = $data['marca_tarjeta_madre'] ?? $data['marca'];
                $data['modelo'] = $data['modelo_tarjeta_madre'] ?? $data['modelo'];
                $data['socket'] = $data['socket_tarjeta_madre'] ?? $data['socket'];
                $data['tipo'] = $data['tipo_tarjeta_madre'] ?? $data['tipo'];
                $data['cantidad_slot_memoria'] = $data['cantidad_slot_memoria'] ?? $data['cantidad_slot_memoria'];

                // Ranuras de expansión
                if (!empty($data['ranuras_expansion'])) {
                    $data['ranuras_expansion'] = is_array($data['ranuras_expansion']) ? implode(',', $data['ranuras_expansion']) : $data['ranuras_expansion'];
                } else {
                    $data['ranuras_expansion'] = null;
                }

                // Puertos internos
                $puertosInternos = $data['puertos_internos'] ?? [];
                if (!is_array($puertosInternos)) $puertosInternos = [$puertosInternos];
                if (!empty($data['puertos_internos_otros'])) $puertosInternos[] = $data['puertos_internos_otros'];
                $data['puertos_internos'] = !empty($puertosInternos) ? implode(',', $puertosInternos) : null;

                // Puertos externos
                $puertosExternos = $data['puertos_externos'] ?? [];
                if (!is_array($puertosExternos)) $puertosExternos = [$puertosExternos];
                if (!empty($data['puertos_externos_otros'])) $puertosExternos[] = $data['puertos_externos_otros'];
                $data['puertos_externos'] = !empty($puertosExternos) ? implode(',', $puertosExternos) : null;

                // Conectores de alimentación
                $conectores = $data['conectores_alimentacion'] ?? [];
                if (!is_array($conectores)) $conectores = [$conectores];
                if (!empty($data['conectores_alimentacion_otros'])) $conectores[] = $data['conectores_alimentacion_otros'];
                $data['conectores_alimentacion'] = !empty($conectores) ? implode(',', $conectores) : null;

                // Frecuencias de memoria
                if (isset($data['frecuencias_memoria']) && is_array($data['frecuencias_memoria'])) {
                    $data['frecuencias_memoria'] = implode(',', $data['frecuencias_memoria']);
                }

                $data['memoria_maxima'] = $data['memoria_maxima'] ?? null;
                $data['bios_uefi'] = $data['bios_uefi'] ?? $data['bios_uefi'];
                $data['estado'] = $data['estado_tarjeta_madre'] ?? $data['estado'];
                $data['detalles'] = $data['detalles_tarjeta_madre'] ?? $data['detalles'] ?? null;
                break;

            // Memoria RAM
            case 'Memoria RAM':
                $data['marca'] = $data['marca_memoria'] ?? null;
                $data['tipo'] = $data['tipo_ram'] ?? null;

                // Normalizar slot
                $slot = $data['slot_memoria'] ?? null;
                if ($slot !== null) {
                    $slot = strtolower(trim($slot));
                    $slot = str_replace([' ', '-', '_'], '', $slot);
                    if (preg_match('/\d+/', $slot, $matches)) $slot = 'Slot ' . $matches[0];
                    else $slot = null;
                }
                $data['slot_memoria'] = $slot;

                $data['capacidad'] = $data['capacidad_ram'] ?? null;
                $data['frecuencia'] = $data['frecuencia_ram'] ?? null;
                $data['estado'] = $data['estado_memoria'] ?? 'Operativo';
                $data['detalles'] = $data['detalles_ram'] ?? null;
                break;

            // Procesador
            case 'Procesador':
                $data['marca'] = $data['marca_procesador'] ?? $data['marca'];
                $data['modelo'] = $data['modelo_procesador'] ?? $data['modelo'];
                $data['arquitectura'] = $data['arquitectura_procesador'] ?? $data['arquitectura'];
                $data['nucleos'] = $data['nucleos'] ?? $data['nucleos'];
                $data['frecuencia'] = $data['frecuencia_procesador'] ?? $data['frecuencia'];
                $data['socket'] = $data['socket_procesador'] ?? $data['socket'];
                $data['consumo'] = $data['consumo_procesador'] ?? $data['consumo'];
                $data['estado'] = $data['estado_procesador'] ?? $data['estado'];
                $data['detalles'] = $data['detalles_procesador'] ?? $data['detalles'] ?? null;
                break;

            // Fuente de Poder
            case 'Fuente de Poder':
                $data['marca'] = $data['marca_fuente'] ?? $data['marca'];
                $data['modelo'] = $data['modelo_fuente'] ?? $data['modelo'];
                $data['potencia'] = $data['potencia'] ?? $data['potencia'];
                if (!empty($data['voltajes_fuente'])) {
                    $voltajes = is_array($data['voltajes_fuente']) ? $data['voltajes_fuente'] : explode(',', $data['voltajes_fuente']);
                    if (!empty($data['voltaje_otro'])) $voltajes[] = $data['voltaje_otro'];
                    $data['voltajes_fuente'] = implode(',', $voltajes);
                } else {
                    $data['voltajes_fuente'] = !empty($data['voltaje_otro']) ? $data['voltaje_otro'] : null;
                }
                $data['estado'] = $data['estado_fuente'] ?? $data['estado'];
                $data['detalles'] = $data['detalles_fuente'] ?? $data['detalles'] ?? null;
                break;

            // Otros tipos (Disco Duro, Tarjeta Grafica, Tarjeta Red, Unidad Optica, Fan Cooler)
            // Cada uno normaliza campos de marca, modelo, capacidad, salidas, estado y detalles
            // de manera similar al caso anterior
            case 'Disco Duro':
                $data['marca'] = $data['marca_disco'] ?? $data['marca'];
                $data['tipo'] = $data['tipo_disco'] ?? $data['tipo'];
                $data['capacidad'] = $data['capacidad_disco'] ?? $data['capacidad'];
                $data['estado'] = $data['estado_disco'] ?? $data['estado'];
                $data['detalles'] = $data['detalles_disco'] ?? $data['detalles'] ?? null;
                break;

            case 'Tarjeta Grafica':
                $data['marca'] = $data['marca_tarjeta_grafica'] ?? $data['marca'];
                $data['modelo'] = $data['modelo_tarjeta_grafica'] ?? $data['modelo'];
                $data['capacidad'] = $data['capacidad_tarjeta_grafica'] ?? $data['capacidad'];
                if (isset($data['salidas_video']) && is_array($data['salidas_video'])) $data['salidas_video'] = implode(', ', $data['salidas_video']);
                $data['estado'] = $data['estado_tarjeta_grafica'] ?? $data['estado'];
                $data['detalles'] = $data['detalles_tarjeta_grafica'] ?? $data['detalles'] ?? null;
                break;

            case 'Tarjeta Red':
                $data['marca'] = $data['marca_tarjeta_red'] ?? $data['marca'];
                $data['modelo'] = $data['modelo_tarjeta_red'] ?? $data['modelo'];
                if (!empty($data['tipo_tarjeta_red'])) $data['tipo'] = is_array($data['tipo_tarjeta_red']) ? implode(', ', $data['tipo_tarjeta_red']) : (string)$data['tipo_tarjeta_red'];
                else $data['tipo'] = null;
                $data['velocidad_transferencia'] = $data['velocidad_transferencia'] ?? $data['velocidad_transferencia'];
                $data['estado'] = $data['estado_tarjeta_red'] ?? $data['estado'];
                $data['detalles'] = $data['detalles_tarjeta_red'] ?? $data['detalles'] ?? null;
                break;

            case 'Unidad Optica':
                $data['marca'] = $data['marca_unidad'] ?? $data['marca'];
                $data['tipo'] = $data['tipo_unidad'] ?? $data['tipo'];
                if (isset($data['tipos_discos']) && is_array($data['tipos_discos'])) $data['tipos_discos'] = implode(',', $data['tipos_discos']);
                else $data['tipos_discos'] = null;
                $data['estado'] = $data['estado_unidad'] ?? $data['estado'];
                $data['detalles'] = $data['detalles_unidad'] ?? $data['detalles'] ?? null;
                break;

            case 'Fan Cooler':
                $data['marca'] = $data['marca_fan'] ?? $data['marca'];
                $data['tipo'] = $data['tipo_fan'] ?? $data['tipo'];
                $data['consumo'] = $data['consumo_fan'] ?? $data['consumo'];
                $data['ubicacion'] = $data['ubicacion'] ?? $data['ubicacion'];
                $data['estado'] = $data['estado_fan'] ?? $data['estado'];
                $data['detalles'] = $data['detalles_fan'] ?? $data['detalles'] ?? null;
                break;
        }

        // Limpiar campos no aplicables
        if (($data['tipo_componente'] ?? '') !== 'Unidad Optica') $data['tipos_discos'] = null;
        if (($data['tipo_componente'] ?? '') !== 'Tarjeta Grafica') $data['salidas_video'] = null;

        // Estado activo por defecto
        if (empty($data['estadoElim'])) $data['estadoElim'] = 'Activo';

        return $data;
    }

    // -----------------------
    // Obtener componentes únicos de un equipo
    // -----------------------
    // Devuelve los tipos de componentes únicos que ya existen en un equipo específico.
    // Este método se usa normalmente para evitar duplicados al agregar componentes.
    public function getComponentesUnicosPorEquipo($id_equipo)
    {
        try {
            // Lista de tipos de componentes únicos que no deben repetirse
            $componentesUnicos = [
                'Tarjeta Madre',
                'Procesador',
                'Fuente de Poder',
                'Tarjeta Grafica',
                'Tarjeta Red',
                'Tarjeta de Sonido Integrada'
            ];

            // Obtener los componentes existentes de estos tipos para el equipo
            $existentes = Componente::where('id_equipo', $id_equipo)
                ->whereIn('tipo_componente', $componentesUnicos)
                ->activos() // Scope para filtrar solo activos (coméntalo si no tienes el scope)
                ->pluck('tipo_componente');

            // Devolver como JSON para que el frontend lo pueda usar
            return response()->json($existentes);
        } catch (\Throwable $e) {
            // En caso de error, devolver JSON con mensaje y código 500
            return response()->json([
                'error' => 'Error al obtener componentes: ' . $e->getMessage()
            ], 500);
        }
    }

    // -----------------------
    // Formulario de creación para un equipo con componentes únicos
    // -----------------------
    // Prepara la vista para agregar un componente, evitando duplicar los componentes únicos
    // que ya existen en este equipo.
    public function createPorEquipo($id_equipo)
    {
        // Buscar el equipo seleccionado
        $equipoSeleccionado = Equipo::findOrFail($id_equipo);

        // Lista de todos los equipos (para select si se desea)
        $equipos = Equipo::all();

        // Tipos de componentes únicos
        $componentesUnicos = [
            'Tarjeta Madre',
            'Procesador',
            'Fuente de Poder',
            'Tarjeta Grafica',
            'Tarjeta Red',
            'Tarjeta de Sonido Integrada'
        ];

        // Componentes únicos ya existentes para este equipo
        $componentesExistentes = Componente::where('id_equipo', $id_equipo)
            ->whereIn('tipo_componente', $componentesUnicos)
            ->where('estadoElim', 'Activo')
            ->pluck('tipo_componente')
            ->toArray();

        // Indicador para la vista de que es "por equipo"
        $porEquipo = true;

        // Retornar la vista con todos los datos necesarios
        return view('componentes.create', compact(
            'porEquipo',
            'equipoSeleccionado',
            'equipos',
            'componentesUnicos',
            'componentesExistentes'
        ))->with('id_equipo', $id_equipo); // Pasamos explícitamente el ID del equipo
    }

    // -----------------------
    // Editar componente "por equipo"
    // -----------------------
    // Prepara el formulario de edición para un componente específico de un equipo
    public function editPorEquipo($id)
    {
        // Obtener el componente a editar
        $componente = Componente::findOrFail($id);

        // Indicador para la vista de que es "por equipo"
        $porEquipo = true;

        // ID real del equipo asociado
        $id_equipo = $componente->id_equipo;

        // Llamar al método edit original y pasar parámetros adicionales a la vista
        return $this->edit($id, $porEquipo)->with([
            'id_equipo' => $id_equipo,
            'equipoSeleccionado' => $componente->equipo ?? null
        ]);
    }

    // -----------------------
    // Obtener slots libres de memoria RAM en un equipo
    // -----------------------
    // Calcula qué slots de memoria están libres según la tarjeta madre y los módulos RAM existentes.
    // Considera tanto componentes principales como opcionales.
    private function obtenerSlotsLibres($id_equipo)
    {
        // Buscar la tarjeta madre del equipo
        $tarjetaMadre = Componente::where('id_equipo', $id_equipo)
            ->where('tipo_componente', 'Tarjeta Madre')
            ->first();

        // Si no hay tarjeta madre, no hay slots
        if (!$tarjetaMadre)
            return [];

        // Cantidad total de slots según la tarjeta madre
        $cantidadSlots = (int) $tarjetaMadre->cantidad_slot_memoria;

        // Crear un array con todos los slots disponibles
        $todosLosSlots = [];
        for ($i = 1; $i <= $cantidadSlots; $i++) {
            $todosLosSlots[] = "Slot $i";
        }

        // Slots ocupados por RAM principal activa
        $slotsOcupadosComponente = Componente::where('id_equipo', $id_equipo)
            ->where('tipo_componente', 'Memoria RAM')
            ->where('estadoElim', 'Activo') // Solo activas
            ->pluck('slot_memoria')
            ->map(fn($s) => "Slot " . (int) filter_var($s, FILTER_SANITIZE_NUMBER_INT))
            ->toArray();

        // Slots ocupados por RAM opcional activa
        $slotsOcupadosOpcionales = ComponenteOpcional::where('id_equipo', $id_equipo)
            ->where('tipo_opcional', 'Memoria Ram')
            ->where('estadoElim', 'Activo')
            ->pluck('slot_memoria')
            ->map(fn($s) => "Slot " . (int) filter_var($s, FILTER_SANITIZE_NUMBER_INT))
            ->toArray();

        // Unir todos los slots ocupados y eliminar duplicados
        $slotsOcupados = array_unique(array_merge($slotsOcupadosComponente, $slotsOcupadosOpcionales));

        // Devolver solo los slots libres
        return array_values(array_diff($todosLosSlots, $slotsOcupados));
    }
}
