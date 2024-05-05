<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Bloqueado;

class BloqueadoController extends Controller
{
    public function store($id, Request $request) {
        $userId = auth()->id();
        $bloqueado = Bloqueado::where('id_usuario_bloqueado', $id)->where('id_usuario', $userId)->first();
    
        // Si el ya lo habia bloqueado, se desbloquea
        if ($bloqueado) {
            $bloqueado->delete();
            return response()->json(['bloqueado' => false]);
        } else {
            // Si no esta bloqueado, se crea
            Bloqueado::create([
                'id_usuario' => $userId,
                'id_usuario_bloqueado' => $id
            ]);
            return response()->json(['bloqueado' => true]);
        }
    }
}
