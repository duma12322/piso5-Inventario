<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Usuario extends Authenticatable
{
    protected $table = 'usuarios'; // tu tabla actual
    protected $primaryKey = 'id_usuario';
    public $timestamps = false; // si tu tabla no tiene created_at / updated_at

    protected $fillable = [
        'usuario',
        'password',
        'rol'
    ];

    protected $hidden = [
        'password',
    ];

    /**
     * Sobrescribir el método de autenticación para usar MD5 si tu DB aún lo requiere.
     */
    public function setPasswordAttribute($value)
    {
        // Guardar siempre el password en MD5 (solo si tu DB lo requiere)
        $this->attributes['password'] = md5($value);
    }

    /**
     * Obtener todos los usuarios
     */
    public static function getAllUsuarios()
    {
        return self::all();
    }

    /**
     * Obtener usuario por ID
     */
    public static function getUsuario($id)
    {
        return self::findOrFail($id);
    }

    /**
     * Crear un usuario
     */
    public static function crearUsuario($data)
    {
        return self::create([
            'usuario' => $data['usuario'],
            'password' => $data['password'], // será MD5 gracias a setPasswordAttribute
            'rol' => $data['rol'],
        ]);
    }

    /**
     * Actualizar usuario
     */
    public function actualizarUsuario($data)
    {
        $this->usuario = $data['usuario'];
        if (isset($data['password']) && $data['password'] !== '') {
            $this->password = $data['password']; // será MD5
        }
        $this->rol = $data['rol'];
        $this->save();
    }

    /**
     * Eliminar usuario
     */
    public function eliminarUsuario()
    {
        $this->delete();
    }
}
