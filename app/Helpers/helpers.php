<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Log as LogModel;

if (!function_exists('checkLogin')) {
    function checkLogin()
    {
        if (!Auth::check()) {
            abort(403, 'No autorizado');
        }
    }
}

if (!function_exists('logAction')) {
    function logAction($usuario, $accion)
    {
        if (class_exists(LogModel::class)) {
            LogModel::create([
                'usuario' => $usuario ?? 'sistema',
                'accion'  => $accion
            ]);
        }

        Log::info($accion, ['usuario' => $usuario ?? 'sistema']);
    }
}
if (!function_exists('md5Password')) {
    function md5Password($password)
    {
        return md5($password);
    }
}
