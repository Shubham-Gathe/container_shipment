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
        Schema::table('drivers', function (Blueprint $table) {
            $table->string('vehicle_type')->nullable();
            $table->string('license_plate')->nullable();
            $table->string('status')->default('active'); // 'active', 'inactive', 'suspended'
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Assuming a driver belongs to a user
            $table->string('vehilce_number')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('drivers', function (Blueprint $table) {
            //
        });
    }
};
