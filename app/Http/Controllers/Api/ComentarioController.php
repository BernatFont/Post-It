<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Comentario;

class ComentarioController extends Controller
{
    //
    public function index()
    {
        $comentarios = Comentario::all()->toArray();
        return $comentarios;
    }

    public function store($id, Request $request)
    {
        $request->validate([
            'contenido' => 'required'
        ]);

        // Obtener el ID del usuario autenticado
        $userId = auth()->id();

        // Crear el comentario
        $comentario = Comentario::create([
            'id_publicacion' => $id,
            'id_usuario' => $userId,
            'contenido' => $request->contenido, 
        ]);

        // Devolver el comentario creado en la respuesta JSON
        return response()->json(['success' => true, 'data' => $comentario]);
    }
}
