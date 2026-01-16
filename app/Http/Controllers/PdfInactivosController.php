<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Equipo;
use App\Models\Componente;
use App\Models\ComponenteOpcional;
use TCPDF;

class PdfInactivosController extends Controller
{
    /**
     * Genera un PDF con los equipos que tienen componentes u opcionales inactivos.
     *
     * Aplica filtros por dirección, división y coordinación.
     *
     * @param \Illuminate\Http\Request $request
     * @return mixed PDF descargable o visualizable
     */
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

        // Aplicar filtros si están presentes
        if ($request->filled('id_direccion')) {
            $query->where('id_direccion', $request->id_direccion);
        }
        if ($request->filled('id_division')) {
            $query->where('id_division', $request->id_division);
        }
        if ($request->filled('id_coordinacion')) {
            $query->where('id_coordinacion', $request->id_coordinacion);
        }

        // Obtener los equipos filtrados
        $equipos = $query->get();

        // Transformar cada equipo para agregar componentes y opcionales inactivos
        $equipos->transform(function ($equipo) {
            // Componentes inactivos (estado o estadoElim)
            $equipo->componentes_inactivos = Componente::where('id_equipo', $equipo->id_equipo)
                ->where(function ($q) {
                    $q->where('estado', 'Inactivo')
                        ->orWhere('estadoElim', 'Inactivo');
                })
                ->get();

            // Componentes opcionales inactivos
            $equipo->opcionales_inactivos = ComponenteOpcional::where('id_equipo', $equipo->id_equipo)
                ->where('estadoElim', 'Inactivo')
                ->get();

            return $equipo;
        });

        // ---------------------------------------------------------
        // CONFIGURACIÓN PDF TCPDF
        // ---------------------------------------------------------
        $pdf = new TCPDF('L', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetTitle('Equipos con Componentes Inactivos');
        $pdf->SetMargins(10, 10, 10);
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->AddPage();

        // Insertar imagen de encabezado si existe
        $imagePath = public_path('encabezado.jpeg');
        if (file_exists($imagePath)) {
            $pdf->Image($imagePath, 0, 0, 297, 27, 'JPEG', '', 'T', false, 300, '', false, false, 0, false, false, false);
            $pdf->Ln(30); // espacio debajo de la imagen
        }

        // Título del PDF
        $html = '<h2>Equipos con Componentes Inactivos</h2>';

        // Tabla de equipos con componentes inactivos
        $html .= '<table border="1" cellpadding="5">
                    <thead>
                        <tr bgcolor="#cccccc">
                            <th>Nº Bien</th>
                            <th>Equipo</th>
                            <th>Dirección</th>
                            <th>División</th>
                            <th>Coordinación</th>
                            <th>Componentes Inactivos</th>
                        </tr>
                    </thead>
                    <tbody>';

        // Recorrer equipos y agregar filas a la tabla
        foreach ($equipos as $e) {
            $comp = '';

            // Listar componentes inactivos
            foreach ($e->componentes_inactivos as $c) {
                $comp .= "{$c->tipo_componente} ({$c->marca}) - Inactivo<br>";
            }

            // Listar componentes opcionales inactivos
            foreach ($e->opcionales_inactivos as $o) {
                $comp .= "{$o->tipo_opcional} ({$o->marca} {$o->modelo}) - Inactivo<br>";
            }

            if ($comp === '') {
                $comp = '—'; // Si no hay inactivos
            }

            // Construir fila de la tabla
            $html .= '<tr>';
            $html .= '<td>' . ($e->numero_bien ?? 'S/I') . '</td>';
            $html .= '<td>' . $e->marca . ' ' . $e->modelo . '</td>';
            $html .= '<td>' . ($e->direccion->nombre_direccion ?? '—') . '</td>';
            $html .= '<td>' . ($e->division->nombre_division ?? '—') . '</td>';
            $html .= '<td>' . ($e->coordinacion->nombre_coordinacion ?? '—') . '</td>';
            $html .= '<td>' . $comp . '</td>';
            $html .= '</tr>';
        }

        $html .= '</tbody></table>';

        // Generar el PDF
        $pdf->writeHTML($html);
        return $pdf->Output('equipos_inactivos.pdf', 'I'); // I = mostrar en navegador
    }
}
