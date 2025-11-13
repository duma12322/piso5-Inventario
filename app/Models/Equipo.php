<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;


class Equipo extends Model
{
    use HasFactory;

    protected $table = 'equipos';
    protected $primaryKey = 'id_equipo';
    public $timestamps = false; // Si no tienes columnas created_at y updated_at

    protected $fillable = [
        'marca',
        'modelo',
        'serial',
        'numero_bien',
        'tipo_gabinete',
        'id_direccion',
        'id_division',
        'id_coordinacion',
        'estado_funcional',
        'estado_tecnologico',
        'estado_gabinete',
        'estado',
    ];



    // Scope para solo activos
    public function scopeActivos($query)
    {
        return $query->where('estado', 'Activo');
    }
    /*
    |--------------------------------------------------------------------------
    | RELACIONES
    |--------------------------------------------------------------------------
    */

    public function direccion()
    {
        return $this->belongsTo(Direccion::class, 'id_direccion', 'id_direccion');
    }

    public function division()
    {
        return $this->belongsTo(Division::class, 'id_division', 'id_division');
    }

    public function coordinacion()
    {
        return $this->belongsTo(Coordinacion::class, 'id_coordinacion', 'id_coordinacion');
    }

    public function componentes()
    {
        return $this->hasMany(Componente::class, 'id_equipo', 'id_equipo');
    }

    public function softwareItems()
    {
        return $this->hasMany(Software::class, 'id_equipo', 'id_equipo');
    }

    /*
    |--------------------------------------------------------------------------
    | MÉTODOS PERSONALIZADOS
    |--------------------------------------------------------------------------
    */

    /**
     * Calcula el estado tecnológico del equipo según el año de la tarjeta madre
     */
    public function calcularEstadoPorAnio($anio)
    {
        if (empty($anio) || !is_numeric($anio)) {
            return 'Desconocido';
        }

        $anioActual = Carbon::now()->year;
        $diferencia = $anioActual - (int) $anio;

        if ($diferencia < 2) return 'Nuevo';
        if ($diferencia <= 5) return 'Actualizable';
        return 'Obsoleto';
    }

    /**
     * Actualiza el estado tecnológico del equipo en base al componente "tarjeta madre"
     */
    public function calcularEstadoTecnologico()
    {
        $componentes = $this->componentes()->where('estadoElim', 'Activo')->get();
        $anioActual = Carbon::now()->year;

        if ($componentes->isEmpty()) {
            $this->estado_tecnologico = 'Nuevo';
            $this->save();
            return ['estado' => 'Nuevo', 'detalle' => 'No hay componentes activos'];
        }

        $puntajeTotal = 0;
        $pesoTotal = 0;
        $explicacion = '';

        foreach ($componentes as $componente) {
            $tipoComp = strtolower($componente->tipo_componente);

            if (!in_array($tipoComp, ['tarjeta madre', 'memoria ram'])) continue;

            if ($tipoComp === 'tarjeta madre') {
                $compTecnologia = DB::table('componentes_tecnologia')
                    ->where('tipo_componente', 'Socket CPU')
                    ->where('tipo', $componente->socket)
                    ->first();

                $vidaUtil = $compTecnologia->vida_util_anios ?? 10;
                $peso = $compTecnologia->peso_importancia ?? 4;
                $socket = $componente->socket ?? 'N/A';
                $anioLanzamiento = $compTecnologia->anio_lanzamiento ?? $anioActual;
                $anioInstalacion = $componente->fecha_instalacion
                    ? (int) $componente->fecha_instalacion
                    : $anioActual;
            } else { // Memoria RAM
                $compTecnologia = DB::table('componentes_tecnologia')
                    ->where('tipo_componente', 'Memoria RAM')
                    ->where('tipo', 'LIKE', "%{$componente->tipo}%")
                    ->first();

                $vidaUtil = $compTecnologia->vida_util_anios ?? 8;
                $peso = $compTecnologia->peso_importancia ?? 2;
                $socket = $componente->tipo ?? 'N/A';
                $anioLanzamiento = $compTecnologia->anio_lanzamiento ?? $anioActual;
                $anioInstalacion = $anioLanzamiento; // usamos solo año de lanzamiento
            }

            $edad = max(0, $anioActual - $anioInstalacion);
            $puntajeComponente = max(0, 1 - ($edad / $vidaUtil)) * $peso;

            $puntajeTotal += $puntajeComponente;
            $pesoTotal += $peso;

            $explicacion .= "- {$componente->tipo_componente} ({$componente->marca} {$componente->modelo}, Socket/Tipo: {$socket}): instalada en {$anioInstalacion}, tecnología lanzada en {$anioLanzamiento}, edad considerada {$edad} años, vida útil {$vidaUtil}, peso {$peso}<br>";
        }

        $ratio = $pesoTotal ? $puntajeTotal / $pesoTotal : 1;

        if ($ratio >= 0.75) {
            $estado = 'Nuevo';
        } elseif ($ratio >= 0.4) {
            $estado = 'Actualizable';
        } else {
            $estado = 'Obsoleto';
        }

        $this->estado_tecnologico = $estado;
        $this->save();

        return [
            'estado' => $estado,
            'detalle' => $explicacion
        ];
    }

    /**
     * Trae todos los equipos con su software y relaciones cargadas
     */
    public static function obtenerEquiposConRelaciones()
    {
        $equipos = self::with(['direccion', 'division', 'coordinacion', 'componentes'])->get();

        foreach ($equipos as $equipo) {
            $equipo->actualizarEstadoTecnologico();
        }

        return $equipos;
    }

    /**
     * Elimina el equipo y su software asociado
     */
    public function eliminarConSoftware()
    {
        $this->softwareItems()->delete();
        $this->delete();
    }

    public function eliminarConTodo()
    {
        // Eliminar software
        $this->softwareItems()->delete();

        // Eliminar componentes y sus opcionales
        foreach ($this->componentes as $componente) {
            $componente->componentesOpcionales()->delete(); // si tienes relación definida
            $componente->delete();
        }

        // Finalmente eliminar el equipo
        $this->delete();
    }
}
