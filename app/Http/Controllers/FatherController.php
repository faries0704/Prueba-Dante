<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Father;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\DB;
class FatherController extends Controller
{
    public function dashboard()
    {
        return view('fathers.dashboard');
    }
    
    public function complete($iduser)
    {
        $user = User::findOrFail($iduser);
        return view('fathers.complete', compact('user'));
    }

    public function storeComplete(Request $request)
    {
        $request->validate([
            'iduser'      => 'required|exists:users,iduser',
            'dni'         => 'required|unique:fathers,dni|digits:8',
            'full_name'   => 'required|string|max:100',
            'occupation'  => 'nullable|string|max:100',
            'phone'       => 'required|string|max:15',
        ]);

        Father::create($request->all());

        return redirect()->route('users.index')->with('perfil_padre', 'OK');
    }

    public function create()
    {
        return view('fathers.create');
    }

    public function store(Request $request)
    {
        // 1. Validar los datos de entrada
        $request->validate([
            'username'   => 'required|unique:users,username',
            'email'      => 'required|email|unique:users,email',
            'password'   => 'required|min:6',
            'dni'        => 'required|digits:8|unique:fathers,dni',
            'full_name'  => 'required|string|max:255',
            'profession' => 'required|string|max:255',
            'phone'      => 'required|string|max:15',
            'address'    => 'required|string|max:255',
            'photo'      => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            // Iniciamos la transacción
            DB::beginTransaction();

            // 2. Manejar la subida de la imagen (si existe)
            $filename = null;
            if ($request->hasFile('photo')) {
                $file = $request->file('photo');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('backend/img/subidas/'), $filename);
            }

            // 3. Crear el Usuario
            $user = User::create([
                'username' => $request->username,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
                'idrole'   => 4, 
                'photo'    => $filename,
                'status'   => '1', 
            ]);

            // 4. Crear el Perfil de Father vinculado al Usuario
            Father::create([
                'iduser'     => $user->iduser,
                'dni'        => $request->dni,
                'full_name'  => $request->full_name,
                'profession' => $request->profession,
                'phone'      => $request->phone,
                'address'    => $request->address,
            ]);

            // Si todo salió bien, confirmamos los cambios en la DB
            DB::commit();

            return redirect()->route('fathers.index')->with('create_success', 'OK');

        } catch (\Exception $e) {
            // Si algo falla, deshacemos cualquier inserción a medias
            DB::rollBack();
            
            return back()->withErrors(['error' => 'Ocurrió un error al guardar el registro: ' . $e->getMessage()])->withInput();
        }
    }

    public function index()
    {
        $fathers = Father::with('user')->orderBy('idfather', 'DESC')->get();
        return view('fathers.index', compact('fathers'));
    }

    //Editar
    public function edit($id)
    {
        // Obtenemos el padre con su usuario relacionado
        $father = Father::with('user')->findOrFail($id);
        return view('fathers.edit', compact('father'));
    }

    public function update(Request $request, $id)
    {
        $father = Father::findOrFail($id);
        $user = $father->user;

        // Validación
        $request->validate([
            'dni' => 'required|max:8|unique:fathers,dni,' . $id . ',idfather',
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->iduser . ',iduser',
            'profession' => 'required',
            'phone' => 'required|max:9',
            'address' => 'required',
            'status' => 'required'
        ]);

        $father->update([
            'dni' => $request->dni,
            'full_name' => $request->full_name,
            'profession' => $request->profession,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        $user->update([
            'email' => $request->email,
            'status' => $request->status
        ]);

        return redirect()->route('fathers.index')->with('updatee_success', 'OK');
    }

    //Delete
    public function confirmDelete($id)
    {
        $father = Father::findOrFail($id);
        return view('fathers.delete', compact('father'));
    }

    public function destroy($id)
    {
        $father = Father::findOrFail($id);
        
        $father->user->update([
            'status' => '0'
        ]);

        return redirect()->route('fathers.index')->with('delete_successApo', 'OK');
    }

    //PHOTO
    public function editPhoto($id)
    {
        $father = Father::with('user')->findOrFail($id);
        return view('fathers.photo', compact('father'));
    }

    public function updatePhoto(Request $request, $id)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $father = Father::findOrFail($id);
        $user = $father->user;

        if ($request->hasFile('photo')) {
            // Eliminar foto anterior si existe y no es la default
            if ($user->photo && file_exists(public_path('backend/img/subidas/' . $user->photo))) {
                unlink(public_path('backend/img/subidas/' . $user->photo));
            }

            $file = $request->file('photo');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('backend/img/subidas/'), $filename);

            $user->update([
                'photo' => $filename
            ]);
        }

        return redirect()->route('fathers.index')->with('photo_success', 'OK');
    }


    //SHOW

    public function show($id)
    {
        // Obtenemos el padre con su usuario y rol relacionado
        $father = Father::with('user.role')->findOrFail($id);
        return view('fathers.show', compact('father'));
    }

    //Contrasena
    public function editPassword($id)
    {
        $father = Father::with('user')->findOrFail($id);
        return view('fathers.password', compact('father'));
    }

    public function updatePassword(Request $request, $id)
    {
        $request->validate([
            'password' => 'required|string|min:6',
        ]);

        $father = Father::findOrFail($id);
        
        // Actualizar la contraseña en la tabla USERS
        $father->user->update([
            'password' => Hash::make($request->password)
        ]);

        return redirect()->route('fathers.index')->with('password_success', 'OK');
    }

 
    public function showAddHijo($id)
    {
        $father = Father::findOrFail($id);

        $students = Student::with('user')
            ->whereDoesntHave('fathers', function ($query) use ($id) {
                $query->where('fathers.idfather', $id);
            })
            ->get();

        $myChildren = $father->students()->with('user')->get();

        return view(
            'fathers.add_hijo',
            compact('father', 'students', 'myChildren')
        );
    }

    public function storeHijo(Request $request)
    {
        $father = Father::findOrFail($request->idfather);
        
        $father->students()->syncWithoutDetaching([$request->idstudent]);

        return redirect()->back()->with('add_hijo_success', 'OK');
    }
}