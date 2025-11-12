<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ComponenteOpcional extends Model
{
    protected $table = 'componentes_opcionales';
    protected $primaryKey = 'id_opcional';
    public $timestamps = false; // Desactivar si la tabla no tiene created_at / updated_at

    // Campos que se pueden llenar masivamente
    protected $fillable = [
        'id_equipo',
        'tipo_opcional',
        'marca',
        'modelo',
        'capacidad',
        'frecuencia',
        'tipo',
        'consumo',
        'ubicacion',
        'salidas_video',
        'salidas_audio',
        'vrm',
        'drivers',
        'compatibilidad',
        'velocidad',
        'seguridad',
        'bluetooth',
        'protocolos',
        'canales',
        'resolucion_audio',
        'estado',
        'detalles',
        'estadoElim',
        'slot_memoria'
    ];

    public function scopeActivos($query)
    {
        return $query->where('estadoElim', 'Activo');
    }

    /**
     * Relación: un componente opcional pertenece a un equipo
     */
    public function equipo()
    {
        return $this->belongsTo(Equipo::class, 'id_equipo', 'id_equipo');
    }
    /**
     * Obtener todos los componentes con su equipo
     */
    public static function obtenerTodos()
    {
        return self::with('equipo')->get();
    }

    /**
     * Obtener un componente opcional por su ID
     */
    public static function obtenerPorId($id)
    {
        return self::with('equipo')->find($id);
    }

    /**
     *Obtener todos los componentes opcionales por equipo
     */
    public static function obtenerPorEquipo($id_equipo)
    {
        return self::where('id_equipo', $id_equipo)->get();
    }
}
