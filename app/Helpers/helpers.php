<?php

/**
 * -------------------------------------------------------------
 * Helpers globales del sistema
 * -------------------------------------------------------------
 * Este archivo contiene funciones auxiliares reutilizables
 * relacionadas con:
 *  - Validación de sesión/autenticación
 *  - Registro de acciones (logs)
 *  - Encriptación básica de contraseñas (MD5)
 *
 * Estas funciones pueden ser utilizadas en cualquier parte
 * de la aplicación Laravel.
 * -------------------------------------------------------------
 */

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Log as LogModel;

/**
 * -------------------------------------------------------------
 * Función: checkLogin
 * -------------------------------------------------------------
 * Verifica si el usuario está autenticado.
 * 
 * - Si el usuario NO está logueado, se detiene la ejecución
 *   devolviendo un error HTTP 403 (No autorizado).
 * - Si el usuario está autenticado, la ejecución continúa.
 *
 * Uso recomendado:
 *  - Controladores
 *  - Middlewares personalizados
 *  - Acciones críticas del sistema
 * -------------------------------------------------------------
 */
if (!function_exists('checkLogin')) {
    function checkLogin()
    {
        if (!Auth::check()) {
            abort(403, 'No autorizado');
        }
    }
}

/**
 * -------------------------------------------------------------
 * Función: logAction
 * -------------------------------------------------------------
 * Registra una acción realizada en el sistema.
 *
 * Esta función realiza dos acciones:
 *  1. Guarda el log en la base de datos (si el modelo existe).
 *  2. Registra el log en el archivo de logs de Laravel.
 *
 * Parámetros:
 * @param string|null $usuario  Nombre del usuario que ejecuta la acción.
 *                              Si es null, se asigna "sistema".
 * @param string $accion        Descripción de la acción realizada.
 *
 * Ejemplos de uso:
 *  logAction(Auth::user()->name, 'Creó un nuevo equipo');
 *  logAction(null, 'Proceso automático ejecutado');
 * -------------------------------------------------------------
 */
if (!function_exists('logAction')) {
    function logAction($usuario, $accion)
    {
        // Verifica que el modelo Log exista antes de intentar guardar
        if (class_exists(LogModel::class)) {
            LogModel::create([
                'usuario' => $usuario ?? 'sistema',
                'accion'  => $accion
            ]);
        }

        // Registra la acción también en los logs de Laravel
        Log::info($accion, ['usuario' => $usuario ?? 'sistema']);
    }
}

/**
 * -------------------------------------------------------------
 * Función: md5Password
 * -------------------------------------------------------------
 * Genera un hash MD5 a partir de una contraseña.
 *
 * Parámetros:
 * @param string $password  Contraseña en texto plano.
 *
 * Retorna:
 * @return string           Hash MD5 de la contraseña.
 *
 * Ejemplo:
 *  $hash = md5Password('123456');
 * -------------------------------------------------------------
 */
if (!function_exists('md5Password')) {
    function md5Password($password)
    {
        return md5($password);
    }
}
