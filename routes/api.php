<?php

use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\PermissionController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\ExerciseController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\PublicacionController;
use App\Http\Controllers\Api\LikeController;
use App\Http\Controllers\Api\ComentarioController;
use App\Http\Controllers\Api\SeguidoController;
use App\Http\Controllers\Api\ChatController;

use App\Http\Controllers\Auth\ResetPasswordController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\ForgotPasswordController;

Route::post('forget-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('forget.password.post');
Route::post('reset-password', [ResetPasswordController::class, 'reset'])->name('password.reset');

Route::get('publicacions', [PublicacionController::class, 'index']); // Listar
Route::post('publicacions/',[PublicacionController::class,'store']); // Guardar
Route::get('publicacions/{id}',[PublicacionController::class, 'mostrarPublicacion']); // Mostrar una publicacion
Route::post('get-publicacions',[PublicacionController::class,'getPosts']);
Route::put('publicacions/update/{id}', [PublicacionController::class,'update']); //Editar
Route::delete('publicacions/delete/{id}', [PublicacionController::class, 'destroy']); // Eliminar

Route::post('/like/add/{id}', [LikeController::class, 'store']);

Route::get('comentarios', [ComentarioController::class, 'index']);
Route::post('/comentario/add/{id}', [ComentarioController::class, 'store']);
Route::delete('comentario/delete/{id}', [ComentarioController::class, 'destroy']); // Eliminar

Route::get('/usuario/{username}', [UserController::class, 'obtenerUsuario']);
Route::get('/chats', [ChatController::class, 'index']);
Route::post('/follow/{id}', [SeguidoController::class, 'store']); // Seguir usuario
Route::post('/chat/{id}', [ChatController::class, 'store']); // Crear chat
Route::get('/chat/mensajes/{id}', [MensajeController::class, 'index']); // Obtener mensajes de un chat

Route::group(['middleware' => 'auth:sanctum'], function() {
    Route::apiResource('users', UserController::class);
    Route::apiResource('posts', PostController::class);
    Route::apiResource('categories', CategoryController::class);
    Route::apiResource('roles', RoleController::class);
    //Route::apiResource('exercises', ExerciseController::class);
    Route::post('exercises/', [ExerciseController::class,'store']); //Guardar
    Route::get('exercises', [ExerciseController::class,'index']); //Listar
    Route::get('exercises/{exercise}', [ExerciseController::class,'show']); //Mostrar
    Route::post('exercises/update/{id}', [ExerciseController::class,'update']); //Editar

    Route::get('role-list', [RoleController::class, 'getList']);
    Route::get('role-permissions/{id}', [PermissionController::class, 'getRolePermissions']);
    Route::put('/role-permissions', [PermissionController::class, 'updateRolePermissions']);
    Route::apiResource('permissions', PermissionController::class);
    Route::get('category-list', [CategoryController::class, 'getList']);
    Route::get('/user', [ProfileController::class, 'user']);
    Route::put('/user', [ProfileController::class, 'update']);

    Route::get('abilities', function(Request $request) {
        return $request->user()->roles()->with('permissions')
            ->get()
            ->pluck('permissions')
            ->flatten()
            ->pluck('name')
            ->unique()
            ->values()
            ->toArray();
    });
});


Route::get('category-list', [CategoryController::class, 'getList']);
Route::get('get-posts', [PostController::class, 'getPosts']);
Route::get('get-category-posts/{id}', [PostController::class, 'getCategoryByPosts']);
Route::get('get-post/{id}', [PostController::class, 'getPost']);
