<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Software extends Model
{
    use HasFactory;

    protected $table = 'software';
    protected $primaryKey = 'id_software';
    public $timestamps = false;

    protected $fillable = [
        'id_equipo',
        'tipo',
        'nombre',
        'version',
        'bits'
    ];

    public function equipo()
    {
        return $this->belongsTo(Equipo::class, 'id_equipo', 'id_equipo');
    }
}
