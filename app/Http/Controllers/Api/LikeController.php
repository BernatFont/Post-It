<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Publicacion;
use App\Models\Like;
use App\Models\User;

class LikeController extends Controller
{
    public function store($id, Request $request)
    {
        $userId = auth()->id();
        $like = Like::where('id_publicacion', $id)
                    ->where('id_usuario', $userId)
                    ->first();
    
        // Si el like existe, lo eliminamos
        if ($like) {
            $like->delete();
            return response()->json(['liked' => false]);
        } else {
            // Si no existe, lo creamos
            Like::create([
                'id_publicacion' => $id,
                'id_usuario' => $userId,
            ]);
            return response()->json(['liked' => true]);
        }
    }
}
