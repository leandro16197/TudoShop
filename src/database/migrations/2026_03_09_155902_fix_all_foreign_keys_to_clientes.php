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
        // 1. Corregir tabla pedidos
        Schema::table('pedidos', function (Blueprint $table) {
            $table->dropForeign(['user_id']); // Elimina la restricción vieja
            $table->foreign('user_id')->references('id')->on('clientes')->onDelete('cascade');
        });

        // 2. Corregir tabla pagos
        Schema::table('pagos', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->foreign('user_id')->references('id')->on('clientes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clientes', function (Blueprint $table) {
            //
        });
    }
};
