<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Division;
use App\Models\Coordinacion;

/**
 * ------------------------------------------------------------------
 * Controlador AjaxController
 * ------------------------------------------------------------------
 * Este controlador gestiona peticiones AJAX relacionadas con:
 *  - Carga dinámica de divisiones según una dirección
 *  - Carga dinámica de coordinaciones según una división
 *
 * Está pensado para ser consumido desde formularios dinámicos
 * (select dependientes) mediante llamadas AJAX.
 * ------------------------------------------------------------------
 */
class AjaxController extends Controller
{
    /**
     * --------------------------------------------------------------
     * Método: divisiones
     * --------------------------------------------------------------
     * Obtiene las divisiones asociadas a una dirección específica
     * y devuelve las opciones en formato HTML (<option>).
     *
     * Parámetros:
     * @param int $idDireccion  ID de la dirección seleccionada.
     *
     * Proceso:
     *  - Consulta las divisiones relacionadas con la dirección.
     *  - Construye dinámicamente las opciones del <select>.
     *  - Incluye una opción inicial "Seleccione".
     *
     * Retorna:
     * @return \Illuminate\Http\Response
     *         Respuesta HTML con las opciones del select.
     *
     * Uso típico:
     *  Llamada AJAX al cambiar un select de direcciones.
     * --------------------------------------------------------------
     */
    public function divisiones($idDireccion)
    {
        // Obtiene todas las divisiones asociadas a la dirección
        $divisiones = Division::where('id_direccion', $idDireccion)->get();

        // Opción inicial por defecto
        $options = '<option value="">Seleccione</option>';

        // Construcción dinámica de las opciones HTML
        foreach ($divisiones as $d) {
            $options .= "<option value='{$d->id_division}'>{$d->nombre_division}</option>";
        }

        // Retorna las opciones como respuesta AJAX
        return response($options);
    }

    /**
     * --------------------------------------------------------------
     * Método: coordinaciones
     * --------------------------------------------------------------
     * Obtiene las coordinaciones asociadas a una división específica
     * y devuelve las opciones en formato HTML (<option>).
     *
     * Parámetros:
     * @param \Illuminate\Http\Request $request
     *        Debe contener el parámetro 'id_division' vía query string.
     *
     * Proceso:
     *  - Extrae el ID de la división desde la request.
     *  - Consulta las coordinaciones relacionadas.
     *  - Genera dinámicamente las opciones del <select>.
     *
     * Retorna:
     * @return \Illuminate\Http\Response
     *         Respuesta HTML con las opciones del select.
     *
     * Uso típico:
     *  Llamada AJAX al cambiar un select de divisiones.
     * --------------------------------------------------------------
     */
    public function coordinaciones(Request $request)
    {
        // Obtiene el ID de la división desde la query string
        $idDivision = $request->query('id_division');

        // Consulta las coordinaciones asociadas a la división
        $coordinaciones = Coordinacion::where('id_division', $idDivision)->get();

        // Opción inicial por defecto
        $options = '<option value="">Seleccione</option>';

        // Construcción dinámica de las opciones HTML
        foreach ($coordinaciones as $c) {
            $options .= "<option value='{$c->id}'>{$c->nombre}</option>";
        }

        // Retorna las opciones como respuesta AJAX
        return response($options);
    }
}
