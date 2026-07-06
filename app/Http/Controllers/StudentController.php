<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{

    public function dashboard()
    {
        return view('students.dashboard');
    }
    public function index()
    {
        // Obtenemos los alumnos cargando su relación 'user'
        $students = Student::with('user')->orderBy('idstudent', 'DESC')->get();
        return view('students.index', compact('students'));
    }

    public function complete($iduser)
    {
        $user = User::findOrFail($iduser);
        return view('students.complete', compact('user'));
    }


    public function storeComplete(Request $request)
    {
        $request->validate([
            'iduser'     => 'required|exists:users,iduser',
            'dni'        => 'required|unique:students,dni|digits:8',
            'full_name'  => 'required|string|max:100',
            'gender'     => 'required|in:M,F',
            'birth_date' => 'required|date',
            'address'    => 'nullable|string|max:255',
        ]);

        Student::create($request->all());

        return redirect()->route('users.index')->with('perfil_completado', 'OK');
    }

    public function create()
    {
        return view('students.create');
    }

    // Guardar Usuario + Estudiante
    public function store(Request $request)
    {
        // 1. Validación combinada
        $request->validate([
            // Datos de Acceso
            'username'   => 'required|unique:users,username',
            'email'      => 'required|email|unique:users,email',
            'password'   => 'required|min:6',
            'photo'      => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            
            // Datos del Estudiante
            'dni'        => 'required|unique:students,dni|digits:8',
            'full_name'  => 'required|string|max:150',
            'gender'     => 'required|string|in:M,F', // Validamos que sea M o F
            'birth_date' => 'required|date',
            'address'    => 'required|string|max:255',
        ]);

        try {
            DB::beginTransaction();


            $role = Role::where('role_name', 'STUDENT')->first();

            if (!$role) {
                throw new \Exception("El rol 'ESTUDIANTE' no existe en la base de datos.");
            }

            // 3. Procesar Foto
            $filename = null;
            if ($request->hasFile('photo')) {
                $destinationPath = public_path('backend/img/subidas');
                $filename = time() . '_' . $request->file('photo')->getClientOriginalName();
                $request->file('photo')->move($destinationPath, $filename);
            }

            // 4. Crear Usuario
            $user = User::create([
                'username' => $request->username,
                'email'    => $request->email,
                'password' => Hash::make($request->password), 
                'idrole'   => $role->idrole,
                'photo'    => $filename,
                'status'   => 1, 
            ]);

            // 5. Crear Perfil de Estudiante
            Student::create([
                'iduser'     => $user->iduser,
                'dni'        => $request->dni,
                'full_name'  => $request->full_name,
                'gender'     => $request->gender,
                'birth_date' => $request->birth_date,
                'address'    => $request->address,
            ]);

            DB::commit();
            
            // Redirigir a la vista principal de estudiantes con mensaje de éxito
            return redirect()->route('students.index')->with('creado', 'OK');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->withErrors(['error' => 'Error: ' . $e->getMessage()]);
        }
    }

    // Actualizar 

    public function edit($id)
    {
        $student = Student::with('user')->findOrFail($id);
        return view('students.edit', compact('student'));
    }

    public function update(Request $request, $id)
    {
        $student = Student::findOrFail($id);
        
        $request->validate([
            'dni'        => 'required|unique:students,dni,' . $id . ',idstudent|digits:8',
            'full_name'  => 'required|string|max:100',
            'gender'     => 'required',
            'birth_date' => 'required|date',
            'address'    => 'required|string|max:255',
            'email'      => 'required|email|unique:users,email,' . $student->iduser . ',iduser',
            'status'     => 'required|in:0,1'
        ]);

        $student->update([
            'dni'        => $request->dni,
            'full_name'  => $request->full_name,
            'gender'     => $request->gender,
            'birth_date' => $request->birth_date,
            'address'    => $request->address,
        ]);

        $student->user->update([
            'email'  => $request->email,
            'status' => $request->status
        ]);

        return redirect()->route('students.index')->with('update_success', 'OK');
    }

    public function show($id)
    {
        $student = Student::with('user')->findOrFail($id);
        return view('students.info', compact('student'));
    }

    public function delete($id)
{
    $student = Student::findOrFail($id);
    return view('students.delete', compact('student'));
}

    public function destroy(Request $request, $id)
    {
        $student = Student::findOrFail($id);

        if ($student->user) {
            $student->user->update([
                'status' => '0' 
            ]);
        }

        return redirect()->route('students.index')->with('delete_success', 'OK');
    }


    public function editPhoto($id)
    {
        $student = Student::findOrFail($id);
        
        $user = $student->user; 

        return view('students.photo', compact('user', 'student'));
    }

    public function updatePhoto(Request $request, $id)
    {
        $student = Student::findOrFail($id);
        $user = $student->user;

        $request->validate([
            'foto' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            $oldPath = public_path('backend/img/subidas/' . $user->photo);
            if ($user->photo && File::exists($oldPath)) {
                File::delete($oldPath);
            }

            // Procesar nueva foto
            $image = $request->file('foto');
            $name = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('backend/img/subidas'), $name);

            // Guardar en la tabla USERS
            $user->photo = $name;
            $user->save();
        }

        return redirect()->route('students.index')->with('photo_success', 'OK');
    }
    // Mostrar el formulario para actualizar la contraseña
    public function password($id)
    {
        $student = Student::findOrFail($id);
        return view('students.password', compact('student'));
    }

    // Procesar la actualización de la contraseña
    public function updatePassword(Request $request, $id)
    {
        $student = Student::findOrFail($id);
        $user = $student->user; // Accedemos a la relación con el usuario

        // Validamos la nueva contraseña
        $request->validate([
            'password' => 'required|min:6',
        ]);

        // Actualizamos la contraseña en la tabla users
        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return redirect()->route('students.index')->with('password_success', 'OK');
    }
}