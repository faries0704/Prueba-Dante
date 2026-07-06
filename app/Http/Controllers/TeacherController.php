<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class TeacherController extends Controller
{

    public function dashboard()
    {
        return view('teachers.dashboard');
    }

    public function complete($iduser)
    {
        $user = User::findOrFail($iduser);
        return view('teachers.complete', compact('user'));
    }

    public function storeComplete(Request $request)
    {
        $request->validate([
            'iduser'      => 'required|exists:users,iduser',
            'dni'         => 'required|unique:teachers,dni|digits:8',
            'full_name'   => 'required|string|max:100',
            'specialty'   => 'required|string|max:100', 
            'phone'       => 'nullable|string|max:15',
            'birth_date'  => 'required|date',
        ]);

        Teacher::create($request->all());

        return redirect()->route('users.index')->with('perfil_docente', 'OK');
    }

    public function create()
    {
        // Ya no necesitamos enviar $roles a la vista
        return view('teachers.create');
    }

    public function store(Request $request)
    {
        // 1. Validamos (Quitamos 'idrole' de la validación)
        $request->validate([
            'username'  => 'required|unique:users,username',
            'email'     => 'required|email|unique:users,email',
            'password'  => 'required|min:6',
            'photo'     => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'dni'       => 'required|unique:teachers,dni|digits:8',
            'full_name' => 'required|string|max:100',
            'gender'    => 'required|string', 
            'phone'     => 'nullable|string|max:15',
        ]);

        try {
            DB::beginTransaction();

            $role = Role::where('role_name', 'TEACHER')->first();

            if (!$role) {
                throw new \Exception("El rol 'DOCENTE' no existe en la base de datos.");
            }

            $filename = null;
            if ($request->hasFile('photo')) {
                $destinationPath = public_path('backend/img/subidas');
                $filename = time() . '_' . $request->file('photo')->getClientOriginalName();
                $request->file('photo')->move($destinationPath, $filename);
            }

            // 3. Crear Usuario asignando el idrole automáticamente
            $user = User::create([
                'username' => $request->username,
                'email'    => $request->email,
                'password' => Hash::make($request->password), 
                'idrole'   => $role->idrole, // <--- ASIGNACIÓN AUTOMÁTICA
                'photo'    => $filename,
                'status'   => 1, 
            ]);

            // 4. Crear Docente
            Teacher::create([
                'iduser'    => $user->iduser,
                'dni'       => $request->dni,
                'full_name' => $request->full_name,
                'gender'    => $request->gender,
                'phone'     => $request->phone,
            ]);

            DB::commit();
            return redirect()->route('teachers.index')->with('creado', 'OK');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->withErrors(['error' => 'Error: ' . $e->getMessage()]);
        }
    }

    
    public function index()
    {
        $teachers = Teacher::with('user')->orderBy('idteacher', 'DESC')->get();
        
        return view('teachers.index', compact('teachers'));
    }

    public function edit($id)
    {
        $teacher = Teacher::with('user')->findOrFail($id);
        return view('teachers.edit', compact('teacher'));
    }

    public function update(Request $request, $id)
    {
        $teacher = Teacher::findOrFail($id);
        $user = $teacher->user;

        $request->validate([
            'dni' => 'required|numeric|digits:8',
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->iduser . ',iduser',
            'phone' => 'required|numeric',
            'gender' => 'required',
            'status' => 'required'
        ]);

        $teacher->update([
            'dni' => $request->dni,
            'full_name' => $request->full_name,
            'gender' => $request->gender,
            'phone' => $request->phone,
        ]);

        $user->update([
            'email' => $request->email,
            'status' => $request->status,
        ]);

        return redirect()->route('teachers.index')->with('update_success', 'OK');
    }

    //Delete 
    public function delete($id)
    {
        $teacher = Teacher::findOrFail($id);
        return view('teachers.delete', compact('teacher'));
    }

    public function destroy($id)
    {
        $teacher = Teacher::findOrFail($id);
        $user = $teacher->user;

        if ($user) {
            $user->status = '0';
            $user->save();
        }

        return redirect()->route('teachers.index')->with('deletee_success', 'OK');
    }


    // Photo

    public function editPhoto($id)
    {

        $teacher = Teacher::with('user')->findOrFail($id);
        return view('teachers.photo', compact('teacher'));
    }

    public function updatePhoto(Request $request, $id)
    {
        $teacher = Teacher::findOrFail($id);
        $user = $teacher->user;

        $request->validate([
            'foto' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            $destinationPath = public_path('backend/img/subidas');
            $oldFilePath = $destinationPath . '/' . $user->photo;

            if ($user->photo && File::exists($oldFilePath)) {
                File::delete($oldFilePath);
            }

            $image = $request->file('foto');
            $filename = time() . '_' . $image->getClientOriginalName();
            $image->move($destinationPath, $filename);

            $user->photo = $filename;
            $user->save();
        }

        return redirect()->route('teachers.index')->with('update_success', 'Foto actualizada correctamente');
    }

    // SHOW
    public function show($id)
    {
        $teacher = Teacher::with('user')->findOrFail($id);
        return view('teachers.info', compact('teacher'));
    }

    // Mostrar la vista para actualizar la contraseña
    public function password($id)
    {
        $teacher = Teacher::findOrFail($id);
        return view('teachers.password', compact('teacher'));
    }

    // Procesar la nueva contraseña y guardarla en la tabla de usuarios
    public function updatePassword(Request $request, $id)
    {
        $teacher = Teacher::findOrFail($id);
        $user = $teacher->user; // Obtenemos el usuario relacionado al docente

        // Validación
        $request->validate([
            'password' => 'required|min:6',
        ]);

        // Encriptar y actualizar la contraseña
        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return redirect()->route('teachers.index')->with('password_success', 'OK');
    }
}