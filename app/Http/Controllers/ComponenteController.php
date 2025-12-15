<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Componente;
use App\Models\Equipo;
use App\Models\ComponenteOpcional;
use App\Models\Log as LogModel;
use Illuminate\Support\Facades\Auth;

class ComponenteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); // protege todas las rutas
    }

    // Lista todos los componentes activos
    public function index(Request $request)
    {
        $search = $request->input('search');

        $query = Componente::activos()->with('equipo');

        if ($search) {
            // 1. Limpiar caracteres extra
            $cleanSearch = preg_replace('/[^\wñÑáéíóúÁÉÍÓÚ ]+/u', ' ', $search);

            // 2. Dividir en palabras
            $terms = array_filter(explode(' ', $cleanSearch));

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

        $componentes = $query->orderBy('id_componente', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('componentes.index', compact('componentes', 'search'));
    }

    // Mostrar formulario de creación
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



    // Guardar nuevo componente
    public function store(Request $request)
    {
        $data = $this->procesarDatos($request->all());

        // VALIDACIÓN DE SOCKET
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

        // VALIDACIÓN DE TIPO DE RAM Y SLOTS
        if ($data['tipo_componente'] === 'Memoria RAM') {
            $tarjetaMadre = Componente::where('id_equipo', $data['id_equipo'])
                ->where('tipo_componente', 'Tarjeta Madre')
                ->activos()
                ->first();

            if (!$tarjetaMadre) {
                return back()->withInput()->withErrors([
                    'tarjeta_madre' => 'El equipo no tiene tarjeta madre registrada, no se puede agregar RAM.'
                ]);
            }

            // Validación de tipo RAM
            if ($tarjetaMadre->tipo) {
                $tipoMother = $this->normalizarTipoRAM($tarjetaMadre->tipo);
                $tipoRAM = $this->normalizarTipoRAM($data['tipo']);
                if ($tipoMother !== $tipoRAM) {
                    return back()->withInput()->withErrors([
                        'tipo' => "El tipo de memoria RAM ({$data['tipo']}) no es compatible con la tarjeta madre ({$tarjetaMadre->tipo})."
                    ]);
                }
            }

            // Normalizamos slot ingresado
            $slot = $data['slot_memoria'] ?? null;
            if (!$slot) {
                return back()->withInput()->withErrors([
                    'slot_memoria' => 'Debe seleccionar un slot de memoria.'
                ]);
            }

            $numeroSlot = (int) filter_var($slot, FILTER_SANITIZE_NUMBER_INT);
            $slot = "Slot $numeroSlot";

            // Validamos slots según tarjeta madre
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

            // Validamos que el slot no esté ocupado
            $slotsLibres = $this->obtenerSlotsLibres($data['id_equipo']);
            if (!in_array($slot, $slotsLibres)) {
                return back()->withInput()->withErrors([
                    'slot_memoria' => "El {$slot} ya está ocupado por otra RAM."
                ]);
            }

            $data['slot_memoria'] = $slot; // asignamos formato correcto

            // Validación frecuencia
            if ($tarjetaMadre->frecuencias_memoria) {
                // Obtenemos las frecuencias válidas como array de números limpios
                $frecuenciasPermitidas = array_map('trim', explode(',', $tarjetaMadre->frecuencias_memoria));

                // Quitamos posibles unidades en $data['frecuencia'] y lo comparamos como número
                $frecuenciaSeleccionada = (int) $data['frecuencia'];

                if (!in_array($frecuenciaSeleccionada, $frecuenciasPermitidas)) {
                    $frecuenciasFormateadas = implode(', ', $frecuenciasPermitidas);
                    return back()->withInput()->withErrors([
                        'frecuencia' => "La frecuencia ingresada ({$frecuenciaSeleccionada} MHz) no es compatible con la tarjeta madre. Frecuencias válidas: {$frecuenciasFormateadas} MHz."
                    ]);
                }
            }
        }

        // CREAR EL COMPONENTE
        $componente = Componente::create($data);

        // GUARDAR LOG
        $usuario = Auth::check() ? Auth::user()->usuario : 'Sistema';
        LogModel::create([
            'usuario' => $usuario,
            'accion' => 'Creado el componente ID: ' . $componente->tipo_componente,
            'fecha' => now()
        ]);

        // REDIRECCIÓN CON MENSAJE
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
    private function normalizarSocket($socket)
    {
        return strtolower(str_replace(' ', '', $socket));
    }

    // -----------------------
    // Normalizar tipo de RAM
    // -----------------------
    private function normalizarTipoRAM($tipo)
    {
        return strtolower(str_replace(' ', '', $tipo)); // "DDR3" => "ddr3"
    }


    // Mostrar formulario de edición
    public function edit($id, $porEquipo = false)
    {
        $componente = Componente::findOrFail($id);
        $equipos = Equipo::where('estado', 'Activo')->get();

        // Componentes únicos
        $componentesUnicos = [
            'Tarjeta Madre',
            'Procesador',
            'Fuente de Poder',
            'Tarjeta Grafica',
            'Tarjeta Red',
            'Tarjeta de Sonido Integrada'
        ];

        // TODOS los tipos de componentes (incluyendo los no únicos)
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

        // Componentes únicos ya existentes (excepto el actual)
        $componentesExistentes = Componente::where('id_equipo', $componente->id_equipo)
            ->whereIn('tipo_componente', $componentesUnicos)
            ->where('id_componente', '<>', $componente->id_componente)
            ->where('estadoElim', 'Activo')  // 🔹 solo activos para la regla de deshabilitar
            ->pluck('tipo_componente')
            ->toArray();

        // PASAMOS $porEquipo a la vista
        return view('componentes.edit', compact(
            'componente',
            'equipos',
            'componentesUnicos',
            'componentesExistentes',
            'tiposComponentes',
            'porEquipo'
        ));
    }

    // Actualizar componente
    public function update(Request $request, $id)
    {
        $componente = Componente::findOrFail($id);
        $data = $this->procesarDatos($request->all());

        // VALIDACIÓN DE SOCKET
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

        // VALIDACIÓN DE TIPO DE RAM
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

            // Convertimos cualquier entrada a formato estándar: "Slot X"
            $numeroSlot = (int) filter_var($slot, FILTER_SANITIZE_NUMBER_INT);
            $slot = "Slot $numeroSlot";

            // Validamos slots según la tarjeta madre
            $cantidadSlots = (int) $tarjetaMadre->cantidad_slot_memoria;
            if ($numeroSlot < 1 || $numeroSlot > $cantidadSlots) {
                return back()->withInput()->withErrors([
                    'slot_memoria' => "El slot {$numeroSlot} no es válido para esta tarjeta madre."
                ]);
            }

            $slotAnterior = $componente->slot_memoria; // slot actual en BD

            if ($slot != $slotAnterior) {
                // Solo si cambiaste el slot, verificamos si está libre
                $slotsLibres = $this->obtenerSlotsLibres($data['id_equipo']);
                if (!in_array($slot, $slotsLibres)) {
                    return back()->withInput()->withErrors([
                        'slot_memoria' => "El {$slot} ya está ocupado por otra RAM."
                    ]);
                }
            }

            // Validamos compatibilidad de tipo RAM con la tarjeta madre
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

            // Validación memoria máxima
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

            // Validación frecuencia
            if ($tarjetaMadre->frecuencias_memoria) {
                // Obtenemos las frecuencias válidas como array de números limpios
                $frecuenciasPermitidas = array_map('trim', explode(',', $tarjetaMadre->frecuencias_memoria));

                // Quitamos posibles unidades en $data['frecuencia'] y lo comparamos como número
                $frecuenciaSeleccionada = (int) $data['frecuencia'];

                if (!in_array($frecuenciaSeleccionada, $frecuenciasPermitidas)) {
                    $frecuenciasFormateadas = implode(', ', $frecuenciasPermitidas);
                    return back()->withInput()->withErrors([
                        'frecuencia' => "La frecuencia ingresada ({$frecuenciaSeleccionada} MHz) no es compatible con la tarjeta madre. Frecuencias válidas: {$frecuenciasFormateadas} MHz."
                    ]);
                }
            }
        } else {
            unset($data['slot_memoria']); // Para otros componentes no usamos slot
        }

        // ACTUALIZAR COMPONENTE
        $componente->update($data);

        // GUARDAR LOG
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

        // REDIRECCIÓN
        if ($request->input('porEquipo')) {
            return redirect()->route('componentes.porEquipo', $request->input('id_equipo'))
                ->with('success', 'Componente actualizado correctamente.');
        } else {
            return redirect()->route('componentes.index')
                ->with('success', 'Componente actualizado correctamente.');
        }
    }

    // Eliminación lógica
    public function destroy(Request $request, $id)
    {
        $componente = Componente::findOrFail($id);
        $componente->estadoElim = 'Inactivo';
        $componente->save();

        $usuario = Auth::check() ? Auth::user()->usuario : 'Sistema';
        try {
            LogModel::create([
                'usuario' => $usuario,
                'accion' => 'Eliminado el componente ID: ' . $componente->tipo_componente,
                'fecha' => now()
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error guardando log: ' . $e->getMessage());
        }

        if ($request->input('porEquipo')) {
            return redirect()->route('componentes.porEquipo', $request->input('id_equipo'))
                ->with('success', 'Componente eliminado correctamente.');
        } else {
            return redirect()->route('componentes.index')
                ->with('success', 'Componente eliminado correctamente.');
        }
    }


    // Lista componentes por equipo
    public function porEquipo($id_equipo)
    {
        // Solo componentes activos
        $componentes = Componente::where('id_equipo', $id_equipo)
            ->where('estadoElim', 'Activo')
            ->get();

        // Solo opcionales activos
        $opcionales = ComponenteOpcional::where('id_equipo', $id_equipo)
            ->where('estadoElim', 'Activo')
            ->get();

        return view('componentes.porEquipo', compact('componentes', 'opcionales', 'id_equipo'));
    }


    // -----------------------
    // PROCESAR DATOS
    // -----------------------
    private function procesarDatos(array $data)
    {

        // Campos de texto
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

        // Convertir cualquier array a string separando por comas
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $data[$key] = implode(',', $value);
            }
        }

        // Campos ENUM
        $camposEnum = ['estado', 'compatibilidad'];

        // Fecha
        $camposFecha = ['fecha_instalacion'];

        // Inicializar
        foreach ($camposTexto as $c) $data[$c] = $data[$c] ?? null;
        foreach ($camposNumericos as $c) $data[$c] = isset($data[$c]) && $data[$c] !== '' ? $data[$c] : null;
        foreach ($camposFecha as $c) $data[$c] = !empty($data[$c]) ? $data[$c] : null;
        foreach ($camposEnum as $c) $data[$c] = in_array($data[$c] ?? '', ['Operativo', 'Medio dañado', 'Dañado', 'Sí', 'Parcialmente', 'No']) ? $data[$c] : null;

        // Campos según tipo de componente
        switch ($data['tipo_componente'] ?? '') {
            case 'Tarjeta Madre':
                $data['marca'] = $data['marca_tarjeta_madre'] ?? $data['marca'];
                $data['modelo'] = $data['modelo_tarjeta_madre'] ?? $data['modelo'];
                $data['socket'] = $data['socket_tarjeta_madre'] ?? $data['socket'];
                $data['tipo'] = $data['tipo_tarjeta_madre'] ?? $data['tipo'];
                $data['cantidad_slot_memoria'] = $data['cantidad_slot_memoria'] ?? $data['cantidad_slot_memoria'];
                // Convertir array de checkboxes a string separado por comas
                if (!empty($data['ranuras_expansion'])) {
                    $data['ranuras_expansion'] = is_array($data['ranuras_expansion'])
                        ? implode(',', $data['ranuras_expansion'])
                        : $data['ranuras_expansion'];
                } else {
                    $data['ranuras_expansion'] = null;
                }

                // Puertos internos
                $puertosInternos = $data['puertos_internos'] ?? [];
                if (!is_array($puertosInternos)) {
                    $puertosInternos = [$puertosInternos]; // forzar array si llega string
                }
                if (!empty($data['puertos_internos_otros'])) {
                    $puertosInternos[] = $data['puertos_internos_otros'];
                }
                $data['puertos_internos'] = !empty($puertosInternos) ? implode(',', $puertosInternos) : null;

                // Puertos externos
                $puertosExternos = $data['puertos_externos'] ?? [];
                if (!is_array($puertosExternos)) {
                    $puertosExternos = [$puertosExternos];
                }
                if (!empty($data['puertos_externos_otros'])) {
                    $puertosExternos[] = $data['puertos_externos_otros'];
                }
                $data['puertos_externos'] = !empty($puertosExternos) ? implode(',', $puertosExternos) : null;

                // Conectores de alimentación
                $conectores = $data['conectores_alimentacion'] ?? [];
                if (!is_array($conectores)) {
                    $conectores = [$conectores];
                }
                if (!empty($data['conectores_alimentacion_otros'])) {
                    $conectores[] = $data['conectores_alimentacion_otros'];
                }

                // Para convertir frecuencias en string separadas por comas
                if (isset($data['frecuencias_memoria'])) {
                    if (is_array($data['frecuencias_memoria'])) {
                        $data['frecuencias_memoria'] = implode(',', $data['frecuencias_memoria']);
                    }
                }

                $data['memoria_maxima'] = $data['memoria_maxima'] ?? null;
                $data['conectores_alimentacion'] = !empty($conectores) ? implode(',', $conectores) : null;
                $data['bios_uefi'] = $data['bios_uefi'] ?? $data['bios_uefi'];
                $data['estado'] = $data['estado_tarjeta_madre'] ?? $data['estado'];
                $data['detalles'] = $data['detalles_tarjeta_madre'] ?? $data['detalles'] ?? null;
                break;

            case 'Memoria RAM':
                $data['marca'] = $data['marca_memoria'] ?? null;
                $data['tipo'] = $data['tipo_ram'] ?? null; // importante que no quede null

                // Normalizar slot
                $slot = $data['slot_memoria'] ?? null;
                if ($slot !== null) {
                    $slot = strtolower(trim($slot)); // convertir a minúsculas y quitar espacios al inicio/fin
                    $slot = str_replace([' ', '-', '_'], '', $slot); // quitar espacios, guiones y guiones bajos
                    if (preg_match('/\d+/', $slot, $matches)) {
                        $slot = 'Slot ' . $matches[0]; // formato estándar
                    } else {
                        $slot = null; // si no se encuentra número
                    }
                }
                $data['slot_memoria'] = $slot;

                $data['capacidad'] = $data['capacidad_ram'] ?? null;
                $data['frecuencia'] = $data['frecuencia_ram'] ?? null;
                $data['estado'] = $data['estado_memoria'] ?? 'Operativo';
                $data['detalles'] = $data['detalles_ram'] ?? null;
                break;

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

            case 'Fuente de Poder':
                $data['marca'] = $data['marca_fuente'] ?? $data['marca'];
                $data['modelo'] = $data['modelo_fuente'] ?? $data['modelo'];
                $data['potencia'] = $data['potencia'] ?? $data['potencia'];
                if (!empty($data['voltajes_fuente'])) {

                    // normalizar a array si viene como string
                    $voltajes = is_array($data['voltajes_fuente'])
                        ? $data['voltajes_fuente']
                        : explode(',', $data['voltajes_fuente']);

                    if (!empty($data['voltaje_otro'])) {
                        $voltajes[] = $data['voltaje_otro'];
                    }

                    $data['voltajes_fuente'] = implode(',', $voltajes);
                } else {
                    $data['voltajes_fuente'] = !empty($data['voltaje_otro']) ? $data['voltaje_otro'] : null;
                }

                $data['estado'] = $data['estado_fuente'] ?? $data['estado'];
                $data['detalles'] = $data['detalles_fuente'] ?? $data['detalles'] ?? null;
                break;

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
                if (isset($data['salidas_video']) && is_array($data['salidas_video'])) {
                    $data['salidas_video'] = implode(', ', $data['salidas_video']);
                }
                $data['estado'] = $data['estado_tarjeta_grafica'] ?? $data['estado'];
                $data['detalles'] = $data['detalles_tarjeta_grafica'] ?? $data['detalles'] ?? null;
                break;

            case 'Tarjeta Red':
                $data['marca'] = $data['marca_tarjeta_red'] ?? $data['marca'];
                $data['modelo'] = $data['modelo_tarjeta_red'] ?? $data['modelo'];
                if (!empty($data['tipo_tarjeta_red'])) {
                    if (is_array($data['tipo_tarjeta_red'])) {
                        $data['tipo'] = implode(', ', $data['tipo_tarjeta_red']);
                    } else {
                        $data['tipo'] = (string) $data['tipo_tarjeta_red'];
                    }
                } else {
                    $data['tipo'] = null;
                }
                $data['velocidad_transferencia'] = $data['velocidad_transferencia'] ?? $data['velocidad_transferencia'];
                $data['estado'] = $data['estado_tarjeta_red'] ?? $data['estado'];
                $data['detalles'] = $data['detalles_tarjeta_red'] ?? $data['detalles'] ?? null;
                break;

            case 'Unidad Optica':
                $data['marca'] = $data['marca_unidad'] ?? $data['marca'];
                $data['tipo'] = $data['tipo_unidad'] ?? $data['tipo'];
                if (isset($data['tipos_discos']) && is_array($data['tipos_discos'])) {
                    $data['tipos_discos'] = implode(',', $data['tipos_discos']);
                } else {
                    $data['tipos_discos'] = null;
                }
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

        if (($data['tipo_componente'] ?? '') !== 'Unidad Optica') {
            $data['tipos_discos'] = null;
        }

        if (($data['tipo_componente'] ?? '') !== 'Tarjeta Grafica') {
            $data['salidas_video'] = null;
        }

        // Estado activo por defecto
        if (empty($data['estadoElim'])) $data['estadoElim'] = 'Activo';

        return $data;
    }

    public function getComponentesUnicosPorEquipo($id_equipo)
    {
        try {
            $componentesUnicos = [
                'Tarjeta Madre',
                'Procesador',
                'Fuente de Poder',
                'Tarjeta Grafica',
                'Tarjeta Red',
                'Tarjeta de Sonido Integrada'
            ];

            $existentes = Componente::where('id_equipo', $id_equipo)
                ->whereIn('tipo_componente', $componentesUnicos)
                ->activos() // coméntalo si no tienes el scope
                ->pluck('tipo_componente');

            return response()->json($existentes);
        } catch (\Throwable $e) {
            // Devuelve el error en JSON para que tu JS lo pueda manejar
            return response()->json([
                'error' => 'Error al obtener componentes: ' . $e->getMessage()
            ], 500);
        }
    }

    // Formulario de creación para "porEquipo" con componentes únicos
    public function createPorEquipo($id_equipo)
    {
        $equipoSeleccionado = Equipo::findOrFail($id_equipo); // Encuentra el equipo
        $equipos = Equipo::all(); // Para mostrar en el select si se quiere

        // Componentes únicos
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

        $porEquipo = true;

        return view('componentes.create', compact(
            'porEquipo',
            'equipoSeleccionado',
            'equipos',
            'componentesUnicos',
            'componentesExistentes'
        ))->with('id_equipo', $id_equipo); // <-- Pasamos el ID del equipo explícitamente
    }

    public function editPorEquipo($id)
    {
        $componente = Componente::findOrFail($id);
        $porEquipo = true;
        $id_equipo = $componente->id_equipo; // <-- Pasamos el ID real del equipo

        return $this->edit($id, $porEquipo)->with([
            'id_equipo' => $id_equipo,
            'equipoSeleccionado' => $componente->equipo ?? null
        ]);
    }

    private function obtenerSlotsLibres($id_equipo)
    {
        $tarjetaMadre = Componente::where('id_equipo', $id_equipo)
            ->where('tipo_componente', 'Tarjeta Madre')
            ->first();

        if (!$tarjetaMadre) return [];

        $cantidadSlots = (int) $tarjetaMadre->cantidad_slot_memoria;

        // Todos los slots disponibles
        $todosLosSlots = [];
        for ($i = 1; $i <= $cantidadSlots; $i++) {
            $todosLosSlots[] = "Slot $i";
        }

        // Slots ocupados por RAM principal activa
        $slotsOcupadosComponente = Componente::where('id_equipo', $id_equipo)
            ->where('tipo_componente', 'Memoria RAM')
            ->where('estadoElim', 'Activo') // <- solo activas
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

        // Unir todos los ocupados y eliminar duplicados
        $slotsOcupados = array_unique(array_merge($slotsOcupadosComponente, $slotsOcupadosOpcionales));

        // Devolver solo los libres
        return array_values(array_diff($todosLosSlots, $slotsOcupados));
    }

    public function buscarPalabras(Request $request)
    {
        $term = $request->input('term');
        if (!$term) return response()->json([]);

        $resultados = Componente::activos()->with('equipo')
            ->where(function ($q) use ($term) {
                $q->where('tipo_componente', 'LIKE', "%$term%")
                    ->orWhere('marca', 'LIKE', "%$term%")
                    ->orWhere('modelo', 'LIKE', "%$term%")
                    ->orWhere('estado', 'LIKE', "%$term%")
                    ->orWhere('capacidad', 'LIKE', "%$term%")
                    ->orWhereHas('equipo', function ($qe) use ($term) {
                        $qe->where('marca', 'LIKE', "%$term%")
                            ->orWhere('modelo', 'LIKE', "%$term%");
                    });
            })
            ->get();

        $palabras = [];
        foreach ($resultados as $c) {
            $columnas = ['tipo_componente', 'marca', 'modelo', 'estado', 'capacidad'];
            foreach ($columnas as $col) {
                if ($c->$col && stripos($c->$col, $term) !== false) $palabras[] = $c->$col;
            }
            if ($c->equipo) {
                if ($c->equipo->marca && stripos($c->equipo->marca, $term) !== false)
                    $palabras[] = $c->equipo->marca;
                if ($c->equipo->modelo && stripos($c->equipo->modelo, $term) !== false)
                    $palabras[] = $c->equipo->modelo;
            }
        }

        $palabras = array_values(array_unique($palabras));
        return response()->json($palabras);
    }
}