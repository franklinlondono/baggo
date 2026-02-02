<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('city_deliveries', function (Blueprint $table) {
            if (Schema::hasColumn('city_deliveries', 'country_state_id')) {
                $table->unsignedInteger('country_state_id')->change();

                $table->foreign('country_state_id')
                ->references('id')
                ->on('country_states')
                ->onDelete('cascade');

            }
        });
    }

    public function down(): void
    {
        Schema::table('city_deliveries', function (Blueprint $table) {
            $table->dropForeign(['country_state_id']);
        });
    }
};
