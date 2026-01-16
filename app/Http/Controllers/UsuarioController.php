<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use Illuminate\Support\Facades\Auth;

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

        /** @var \App\Models\Usuario|null $usuario */
        $usuario = Auth::user();
        logAction($usuario->name ?? 'sistema', "Agregó usuario: " . $request->usuario);

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

        /** @var \App\Models\Usuario|null $usuarioAuth */
        $usuarioAuth = Auth::user();
        logAction($usuarioAuth->name ?? 'sistema', "Editó usuario ID: $id");

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
        $usuario = Usuario::findOrFail($id);
        $usuario->eliminarUsuario();

        /** @var \App\Models\Usuario|null $usuarioAuth */
        $usuarioAuth = Auth::user();
        logAction($usuarioAuth->name ?? 'sistema', "Eliminó usuario ID: $id");

        return redirect()->route('usuarios.index')->with('success', 'Usuario eliminado correctamente.');
    }
}
