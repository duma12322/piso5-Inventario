<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    protected $table = 'logs';
    public $timestamps = false; // si tu tabla no tiene created_at / updated_at

    protected $fillable = [
        'usuario',  // FK al usuario
        'accion',
        'fecha',
    ];

    // Relación con el usuario
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }
}
