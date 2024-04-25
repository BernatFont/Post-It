<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Chat;
use App\Models\User;
use App\Models\Media;


class ChatController extends Controller
{
    public function index()
    {
        $userLogeado = auth()->id();
        $chats = Chat::with(['user1', 'user2', 'user1.media', 'user2.media'])->where('user_id_1', $userLogeado)->orWhere('user_id_2', $userLogeado)->get();
    
        return $chats;
    }

    public function store($id, Request $request)
    {
        $userLogeado = auth()->id();
        $chat1 = Chat::where('user_id_1', $userLogeado)->where('user_id_2', $id)->first();
        $chat2 = Chat::where('user_id_1', $id)->where('user_id_2', $userLogeado)->first();
    
        // Si el chat ya existe en alguna dirección, devuelve false
        if ($chat1 || $chat2) {
            return response()->json(['chat' => false]);
        } else {
            // Si el chat no existe, crea el chat y devuelve true
            Chat::create([
                'user_id_1' => $userLogeado,
                'user_id_2' => $id
            ]);
            return response()->json(['chat' => false]);
        }
    }

    public function obtenerChat($id, Request $request) {
        $userLogeado = auth()->id();
        $chat = Chat::find($id);
    
        // Verifica si el chat existe
        if (!$chat) {
            return response()->json(['error' => 'Chat no encontrado'], 404);
        } else {
            $chat = Chat::with(['user1', 'user2', 'user1.media', 'user2.media'])->where('user_id_1', $userLogeado)->orWhere('user_id_2', $userLogeado)->get();
        }
        // Devuelve solo el chat encontrado por su ID
        return response()->json($chat);
    }

}
