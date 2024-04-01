<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Chat;

class ChatController extends Controller
{
    public function index()
    {
        $chats = Chat::all()->toArray();
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
}
