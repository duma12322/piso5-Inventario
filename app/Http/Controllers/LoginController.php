<?php

namespace App\Http\Controllers;

// Facade para autenticación de usuario
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

// Modelo Usuario
use App\Models\Usuario;

use Illuminate\Http\Request;

class LoginController extends Controller
{
    /**
     * Muestra el formulario de login.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('login.index');
    }

    /**
     * Autentica al usuario usando credenciales enviadas desde el formulario.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function autenticar(Request $request)
    {
        // Validación de los campos enviados
        $credentials = $request->validate([
            'usuario' => 'required|string',
            'password' => 'required|string',
        ]);

        // Buscar el usuario por su nombre de usuario
        $usuario = Usuario::where('usuario', $credentials['usuario'])->first();

        // Verificar existencia y contraseña (md5 en este proyecto)
        if ($usuario && $usuario->password === md5($credentials['password'])) {
            // Autenticación manual usando Auth
            Auth::login($usuario);

            // Regenerar la sesión para prevenir ataques de fijación de sesión
            $request->session()->regenerate();

            // Redirigir al dashboard
            return redirect()->route('dashboard.index');
        }

        // Si falla la autenticación, regresar al formulario con error
        return back()->withErrors([
            'usuario' => 'Usuario o contraseña incorrectos',
        ])->onlyInput('usuario');
    }

    /**
     * Cierra la sesión del usuario autenticado.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        // Cerrar sesión
        Auth::logout();

        // Invalida la sesión actual para mayor seguridad
        $request->session()->invalidate();

        // Regenerar el token CSRF para prevenir ataques
        $request->session()->regenerateToken();

        // Redirigir al login
        return redirect('/login');
    }
}
