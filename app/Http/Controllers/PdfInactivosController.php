<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Equipo;
use App\Models\Componente;
use App\Models\ComponenteOpcional;
use TCPDF;

class PdfInactivosController extends Controller
{
    public function exportar(Request $request)
    {
        $idDireccion = $request->id_direccion;
        $idDivision = $request->id_division;
        $idCoordinacion = $request->id_coordinacion;

        // ---------------------------------------------------------
        // VALIDACIONES DE SENTIDO LÓGICO
        // ---------------------------------------------------------

        if (!$idDireccion && $idDivision) {
            return back()->with('error', 'Debe seleccionar primero la Dirección.');
        }

        if (!$idDireccion && $idCoordinacion) {
            return back()->with('error', 'Debe seleccionar primero la Dirección.');
        }

        if ($idDireccion && !$idDivision && $idCoordinacion) {
            return back()->with('error', 'Debe seleccionar la División antes de la Coordinación.');
        }

        // ---------------------------------------------------------
        // CONSULTA BASE + RELACIONES
        // ---------------------------------------------------------
        $query = Equipo::where('estado', 'Activo'); // 🔹 Solo equipos activos

        // Aplicar filtros
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

        // Agregar componentes y opcionales inactivos
        $equipos->transform(function ($equipo) {
            $equipo->componentes_inactivos = Componente::where('id_equipo', $equipo->id_equipo)
                ->where(function ($q) {
                    $q->where('estado', 'Inactivo')
                        ->orWhere('estadoElim', 'Inactivo');
                })
                ->get();

            $equipo->opcionales_inactivos = ComponenteOpcional::where('id_equipo', $equipo->id_equipo)
                ->where('estadoElim', 'Inactivo')
                ->get();

            return $equipo;
        });

        // ---------------------------------------------------------
        // PDF TCPDF
        // ---------------------------------------------------------
        $pdf = new TCPDF('L', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetTitle('Equipos con Componentes Inactivos');
        $pdf->SetMargins(10, 10, 10);
        $pdf->AddPage();

        $html = '<h2>Equipos con Componentes Inactivos</h2>';

        $html .= '<table border="1" cellpadding="5">
                    <thead>
                        <tr bgcolor="#cccccc">
                            <th>ID</th>
                            <th>Equipo</th>
                            <th>Dirección</th>
                            <th>División</th>
                            <th>Coordinación</th>
                            <th>Componentes Inactivos</th>
                        </tr>
                    </thead>
                    <tbody>';

        foreach ($equipos as $e) {

            $comp = '';

            foreach ($e->componentes_inactivos as $c) {
                $comp .= "{$c->tipo_componente} ({$c->marca}) - Inactivo<br>";
            }

            foreach ($e->opcionales_inactivos as $o) {
                $comp .= "{$o->tipo_opcional} ({$o->marca} {$o->modelo}) - Inactivo<br>";
            }

            if ($comp === '') {
                $comp = '—';
            }

            $html .= '<tr>';
            $html .= '<td>' . $e->id_equipo . '</td>';
            $html .= '<td>' . $e->marca . ' ' . $e->modelo . '</td>';
            $html .= '<td>' . ($e->direccion->nombre_direccion ?? '—') . '</td>';
            $html .= '<td>' . ($e->division->nombre_division ?? '—') . '</td>';
            $html .= '<td>' . ($e->coordinacion->nombre_coordinacion ?? '—') . '</td>';
            $html .= '<td>' . $comp . '</td>';
            $html .= '</tr>';
        }

        $html .= '</tbody></table>';

        $pdf->writeHTML($html);
        return $pdf->Output('equipos_inactivos.pdf', 'I');
    }
}
