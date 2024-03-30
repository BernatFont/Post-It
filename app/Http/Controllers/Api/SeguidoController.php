<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Seguido;

class SeguidoController extends Controller
{
    public function store($id, Request $request)
    {
        $userId = auth()->id();
        $seguido = Seguido::where('id_usuario_seguido', $id)->where('id_usuario', $userId)->first();
    
        // Si el usuario actual ya sigue a ese usuario, dejara de seguirlo
        if ($seguido) {
            $seguido->delete();
            return response()->json(['seguido' => false]);
        } else {
            // Si no lo sigue, se crea la relacion para que sea seguido
            Seguido::create([
                'id_usuario' => $userId,
                'id_usuario_seguido' => $id
            ]);
            return response()->json(['seguido' => true]);
        }
    }

}
