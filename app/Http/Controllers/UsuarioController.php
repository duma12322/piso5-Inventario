<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use Illuminate\Support\Facades\Auth;
// Importa el modelo LogModel para guardar acciones de los usuarios en la base de datos
use App\Models\Log as LogModel;

/**
 * Controlador para manejar los usuarios del sistema.
 *
 * Proporciona funcionalidades CRUD (Crear, Leer, Actualizar, Eliminar)
 * para los usuarios, incluyendo:
 * - Listado de usuarios con búsqueda y paginación.
 * - Creación y edición de usuarios.
 * - Eliminación de usuarios.
 * - Validación de contraseña actual al actualizar.
 * - Registro de acciones en logs para auditoría.
 */

class UsuarioController extends Controller
{
    /**
     * Listar todos los usuarios con paginación y búsqueda opcional.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = Usuario::query();

        if ($search = $request->input('search')) {
            $query->where('usuario', 'like', "%{$search}%")
                ->orWhere('rol', 'like', "%{$search}%");
        }

        $usuarios = $query->orderBy('usuario')->paginate(10)->withQueryString();

        return view('usuarios.index', compact('usuarios'));
    }

    /**
     * Mostrar formulario para crear un nuevo usuario.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('usuarios.create');
    }

    /**
     * Guardar un nuevo usuario en la base de datos.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'usuario' => 'required|string|max:255|unique:usuarios,usuario',
            'password' => 'required|string|min:4',
            'rol' => 'required|string|max:50',
        ]);

        $usuarioNuevo = Usuario::crearUsuario([
            'usuario' => $request->usuario,
            'password' => $request->password, // se convierte a MD5 en el modelo
            'rol' => $request->rol,
        ]);

        $usuarioLog = Auth::check() ? Auth::user()->usuario : 'Sistema';

        try {
            LogModel::create([
                'usuario' => $usuarioLog, // quien hizo la acción
                'accion' => 'Agregó el usuario: ' . $usuarioNuevo->usuario, // usuario recién creado
                'fecha' => now()
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error(
                'Error guardando log: ' . $e->getMessage()
            );
        }

        return redirect()->route('usuarios.index')->with('success', 'Usuario agregado correctamente.');
    }

    /**
     * Mostrar formulario para editar un usuario existente.
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $usuario = Usuario::findOrFail($id);
        return view('usuarios.edit', compact('usuario'));
    }

    /**
     * Actualizar los datos de un usuario existente.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $usuario = Usuario::findOrFail($id);

        $request->validate([
            'usuario' => 'required|string|max:255|unique:usuarios,usuario,' . $id . ',id_usuario',
            'rol' => 'required|string|max:50',
            'password_actual' => 'required|string', // contraseña actual obligatoria
            'password' => 'nullable|string|min:4',
        ]);

        // Obtener la contraseña real desde la base de datos
        $passwordReal = $usuario->getRawOriginal('password');

        // Validar la contraseña actual con MD5
        if (md5($request->password_actual) !== $passwordReal) {
            return back()->withErrors(['password_actual' => 'La contraseña actual no es correcta.'])->withInput();
        }

        // Si llega aquí, la contraseña actual es válida
        $usuario->actualizarUsuario([
            'usuario' => $request->usuario,
            'password' => $request->password,
            'rol' => $request->rol,
        ]);

        $usuarioLog = Auth::check() ? Auth::user()->usuario : 'Sistema';

        try {
            LogModel::create([
                'usuario' => $usuarioLog,
                'accion' => 'Actualizó el usuario: ' . $usuario->usuario,
                'fecha' => now()
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error(
                'Error guardando log: ' . $e->getMessage()
            );
        }

        return redirect()->route('usuarios.index')->with('success', 'Usuario actualizado correctamente.');
    }


    /**
     * Eliminar un usuario del sistema.
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        // Obtener el usuario a eliminar
        $usuario = Usuario::findOrFail($id);

        // Guardar el nombre antes de eliminar
        $usuarioNombre = $usuario->usuario;

        // Eliminar usuario
        $usuario->eliminarUsuario();

        // Usuario que realizó la acción
        $usuarioLog = Auth::check() ? Auth::user()->usuario : 'Sistema';

        // Guardar log
        try {
            LogModel::create([
                'usuario' => $usuarioLog,
                'accion' => 'Eliminó el usuario: ' . $usuarioNombre,
                'fecha' => now()
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error(
                'Error guardando log: ' . $e->getMessage()
            );
        }

        return redirect()->route('usuarios.index')->with('success', 'Usuario eliminado correctamente.');
    }
}
