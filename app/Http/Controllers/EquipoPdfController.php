<?php

namespace App\Http\Controllers;

use App\Models\Equipo;
use App\Models\Componente;
use App\Models\ComponenteOpcional;
use TCPDF;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class EquipoPdfController extends Controller
{
    /**
     * Genera un PDF detallado de un equipo específico.
     *
     * @param int $id ID del equipo a generar
     * @return void (descarga del PDF directamente)
     */
    public function generarPDF($id)
    {
        // Traer el equipo junto con sus relaciones
        $equipo = Equipo::with(['direccion', 'division', 'coordinacion'])->findOrFail($id);
        $anioActual = Carbon::now()->year;

        // Componentes activos
        $componentes = Componente::where('id_equipo', $id)
            ->where('estadoElim', 'Activo')
            ->get();

        // Obtener componentes opcionales activos
        $componentesOpcionales = ComponenteOpcional::where('id_equipo', $id)
            ->where('estadoElim', 'Activo')
            ->get();

        // Filtrar componentes tecnológicos para evaluación de estado
        $componentesTecnologicos = $componentes->filter(
            fn($c) => in_array(strtolower($c->tipo_componente), ['tarjeta madre', 'procesador', 'memoria ram'])
        );

        // Inicializar variables para explicación y cálculo de estado tecnológico
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
                // Info tecnológica para memoria RAM
                $compTecnologia = DB::table('componentes_tecnologia')
                    ->where('tipo_componente', 'Memoria RAM')
                    ->where('tipo', 'LIKE', "%{$componente->tipo}%")
                    ->first();

                $anioLanzamiento = $compTecnologia->anio_lanzamiento ?? $anioActual;
                $vidaUtil = $compTecnologia->vida_util_anios ?? 8;
                $peso = $compTecnologia->peso_importancia ?? 2;
                $edad = max(0, $anioActual - $anioLanzamiento);

                // Explicación detallada
                $explicacion .= "- {$componente->tipo_componente} ({$componente->marca}, Tipo: {$componente->tipo}): tecnología lanzada en {$anioLanzamiento}, vida útil {$vidaUtil}.<br>";

                $puntajeComponente = max(0, 1 - ($edad / $vidaUtil)) * $peso;
            } else {
                // Otros componentes (procesador u otros) sin puntaje
                $puntajeComponente = 0;
                $peso = 0;
            }

            $puntajeTotal += $puntajeComponente;
            $pesoTotal += $peso;
        }

        // Calcular estado tecnológico general del equipo
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

        // Configuración inicial de TCPDF para PDF horizontal
        $pdf = new TCPDF('L', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetCreator('Laravel TCPDF');
        $pdf->SetAuthor('Sistema de Inventario');
        $pdf->SetTitle('Ficha del Equipo');
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->AddPage();

        // Insertar imagen de encabezado como marca de agua
        $imagePath = public_path('encabezado.jpeg');
        if (file_exists($imagePath)) {
            $pdf->SetAlpha(0.3); // Opacidad baja para marca de agua
            $pdf->Image($imagePath, 0, 0, 297, 27, 'JPEG', '', 'T', false, 300, '', false, false, 0, false, false, false);
            $pdf->SetAlpha(1);   // Restaurar opacidad
            $pdf->Ln(30); // Espacio para bajar el contenido
        }

        $fechaHora = date('d/m/Y h:i A');

        // Obtener nombres de relaciones
        $direccion = $equipo->direccion->nombre_direccion ?? 'N/A';
        $division = $equipo->division->nombre_division ?? 'N/A';
        $coordinacion = $equipo->coordinacion->nombre_coordinacion ?? 'N/A';

        // HTML principal del PDF
        $html = '
        <div style="background-color: #b91d47; color: white; line-height: 25px; font-size: 14px; font-weight: bold; text-align: center;">
            FICHA DEL EQUIPO
        </div>
        <div style="text-align: right; font-size: 9px; color: #444; margin-top: 2px;">Generado el: ' . $fechaHora . '</div>
        <br><br>
        <table border="0" cellpadding="4">
            <tr>
                <td width="50%"><strong>Número de Bien:</strong> ' . e($equipo->numero_bien ?? 'N/A') . '</td>
                <td width="50%"><strong>Marca:</strong> ' . e($equipo->marca ?? 'N/A') . '</td>
            </tr>
            <tr>
                <td width="50%"><strong>Modelo:</strong> ' . e($equipo->modelo ?? 'N/A') . '</td>
                <td width="50%"><strong>Dirección:</strong> ' . e($direccion) . '</td>
            </tr>
            <tr>
                <td width="50%"><strong>División:</strong> ' . e($division) . '</td>
                <td width="50%"><strong>Coordinación:</strong> ' . e($coordinacion) . '</td>
            </tr>
            <tr>
                <td width="50%"><strong>Estado Funcional:</strong> ' . e($equipo->estado_funcional ?? 'N/A') . '</td>
                <td width="50%"><strong>Estado Gabinete:</strong> ' . e($equipo->estado_gabinete ?? 'N/A') . '</td>
            </tr>
            <tr>
                <td width="50%"><strong>Estado Tecnológico:</strong> ' . e($estadoTecnologico) . '</td>
                <td width="50%"></td>
            </tr>
        </table>
        <br>
        <div style="background-color: #f2f2f2; padding: 5px; border: 1px solid #ddd;">
            <strong>Detalles Técnicos:</strong><br><br>' . $explicacion . '
        </div>
        <br><br>
        ';

        // Tabla de TODOS los componentes activos
        $html .= '<h4>Componentes</h4>';
        if ($componentes->isNotEmpty()) {
            $html .= '<table border="1" cellpadding="6" cellspacing="0" style="border-collapse:collapse;">
        <thead>
            <tr style="background-color:#b91d47; color:#ffffff; font-weight:bold;">
                <th width="20%">Tipo</th>
                <th width="20%">Marca</th>
                <th width="25%">Modelo</th>
                <th width="20%">Capacidad/Frecuencia</th>
                <th width="15%">Estado</th>
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
            $html .= '<table border="1" cellpadding="6" cellspacing="0" style="border-collapse:collapse;">
        <thead>
            <tr style="background-color:#c82333; color:#ffffff; font-weight:bold;">
                <th width="20%">Componente Opcional</th>
                <th width="20%">Marca</th>
                <th width="25%">Modelo</th>
                <th width="20%">Capacidad/Velocidad</th>
                <th width="15%">Estado</th>
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

        // --- FIRMAS ---
        $html .= '
        <br><br><br>
        <table border="0" cellpadding="2" cellspacing="0" style="width: 100%; text-align: center; font-size: 8px;">
            <tr>
                <td style="width: 50%;">
                    _________________________________<br>
                    <b>' . e(Auth::user()->usuario  ?? 'N/A') . '</b><br>
                    Técnico Div. soporte<br>
                    Hardware y Software
                </td>
                <td style="width: 50%;">
                    _________________________________<br>
                    <b>T.S.U Cruz Mario Veliz</b><br>
                    Jefe de la División de Soporte de Hardware y Software del Sistema<br>
                    Decreto N° 08857 publicado en la Gaceta Oficial del<br>
                    Estado Lara Ordinaria N° 25.596 de fecha 03 diciembre del 2025
                </td>
            </tr>
            <tr>
                <td colspan="2" style="height: 30px;">&nbsp;</td> 
            </tr>
            <tr>
                <td style="width: 50%;">
                    _________________________________<br>
                    <b>T.S.U Milagros Oropeza</b><br>
                    Directora de Soporte al usuario<br>
                    Decreto N° 08857 publicado en la Gaceta Oficial del<br>
                    Estado Lara Ordinaria N° 25.596 de fecha 03 diciembre del 2025
                </td>
                <td style="width: 50%;">
                    _________________________________<br>
                    <b>Ing. Arnaldo E. Suárez C.</b><br>
                    Director General de la Oficina de Tecnología<br>
                    de Información y Comunicaciones<br>
                    Decreto N° 0035 publicado en la Gaceta Oficial del Estado Lara Ordinaria N° 25.721 de fecha 26 junio de 2025<br>
                    y Modificado según Decreto N° 00258 publicado en la Gaceta Oficial del Estado Lara<br>
                    extraordinaria N° 1739 de fecha 25 de septiembre 2025
                </td>
            </tr>
        </table>';

        // Escribir HTML en PDF
        $pdf->writeHTML($html, true, false, true, false, '');

        // Salida del PDF al navegador
        $pdf->Output('ficha_equipo_' . ($equipo->nombre ?? 'equipo') . '.pdf', 'I');
    }
}
