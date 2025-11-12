<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Direccion extends Model
{
    protected $table = 'direcciones';
    protected $primaryKey = 'id_direccion';
    public $timestamps = false;

    protected $fillable = [
        'nombre_direccion',
        'estado'
    ];

    // 🔹 Scope para solo activos
    public function scopeActivos($query)
    {
        return $query->where('estado', 'Activo');
    }

    // Obtener todas las direcciones activas
    public static function getAll()
    {
        return self::activos()->get();
    }

    public static function getById($id)
    {
        return self::activos()->find($id);
    }

    public static function addDireccion($data)
    {
        return self::create([
            'nombre_direccion' => $data['nombre_direccion'],
            'estado' => 'Activo'
        ]);
    }

    public static function updateDireccion($id, $data)
    {
        $direccion = self::find($id);
        if ($direccion) {
            $direccion->update([
                'nombre_direccion' => $data['nombre_direccion']
            ]);
        }
        return $direccion;
    }

    // 🔹 Borrado lógico
    public static function deleteDireccion($id)
    {
        $direccion = self::find($id);
        if ($direccion) {
            $direccion->estado = 'Inactivo';
            $direccion->save();
        }
        return $direccion;
    }
}
