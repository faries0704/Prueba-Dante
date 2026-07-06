<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        $profile = null;

        // Detectar perfil dinámicamente según rol
        if ($user->idrole == 2) { // TEACHER
            $profile = DB::table('teachers')->where('iduser', $user->iduser)->first();
        } elseif ($user->idrole == 3) { // STUDENT
            $profile = DB::table('students')->where('iduser', $user->iduser)->first();
        } elseif ($user->idrole == 4) { // FATHER
            $profile = DB::table('fathers')->where('iduser', $user->iduser)->first();
        }

        return view('profile.edit', compact('user', 'profile'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        // Validaciones comunes
        $request->validate([
            'email' => 'required|email|max:100|unique:users,email,' . $user->iduser . ',iduser',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Procesamiento de Imagen de Perfil
        $photoName = $user->photo;
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $photoName = time() . '_' . Str::slug($user->username) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('backend/img/subidas'), $photoName);
        }

        // 1. Actualizar credenciales de usuario
        DB::table('users')->where('iduser', $user->iduser)->update([
            'email' => $request->email,
            'photo' => $photoName,
            'updated_at' => now()
        ]);

        // 2. Actualizar datos específicos según rol
        if ($user->idrole == 2) { // Teacher
            DB::table('teachers')->where('iduser', $user->iduser)->update([
                'full_name' => $request->full_name,
                'phone' => $request->phone,
            ]);
        } elseif ($user->idrole == 3) { // Student
            DB::table('students')->where('iduser', $user->iduser)->update([
                'full_name' => $request->full_name,
                'address' => $request->address,
            ]);
        } elseif ($user->idrole == 4) { // Father
            DB::table('fathers')->where('iduser', $user->iduser)->update([
                'full_name' => $request->full_name,
                'phone' => $request->phone,
                'address' => $request->address,
                'profession' => $request->profession,
            ]);
        }

        return redirect()->back()->with('actualizado', 'OK');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:6|confirmed'
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()->withErrors(['current_password' => 'La contraseña actual no coincide.']);
        }

        DB::table('users')->where('iduser', $user->iduser)->update([
            'password' => Hash::make($request->password),
            'updated_at' => now()
        ]);

        return redirect()->back()->with('actualizado', 'OK');
    }
}