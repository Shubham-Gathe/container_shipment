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
        Schema::table('delivery_containers', function (Blueprint $table) {

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('delivery_containers', function (Blueprint $table) {
            $table->dropForeign(['delivery_id']);

            // Optional: Revert to previous behavior if different    
            $table->foreign('delivery_id')->references('id')->on('deliveries');
        });
    }
};
