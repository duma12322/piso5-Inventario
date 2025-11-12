<?php

namespace App\Http\Controllers;

use App\Models\Log;
use Illuminate\Http\Request;

class LogController extends Controller
{
    public function index()
    {
        // Cargar logs con relación al usuario y ordenados por fecha
        $logs = Log::with('usuario')->orderBy('fecha', 'desc')->get();
        return view('logs.index', compact('logs'));
    }
}
