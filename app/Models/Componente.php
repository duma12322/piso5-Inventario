<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Componente extends Model
{
    use HasFactory;

    protected $table = 'componentes';
    protected $primaryKey = 'id_componente';
    public $timestamps = false;

    // Campos que se pueden asignar en masa

    protected $fillable = [
        'id_equipo',
        'tipo_componente',
        'marca',
        'modelo',
        'arquitectura',
        'tipo',
        'frecuencia',
        'velocidad',
        'capacidad',
        'estado',
        'ubicacion',
        'consumo',
        'rgb_led',
        'fecha_instalacion',
        'ranuras_expansion',
        'puertos_internos',
        'puertos_externos',
        'conectores_alimentacion',
        'bios_uefi',
        'potencia',
        'voltajes_fuente',
        'nucleos',
        'socket',
        'soporte_memoria',
        'tipo_conector',
        'conectividad_soporte',
        'salidas_video',
        'soporte_apis',
        'fabricante_controlador',
        'modelo_red',
        'velocidad_transferencia',
        'tipo_conector_fisico',
        'mac_address',
        'drivers_sistema',
        'compatibilidad_sistema',
        'tipos_discos',
        'interfaz_conexion',
        'tipo_cooler',
        'consumo_electrico',
        'estadoElim',
        'detalles',
        'cantidad_slot_memoria',
        'slot_memoria',
        'frecuencias_memoria',
        'memoria_maxima'
    ];

    public function scopeActivos($query)
    {
        return $query->where('estadoElim', 'Activo');
    }

    // Relación: un componente pertenece a un equipo
    public function equipo()
    {
        return $this->belongsTo(Equipo::class, 'id_equipo', 'id_equipo');
    }

    public function componentesOpcionales()
    {
        return $this->hasMany(ComponenteOpcional::class, 'id_opcional', 'id_componente');
    }


    // 🧮 Cálculo del estado tecnológico
    public function getEstadoTecnologicoAttribute()
    {
        if (!$this->fecha_instalacion) {
            return 'Desconocido';
        }

        try {
            $anioInstalacion = Carbon::parse($this->fecha_instalacion)->year;
            $anioActual = Carbon::now()->year;
            $diferencia = $anioActual - $anioInstalacion;

            if ($diferencia < 2) return 'Nuevo';
            if ($diferencia <= 5) return 'Actualizable';
            return 'Obsoleto';
        } catch (\Exception $e) {
            return 'Desconocido';
        }
    }

    // 🔍 Obtener todos los componentes con su equipo (como hacía getAll)
    public static function obtenerConEquipos()
    {
        $componentes = self::with('equipo')->get();

        foreach ($componentes as $comp) {
            if (strtolower($comp->tipo_componente) !== 'tarjeta madre') {
                // Solo sobreescribimos si no es tarjeta madre
                $comp->estado_tecnologico = $comp->estado;
            }
            // Si es tarjeta madre, Laravel ya calcula el atributo "estado_tecnologico"
        }

        return $componentes;
    }

    // Devuelve los tipos de componentes activos de un equipo específico
    public static function tiposExistentes($id_equipo)
    {
        return self::where('id_equipo', $id_equipo)
            ->where('estadoElim', 'Activo')
            ->pluck('tipo_componente')
            ->toArray();
    }
}
