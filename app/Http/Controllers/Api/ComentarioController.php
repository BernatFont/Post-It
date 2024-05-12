<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Comentario;
use App\Models\Notificacion;

class ComentarioController extends Controller
{
    // Funcion para obtener todos los comentarios de una publicacion.
    public function index()
    {
        $comentarios = Comentario::all()->toArray();
        return $comentarios;
    }

    // Esta funcion es utilizada para crear un nuevo comentario y almacenarlo.
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
    
    // Funcion que elimina un comentario en base a su id.
    public function destroy($id) 
    {
        $comentario = Comentario::find($id);
        // Verifica si el comentario existe
        if (!$comentario) {
            return response()->json(['error' => 'Comentario no encontrado'], 404);
        } else {
            $comentario->delete();
            Notificacion::where('id_contenido', $id)->delete(); // Elimina las notificaciones asociadas
        }
        
        return response()->noContent();
    }
}
