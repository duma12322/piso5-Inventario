<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * Controlador base de la aplicación.
 *
 * Este controlador extiende de la clase BaseController de Laravel y
 * proporciona funcionalidades comunes a todos los controladores
 * de la aplicación mediante traits (comportamientos reutilizables):
 *
 * 1. AuthorizesRequests
 *    - Permite verificar permisos y políticas de acceso para recursos.
 *    - Ejemplo: $this->authorize('update', $post);
 *
 * 2. DispatchesJobs
 *    - Permite enviar trabajos (jobs) a la cola de Laravel.
 *    - Ejemplo: $this->dispatch(new SendEmailJob($user));
 *
 * 3. ValidatesRequests
 *    - Permite validar solicitudes HTTP de forma sencilla.
 *    - Ejemplo: $request->validate(['name' => 'required|string']);
 *
 * Todos los controladores de la aplicación pueden heredar de esta clase
 * para acceder a estas funcionalidades de forma centralizada.
 */
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}
