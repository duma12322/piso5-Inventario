<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Division extends Model
{
    protected $table = 'divisiones';
    protected $primaryKey = 'id_division';
    public $timestamps = false;

    protected $fillable = [
        'id_direccion',
        'nombre_division',
        'estado'
    ];

    // 🔹 Scope para solo activas
    public function scopeActivas($query)
    {
        return $query->where('estado', 'Activo');
    }

    // Relación con Direccion
    public function direccion()
    {
        return $this->belongsTo(Direccion::class, 'id_direccion', 'id_direccion');
    }

    // Obtener todas las divisiones activas
    public static function obtenerTodas()
    {
        return self::activas()->with('direccion')->get();
    }

    // Obtener división por ID (solo activa)
    public static function obtenerPorId($id)
    {
        return self::activas()->find($id);
    }

    // 🔹 Borrado lógico
    public static function eliminar($id)
    {
        $division = self::find($id);
        if ($division) {
            $division->estado = 'Inactivo';
            $division->save();
        }
        return $division;
    }
}
