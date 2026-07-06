<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Carbon\Carbon;

class AuthController extends Controller
{
    public function index() {
        return view('auth.login');
    }
    public function store(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $user = User::where('username', $credentials['username'])->first();

        if ($user && $user->locked_until && Carbon::now()->lt($user->locked_until)) {
            return back()->withErrors([
                'username' => 'Cuenta bloqueada temporalmente. Contacte al administrador.'
            ]);
        }

        if (Auth::attempt($credentials, $request->filled('remember'))) {

            $request->session()->regenerate();

            $user->login_attempts = 0;
            $user->locked_until = null;
            $user->save();

            switch ($user->idrole) {
                case 1: return redirect()->intended('dashboard');
                case 2: return redirect()->route('teacher.dashboard');
                case 3: return redirect()->route('student.dashboard');
                case 4: return redirect()->route('father.dashboard');
                default:
                    Auth::logout();
                    return back()->withErrors(['username' => 'Rol no válido.']);
            }
        }

        if ($user) {
            $user->login_attempts += 1;

            if ($user->login_attempts >= 3) {
                $user->locked_until = Carbon::now()->addYear(); // bloqueo temporal
            }

            $user->save();
        }

        return back()->withErrors([
            'username' => 'Credenciales incorrectas.'
        ])->withInput();
    }

    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}