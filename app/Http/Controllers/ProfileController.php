<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use App\Models\SimulacionBono;
use App\Models\ComisionExcel;
use App\Models\RegistroBono; // 👈 importante

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user()->load('agente');
        $email = strtolower(trim($user->email));

        // Actividad: todas las simulaciones del usuario
        $simulaciones = SimulacionBono::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        // Consulta (1): filas importadas desde Excel (si las usas)
        $filas = ComisionExcel::where('email', $email)
            ->orderBy('created_at', 'desc')
            ->get();

        // Consulta (2): bono real desde la tabla registro_bono
        $bonos = RegistroBono::where('email', $email)
            ->orderBy('id', 'desc')
            ->get();

        // Vista única con pestañas (profile.blade.php)
        return view('profile', compact('user', 'simulaciones', 'filas', 'bonos'));
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        $user = Auth::user();
        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->route('perfil')->with('status', 'Contraseña actualizada correctamente.');
    }

    // Opcional: si tienes rutas a /perfil/consulta, /perfil/simulacion, /perfil/actividad
    // y quieres seguir usando la MISMA vista "profile" con pestañas:
    public function consulta()
    {
        $user = Auth::user()->load('agente');
        $email = strtolower(trim($user->email));

        $simulaciones = SimulacionBono::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $filas = ComisionExcel::where('email', $email)
            ->orderBy('created_at', 'desc')
            ->get();

        $bonos = RegistroBono::where('email', $email)
            ->orderBy('id', 'desc')
            ->get();

        return view('profile', compact('user', 'simulaciones', 'filas', 'bonos'));
        // Si prefieres vista separada:
        // return view('perfil.consulta', compact('user', 'bonos'));
    }

    public function simulacion()
    {
        $user = Auth::user()->load('agente');
        $email = strtolower(trim($user->email));

        $simulaciones = SimulacionBono::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $filas = ComisionExcel::where('email', $email)
            ->orderBy('created_at', 'desc')
            ->get();

        $bonos = RegistroBono::where('email', $email)
            ->orderBy('id', 'desc')
            ->get();

        return view('profile', compact('user', 'simulaciones', 'filas', 'bonos'));
        // o return view('perfil.simulacion', compact('user'));
    }

    public function configuracion()
    {
        $user = Auth::user()->load('agente');
        return view('perfil.configuracion', compact('user'));
    }

    public function actividad()
    {
        $user = Auth::user()->load('agente');
        $email = strtolower(trim($user->email));

        $simulaciones = SimulacionBono::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $filas = ComisionExcel::where('email', $email)
            ->orderBy('created_at', 'desc')
            ->get();

        $bonos = RegistroBono::where('email', $email)
            ->orderBy('id', 'desc')
            ->get();

        return view('profile', compact('user', 'simulaciones', 'filas', 'bonos'));
        // o return view('perfil.actividad', compact('user','simulaciones'));
    }
}
