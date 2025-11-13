<?php

namespace App\Http\Controllers;

use App\Models\Equipo;
use App\Models\Componente;
use App\Models\ComponenteOpcional;
use TCPDF;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class EquipoPdfController extends Controller
{
    public function generarPDF($id)
    {
        $equipo = Equipo::with(['direccion', 'division', 'coordinacion'])->findOrFail($id);
        $anioActual = Carbon::now()->year;

        // Componentes activos
        $componentes = Componente::where('id_equipo', $id)
            ->where('estadoElim', 'Activo')
            ->get();

        $componentesOpcionales = ComponenteOpcional::where('id_equipo', $id)
            ->where('estadoElim', 'Activo')
            ->get();

        // Filtrar componentes tecnológicos
        $componentesTecnologicos = $componentes->filter(
            fn($c) => in_array(strtolower($c->tipo_componente), ['tarjeta madre', 'procesador', 'memoria ram'])
        );

        // Generar explicación detallada y calcular estado tecnológico
        $explicacion = '';
        $puntajeTotal = 0;
        $pesoTotal = 0;

        foreach ($componentesTecnologicos as $componente) {
            $tipoComp = strtolower($componente->tipo_componente);

            if ($tipoComp === 'tarjeta madre') {
                $compTecnologia = DB::table('componentes_tecnologia')
                    ->where('tipo_componente', 'Socket CPU')
                    ->where('tipo', $componente->socket)
                    ->first();

                $anioInstalacion = $componente->fecha_instalacion
                    ? (int) $componente->fecha_instalacion
                    : $anioActual;

                $anioLanzamiento = $compTecnologia->anio_lanzamiento ?? $anioActual;
                $vidaUtil = $compTecnologia->vida_util_anios ?? 10;
                $peso = $compTecnologia->peso_importancia ?? 4;
                $socket = $componente->socket ?? 'N/A';
                $edad = max(0, $anioActual - $anioInstalacion);

                $explicacion .= "- {$componente->tipo_componente} ({$componente->marca} {$componente->modelo}): instalada en {$anioInstalacion}, edad considerada {$edad} años<br>";
                $explicacion .= "- Socket: {$socket}: tecnología lanzada en {$anioLanzamiento}, vida útil {$vidaUtil}.<br>";

                $puntajeComponente = max(0, 1 - ($edad / $vidaUtil)) * $peso;
            } elseif ($tipoComp === 'memoria ram') {
                $compTecnologia = DB::table('componentes_tecnologia')
                    ->where('tipo_componente', 'Memoria RAM')
                    ->where('tipo', 'LIKE', "%{$componente->tipo}%")
                    ->first();

                $anioLanzamiento = $compTecnologia->anio_lanzamiento ?? $anioActual;
                $vidaUtil = $compTecnologia->vida_util_anios ?? 8;
                $peso = $compTecnologia->peso_importancia ?? 2;
                $edad = max(0, $anioActual - $anioLanzamiento);

                $explicacion .= "- {$componente->tipo_componente} ({$componente->marca}, Tipo: {$componente->tipo}): tecnología lanzada en {$anioLanzamiento}, vida útil {$vidaUtil}.<br>";

                $puntajeComponente = max(0, 1 - ($edad / $vidaUtil)) * $peso;
            } else { // Procesador u otros
                $puntajeComponente = 0;
                $peso = 0;
            }

            $puntajeTotal += $puntajeComponente;
            $pesoTotal += $peso;
        }

        // Estado tecnológico
        $ratio = $pesoTotal ? $puntajeTotal / $pesoTotal : 1;
        if ($ratio >= 0.75) {
            $estadoTecnologico = 'Nuevo';
        } elseif ($ratio >= 0.4) {
            $estadoTecnologico = 'Actualizable';
        } else {
            $estadoTecnologico = 'Obsoleto';
        }

        $equipo->estado_tecnologico = $estadoTecnologico;
        $equipo->save();

        // Generar PDF horizontal
        $pdf = new TCPDF('L', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetCreator('Laravel TCPDF');
        $pdf->SetAuthor('Sistema de Inventario');
        $pdf->SetTitle('Ficha del Equipo');
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->AddPage();

        $direccion = $equipo->direccion->nombre_direccion ?? 'N/A';
        $division = $equipo->division->nombre_division ?? 'N/A';
        $coordinacion = $equipo->coordinacion->nombre_coordinacion ?? 'N/A';

        $html = '
        <h2 style="text-align:center;">Ficha del Equipo</h2>
        <br>
        <strong>Marca:</strong> ' . e($equipo->marca ?? 'N/A') . '<br>
        <strong>Modelo:</strong> ' . e($equipo->modelo ?? 'N/A') . '<br>
        <strong>Dirección:</strong> ' . e($direccion) . '<br>
        <strong>División:</strong> ' . e($division) . '<br>
        <strong>Coordinación:</strong> ' . e($coordinacion) . '<br>
        <strong>Estado Funcional:</strong> ' . e($equipo->estado_funcional ?? 'N/A') . '<br>
        <strong>Estado Gabinete:</strong> ' . e($equipo->estado_gabinete ?? 'N/A') . '<br>
        <strong>Estado Tecnológico:</strong> ' . e($estadoTecnologico) . '<br>
        <em>Detalles: <br>' . $explicacion . '</em><br><br>
        ';

        // Tabla de TODOS los componentes activos
        $html .= '<h4>Componentes</h4>';
        if ($componentes->isNotEmpty()) {
            $html .= '<table border="1" cellpadding="6">
        <thead>
            <tr style="background-color:#f2f2f2;">
                <th>Tipo</th>
                <th>Marca</th>
                <th>Modelo</th>
                <th>Capacidad/Frecuencia</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>';
            foreach ($componentes as $comp) {
                $capacidadFrecuencia = !empty($comp->capacidad)
                    ? $comp->capacidad
                    : (!empty($comp->frecuencia) ? $comp->frecuencia : 'N/A');

                $html .= '<tr>
            <td>' . e($comp->tipo_componente ?: 'N/A') . '</td>
            <td>' . e($comp->marca ?: 'N/A') . '</td>
            <td>' . e($comp->modelo ?: 'N/A') . '</td>
            <td>' . e($capacidadFrecuencia) . '</td>
            <td>' . e($comp->estado ?: 'Desconocido') . '</td>
        </tr>';
            }
            $html .= '</tbody></table>';
        } else {
            $html .= '<p>No hay componentes activos registrados.</p>';
        }

        // Espacio entre tablas
        $html .= '<div style="height:15px;"></div>';

        // Componentes opcionales en tabla
        $html .= '<h4>Componentes Opcionales</h4>';
        if ($componentesOpcionales->isNotEmpty()) {
            $html .= '<table border="1" cellpadding="6">
        <thead>
            <tr style="background-color:#f2f2f2;">
                <th>Tipo</th>
                <th>Marca</th>
                <th>Modelo</th>
                <th>Capacidad/Velocidad</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>';
            foreach ($componentesOpcionales as $opcional) {
                $capacidadVelocidad = !empty($opcional->capacidad)
                    ? $opcional->capacidad
                    : (!empty($opcional->velocidad) ? $opcional->velocidad : 'N/A');

                $html .= '<tr>
            <td>' . e($opcional->tipo_opcional ?: 'N/A') . '</td>
            <td>' . e($opcional->marca ?: 'N/A') . '</td>
            <td>' . e($opcional->modelo ?: 'N/A') . '</td>
            <td>' . e($capacidadVelocidad) . '</td>
            <td>' . e($opcional->estado ?: 'N/A') . '</td>
        </tr>';
            }
            $html .= '</tbody></table>';
        } else {
            $html .= '<p>No hay componentes opcionales activos registrados.</p>';
        }

        $pdf->writeHTML($html, true, false, true, false, '');
        $pdf->Output('ficha_equipo_' . ($equipo->nombre ?? 'equipo') . '.pdf', 'I');
    }
}
