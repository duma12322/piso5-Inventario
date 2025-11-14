<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Equipo;
use App\Models\Componente;
use App\Models\ComponenteOpcional;
use App\Models\Direccion;
use App\Models\Division;
use App\Models\Coordinacion;
use TCPDF;

class PdfInactivosController extends Controller
{
    public function exportar(Request $request)
    {
        // Consulta equipos filtrados
        $query = Equipo::query();

        if ($request->filled('id_direccion')) {
            $query->where('id_direccion', $request->id_direccion);
        }
        if ($request->filled('id_division')) {
            $query->where('id_division', $request->id_division);
        }
        if ($request->filled('id_coordinacion')) {
            $query->where('id_coordinacion', $request->id_coordinacion);
        }

        $equipos = $query->get();

        // Cargar componentes y opcionales inactivos
        $equipos->transform(function ($equipo) {
            $equipo->componentes_inactivos = Componente::where('id_equipo', $equipo->id_equipo)
                ->where(function ($q) {
                    $q->where('estado', 'Inactivo')
                        ->orWhere('estadoElim', 'Inactivo');
                })->get();

            $equipo->opcionales_inactivos = ComponenteOpcional::where('id_equipo', $equipo->id_equipo)
                ->where('estadoElim', 'Inactivo')->get();

            return $equipo;
        });

        // Crear PDF
        $pdf = new TCPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->SetTitle('Equipos con Componentes Inactivos');
        $pdf->SetMargins(10, 10, 10);
        $pdf->SetAutoPageBreak(TRUE, 10);
        $pdf->AddPage();

        // Construir HTML del PDF
        $html = '<h2>💀 Equipos con Componentes Inactivos</h2>';
        $html .= '<table border="1" cellpadding="5">
                    <thead>
                        <tr style="background-color:#cccccc;">
                            <th>ID</th>
                            <th>Equipo</th>
                            <th>Dirección</th>
                            <th>División</th>
                            <th>Coordinación</th>
                            <th>Componentes Inactivos</th>
                        </tr>
                    </thead>
                    <tbody>';

        foreach ($equipos as $equipo) {
            $html .= '<tr>';
            $html .= '<td>' . $equipo->id_equipo . '</td>';
            $html .= '<td>' . $equipo->marca . ' ' . $equipo->modelo . '</td>';
            $html .= '<td>' . ($equipo->direccion->nombre_direccion ?? 'N/A') . '</td>';
            $html .= '<td>' . ($equipo->division->nombre_division ?? 'N/A') . '</td>';
            $html .= '<td>' . ($equipo->coordinacion->nombre_coordinacion ?? 'N/A') . '</td>';

            // Componentes principales y opcionales
            $compHtml = '';

            if ($equipo->componentes_inactivos->count()) {
                $compHtml .= "<strong>Componentes Principales:</strong><br>";
                foreach ($equipo->componentes_inactivos as $comp) {
                    $compHtml .= "🧩 {$comp->tipo_componente} ({$comp->marca}) - Inactivo<br>";
                }
            }

            if ($equipo->opcionales_inactivos->count()) {
                $compHtml .= "<strong>Componentes Opcionales:</strong><br>";
                foreach ($equipo->opcionales_inactivos as $op) {
                    $compHtml .= "⚙️ {$op->tipo_opcional} ({$op->marca} {$op->modelo}) - Inactivo<br>";
                }
            }

            $html .= '<td>' . $compHtml . '</td>';
            $html .= '</tr>';
        }

        $html .= '</tbody></table>';

        // Escribir HTML en PDF
        $pdf->writeHTML($html, true, false, true, false, '');

        // Retornar PDF al navegador
        return $pdf->Output('equipos_inactivos.pdf', 'I');
    }
}
