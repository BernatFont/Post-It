<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('notificacions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_remitente');
            $table->unsignedBigInteger('id_destinatario');
            $table->unsignedBigInteger('id_contenido');
            $table->foreign('id_remitente')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('id_destinatario')->references('id')->on('users')->onDelete('cascade');
            $table->string('tipo_interaccion');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notificacions');
    }
};
