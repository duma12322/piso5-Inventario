<?php

namespace App\Http\Controllers;

use App\Models\Equipo;
use TCPDF;
use Carbon\Carbon;

class EstadoFuncionalPdfController extends Controller
{
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
        $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetCreator('Laravel TCPDF');
        $pdf->SetAuthor('Sistema de Inventario');
        $pdf->SetTitle('Estado Funcional de Equipos Activos');
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->AddPage();

        $html = '<h2 style="text-align:center;">Estado Funcional de Equipos Activos</h2><br>';

        // Tabla resumen por estado
        $html .= '<table border="1" cellpadding="6">
            <thead>
                <tr style="background-color:#f2f2f2;">
                    <th>Estado Funcional</th>
                    <th>Cantidad de Equipos</th>
                </tr>
            </thead>
            <tbody>';

        foreach ($estadoFuncionalCount as $estado => $count) {
            if ($count > 0) { // Mostrar solo si hay equipos activos
                $html .= '<tr>
                    <td>' . e($estado) . '</td>
                    <td>' . e($count) . '</td>
                </tr>';
            }
        }

        $html .= '</tbody></table><br><br>';

        // Detalle de equipos por estado
        foreach ($estadoFuncionalCount as $estado => $count) {
            $equiposPorEstado = $equipos->where('estado_funcional', $estado);

            if ($equiposPorEstado->isEmpty()) continue; // Saltar si no hay equipos

            $html .= '<h4>' . e($estado) . ' (' . e($count) . ' equipos)</h4>';
            $html .= '<table border="1" cellpadding="4">
                <thead>
                    <tr style="background-color:#f9f9f9;">
                        <th>Equipo</th>
                        <th>Marca</th>
                        <th>Modelo</th>
                        <th>Estado Funcional</th>
                        <th>Estado Gabinete</th>
                    </tr>
                </thead>
                <tbody>';

            foreach ($equiposPorEstado as $equipoItem) {
                $html .= '<tr>
                    <td>' . e($equipoItem->nombre ?? 'N/A') . '</td>
                    <td>' . e($equipoItem->marca ?? 'N/A') . '</td>
                    <td>' . e($equipoItem->modelo ?? 'N/A') . '</td>
                    <td>' . e($equipoItem->estado_funcional ?? 'Desconocido') . '</td>
                    <td>' . e($equipoItem->estado_gabinete ?? 'Desconocido') . '</td>
                </tr>';
            }

            $html .= '</tbody></table><br>';
        }

        $pdf->writeHTML($html, true, false, true, false, '');
        $pdf->Output('estado_funcional_activos_' . $anioActual . '.pdf', 'I');
    }
}
