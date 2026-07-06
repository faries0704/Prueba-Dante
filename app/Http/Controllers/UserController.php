<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Student;
use App\Models\User;
use Faker\Core\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
            $users = User::with(['role', 'student', 'teacher', 'father'])
                ->orderBy('iduser', 'DESC')
                ->get();

            return view('users.index', compact('users'));
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $roles = Role::all(); 
        return view('users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        // 1. Añadimos la validación 'nullable' para la contraseña
        $request->validate([
            'username' => 'required|unique:users,username,'.$id.',iduser',
            'email'    => 'required|email|unique:users,email,'.$id.',iduser',
            'idrole'   => 'required',
            'status'   => 'required',
            'password' => 'nullable|min:6' // Opcional, pero si se escribe, mínimo 6 caracteres
        ]);
        
        // 2. Preparamos los datos básicos a actualizar
        $data = [
            'username' => $request->username,
            'email'    => $request->email,
            'idrole'   => $request->idrole,
            'status'   => $request->status,
        ];

        // 3. Verificamos si se envió una nueva contraseña
        // Si el campo no está vacío, lo encriptamos y lo agregamos al array de actualización
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }
        
        // 4. Lógica de desbloqueo (la mantenemos igual)
        if ($request->has('unlock_user')) {
            $data['login_attempts'] = 0;
            $data['locked_until'] = null;
        }

        // 5. Actualizamos el modelo usando el array procesado
        $user->update($data);

        return redirect()->route('users.index')->with('actualizado', 'OK');
    }


    public function destroy($id)
    {
        $user = User::findOrFail($id);
        
        $user->update(['status' => 0]);

        return redirect()->route('users.index')->with('desactivado', 'OK');
    }

    public function confirm($id) {
        $user = User::findOrFail($id);
        return view('users.delete', compact('user'));
    }



    public function editPhoto($id)
    {
        $user = User::findOrFail($id);
        return view('users.photo', compact('user'));
    }

    public function updatePhoto(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('photo')) {

            $destinationPath = public_path('backend/img/subidas');

           

            $file = $request->file('photo');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move($destinationPath, $filename);

            $user->update(['photo' => $filename]);
        }

        return redirect()->route('users.index')->with('foto_actualizada', 'OK');
    }



    public function create()
    {
        $roles = Role::all(); 
        return view('users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        // 1. Validamos los campos, incluyendo la foto
        $request->validate([
            'username' => 'required|unique:users,username',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'idrole'   => 'required',
            'photo'    => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Validación de foto
        ]);

        $filename = null;

        // 2. Verificamos y subimos la imagen si existe
        if ($request->hasFile('photo')) {
            $destinationPath = public_path('backend/img/subidas');
            $file = $request->file('photo');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move($destinationPath, $filename);
        }

        // 3. Guardamos en la base de datos
        User::create([
            'username' => $request->username,
            'email'    => $request->email,
            'password' => Hash::make($request->password), 
            'idrole'   => $request->idrole,
            'photo'    => $filename, // Guardamos el nombre de la foto generada
            'status'   => 1, 
        ]);

        return redirect()->route('users.index')->with('creado', 'OK');
    }
}