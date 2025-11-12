<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Division;
use App\Models\Coordinacion;

class AjaxController extends Controller
{
    public function divisiones($idDireccion)
    {
        $divisiones = Division::where('id_direccion', $idDireccion)->get();

        $options = '<option value="">Seleccione</option>';
        foreach ($divisiones as $d) {
            $options .= "<option value='{$d->id_division}'>{$d->nombre_division}</option>";
        }

        return response($options);
    }


    public function coordinaciones(Request $request)
    {
        $idDivision = $request->query('id_division');
        $coordinaciones = Coordinacion::where('id_division', $idDivision)->get();

        $options = '<option value="">Seleccione</option>';
        foreach ($coordinaciones as $c) {
            $options .= "<option value='{$c->id}'>{$c->nombre}</option>";
        }

        return response($options);
    }
}
