<?php

namespace App\Http\Controllers;

use App\Models\Equipo;
use TCPDF;
use Carbon\Carbon;

class EstadoGabinetePdfController extends Controller
{
    public function generarPDF()
    {
        $anioActual = Carbon::now()->year;

        // Valores posibles del enum de gabinetes
        $estadosGabinete = [
            'Nuevo',
            'Deteriorado',
            'Dañado'
        ];

        // Inicializar conteo por estado
        $estadoGabineteCount = array_fill_keys($estadosGabinete, 0);
        $equipos = Equipo::all();

        // Contar equipos por estado de gabinete
        foreach ($equipos as $equipo) {
            $estado = in_array($equipo->estado_gabinete, $estadosGabinete)
                ? $equipo->estado_gabinete
                : null;

            if ($estado) {
                $estadoGabineteCount[$estado]++;
            }
        }

        // Crear PDF
        $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetCreator('Laravel TCPDF');
        $pdf->SetAuthor('Sistema de Inventario');
        $pdf->SetTitle('Estado Físico de Gabinetes');
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->AddPage();

        $html = '<h2 style="text-align:center;">Estado Físico de Gabinetes</h2><br>';

        // Tabla resumen por estado
        $html .= '<table border="1" cellpadding="6">
            <thead>
                <tr style="background-color:#f2f2f2;">
                    <th>Estado Gabinete</th>
                    <th>Cantidad de Equipos</th>
                </tr>
            </thead>
            <tbody>';

        foreach ($estadoGabineteCount as $estado => $count) {
            $html .= '<tr>
                <td>' . e($estado) . '</td>
                <td>' . e($count) . '</td>
            </tr>';
        }

        $html .= '</tbody></table><br><br>';

        // Detalle de equipos por estado
        foreach ($estadoGabineteCount as $estado => $count) {
            $html .= '<h4>' . e($estado) . ' (' . e($count) . ' equipos)</h4>';
            $html .= '<table border="1" cellpadding="4">
                <thead>
                    <tr style="background-color:#f9f9f9;">
                        <th>Marca</th>
                        <th>Modelo</th>
                        <th>Estado Funcional</th>
                        <th>Estado Gabinete</th>
                    </tr>
                </thead>
                <tbody>';

            $equiposPorEstado = $equipos->where('estado_gabinete', $estado);

            if ($equiposPorEstado->isEmpty()) {
                $html .= '<tr>
                    <td colspan="5" style="text-align:center;">No hay equipos</td>
                </tr>';
            } else {
                foreach ($equiposPorEstado as $equipoItem) {
                    $html .= '<tr>
                        <td>' . e($equipoItem->marca ?? 'N/A') . '</td>
                        <td>' . e($equipoItem->modelo ?? 'N/A') . '</td>
                        <td>' . e($equipoItem->estado_funcional ?? 'Desconocido') . '</td>
                        <td>' . e($equipoItem->estado_gabinete ?? 'Desconocido') . '</td>
                    </tr>';
                }
            }

            $html .= '</tbody></table><br>';
        }

        $pdf->writeHTML($html, true, false, true, false, '');
        $pdf->Output('estado_gabinete_' . $anioActual . '.pdf', 'I');
    }
}
