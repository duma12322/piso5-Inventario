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
        // Traer solo componentes activos
        $componentes = $this->componentes()->where('estadoElim', 'Activo')->get();
        $anioActual = Carbon::now()->year;

        if ($componentes->isEmpty()) {
            $this->estado_tecnologico = 'Nuevo';
            $this->save();
            return ['estado' => 'Nuevo'];
        }

        $puntajeTotal = 0;
        $pesoTotal = 0;

        // Obtener la fecha de instalación de la tarjeta madre si existe
        $tarjetaMadre = $componentes->firstWhere('tipo_componente', 'Tarjeta Madre');
        $anioInstalacionEquipo = $tarjetaMadre && !empty($tarjetaMadre->fecha_instalacion)
            ? (int) Carbon::parse($tarjetaMadre->fecha_instalacion)->year
            : null;

        foreach ($componentes as $componente) {
            // Determinar la tabla de tecnología
            $tipoTabla = strtolower($componente->tipo_componente) === 'procesador'
                ? 'Socket CPU'
                : $componente->tipo_componente;

            // Valor a buscar en la tabla de tecnología
            $valorBuscar = $componente->socket ?: $componente->tipo ?: null;
            $valorBuscarNormalizado = $valorBuscar ? strtolower(str_replace(' ', '', $valorBuscar)) : null;

            // Obtener componente en la tabla de tecnología
            $compTecnologia = DB::table('componentes_tecnologia')
                ->where('tipo_componente', $tipoTabla)
                ->where(function ($q) use ($valorBuscarNormalizado) {
                    if ($valorBuscarNormalizado) {
                        $q->whereRaw('REPLACE(LOWER(tipo), " ", "") LIKE ?', ["%$valorBuscarNormalizado%"]);
                    }
                    $q->orWhereNull('tipo');
                })
                ->first();

            $vidaUtil = $compTecnologia->vida_util_anios ?? ($tipoTabla === 'Socket CPU' ? 10 : 8);
            $peso = $compTecnologia->peso_importancia ?? 1;
            $anioLanzamiento = $compTecnologia->anio_lanzamiento ?? $anioActual;

            // Validar fecha de instalación: ignorar años imposibles
            if ($tipoTabla === 'Tarjeta Madre' && $anioInstalacionEquipo) {
                // Si el año de instalación es menor que el año de lanzamiento o mayor que el actual, usar el lanzamiento
                if ($anioInstalacionEquipo < $anioLanzamiento || $anioInstalacionEquipo > $anioActual) {
                    $anioReferencia = $anioLanzamiento;
                } else {
                    $anioReferencia = $anioInstalacionEquipo;
                }
            } else {
                $anioReferencia = $anioLanzamiento;
            }

            // Edad del componente
            $edad = max(0, $anioActual - $anioReferencia);

            // Puntaje ponderado del componente
            $puntajeComponente = max(0, 1 - ($edad / $vidaUtil)) * $peso;

            // Sumar totales
            $puntajeTotal += $puntajeComponente;
            $pesoTotal += $peso;
        }

        // Calcular ratio ponderado
        $ratio = $pesoTotal ? $puntajeTotal / $pesoTotal : 1;

        // Determinar estado final
        if ($ratio >= 0.75) {
            $estado = 'Nuevo';
        } elseif ($ratio >= 0.4) {
            $estado = 'Actualizable';
        } else {
            $estado = 'Obsoleto';
        }

        // Guardar estado en el equipo
        $this->estado_tecnologico = $estado;
        $this->save();

        return ['estado' => $estado];
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
