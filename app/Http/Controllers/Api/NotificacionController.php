<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notificacion;

class NotificacionController extends Controller
{
    public function index(){
    
        //$notificaciones = Notificacion::with('user', 'user.media', 'media', 'likes')->withCount('likes','comentarios')->orderBy('created_at', 'desc')->get()->toArray();
        return $notificaciones;
    }

    public function store($destinatario,$id,$interaccion, Request $request) {
        $userId = auth()->id();
        Notificacion::create([
            'id_remitente' => $userId,
            'id_destinatario' => $destinatario,
            'id_contenido' => $id,
            'tipo_interaccion' => $interaccion, // 0 = Like, 1 = Comentario, 2 = Nuevo seguidor.
        ]);
        return response()->json(['notificacion' => true]);
    }
 }

