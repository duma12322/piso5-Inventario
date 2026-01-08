<?php

namespace App\Http\Controllers;

use App\Models\Equipo;
use App\Models\Componente;
use App\Models\ComponenteOpcional;
use TCPDF;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class EquiposGlobalPdfController extends Controller
{
    public function generarPDFGlobal($estadoTecnologico = null)
    {
        $anioActual = Carbon::now()->year;
        $filtros = request()->all();

        // Equipos activos
        $query = Equipo::with(['direccion', 'division', 'coordinacion'])
            ->where('estado', 'Activo');

        $filtros = request()->all();

        // 1. Crear query base
        $query = Equipo::with(['direccion', 'division', 'coordinacion'])
            ->where('estado', 'Activo');

        // 2. Filtro de búsqueda general (search)
        if (!empty($filtros['search'])) {
            $clean = preg_replace('/[^\wñÑáéíóúÁÉÍÓÚ ]+/u', ' ', $filtros['search']);
            $terms = array_filter(explode(' ', $clean));

            $query->where(function ($q) use ($terms) {
                foreach ($terms as $t) {
                    $q->where(function ($sub) use ($t) {
                        $sub->where('marca', 'LIKE', "%{$t}%")
                            ->orWhere('modelo', 'LIKE', "%{$t}%")
                            ->orWhere('estado_funcional', 'LIKE', "%{$t}%")
                            ->orWhere('estado_tecnologico', 'LIKE', "%{$t}%")
                            ->orWhere('estado_gabinete', 'LIKE', "%{$t}%")
                            ->orWhereHas('direccion', fn($rel) => $rel->where('nombre_direccion', 'LIKE', "%{$t}%"))
                            ->orWhereHas('division', fn($rel) => $rel->where('nombre_division', 'LIKE', "%{$t}%"))
                            ->orWhereHas('coordinacion', fn($rel) => $rel->where('nombre_coordinacion', 'LIKE', "%{$t}%"));
                    });
                }
            });
        }

        // 3. Filtros individuales
        if (!empty($filtros['marca'])) {
            $query->where('marca', 'LIKE', "%{$filtros['marca']}%");
        }
        if (!empty($filtros['modelo'])) {
            $query->where('modelo', 'LIKE', "%{$filtros['modelo']}%");
        }
        if (!empty($filtros['division'])) {
            $query->where('division_id', $filtros['division']);
        }
        if (!empty($filtros['direccion'])) {
            $query->where('direccion_id', $filtros['direccion']);
        }
        if (!empty($filtros['estado_tecnologico'])) {
            $query->where('estado_tecnologico', $filtros['estado_tecnologico']);
        }
        if (!empty($filtros['estado_funcional'])) {
            $query->where('estado_funcional', $filtros['estado_funcional']);
        }

        // 4. Filtrar por estadoTecnologico si se pasa como parámetro
        if ($estadoTecnologico) {
            $query->where('estado_tecnologico', $estadoTecnologico);
        }

        // 5. Obtener resultados filtrados
        $equipos = $query->get();

        if ($equipos->isEmpty()) {
            abort(404, 'No hay equipos activos para generar el PDF.');
        }



        if ($estadoTecnologico) {
            $query->where('estado_tecnologico', $estadoTecnologico);
        }

        $equipos = $query->get();

        if ($equipos->isEmpty()) {
            abort(404, 'No hay equipos activos para generar el PDF.');
        }

        $pdf = new TCPDF('L', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetCreator('Laravel TCPDF');
        $pdf->SetAuthor('Sistema de Inventario');
        $pdf->SetTitle('Listado de Equipos Activos');
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->SetMargins(15, 15, 15); // Márgenes estándar
        $pdf->SetAutoPageBreak(TRUE, 15);
        $pdf->AddPage();

        // Insertar imagen de encabezado
        $imagePath = public_path('encabezado.jpeg');
        if (file_exists($imagePath)) {
            $pdf->SetAlpha(0.3); // Opacidad baja
            $pdf->Image($imagePath, 0, 0, 297, 27, 'JPEG', '', 'T', false, 300, '', false, false, 0, false, false, false);
            $pdf->SetAlpha(1);   // Restaurar opacidad
            $pdf->Ln(30); // Espacio para bajar el contenido
        }

        $fechaHora = date('d/m/Y h:i A');

        $html = '<div style="background-color: #b91d47; color: white; line-height: 25px; font-size: 14px; font-weight: bold; text-align: center;">
            LISTADO DE EQUIPOS ACTIVOS ' . ($estadoTecnologico ? strtoupper($estadoTecnologico) : '') . '
        </div>
        <div style="text-align: right; font-size: 9px; color: #444; margin-top: 2px;">Generado el: ' . $fechaHora . '</div><br>';

        foreach ($equipos as $equipo) {
            $direccion = $equipo->direccion->nombre_direccion ?? 'N/A';
            $division = $equipo->division->nombre_division ?? 'N/A';
            $coordinacion = $equipo->coordinacion->nombre_coordinacion ?? 'N/A';

            // Componentes activos
            $componentes = Componente::where('id_equipo', $equipo->id_equipo)
                ->where('estadoElim', 'Activo')
                ->get();

            $componentesOpcionales = ComponenteOpcional::where('id_equipo', $equipo->id_equipo)
                ->where('estadoElim', 'Activo')
                ->get();

            // Calcular explicación y estado tecnológico
            $componentesTecnologicos = $componentes->filter(fn($c) => in_array(strtolower($c->tipo_componente), ['tarjeta madre', 'procesador', 'memoria ram']));
            $explicacion = '';
            $puntajeTotal = 0;
            $pesoTotal = 0;

            foreach ($componentesTecnologicos as $comp) {
                $tipoComp = strtolower($comp->tipo_componente);
                if ($tipoComp === 'tarjeta madre') {
                    $tec = DB::table('componentes_tecnologia')
                        ->where('tipo_componente', 'Socket CPU')
                        ->where('tipo', $comp->socket)
                        ->first();
                    $anioInst = $comp->fecha_instalacion ? (int) $comp->fecha_instalacion : $anioActual;
                    $anioLanz = $tec->anio_lanzamiento ?? $anioActual;
                    $vidaUtil = $tec->vida_util_anios ?? 10;
                    $peso = $tec->peso_importancia ?? 4;
                    $socket = $comp->socket ?? 'N/A';
                    $edad = max(0, $anioActual - $anioInst);

                    $explicacion .= "- {$comp->tipo_componente} ({$comp->marca} {$comp->modelo}): instalada en {$anioInst}, edad considerada {$edad} años<br>";
                    $explicacion .= "- Socket: {$socket}, tecnología lanzada en {$anioLanz}, vida útil {$vidaUtil} años.<br>";
                    $puntajeComponente = max(0, 1 - ($edad / $vidaUtil)) * $peso;
                } elseif ($tipoComp === 'memoria ram') {
                    $tec = DB::table('componentes_tecnologia')
                        ->where('tipo_componente', 'Memoria RAM')
                        ->where('tipo', 'LIKE', "%{$comp->tipo}%")
                        ->first();
                    $anioLanz = $tec->anio_lanzamiento ?? $anioActual;
                    $vidaUtil = $tec->vida_util_anios ?? 8;
                    $peso = $tec->peso_importancia ?? 2;
                    $edad = max(0, $anioActual - $anioLanz);
                    $explicacion .= "- {$comp->tipo_componente} ({$comp->marca}, Tipo: {$comp->tipo}): tecnología lanzada en {$anioLanz}, vida útil {$vidaUtil} años.<br>";
                    $puntajeComponente = max(0, 1 - ($edad / $vidaUtil)) * $peso;
                } else {
                    $puntajeComponente = 0;
                    $peso = 0;
                }
                $puntajeTotal += $puntajeComponente;
                $pesoTotal += $peso;
            }

            // Si no se generó ningún detalle, asignar mensaje por defecto
            if (empty($explicacion)) {
                $explicacion = "Sin detalles disponibles.";
            }

            $ratio = $pesoTotal ? $puntajeTotal / $pesoTotal : 1;
            if ($ratio >= 0.75)
                $estadoTecnologicoEquipo = 'Nuevo';
            elseif ($ratio >= 0.4)
                $estadoTecnologicoEquipo = 'Actualizable';
            else
                $estadoTecnologicoEquipo = 'Obsoleto';

            $html .= '<h4 style="background-color:#eee; padding:5px; border-left: 5px solid #b91d47;">&nbsp;Equipo: ' . e($equipo->marca . ' ' . $equipo->modelo) . ' (Bien: ' . e($equipo->numero_bien ?? 'N/A') . ')</h4>';

            $html .= '<table border="0" cellpadding="2">
                <tr>
                    <td width="15%"><strong>Dirección:</strong></td><td width="35%">' . e($direccion) . '</td>
                    <td width="15%"><strong>División:</strong></td><td width="35%">' . e($division) . '</td>
                </tr>
                <tr>
                    <td width="15%"><strong>Coordinación:</strong></td><td width="35%">' . e($coordinacion) . '</td>
                    <td width="15%"><strong>Funcional:</strong></td><td width="35%">' . e($equipo->estado_funcional ?: 'N/A') . '</td>
                </tr>
                <tr>
                    <td width="15%"><strong>Gabinete:</strong></td><td width="35%">' . e($equipo->estado_gabinete ?: 'N/A') . '</td>
                    <td width="15%"><strong>Tecnológico:</strong></td><td width="35%">' . e($estadoTecnologicoEquipo) . '</td>
                </tr>
            </table>
            <div style="font-size:10px; color:#555; padding-left:5px;"><em>' . $explicacion . '</em></div><br>';

            // Tabla de componentes
            if ($componentes->isNotEmpty()) {
                $html .= '<table border="1" cellpadding="6" cellspacing="0" style="border-collapse:collapse; page-break-inside:avoid;">
                    <thead>
                        <tr style="background-color:#b91d47; color:#ffffff; font-weight:bold;">
                            <th width="20%">Componente</th>
                            <th width="20%">Marca</th>
                            <th width="25%">Modelo</th>
                            <th width="20%">Capacidad/Frecuencia</th>
                            <th width="15%">Estado</th>
                        </tr>
                    </thead>
                    <tbody>';
                foreach ($componentes as $c) {
                    $capacidad = $c->capacidad ?? $c->frecuencia ?? 'N/A';
                    $html .= '<tr>
                        <td>' . e(trim($c->tipo_componente) ?: 'N/A') . '</td>
                        <td>' . e(trim($c->marca) ?: 'N/A') . '</td>
                        <td>' . e(trim($c->modelo) ?: 'N/A') . '</td>
                        <td>' . e(trim($capacidad) ?: 'N/A') . '</td>
                        <td>' . e(trim($c->estado) ?: 'Desconocido') . '</td>
                    </tr>';
                }
                $html .= '</tbody></table><br>';
            }

            // Componentes opcionales
            if ($componentesOpcionales->isNotEmpty()) {
                $html .= '<table border="1" cellpadding="6" cellspacing="0" style="border-collapse:collapse; page-break-inside:avoid;">
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
                foreach ($componentesOpcionales as $o) {
                    $capacidad = $o->capacidad ?? $o->velocidad ?? 'N/A';
                    $html .= '<tr>
                        <td>' . e(trim($o->tipo_opcional) ?: 'N/A') . '</td>
                        <td>' . e(trim($o->marca) ?: 'N/A') . '</td>
                        <td>' . e(trim($o->modelo) ?: 'N/A') . '</td>
                        <td>' . e(trim($capacidad) ?: 'N/A') . '</td>
                        <td>' . e(trim($o->estado) ?: 'N/A') . '</td>
                    </tr>';
                }
                $html .= '</tbody></table><br>';
            }

            $html .= '<hr style="border-top:1px solid #999;"><br>';
        }

        // --- FIRMAS ---
        $html .= '
        <br><br><br>
        <table border="0" cellpadding="2" cellspacing="0" style="width: 100%; text-align: center; font-size: 8px;">
            <tr>
                <td style="width: 50%;">
                    _________________________________<br>
                    <b>' . e(auth()->user()->usuario ?? 'N/A') . '</b><br>
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

        $pdf->writeHTML($html, true, false, true, false, '');
        $pdf->Output('listado_equipos_activos_' . ($estadoTecnologico ?: 'todos') . '.pdf', 'I');
    }
}
