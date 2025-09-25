<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Agente;
use App\Models\Area;
use App\Models\BolsaArea;
use App\Models\BolsaMensual;

class AdminConfigController extends Controller
{
    public function dashboard()
    {
        return view('admin.dashboard', [
            'users' => User::all(),
            'agentes' => Agente::all(),
            'areas' => Area::all(),
            'bolsaAreas' => BolsaArea::all(),
            'bolsaMensual' => BolsaMensual::all(),
        ]);
    }

    public function index()
    {
        return view('admin.configuracion', [
            'users' => User::all(),
            'agentes' => Agente::all(),
            'areas' => Area::all(),
            'bolsaAreas' => BolsaArea::all(),
            'bolsaMensual' => BolsaMensual::all(),
        ]);
    }

    // Aquí podrías agregar update y destroy si vas a usarlos
    public function destroy($id)
    {
        User::destroy($id);
        return back()->with('status', 'Usuario eliminado');
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->update($request->all());
        return back()->with('status', 'Usuario actualizado');
    }
}
