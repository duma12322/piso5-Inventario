<?php

namespace App\Http\Controllers;
// Autenticar usuario
use Illuminate\Support\Facades\Hash;
use App\Models\Usuario;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    // Mostrar formulario de login
    public function index()
    {
        return view('login.index');
    }


    public function autenticar(Request $request)
    {
        $credentials = $request->validate([
            'usuario' => 'required|string',
            'password' => 'required|string',
        ]);

        $usuario = Usuario::where('usuario', $credentials['usuario'])->first();

        if ($usuario && $usuario->password === md5($credentials['password'])) {
            // Autenticar manualmente
            Auth::login($usuario);

            $request->session()->regenerate();

            return redirect()->route('dashboard.index');
        }

        return back()->withErrors([
            'usuario' => 'Usuario o contraseña incorrectos',
        ])->onlyInput('usuario');
    }


    // Cerrar sesión
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate(); // Invalida la sesión actual
        $request->session()->regenerateToken(); // Regenera el token CSRF
        return redirect('/login'); // Redirige al login
    }
}
