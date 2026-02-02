<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('city_deliveries')) {
            Schema::create('city_deliveries', function (Blueprint $table) {
                $table->id();
                $table->integer('country_state_id');
                $table->string('name');
                $table->string('postcode')->nullable();
                $table->decimal('delivery_cost', 10, 2)->default(0);
                $table->boolean('is_active')->default(true);
                $table->timestamps();

                $table->foreign('country_state_id')
                      ->references('id')
                      ->on('country_states')
                      ->onDelete('cascade');
            });
        } else {
            Schema::table('city_deliveries', function (Blueprint $table) {
                if (!Schema::hasColumn('city_deliveries', 'name')) {
                    $table->string('name')->after('country_state_id');
                }

                if (!Schema::hasColumn('city_deliveries', 'postcode')) {
                    $table->string('postcode')->nullable()->after('name');
                }
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('city_deliveries');
    }
};
