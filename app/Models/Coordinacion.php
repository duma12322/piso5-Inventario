<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coordinacion extends Model
{
    use HasFactory;

    // Nombre de la tabla
    protected $table = 'coordinaciones';

    // Nombre de la clave primaria
    protected $primaryKey = 'id_coordinacion';

    // Campos que se pueden asignar masivamente
    protected $fillable = [
        'id_division',
        'nombre_coordinacion',
        'estado'
    ];

    // Desactivar timestamps si tu tabla no tiene created_at / updated_at
    public $timestamps = false;

    // 🔹 Scope para solo activos
    public function scopeActivos($query)
    {
        return $query->where('estado', 'Activo');
    }

    /**
     * Relación: una coordinación pertenece a una división.
     */
    public function division()
    {
        return $this->belongsTo(Division::class, 'id_division', 'id_division');
    }

    /**
     * Obtener todas las coordinaciones con su división (equivalente a getAll()).
     */
    public static function obtenerTodas()
    {
        return self::with('division')->get();
    }

    /**
     * Obtener coordinación por ID (equivalente a get()).
     */
    public static function obtenerPorId($id)
    {
        return self::findOrFail($id);
    }

    /**
     * Obtener coordinaciones por división (equivalente a getByDivision()).
     */
    public static function obtenerPorDivision($id_division)
    {
        return self::where('id_division', $id_division)->get();
    }

    /**
     * Agregar una nueva coordinación (equivalente a add()).
     */
    public static function agregar($data)
    {
        return self::create([
            'id_division' => $data['id_division'],
            'nombre_coordinacion' => $data['nombre_coordinacion'],
        ]);
    }

    /**
     * Actualizar una coordinación (equivalente a update()).
     */
    public function actualizarDatos($data)
    {
        $this->update([
            'id_division' => $data['id_division'],
            'nombre_coordinacion' => $data['nombre_coordinacion'],
        ]);
    }

    /**
     * Eliminar una coordinación (equivalente a delete()).
     */
    public function eliminar()
    {
        $this->delete();
    }
}
