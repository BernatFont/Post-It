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
        $notificaciones = Notificacion::with(['publicacion'])->where('id_destinatario', $userId)->orderBy('created_at', 'desc')->get();

        return response()->json(['notificaciones' => $notificaciones]);
    }

    public function store(Request $request) {
        Notificacion::create([
            'id_remitente' => $request->remitente,
            'id_destinatario' => $request->destinatario,
            'id_contenido' => $request->id,
            'tipo_interaccion' => $request->interaccion, // 0 = Like, 1 = Comentario, 2 = Nuevo seguidor.
        ]);
        return response()->json(['notificacion' => true]);
    }
 }

