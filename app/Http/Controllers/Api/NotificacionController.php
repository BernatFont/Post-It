<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notificacion;
use App\Models\Publicacion;
use App\Models\User;
use App\Models\Comentario;

class NotificacionController extends Controller
{
    public function index(){
        // Obtener las notificaciones para el usuario autenticado
        $userId = auth()->id();
        $notificaciones = Notificacion::with('publicacion', 'user1', 'user2', 'user1.media','user2.media', 'comentario', 'comentario.publicacion')->where('id_destinatario', $userId)->orderBy('created_at', 'desc')->get();

        return response()->json($notificaciones);
    }

    public function store(Request $request) {
        // Verificar si ya existe una notificación exactamente igual en la base de datos
        $existeNotificacion = Notificacion::where('id_remitente', $request->remitente)
            ->where('id_destinatario', $request->destinatario)
            ->where('id_contenido', $request->id)
            ->where('tipo_interaccion', $request->interaccion)
            ->exists();
    
        // Si la notificación ya existe no se creara una nueva
        if ($existeNotificacion) {
            return response()->json(['mensaje' => 'La notificación ya existe'], 200);
        }
    
        // Si no existe, se crea una nueva notificación
        Notificacion::create([
            'id_remitente' => $request->remitente,
            'id_destinatario' => $request->destinatario,
            'id_contenido' => $request->id,
            'tipo_interaccion' => $request->interaccion, // 0 = Like, 1 = Comentario, 2 = Nuevo seguidor.
        ]);
    
        return response()->json(['mensaje' => 'Notificación creada correctamente'], 201);
    }

    public function destroy($id) 
    {
        $notificacion = Notificacion::find($id);
        // Verifica si la notificacion existe
        if (!$notificacion) {
            return response()->json(['error' => 'Notificacion no encontrada'], 404);
        } else {
            $notificacion->delete();
        }
        
        return response()->noContent();
    }

    // Funcion para eliminar todas las publicaciones de un usuario
    public function eliminarNotificacionesUsuario($idDestinatario) 
    {
        // Busca todas las notificaciones del usuario con el id_destinatario especificado
        $notificaciones = Notificacion::where('id_destinatario', $idDestinatario)->get();
        
        // Verifica si hay notificaciones para el usuario
        if ($notificaciones->isEmpty()) {
            return response()->json(['mensaje' => 'El usuario no tiene notificaciones'], 200);
        }
        
        // Elimina todas las notificaciones encontradas
        foreach ($notificaciones as $notificacion) {
            $notificacion->delete();
        }
        
        return response()->json(['mensaje' => 'Eliminadas todas las notificaciones del usuario'], 200);
    }

    public function numNotificaciones(){
        // Obtener el recuento de notificaciones para el usuario autenticado
        $userId = auth()->id();
        $numNotificaciones = Notificacion::where('id_destinatario', $userId)->count();
    
        return response()->json(['numNotificaciones' => $numNotificaciones]);
    }
 }

