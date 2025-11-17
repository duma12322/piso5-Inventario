<?php

namespace App\Http\Controllers;

use App\Models\Equipo;
use TCPDF;
use Carbon\Carbon;

class EstadoTecnologicoPdfController extends Controller
{
    public function generarPDF()
    {
        $anioActual = Carbon::now()->year;

        // Obtener solo los equipos activos
        $equipos = Equipo::where('estado', 'Activo')->get();

        // Contar estado tecnológico
        $estadoTecnologico = [
            'Nuevo' => 0,
            'Actualizable' => 0,
            'Obsoleto' => 0,
        ];

        foreach ($equipos as $equipo) {
            if (!empty($equipo->estado_tecnologico)) {
                $estadoTecnologico[$equipo->estado_tecnologico] = ($estadoTecnologico[$equipo->estado_tecnologico] ?? 0) + 1;
            } else {
                $estadoTecnologico['Obsoleto']++;
            }
        }

        // Crear PDF
        $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetCreator('Laravel TCPDF');
        $pdf->SetAuthor('Sistema de Inventario');
        $pdf->SetTitle('Estado Tecnológico de Equipos');
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->AddPage();

        $html = '<h2 style="text-align:center;">Estado Tecnológico de Equipos Activos</h2><br>';

        $html .= '<table border="1" cellpadding="6">
        <thead>
            <tr style="background-color:#f2f2f2;">
                <th>Estado</th>
                <th>Cantidad de Equipos</th>
            </tr>
        </thead>
        <tbody>';

        foreach ($estadoTecnologico as $estado => $count) {
            $html .= '<tr>
            <td>' . e($estado) . '</td>
            <td>' . e($count) . '</td>
        </tr>';
        }

        $html .= '</tbody></table><br><br>';

        // Listado de equipos por estado (solo activos)
        foreach ($estadoTecnologico as $estado => $count) {
            $equiposPorEstado = $equipos->where('estado_tecnologico', $estado);
            if ($equiposPorEstado->isEmpty()) continue; // Saltar si no hay equipos activos en este estado

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

            foreach ($equiposPorEstado as $equipoItem) {
                $html .= '<tr>
                <td>' . e($equipoItem->marca ?? 'N/A') . '</td>
                <td>' . e($equipoItem->modelo ?? 'N/A') . '</td>
                <td>' . e($equipoItem->estado_funcional ?? 'Desconocido') . '</td>
                <td>' . e($equipoItem->estado_gabinete ?? 'Desconocido') . '</td>
            </tr>';
            }

            $html .= '</tbody></table><br>';
        }

        $pdf->writeHTML($html, true, false, true, false, '');
        $pdf->Output('estado_tecnologico_activos_' . $anioActual . '.pdf', 'I');
    }
}
