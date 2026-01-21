<?php

namespace App\Http\Controllers;

use App\Models\Equipo;
use TCPDF;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class EstadoFuncionalPdfController extends Controller
{
    /**
     * Genera un PDF con el estado funcional de todos los equipos activos.
     *
     * @return void (descarga directa del PDF)
     */
    public function generarPDF()
    {
        $anioActual = Carbon::now()->year;

        // Obtener solo equipos activos
        $equipos = Equipo::where('estado', 'Activo')->get();

        // Valores posibles del enum
        $estadosFuncionales = [
            'Buen Funcionamiento',
            'Operativo',
            'Sin Funcionar'
        ];

        // Inicializar conteo por estado
        $estadoFuncionalCount = array_fill_keys($estadosFuncionales, 0);

        // Contar equipos por estado funcional (solo activos)
        foreach ($equipos as $equipo) {
            $estado = in_array($equipo->estado_funcional, $estadosFuncionales)
                ? $equipo->estado_funcional
                : null;

            if ($estado) {
                $estadoFuncionalCount[$estado]++;
            }
        }

        // Crear PDF
        $pdf = new TCPDF('L', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetCreator('Laravel TCPDF');
        $pdf->SetAuthor('Sistema de Inventario');
        $pdf->SetTitle('Estado Funcional de Equipos Activos');
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->AddPage();

        // --- AGREGAR IMAGEN DE ENCABEZADO ---
        if (file_exists(public_path('encabezado.jpeg'))) {
            $pdf->SetAlpha(0.3);
            $pdf->Image(public_path('encabezado.jpeg'), 0, 0, 210, 20, '', '', '', false, 300);
            $pdf->SetAlpha(1);
        }

        // Espacio debajo de la imagen para el contenido
        $pdf->Ln(45);

        $fechaHora = date('d/m/Y h:i A');

        // Encabezado principal del PDF
        $html = '<div style="background-color: #b91d47; color: white; line-height: 25px; font-size: 14px; font-weight: bold; text-align: center;">
            ESTADO FUNCIONAL DE EQUIPOS ACTIVOS
        </div>
        <div style="text-align: right; font-size: 9px; color: #444; margin-top: 2px;">Generado el: ' . $fechaHora . '</div><br><br>';

        // Tabla resumen por estado funcional
        $html .= '<table border="1" cellpadding="6" cellspacing="0" style="border-collapse:collapse;">
            <thead>
                <tr style="background-color:#b91d47; color:#ffffff; font-weight:bold;">
                    <th width="70%">Estado Funcional</th>
                    <th width="30%">Cantidad de Equipos</th>
                </tr>
            </thead>
            <tbody>';

        foreach ($estadoFuncionalCount as $estado => $count) {
            if ($count > 0) { // Mostrar solo si hay equipos activos
                $html .= '<tr style="background-color:#f9f9f9;">
                    <td>' . e($estado) . '</td>
                    <td>' . e($count) . '</td>
                </tr>';
            }
        }

        $html .= '</tbody></table><br><br>';

        // Detalle de equipos por estado funcional
        foreach ($estadoFuncionalCount as $estado => $count) {
            $equiposPorEstado = $equipos->where('estado_funcional', $estado);

            if ($equiposPorEstado->isEmpty())
                continue; // Saltar si no hay equipos

            // Sub-encabezado por estado
            $html .= '<h4 style="background-color:#eee; padding:5px; border-left: 5px solid #b91d47;">&nbsp;' . e($estado) . ' (' . e($count) . ' equipos)</h4>';

            // Tabla de detalle de equipos
            $html .= '<table border="1" cellpadding="3" cellspacing="0">
    <thead>
        <tr style="background-color:#555; color:#ffffff; font-size:9px;">
            <th width="12.5%">Bien</th>
            <th width="12.5%">Marca</th>
            <th width="12.5%">Modelo</th>
            <th width="12.5%">Funcional</th>
            <th width="12.5%">Gabinete</th>
            <th width="12.5%">Dirección</th>
            <th width="12.5%">División</th>
            <th width="12.5%">Coordinación</th>
        </tr>
    </thead>
    <tbody>';

            foreach ($equiposPorEstado as $equipoItem) {
                $html .= '<tr>
        <td style="font-size:7px; text-align:center;">' . e($equipoItem->numero_bien ?? 'N/A') . '</td>
        <td style="font-size:7px;">' . e($equipoItem->marca ?? 'N/A') . '</td>
        <td style="font-size:8px;">' . e($equipoItem->modelo ?? 'N/A') . '</td>
        <td style="font-size:8px;">' . e($equipoItem->estado_funcional ?? 'Desconocido') . '</td>
        <td style="font-size:8px;">' . e($equipoItem->estado_gabinete ?? 'Desconocido') . '</td>
        <td style="font-size:8px;">' . e($equipoItem->direccion->nombre_direccion ?? 'N/A') . '</td>
        <td style="font-size:8px;">' . e($equipoItem->division->nombre_division ?? 'N/A') . '</td>
        <td style="font-size:8px;">' . e($equipoItem->coordinacion->nombre_coordinacion ?? 'N/A') . '</td>

    </tr>';
            }

            $html .= '</tbody></table><br>';
        }

        // --- FIRMAS ---
        $html .= '
        <br><br><br>
        <table border="0" cellpadding="2" cellspacing="0" style="width: 100%; text-align: center; font-size: 8px;">
            <tr>
                <td style="width: 50%;">
                    _________________________________<br>
                    <b>' . e(Auth::user()->usuario ?? 'N/A') . '</b><br>
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
        $pdf->Output('estado_funcional_activos_' . $anioActual . '.pdf', 'I');
    }
}
