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
        Schema::table('city_deliveries', function (Blueprint $table) {
            // Agrega la columna 'deleted_at' (TIMESTAMPTZ nullable)
            $table->softDeletes(); 
        });
    }

    /**
     * Reverse the migrations (deshace el cambio).
     */
    public function down(): void
    {
        Schema::table('city_deliveries', function (Blueprint $table) {
            // Elimina la columna 'deleted_at'
            $table->dropSoftDeletes(); 
        });
    }
};